<?php
// Subir_foto.php
// Retorna el nombre encriptado de la imagen o FALSE si hay error

function subirFoto() {
    // Verificar que se recibió un archivo
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] == UPLOAD_ERR_NO_FILE) {
        return array('success' => true, 'nombre' => '', 'mensaje' => 'No se seleccionó imagen');
    }
    
    // Verificar si hubo error en la carga
    if ($_FILES['imagen']['error'] != 0) {
        return array('success' => false, 'nombre' => '', 'mensaje' => 'Error al cargar la imagen: ' . $_FILES['imagen']['error']);
    }
    
    // Obtener datos del archivo
    $nombre_real = $_FILES['imagen']['name'];
    $archivo_temporal = $_FILES['imagen']['tmp_name'];
    
    // Validar que sea una imagen
    $info = getimagesize($archivo_temporal);
    if ($info === false) {
        return array('success' => false, 'nombre' => '', 'mensaje' => 'El archivo no es una imagen válida');
    }
    
    // Obtener extensión
    $arreglo = explode(".", $nombre_real);
    $len = count($arreglo);
    $pos = $len - 1;
    $extension = strtolower($arreglo[$pos]);
    
    // Validar extensiones permitidas
    $extensiones_validas = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
    if (!in_array($extension, $extensiones_validas)) {
        return array('success' => false, 'nombre' => '', 'mensaje' => 'Extensión no permitida. Solo: jpg, jpeg, png, gif, bmp');
    }
    
    // Carpeta para guardar archivos
    // Si el formulario es de productos, usar carpeta productos
    $carpeta = "/home/rodrigo/html/archivos/";
    if (isset($_POST['codigo']) && isset($_POST['nombre']) && isset($_POST['costo']) && isset($_POST['stock']) && strpos($_SERVER['REQUEST_URI'], 'productos') !== false) {
        $carpeta = "/home/rodrigo/html/productos/";
    }
    
    // Verificar que la carpeta existe
    if (!is_dir($carpeta)) {
        return array('success' => false, 'nombre' => '', 'mensaje' => 'La carpeta de destino no existe: ' . $carpeta);
    }
    
    // Verificar permisos de escritura
    if (!is_writable($carpeta)) {
        return array('success' => false, 'nombre' => '', 'mensaje' => 'Sin permisos de escritura en: ' . $carpeta . ' (permisos: ' . substr(sprintf('%o', fileperms($carpeta)), -4) . ')');
    }
    
    // Obtener nombre encriptado
    $encriptado = md5_file($archivo_temporal);
    $nuevo_nombre = "$encriptado.$extension";
    $ruta_completa = $carpeta . $nuevo_nombre;
    
    // Intentar copiar el archivo
    if (copy($archivo_temporal, $ruta_completa)) {
        if (file_exists($ruta_completa)) {
            return array('success' => true, 'nombre' => $nuevo_nombre, 'mensaje' => 'Imagen subida correctamente');
        } else {
            return array('success' => false, 'nombre' => '', 'mensaje' => 'Copy retornó true pero el archivo no existe');
        }
    } else {
        $error = error_get_last();
        return array('success' => false, 'nombre' => '', 'mensaje' => 'Error al copiar: ' . ($error ? $error['message'] : 'desconocido'));
    }
}
