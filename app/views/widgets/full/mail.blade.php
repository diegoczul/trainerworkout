<html>
<head>
    {{ HTML::style('css/innerStyles.css') }}
    {{ HTML::style('fw/jquery-ui-1.11.1.custom/jquery-ui.min.css'); }}
    {{ HTML::style('fw/chosen_v1/chosen.css'); }}
</head>
<body>
<div class="popupholder shadow">

<h2>

	<a href="javascript:void(0)" onclick="parent.$.fancybox.close();" class="buttonbig fltright">Close button</a>

	<a class="bluebtn fltright fancybox popups" href="/Trainee/ComposeMail">New Message</a>

	Message

</h2>

@foreach($messages as $message)
<a class='fancy fancybox messageHover' href="/widgets/messages/dialog/{{ $message->user }}">
	<div class="messagelisting clearfix" >

	<div class="messageimage"><img src="/{{ Helper::image("") }}"/></div>

	<div class="message">

	<h5  class=""><strong>{{ $message->firstName." ".$message->lastName }}</strong></h5>

	{{ $message->message }}

	</div>

	<div class="msgdate"> {{ Helper::datetime($message->created_at) }}</div>

	<span href='#' onclick='removeDialogMessage({{ $message->id }},$(this)); return false;' message='' class="icon-delete"></span>

	</div>
</a>
@endforeach
</div>
</body>
</html>