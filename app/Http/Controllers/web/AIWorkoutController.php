<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\BodyGroups;
use App\Models\Exercises;
use App\Models\Equipments;
use App\Models\Workouts;
use App\Models\WorkoutsExercises;
use App\Models\ExerciseChat;
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

        // Determine the view based on user type
        $viewPath = Auth::user()->userType === 'Trainee' ? 'trainee.createWorkoutAI' : 'trainer.createWorkoutAI';

        return view($viewPath)
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
                'special_requests' => 'nullable|string|max:1000',
                'include_supersets' => 'nullable|boolean',
                'include_circuits' => 'nullable|boolean',
                'cardio_at_end' => 'nullable|boolean',
                'cardio_at_beginning' => 'nullable|boolean',
                'cardio_duration_end' => 'nullable|integer|min:5|max:60',
                'cardio_duration_beginning' => 'nullable|integer|min:5|max:60',
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

            // Handle cardio if requested
            if (!empty($validated['cardio_at_beginning'])) {
                $cardioDuration = $validated['cardio_duration_beginning'] ?? 10;
                $cardioData = $this->generateCardioExercise($validated, $cardioDuration);
                
                if ($cardioData) {
                    // Add cardio at the beginning
                    array_unshift($exerciseGroups, $cardioData['exerciseGroup']);
                    array_unshift($exerciseGroupRest, $cardioData['exerciseGroupRest']);
                }
            }
            
            if (!empty($validated['cardio_at_end'])) {
                $cardioDuration = $validated['cardio_duration_end'] ?? 10;
                $cardioData = $this->generateCardioExercise($validated, $cardioDuration);
                
                if ($cardioData) {
                    // Add cardio at the end
                    $exerciseGroups[] = $cardioData['exerciseGroup'];
                    $exerciseGroupRest[] = $cardioData['exerciseGroupRest'];
                }
            }

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

            // Redirect to workout editor based on user type
            $userType = Auth::user()->userType;
            $routePrefix = $userType === 'Trainee' ? '/Trainee/CreateWorkout/' : __('routes./Trainer/CreateWorkout/');
            return redirect()->to($routePrefix . $workout->id)
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
        $specialRequests = $validated['special_requests'] ?? '';

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

        // Add special requests section if provided
        $specialRequestsSection = '';
        if (!empty($specialRequests)) {
            $specialRequestsSection = "\n- Special Requests: {$specialRequests}";
        }

        // Add workout structure preferences
        $structurePreferences = [];
        if (!empty($validated['include_supersets'])) {
            $structurePreferences[] = "Include supersets where appropriate";
        }
        if (!empty($validated['include_circuits'])) {
            $structurePreferences[] = "Include circuits where appropriate";
        }
        
        $structureSection = '';
        if (!empty($structurePreferences)) {
            $structureSection = "\n- Structure Preferences: " . implode(', ', $structurePreferences);
        }

        // Determine if circuits/supersets are allowed
        $allowMultipleExercises = !empty($validated['include_supersets']) || !empty($validated['include_circuits']);

        $prompt = <<<PROMPT
You are a professional fitness trainer creating a workout plan.

**Workout Requirements:**
- Target Body Groups: {$bodyGroupNames}
- Duration: {$duration} minutes
- Difficulty Level: {$difficulty}
- Goals: {$goals}{$specialRequestsSection}{$structureSection}

**Available Exercises:**
{$exerciseList}

**Instructions:**
Create a workout plan with exercise groups. Each group can be:
1. Single exercise (regular set)
2. Circuit/Superset (2-3 exercises performed together)

PROMPT;

        // Add constraint for single exercise per group if circuits/supersets not requested
        if (!$allowMultipleExercises) {
            $prompt .= "\n**IMPORTANT: The user has NOT requested circuits or supersets. You MUST use ONLY ONE exercise per exerciseGroup. Do NOT add more than 1 exercise to any exerciseGroup.**\n\n";
        }

        $prompt .= <<<PROMPT
For circuits/supersets with 2+ exercises, specify the circuit type:
- "rounds": Fixed number of rounds with rest between (e.g., 3 rounds, 60s rest)
- "amrap": As Many Rounds As Possible in a time limit (e.g., 10 minutes, optional rest)
- "emom": Every Minute On the Minute for X minutes (e.g., 8 minutes)

