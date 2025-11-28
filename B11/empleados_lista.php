<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Empleados</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        function eliminarEmpleado(id) {
            if (confirm('¿Está seguro de que desea eliminar este empleado?')) {
                console.log('Eliminando empleado ID:', id);
                
                $.ajax({
                    url: 'empleados_eliminar.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        console.log('Respuesta:', response);
                        if (response == '1') {
                            // Eliminar la fila de la tabla
                            $('tr[data-id="' + id + '"]').fadeOut(400, function() {
                                $(this).remove();
                                // Verificar si quedan empleados
                                if ($('tbody tr').length === 0) {
                                    $('tbody').html('<tr><td colspan="7">No hay empleados registrados</td></tr>');
                                }
                            });
                            alert('Empleado eliminado correctamente');
                        } else {
                            alert('Error al eliminar el empleado');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error AJAX:', status, error);
                        alert('Error de comunicación con el servidor');
                    }
                });
            }
        }
        
        $(document).ready(function() {
            console.log('jQuery cargado correctamente');
        });
    </script>
</head>
<body>
    <?php include "menu.php"; ?>
    
    <div style="margin-top: 20px; margin-bottom: 20px; text-align: center;">
        <a href="empleados_alta_form.php" class="button">Registrar Nuevo Empleado</a>
    </div>
    <h1>Lista de Empleados</h1>
    
    <?php
    include "db_connect.php";
    
    // Consultar empleados
    $sql = "SELECT id, nombre, apellidos, correo, rol FROM empleados WHERE eliminado = 0 ORDER BY id";
    $result = $conn->query($sql);
    ?>
    
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Ver Detalle</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nombre_completo = $row['nombre'] . " " . $row['apellidos'];
                    $rol_texto = ($row['rol'] == 2) ? 'Gerente' : 'Ejecutivo';
                    
                    echo "<tr data-id='" . $row['id'] . "'>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $nombre_completo . "</td>";
                    echo "<td>" . $row['correo'] . "</td>";
                    echo "<td>" . $rol_texto . "</td>";
                    echo "<td><a href='empleados_detalle.php?id=" . $row['id'] . "'>Ver Detalle</a></td>";
                    echo "<td><a href='empleados_editar_form.php?id=" . $row['id'] . "'>Editar</a></td>";
                    echo "<td><a href='#' onclick='eliminarEmpleado(" . $row['id'] . "); return false;'>Eliminar</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No hay empleados registrados</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
