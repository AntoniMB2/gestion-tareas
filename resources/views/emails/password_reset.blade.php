<!DOCTYPE html>
<html>
<head>
    <title>Restablecimiento de Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #7ba9b0;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: #333;
            text-align: center;
        }
        
        p, a {
            color: #333;
            line-height: 1.5;
        }
        
        a.button {
            display: inline-block;
            background-color: #28555c;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Restablecimiento de Contraseña</h1>
        <p>¡Hola!</p>
        <p>Recibiste este correo porque solicitaste restablecer tu contraseña. Haz clic en el siguiente botón para continuar:</p>
        <p>
            Este es tu código de verificación: {{ $token }}
        </p>
        <p><a href="{{ url("https://workspace.google.com/intl/es-419/lp/forms/?utm_source=google&utm_medium=cpc&utm_campaign=latam-CO-all-es-dr-skws-all-all-trial-b-dr-1707806-LUAC0020527&utm_content=text-ad-none-any-DEV_c-CRE_687240566591-ADGP_Hybrid%20%7C%20SKWS%20-%20BRO%20%7C%20Txt-General-Forms-KWID_43700079198339139-kwd-298470733618&utm_term=KW_formularios%20online-ST_formularios%20online&gad_source=1&gclid=Cj0KCQjwk6SwBhDPARIsAJ59GwdGxWDZTMnh7R59EFtgjXl7Xwy2rR9ZFzJlCaTbuqHC4Npu_mwTarMaAqw_EALw_wcB&gclsrc=aw.ds") }}" class="button">Restablecer Contraseña</a></p>
        <p>Si no solicitaste restablecer tu contraseña, puedes ignorar este correo.</p>
    </div>
</body>
</html>