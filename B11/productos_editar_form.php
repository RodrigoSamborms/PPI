<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        var codigoValido = true; // Inicialmente válido (es el código actual)
        var codigoOriginal = '';
        
        function mostrarMensaje(mensaje, tipo) {
            var mensajeDiv = $('#mensaje');
            mensajeDiv.removeClass().addClass('mensaje ' + tipo).text(mensaje).fadeIn();
            setTimeout(function() {
                mensajeDiv.fadeOut();
            }, 5000);
        }
        
        function validarCodigo() {
            var codigo = $('#codigo').val().trim();
            var id = $('#producto_id').val();
            
            if (codigo === '' || codigo === codigoOriginal) {
                $('#codigo-validacion').text('');
                codigoValido = true;
                return;
            }
            
            $.ajax({
                url: 'codigo_consulta_editar.php',
                type: 'POST',
                data: { codigo: codigo, id: id },
                success: function(response) {
                    if (response == '1') {
                        $('#codigo-validacion').text('✓ Código disponible').css('color', 'green');
                        codigoValido = true;
                    } else {
                        $('#codigo-validacion').text('✗ Código ya existe').css('color', 'red');
                        codigoValido = false;
                    }
                }
            });
        }
        
        function previsualizarImagen() {
            var archivo = $('#imagen')[0].files[0];
            if (archivo) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').html('<p>Nueva imagen:</p><img src="' + e.target.result + '" style="max-width: 200px; max-height: 200px;">');
                }
                reader.readAsDataURL(archivo);
            } else {
                $('#preview').html('');
            }
        }
        
        function validarFormulario() {
            $('#mensaje').hide();
            
            var nombre = $('#nombre').val().trim();
            var codigo = $('#codigo').val().trim();
            var costo = $('#costo').val().trim();
            var stock = $('#stock').val().trim();
            
            if (nombre === '') {
                mostrarMensaje('El nombre del producto es obligatorio', 'error');
                $('#nombre').focus();
                return false;
            }
            
            if (codigo === '') {
                mostrarMensaje('El código del producto es obligatorio', 'error');
                $('#codigo').focus();
                return false;
            }
            
            if (!codigoValido) {
                mostrarMensaje('El código del producto no es válido o ya existe', 'error');
                $('#codigo').focus();
                return false;
            }
            
            if (costo === '' || isNaN(costo) || parseFloat(costo) < 0) {
                mostrarMensaje('Ingresa un costo válido', 'error');
                $('#costo').focus();
                return false;
            }
            
            if (stock === '' || isNaN(stock) || parseInt(stock) < 0) {
                mostrarMensaje('Ingresa un stock válido', 'error');
                $('#stock').focus();
                return false;
            }
            
            return true;
        }
        
        $(document).ready(function() {
            codigoOriginal = $('#codigo').val();
            $('#codigo').blur(validarCodigo);
            $('#imagen').change(previsualizarImagen);
        });
    </script>
</head>
<body>
    <?php include "menu.php"; ?>
    
    <h1>Editar Producto</h1>
    
    <div id="mensaje" class="mensaje" style="display: none;"></div>
    
    <?php
    include "db_connect.php";
    
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        
        $sql = "SELECT * FROM productos WHERE id = $id AND eliminado = 0";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            
            <form action="productos_editar.php" method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario();">
                <input type="hidden" id="producto_id" name="id" value="<?php echo $row['id']; ?>">
                
                <label for="nombre">Nombre del Producto: *</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($row['nombre']); ?>" required>
                
                <label for="codigo">Código: *</label>
                <input type="text" id="codigo" name="codigo" maxlength="32" value="<?php echo htmlspecialchars($row['codigo']); ?>" required>
                <span id="codigo-validacion" style="font-size: 14px;"></span>
                
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4"><?php echo htmlspecialchars($row['descripcion']); ?></textarea>
                
                <label for="costo">Costo: *</label>
                <input type="number" id="costo" name="costo" step="0.01" min="0" value="<?php echo $row['costo']; ?>" required>
                
                <label for="stock">Stock: *</label>
                <input type="number" id="stock" name="stock" min="0" value="<?php echo $row['stock']; ?>" required>
                
                <?php if ($row['imagen']): ?>
                    <label>Imagen Actual:</label>
                    <?php
                    $ruta_imagen = "/home/rodrigo/html/archivos/" . $row['imagen'];
                    if (file_exists($ruta_imagen)) {
                        $imagen_size = getimagesize($ruta_imagen);
                        if ($imagen_size !== false) {
                            $imagen_data = base64_encode(file_get_contents($ruta_imagen));
                            $mime_type = $imagen_size['mime'];
                            echo "<div class='imagen-preview'>";
                            echo "<img src='data:$mime_type;base64,$imagen_data' alt='Imagen actual'>";
                            echo "</div>";
                        }
                    }
                    ?>
                <?php endif; ?>
                
                <label for="imagen">Nueva Imagen (opcional):</label>
                <input type="file" id="imagen" name="imagen" accept="image/*">
                <small>Deja vacío para mantener la imagen actual</small>
                <div id="preview"></div>
                
                <label for="status">Estado:</label>
                <select id="status" name="status">
                    <option value="1" <?php echo $row['status'] == 1 ? 'selected' : ''; ?>>Activo</option>
                    <option value="0" <?php echo $row['status'] == 0 ? 'selected' : ''; ?>>Inactivo</option>
                </select>
                
                <button type="submit">Guardar Cambios</button>
            </form>
            
            <?php
        } else {
            echo "<div class='mensaje error'>Producto no encontrado.</div>";
            echo "<br><a href='productos_lista.php' class='boton'>Volver a la Lista</a>";
        }
    } else {
        echo "<div class='mensaje error'>ID de producto no especificado.</div>";
        echo "<br><a href='productos_lista.php' class='boton'>Volver a la Lista</a>";
    }
    
    $conn->close();
    ?>
</body>
</html>
