<!-- section copied from folder Trainer, file workouts.blade.php-->
<div class="lightBox" onclick="hidelightbox(event)";>
    <div class="popup_container sharewokoutform">
        <div class="header">
            <div class="upper_header">
                <h1>History</h1>
            </div>
            <div class="lower_header">
                <ul>

                </ul>
            </div>
        </div>

        <div class="share_content">
            <!-- TAB: EMAIL -->
            <div class="input_container" id="shareContentContainer">

            </div>
            <div class="btn_container">
                <button onclick="hidelightboxWithoutE();" class="cancel">{{ Lang::get('content.Cancel') }}</button>
                <button onclick="shareToEmail($(this));" class="send">{{ Lang::get('content.Send') }}</button>
            </div>
        </div>
    </div>
</div>


<div class="lightbox_mask" onclick="hidelightbox();"></div>


{{ HTML::script(asset('assets/fw/awesomplete-gh-pages/awesomplete.js')) }}
{{ HTML::script(asset('assets/js/twLightbox.js')) }}