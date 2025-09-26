# AI Workout Generator

This new AI Workout Generator creates personalized workout plans using ChatGPT based on user preferences.

## Features

1. **Interactive Questionnaire**: Users answer questions about:

   - Workout name
   - Target muscle groups (from existing body groups)
   - Available equipment (from existing equipment database)

2. **AI-Powered Generation**:

   - Sends user selections to ChatGPT
   - Provides available exercises in `id:exercise_name` format
   - Receives structured workout response

3. **Workout Structure Creation**:
   - Creates workout with proper `exerciseGroup` and `exerciseGroupRest` structure
   - Populates sets, reps, weights, and rest periods
   - Integrates with existing workout system

## How It Works

### 1. User Flow

1. User clicks "🤖 AI Workout Questionnaire" button on trainer workouts page
2. Fills out questionnaire form with workout name, body groups, and equipment
3. Submits form to generate AI workout
4. System redirects to workout editor with pre-populated AI-generated workout

### 2. Backend Process

1. **AIWorkoutController::showQuestionnaire()** - Shows the questionnaire form
2. **AIWorkoutController::generateAIWorkout()** - Processes form submission:
   - Validates input
   - Queries exercises based on selected body groups and equipment
   - Builds ChatGPT prompt with available exercises
   - Sends request to ChatGPT API
   - Processes AI response and creates workout structure
   - Redirects to workout editor

### 3. ChatGPT Integration

- **API**: Uses OpenAI GPT-4 model
- **Input**: Structured prompt with available exercises in `id:exercise_name` format
- **Output**: JSON structure matching the existing workout format
- **Fallback**: Creates simple workout if AI response fails

### 4. Database Structure

The system creates:

- **Workouts** record
- **WorkoutsGroups** records (exercise groups)
- **WorkoutsExercises** records (individual exercises)
- **Sets** records (with reps, weights, rest periods)

## Files Added/Modified

### New Files

- `app/Http/Controllers/web/AIWorkoutController.php` - Main controller
- `resources/views/trainer/createWorkoutAIQuestionnaire.blade.php` - Questionnaire view

### Modified Files

- `routes/web.php` - Added new routes
- `resources/views/trainer/workouts.blade.php` - Added questionnaire button
- `lang/en/content.php` - Added language constant

## Routes Added

```php
Route::get('/Trainer/CreateWorkoutAIQuestionnaire', [AIWorkoutController::class, 'showQuestionnaire'])->name('aiWorkout.questionnaire');
Route::post('/Trainer/GenerateAIWorkout', [AIWorkoutController::class, 'generateAIWorkout'])->name('aiWorkout.generate');
```

## Configuration

Ensure ChatGPT API key is configured in `config/services.php`:

```php
'chatgpt' => [
    'api_key' => env('CHATGPT_API_KEY'),
],
```

## Error Handling

- Input validation for required fields
- ChatGPT API error handling
- Fallback workout creation if AI response fails
- Comprehensive logging for debugging

## Future Enhancements

1. **Additional Parameters**: Could add intensity level, workout duration, fitness level
2. **Exercise Filtering**: More sophisticated exercise selection based on user preferences
3. **AI Refinement**: Improve prompts for better workout structure
4. **User Feedback**: Learn from user modifications to improve AI suggestions
5. **Multi-language Support**: Extend questionnaire to other supported languages

## Testing

To test the feature:

1. Navigate to trainer workouts page
2. Click the "🤖 AI Workout Questionnaire" button
3. Fill out the form with desired parameters
4. Submit and verify workout is created and user is redirected to editor
