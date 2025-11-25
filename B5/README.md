# Proyecto B5 - Editar Empleado

## Descripción
Continuación del proyecto B4. Implementación de la funcionalidad "Editar" para modificar los datos de un empleado existente.

## Archivos Nuevos
- `empleados_editar_form.php` - Formulario prellenado con los datos actuales del empleado
- `empleados_editar.php` - Procesar la actualización en la base de datos
- `correo_consulta_editar.php` - Validar que el correo no exista en otro empleado

## Archivos Modificados
- `empleados_lista.php` - Actualizado enlace "Editar" para que apunte a `empleados_editar_form.php?id=X`

## Archivos Copiados de B4
- `db_connect.php` - Conexión a la base de datos
- `empleados_eliminar.php` - Endpoint AJAX para eliminación lógica
- `empleados_detalle.php` - Página de detalle del empleado
- `estilos.css` - Estilos CSS

## Funcionalidad Implementada

### Editar Empleado
1. **Formulario de Edición** (`empleados_editar_form.php`):
   - Recibe el ID del empleado por GET: `empleados_editar_form.php?id=123`
   - Consulta la base de datos y verifica que:
     * El empleado existe
     * El empleado está activo (`eliminado = 0`)
   - Prellena el formulario con los datos actuales:
     * Nombre
     * Apellidos
     * Correo
     * Rol (Ejecutivo/Gerente)
   - Campo ID oculto para enviar al procesar
   - Validación JavaScript de campos vacíos
   - Validación AJAX de correo duplicado (excepto el correo actual)
   - Botón "Volver al Listado"

2. **Validación de Correo** (`correo_consulta_editar.php`):
   - Verifica que el correo no exista en OTRO empleado
   - Permite el correo actual del empleado que se está editando
   - Query: `WHERE correo = '$correo' AND id != $id_empleado AND eliminado = 0`
   - Retorna "1" si existe, "0" si no

3. **Procesar Actualización** (`empleados_editar.php`):
   - Solo acepta peticiones POST
   - Verifica que el empleado existe y está activo
   - Actualiza: nombre, apellidos, correo, rol
   - NO actualiza: contraseña, imagen (pendiente para futuras versiones)
   - Query: `UPDATE empleados SET ... WHERE id = $id AND eliminado = 0`
   - Redirige a `empleados_lista.php` si es exitoso
   - Muestra error si falla

4. **Restricciones de Seguridad**:
   - Solo se pueden editar empleados con `eliminado = 0`
   - Si intentas editar un empleado inactivo, muestra error
   - Validación de correo duplicado en tiempo real con AJAX
   - Protección contra acceso directo (requiere POST para actualizar)

## Copiar Archivos desde Windows al Servidor (SSH/SCP)

### Copiar todos los archivos de B5
```powershell
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B5\*.php C:\Users\sambo\Documents\Programacion\GitHub\PPI\B5\*.css rodrigo@169.254.218.17:~/html/ProduccionDB/
```

### Desde el servidor, copiar a /var/www/html/ProduccionDB/
```bash
cd /var/www/html/ProduccionDB
sudo cp ~/html/ProduccionDB/* ./
```

### Ajustar permisos
```bash
sudo chmod 644 /var/www/html/ProduccionDB/*.php
sudo chmod 644 /var/www/html/ProduccionDB/*.css
sudo chown rodrigo:www-data /var/www/html/ProduccionDB/*
```

## Pruebas

1. **Acceder al listado**: `http://169.254.218.17/ProduccionDB/empleados_lista.php`
2. **Hacer clic en "Editar"** de cualquier empleado activo
3. **Verificar que el formulario** está prellenado con los datos actuales
4. **Modificar campos** (nombre, apellidos, correo, rol)
5. **Probar validación de correo**:
   - Cambiar el correo a uno que ya existe → debe mostrar "Correo ya existente"
   - Dejar el correo igual → no debe validar
   - Cambiar a un correo nuevo → debe permitir
6. **Guardar cambios** → debe redirigir al listado
7. **Verificar en el listado** que los cambios se aplicaron

## Tecnologías Utilizadas
- **Backend**: PHP 7.x, MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Librerías**: jQuery 3.3.1 (desde CDN)
- **Servidor**: Apache 2.4.65 en Raspberry Pi (Debian)

## Base de Datos
- **Tabla**: empleados
- **Campos actualizables**: nombre, apellidos, correo, rol
- **Campos NO editables**: id, pass, imagen, eliminado

## Flujo de Edición
```
empleados_lista.php 
  → Click "Editar"
  → empleados_editar_form.php?id=X
  → Modificar datos
  → Submit form
  → empleados_editar.php (UPDATE)
  → Redirect empleados_lista.php
```

## Próximos Pasos (Pendientes)
- [ ] Permitir cambiar la contraseña al editar
- [ ] Permitir cambiar la imagen al editar
- [ ] Mostrar la imagen actual en el formulario de edición
- [ ] Agregar validación más robusta (preparar sentencias SQL)
