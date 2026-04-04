<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
</head>
<body style="margin:0; padding:0; background:#f4f6f8; font-family: Arial;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:20px;">
<tr>
<td align="center">

<table width="600" style="background:#fff; border-radius:10px; padding:25px;">

    <!-- HEADER -->
    <tr>
        <td align="center">
            <h2 style="margin:0; color:#111;">
                Solution Sindicância
            </h2>
            <p style="color:#666; margin-top:5px;">
                Sistema de Solicitações
            </p>
        </td>
    </tr>

    <!-- TICKET -->
    <tr>
        <td align="center" style="padding:25px 0;">
            <div style="
                background:#2563eb;
                color:#fff;
                padding:18px 30px;
                border-radius:8px;
                font-size:22px;
                font-weight:bold;
            ">
                Ticket #{{ $solicitacao->id }}
            </div>
        </td>
    </tr>

    <!-- MENSAGEM USUÁRIO -->

    <tr>
        <td style="text-align:center; padding-bottom:20px;">
            <p style="font-size:15px; color:#333;">
                Sua solicitação foi recebida com sucesso ✅
            </p>

            <p style="color:#555;">
                Em breve entraremos em contato com você.
            </p>

            <p style="color:#777; font-size:14px;">
                Caso queira acompanhar ou falar sobre este assunto,
                utilize o número do ticket acima.
            </p>
        </td>
    </tr>


    <tr>
        <td>
            <table width="100%" cellpadding="8">

                <tr>
                    <td><strong>Assunto:</strong></td>
                    <td>{{ $solicitacao->assunto }}</td>
                </tr>

                <tr style="background:#f9fafb;">
                    <td><strong>Solicitação:</strong></td>
                    <td>{{ $solicitacao->solicitacao }}</td>
                </tr>



            </table>
        </td>
    </tr>


    <!-- FOOTER -->
    <tr>
        <td align="center" style="padding-top:25px; font-size:12px; color:#999;">
            © {{ date('Y') }} Solution Sindicância
        </td>
    </tr>

</table>

</td>
</tr>
</table>

</body>
</html>
