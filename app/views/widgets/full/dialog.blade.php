
<html>
<head>
    {{ HTML::style('css/innerStyles.css') }}
    {{ HTML::style('fw/jquery-ui-1.11.1.custom/jquery-ui.min.css'); }}
    {{ HTML::style('fw/chosen_v1/chosen.css'); }}
    {{ HTML::style('fw/autocomplete/foxycomplete.css'); }}
</head>
<body>
<div class="systemMessages"></div>

<div class="fb_popup-compose whitepopup" style="width:1040px;">

    
    <div class="popupholder shadow">

          <h2>

                <a href="javascript:void(0)" onclick="parent.$.fancybox.close();" class="buttonbig fltright">Close button</a>
                
                
                <a class="bluebtn fltright fancybox" href="/Trainee/Mail/">Back to Inbox</a>

                Messages with <span class="italic">{{ $friend->firstName }}</span>

            </h2>
            

                    <div class="conversation" id="conversation">
                    @foreach ($messages as $message)
                                
                        
                                <div class="conversationlist {{ ($message->fromId == Auth::user()->id ? 'even' : 'odd' ) }} clearfix">
                                  <?php
                                    $image = ($message->fromId == Auth::user()->id ? Auth::user()->thumb : $message->fromUser()->first()->thumb );
                                  ?>
                                  <div class="messageimage"><img src="/{{ Helper::image($image) }}" alt="message image"></div>

                                  <div class="message border-radius shadow">

                                      <span class="pointer"></span>
                                    
                                      {{ $message->message }}<br />
                                      <span class="datetime">{{ Helper::datetime($message->created_at) }}</span>

                                  </div>

                                </div>
                    @endforeach
           

            

            </div>
            
            <div class="messageformbox">

            {{ Form::open(array('url' => '/widgets/messages/addEdit/')); }}

                <fieldset>
                <input type="hidden" id="friend" name="friend" value="{{ $friend->id }}">
                <textarea name="message" id="message" type="text" class="border-radius"></textarea>

                </fieldset>

                <fieldset>

                <button type="button" id="button_send" class="bluebtn fltright" onclick="sendMessage()">

                    Send Message

                </button>

                </fieldset>

            {{ Form::close() }}

            </div>

        </div>
        </div>
    {{ HTML::script('/js/jquery-1.11.0.js'); }}
    {{ HTML::script('/fw/jquery-ui-1.11.1.custom/jquery-ui.min.js'); }}
    {{ HTML::script('js/global.js'); }}

    <script>

    $(document).ready(function(){
        $('#conversation').scrollTop($('#conversation')[0].scrollHeight);
        readUserMessages();
    });

     $(function() {
        function log( message ) {
            $( "<div>" ).text( message ).prependTo( "#log" );
            $( "#log" ).scrollTop( 0 );
        }

        $( "#searchMessage" ).autocomplete({
                source: "/widgets/friends/suggest",
                minLength: 2,
                select: function( event, ui ) {
                    $( "#searchMessage" ).val( ui.item.firstName+" "+ui.item.lastName );

                    $( "#friend" ).val( ui.item.followingId );
                    return false;
                }
        }) .autocomplete( "instance" )._renderItem = function( ul, item ) {
            var image = "/img/default.gif";
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

    function readUserMessages(){
        $.ajax(
            {
                url :"/widgets/messages/readUserMessages",
                type: "POST",
                data: { user:{{ $friend->id }} },
                success:function(data, textStatus, jqXHR) 
                {

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