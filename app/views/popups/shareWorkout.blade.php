<!-- section copied from folder Trainer, file workouts.blade.php-->
<div class="lightBox" onclick="hidelightbox(event)";>
    <div class="popup_container sharewokoutform" >
        <div class="header">
            <div class="upper_header"><h1>{{ Lang::get("content.Share") }} </h1></div>
            <div class="lower_header">
                <ul>
    
                </ul>
            </div>
        </div>

        <div class="share_content">
            <!-- TAB: EMAIL -->
            <div class="input_container">
                <label for="toemail" class="input_label">{{ Lang::get("content.emailaddress") }}</label>
                <input type="text" id="toemail" name="toemail" placeholder="email address" data-multiple>

                <label for="personalizedTxt" class="input_label">{{ Lang::get("content.Personalizethemessagetoyourclients") }}</label>
                <textarea name="personalizedTxt" id="personalizedTxt" placeholder="{{ Lang::get("content.Thisisthemessagethatyourclientwillreceive") }}"></textarea>
                <div class="sharingOptions">
                    <div class="option"><input id="copyMe" type="checkbox" value="yes" name="copyMe" checked="checked"/> <label for="copyMe">{{ Lang::get("content.sendCopy") }}</label></div>
                    <div class="option"><input id="copyPrint" type="checkbox" value="yes" name="copyPrint" checked="checked" /> <label for="copyPrint">{{ Lang::get("content.includePrintWorkout") }}</label> </div>
                    <div class="option"><input id="subscribe" type="checkbox" value="yes" name="subscribe" checked="checked" /> <label for="subscribeToWorkout">{{ Lang::get("content.subscribeToWorkout") }}</label> </div>
                    <div class="option"><input id="lock" type="checkbox" value="yes" name="lock" checked="checked" /> <label for="lock">{{ Lang::get("content.lockWorkout") }}</label> </div>
                </div>

            </div>
            <div class="btn_container">
                <button onclick="hidelightboxWithoutE();" class="cancel">{{ Lang::get("content.Cancel") }}</button>
                <button onclick="shareToEmail($(this));" class="send">{{ Lang::get("content.Send") }}</button>
            </div>
        </div>
    </div>
</div>


<div class="lightbox_mask" onclick="hidelightbox();"></div>


{{ HTML::script('fw/awesomplete-gh-pages/awesomplete.js'); }}
{{ HTML::script('js/twLightbox.js'); }}

<script type="text/javascript">

function shareToEmail(el){

    var div = el.closest('.sharewokoutform');
    var email = div.find('input[name=toemail]').val();

    //if (/^[A-z0-9._-]+@[A-z0-9-]+(\.[A-z]{2,}){1,2}$/.test(email)){

        var copyMe = true;
        var copyView = true;
        var copyPrint = true;
        var subscribeToWorkout = true;
        var lock = true;

        if(!$("#copyMe").prop('checked')) copyMe = false;
        if(!$("#copyView").prop('checked')) copyView = false;
        if(!$("#copyPrint").prop('checked')) copyPrint = false;
        if(!$("#subscribe").prop('checked')) subscribeToWorkout = false;
        if(!$("#lock").prop('checked')) lock = false;

        var preLoad = showLoadWithElement(el, 40, 'center');
        $.ajax(
            {
                url :"{{ Lang::get("routes./Workout/ShareByEmail") }}",
                type: "POST",
                data: { workoutId:div.attr('workout'),email : email,comments : $("#personalizedTxt").val(), copyMe:copyMe, copyView:copyView, copyPrint:copyPrint , subscribeToWorkout:subscribeToWorkout,lock:lock  },
                success:function(data, textStatus, jqXHR) 
                {
                    
                    parent.successMessage(data);
                    //parent.$.fancybox.close();
                    hideLoadWithElement(preLoad);
                    hidelightboxWithoutE();
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    parent.errorMessage(jqXHR.responseText);
                    hideLoadWithElement(preLoad);
                    hidelightboxWithoutE();
                    $(".workoutsHover").each(function(){
                        $(this).hide();
                        $(this).removeAttr("style");
                        showLess();
                        $(this).removeClass("workout_main_containerAlways");
                    });

                    
                    
                },
                statusCode: {
                    400: function(jqXHR) {
                        if(jqXHR.responseText != ""){
                            errorMessage(jqXHR.responseText);
                        }else {
                            
                        }
                        
                    }
                }
            });
    //}

}
//////////////////////////////////////////////////////////////////


var input = document.getElementById("toemail");
var ajax = new XMLHttpRequest();
ajax.open("GET", "/Clients/list/emails", true);
ajax.onload = function() {
    var list =  $.parseJSON(ajax.responseText); 
    console.log(list);
    // new Awesomplete(input,{ list: list });
    new Awesomplete(input, {
    filter: function(text, input) {
        return Awesomplete.FILTER_CONTAINS(text, input.match(/[^,]*$/)[0]);
    },

    replace: function(text) {
        var before = this.input.value.match(/^.+,\s*|/)[0];
        this.input.value = before + text + ", ";
    },
    list: list
});
};
ajax.send();


$('input').keyup(function(event) {
    if (event.which === 13) {
      $(this).blur();
    }
});


</script>