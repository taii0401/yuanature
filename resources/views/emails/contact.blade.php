<table width="80%" border="1" cellspacing="0" cellpadding="3" valign="top" align="center">
    <tr>
        <td>姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：</td>
        <td align="left">{{ @$txt_name }}</td>
    </tr>
    <tr>
        <td>電子郵件：</td>
        <td align="left">{{ @$txt_email }}</td>
    </tr>
    <tr>
        <td>聯絡電話：</td>
        <td align="left">{{ @$txt_phone }}</td>
    </tr>
    <tr>
        <td>問題類型：</td>
        <td align="left">{{ @$txt_type }}</td>
    </tr>
    <tr>
        <td align="left" valign="top">訊息內容：</td>
        <td>{!! @$txt_content !!}</td>
    </tr>
</table>