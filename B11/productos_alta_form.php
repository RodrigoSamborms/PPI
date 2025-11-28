<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Producto</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        function validarCodigoAjax(codigo, callback) {
            $.ajax({
                url: 'codigo_consulta.php',
                type: 'POST',
                data: { codigo: codigo },
                success: function(response) {
                    callback(response == '1');
                },
                error: function() { callback(false); }
            });
        }

        function mostrarError(msg) {
            var $div = $('#mensaje');
            $div.text(msg).show();
            setTimeout(function() { $div.fadeOut(); }, 5000);
        }

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
            $('#imagen').change(previsualizarImagen);
            $('#codigo').blur(function() {
                var codigo = $(this).val().trim();
                if (codigo) {
                    validarCodigoAjax(codigo, function(existe) {
                        if (!existe) {
                            $('#codigo-validacion').text('✗ Código ya existe').css('color', 'red');
                        } else {
                            $('#codigo-validacion').text('✓ Código disponible').css('color', 'green');
                        }
                    });
                } else {
                    $('#codigo-validacion').text('');
                }
            });
            $('#formAltaProducto').submit(function(e) {
                e.preventDefault();
                var nombre = $('#nombre').val().trim();
                var codigo = $('#codigo').val().trim();
                var costo = $('#costo').val().trim();
                var stock = $('#stock').val().trim();
                if (!nombre || !codigo || costo === '' || isNaN(costo) || parseFloat(costo) < 0 || stock === '' || isNaN(stock) || parseInt(stock) < 0) {
                    mostrarError('Faltan campos por llenar o hay datos inválidos');
                    return;
                }
                validarCodigoAjax(codigo, function(valido) {
                    if (!valido) {
                        mostrarError('El código del producto ya existe');
                        $('#codigo').focus();
                        return;
                    }
                    // Si todo está bien, enviar el formulario
                    $('#formAltaProducto')[0].submit();
                });
            });
        });
    </script>
</head>
<body>
    <?php include "menu.php"; ?>
    
    <div style="margin-top: 20px; margin-bottom: 20px; text-align: center;">
        <a href="productos_lista.php" class="button">Volver al Listado de Productos</a>
    </div>
    <h1>Registrar Nuevo Producto</h1>
    
    <div id="mensaje" class="mensaje" style="display: none;"></div>
    
    <form id="formAltaProducto" action="productos_alta.php" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre del Producto: *</label>
        <input type="text" id="nombre" name="nombre" required>
        
        <label for="codigo">Código: *</label>
        <input type="text" id="codigo" name="codigo" maxlength="32" required>
        <span id="codigo-validacion" style="font-size: 14px;"></span>
        
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" rows="4"></textarea>
        
        <label for="costo">Costo: *</label>
        <input type="number" id="costo" name="costo" step="0.01" min="0" value="0" required>
        
        <label for="stock">Stock: *</label>
        <input type="number" id="stock" name="stock" min="0" value="0" required>
        
        <label for="imagen">Imagen del Producto:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">
        <div id="preview"></div>
        
        <!-- Estado oculto, siempre activo al crear -->
        <input type="hidden" id="status" name="status" value="1">
        <button type="submit">Registrar Producto</button>
    </form>
</body>
</html>
