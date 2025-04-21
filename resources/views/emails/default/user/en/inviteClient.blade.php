@extends("layouts.emails")

@section("content")
    <?php if($user){ $user = unserialize($user); } ?>
    <?php if($fake){ $fake = unserialize($fake); } ?>
    <?php if($invite){ $invite = unserialize($invite); } ?>
    <!-- Beign of message from Trainer -->
    <tr>
        <td align="center" valign="top" id="mainActionContainer" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #369AD8;padding: 80px 40px;">
            <table border="0" cellpadding="0" cellspacing="0" width="520" id="mainActionBlock" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                <tr>
                    <td colspan="3" align="left" valign="top" width="520" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <h1 class="introMessage" style="margin: 0;padding: 0;font-family: Avenir, Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 44px;font-weight: 100;line-height: 115%;text-align: left;padding-bottom: 40px;"> Hello,</h1>
                    </td>
                </tr>
                <tr style="width:100%;">
                    <td style="width:100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><h4 class="friendMessage" style="margin: 0;padding: 0;font-family: Avenir, Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 26px;font-weight: 300;line-height: 115%;text-align: center;word-break: normal;">{{ $user->firstName }} {{ $user->lastName }} is inviting you to join Trainer Workout!</h4></td>
                </tr>
                @if($comments != "")
                    <tr>
                        <td colspan="3" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><h4 class="messageFromFriend" style="margin: 0;padding: 0;font-family: Avenir, Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;color: #fff;font-size: 26px;font-weight: 100;line-height: 115%;text-align: center;padding-bottom: 10px;font-style: italic;">message from {{ $user->firstName }} {{ $user->lastName }}:</h4></td>
                    </tr>
                    <tr>
                        <td class="quote1" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;width: 20px;vertical-align: top;padding-right: 5px;"><img src="{{ URL::secure("assets/img/newsletter/quote1.png") }}" style="border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;"></td>
                        <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><h4 class="friendMessage" style="margin: 0;padding: 0;font-family: Avenir, Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 26px;font-weight: 300;line-height: 115%;text-align: center;">{{{ $comments }}}</h4></td>
                        <td class="quote2" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;width: 20px;vertical-align: bottom;padding-left: 5px;"><img src="{{ URL::secure("assets/img/newsletter/quote2.png") }}" style=" border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;"></td>
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
                        <p style="margin: 1em 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: Avenir, Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">{{ $user->firstName }} will be sharing workouts for you via Trainer Workout. To always have access to them create your free account now.</p>
                    </td>
                </tr>
                <tr>
                    <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="actionButton" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                            <tr>
                                <td align="center" valign="middle" class="actionButtonContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;padding: 20px;">
                                    <a href="{{ route('TraineeSignUp',['key' => $invite->key]) }}" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: Avenir, Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;background: #FBB147;display: inline-block;border-radius: 2;color: #fff;padding: 10px 18px;text-decoration: none;font-size: 18px;border-bottom: solid 2px #EB9561;">Create Free Account</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="listItem" style="margin: 10px 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: Avenir, Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;"> Click <a class="hereLink" href="{{ route('TraineeSignUp',['key' => $invite->key]) }}" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: Avenir, Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">here</a> or the button above to create your account. You can also copy & paste the following link in your favorite browser.</p></td>
                </tr>
                <tr>
                    <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="link" style="margin: 1em 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: Avenir, Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;text-decoration: underline;word-break: break-all;">{{ route('TraineeSignUp',['key' => $invite->key]) }}</p></td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- End of Shared workout -->
@endsection