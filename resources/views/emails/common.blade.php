<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
    <head>
        <title></title>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta name="x-apple-disable-message-reformatting" />
        <!--[if !mso]><!-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!--<![endif]--><!--[if gte mso 9]>
        <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml>
        <![endif]-->
        <style> * { text-size-adjust: 100%; -ms-text-size-adjust: 100%; -moz-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; } html { height: 100%; width: 100%;} body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; mso-line-height-rule: exactly; color:#333333; font-family:'PingFang TC','微軟正黑體','Microsoft JhengHei','Helvetica Neue',Helvetica,Arial,sans-serif;} div[style*="margin: 16px 0"] { margin: 0 !important; } table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; } img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; } a {color: #333333;} h1 {font-size:20px;font-weight:bold;line-height:1.2} h2 {font-size:18px;font-weight:bold;line-height:1.2} h3 {font-size:16px;font-weight:bold;line-height:1.2} .hr {border-bottom:1px solid #000;} .text_link {font-size:14px;line-height:1.6}</style>
        <!--[if gte mso 9]>
        <style type="text/css"> li { text-indent: -1em; } table td { border-collapse: collapse; } </style>
        <![endif]-->
        <style> @media only screen and (max-width:800px) { .cBlock--spacingLR { padding-left: 16px !important; padding-right: 16px !important; } .img_block { width: 100% !important; } } </style>
    </head>
    <body class="body cLayout--bgColor" style="background-color:#ffffff; margin:0;width:100%;">
        <table class="layout__wrapper" align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="layout__row">
            <td class="layout__column cLayout--bgColor" align="center" width="100%" style="padding-left:10px;padding-right:10px;padding-top:40px;padding-bottom:40px" bgcolor="#ffffff">
            <!--[if !mso]><!---->
            <div style="margin:0 auto;width:100%;max-width:800px;">
                <!-- <![endif]--><!--[if mso | IE]>
                <table role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="800" style="margin:0 auto;width:100%;max-width:800px;">
                <tr>
                    <td>
                    <![endif]-->
                    <!-- Block: Start Text -->
                    <table class="block-inner" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="block-inner__content cBlock--spacingLR " align="left" valign="top" width="100%" bgcolor="#f7f7f7" style="padding:20px 30px;">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <a href="https://www.yuanature.tw/">
                                            <img src="https://www.yuanature.tw/img/icons/logo.png" width="100px">
                                        </a>
                                    </td>
                                </tr>
                                    <table width="80%" border="0" cellspacing="6" cellpadding="6" bgcolor="#fff" align="center" style="font-size:18px;">
                                        <tr>
                                            <td align="center">
                                                <h1>{{ @$title }}</h1>
                                            </td>
                                        </tr>

                                        @if(@$email_tpl == 'contact') @include('emails.contact')
                                        @else @include('emails.user')
                                        @endif

                                        <!-- Block: Start Button -->
                                        @if(@$btn_txt != '' && @$btn_url != '') 
                                        <table border="0" cellspacing="0" cellpadding="0" align="center">
                                            <tbody>
                                                <tr>
                                                    <!--[if mso | IE]>
                                                    <td align="center" bgcolor="#005da0" style="color:#ffffff;padding-top:7px;padding-bottom:7px;padding-left:60px;padding-right:60px">
                                                        <![endif]--><!--[if !mso]><!---->
                                                    <td align="center" bgcolor="#005da0" style="border-radius:4px">
                                                        <!-- <![endif]--><a class="dnd-button" href="{{ @$btn_url }}" target="_blank" style="color:#ffffff;border-radius:4px;display:inline-block;text-decoration:none;font-size:16px;font-weight:bold;letter-spacing:1px;padding-top:7px;padding-bottom:7px;padding-left:60px;padding-right:60px"><span class="a__text" style="color:#ffffff;text-decoration:none;font-family:'PingFang TC','微軟正黑體','Microsoft JhengHei','Helvetica Neue',Helvetica,Arial,sans-serif">{{ @$btn_txt }}</span></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @endif
                                        <!-- Block: End Button -->
                                        <tr>
                                            <td height="20"></td>
                                        </tr>
                                        <tr>
                                            <td class="text_link">
                                                原生學網站：https://www.yuanature.tw/ <br>
                                                客服專線：07-9721992 <br>
                                                客服信箱：Service@yuanture.tw <br>
                                            </td>
                                        </tr>
                                    </table>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <!-- Block: End Text -->

                    <!--[if mso | IE]>
                    </td>
                </tr>
                </table>
                <![endif]--><!--[if !mso]><!---->
            </div>
            <!-- <![endif]-->
            </td>
        </tr>
        </table>
    </body>
</html>