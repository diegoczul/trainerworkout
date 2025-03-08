@extends("layouts.emails")
@section("content")
    <tr>
        <td align="center" valign="top" id="mainActionContainer" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #369AD8;padding: 80px 40px;">
            <table border="0" cellpadding="0" cellspacing="0" width="520" id="mainActionBlock" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                <tr>
                    <td colspan="3" align="left" valign="top" width="520" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <h1 class="introMessage" style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 44px;font-weight: 100;line-height: 115%;text-align: left;padding-bottom: 40px;">Hello,</h1>
                    </td>
                </tr>
                <tr>
                    <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><h4 class="friendMessage" style="margin: 0;padding: 0;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 26px;font-weight: 300;line-height: 115%;text-align: center;word-break: normal;">Welcome to Trainer Workout {{ $user->firstName }} {{ $user->lastName }}</h4></td>
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
                    <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="listItem" style="margin: 10px 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">Congratulation on creating your Trainer Workout account with Facebook Connect. Here is your user name and password in case you want to login with your credentials.</p></td>
                </tr>
                <tr>
                    <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><br></td>
                </tr>
                <tr>
                    <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="listItem" style="margin: 10px 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">Username: {{ $user->email }}</p></td>
                </tr>
                <tr>
                    <td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><p class="listItem" style="margin: 10px 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family: &quot;Avenir&quot;, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;color: #2C3E50;font-size: 20px;font-weight: 200;line-height: 115%;text-align: justify;">Password: {{ $password }}</p></td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- End of Shared workout -->
@endsection