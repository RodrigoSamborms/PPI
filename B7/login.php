<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.10.0/js/md5.min.js"></script>
    <script>
        function mostrarError(mensaje) {
            $('#mensaje-error').text(mensaje).fadeIn();
            setTimeout(function() {
                $('#mensaje-error').fadeOut();
            }, 5000);
        }

        function validarFormulario() {
            // Limpiar mensaje de error previo
            $('#mensaje-error').hide();
            
            // Obtener valores
            var correo = $('#correo').val().trim();
            var pass = $('#pass').val().trim();
            
            // Validar campos vacíos
            if (correo === '') {
                mostrarError('El campo de correo electrónico es obligatorio');
                $('#correo').focus();
                return false;
            }
            
            if (pass === '') {
                mostrarError('El campo de contraseña es obligatorio');
                $('#pass').focus();
                return false;
            }
            
            // Validar formato de correo
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(correo)) {
                mostrarError('Por favor ingrese un correo electrónico válido');
                $('#correo').focus();
                return false;
            }
            
            // Encriptar contraseña con MD5
            var passMd5 = md5(pass);
            
            // Mostrar indicador de carga
            $('#btn-login').prop('disabled', true).text('Verificando...');
            
            // Enviar solicitud AJAX
            $.ajax({
                url: 'login_consulta.php',
                type: 'POST',
                data: {
                    correo: correo,
                    pass: passMd5
                },
                success: function(response) {
                    console.log('Respuesta del servidor:', response);
                    
                    if (response == '1') {
                        // Login exitoso - redirigir a bienvenido.php
                        window.location.href = 'bienvenido.php';
                    } else {
                        // Login fallido
                        $('#btn-login').prop('disabled', false).text('Iniciar Sesión');
                        mostrarError('Correo o contraseña incorrectos, o el usuario no está activo');
                        $('#pass').val(''); // Limpiar contraseña
                        $('#pass').focus();
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error AJAX:', status, error);
                    $('#btn-login').prop('disabled', false).text('Iniciar Sesión');
                    mostrarError('Error de comunicación con el servidor');
                }
            });
            
            return false; // Prevenir envío tradicional del formulario
        }
        
        $(document).ready(function() {
            console.log('jQuery cargado correctamente');
            
            // Permitir Enter para enviar el formulario
            $('#correo, #pass').keypress(function(e) {
                if (e.which == 13) { // Enter key
                    validarFormulario();
                    return false;
                }
            });
        });
    </script>
</head>
<body>
    <div class="login-container">
        <h1>Sistema de Gestión de Empleados</h1>
        <h2>Inicio de Sesión</h2>
        
        <div id="mensaje-error" class="mensaje error" style="display: none;"></div>
        
        <form id="form-login" onsubmit="return validarFormulario();">
            <label for="correo">Correo Electrónico:</label>
            <input type="text" id="correo" name="correo" placeholder="usuario@ejemplo.com" autocomplete="username">
            
            <label for="pass">Contraseña:</label>
            <input type="password" id="pass" name="pass" placeholder="Ingrese su contraseña" autocomplete="current-password">
            
            <button type="submit" id="btn-login">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
