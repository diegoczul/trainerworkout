<!-- section copied from folder Trainer, file workouts.blade.php-->
<div class="lightBox" onclick="hideexerclosemodal(event)";>
    <div class="popup_container sharewokoutform">
        <div class="header">
            <div class="upper_header">
                <h1>New Exercise Name</h1>
            </div>
            <div class="lower_header">
                <ul>

                </ul>
            </div>
        </div>

        <div class="share_content">
            <!-- TAB: EMAIL -->
            <div class="input_container" id="shareContentContainer">
                <form action="">
                <input type="text" placeholder="Enter new exercise name" id="" class="mt-4">
                </form>
            </div>
            <div class="btn_container">
                <button onclick="hideexerclosemodalWithoutE();" class="cancel">{{ Lang::get('content.Cancel') }}</button>
                <button class="send">{{ Lang::get('content.Send') }}</button>
            </div>
        </div>
    </div>
</div>


<div class="lightbox_mask" onclick="hideexerclosemodal();"></div>


{{ HTML::script(asset('assets/fw/awesomplete-gh-pages/awesomplete.js')) }}
{{ HTML::script(asset('assets/js/twLightbox.js')) }}