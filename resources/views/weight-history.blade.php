@php
    $globalIndex = 1;
    function lbsToKg($pounds) {
        return number_format(round($pounds * 0.453592),2);
    }
@endphp
<table style="border: 1px solid #ffffff;">
    <thead>
        <tr>
            <th>Set</th>
            <th>Weight</th>
            <th>Date/Time.</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($originalSet??[] as $row)
        <tr>
            <td>{{$row['number']}}</td>
            <td>{{number_format($row['weight']??0,2)}}</td>
            @if(isset($row['date']) && !empty($row['date']))
                <td>{{\Illuminate\Support\Carbon::make($row['date'])->timezone($timezone??"UTC")}}</td>
            @else
                <td>N/A</td>
            @endif
            <td> - </td>
        </tr>
        @php $globalIndex ++; @endphp
    @endforeach

    @foreach($setsHistory??[] as $row)
        <tr>
            <td>{{$row['set']['number']??0}}</td>
            @if(isset($row['set']['units']) && $row['set']['units'] == 'Metric')
                <td>{{lbsToKg($row['weight']??0)}}</td>
            @else
                <td>{{number_format($row['weight']??0,2)}}</td>
            @endif
            @if(isset($row['date']) && !empty($row['date']))
                <td>{{\Illuminate\Support\Carbon::make($row['date'])->timezone($timezone??"UTC")}}</td>
            @else
                <td>N/A</td>
            @endif
            <td><a href="javascript:void(0);" style="color: #000" onclick="removeSetHistory(this,{{$row['id']}})">Delete</a></td>
        </tr>
        @php $globalIndex ++; @endphp
    @endforeach
    </tbody>
</table>