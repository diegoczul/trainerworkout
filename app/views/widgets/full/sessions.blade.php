
@if($permissions["view"]) 
<?php $counter = 0; ?>
 @if ($sessions->count() > 0)
                @foreach ($sessions as $session)
  <!--    'bluebg : 'orangebg') ?> -->
     <div class="sessionblock  clearfix {{ ($counter % 2 == 0) ? "bluebg" : "orangebg" }} showDelete">
       <a href="javascript:void(0)" onClick="deleteSession({{ $session->id }},$(this)); arguments[0].stopPropagation(); return false;" class="deleteicon2"></a>
        <div class="fltright"> <a href="/Store/addToCart/{{ $session->id }}/Session" class="bluebtn alignright">Book this session</a> <span class="pricetag">{{ $session->price }}$</span></div>
        <h3>{{ $session->name }}</h3>
        <table>
          <tbody>
            <tr>
              <td width="40%"># of Sessions</td>
              <td width="60%">{{ $session->numberOfSessions }}</td>
            </tr>
            <tr>
              <td>Time / Sessions </td>
              <td>{{ $session->timePerSession }}</td>
            </tr>
            <tr>
              <td>Description</td>
              <td class="smltext">{{ $session->description }}</td>
            </tr>
           
          </tbody>
        </table><a href="javascript:void(0)" onclick="deleteSession({{ $session->id }},$(this))" class="trashicon"></a> </div>

        <?php $counter ++ ;?>
@endforeach



    <script>

    function deleteSession(id,obj){
         $.ajax(
            {
                url : "/widgets/sessions/"+id,
                type: "DELETE",

                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    widgetsToReload.push("w_sessions");
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
    {{ Messages::showEmptyMessage("SessionsEmpty",$permissions["self"]) }}
@endif

             
@if($total > $sessions->count())
<div class="clearfix"></div>
    <div class="btmbuttonholder">
                <div class="clearfix"></div>
                    <span class="hrborder"></span>
                    <a href="javascript:void(0)" onclick="callWidget('w_sessions',{{ $sessions->count() }},null,$(this))" class="greybtn">More Sessions</a>
                </div>
@endif

@else
    {{ Messages::showEmptyMessage("NoPermissions") }}
@endif


