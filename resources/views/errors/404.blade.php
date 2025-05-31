<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada</title>
    <style>
        body {
            background-color: #0A192F;
            color: #ffffff;
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            font-size: 80px;
            margin: 0;
        }

        p {
            font-size: 18px;
            margin: 10px 0;
        }

        a {
            text-decoration: none;
            background-color: #1E90FF;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            transition: 0.3s;
        }

        a:hover {
            background-color: #007BFF;
        }

        .image {
            width: 300px;
            margin: 20px 0;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>404</h1>
        <p>¡Oops! La página que buscas no existe.</p>
        <p><a href="{{ url('/Especies') }}">Volver al inicio</a></p>
    </div>

</body>
</html>
