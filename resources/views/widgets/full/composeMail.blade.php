<html>
<head>
    {{ HTML::style('css/innerStyles.css') }}
    {{ HTML::style('fw/jquery-ui-1.11.1.custom/jquery-ui.min.css'); }}
    {{ HTML::style('fw/chosen_v1/chosen.css'); }}
    {{ HTML::style('fw/autocomplete/foxycomplete.css'); }}
</head>
<body>
<div class="systemMessages"></div>
<div class="popupholder shadow leftcomposemessage .fb_popup-compose">

        	<h2>
<a href="javascript:void(0)" onclick="parent.$.fancybox.close();" class="buttonbig fltright">Close button</a>
              

                New Message

            </h2>

            <div class="messageformbox">

            {{ Form::open(array('url' => '/widgets/messages/addEdit/')); }}

                <fieldset class="padding-bottom">

    
                <input type="text" autocomplete="off" onkeyup="" value="{{ (isset($client) ? $client->getCompleteName() : "") }}" name="searchMessage" id="searchMessage" class="border-radius" placeholder="To" />
                <input type="hidden" id="friend" name="friend" value="{{ (isset($client) ? $client->id : "") }}">
                <p id="results"></p>
        <div class="friendholder"></div>

                </fieldset>

                <fieldset>

                <textarea name="message" id="message" type="text" class="border-radius" placeholder="Message"></textarea>

                </fieldset>

                <fieldset>

                <button type="button" id="button_send" class="bluebtn fltright" onclick="sendMessage()">

                    Send Message

                </button>

                </fieldset>

            {{ Form::close() }}

            </div>

        </div>
    {{ HTML::script('/js/jquery-1.11.0.js'); }}
    {{ HTML::script('/fw/jquery-ui-1.11.1.custom/jquery-ui.min.js'); }}
    <!-- Bootstrap Core JavaScript -->
    {{ HTML::script('js/bootstrap.min.js'); }}
    <!-- CHOSEN SELCT BOX -->
    {{ HTML::script('fw/ckeditor/ckeditor.js'); }}

    {{ HTML::script('fw/fancybox/source/jquery.fancybox.pack.js?v=2.1.5'); }}
    {{ HTML::script('fw/fullcalendar/fullcalendar.min.js'); }}
    {{ HTML::script('fw/datapicker/jquery.ui.timepicker.js'); }}

    {{ HTML::script('fw/lightbox/js/lightbox.js'); }}
    {{ HTML::script('js/widgets.js'); }}
    <!-- CHOSEN SELCT BOX -->
    {{ HTML::script('fw/chosen_v1/chosen.jquery.js'); }}
    {{ HTML::script('fw/chosen_v1/docsupport/prism.js'); }}
    {{ HTML::script('js/global.js'); }}

    <script>



     $(function() {
        function log( message ) {
            $( "<div>" ).text( message ).prependTo( "#log" );
            $( "#log" ).scrollTop( 0 );
        }

        $( "#searchMessage" ).autocomplete({
                source: "/widgets/friends/suggest",
                minLength: 2,
                response: function(event,ui){
                     if (ui.content.length === 0) {
                        $("#results").text("No results found");
                    }
                },
                select: function( event, ui ) {
                    $( "#searchMessage" ).val( ui.item.firstName+" "+ui.item.lastName );

                    $( "#friend" ).val( ui.item.followingId );
                    return false;
                }
        }) .autocomplete( "instance" )._renderItem = function( ul, item ) {
            $("#results").text("");
            var image = "/img/holder.png";
            if(item.thumb != null){
                image = "/"+item.thumb;
            }
            return $( "<li style='cursor:pointer' class='clientinfo marginleftnone clearfix'>" )
            .append( "<a class='image fltleft'><img width:45; height:45; src='"+image+"'/></a><div class='detail'>" + item.firstName + "<br>" + item.lastName + "</div>")
            .appendTo( ul );
        };
    });


    function sendMessage(){
        $.ajax(
            {
                url :"/widgets/messages/addEdit",
                type: "POST",
                data: { message:$("#message").val(),friend:$("#friend").val() },
                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    parent.$.fancybox.close();
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function() {
                        if(jqXHR.responseText != ""){
                            errorMessage(jqXHR.responseText);
                        }else {
                            
                        }
                        
                    }
                }
            });

    }


                
    </script>
        </body>
</html>