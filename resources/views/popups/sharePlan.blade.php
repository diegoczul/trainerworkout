<div class="lightBox" onclick="hidelightbox(event)">
    <div class="popup_container shareplanform" plan="">
        <div class="header">
            <div class="upper_header">
                <h1>Share Plan</h1>
            </div>
        </div>

        <div class="share_content">
            <div class="input_container">
                <label for="toemail" class="input_label">Email address</label>
                <input type="text" id="toemail" name="toemail" placeholder="email address" data-multiple>

                <label for="personalizedTxt" class="input_label">Personalized message</label>
                <textarea name="personalizedTxt" id="personalizedTxt" placeholder="This is the message your client will receive."></textarea>

                <div class="sharingOptions">
                    <div class="option">
                        <input id="copyMe" type="checkbox" name="copyMe" value="yes" checked>
                        <label for="copyMe">Send me a copy</label>
                    </div>
                </div>
            </div>

            <div class="btn_container">
                <button onclick="hidelightboxWithoutE();" class="cancel">Cancel</button>
                <button onclick="sharePlanByEmail($(this));" class="send">Send</button>
            </div>
        </div>
    </div>
</div>

<div class="lightbox_mask" onclick="hidelightbox();"></div>
