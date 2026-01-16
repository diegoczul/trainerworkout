@php
    use App\Http\Libraries\Helper;
@endphp
@extends('layouts.trainee')

@section('header')
    {!! Helper::seo('createWorkout') !!}
@endsection

@section('content')
    <div class="wrapper">
        <div class="widget">
            <div class="ai-workout-header">
                <div
                    class="a        .ai-form-section {
            background: white;
            border: 1px solid #e1e5e9;
            border-radius: 10px;
            padding: 0.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }t-title">
                    <h1>{{ Lang::get('content.CreateNewWorkoutAI') }}</h1>
                    <p>{{ Lang::get('content.AIWorkoutDescription') }}</p>
                </div>
            </div>

            <form action="{{ route('traineeGenerateWorkoutAI') }}" method="POST" class="ai-workout-form">
                @csrf <!-- Workout Name Section -->
                <div class="ai-form-section">
                    <h3>Workout Name</h3>
                    <p class="ai-form-description">Give your workout a name</p>
                    <div class="input-container">
                        <input type="text" id="workout_name" name="workout_name" value="{{ old('workout_name') }}"
                            placeholder="Enter workout name (e.g., 'Morning Chest & Back Blast')" class="workout-name-input">
                    </div>
                    @error('workout_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Special Requests Section -->
                <div class="ai-form-section">
                    <h3>Special Requests <span style="color: #999; font-weight: normal; font-size: 14px;">(Optional)</span></h3>
                    <p class="ai-form-description">Tell the AI anything specific you want in your workout - exercises you prefer, things to avoid, specific goals, etc.</p>
                    <div class="input-container">
                        <textarea id="special_requests" name="special_requests" rows="4" 
                            placeholder="Example: Include pull-ups, avoid exercises with jumping, focus on building bigger arms, want more isolation exercises..."
                            class="special-requests-textarea">{{ old('special_requests') }}</textarea>
                    </div>
                    @error('special_requests')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Body Groups Section -->
                <div class="ai-form-section">
                    <h3>Target Muscle Groups</h3>
                    <p class="ai-form-description">Select the muscle groups you want to target (choose at least one)</p>
                    <div id="muscle-group-error" class="error-message" style="display: none; margin-bottom: 1rem;">
                        Please select at least one muscle group to generate a workout
                    </div>
                    <div class="checkbox-grid">
                        @foreach ($bodyGroups as $bodyGroup)
                            <div class="checkbox-item">
                                <input type="checkbox" id="body_group_{{ $bodyGroup->id }}" name="body_groups[]" class="body-group-checkbox"
                                    value="{{ $bodyGroup->id }}"
                                    {{ in_array($bodyGroup->id, old('body_groups', [])) ? 'checked' : '' }}>
                                <label for="body_group_{{ $bodyGroup->id }}">{{ $bodyGroup->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('body_groups')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Equipment Section - Hidden for now -->
                <div class="ai-form-section" style="display: none;">
                    <h3>Available Equipment</h3>
                    <p class="ai-form-description">Select the equipment you have access to (optional)</p>
                    <div class="checkbox-grid">
                        @foreach ($equipments as $equipment)
                            <div class="checkbox-item">
                                <input type="checkbox" id="equipment_{{ $equipment->id }}" name="equipments[]"
                                    value="{{ $equipment->id }}"
                                    {{ in_array($equipment->id, old('equipments', [])) ? 'checked' : '' }}>
                                <label for="equipment_{{ $equipment->id }}">{{ $equipment->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('equipments')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Equipment Preference Section -->
                <div class="ai-form-section">
                    <h3>Equipment Preference</h3>
                    <p class="ai-form-description">How much equipment would you like to use in your workout?</p>
                    <div class="radio-grid">
                        <div class="radio-item">
                            <input type="radio" id="equipment_minimal" name="equipment_preference" value="minimal"
                                {{ old('equipment_preference') == 'minimal' ? 'checked' : '' }}>
                            <label for="equipment_minimal">
                                <strong>Minimal Equipment</strong><br>
                                <small>Prefer bodyweight exercises</small>
                            </label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="equipment_moderate" name="equipment_preference" value="moderate"
                                {{ old('equipment_preference', 'moderate') == 'moderate' ? 'checked' : '' }}>
                            <label for="equipment_moderate">
                                <strong>Moderate Equipment</strong><br>
                                <small>Mix of bodyweight and equipment</small>
                            </label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="equipment_heavy" name="equipment_preference" value="heavy"
                                {{ old('equipment_preference') == 'heavy' ? 'checked' : '' }}>
                            <label for="equipment_heavy">
                                <strong>Heavy Equipment</strong><br>
                                <small>Prefer equipment-based exercises</small>
                            </label>
                        </div>
                    </div>
                    @error('equipment_preference')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Workout Intensity Section -->
                <div class="ai-form-section">
                    <h3>Workout Intensity</h3>
                    <p class="ai-form-description">Choose your preferred workout intensity level</p>
                    <div class="radio-grid">
                        <div class="radio-item">
                            <input type="radio" id="intensity_light" name="intensity" value="light"
                                {{ old('intensity') == 'light' ? 'checked' : '' }}>
                            <label for="intensity_light">
                                <strong>Light Intensity</strong><br>
                                <small>Beginner-friendly, lower reps/sets</small>
                            </label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="intensity_moderate" name="intensity" value="moderate"
                                {{ old('intensity', 'moderate') == 'moderate' ? 'checked' : '' }}>
                            <label for="intensity_moderate">
                                <strong>Moderate Intensity</strong><br>
                                <small>Intermediate level</small>
                            </label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="intensity_high" name="intensity" value="high"
                                {{ old('intensity') == 'high' ? 'checked' : '' }}>
                            <label for="intensity_high">
                                <strong>High Intensity</strong><br>
                                <small>Advanced level, higher reps/sets</small>
                            </label>
                        </div>
                    </div>
                    @error('intensity')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Duration Section -->
                <div class="ai-form-section">
                    <h3>Workout Duration</h3>
                    <p class="ai-form-description">How long would you like your workout to be?</p>
                    <div class="duration-container">
                        <input type="range" id="duration" name="duration" min="15" max="120"
                            value="{{ old('duration', 45) }}" class="duration-slider">
                        <div class="duration-display">
                            <span id="duration-value">{{ old('duration', 45) }}</span> minutes
                        </div>
                        <div class="duration-labels">
                            <span>15 min</span>
                            <span>120 min</span>
                        </div>
                    </div>
                    @error('duration')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Workout Focus Section -->
                <div class="ai-form-section">
                    <h3>Workout Focus</h3>
                    <p class="ai-form-description">What is your primary fitness goal for this workout?</p>
                    <div class="radio-grid">
                        <div class="radio-item">
                            <input type="radio" id="focus_strength" name="workout_focus" value="strength"
                                {{ old('workout_focus') == 'strength' ? 'checked' : '' }}>
                            <label for="focus_strength">
                                <strong>Strength Training</strong><br>
                                <small>Focus on heavy compound movements</small>
                            </label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="focus_muscle" name="workout_focus" value="muscle_building"
                                {{ old('workout_focus') == 'muscle_building' ? 'checked' : '' }}>
                            <label for="focus_muscle">
                                <strong>Muscle Building</strong><br>
                                <small>Focus on muscle growth/hypertrophy</small>
                            </label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="focus_endurance" name="workout_focus" value="endurance"
                                {{ old('workout_focus') == 'endurance' ? 'checked' : '' }}>
                            <label for="focus_endurance">
                                <strong>Endurance Training</strong><br>
                                <small>Focus on cardiovascular fitness</small>
                            </label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="focus_general" name="workout_focus" value="general_fitness"
                                {{ old('workout_focus', 'general_fitness') == 'general_fitness' ? 'checked' : '' }}>
                            <label for="focus_general">
                                <strong>General Fitness</strong><br>
                                <small>Balanced approach to overall fitness</small>
                            </label>
                        </div>
                    </div>
                    @error('workout_focus')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <!-- Workout Structure Options -->
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0;">
                        <h4 style="color: #2d3748; font-size: 1rem; font-weight: 600; margin-bottom: 0.75rem;">Workout Structure Preferences</h4>
                        <div class="checkbox-grid" style="grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));">
                            <div class="checkbox-item">
                                <input type="checkbox" id="include_supersets" name="include_supersets" value="1"
                                    {{ old('include_supersets') ? 'checked' : '' }}>
                                <label for="include_supersets">Consider Supersets</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="include_circuits" name="include_circuits" value="1"
                                    {{ old('include_circuits') ? 'checked' : '' }}>
                                <label for="include_circuits">Consider Circuits</label>
                            </div>
                            <div class="checkbox-item" style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="cardio_at_end" name="cardio_at_end" value="1"
                                    {{ old('cardio_at_end') ? 'checked' : '' }}>
                                <label for="cardio_at_end" style="flex: 1;">Add Cardio at End</label>
                                <div style="display: flex; align-items: center; gap: 0.25rem;">
                                    <input type="number" id="cardio_duration_end" name="cardio_duration_end" 
                                        value="{{ old('cardio_duration_end', 10) }}" min="5" max="60" step="5"
                                        style="width: 60px; padding: 0.25rem 0.5rem; border: 1px solid #e2e8f0; border-radius: 4px; text-align: center;">
                                    <span style="font-size: 0.875rem; color: #718096;">min</span>
                                </div>
                            </div>
                            <div class="checkbox-item" style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="cardio_at_beginning" name="cardio_at_beginning" value="1"
                                    {{ old('cardio_at_beginning') ? 'checked' : '' }}>
                                <label for="cardio_at_beginning" style="flex: 1;">Add Cardio at Beginning</label>
                                <div style="display: flex; align-items: center; gap: 0.25rem;">
                                    <input type="number" id="cardio_duration_beginning" name="cardio_duration_beginning" 
                                        value="{{ old('cardio_duration_beginning', 10) }}" min="5" max="60" step="5"
                                        style="width: 60px; padding: 0.25rem 0.5rem; border: 1px solid #e2e8f0; border-radius: 4px; text-align: center;">
                                    <span style="font-size: 0.875rem; color: #718096;">min</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-submit-section">
                    <button type="submit" id="submit-btn" class="btn-generate-workout" onClick="lightBoxLoadingTwSpinner();">
                        Generate AI Workout
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        .ai-workout-header {
            margin-bottom: 2rem;
            padding: 1.5rem 0;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        .ai-workout-title h1 {
            color: #2C3E50;
            margin: 0 0 0.5rem 0;
            font-size: 1.75rem;
            font-weight: 600;
        }

        .ai-workout-title p {
            color: #666;
            margin: 0;
            font-size: 1rem;
        }

        .ai-workout-form {
            max-width: 800px;
            margin: 0 auto;
        }

        .ai-form-section {
            background: white;
            border: 1px solid #e1e5e9;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .ai-form-section h3 {
            margin: 0 0 0.5rem 0;
            color: #2d3748;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .ai-form-description {
            margin: 0 0 1rem 0;
            color: #718096;
            font-size: 0.95rem;
        }

        .workout-name-input {
            width: 100%;
            padding: 0.25rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .workout-name-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .special-requests-textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            resize: vertical;
            transition: border-color 0.2s, box-shadow 0.2s;
            line-height: 1.5;
        }

        .special-requests-textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .special-requests-textarea::placeholder {
            color: #a0aec0;
        }
        .checkbox-grid,
        .radio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 0.75rem;
        }

        .checkbox-item,
        .radio-item {
            position: relative;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.25rem;
            cursor: pointer;
            transition: all 0.2s;
            min-height: 60px;
            display: flex;
            align-items: center;
        }

        .checkbox-item:hover,
        .radio-item:hover {
            border-color: #667eea;
            background: #f0f4ff;
        }

        .checkbox-item input,
        .radio-item input {
            margin: 0 0.75rem 0 0.75rem;
            width: 18px;
            height: 18px;
            accent-color: #667eea;
            cursor: pointer;
            flex-shrink: 0;
        }

        .checkbox-item label,
        .radio-item label {
            cursor: pointer;
            font-weight: 500;
            color: #2d3748;
            flex-grow: 1;
            margin: 0;
            line-height: 1.4;
        }

        .radio-item label small {
            color: #718096;
            display: block;
            font-weight: 400;
            margin-top: 0.25rem;
        }

        .checkbox-item input:checked+label,
        .radio-item input:checked+label {
            color: #667eea;
        }

        .checkbox-item:has(input:checked),
        .radio-item:has(input:checked) {
            border-color: #667eea;
            background: #f0f4ff;
        }

        .duration-container {
            padding: 0.25rem 0;
        }

        .duration-slider {
            width: 100%;
            height: 8px;
            border-radius: 4px;
            background: #e2e8f0;
            outline: none;
            margin-bottom: 1rem;
        }

        .duration-slider::-webkit-slider-thumb {
            appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #667eea;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .duration-display {
            text-align: center;
            font-size: 1.25rem;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .duration-labels {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #718096;
        }

        .form-submit-section {
            text-align: center;
            padding: 0.25rem 0;
        }

        .btn-generate-workout {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 200px;
            justify-content: center;
        }

        .btn-generate-workout:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-generate-workout:disabled {
            background: linear-gradient(135deg, #cbd5e0 0%, #a0aec0 100%);
            cursor: not-allowed;
            opacity: 0.6;
        }

        .btn-icon {
            font-size: 1.2rem;
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {

            .checkbox-grid,
            .radio-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .ai-form-section {
                padding: 0.75rem;
                margin-bottom: 1rem;
            }

            .checkbox-item,
            .radio-item {
                padding: 0.75rem;
                min-height: 45px;
            }

            .ai-workout-form {
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {

            .checkbox-grid,
            .radio-grid {
                gap: 0.4rem;
            }

            .checkbox-item,
            .radio-item {
                padding: 0.6rem;
                font-size: 0.85rem;
                min-height: 40px;
            }

            .ai-form-section {
                padding: 0.5rem;
            }
        }
    </style>

    <script>
        // Validation for muscle group selection
        document.addEventListener('DOMContentLoaded', function() {
            const submitBtn = document.getElementById('submit-btn');
            const bodyGroupCheckboxes = document.querySelectorAll('.body-group-checkbox');
            const errorMessage = document.getElementById('muscle-group-error');

            function validateMuscleGroups() {
                const isAnyChecked = Array.from(bodyGroupCheckboxes).some(cb => cb.checked);
                
                if (isAnyChecked) {
                    submitBtn.disabled = false;
                    errorMessage.style.display = 'none';
                } else {
                    submitBtn.disabled = true;
                    errorMessage.style.display = 'block';
                }
            }

            // Check on page load
            validateMuscleGroups();

            // Add event listeners to all checkboxes
            bodyGroupCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', validateMuscleGroups);
            });

            // Duration slider update
            const durationSlider = document.getElementById('duration');
            const durationValue = document.getElementById('duration-value');
            if (durationSlider && durationValue) {
                durationSlider.addEventListener('input', function() {
                    durationValue.textContent = this.value;
                });
            }
        });
    </script>
@endsection
