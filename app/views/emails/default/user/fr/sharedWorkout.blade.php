@extends("layouts.emails")

@section("content")
                <?php $friendName = ""; ?>
                <?php if(isset($toUser)){ $toUser = unserialize($toUser); } ?> 
                <?php if(isset($fromUser)){ $fromUser = unserialize($fromUser); } ?> 
                <?php if(isset($fromUser)){ $friendName = $fromUser->firstName." ".$fromUser->lastName; } ?> 
                <?php if(isset($sharing)){ $sharing = unserialize($sharing); } ?> 
                <?php if(isset($invite)){ $invite = unserialize($invite); } ?> 

<!-- Beign of message from Trainer -->
<tr>
    <td align="center" valign="top" id="mainActionContainer" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #369AD8;padding: 80px 40px;">
        <table border="0" cellpadding="0" cellspacing="0" width="520" id="mainActionBlock" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
            <tr>
                <td colspan="3" align="left" valign="top" width="520" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                    <h1 class="introMessage" style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 44px;font-weight: 100;line-height: 115%;text-align: left;padding-bottom: 40px;">Allô {{ isset($toUser) ? $toUser->firstName : " " }},</h1>
                </td>
            </tr>
            @if($comments != "")
            <tr>
                <td colspan="3" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><h4 class="messageFromFriend" style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #fff;font-size: 26px;font-weight: 100;line-height: 115%;text-align: center;padding-bottom: 10px;font-style: italic;">Message de {{ $friendName }}:</h4></td>
            </tr>
            <tr>
                <td class="quote1" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;width: 20px;vertical-align: top;padding-right: 5px;"><img src="{{ URL::secure("/img/newsletter/quote1.png") }}" style="border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;"></td>
                <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><h4 class="friendMessage" style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 26px;font-weight: 300;line-height: 115%;text-align: center;">{{{ $comments }}}</h4></td>
                <td class="quote2" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;width: 20px;vertical-align: bottom;padding-left: 5px;"><img src="{{ URL::secure("/img/newsletter/quote2.png") }}" style="border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;"></td>
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
                <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p style="margin: 1em 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">Votre entraîneur, {{ $friendName }}, a partagé un programme d’entraînement avec vous.</p></td>
            </tr>
            <tr>
                <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="actionButton" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <tr>
                            <td align="center" valign="middle" class="actionButtonContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;padding: 20px;">
                                <a href="{{  URL::secure(Lang::get('routes./Share/Workout/').$sharing->access_link) }}" target="_blank" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;background: #FBB147;display: inline-block;border-radius: 2px;color: #fff;padding: 10 18;text-decoration: none; border-bottom: solid 2px #EB9561; box-shadow: 0 2px 0 #EB9561; font-size: 18px;">Votre Nouveau Workout</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p style="margin: 1em 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">Vous pouvez aussi accéder à vos entraînements <a class="hereLink" href="{{  URL::secure(Lang::get('routes./Share/Workout/').$sharing->access_link) }}" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">ici</a> ou en copiant le lien suivant dans votre navigateur préféré.</p></td>
            </tr>
            <tr>
                <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="link" style="margin: 1em 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;text-decoration: underline;word-break: break-all;">{{  URL::secure(Lang::get('routes./Share/Workout/').$sharing->access_link) }}</p></td>
            </tr>
        </table>
    </td>
</tr>
<!-- End of Shared workout -->


@endsection