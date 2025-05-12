<!-- section copied from folder Trainer, file workouts.blade.php-->
<div class="lightBox" onclick="hideexerclosemodal(event)";>
    <div class="popup_container sharewokoutform">
        <div class="header">
            <div class="upper_header">
                <h1>Suggest Exercises</h1>
            </div>
            <div class="lower_header">
                <ul>

                </ul>
            </div>
        </div>

        <div class="share_content">
            <div class="input_container" id="shareContentContainer">
                <form action="{{ route('suggest-exercise') }}" method="post" id="suggestExerciseForm"
                    class="form-horizontal">
                    <div style="margin-bottom: 10px;" class="form-group">
                        <label for="">{{ Lang::get('content.Exercisename') }}*</label>
                        <input style="margin: 0;" type="text" placeholder="bench press, bicep curl, etc..."
                            id="exercise_name" name="exercise_name" class="mt-4" required>
                    </div>
                </form>
            </div>
            <div class="btn_container">
                <button onclick="hideexerclosemodalWithoutE();"
                    class="cancel">{{ Lang::get('content.Cancel') }}</button>
                <button type="submit" form="suggestExerciseForm"
                    class="send">{{ Lang::get('content.Send') }}</button>
            </div>
        </div>
    </div>
</div>


<div class="lightbox_mask" onclick="hideexerclosemodal();"></div>


{{ HTML::script(asset('assets/fw/awesomplete-gh-pages/awesomplete.js')) }}
{{ HTML::script(asset('assets/js/twLightbox.js')) }}
