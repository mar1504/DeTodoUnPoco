<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'conexion.php'; // Asegúrate de que este archivo existe y está bien configurado

    // Sanitizar entradas
    $anuncio = htmlspecialchars($_POST['anuncio'] ?? '');
    $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $mensaje = htmlspecialchars(trim($_POST['mensaje'] ?? ''));

    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($mensaje)) {
        mostrarError("Todos los campos son obligatorios.");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        mostrarError("El correo electrónico no es válido.");
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO mensajes_interes (anuncio, nombre, email, mensaje) VALUES (?, ?, ?, ?)");
        $stmt->execute([$anuncio, $nombre, $email, $mensaje]);

        // Página de éxito
        mostrarExito();
    } catch (PDOException $e) {
        http_response_code(500);
        mostrarError("Error al guardar en la base de datos: " . $e->getMessage());
    }
} else {
    http_response_code(405);
    echo "Método no permitido.";
}

// Función de error
function mostrarError($mensaje) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Error</title>
        <style>
            body {
                margin: 0; padding: 0;
                font-family: 'Segoe UI', sans-serif;
                background: linear-gradient(to right, #ff6b6b, #c0392b);
                height: 100vh; display: flex; align-items: center; justify-content: center;
            }
            .card {
                background: #fff;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 10px 20px rgba(0,0,0,0.2);
                text-align: center;
            }
            h2 { color: #e74c3c; }
            a.boton {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background: #e74c3c;
                color: #fff;
                text-decoration: none;
                border-radius: 25px;
                font-weight: bold;
                transition: background 0.3s;
            }
            a.boton:hover {
                background: #c0392b;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <h2>❌ <?php echo htmlspecialchars($mensaje); ?></h2>
            <a class="boton" href="javascript:history.back()">Volver</a>
        </div>
    </body>
    </html>
    <?php
}

// Función de éxito
function mostrarExito() {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Interés Enviado</title>
        <style>
            body {
                margin: 0; padding: 0;
                font-family: 'Segoe UI', sans-serif;
                background: linear-gradient(to bottom right, #6b73ff, #000dff);
                display: flex; align-items: center; justify-content: center;
                height: 100vh;
            }
            .card {
                background: #fff;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                text-align: center;
            }
            h2 {
                color: #4b3ecf;
                margin-bottom: 30px;
            }
            .boton {
                display: block;
                width: 200px;
                margin: 10px auto;
                padding: 12px 20px;
                background: linear-gradient(to right, #7b52ff, #3f79ff);
                color: #fff;
                font-weight: bold;
                border: none;
                border-radius: 30px;
                text-decoration: none;
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
                transition: transform 0.2s, box-shadow 0.2s;
            }
            .boton:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            }
            .emoji {
                margin-right: 6px;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <h2>¡Tu interés ha sido enviado con éxito! 💌</h2>
            <a class="boton" href="explorar_anuncios.php"><span class="emoji">🔍</span> Ver más anuncios</a>
            <a class="boton" href="inicio.php"><span class="emoji">🏠</span> Volver al inicio</a>
        </div>
    </body>
    </html>
    <?php
}
?>
