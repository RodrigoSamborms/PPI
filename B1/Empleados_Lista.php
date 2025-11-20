<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Empleados</title>

</head>
<body>
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
                    
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $nombre_completo . "</td>";
                    echo "<td>" . $row['correo'] . "</td>";
                    echo "<td>" . $rol_texto . "</td>";
                    echo "<td><a href='#'>Ver Detalle</a></td>";
                    echo "<td><a href='#'>Editar</a></td>";
                    echo "<td><a href='#'>Eliminar</a></td>";
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
