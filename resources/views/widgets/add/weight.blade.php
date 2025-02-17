@php
    use App\Http\Libraries\Helper;
@endphp
{{ Form::open(array('url' => '/widgets/weight/addEdit/')) }}
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formdata" style="margin-bottom:30px;">
    <tr>
        <td width="40%">
            <input style="width:80px; position: relative; z-index: 100000" type="text" name="dateRecord" id="calendarWeight" class="datepickerPast inputbox-small" placeholder="Date" value=""/>
        </td>
        <td width="60%">
            <input type="text" name="weight" id="txt-weight" value="" class="inputbox-small" placeholder="Weight" maxlength="7" style="width:70px">
        </td>
    <tr>
        <td colspan="2">
            <label> Pounds <input type="radio" name="type" id="type" value="pounds" checked="checked"></label>
            <label> Kilograms <input type="radio" name="type" id="type" value="kilograms">
            </label>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="alignright">
            <input type="submit" class="bluebtn  ajaxSave{{ Helper::getTypeOfCall($user->id) }}" value="Save" widget='w_weights'>{{ Form::hidden("userId",$user->id) }}
        </td>
    </tr>
</table>
{{Form::close() }}
