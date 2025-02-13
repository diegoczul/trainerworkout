@extends("layouts.emails")

@section("content")
		<?php if($user){ $user = unserialize($user); 
		}; ?> 
<!-- 		<h1>Bienvenue sur Trainer Workout {{ $user->firstName }} {{ $user->lastName }}</h1>
        <p class="lead">Nous savons que vous vous êtes connecté avec Facebook. Nous sommes heureux que vous ayez choisi notre système de connexion sécurisée (API). Voici votre nom d'utilisateur et mot de passe dans la mesure où vous avez besoin de vous connecter manuellement avec vos identifiants</p>
        <p>Nom d'utilisateur: {{ $user->email }} Password: {{ $password }}</p> -->
         <tr>
                            <td align="center" valign="top" id="mainActionContainer" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #369AD8;padding: 80px 40px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="520" id="mainActionBlock" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                    <tr>
                                        <td colspan="3" align="left" valign="top" width="520" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                            <h1 class="introMessage" style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 44px;font-weight: 100;line-height: 115%;text-align: left;padding-bottom: 40px;">Allo,</h1>
                                        </td>
                                    </tr>

                                    <tr>
                                        <!-- <td class="quote1"><img src="http://www.beta.trainerworkout.com/img/newsletter/quote1.png"></td> -->
                                        <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><h4 class="friendMessage" style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 26px;font-weight: 300;line-height: 115%;text-align: center;word-break: normal;">Merci d'avoir choisi Trainer Workout {{ $user->firstName }}</h4></td>
                                        <!-- <td class="quote2"><img src="http://www.beta.trainerworkout.com/img/newsletter/quote2.png"></td> -->
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
                                        <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="listItem" style="margin: 10px 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">Félicitation pour avoir créer votre compte Trainer Workout avec Facebook Connect. Voici votre nom d'utilisateur et votre mot de passe en cas ou voulez vous connectez avec de cette manière.</p></td>
                                    </tr>
                                    <tr>
                                        <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><br></td>
                                    </tr>
                                    <tr>
                                        <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="listItem" style="margin: 10px 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">Compte utilisateur: {{ $user->email }}</p></td>
                                    </tr>
                                    <tr>
                                        <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="listItem" style="margin: 10px 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">Mot de passe: {{ $password }}</p></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- End of Shared workout -->
@endsection