For each exercise, specify:
- Use ONLY exercise IDs from the available exercises list above
- Specify 3-4 sets with varying rep types and weight in lbs (0 for bodyweight)
- Rep Types available:
  * "rep": Normal reps (value is a number, e.g., "12")
  * "maxRep": Maximum reps (value is the string "maximum")
  * "time": Time-based in seconds (value is a number, e.g., "30" for 30 seconds)
  * "range": Rep range (value is a string like "8-10")
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
          {"repType": "rep", "reps": "12", "weight": 25, "rest": 60},
          {"repType": "rep", "reps": "10", "weight": 30, "rest": 60},
          {"repType": "rep", "reps": "8", "weight": 35, "rest": 0}
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
          {"repType": "rep", "reps": "8", "weight": 100, "rest": 60},
          {"repType": "maxRep", "reps": "maximum", "weight": 80, "rest": 60},
          {"repType": "time", "reps": "30", "weight": 0, "rest": 60},
          {"repType": "range", "reps": "8-10", "weight": 90, "rest": 0}
        ],
        "notes": "Example showing all rep types - vary the types to add intensity"
      }
    ]
  },
  {
    "exerciseGroupRest": 90,
    "circuitType": "rounds",
    "circuitRounds": 3,
    "circuitRest": 60,
    "exerciseGroup": [
      {
        "exerciseId": 456,
        "sets": [
          {"repType": "rep", "reps": "15", "weight": 0, "rest": 0}
        ],
        "notes": "First exercise in circuit"
      },
      {
        "exerciseId": 789,
        "sets": [
          {"repType": "rep", "reps": "12", "weight": 20, "rest": 0}
        ],
        "notes": "Second exercise in circuit"
      }
    ]
  },
  {
    "exerciseGroupRest": 120,
    "circuitType": "amrap",
    "amrapTime": 10,
    "circuitRest": 30,
    "exerciseGroup": [
      {
        "exerciseId": 321,
        "sets": [
          {"repType": "rep", "reps": "10", "weight": 0, "rest": 0}
        ],
        "notes": "Complete as many rounds as possible in 10 minutes"
      },
      {
        "exerciseId": 654,
        "sets": [
          {"repType": "rep", "reps": "15", "weight": 0, "rest": 0}
        ],
        "notes": "Keep consistent pace"
      }
    ]
  },
  {
    "exerciseGroupRest": 90,
    "circuitType": "emom",
    "emomMinutes": 8,
    "exerciseGroup": [
      {
        "exerciseId": 111,
        "sets": [
          {"repType": "rep", "reps": "12", "weight": 0, "rest": 0}
        ],
        "notes": "Complete reps at the start of each minute"
      }
    ]
  }
]

**Important Notes:**
- Rep Types:
  * Use "rep" with a number value (e.g., "12") for standard rep counts
  * Use "maxRep" with "maximum" value for max effort sets
  * Use "time" with a number value (e.g., "30") for time-based exercises in seconds
  * Use "range" with a string value (e.g., "8-10") for rep ranges
