 @if ($users->count() > 0)


 <?php $i = 0; ?>
  @foreach ($users as $user)
                  <div class="friend-box">
                    <div class="friendimage">
                   
                       <a href="/{{ Auth::user()->userType }}/{{ $user->getURL() }}" > <img src="/{{ Helper::image($user->thumb)}}" alt="friends"></a>
                    </div>
                    <div class="frienddetails">
                      <h4>{{ $user->firstName }}
                                            </br>{{ $user->lastName }}
                                     </h4>
                      @if(Friends::checkFollower($user->id))
                          <a style="font-size:10px" href="javascript:void(0)" onClick="deleteFriends('{{ $user->id }}',$(this)); return false;" >UnFollow</a>
                      @else
                          <a class="bluebtn" href="javascript:void(0)" onClick="followFriend('{{ $user->id }}',$(this)); return false;" >Follow</a>
                      @endif               
                      
                    </div>
                  </div>
                

              
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
                         var preLoad = showLoadWithElement(obj);
                         $.ajax(
                            {
                                url : "/widgets/friends/addEdit",
                                type: "POST",
                                data: {
                                          followingId:id
                                },
                                success:function(data, textStatus, jqXHR) 
                                {
                                    restoreLoader(preLoad);
                                    successMessage(data);
                                    widgetsToReload.push("w_friends_full");
                                    refreshWidgets();
                                },
                                error: function(jqXHR, textStatus, errorThrown) 
                                {
                                    restoreLoader(preLoad);
                                    errorMessage(jqXHR.responseText);
                                },
                            });
                    }

                    </script>






@else
    {{ Messages::showEmptyMessage("NoFriendsFound") }}
@endif

@if($total > $users->count())
<div class="clearfix"></div>
    <div class="btmbuttonholder">
                <div class="clearfix"></div>
                    <span class="hrborder"></span>
                    <a href="javascript:void(0)" onclick="moreFriends('w_friends_full',{{ $users->count() }},null,$(this))" class="greybtn">More Search Results</a>
                </div>
@endif


