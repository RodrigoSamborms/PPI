<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta de Empleado</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Alta de Empleado</h1>
    
    <form action="Empleados_Alta.php" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" required>
        
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required>
        
        <label for="pass">Contraseña:</label>
        <input type="password" id="pass" name="pass" required>
        
        <label for="rol">Rol:</label>
        <select id="rol" name="rol" required>
            <option value="">Seleccione un rol</option>
            <option value="1">Ejecutivo</option>
            <option value="2">Gerente</option>
        </select>
        
        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">
        
        <div>
            <input type="submit" value="Guardar Empleado">
            <a href="Empleados_Lista.php" class="button secondary">Cancelar</a>
        </div>
    </form>
</body>
</html>
