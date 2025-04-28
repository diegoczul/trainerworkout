@extends('layouts.emailsYmca')

@section('content')
    <?php if ($user) {
        $user = unserialize($user);
    } ?>
    <?php if ($fake) {
        $fake = unserialize($fake);
    } ?>
    <?php if ($invite) {
        $invite = unserialize($invite);
    } ?>

    <!-- <h1>Bienvenue sur Trainer Workout!</h1>
      
      <p>Vous avez été invité à joindre {{ $user->firstName }} {{ $user->lastName }} - {{ $user->email }} sur Trainer Workout.</p>
      

      <p>Vous pouvez consulter tous vos programmes d’entraînement en vous créant, en premier lieu, un compte tout à fait gratuitement ici: <a href="{{ URL::secure(Lang::get('/TraineeSignUp/', [], 'fr') . $invite->key) }}"> {{ URL::secure(Lang::get('/TraineeSignUp/', [], 'fr') . $invite->key) }}</a></p> -->

    <!-- Beign of message from Trainer -->
    <tr>
        <td align="center" valign="top" id="mainActionContainer"
            style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EE3D43;padding: 80px 40px;">
            <table border="0" cellpadding="0" cellspacing="0" width="520" id="mainActionBlock"
                style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                <tr>
                    <td colspan="3" align="left" valign="top" width="520"
                        style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <h1 class="introMessage"
                            style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #fff;font-size: 44px;font-weight: 100;line-height: 115%;text-align: left;padding-bottom: 40px;">
                            Allô,</h1>
                    </td>
                </tr>
                <!-- <tr>
                                            <td colspan="3"><h4 class="messageFromFriend">{{ $user->firstName }} {{ $user->lastName }} is inviting you to join YMCA!</h4></td>
                                        </tr> -->
                <tr>
                    <!-- <td class="quote1"><img src="http://www.beta.trainer-workout.com/img/newsletter/quote1.png"></td> -->
                    <td
                        style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <h4 class="friendMessage"
                            style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #4A4A4A;font-size: 26px;font-weight: 300;line-height: 115%;text-align: center;word-break: normal;">
                            {{ $user->firstName }} {{ $user->lastName }} vous invite à joindre YMCA!</h4>
                    </td>
                    <!-- <td class="quote2"><img src="http://www.beta.trainer-workout.com/img/newsletter/quote2.png"></td> -->
                </tr>
            </table>
        </td>
    </tr>



    <!-- Begin of shared Workout -->
    <tr>
        <td align="center" valign="top" id="contentContainer"
            style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;padding: 60px 40px;">
            <table border="0" cellpadding="0" cellspacing="0" width="520" id="contentBlock"
                style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                <tr>
                    <td
                        style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <p
                            style="margin: 1em 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #4A4A4A;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">
                            {{ $user->firstName }} partagera vos programmes d'entraînement avec vous sur la plateforme YMCA.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="actionButton"
                            style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                            <tr>
                                <td align="center" valign="middle" class="actionButtonContent"
                                    style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;padding: 20px;">
                                    <a href="{{ URL::secure('/TraineeSignUp/' . $invite->key) }}"
                                        style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;background: #990124;display: inline-block;border-radius: 4px;color: #fff;padding: 10px 18px;text-decoration: none;font-size: 18px;border-bottom: solid 2px #560114;">Créez
                                        votre compte gratuitement</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td
                        style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <p class="listItem"
                            style="margin: 10px 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #4A4A4A;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">
                            Cliquez <a class="hereLink" href="{{ URL::secure('/TraineeSignUp/' . $invite->key) }}"
                                style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #4A4A4A;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">ici</a>
                            ou le bouton ci-haut pour créer votre compte. Vous pouvez aussi copier & coller le lien ci-bas
                            dans votre navigateur préféré.</p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <p class="link"
                            style="margin: 1em 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #4A4A4A;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;text-decoration: underline;word-break: break-all;">
                            {{ URL::secure('/TraineeSignUp/' . $invite->key) }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- End of Shared workout -->
@endsection
