<?php $counter = 0; ?>
 @if ($sales->count() > 0)
  <div class="sessionsale">
          <table cellpadding="0" cellspacing="0" width="100%" class="border-radius">
            <tbody>
              <tr>
                <th>Total Sales</th>
                <th>Units Sold</th>
                <th>$ / Workout</th>
                <th>Best Seller</th>
              </tr>
                @foreach ($sales as $sale)
  

  
        <tr>    
             <td width="25%">$</td>
                <td width="25%"></td>
                <td width="25%">$</td>
                <td width="25%"></td>
</tr>
</tbody>
</table>
    <?php $counter ++ ;?>
@endforeach




 @else
    {{ Messages::showEmptyMessage("NoSales") }}
@endif

</div>

@if($total > $sales->count())
<div class="clearfix"></div>
    <div class="btmbuttonholder">
                <div class="clearfix"></div>
                    <span class="hrborder"></span>
                    <a href="javascript:void(0)" onclick="callWidget('w_workoutSales',{{ $sessions->count() }},null,$(this))" class="greybtn">More Sales</a>
                </div>
@endif

