# B6 - Gestión de Empleados con Imágenes

Esta carpeta contiene la versión B6 del sistema de gestión de empleados, que incluye funcionalidad completa para manejar imágenes de perfil de los empleados.

## Archivos PHP

### Archivos de Base de Datos
- `db_connect.php` - Conexión a la base de datos ProduccionDB

### Archivos de Gestión de Imágenes
- `Subir_foto.php` - Función para subir y procesar imágenes con:
  - Validación de tipo de archivo (jpg, jpeg, png, gif, bmp)
  - Validación con `getimagesize()`
  - Encriptación MD5 del nombre del archivo
  - Guardado en `/home/rodrigo/html/archivos/`
  - Retorno de array con éxito, nombre y mensaje

### Archivos de Empleados
- `empleados_lista.php` - Lista de empleados activos con opciones para:
  - Ver detalle
  - Editar empleado
  - Eliminar empleado (soft delete)
  - Link para registrar nuevo empleado

- `empleados_detalle.php` - Detalle completo del empleado incluyendo:
  - Nombre completo
  - Correo electrónico
  - Rol (Gerente/Ejecutivo)
  - Status (Activo/Inactivo) con badges de colores
  - **Imagen de perfil** (o mensaje "Imagen no disponible" si no existe)

- `empleados_editar_form.php` - Formulario de edición con:
  - Campos pre-llenados con datos actuales
  - Campo de contraseña opcional (dejar vacío para no modificar)
  - **Campo de imagen opcional** (dejar vacío para no modificar)
  - Muestra nombre de imagen actual si existe
  - Validación AJAX de correo electrónico
  - Encriptación MD5 de contraseña en el cliente

- `empleados_editar.php` - Procesador de actualización que maneja:
  - Actualización de datos básicos (nombre, apellidos, correo, rol)
  - **Actualización opcional de imagen** usando `Subir_foto()`
  - Actualización opcional de contraseña con MD5
  - Traducción de errores MySQL al español
  - Redirección a lista después de éxito

- `empleados_eliminar.php` - Endpoint AJAX para eliminación lógica (soft delete)

- `correo_consulta_editar.php` - Endpoint AJAX para validar correo único (excluye empleado actual)

### Archivos de Estilos
- `estilos.css` - Estilos CSS incluyendo:
  - Estilos para tablas y formularios
  - Badges de status (activo/inactivo)
  - Clase `.imagen-detalle` para mostrar imágenes de perfil
  - Clase `.imagen-preview` para vistas previas
  - Estilos responsivos

## Funcionalidad de Imágenes

### Subida de Imagen
1. El usuario selecciona un archivo de imagen en el formulario de edición
2. El archivo se valida en el servidor con `getimagesize()`
3. Se genera un nombre único usando `md5_file()` + extensión original
4. La imagen se copia a `/home/rodrigo/html/archivos/`
5. El nombre encriptado se guarda en el campo `imagen` de la tabla `empleados`

### Visualización de Imagen
- En `empleados_detalle.php`:
  - Si el campo `imagen` tiene valor: se muestra la imagen desde `http://169.254.218.17/archivos/[nombre_imagen]`
  - Si el campo `imagen` está vacío o es NULL: se muestra "Imagen no disponible" en gris cursiva

### Edición de Imagen
- En `empleados_editar_form.php`:
  - Se muestra el nombre de la imagen actual si existe
  - Campo de archivo opcional con mensaje "Dejar este campo vacío si no desea cambiar la imagen"
  - Si no se selecciona archivo nuevo: se mantiene la imagen actual
  - Si se selecciona archivo nuevo: se reemplaza la imagen

## Comandos SCP para Copiar Archivos al Servidor

### Desde Windows PowerShell:

