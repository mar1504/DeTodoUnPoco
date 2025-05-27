<?php
include 'conexion.php';

// Inicializar variables para mostrar resultado
$exito = false;
$mensaje = "";

// Validar datos recibidos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = trim($_POST['precio'] ?? '');
    $categoria = intval($_POST['categoria'] ?? 0);
    $id_usuario = 1; // Aquí debes reemplazar por el ID real del usuario logueado (o session)

    // Validaciones básicas
    if (!$titulo || !$descripcion || !$precio || !$categoria) {
        $mensaje = "Por favor, completa todos los campos obligatorios.";
    } elseif (!is_numeric($precio) || $precio < 0) {
        $mensaje = "El precio debe ser un número válido y positivo.";
    } elseif (!isset($_FILES['fotos'])) {
        $mensaje = "Por favor, sube al menos una imagen.";
    } else {
        try {
            // Comenzar transacción
            $pdo->beginTransaction();

            // Insertar anuncio
            $stmt = $pdo->prepare("INSERT INTO publicar_anuncios (titulo, descripcion, precio, id_categoria, id_usuario) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$titulo, $descripcion, $precio, $categoria, $id_usuario]);
            $id_anuncio = $pdo->lastInsertId();

            // Procesar imágenes
            $uploadsDir = 'uploads/';
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0755, true);
            }

            $fotos = $_FILES['fotos'];
            $numFotos = count($fotos['name']);
            $fotosSubidas = 0;

            for ($i = 0; $i < $numFotos; $i++) {
                if ($fotos['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $fotos['tmp_name'][$i];
                    $name = basename($fotos['name'][$i]);

                    // Crear nombre único para evitar conflictos
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    $nuevoNombre = uniqid('foto_') . '.' . $ext;
                    $destino = $uploadsDir . $nuevoNombre;

                    // Validar tipo de imagen (simple)
                    $allowed = ['jpg','jpeg','png','gif','jfif','webp','bmp'];
                    if (!in_array(strtolower($ext), $allowed)) {
                        continue; // Saltar si no es válido
                    }

                    if (move_uploaded_file($tmpName, $destino)) {
                        // Insertar ruta en fotos_anuncio
                        $stmtFoto = $pdo->prepare("INSERT INTO fotos_anuncio (id_anuncio, ruta_foto) VALUES (?, ?)");
                        $stmtFoto->execute([$id_anuncio, $destino]);
                        $fotosSubidas++;
                    }
                }
            }

            if ($fotosSubidas === 0) {
                // Si ninguna foto se subió, revertir y mostrar error
                $pdo->rollBack();
                $mensaje = "Error: No se subió ninguna imagen válida.";
            } else {
                $pdo->commit();
                $exito = true;
                $mensaje = "¡Anuncio publicado con éxito!";
            }
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $mensaje = "Error al publicar el anuncio: " . $e->getMessage();
        }
    }
} else {
    $mensaje = "Acceso inválido.";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Resultado de Publicación</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #2c3e50;
            padding: 20px;
            box-sizing: border-box;
        }

        .mensaje {
            background-color: #fff;
            padding: 40px 35px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(78, 84, 200, 0.3);
            text-align: center;
            max-width: 500px;
            width: 100%;
            animation: fadeIn 1s ease forwards;
            word-wrap: break-word;
            white-space: pre-wrap;
        }

        .mensaje p {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 30px;
            color: <?= $exito ? '#4e54c8' : '#9b59b6' ?>;
        }

        a {
            display: inline-block;
            margin: 8px 15px;
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            color: #ecf0f1;
            background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
            box-shadow: 0 5px 15px rgba(106, 17, 203, 0.6);
            transition: background 0.4s ease, box-shadow 0.3s ease;
            letter-spacing: 0.8px;
        }

        a:hover {
            background: linear-gradient(90deg, #2575fc 0%, #6a11cb 100%);
            box-shadow: 0 8px 22px rgba(101, 67, 234, 0.8);
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        @media (max-width: 480px) {
            .mensaje {
                padding: 30px 25px;
                max-width: 100%;
            }
            .mensaje p {
                font-size: 1.4rem;
            }
            a {
                padding: 12px 30px;
                margin: 8px 10px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<div class="mensaje" role="alert" aria-live="polite">
    <p><?= htmlspecialchars($mensaje) ?></p>
    <?php if ($exito): ?>
        <a href="publicar.php" title="Publicar otro anuncio">📢 Publicar otro</a>
        <a href="explorar_anuncios.php" title="Ver anuncios">🔍 Ver anuncios</a>
    <?php else: ?>
        <a href="publicar.php" title="Volver a intentar">🔄 Intentar de nuevo</a>
    <?php endif; ?>
</div>

</body>
</html>