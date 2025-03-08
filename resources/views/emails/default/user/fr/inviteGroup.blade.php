@extends("layouts.emails")

@section("content")
    <?php if($user){ $user = unserialize($user); }; ?>

    <!-- Beign of message from Trainer -->
    <tr>
        <td align="center" valign="top" id="mainActionContainer" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #369AD8;padding: 80px 40px;">
            <table border="0" cellpadding="0" cellspacing="0" width="520" id="mainActionBlock" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                <tr>
                    <td colspan="3" align="left" valign="top" width="520" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <h1 class="introMessage" style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 44px;font-weight: 100;line-height: 115%;text-align: left;padding-bottom: 40px;">Allô,</h1>
                    </td>
                </tr>
                <tr>
                    <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><h4 class="friendMessage" style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 26px;font-weight: 300;line-height: 115%;text-align: center;word-break: normal;">{{ $authorFirstName }} {{ $authorLastName }} vous invite a joindre votre équipe sur Trainer Workout!</h4></td>
                </tr>
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
                        <p style="margin: 1em 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">Trainer Workout vous aide à créer et partager vos programmes d'entraînement avec vos clients. Vous pourrai aussi suivre le progrès de vos clients.</p>
                    </td>
                </tr>
                <tr>
                    <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="actionButton" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                            <tr>
                                <td align="center" valign="middle" class="actionButtonContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;padding: 20px;">
                                    <a href="{{ route('confirmEmail',['token' => $user->token]) }}" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;background: #FBB147;display: inline-block;border-radius: 2;color: #fff;padding: 10px 18px;text-decoration: none;font-size: 18px;border-bottom: solid 2px #EB9561;">Créez votre compte</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="listItem" style="margin: 10px 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">Cliquez <a class="hereLink" href="{{ route('confirmEmail',['token' => $user->token]) }}" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">ici</a> ou sur le bouton ci-haut afin de créer votre compte. Vous pouvez également copier & coller le lien suivant dans votre navigateur préféré.</p></td>
                </tr>
                <tr>
                    <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="link" style="margin: 1em 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;text-decoration: underline;word-break: break-all;">{{ route('confirmEmail',['token' => $user->token]) }}</p></td>
                </tr>
                @if($password != "")
                    <tr>
                        <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><br></td>
                    </tr>
                    <tr>
                        <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="listItem" style="margin: 10px 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">Compte d'utilisateur: {{ $user->email }}</p></td>
                    </tr>
                    <tr>
                        <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="listItem" style="margin: 10px 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">Mot de passe: {{ $password }}</p></td>
                    </tr>
                @endif
            </table>
        </td>
    </tr>
    <!-- End of Shared workout -->

@endsection