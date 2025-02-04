 @if($permissions["view"])  
 @if ($objectives->count() > 0)

                @foreach ($objectives as $objective)
                <?php
                                $now = new DateTime();
                                $ref = new DateTime($objective->created_at);
                                $output = "";
                                $diff = $now->diff($ref);
                                $days = $diff->d; 
                            ?>
                              <div class="objectives showDelete">
                              {{ $objective->objective }}
                              {{ ($objective->measureable != "") ? "</br>".$objective->measureable : "" }}
                    <div class="objectivedetails">
                    	<ul >
                        	<li>Start date  {{ Helper::date($objective->created_at) }}</li>
                            <li>End date  {{ Helper::date($objective->recordDate) }} </li>
                            
                            <li> {{ $days  }} days left 

                        <a href="javascript:void(0)" class="deleteicon2" onClick="return deleteObjective('{{$objective->id}}', $(this));" ></a>

                            </li>
                        </ul>
                    </div>
                    </div>
                @endforeach
                </div>



    <script>

    function deleteObjective(id,obj){
         $.ajax(
            {
                url : "/widgets/objectives/"+id,
                type: "DELETE",

                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    widgetsToReload.push("w_objectives_full");
                    refreshWidgets();
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    errorMessage(jqXHR.responseText);
                },
            });
    }

    </script>

 @else
    {{ Messages::showEmptyMessage("ObjectivesEmpty",$permissions["self"]) }}
@endif

@if($total > $objectives->count())
<div class="clearfix"></div>
    <div class="btmbuttonholder">
                <div class="clearfix"></div>
                    <span class="hrborder"></span>
                    <a href="javascript:void(0)" onclick="callWidget('w_objectives',{{ $objectives->count() }},null,$(this))" class="greybtn">More Objectives</a>
                </div>
@endif
@else
    {{ Messages::showEmptyMessage("NoPermissions") }}
@endif

