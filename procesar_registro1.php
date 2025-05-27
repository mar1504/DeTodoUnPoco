<?php
session_start();
include 'conexion.php';

$correo = $_POST['correo'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

// Validar que no estén vacíos
if (!$correo || !$contrasena) {
    mostrarError("Correo y contraseña son obligatorios.");
    exit;
}

// Buscar usuario por correo
$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$correo]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    mostrarError("Correo o contraseña incorrectos.");
    exit;
}

// Verificar contraseña
if (password_verify($contrasena, $usuario['contrasena'])) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_correo'] = $usuario['correo'];

    header("Location: inicio.php");
    exit;
} else {
    mostrarError("Correo o contraseña incorrectos.");
    exit;
}

function mostrarError($mensaje) {
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>Error de Inicio de Sesión</title>
      <style>
        body {
          font-family: Arial, sans-serif;
          background: linear-gradient(135deg, #6a11cb, #2575fc);
          height: 100vh;
          margin: 0;
          display: flex;
          justify-content: center;
          align-items: center;
          color: #fff;
        }
        .error-box {
          background: rgba(255, 255, 255, 0.95);
          padding: 2rem 2.5rem;
          border-radius: 16px;
          box-shadow: 0 12px 30px rgba(0,0,0,0.3);
          max-width: 400px;
          text-align: center;
          color: #d8000c;
          font-weight: bold;
          font-size: 1.25rem;
          display: flex;
          flex-direction: column;
          gap: 1.5rem;
        }
        a {
          background: linear-gradient(90deg, #6a11cb, #2575fc);
          color: white;
          text-decoration: none;
          padding: 12px 28px;
          border-radius: 12px;
          font-weight: 700;
          box-shadow: 0 6px 15px rgba(38, 33, 99, 0.4);
          transition: background 0.3s ease;
          align-self: center;
        }
        a:hover,
        a:focus {
          background: linear-gradient(90deg, #4b0082, #1a52c0);
          outline: none;
        }
      </style>
    </head>
    <body>
      <div class="error-box" role="alert" aria-live="assertive">
        <div>'.htmlspecialchars($mensaje).'</div>
        <a href="iniciar_sesion.php">Regresar</a>
      </div>
    </body>
    </html>';
}

