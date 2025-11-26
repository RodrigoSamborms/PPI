<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
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
    
    echo "<h1>Editar Producto</h1>";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $codigo = $_POST['codigo'];
        $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
        $costo = $_POST['costo'];
        $stock = $_POST['stock'];
        $status = isset($_POST['status']) ? $_POST['status'] : 1;
        
        // Obtener la imagen actual
        $sql_imagen = "SELECT imagen FROM productos WHERE id = $id";
        $result_imagen = $conn->query($sql_imagen);
        $imagen_actual = '';
        if ($result_imagen && $result_imagen->num_rows > 0) {
            $row_imagen = $result_imagen->fetch_assoc();
            $imagen_actual = $row_imagen['imagen'];
        }
        
        $nombre_imagen = $imagen_actual; // Por defecto mantener la imagen actual
        
        // Procesar nueva imagen si se subió
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] != 4) {
            $resultado_imagen = subirFoto($_FILES['imagen']);
            
            if ($resultado_imagen['success']) {
                $nombre_imagen = $resultado_imagen['nombre'];
            } else {
                echo "<div class='mensaje error'>" . $resultado_imagen['mensaje'] . "</div>";
                echo "<br><a href='productos_editar_form.php?id=$id' class='boton'>Volver al Formulario</a>";
                $conn->close();
                exit;
            }
        }
        
        // Actualizar producto
        if ($nombre_imagen) {
            $sql = "UPDATE productos SET 
                    nombre = '$nombre', 
                    codigo = '$codigo', 
                    descripcion = '$descripcion', 
                    costo = $costo, 
                    stock = $stock, 
                    imagen = '$nombre_imagen', 
                    status = $status 
                    WHERE id = $id";
        } else {
            $sql = "UPDATE productos SET 
                    nombre = '$nombre', 
                    codigo = '$codigo', 
                    descripcion = '$descripcion', 
                    costo = $costo, 
                    stock = $stock, 
                    status = $status 
                    WHERE id = $id";
        }
        
        if ($conn->query($sql) === TRUE) {
            // Redirigir automáticamente a la lista de productos
            header('Location: productos_lista.php');
            $conn->close();
            exit;
        } else {
            $error = $conn->error;
            
            if (strpos($error, "Duplicate entry") !== false && strpos($error, "codigo") !== false) {
                $mensaje_error = "Error: El código '" . htmlspecialchars($codigo) . "' ya está registrado.";
            } else {
                $mensaje_error = "Error al actualizar el producto: " . $error;
            }
            
            echo "<div class='mensaje error'>$mensaje_error</div>";
            echo "<br><a href='productos_editar_form.php?id=$id' class='boton'>Volver al Formulario</a>";
        }
    } else {
        echo "<div class='mensaje error'>Error: No se recibieron los datos necesarios.</div>";
        echo "<br><a href='productos_lista.php' class='boton'>Volver a la Lista</a>";
    }
    
    $conn->close();
    ?>
</body>
</html>
