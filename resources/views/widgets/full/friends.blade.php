 @if ($friends->count() > 0)
 <?php $i = 0; ?>
  @foreach ($friends as $friend)
    @if($friend->user)
                  <div class="friend-box">
                    <div class="friendimage">
                    
                      <a href="/{{ Auth::user()->userType }}/{{ $friend->getURL() }}" >  <img src="/{{ ($friend->user) ? Helper::image($friend->user->thumb) : Helper::image("")}}" alt="friends"></a>
                    </div>
                    <div class="frienddetails">
                      <h4>{{ $friend->user->firstName }}
                                            </br>{{ $friend->user->lastName }}
                                     </h4>
                      <a style="font-size:10px" href="javascript:void(0)" onClick="deleteFriends('{{ $friend->id }}',$(this)); return false;" >UnFollow</a>
                    </div>
                  </div>
    @endif

              
                @endforeach
<div class="clearfix"></div>
                    <script>

                    function deleteFriends(id,obj){
                         $.ajax(
                            {
                                url : "/widgets/friends/"+id,
                                type: "DELETE",

                                success:function(data, textStatus, jqXHR) 
                                {
                                    successMessage(data);
                                    widgetsToReload.push("w_friends_full");
                                    refreshWidgets();
                                },
                                error: function(jqXHR, textStatus, errorThrown) 
                                {
                                    errorMessage(jqXHR.responseText);
                                },
                            });
                    }

                   function followFriend(id,obj){
                         $.ajax(
                            {
                                url : "/widgets/friends/addEdit",
                                type: "POST",
                                data: {
                                          followingId:id
                                },
                                success:function(data, textStatus, jqXHR) 
                                {

                                    successMessage(data);
                                    widgetsToReload.push("w_friends_full");
                                    refreshWidgets();
                                },
                                error: function(jqXHR, textStatus, errorThrown) 
                                {
                                    errorMessage(jqXHR.responseText);
                                },
                            });
                    }

                    </script>

                    </script>



@else
    {{ Messages::showEmptyMessage("FriendsEmpty") }}
@endif

@if($total > $friends->count())
<div class="clearfix"></div>
    <div class="btmbuttonholder">
                <div class="clearfix"></div>
                    <span class="hrborder"></span>
                    <a href="javascript:void(0)" onclick="callWidget('w_friends_full',{{ $friends->count() }},null,$(this))" class="greybtn">More Friends</a>
                </div>
@endif
