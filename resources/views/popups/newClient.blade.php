<div class="lightBox createClient" onclick="hidelightbox(event)";>
    <div class="popup_container sharewokoutform" >
        <div class="header">
            <div class="upper_header"><h1>{{ Lang::get("content.AddNewClient") }} </h1></div>
            <div class="lower_header">
                <ul>
    
                </ul>
            </div>
        </div>
        {{ Form::open(array('url' => Lang::get("routes./Clients/AddClient"), "files" => true, 'name' => 'addClientForm', 'id' => 'addClientForm')) }}
        <div class="share_content">
            <div class="input_container">
                <div class="inputRow">
                    <div class="inputContainer">
                        <label for="clientFirstName" class="createClient">{{ Lang::get("content.clientFirstName") }}</label>
                        <input type="text" id="firstName" class="createClient" name="firstName" placeholder="{{ Lang::get('content.TFirstName')}}">
                    </div>

                    <div class="inputContainer">
                        <label for="clientLastName" class="createClient">{{ Lang::get("content.clientLastName") }}</label>
                        <input type="text" id="lastName" class="createClient" name="lastName" placeholder="{{ Lang::get('content.TLastName')}}">
                    </div>
                </div>

                <div class="inputRow">
                    <div class="inputContainer">
                        <label for="clientEmail" class="createClient">{{ Lang::get("content.clientEmail") }}</label>
                        <input type="text" id="email" class="createClient" name="email" placeholder="{{ Lang::get('content.Temail')}}">
                    </div>
                    <div class="inputContainer">
                        <label for="clientEmail" class="createClient">{{ Lang::get("content.clientPhoneNumber") }}</label>
                        <input type="text" id="phone" class="createClient" name="phone" placeholder="{{ Lang::get('content.TphoneNumber')}}">
                    </div>
                </div>
                <div class="clientOption">

                    <label>{{ Lang::get("content.LinkClient") }}</label>

                    <div class="clientConnectSwitcher">
                        <label for="clientOption">No</label>
                        <label class="unitToggleLabel">
                            <input id="clientOption" type="checkbox" name="clientLink" class="unitToggleInput" value="Yes" checked="checked">
                            <div class="unitToggleControl"></div>
                        </label>
                        <label for="clientOption">Yes</label>
                    </div>

                </div>
                <div class="connectingClient" style="display:block;">
                    <label for="personalizedTxt" class="input_label">{{ Lang::get("content.Personalizethemessagetoyourclients") }}</label>
                    <textarea name="personalizedTxt" id="personalizedTxt" placeholder="{{ Lang::get("content.Thisisthemessagethatyourclientwillreceive") }}"></textarea>

                    <div class="sharingOptions">
                        <div class="option"><input id="subscribe" type="checkbox" value="yes" name="subscribe" checked="checked" /> <label for="subscribeToWorkout">{{ Lang::get("content.subscribeToWorkout") }}</label> </div>
                    </div>
                </div>
            </div>
            <button type="submit" id="newClientAction" class="send ajaxSaveSubmit">{{ Lang::get("content.addClient") }}</button>
        </div>
        {{ Form::close() }}
    </div>
</div>


<div class="lightbox_mask" onclick="hidelightbox();"></div>


{{ HTML::script(asset('assets/fw/awesomplete-gh-pages/awesomplete.js')) }}
{{ HTML::script(asset('assets/js/twLightbox.js')) }}
<script type="text/javascript">
    $("#clientOption").change( function() {
        var showClient = $("#clientOption").is(':checked');
        if (showClient == true) {
            $('.connectingClient').show();
            $('#newClientAction').html(dict["sendInvite"]);
        } else {
            $('.connectingClient').hide();
            $('#newClientAction').html(dict["addClient"]);
        }
    });


    $("body").on("click",".ajaxSaveSubmit",function(event){
        //alert(1);
        var handler = $(this);
        tForm = $(this).closest("form");
        widget = $(this).attr("widget");
        tForm.submit(function(e)
            {
                e.preventDefault(); //STOP default action
                e.stopImmediatePropagation();
                //var postData = $(this).serializeArray();
                var formURL = $(this).attr("action");
                $.ajax({
                    url : formURL,
                    type: "POST",
                    data: new FormData( this ),
                    processData: false,
                    contentType: false,
                    beforeSend:function() 
                    {
                        showTopLoader();
                    },
                    success:function(data, textStatus, jqXHR) 
                    {
                        hideTopLoader();
                        hidelightboxWithoutE();
                        successMessage(data);
                        callWidget("w_clients");
                        return false;

                    },
                    error: function(jqXHR, textStatus, errorThrown) 
                    {
                        hideTopLoader();
                        errorMessage(jqXHR.responseText);
                        return false;
                    },
                    statusCode: {
                        500: function() {
                            if(jqXHR.responseText != ""){
                                errorMessage(jqXHR.responseText);
                            }
                            
                        }
                    }
                });
            }
        );
    });
</script>