```powershell
# Copiar archivos PHP individuales
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B6\db_connect.php rodrigo@169.254.218.17:~/html/ProduccionDB/
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B6\Subir_foto.php rodrigo@169.254.218.17:~/html/ProduccionDB/
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B6\empleados_lista.php rodrigo@169.254.218.17:~/html/ProduccionDB/
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B6\empleados_detalle.php rodrigo@169.254.218.17:~/html/ProduccionDB/
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B6\empleados_editar_form.php rodrigo@169.254.218.17:~/html/ProduccionDB/
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B6\empleados_editar.php rodrigo@169.254.218.17:~/html/ProduccionDB/
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B6\empleados_eliminar.php rodrigo@169.254.218.17:~/html/ProduccionDB/
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B6\correo_consulta_editar.php rodrigo@169.254.218.17:~/html/ProduccionDB/

# Copiar archivo CSS
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B6\estilos.css rodrigo@169.254.218.17:~/html/ProduccionDB/

# O copiar todos los archivos de una vez
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B6\*.php rodrigo@169.254.218.17:~/html/ProduccionDB/
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B6\*.css rodrigo@169.254.218.17:~/html/ProduccionDB/
```

### En el servidor (Raspberry Pi):

```bash
# Copiar archivos al directorio web
sudo cp ~/html/ProduccionDB/* /var/www/html/ProduccionDB/

# Establecer permisos correctos
cd /var/www/html/ProduccionDB/
sudo chown rodrigo:www-data *.php *.css
sudo chmod 644 *.php *.css

# Verificar permisos del directorio de imágenes
ls -la /home/rodrigo/html/archivos/
# Debe mostrar: drwxrwxr-x rodrigo www-data

# Si es necesario ajustar permisos de imágenes
sudo chown rodrigo:www-data /home/rodrigo/html/archivos/*
sudo chmod 644 /home/rodrigo/html/archivos/*
```

## Pruebas

### 1. Probar Edición con Imagen Nueva
```bash
# En el navegador
http://169.254.218.17/ProduccionDB/empleados_lista.php
# Click en "Editar" de un empleado
# Seleccionar una imagen nueva
# Verificar que se actualiza correctamente
```

### 2. Probar Detalle con Imagen
```bash
# Verificar empleado CON imagen
http://169.254.218.17/ProduccionDB/empleados_detalle.php?id=1
# Debe mostrar la imagen

# Verificar empleado SIN imagen (si existe uno)
http://169.254.218.17/ProduccionDB/empleados_detalle.php?id=X
# Debe mostrar "Imagen no disponible"
```

### 3. Probar Edición sin Cambiar Imagen
```bash
# Editar un empleado
# NO seleccionar archivo de imagen
# Modificar solo nombre o correo
# Verificar que la imagen anterior se mantiene
```

### 4. Verificar Permisos de Archivos
```bash
# En el servidor
ssh rodrigo@169.254.218.17
ls -la /var/www/html/ProduccionDB/
# Archivos deben ser: -rw-r--r-- rodrigo www-data

ls -la /home/rodrigo/html/archivos/
# Directorio: drwxrwxr-x rodrigo www-data
# Archivos: -rw-r--r-- rodrigo www-data
```

## Estructura de la Base de Datos

La tabla `empleados` incluye el campo:
- `imagen` VARCHAR(255) NULL - Nombre del archivo de imagen (puede ser NULL)

## Notas Importantes

1. **Caché del Navegador**: Si los cambios CSS no se reflejan, presionar `Ctrl+F5` o `Ctrl+Shift+R`

2. **Formato de Imágenes**: Solo se aceptan: jpg, jpeg, png, gif, bmp

3. **Tamaño de Imagen**: No hay límite explícito, pero se recomienda usar imágenes razonables

4. **Almacenamiento**: Las imágenes se guardan con nombre MD5 en `/home/rodrigo/html/archivos/`

5. **Acceso Web**: Las imágenes son accesibles vía `http://169.254.218.17/archivos/[nombre_md5].[ext]`

6. **Campo Opcional**: El campo de imagen es OPCIONAL tanto en alta como en edición

7. **Mensaje NULL**: Si un empleado no tiene imagen, se muestra "Imagen no disponible" en gris

## Próximos Pasos

- [ ] Crear `empleados_alta_form.php` con campo de imagen opcional
- [ ] Crear `empleados_alta.php` procesador con soporte de imagen
- [ ] Agregar validación de tamaño máximo de imagen
- [ ] Agregar opción para eliminar imagen existente (establecer a NULL)
- [ ] Implementar redimensionamiento automático de imágenes grandes
- [ ] Agregar vista previa de imagen antes de subir
