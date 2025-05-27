<?php
include 'conexion.php';

function mostrarMensaje($mensaje, $tipo = 'info') {
    $colorTexto = [
        'info' => '#055160',
        'error' => '#721c24',
        'success' => '#155724',
    ];
    $colorBorde = [
        'info' => '#bde5f8',
        'error' => '#f5c6cb',
        'success' => '#c3e6cb',
    ];

    $bg = '#ffffff';
    $text = isset($colorTexto[$tipo]) ? $colorTexto[$tipo] : '#055160';
    $border = isset($colorBorde[$tipo]) ? $colorBorde[$tipo] : '#bde5f8';

    echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensaje</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        }
        .mensaje {
            max-width: 600px;
            padding: 40px;
            border-radius: 12px;
            background-color: '.$bg.';
            color: '.$text.';
            border: 2px solid '.$border.';
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            font-size: 1.2em;
            line-height: 1.5;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="mensaje">'.$mensaje.'</div>
</body>
</html>';

    exit;
}

// Verificar que se recibió el correo por POST y que no está vacío
if (!isset($_POST['correo']) || empty(trim($_POST['correo']))) {
    mostrarMensaje("El correo es obligatorio. <a href='recuperar_contrasena.php'>Intentar de nuevo</a>", "error");
}

$correo = trim($_POST['correo']);

// Validar que el correo tenga formato válido
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    mostrarMensaje("Formato de correo inválido. <a href='recuperar_contrasena.php'>Intentar de nuevo</a>", "error");
}

try {
    // Preparar y ejecutar la consulta de forma segura
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);

    if ($stmt->rowCount() === 0) {
        mostrarMensaje("Si existe una cuenta asociada a ese correo, se ha enviado un enlace de recuperación. <a href='inicio.php'>Volver al inicio</a>", "info");
    }

    // Por simplicidad, redirigimos con el correo (mejor enviar token en producción)
    header("Location: nueva_contrasena.php?correo=" . urlencode($correo));
    exit;

} catch (PDOException $e) {
    error_log("Error al buscar usuario: " . $e->getMessage());
    mostrarMensaje("Error interno. Por favor intenta más tarde.", "error");
}
?>






