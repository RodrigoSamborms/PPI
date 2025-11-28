<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        function eliminarProducto(id, nombre) {
            if (confirm('¿Estás seguro de eliminar el producto "' + nombre + '"?')) {
                $.ajax({
                    url: 'productos_eliminar.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response == '1') {
                            alert('Producto eliminado correctamente');
                            location.reload();
                        } else {
                            alert('Error al eliminar el producto');
                        }
                    },
                    error: function() {
                        alert('Error de comunicación con el servidor');
                    }
                });
            }
        }
    </script>
</head>
<body>
    <?php include "menu.php"; ?>
    
    <div style="margin-top: 20px; margin-bottom: 20px; text-align: center;">
        <a href="productos_alta_form.php" class="button">Registrar Nuevo Producto</a>
    </div>
    <h1>Lista de Productos</h1>
    
    <?php
    include "db_connect.php";
    
    // Consultar productos activos (no eliminados)
    $sql = "SELECT id, nombre, codigo, descripcion, costo, stock, imagen, status 
            FROM productos 
            WHERE eliminado = 0 
            ORDER BY nombre ASC";
    
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>Código</th>";
        echo "<th>Nombre</th>";
        echo "<th>Descripción</th>";
        echo "<th>Costo</th>";
        echo "<th>Stock</th>";
        echo "<th>Estado</th>";
        echo "<th>Acciones</th>";
        echo "</tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['codigo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
            
            // Descripción truncada
            $descripcion = $row['descripcion'];
            if (strlen($descripcion) > 50) {
                $descripcion = substr($descripcion, 0, 50) . "...";
            }
            echo "<td>" . htmlspecialchars($descripcion) . "</td>";
            
            // Formato de costo con signo de pesos
            echo "<td>$" . number_format($row['costo'], 2) . "</td>";
            
            // Stock con alerta si es bajo
            $stock_class = $row['stock'] < 10 ? 'style="color: red; font-weight: bold;"' : '';
            echo "<td $stock_class>" . $row['stock'] . "</td>";
            
            // Estado con badge
            if ($row['status'] == 1) {
                echo "<td><span class='badge badge-activo'>Activo</span></td>";
            } else {
                echo "<td><span class='badge badge-inactivo'>Inactivo</span></td>";
            }
            
            // Acciones
            echo "<td>";
            echo "<a href='productos_detalle.php?id=" . $row['id'] . "'>Ver Detalle</a> | ";
            echo "<a href='productos_editar_form.php?id=" . $row['id'] . "'>Editar</a> | ";
            echo "<a href='#' onclick='eliminarProducto(" . $row['id'] . ", \"" . htmlspecialchars($row['nombre']) . "\"); return false;'>Eliminar</a>";
            echo "</td>";
            
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No hay productos registrados.</p>";
    }
    
    $conn->close();
    ?>
    
    <br>
</body>
</html>
