@extends("layouts.emails")

@section("content")
		<?php if($user){ $user = unserialize($user); } ?> 
		<?php if($fake){ $fake = unserialize($fake); } ?> 
		<?php if($invite){ $invite = unserialize($invite); } ?> 
<!-- 		<h1>You’re invited to Trainer Workout!</h1>
		<p>You've been invited to join your personal trainer {{ $user->firstName }} {{ $user->lastName }} - {{ $user->email }} on Trainer Workout.</p>
		<p>You can access your workout program(s) by creating a free account here: <a href="{{ URL::secure("/TraineeSignUp/".$invite->key) }}"> {{ URL::secure("/TraineeSignUp/".$invite->key) }}</a></p> -->

		<!-- Beign of message from Trainer -->
<tr>
    <td align="center" valign="top" id="mainActionContainer" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #369AD8;padding: 80px 40px;">
        <table border="0" cellpadding="0" cellspacing="0" width="520" id="mainActionBlock" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
            <tr>
                <td colspan="3" align="left" valign="top" width="520" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                    <h1 class="introMessage" style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 44px;font-weight: 100;line-height: 115%;text-align: left;padding-bottom: 40px;">Hello,</h1>
                </td>
            </tr>
           
            <tr style="width:100%;" >
                <!-- <td class="quote1"><img src="http://www.beta.trainerworkout.com/img/newsletter/quote1.png"></td> -->
                <td style="width:1 00%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><h4 class="friendMessage" style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 26px;font-weight: 300;line-height: 115%;text-align: center;word-break: normal;">{{ $user->firstName }} {{ $user->lastName }} is inviting you to join Trainer Workout!</h4></td>
                <!-- <td class="quote2"><img src="http://www.beta.trainerworkout.com/img/newsletter/quote2.png"></td> -->
            </tr>

            @if($comments != "")
            <tr>
                <td colspan="3" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><h4 class="messageFromFriend" style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #fff;font-size: 26px;font-weight: 100;line-height: 115%;text-align: center;padding-bottom: 10px;font-style: italic;">message from {{ $user->firstName }} {{ $user->lastName }}:</h4></td>
            </tr>
            <tr>   <!-- URL::secure("/img/newsletter/quote1.png"); -->
                <td class="quote1" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;width: 20px;vertical-align: top;padding-right: 5px;"><img src="{{ URL::secure("/img/newsletter/quote1.png") }}" style="border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;"></td>
                <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><h4 class="friendMessage" style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 26px;font-weight: 300;line-height: 115%;text-align: center;">{{{ $comments }}}</h4></td>
                <td class="quote2" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;width: 20px;vertical-align: bottom;padding-left: 5px;"><img src="{{ URL::secure("/img/newsletter/quote2.png") }} style="border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;"></td>
            </tr>
            @endif
        </table>
    </td>
</tr>
<!-- End of message from Trainer -->

                        <!-- Begin of shared Workout -->
                        <tr>
                            <td align="center" valign="top" id="contentContainer" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;padding: 60px 40px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="520" id="contentBlock" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                    <tr>
                                        <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                            <p style="margin: 1em 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">{{ $user->firstName }} will be sharing workouts for you via Trainer Workout. To always have access to them create your free account now.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="actionButton" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                <tr>
                                                    <td align="center" valign="middle" class="actionButtonContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;padding: 20px;">
                                                        <a href="{{ URL::secure('/TraineeSignUp/'.$invite->key) }}" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;background: #FBB147;display: inline-block;border-radius: 2;color: #fff;padding: 10px 18px;text-decoration: none;font-size: 18px;border-bottom: solid 2px #EB9561;">Create Free Account</a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="listItem" style="margin: 10px 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">Click <a class="hereLink" href=" {{ URL::secure("/TraineeSignUp/".$invite->key) }}" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">here</a>  or the button above to create your account. You can also copy & paste the following link in your favorite browser.</p></td>
                                    </tr>
                                    <tr>
                                        <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="link" style="margin: 1em 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;text-decoration: underline;word-break: break-all;">{{ URL::secure('/TraineeSignUp/'.$invite->key) }}</p></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- End of Shared workout -->
@endsection