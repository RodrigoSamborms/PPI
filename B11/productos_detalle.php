<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Producto</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php include "menu.php"; ?>
    
    <style>
        .detalle-card {
            max-width: 420px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 30px 30px 20px 30px;
        }
        .campo {
            display: flex;
            margin-bottom: 16px;
        }
        .campo .label {
            width: 140px;
            font-weight: bold;
            color: #333;
            display: inline-block;
        }
        .campo .valor {
            color: #222;
        }
        .status.activo {
            color: #fff;
            background: #4CAF50;
            border-radius: 12px;
            padding: 2px 12px;
            font-size: 0.98em;
            font-weight: bold;
        }
        .status.inactivo {
            color: #fff;
            background: #e53935;
            border-radius: 12px;
            padding: 2px 12px;
            font-size: 0.98em;
            font-weight: bold;
        }
        .imagen-detalle {
            max-width: 180px;
            max-height: 180px;
            border-radius: 6px;
            margin-top: 10px;
        }
    </style>
    <div style="margin-top: 20px; margin-bottom: 20px; text-align: center;">
        <a href="productos_lista.php" class="button">Volver al Listado</a>
    </div>
    <h1 style="text-align:center; margin-top:30px;">Detalle del Producto</h1>
    <div class="detalle-card">
    <?php
    include "db_connect.php";
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM productos WHERE id = $id AND eliminado = 0";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $status = ($row['status'] == 1) ? 'Activo' : 'Inactivo';
            $status_class = ($row['status'] == 1) ? 'activo' : 'inactivo';
            // Campos como en empleados_detalle.php
            echo "<div class='campo'><span class='label'>ID:</span><span class='valor'>" . $row['id'] . "</span></div>";
            echo "<div class='campo'><span class='label'>Código:</span><span class='valor'>" . htmlspecialchars($row['codigo']) . "</span></div>";
            echo "<div class='campo'><span class='label'>Nombre:</span><span class='valor'>" . htmlspecialchars($row['nombre']) . "</span></div>";
            echo "<div class='campo'><span class='label'>Descripción:</span><span class='valor'>" . nl2br(htmlspecialchars($row['descripcion'])) . "</span></div>";
            echo "<div class='campo'><span class='label'>Costo:</span><span class='valor'>$" . number_format($row['costo'], 2) . "</span></div>";
            $stock_style = $row['stock'] < 10 ? 'color: red; font-weight: bold;' : '';
            echo "<div class='campo'><span class='label'>Stock:</span><span class='valor' style='$stock_style'>" . $row['stock'];
            if ($row['stock'] < 10) {
                echo " <span style='font-size: 12px;'>(⚠️ Stock bajo)</span>";
            }
            echo "</span></div>";
            echo "<div class='campo'><span class='label'>Estado:</span><span class='status $status_class'>$status</span></div>";
            // Imagen dentro de la tabla
            echo "<div class='campo'><span class='label'>Imagen:</span>";
            if ($row['imagen']) {
                $ruta_imagen = "/home/rodrigo/html/productos/" . $row['imagen'];
                if (file_exists($ruta_imagen)) {
                    $imagen_size = getimagesize($ruta_imagen);
                    if ($imagen_size !== false) {
                        $imagen_data = base64_encode(file_get_contents($ruta_imagen));
                        $mime_type = $imagen_size['mime'];
                        echo "<img src='data:$mime_type;base64,$imagen_data' alt='Imagen del producto' class='imagen-detalle'>";
                    }
                }
            } else {
                echo "<span class='valor' style='color: #999; font-style: italic;'>Imagen no disponible</span>";
            }
            echo "</div>";
        } else {
            echo "<div class='mensaje error'>Producto no encontrado.</div>";
            echo "<br><a href='productos_lista.php' class='button'>Volver al Listado</a>";
        }
    } else {
        echo "<div class='mensaje error'>ID de producto no especificado.</div>";
        echo "<br><a href='productos_lista.php' class='button'>Volver al Listado</a>";
    }
    $conn->close();
    ?>
    </div>
</body>
</html>
