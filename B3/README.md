# Proyecto B3 - Sistema de Gestión de Empleados

## Descripción
Sistema web para gestionar empleados con funcionalidad de listado y eliminación lógica (soft delete) usando AJAX.

Base copiada de B2 para continuar desarrollo cronológico.

## Archivos del Proyecto
- `empleados_lista.php` - Página principal que muestra la lista de empleados
- `empleados_eliminar.php` - Endpoint AJAX para marcar empleados como eliminados
- `estilos.css` - Hoja de estilos CSS
- `db_connect.php` - Archivo de conexión a la base de datos (debe estar en el servidor)

## Configuración de Permisos en el Servidor

Cuando subas archivos al servidor Raspberry Pi, es necesario ajustar los permisos para que Apache pueda leerlos.

### Verificar permisos actuales
```bash
ls -l /var/www/html/ProduccionDB/
```

### Ajustar permisos de archivos individuales
```bash
# Para un archivo específico (ejemplo: estilos.css)
sudo chmod 644 /var/www/html/ProduccionDB/estilos.css
sudo chown rodrigo:www-data /var/www/html/ProduccionDB/estilos.css
```

### Ajustar permisos de todos los archivos del proyecto
```bash
# Cambiar a la carpeta del proyecto
cd /var/www/html/ProduccionDB/

# Ajustar permisos de directorios (755)
sudo find /var/www/html/ProduccionDB -type d -exec chmod 755 {} \;

# Ajustar permisos de archivos (644)
sudo find /var/www/html/ProduccionDB -type f -exec chmod 644 {} \;

# Ajustar dueño (opción 1: Apache como dueño)
sudo chown -R www-data:www-data /var/www/html/ProduccionDB

# Ajustar dueño (opción 2: rodrigo como dueño, Apache puede leer)
sudo chown -R rodrigo:www-data /var/www/html/ProduccionDB
```

### Script rápido para aplicar permisos después de subir archivos
```bash
# Crear y ejecutar este script cuando subas archivos nuevos
sudo find /var/www/html/ProduccionDB -type d -exec chmod 755 {} \;
sudo find /var/www/html/ProduccionDB -type f -exec chmod 644 {} \;
sudo chown -R rodrigo:www-data /var/www/html/ProduccionDB
```

## Tecnologías Utilizadas
- **Backend**: PHP 7.x, MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Librerías**: jQuery 3.3.1 (desde CDN)
- **Servidor**: Apache 2.4.65 en Raspberry Pi (Debian)

## Base de Datos
- **Nombre**: ProduccionDB
- **Usuario**: ProduccionDBAdmin
- **Tabla**: empleados
  - Campos: id, nombre, apellidos, correo, pass, rol, imagen, eliminado

## Funcionalidades Implementadas (B2)
- ✓ Listar empleados activos (eliminado = 0)
- ✓ Eliminar empleado (soft delete con confirmación)
- ✓ Actualización dinámica sin recargar página (AJAX)
- ✓ Estilos CSS profesionales

## Funcionalidades en Desarrollo (B3)
- [ ] Por definir...

## Notas de Desarrollo
- jQuery se carga desde CDN oficial: `https://code.jquery.com/jquery-3.3.1.min.js`
- El archivo local `jquery-3.3.1.min.js` no se usa debido a problemas de carga
- La eliminación es lógica, no física (marca `eliminado = 1`)
- Los archivos PHP no deben tener etiqueta de cierre `?>` para evitar errores de headers

## Copiar Archivos desde Windows al Servidor (SSH/SCP)

### Copiar un archivo individual
```powershell
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B3\archivo.php rodrigo@169.254.218.17:/var/www/html/ProduccionDB/
```

### Copiar múltiples archivos PHP
```powershell
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B3\*.php rodrigo@169.254.218.17:/var/www/html/ProduccionDB/
```

### Copiar archivos específicos
```powershell
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B3\empleados_alta.php C:\Users\sambo\Documents\Programacion\GitHub\PPI\B3\empleados_lista.php rodrigo@169.254.218.17:/var/www/html/ProduccionDB/
```

### Formato general del comando SCP
```
scp [archivo_local] [usuario]@[ip]:[ruta_destino]
```

**Nota**: Te pedirá la contraseña del usuario `rodrigo` en la Raspberry Pi.

## Despliegue
1. Subir archivos a `/var/www/html/ProduccionDB/` usando SCP (ver comandos arriba)
2. Aplicar permisos (ver comandos arriba)
3. Asegurarse que `db_connect.php` existe en el servidor
4. Acceder via: `http://169.254.218.17/ProduccionDB/empleados_lista.php`