- All reps values must be strings, not numbers
- Use ONLY exercise IDs from the provided list
- Single exercises: normal sets with rest between
- Circuits (2+ exercises): Add circuitType ("rounds", "amrap", or "emom") with appropriate parameters:
  * rounds: circuitRounds (number), circuitRest (seconds between rounds)
  * amrap: amrapTime (minutes), circuitRest (optional seconds between rounds)
  * emom: emomMinutes (number of minutes)
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
                $exerciseCount = count($exerciseGroupData);
                $circuitType = $groupData['circuitType'] ?? null;
                
                if ($exerciseCount > 1 && $circuitType) {
                    // This is a circuit/superset
                    $restEntry = [
                        'type' => 'circuit',
                        'circuitStyle' => $circuitType,
                        'restBetweenCircuitExercises' => []
                    ];
                    
                    if ($circuitType === 'rounds') {
                        $restEntry['circuitRound'] = (string)($groupData['circuitRounds'] ?? 3);
                        $restEntry['circuitRest'] = (string)($groupData['circuitRest'] ?? 60);
                    } elseif ($circuitType === 'amrap') {
                        $restEntry['amrapTime'] = (string)($groupData['amrapTime'] ?? 10);
                        if (isset($groupData['circuitRest'])) {
                            $restEntry['circuitRest'] = (string)$groupData['circuitRest'];
                        }
                    } elseif ($circuitType === 'emom') {
                        $restEntry['emomMinutes'] = (string)($groupData['emomMinutes'] ?? 8);
                    }
                } else {
                    // Regular single exercise
                    $restEntry = [
                        'type' => 'regular',
                        'restTime' => (string)$exerciseGroupRest
                    ];
                }

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
                    
                    // Build arrays for each set
                    $repsTypeArray = [];
                    $weightsArray = [];
                    $repArray = [];
                    $timesArray = [];
                    $restBetweenSets = [];
                    
                    foreach ($sets as $index => $set) {
                        // Get repType from the set, default to 'rep' if not specified
                        $setRepType = $set['repType'] ?? 'rep';
                        $repsValue = $set['reps'] ?? '12';
                        
                        $repsTypeArray[] = $setRepType;
                        $weightsArray[] = (string)($set['weight'] ?? 0);
                        $repArray[] = (string)$repsValue;
                        
                        // Set time based on repType
                        if ($setRepType === 'time') {
                            $timesArray[] = (string)$repsValue;
                        } else {
                            $timesArray[] = null;
                        }
                        
                        // Add rest between sets (not after last set)
                        if ($index < count($sets) - 1) {
                            $restBetweenSets[] = (string)($set['rest'] ?? 60);
                        }
                    }
                    
                    // Determine primary repType for the exercise (use first set's type)
                    $primaryRepType = $repsTypeArray[0] ?? 'rep';
                    
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
                        'repType' => $primaryRepType,
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

    /**
     * Get chat history for an exercise
     */
    public function getExerciseChat(Request $request, $exerciseId)
    {
        try {
            $exercise = Exercises::findOrFail($exerciseId);
            
            $chats = ExerciseChat::where('user_id', Auth::id())
                ->where('exercise_id', $exerciseId)
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'exercise' => [
                    'id' => $exercise->id,
                    'name' => $exercise->name,
                    'description' => $exercise->description
                ],
                'messages' => $chats->map(function($chat) {
                    return [
                        'id' => $chat->id,
                        'message' => $chat->message,
                        'sender' => $chat->sender,
                        'timestamp' => $chat->created_at->format('Y-m-d H:i:s')
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Exercise Chat: Failed to get chat history', [
                'user_id' => Auth::id(),
                'exercise_id' => $exerciseId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load chat history'
            ], 500);
        }
    }

    /**
     * Send message and get AI response
     */
    public function sendExerciseChat(Request $request, $exerciseId)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string|max:2000'
            ]);

            $exercise = Exercises::findOrFail($exerciseId);
            
            // Save user message
            $userMessage = ExerciseChat::create([
                'user_id' => Auth::id(),
                'exercise_id' => $exerciseId,
                'message' => $validated['message'],
                'sender' => 'user'
            ]);

            // Get previous chat history for context
            $previousChats = ExerciseChat::where('user_id', Auth::id())
                ->where('exercise_id', $exerciseId)
                ->where('id', '<', $userMessage->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->reverse();

            // Build chat context
            $chatHistory = "";
            foreach ($previousChats as $chat) {
                $role = $chat->sender === 'user' ? 'User' : 'AI Trainer';
                $chatHistory .= "{$role}: {$chat->message}\n";
            }

            // Build AI prompt
            $prompt = "You are an expert AI Personal Trainer assistant. You're helping a user with questions about an exercise.\n\n";
            $prompt .= "Exercise: {$exercise->name}\n";
            if ($exercise->description) {
                $prompt .= "Description: {$exercise->description}\n";
            }
            $prompt .= "\nPrevious conversation:\n{$chatHistory}\n";
            $prompt .= "User: {$validated['message']}\n\n";
            $prompt .= "Provide a helpful, concise response about form, technique, benefits, common mistakes, or variations. ";
            $prompt .= "Be encouraging and professional. Keep responses under 200 words unless more detail is specifically requested.";

            // Send to OpenAI
            $apiKey = config('services.chatgpt.api_key');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert AI Personal Trainer assistant, knowledgeable in exercise science, biomechanics, and fitness training.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

            if ($response->failed()) {
                throw new \Exception('OpenAI API request failed: ' . $response->body());
            }

            $aiResponse = $response->json()['choices'][0]['message']['content'] ?? '';

            if (empty($aiResponse)) {
                throw new \Exception('Empty response from OpenAI');
            }

            // Save AI response
            $aiMessage = ExerciseChat::create([
                'user_id' => Auth::id(),
                'exercise_id' => $exerciseId,
                'message' => $aiResponse,
                'sender' => 'ai'
            ]);

            Log::info('Exercise Chat: Message sent successfully', [
                'user_id' => Auth::id(),
                'exercise_id' => $exerciseId,
                'user_message_id' => $userMessage->id,
                'ai_message_id' => $aiMessage->id
            ]);

            return response()->json([
                'success' => true,
                'userMessage' => [
                    'id' => $userMessage->id,
                    'message' => $userMessage->message,
                    'sender' => 'user',
                    'timestamp' => $userMessage->created_at->format('Y-m-d H:i:s')
                ],
                'aiMessage' => [
                    'id' => $aiMessage->id,
                    'message' => $aiMessage->message,
                    'sender' => 'ai',
                    'timestamp' => $aiMessage->created_at->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Exercise Chat: Failed to send message', [
                'user_id' => Auth::id(),
                'exercise_id' => $exerciseId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again.'
            ], 500);
        }
    }

    /**
     * Estimate workout duration based on exercise groups
     */
    private function estimateWorkoutDuration($exerciseGroups)
    {
        $totalMinutes = 0;

        foreach ($exerciseGroups as $group) {
            foreach ($group as $exercise) {
                // Get number of sets
                $numSets = count($exercise['repsType'] ?? []);
                
                // Estimate time per set (average 45 seconds per set + rest)
                $setTime = 0.75; // 45 seconds in minutes
                
                // Add rest between sets
                $restBetweenSets = $exercise['restBetweenSets'] ?? [];
                $totalRest = 0;
                foreach ($restBetweenSets as $rest) {
                    $totalRest += intval($rest);
                }
                $restMinutes = $totalRest / 60;
                
                $totalMinutes += ($numSets * $setTime) + $restMinutes;
            }
            
            // Add 2 minutes rest between exercise groups
            $totalMinutes += 2;
        }

        return round($totalMinutes);
    }

    /**
     * Generate a cardio exercise using AI
     */
    private function generateCardioExercise($validated, $cardioDurationMinutes = 10)
    {
        try {
            // Get cardio exercises (bodygroup 18)
            $cardioExercises = Exercises::where("type", "public")
                ->where('bodygroupId', 18)
                ->get();

            if ($cardioExercises->isEmpty()) {
                Log::warning('AI Cardio Generation: No cardio exercises found', [
                    'user_id' => Auth::user()->id
                ]);
                return null;
            }

            // Use the specified cardio duration (default 10 minutes)
            $remainingDuration = max(5, min(60, $cardioDurationMinutes)); // Min 5 min, max 60 min
            $cardioDurationSeconds = $remainingDuration * 60;

            // Format exercises for the prompt
            $exerciseList = $cardioExercises->map(function ($exercise) {
                return sprintf(
                    "- Exercise ID %d: %s (Equipment ID: %s)",
                    $exercise->id,
                    $exercise->name,
                    $exercise->equipmentId ?? 'None'
                );
            })->join("\n");

            $difficulty = $validated['difficulty'] ?? 'intermediate';
            $specialRequests = $validated['special_requests'] ?? '';
            
            $specialRequestsSection = '';
            if (!empty($specialRequests)) {
                $specialRequestsSection = "\n- Special Requests: {$specialRequests}";
            }

            $prompt = <<<PROMPT
You are a professional fitness trainer adding a cardio exercise to a workout plan.

**Cardio Requirements:**
- Duration: {$remainingDuration} minutes ({$cardioDurationSeconds} seconds total)
- Difficulty Level: {$difficulty}{$specialRequestsSection}

**Available Cardio Exercises:**
{$exerciseList}

**Instructions:**
Select ONE cardio exercise and create intervals with heart rate zones. You can create multiple intervals (sets) with different intensities.

**Cardio Metric Types:**
- "hr": Target heart rate in BPM (e.g., "150")
- "effort": Percentage of effort (e.g., "70" for 70%)
- "Vo2Max": Percentage of VO2 Max (e.g., "80")
- "reserve": Heart rate reserve in BPM (e.g., "40")
- "range": Heart rate range (e.g., "120-150")
- "max": Maximum heart rate (use "Max" as value)

Return ONLY valid JSON in this EXACT format:

{
  "exerciseId": 123,
  "sets": [
    {"repType": "hr", "hrValue": "150", "timeSeconds": "600"},
    {"repType": "effort", "hrValue": "80", "timeSeconds": "300"}
  ],
  "notes": "Maintain steady pace, increase intensity in final interval"
}

**Important Rules:**
- Total time across all sets must equal approximately {$cardioDurationSeconds} seconds
- Each interval (set) should be between 180-1200 seconds (3-20 minutes)
- hrValue is a string (e.g., "150", "80", "120-150", "Max")
- timeSeconds is the duration of that interval in seconds
- Choose repType based on difficulty: beginner (effort/Vo2Max), intermediate (hr/reserve), advanced (hr/range/max)
- Use ONLY exercise IDs from the provided list
- Return ONLY the JSON object, no markdown, no extra text
PROMPT;

            // Send to ChatGPT
            $apiKey = config('services.chatgpt.api_key');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o',
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
                'max_tokens' => 500,
            ]);

            if ($response->failed()) {
                Log::error('AI Cardio Generation: API request failed', [
                    'user_id' => Auth::user()->id,
                    'response' => $response->body()
                ]);
                return null;
            }

            $chatGPTResponse = $response->json()['choices'][0]['message']['content'] ?? '';

            if (empty($chatGPTResponse)) {
                return null;
            }

            // Clean up response
            $cleanResponse = trim($chatGPTResponse);
            $cleanResponse = preg_replace('/^```json\s*/s', '', $cleanResponse);
            $cleanResponse = preg_replace('/\s*```$/s', '', $cleanResponse);
            $cleanResponse = trim($cleanResponse);

            $cardioData = json_decode($cleanResponse, true);

            if (!$cardioData || !isset($cardioData['exerciseId'])) {
                Log::error('AI Cardio Generation: Invalid JSON response', [
                    'user_id' => Auth::user()->id,
                    'response' => $chatGPTResponse
                ]);
                return null;
            }

            // Get exercise details
            $exerciseId = $cardioData['exerciseId'];
            $dbExercise = $cardioExercises->firstWhere('id', $exerciseId);

            if (!$dbExercise) {
                Log::error('AI Cardio Generation: Invalid exercise ID', [
                    'user_id' => Auth::user()->id,
                    'exercise_id' => $exerciseId
                ]);
                return null;
            }

            $sets = $cardioData['sets'] ?? [];
            $repsTypeArray = [];
            $weightsArray = [];
            $repArray = [];
            $timesArray = [];
            $hrsArray = [];
            $speedsArray = [];
            $distancesArray = [];
            $restBetweenSets = [];

            foreach ($sets as $index => $set) {
                $repType = $set['repType'] ?? 'hr';
                $hrValue = $set['hrValue'] ?? '150';
                $timeSeconds = $set['timeSeconds'] ?? '600';
                
                // Convert seconds to minutes for display (UI expects minutes)
                $timeMinutes = round(intval($timeSeconds) / 60, 2);

                $repsTypeArray[] = $repType;
                $weightsArray[] = null;
                $repArray[] = (string)$hrValue;
                $timesArray[] = (string)$timeMinutes;  // Store as minutes for UI
                $hrsArray[] = (string)$hrValue;
                $speedsArray[] = null;
                $distancesArray[] = null;
                
                // Add rest between intervals (not after last)
                if ($index < count($sets) - 1) {
                    $restBetweenSets[] = '0';
                }
            }

            // Build enriched exercise data
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
                'repType' => $repsTypeArray[0] ?? 'hr',
                'repsType' => $repsTypeArray,
                'weights' => $weightsArray,
                'repArray' => $repArray,
                'times' => $timesArray,
                'hrs' => $hrsArray,
                'speeds' => $speedsArray,
                'distances' => $distancesArray,
                'metric' => 'imperial',
                'notes' => $cardioData['notes'] ?? '',
                'tempo1' => '',
                'tempo2' => '',
                'tempo3' => '',
                'tempo4' => '',
                'restBetweenSets' => $restBetweenSets
            ];

            return [
                'exerciseGroup' => [$enrichedExercise],
                'exerciseGroupRest' => [
                    'type' => 'regular',
                    'restTime' => '120'
                ]
            ];

        } catch (\Exception $e) {
            Log::error('AI Cardio Generation: Exception', [
                'user_id' => Auth::user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Get AI suggestions for exercise replacement
     */
    public function getExerciseReplacements(Request $request)
    {
        try {
            $validated = $request->validate([
                'workout_id' => 'required|exists:workouts,id',
                'workouts_exercise_id' => 'required|exists:workouts_exercises,id',
                'exercise_id' => 'required|exists:exercises,id',
                'bodygroup_id' => 'required|exists:bodygroups,id'
            ]);

            $workout = Workouts::find($validated['workout_id']);
            $targetExercise = Exercises::find($validated['exercise_id']);
            
            if (!$workout || !$targetExercise) {
                return response()->json([
                    'success' => false,
                    'message' => 'Workout or exercise not found'
                ], 404);
            }

            // Get all exercises in the workout
            $workoutExercises = WorkoutsExercises::where('workoutId', $workout->id)
                ->with('exercises')
                ->get();
            
            $currentExerciseNames = $workoutExercises->map(function($we) {
                return $we->exercises->name ?? '';
            })->filter()->toArray();

            // Get available exercises for this body group (excluding current workout exercises)
            $availableExercises = Exercises::where('type', 'public')
                ->where('bodygroupId', $validated['bodygroup_id'])
                ->whereNotIn('id', $workoutExercises->pluck('exerciseId')->toArray())
                ->get();

            if ($availableExercises->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No alternative exercises found for this body group'
                ], 400);
            }

            // Build AI prompt for exercise replacement
            $prompt = $this->buildReplacementPrompt($targetExercise, $currentExerciseNames, $availableExercises);

            Log::info('AI Exercise Replacement: Prompt', [
                'user_id' => Auth::user()->id,
                'workout_id' => $workout->id,
                'target_exercise' => $targetExercise->name,
                'prompt' => $prompt
            ]);

            // Send to ChatGPT
            $chatGPTResponse = $this->sendToChatGPT($prompt);

            if (!$chatGPTResponse) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to get AI suggestions'
                ], 500);
            }

            // Parse response
            $suggestions = $this->parseReplacementResponse($chatGPTResponse, $availableExercises);

            if (!$suggestions || count($suggestions) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to parse AI suggestions'
                ], 500);
            }

            Log::info('AI Exercise Replacement: Success', [
                'user_id' => Auth::user()->id,
                'workout_id' => $workout->id,
                'suggestions_count' => count($suggestions)
            ]);

            return response()->json([
                'success' => true,
                'suggestions' => $suggestions
            ]);

        } catch (\Exception $e) {
            Log::error('AI Exercise Replacement: Exception', [
                'user_id' => Auth::user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while getting exercise suggestions'
            ], 500);
        }
    }

    /**
     * Build prompt for exercise replacement
     */
    private function buildReplacementPrompt($targetExercise, $currentExerciseNames, $availableExercises)
    {
        $exerciseList = $availableExercises->map(function($ex) {
            return "ID: {$ex->id}, Name: {$ex->name}, Equipment: " . ($ex->equipment->name ?? 'None');
        })->join("\n");

        $currentExercises = implode(", ", $currentExerciseNames);

        return "You are a professional fitness trainer helping to find exercise replacements.

Target Exercise to Replace: {$targetExercise->name}
Body Group: " . ($targetExercise->bodygroup->name ?? 'Unknown') . "

Current Exercises in Workout: {$currentExercises}

Find exactly 5 alternative exercises from the list below that would be good replacements for '{$targetExercise->name}'.
Consider:
- Similar movement patterns and muscle activation
- Exercise variety (don't suggest exercises already in the workout)
- Different equipment options when possible
- Progression/regression options

Available Exercises:
{$exerciseList}

Respond ONLY with valid JSON (no markdown, no code blocks) in this exact format:
{
  \"suggestions\": [
    {\"exercise_id\": 123, \"reason\": \"Brief reason why this is a good replacement\"},
    {\"exercise_id\": 456, \"reason\": \"Brief reason why this is a good replacement\"},
    {\"exercise_id\": 789, \"reason\": \"Brief reason why this is a good replacement\"},
    {\"exercise_id\": 234, \"reason\": \"Brief reason why this is a good replacement\"},
    {\"exercise_id\": 567, \"reason\": \"Brief reason why this is a good replacement\"}
  ]
}";
    }

    /**
     * Parse AI replacement response
     */
    private function parseReplacementResponse($response, $availableExercises)
    {
        try {
            // Clean up response
            $cleanResponse = trim($response);
            $cleanResponse = preg_replace('/^```json\s*/s', '', $cleanResponse);
            $cleanResponse = preg_replace('/\s*```$/s', '', $cleanResponse);
            $cleanResponse = trim($cleanResponse);

            $data = json_decode($cleanResponse, true);

            if (!$data || !isset($data['suggestions']) || !is_array($data['suggestions'])) {
                Log::error('AI Exercise Replacement: Invalid JSON response', [
                    'response' => $response
                ]);
                return null;
            }

            // Create exercise map for quick lookup
            $exerciseMap = [];
            foreach ($availableExercises as $exercise) {
                $exerciseMap[$exercise->id] = $exercise;
            }

            // Enrich suggestions with full exercise data
            $enrichedSuggestions = [];
            foreach ($data['suggestions'] as $suggestion) {
                if (!isset($suggestion['exercise_id']) || !isset($exerciseMap[$suggestion['exercise_id']])) {
                    continue;
                }

                $exercise = $exerciseMap[$suggestion['exercise_id']];
                $enrichedSuggestions[] = [
                    'exercise_id' => $exercise->id,
                    'name' => $exercise->name,
                    'equipment' => $exercise->equipment->name ?? 'None',
                    'reason' => $suggestion['reason'] ?? 'Good alternative exercise',
                    'image' => $exercise->image ?? '',
                    'video' => $exercise->video ?? '',
                    'youtube' => $exercise->youtube ?? ''
                ];
            }

            return array_slice($enrichedSuggestions, 0, 5);

        } catch (\Exception $e) {
            Log::error('AI Exercise Replacement: Parse error', [
                'error' => $e->getMessage(),
                'response' => $response
            ]);
            return null;
        }
    }

    /**
     * Execute exercise replacement
     */
    public function executeExerciseReplacement(Request $request)
    {
        try {
            $validated = $request->validate([
                'workout_id' => 'required|exists:workouts,id',
                'workouts_exercise_id' => 'required|exists:workouts_exercises,id',
                'new_exercise_id' => 'required|exists:exercises,id'
            ]);

            $workout = Workouts::find($validated['workout_id']);
            $workoutsExercise = WorkoutsExercises::find($validated['workouts_exercise_id']);
            $newExercise = Exercises::find($validated['new_exercise_id']);

            if (!$workout || !$workoutsExercise || !$newExercise) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid workout, exercise, or replacement'
                ], 404);
            }

            $oldExerciseId = $workoutsExercise->exerciseId;

            // Update the WorkoutsExercises record (keeps all sets intact)
            $workoutsExercise->exerciseId = $newExercise->id;
            $workoutsExercise->save();

            // Update the JSON exerciseGroup field
            $this->updateWorkoutJSON($workout, $oldExerciseId, $newExercise->id);

            Log::info('AI Exercise Replacement: Executed', [
                'user_id' => Auth::user()->id,
                'workout_id' => $workout->id,
                'old_exercise_id' => $oldExerciseId,
                'new_exercise_id' => $newExercise->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Exercise replaced successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('AI Exercise Replacement: Execution error', [
                'user_id' => Auth::user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to replace exercise'
            ], 500);
        }
    }

    /**
     * Update workout JSON with new exercise ID
     */
    private function updateWorkoutJSON($workout, $oldExerciseId, $newExerciseId)
    {
        try {
            $exerciseGroup = json_decode($workout->exerciseGroup, true);
            
            if (!$exerciseGroup || !is_array($exerciseGroup)) {
                return;
            }

            // Recursively search and replace exercise IDs in the nested structure
            $updated = false;
            foreach ($exerciseGroup as $groupIndex => &$group) {
                if (!is_array($group)) {
                    continue;
                }

                foreach ($group as $exerciseIndex => &$exercise) {
                    if (isset($exercise['exercise']['id']) && $exercise['exercise']['id'] == $oldExerciseId) {
                        $exercise['exercise']['id'] = $newExerciseId;
                        $updated = true;
                    }
                }
            }

            if ($updated) {
                $workout->exerciseGroup = json_encode($exerciseGroup);
                $workout->save();

                Log::info('AI Exercise Replacement: JSON updated', [
                    'workout_id' => $workout->id,
                    'old_exercise_id' => $oldExerciseId,
                    'new_exercise_id' => $newExerciseId
                ]);
            }

        } catch (\Exception $e) {
            Log::error('AI Exercise Replacement: JSON update error', [
                'workout_id' => $workout->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
