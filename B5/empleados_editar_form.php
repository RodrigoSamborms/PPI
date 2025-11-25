<?php
// Verificar que se recibió el ID del empleado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: empleados_lista.php');
    exit;
}

$id_empleado = intval($_GET['id']);

include "db_connect.php";

// Consultar los datos del empleado - solo empleados activos
$sql = "SELECT id, nombre, apellidos, correo, rol FROM empleados WHERE id = $id_empleado AND eliminado = 0";
$result = $conn->query($sql);

// Verificar que el empleado existe y está activo
if (!$result || $result->num_rows == 0) {
    echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>";
    echo "<title>Error</title><link rel='stylesheet' href='estilos.css'></head><body>";
    echo "<div class='mensaje error'>";
    echo "<h2>Empleado no encontrado</h2>";
    echo "<p>El empleado con ID $id_empleado no existe o está inactivo.</p>";
    echo "<p><a href='empleados_lista.php' class='button'>Volver al Listado</a></p>";
    echo "</div></body></html>";
    $conn->close();
    exit;
}

$empleado = $result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Empleado</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.19.0/js/md5.min.js"></script>
    <script>
    $(document).ready(function() {
        // Validar correo cuando pierde el foco (excepto si es el mismo correo actual)
        var correoOriginal = $('#correo').val();
        
        $('#correo').blur(function() {
            var correo = $(this).val();
            // Solo validar si el correo cambió
            if (correo && correo !== correoOriginal) {
                $.ajax({
                    url: 'correo_consulta_editar.php',
                    type: 'POST',
                    data: { 
                        correo: correo,
                        id_empleado: $('#id_empleado').val()
                    },
                    success: function(response) {
                        if (response == '1') {
                            // Correo existe
                            $('#correo').val(correoOriginal);
                            $('#correo').focus();
                            mostrarError('Correo ya existente');
                        }
                    }
                });
            }
        });
        
        $('#formEditarEmpleado').submit(function(e) {
            e.preventDefault();
            // Validar campos obligatorios (contraseña es opcional)
            var campos = ['nombre', 'apellidos', 'correo', 'rol'];
            var vacio = false;
            campos.forEach(function(campo) {
                if (!$('#' + campo).val()) vacio = true;
            });
            if (vacio) {
                mostrarError('Faltan campos por llenar');
                return;
            }
            
            // Si se ingresó contraseña, encriptarla con md5
            var pass = $('#pass').val();
            if (pass && pass.trim() !== '') {
                var passMd5 = md5(pass);
                $('#pass').val(passMd5);
            }
            
            // Enviar el formulario
            this.submit();
        });
    });
    
    function mostrarError(msg) {
        var $div = $('#mensajeError');
        $div.text(msg).show();
        setTimeout(function() { $div.fadeOut(); }, 5000);
    }
    </script>
</head>
<body>
    <h1>Editar Empleado</h1>
    <p style="text-align:center; margin: 20px 0;">
        <a href="empleados_lista.php" class="button">Volver al Listado</a>
    </p>
    
    <form id="formEditarEmpleado" action="empleados_editar.php" method="POST">
        <input type="hidden" id="id_empleado" name="id" value="<?php echo $empleado['id']; ?>">
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" maxlength="128" 
               value="<?php echo htmlspecialchars($empleado['nombre']); ?>" required>
        
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" maxlength="128" 
               value="<?php echo htmlspecialchars($empleado['apellidos']); ?>" required>
        
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" maxlength="128" 
               value="<?php echo htmlspecialchars($empleado['correo']); ?>" required>
        
        <label for="pass">Contraseña:</label>
        <input type="password" id="pass" name="pass" maxlength="32" placeholder="Dejar vacío para no modificar">
        <p style="color: #666; font-size: 12px; margin-top: -15px; margin-bottom: 20px;">
            * Dejar este campo vacío si no desea cambiar la contraseña
        </p>
        
        <label for="rol">Rol:</label>
        <select id="rol" name="rol" required>
            <option value="">Seleccione un rol</option>
            <option value="1" <?php echo ($empleado['rol'] == 1) ? 'selected' : ''; ?>>Ejecutivo</option>
            <option value="2" <?php echo ($empleado['rol'] == 2) ? 'selected' : ''; ?>>Gerente</option>
        </select>
        
        <div>
            <input type="submit" value="Actualizar Empleado">
        </div>
        <div id="mensajeError" class="mensaje error" style="display:none;"></div>
    </form>
</body>
</html>
