<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Producto</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php
    // Iniciar sesión
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Validar sesión activa
    if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit;
    }
    
    include "menu.php";
    include "db_connect.php";
    include "Subir_foto.php";
    
    echo "<h1>Registrar Producto</h1>";
    
    // Verificar que se recibieron los datos
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre']) && isset($_POST['codigo'])) {
        $nombre = $_POST['nombre'];
        $codigo = $_POST['codigo'];
        $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
        $costo = $_POST['costo'];
        $stock = $_POST['stock'];
        // Estado siempre activo al crear
        $status = 1;
        $nombre_imagen = NULL;
        
        // Procesar imagen si se subió
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] != 4) {
            $resultado_imagen = subirFoto($_FILES['imagen']);
            if ($resultado_imagen['success']) {
                $nombre_imagen = $resultado_imagen['nombre'];
            } else {
                echo "<div class='mensaje error'>" . $resultado_imagen['mensaje'] . "</div>";
                echo "<br><a href='productos_alta_form.php' class='boton'>Volver al Formulario</a>";
                $conn->close();
                exit;
            }
        }

        // Validar que el código no exista (servidor, protección extra)
        $sql_check = "SELECT id FROM productos WHERE codigo = '$codigo' AND eliminado = 0";
        $result_check = $conn->query($sql_check);
        if ($result_check && $result_check->num_rows > 0) {
            echo "<div class='mensaje error'>Error: El código '" . htmlspecialchars($codigo) . "' ya está registrado.</div>";
            echo "<br><a href='productos_alta_form.php' class='boton'>Volver al Formulario</a>";
            $conn->close();
            exit;
        }
        
        // Preparar consulta INSERT
        if ($nombre_imagen) {
            $sql = "INSERT INTO productos (nombre, codigo, descripcion, costo, stock, imagen, status) 
                    VALUES ('$nombre', '$codigo', '$descripcion', $costo, $stock, '$nombre_imagen', $status)";
        } else {
            $sql = "INSERT INTO productos (nombre, codigo, descripcion, costo, stock, status) 
                    VALUES ('$nombre', '$codigo', '$descripcion', $costo, $stock, $status)";
        }
        
        if ($conn->query($sql) === TRUE) {
            header('Location: productos_lista.php');
            exit;
        } else {
            // Traducir errores comunes de MySQL a español
            $error = $conn->error;
            
            if (strpos($error, "Duplicate entry") !== false && strpos($error, "codigo") !== false) {
                $mensaje_error = "Error: El código '" . htmlspecialchars($codigo) . "' ya está registrado.";
            } else {
                $mensaje_error = "Error al registrar el producto: " . $error;
            }
            
            echo "<div class='mensaje error'>$mensaje_error</div>";
            echo "<br><a href='productos_alta_form.php' class='boton'>Volver al Formulario</a>";
        }
    } else {
        echo "<div class='mensaje error'>Error: No se recibieron los datos necesarios.</div>";
        echo "<br><a href='productos_alta_form.php' class='boton'>Volver al Formulario</a>";
    }
    
    $conn->close();
    ?>
</body>
</html>
