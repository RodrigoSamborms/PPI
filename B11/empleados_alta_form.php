<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta de Empleado</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.19.0/js/md5.min.js"></script>
    <script>
    function previsualizarImagen() {
        var archivo = $('#imagen')[0].files[0];
        if (archivo) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').html('<img src="' + e.target.result + '" style="max-width: 200px; max-height: 200px;">');
            }
            reader.readAsDataURL(archivo);
        } else {
            $('#preview').html('');
        }
    }

    $(document).ready(function() {
        // Previsualizar imagen
        $('#imagen').change(previsualizarImagen);
        // Validar correo cuando pierde el foco
        $('#correo').blur(function() {
            var correo = $(this).val();
            var $msg = $('#correo-validacion');
            $msg.text('');
            if (correo) {
                $.ajax({
                    url: 'correo_consulta.php',
                    type: 'POST',
                    data: { correo: correo },
                    success: function(response) {
                        if (response == '1') {
                            $msg.text('El correo ' + correo + ' ya existe.').css('color', 'red');
                            setTimeout(function() { $msg.text(''); }, 5000);
                        }
                    }
                });
            }
        });

        $('#formAltaEmpleado').submit(function(e) {
            var campos = ['nombre', 'apellidos', 'correo', 'pass', 'rol'];
            var vacio = false;
            campos.forEach(function(campo) {
                if (!$('#' + campo).val()) vacio = true;
            });
            if (vacio) {
                mostrarError('Faltan campos por llenar');
                e.preventDefault();
                return;
            }
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
    <?php include "menu.php"; ?>
    
    <div style="margin-top: 20px; margin-bottom: 20px; text-align: center;">
        <a href="empleados_lista.php" class="button">Volver al Listado</a>
    </div>
    <h1>Alta de Empleado</h1>
    
    <form id="formAltaEmpleado" action="empleados_alta.php" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" maxlength="128" required>
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" maxlength="128" required>
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" maxlength="128" required>
        <span id="correo-validacion" style="margin-left:10px;font-size:14px;"></span>
        <label for="pass">Contraseña:</label>
        <input type="password" id="pass" name="pass" maxlength="32" required>
        <label for="rol">Rol:</label>
        <select id="rol" name="rol" required>
            <option value="">Seleccione un rol</option>
            <option value="1">Ejecutivo</option>
            <option value="2">Gerente</option>
        </select>
        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">
        <div id="preview"></div>
        <p style="color: #999; font-size: 13px;">* Campo opcional</p>
        <div>
            <input type="submit" value="Guardar Empleado">
        </div>
        <div id="mensajeError" class="mensaje error" style="display:none;"></div>
    </form>
</body>
</html>
