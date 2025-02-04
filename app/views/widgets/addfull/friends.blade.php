{{ Form::open(array('url' => '/widgets/friends/addEdit/')); }}
<div style="padding:10px; margin-bottom:20px;">
      <form method="post" class="formholder">
        <fieldset>
          <input type="text" name="email" id="email" class="input" placeholder="Enter E-mail address" value="">
          <input type="text" name="name" id="name"  class="input" placeholder="Name" value="">
        </fieldset><br />
        <fieldset>
          <a href="#" onClick="AddFriendEmail($(this));" class="bluebtn" id="btn-add-friend-email">Invite for free</a>
          <a href="#" onClick="AddFriendName($(this));" class="bluebtn" id="btn-add-friend-name" style="margin-left: 15px;">Search</a>
        </fieldset>
      </form>
      </div>

      {{Form::close() }}

<script>
$(document).keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();
        searchForFriend();
       
    }
});

function AddFriendEmail(obj){
  var handler = $(obj);
  var preLoad;
  $.ajax(
          {
              url : '/Friends/Add',
              type: "POST",
              data: {
                  email: $("#email").val(),
                  name: $("#name").val()
                },
              beforeSend:function() 
              {
                                preLoad = showLoadWithElement(handler);
                    },
              success:function(data, textStatus, jqXHR) 
              {
                successMessage(data);
                upAndClearAdd();
                widgetsToReload.push("w_friends_full");
                refreshWidgets();
                return false;
              },
              complete:function() 
              {
                                hideLoadWithElement(preLoad);
                    },
              error: function(jqXHR, textStatus, errorThrown) 
              {
                errorMessage(jqXHR.responseText+errorThrown);
                return false;
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


function AddFriendName(obj){
  
  var handler = $(obj);
  var search = "";
  if($("#name").val() != ""){
    search = $("#name").val() ;
  }
  if($("#search").val() != ""){
    search = $("#search").val() ;
  }
  $.ajax(
    {
        url: '/Friends/Search',
        type: "POST",
        data: { search:search },
        success:function(data, textStatus, jqXHR) 
        {
          $("#w_friends_full").html(data);

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

function moreFriends(wid,pageSize){
  var handler = $(obj);
  $.ajax(
    {
        url: '/Friends/Search',
        type: "POST",
        data: { search:$("#name").val() },
        success:function(data, textStatus, jqXHR) 
        {
          $("#w_friends_full").html(data);

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
