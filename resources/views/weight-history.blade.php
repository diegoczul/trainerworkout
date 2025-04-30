@php $globalIndex = 1; @endphp
<table style="border: 1px solid #ffffff;">
    <thead>
        <tr>
            <th>Set</th>
            <th>Weight</th>
            <th>Weight</th>
            <th>Date/Time.</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($originalSet??[] as $row)
        <tr>
            <td>{{$row['number']}}</td>
            <td>{{$row['weight']??0}}</td>
            @if(isset($row['date']) && !empty($row['date']))
                <td>{{\Illuminate\Support\Carbon::make($row['date'])}}</td>
            @else
                <td>N/A</td>
            @endif
            <td> - </td>
        </tr>
        @php $globalIndex ++; @endphp
    @endforeach

    @foreach($setsHistory??[] as $row)
        <tr>
            <td>{{$row['template_set']['number']??0}}</td>
            <td>{{$row['weight']??0}}</td>
            @if(isset($row['date']) && !empty($row['date']))
                <td>{{\Illuminate\Support\Carbon::make($row['date'])}}</td>
            @else
                <td>N/A</td>
            @endif
            <td><a href="javascript:void(0);" style="color: #000" onclick="removeSetHistory(this,{{$row['id']}})">Delete</a></td>
        </tr>
        @php $globalIndex ++; @endphp
    @endforeach
    </tbody>
</table>