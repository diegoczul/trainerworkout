<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\BodyGroups;
use App\Models\Exercises;
use App\Models\Equipments;
use App\Models\Workouts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Libraries\Helper;

class AIWorkoutController extends Controller
{
    /**
     * Show AI workout creation form
     */
    public function createWorkoutWithAI(Request $request)
    {
        $userId = Auth::user()->id;
        $permissions = Helper::checkPremissions(Auth::user()->id, null);

        Event::dispatch('userNewWorkout', array(Auth::user()));

        Log::info('AI Workout Generation: User accessed questionnaire', [
            'user_id' => $userId,
            'user_email' => Auth::user()->email,
            'timestamp' => now()
        ]);

        return view("trainer.createWorkoutAI")
            ->with("permissions", $permissions)
            ->with("bodyGroups", BodyGroups::select("id", "name")->where("main", 1)->orderBy("name")->get())
            ->with("equipments", Equipments::select("id", "name")->orderBy("name")->get());
    }

    /**
     * Generate workout structure using AI
     */
    public function generateWorkout(Request $request)
    {
        try {
            $validated = $request->validate([
                'workout_name' => 'required|string|max:255',
                'body_groups' => 'required|array',
                'body_groups.*' => 'exists:bodygroups,id',
                'duration' => 'nullable|integer|min:10|max:180',
                'difficulty' => 'nullable|in:beginner,intermediate,advanced',
                'equipments' => 'nullable|array',
                'equipments.*' => 'exists:equipments,id',
                'goals' => 'nullable|string|max:500',
            ]);

            Log::info('AI Workout Generation: Started', [
                'user_id' => Auth::user()->id,
                'workout_name' => $validated['workout_name'],
                'body_groups' => $validated['body_groups'],
                'duration' => $validated['duration'] ?? 60,
                'difficulty' => $validated['difficulty'] ?? 'intermediate',
                'timestamp' => now()
            ]);

            // Get selected body groups
            $selectedBodyGroups = BodyGroups::whereIn('id', $validated['body_groups'])->get();

            if ($selectedBodyGroups->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid body groups selected'
                ], 400);
            }

            // Get available exercises
            $exercises = $this->getExercisesForBodyGroups($validated['body_groups'], $validated['equipments'] ?? []);

