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
            <div class="input_container">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Phone no.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td>Divya</td>
                            <td>111111111255</td>
                        </tr>
                    </tbody>
                </table>               

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

<script type="text/javascript">
    function shareToEmail(el) {

        var div = el.closest('.sharewokoutform');
        var email = div.find('input[name=toemail]').val();

        //if (/^[A-z0-9._-]+@[A-z0-9-]+(\.[A-z]{2,}){1,2}$/.test(email)){

        var copyMe = true;
        var copyView = true;
        var copyPrint = true;
        var subscribeToWorkout = true;
        var lock = true;

        if (!$("#copyMe").prop('checked')) copyMe = false;
        if (!$("#copyView").prop('checked')) copyView = false;
        if (!$("#copyPrint").prop('checked')) copyPrint = false;
        if (!$("#subscribe").prop('checked')) subscribeToWorkout = false;
        if (!$("#lock").prop('checked')) lock = false;

        var preLoad = showLoadWithElement(el, 40, 'center');
        $.ajax({
            url: "{{ Lang::get('routes./Workout/ShareByEmail') }}",
            type: "POST",
            data: {
                workoutId: div.attr('workout'),
                email: email,
                comments: $("#personalizedTxt").val(),
                copyMe: copyMe,
                copyView: copyView,
                copyPrint: copyPrint,
                subscribeToWorkout: subscribeToWorkout,
                lock: lock
            },
            success: function(data, textStatus, jqXHR) {

                parent.successMessage(data);
                //parent.$.fancybox.close();
                hideLoadWithElement(preLoad);
                hidelightboxWithoutE();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                parent.errorMessage(jqXHR.responseText);
                hideLoadWithElement(preLoad);
                hidelightboxWithoutE();
                $(".workoutsHover").each(function() {
                    $(this).hide();
                    $(this).removeAttr("style");
                    showLess();
                    $(this).removeClass("workout_main_containerAlways");
                });



            },
            // statusCode: {
            //     400: function(jqXHR) {
            //         if(jqXHR.responseText != ""){
            //             errorMessage(jqXHR.responseText);
            //         }else {
            //
            //         }
            //
            //     }
            // }
        });
        //}

    }
    //////////////////////////////////////////////////////////////////


    var input = document.getElementById("toemail");
    var ajax = new XMLHttpRequest();
    ajax.open("GET", "/Clients/list/emails", true);
    ajax.onload = function() {
        var list = $.parseJSON(ajax.responseText);
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