            if ($exercises->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No exercises found for the selected body groups and equipment'
                ], 400);
            }

            Log::info('AI Workout Generation: Exercises retrieved', [
                'user_id' => Auth::user()->id,
                'total_exercises' => $exercises->count(),
                'exercise_details' => $exercises->map(function($ex) {
                    return [
                        'id' => $ex->id,
                        'name' => $ex->name,
                        'bodygroup_id' => $ex->bodygroupId,
                        'equipment_id' => $ex->equipmentId
                    ];
                })->toArray(),
                'timestamp' => now()
            ]);

            // Build ChatGPT prompt
            $prompt = $this->buildChatGPTPrompt($selectedBodyGroups, $exercises, $validated);

            Log::info('AI Workout Generation: Full ChatGPT prompt', [
                'user_id' => Auth::user()->id,
                'full_prompt' => $prompt,
                'timestamp' => now()
            ]);

            // Send to ChatGPT
            $chatGPTResponse = $this->sendToChatGPT($prompt);

            if (!$chatGPTResponse) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate workout. Please try again.'
                ], 500);
            }

            Log::info('AI Workout Generation: Full ChatGPT response', [
                'user_id' => Auth::user()->id,
                'full_response' => $chatGPTResponse,
                'timestamp' => now()
            ]);

            // Parse and validate response
            $parsedData = $this->parseAIResponse($chatGPTResponse, $exercises);

            if (!$parsedData) {
                return redirect()->back()
                    ->withError('Invalid AI response format. Please try again.')
                    ->withInput();
            }

            $exerciseGroups = $parsedData['exerciseGroups'];
            $exerciseGroupRest = $parsedData['exerciseGroupRest'];

            // Create workout record
            $workout = new Workouts();
            $workout->name = $validated['workout_name'];
            $workout->description = "AI-generated workout for " . $selectedBodyGroups->pluck('name')->join(', ');
            $workout->sale = 0;
            $workout->availability = 'private';
            $workout->shares = 0;
            $workout->views = 0;
            $workout->timesPerformed = 0;
            $workout->userId = Auth::user()->id;
            $workout->authorId = Auth::user()->id;
            $workout->status = "Draft";
            $workout->version = Config::get("constants.version");
            
            // Store AI generation parameters for regeneration
            $workout->aiGenerationParams = json_encode([
                'body_groups' => $validated['body_groups'],
                'equipments' => $validated['equipments'] ?? [],
                'duration' => $validated['duration'] ?? 60,
                'difficulty' => $validated['difficulty'] ?? 'intermediate',
                'goals' => $validated['goals'] ?? ''
            ]);
            $workout->aiConversationId = uniqid('conv_', true); // Generate unique conversation ID
            
            // Save the AI-generated exercise groups structure
            $workout->exerciseGroup = json_encode($exerciseGroups);
            $workout->exerciseGroupRest = json_encode($exerciseGroupRest);
            $workout->exercises = json_encode([]);
            
            $workout->save();

            Log::info('AI Workout Generation: Workout created successfully', [
                'user_id' => Auth::user()->id,
                'workout_id' => $workout->id,
                'workout_name' => $workout->name,
                'timestamp' => now()
            ]);

            // Store workout ID in session for editor
            Session::put("workoutIdInProgress", $workout->id);
            Session::save();

            // Redirect to workout editor
            return redirect()->to(__('routes./Trainer/CreateWorkout/') . $workout->id)
                ->with('message', 'AI workout generated successfully! You can now customize it further.');

        } catch (\Exception $e) {
            Log::error('AI Workout Generation: Exception', [
                'user_id' => Auth::user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()
            ]);

            return redirect()->back()
                ->withError('An error occurred while generating the workout. Please try again.')
                ->withInput();
        }
    }

    /**
     * Regenerate workout using existing parameters
     */
    public function regenerateWorkout($workoutId)
    {
        try {
            $workout = Workouts::findOrFail($workoutId);

            // Verify ownership
            if ($workout->userId != Auth::user()->id && $workout->authorId != Auth::user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Get stored parameters
            $params = json_decode($workout->aiGenerationParams, true);
            
            if (!$params) {
                return response()->json([
                    'success' => false,
                    'message' => 'No AI generation parameters found for this workout'
                ], 400);
            }

            Log::info('AI Workout Regeneration: Started', [
                'user_id' => Auth::user()->id,
                'workout_id' => $workoutId,
                'params' => $params,
                'timestamp' => now()
            ]);

            // Get selected body groups
            $selectedBodyGroups = BodyGroups::whereIn('id', $params['body_groups'])->get();

            // Get available exercises
            $exercises = $this->getExercisesForBodyGroups($params['body_groups'], $params['equipments'] ?? []);

            if ($exercises->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No exercises found for regeneration'
                ], 400);
            }

            // Extract current exercise IDs from the existing workout
            $currentExerciseIds = [];
            $currentExerciseGroups = json_decode($workout->exerciseGroup, true);
            if ($currentExerciseGroups && is_array($currentExerciseGroups)) {
                foreach ($currentExerciseGroups as $group) {
                    if (isset($group['exerciseGroup']) && is_array($group['exerciseGroup'])) {
                        foreach ($group['exerciseGroup'] as $exercise) {
                            if (isset($exercise['exerciseId'])) {
                                $currentExerciseIds[] = $exercise['exerciseId'];
                            }
                        }
                    }
                }
            }

            // Build ChatGPT prompt with regeneration context
            $prompt = $this->buildChatGPTPrompt($selectedBodyGroups, $exercises, $params);
            
            if (!empty($currentExerciseIds)) {
                $currentIdsString = implode(', ', array_unique($currentExerciseIds));
                $prompt .= "\n\nIMPORTANT: The user wants a DIFFERENT workout. The previous workout used these exercise IDs: [{$currentIdsString}]. Please use DIFFERENT exercises (different IDs) as much as possible to create variety. Avoid repeating the same exercise IDs unless absolutely necessary.";
            } else {
                $prompt .= "\n\nIMPORTANT: The user didn't like the previous workout. Generate a DIFFERENT workout with different exercises or different exercise combinations, sets, and reps.";
            }

            // Send to ChatGPT
            $chatGPTResponse = $this->sendToChatGPT($prompt);

            if (!$chatGPTResponse) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate workout'
                ], 500);
            }

            // Parse and validate response
            $parsedData = $this->parseAIResponse($chatGPTResponse, $exercises);

            if (!$parsedData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid AI response format'
                ], 500);
            }

            $exerciseGroups = $parsedData['exerciseGroups'];
            $exerciseGroupRest = $parsedData['exerciseGroupRest'];

            // Update workout with new exercise groups
            $workout->exerciseGroup = json_encode($exerciseGroups);
            $workout->exerciseGroupRest = json_encode($exerciseGroupRest);
            $workout->save();

            Log::info('AI Workout Regeneration: Success', [
                'user_id' => Auth::user()->id,
                'workout_id' => $workoutId,
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $workout->name,
                    'exerciseGroups' => $exerciseGroups
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('AI Workout Regeneration: Exception', [
                'user_id' => Auth::user()->id,
                'workout_id' => $workoutId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while regenerating the workout'
            ], 500);
        }
    }

    /**
     * Get exercises for selected body groups
     */
    private function getExercisesForBodyGroups($bodyGroupIds, $equipmentIds = [])
    {
        $query = Exercises::where("type","public")->whereIn('bodygroupId', $bodyGroupIds);

        if (!empty($equipmentIds)) {
            $query->whereIn('equipmentId', $equipmentIds);
        }

        // Get all matching exercises and shuffle for variety
        $allExercises = $query->get()->shuffle();
        
        // Calculate max exercises based on token limits
        // GPT-4o has 128k context window, we use 2k for completion
        // Available for prompt: 126k tokens
        // With 20% buffer: 126k * 0.8 = 100.8k tokens for prompt
        // Base prompt (instructions + format): ~1500 tokens
        // Each exercise line: ~40 tokens average
        // Max exercises: (100800 - 1500) / 40 = ~2482
        // But let's be conservative and use a lower estimate
        
        $maxTokens = 100800; // 80% of available context (20% buffer)
        $basePromptTokens = 1500; // Estimated tokens for instructions
        $tokensPerExercise = 40; // Conservative estimate per exercise line
        
        $maxExercises = (int)(($maxTokens - $basePromptTokens) / $tokensPerExercise);
        
        // Cap at a reasonable number to avoid extremely long prompts
        $maxExercises = min($maxExercises, 2000);
        
        return $allExercises->take($maxExercises);
    }

    /**
     * Build ChatGPT prompt
     */
    private function buildChatGPTPrompt($bodyGroups, $exercises, $validated)
    {
        $duration = $validated['duration'] ?? 60;
        $difficulty = $validated['difficulty'] ?? 'intermediate';
        $goals = $validated['goals'] ?? 'Build strength and muscle';

        $bodyGroupNames = $bodyGroups->pluck('name')->join(', ');

        // Format exercises for the prompt
        $exerciseList = $exercises->map(function ($exercise) {
            return sprintf(
                "- Exercise ID %d: %s (Body Group: %s, Equipment ID: %s)",
                $exercise->id,
                $exercise->name,
                $exercise->bodygroup->name ?? 'Unknown',
                $exercise->equipmentId ?? 'None'
            );
        })->join("\n");

        $prompt = <<<PROMPT
You are a professional fitness trainer creating a workout plan.

**Workout Requirements:**
- Target Body Groups: {$bodyGroupNames}
- Duration: {$duration} minutes
- Difficulty Level: {$difficulty}
- Goals: {$goals}

**Available Exercises:**
{$exerciseList}

**Instructions:**
Create a workout plan with exercise groups (circuits/supersets). Each group should have 1-3 exercises.
For each exercise, specify:
- Use ONLY exercise IDs from the available exercises list above
- Specify 3-4 sets with reps (or time in seconds for cardio/isometric) and weight in lbs (0 for bodyweight)
- Rest between sets in seconds (30-90 typical)
- Add helpful notes with form cues or tips
- Optionally specify tempo as 4 strings (eccentric, bottom pause, concentric, top pause)

**CRITICAL: Return ONLY valid JSON in this EXACT format (no markdown, no extra text):**

[
  {
    "exerciseGroupRest": 120,
    "exerciseGroup": [
      {
        "exerciseId": 123,
        "sets": [
          {"reps": 12, "weight": 25, "rest": 60},
          {"reps": 10, "weight": 30, "rest": 60},
          {"reps": 8, "weight": 35, "rest": 0}
        ],
        "notes": "Keep your back straight and core engaged",
        "tempo1": "2",
        "tempo2": "1",
        "tempo3": "2",
        "tempo4": "0"
      }
    ]
  },
  {
    "exerciseGroupRest": 90,
    "exerciseGroup": [
      {
        "exerciseId": 456,
        "sets": [
          {"time": 30, "weight": 0, "rest": 20},
          {"time": 30, "weight": 0, "rest": 20},
          {"time": 30, "weight": 0, "rest": 0}
        ],
        "notes": "Maintain steady pace throughout",
        "tempo1": "",
        "tempo2": "",
        "tempo3": "",
        "tempo4": ""
      }
    ]
  }
]

**Important Notes:**
- Use "reps" for strength exercises, "time" for cardio/isometric exercises (in seconds)
- Use ONLY exercise IDs from the provided list
- Match the difficulty level: beginner (higher reps, lower weight), intermediate (balanced), advanced (lower reps, higher weight)
- exerciseGroupRest is rest after completing the entire group (60-180 seconds)
- Last set in each exercise should have rest: 0
- Total workout should fit within {$duration} minutes
- Return ONLY the JSON array, no explanations
PROMPT;

        return $prompt;
    }

    /**
     * Send prompt to ChatGPT
     */
    private function sendToChatGPT($prompt)
    {
        try {
            $apiKey = config('services.chatgpt.api_key');

            if (!$apiKey) {
                Log::error('AI Workout Generation: OpenAI API key not configured', [
                    'user_id' => Auth::user()->id,
                    'timestamp' => now()
                ]);
                return null;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(90)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o',  // gpt-4o has 128k token context window
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional fitness trainer. You must respond ONLY with valid JSON, no markdown formatting, no code blocks, no extra text.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000,  // Reduced since workout structure doesn't need 4000 tokens
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            Log::error('AI Workout Generation: ChatGPT API error', [
                'user_id' => Auth::user()->id,
                'status' => $response->status(),
                'response' => $response->body(),
                'timestamp' => now()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('AI Workout Generation: Exception in ChatGPT request', [
                'user_id' => Auth::user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()
            ]);
            return null;
        }
    }

    /**
     * Parse and validate AI response
     */
    private function parseAIResponse($chatGPTResponse, $exercises)
    {
        try {
            // Clean up response - remove markdown code blocks if present
            $cleanResponse = trim($chatGPTResponse);
            $cleanResponse = preg_replace('/^```json\s*/s', '', $cleanResponse);
            $cleanResponse = preg_replace('/\s*```$/s', '', $cleanResponse);
            $cleanResponse = trim($cleanResponse);

            $aiData = json_decode($cleanResponse, true);

            if (!$aiData || !is_array($aiData)) {
                Log::error('AI Workout Generation: Invalid JSON in AI response', [
                    'user_id' => Auth::user()->id,
                    'response' => $chatGPTResponse,
                    'timestamp' => now()
                ]);
                return null;
            }

            // Create exercise map for validation and enrichment
            $exerciseMap = [];
            foreach ($exercises as $exercise) {
                $exerciseMap[$exercise->id] = $exercise;
            }

            // Transform AI response to match the frontend structure
            $enrichedGroups = [];
            $exerciseGroupRestArray = [];
            
            foreach ($aiData as $groupIndex => $groupData) {
                $exerciseGroupData = $groupData['exerciseGroup'] ?? [];
                $exerciseGroupRest = $groupData['exerciseGroupRest'] ?? 120;
                
                if (empty($exerciseGroupData)) {
                    continue;
                }

                // Build exerciseGroupRest entry for this group
                $restEntry = [
                    'type' => 'regular',
                    'restTime' => (string)$exerciseGroupRest
                ];

                $enrichedGroup = [];
                
                foreach ($exerciseGroupData as $aiExercise) {
                    $exerciseId = $aiExercise['exerciseId'] ?? null;
                    $sets = $aiExercise['sets'] ?? [];

                    if (!$exerciseId || !isset($exerciseMap[$exerciseId]) || empty($sets)) {
                        Log::warning('AI Workout Generation: Invalid exercise ID in response', [
                            'user_id' => Auth::user()->id,
                            'exercise_id' => $exerciseId,
                            'group_index' => $groupIndex,
                            'timestamp' => now()
                        ]);
                        continue;
                    }

                    // Get full exercise data from database
                    $dbExercise = $exerciseMap[$exerciseId];
                    
                    // Determine rep type based on sets
                    $firstSet = $sets[0] ?? [];
                    $hasTime = isset($firstSet['time']) && $firstSet['time'] > 0;
                    $repType = $hasTime ? 'time' : 'rep';
                    
                    // Build arrays for each set
                    $repsTypeArray = [];
                    $weightsArray = [];
                    $repArray = [];
                    $timesArray = [];
                    $restBetweenSets = [];
                    
                    foreach ($sets as $index => $set) {
                        $repsTypeArray[] = $repType;
                        $weightsArray[] = (string)($set['weight'] ?? 0);
                        
                        if ($repType === 'time') {
                            $timeValue = $set['time'] ?? 30;
                            $repArray[] = (string)$timeValue;
                            $timesArray[] = (string)$timeValue;
                        } else {
                            $repArray[] = (string)($set['reps'] ?? 12);
                            $timesArray[] = null;
                        }
                        
                        // Add rest between sets (not after last set)
                        if ($index < count($sets) - 1) {
                            $restBetweenSets[] = (string)($set['rest'] ?? 60);
                        }
                    }
                    
                    // Build the enriched exercise data structure
                    $enrichedExercise = [
                        'exercise' => [
                            'name' => $dbExercise->name,
                            'id' => $dbExercise->id,
                            'bodygroupId' => $dbExercise->bodygroupId ?? null,
                            'thumb' => $dbExercise->thumb ?? '',
                            'image' => $dbExercise->image ?? '',
                            'thumb2' => $dbExercise->thumb2 ?? '',
                            'image2' => $dbExercise->image2 ?? '',
                            'used' => $dbExercise->used ?? 0,
                            'length' => $dbExercise->length ?? 0,
                            'equipmentId' => $dbExercise->equipmentId ?? null,
                            'nameEngine' => $dbExercise->nameEngine ?? '',
                            'type' => $dbExercise->type ?? 'public',
                            'authorId' => $dbExercise->authorId,
                            'scoreName' => $dbExercise->scoreName ?? 0,
                            'scoreNameEngine' => $dbExercise->scoreNameEngine ?? 0,
                        ],
                        'repType' => $repType,
                        'repsType' => $repsTypeArray,
                        'weights' => $weightsArray,
                        'repArray' => $repArray,
                        'times' => $timesArray,
                        'hrs' => array_fill(0, count($sets), null),
                        'speeds' => array_fill(0, count($sets), null),
                        'distances' => array_fill(0, count($sets), null),
                        'metric' => 'imperial',
                        'notes' => $aiExercise['notes'] ?? '',
                        'tempo1' => $aiExercise['tempo1'] ?? '',
                        'tempo2' => $aiExercise['tempo2'] ?? '',
                        'tempo3' => $aiExercise['tempo3'] ?? '',
                        'tempo4' => $aiExercise['tempo4'] ?? '',
                        'restBetweenSets' => $restBetweenSets,
                    ];

                    $enrichedGroup[] = $enrichedExercise;
                }

                if (!empty($enrichedGroup)) {
                    $enrichedGroups[] = $enrichedGroup;
                    $exerciseGroupRestArray[] = $restEntry;
                }
            }

            if (empty($enrichedGroups)) {
                Log::error('AI Workout Generation: No valid exercise groups after validation', [
                    'user_id' => Auth::user()->id,
                    'timestamp' => now()
                ]);
                return null;
            }

            Log::info('AI Workout Generation: Successfully parsed and validated AI response', [
                'user_id' => Auth::user()->id,
                'group_count' => count($enrichedGroups),
                'timestamp' => now()
            ]);

            return [
                'exerciseGroups' => $enrichedGroups,
                'exerciseGroupRest' => $exerciseGroupRestArray
            ];

        } catch (\Exception $e) {
            Log::error('AI Workout Generation: Exception parsing AI response', [
                'user_id' => Auth::user()->id,
                'error' => $e->getMessage(),
                'response' => $chatGPTResponse,
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()
            ]);
            return null;
        }
    }
}
