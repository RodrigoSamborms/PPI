# B7 - Sistema de Login

Esta carpeta contiene la versión B7 del sistema de gestión de empleados, que implementa el sistema de autenticación (login).

## Archivos PHP

### Archivos de Base de Datos
- `db_connect.php` - Conexión a la base de datos ProduccionDB

### Archivos de Autenticación
- `login.php` - Página de inicio de sesión con:
  - Formulario de correo electrónico y contraseña
  - Validación de campos vacíos con AJAX
  - Validación de formato de correo electrónico
  - Encriptación MD5 de contraseña en el cliente
  - Manejo de errores con mensajes temporales (5 segundos)
  - Soporte para tecla Enter para enviar formulario
  - Indicador de "Verificando..." durante el proceso

- `login_consulta.php` - Endpoint AJAX que verifica:
  - Si el correo existe en la base de datos
  - Si la contraseña (MD5) coincide
  - Si el empleado está activo (eliminado = 0)
  - Retorna "1" si todo es correcto, "0" en caso contrario

- `bienvenido.php` - Página de bienvenida después del login exitoso
  - Muestra mensaje de "Bienvenido al Sistema de Gestión de Empleados"
  - Botón para cerrar sesión (regresa a login.php)
  - Por el momento solo muestra el mensaje, más adelante tendrá funcionalidad completa

### Archivos de Estilos
- `estilos.css` - Estilos CSS con:
  - Diseño moderno con gradiente de fondo
  - Formulario centrado con sombras
  - Animaciones suaves
  - Diseño responsivo
  - Botones con efectos hover
  - Mensajes de error y éxito

## Funcionalidad de Login

### Validación del Formulario
1. **Campos Vacíos**: Valida que correo y contraseña no estén vacíos
2. **Formato de Correo**: Valida formato válido de email con regex
3. **Encriptación**: La contraseña se encripta con MD5 en el cliente antes de enviarse
4. **AJAX**: Todo el proceso se maneja sin recargar la página

### Consulta de Autenticación
La consulta SQL verifica tres condiciones simultáneamente:
```sql
SELECT COUNT(*) FROM empleados 
WHERE correo = '$correo' AND pass = '$pass' AND eliminado = 0
```

Retorna:
- `1` = Usuario existe, contraseña correcta y está activo → Redirige a bienvenido.php
- `0` = Usuario no existe, contraseña incorrecta o está inactivo → Muestra error

### Mensajes de Error
- "El campo de correo electrónico es obligatorio"
- "El campo de contraseña es obligatorio"
- "Por favor ingrese un correo electrónico válido"
- "Correo o contraseña incorrectos, o el usuario no está activo"
- "Error de comunicación con el servidor"

## Comandos SCP para Copiar Archivos al Servidor

### Desde Windows PowerShell:

```powershell
# Copiar archivos PHP individuales
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B7\db_connect.php rodrigo@169.254.218.17:~/html/ProduccionDB/
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B7\login.php rodrigo@169.254.218.17:~/html/ProduccionDB/
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B7\login_consulta.php rodrigo@169.254.218.17:~/html/ProduccionDB/
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B7\bienvenido.php rodrigo@169.254.218.17:~/html/ProduccionDB/

# Copiar archivo CSS
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B7\estilos.css rodrigo@169.254.218.17:~/html/ProduccionDB/

# O copiar todos los archivos de una vez
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B7\*.php rodrigo@169.254.218.17:~/html/ProduccionDB/
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B7\*.css rodrigo@169.254.218.17:~/html/ProduccionDB/
```

### En el servidor (Raspberry Pi):

```bash
# Copiar archivos al directorio web
sudo cp ~/html/ProduccionDB/* /var/www/html/ProduccionDB/

# Establecer permisos correctos
cd /var/www/html/ProduccionDB/
sudo chown rodrigo:www-data *.php *.css
sudo chmod 644 *.php *.css

# Verificar archivos
ls -la | grep -E "login|bienvenido"
```

## Pruebas

### 1. Probar Validación de Campos Vacíos
```bash
# En el navegador
http://169.254.218.17/ProduccionDB/login.php
# Intentar enviar sin llenar campos
# Debe mostrar mensajes de error
```

### 2. Probar Validación de Formato de Correo
```bash
# Ingresar correo inválido (sin @, sin dominio, etc.)
# Debe mostrar: "Por favor ingrese un correo electrónico válido"
```

### 3. Probar Login con Usuario Inexistente
```bash
# Ingresar correo que no existe en la base de datos
# Debe mostrar: "Correo o contraseña incorrectos, o el usuario no está activo"
```

### 4. Probar Login con Contraseña Incorrecta
```bash
# Ingresar correo válido pero contraseña incorrecta
# Debe mostrar el mismo mensaje de error
```

### 5. Probar Login con Usuario Inactivo
```bash
# Usar correo de empleado con eliminado = 1
# Debe mostrar el mismo mensaje de error
```

### 6. Probar Login Exitoso
```bash
# Ingresar correo y contraseña correctos de empleado activo
# Debe redirigir a bienvenido.php
# Ejemplo: rodrigo@mail.mx (si existe en la BD)
```

### 7. Verificar Empleados Activos en la Base de Datos
```bash
# En el servidor
ssh rodrigo@169.254.218.17
mysql -u ProduccionDBAdmin -p1234 ProduccionDB
SELECT id, nombre, apellidos, correo, eliminado FROM empleados;
# Verificar que existan empleados con eliminado = 0
```

### 8. Probar Tecla Enter
```bash
# En el formulario, presionar Enter en cualquier campo
# Debe enviar el formulario sin necesidad de hacer clic en el botón
```

## Seguridad

### Consideraciones Actuales:
- Contraseñas encriptadas con MD5 (básico)
- Validación de formato de correo
- Protección contra campos vacíos
- Verificación de usuario activo

### Mejoras Futuras (Próximos Pasos):
- [ ] Implementar sesiones PHP para mantener el login
- [ ] Usar password_hash() en lugar de MD5
- [ ] Agregar protección contra inyección SQL (prepared statements)
- [ ] Implementar límite de intentos fallidos
- [ ] Agregar CAPTCHA después de varios intentos
- [ ] Registrar intentos de login en logs
- [ ] Agregar recuperación de contraseña
- [ ] Implementar token CSRF

## Integración Futura

Este sistema de login será la base para:
- Controlar acceso a todas las páginas del sistema
- Identificar al usuario que realiza operaciones
- Mostrar diferentes opciones según el rol (Gerente/Ejecutivo)
- Registrar quién crea, edita o elimina empleados
- Implementar permisos por rol

## Notas Importantes

1. **MD5**: Se usa MD5 por simplicidad, pero NO es seguro para producción. Más adelante se debe migrar a bcrypt o Argon2.

2. **Sin Sesiones**: Por el momento no se mantiene la sesión, cualquiera puede acceder a `bienvenido.php` directamente. Esto se implementará en versiones futuras.

3. **Diseño Visual**: El diseño es más elaborado que las páginas anteriores para dar una mejor primera impresión en el login.

4. **Mensajes Genéricos**: Los mensajes de error no revelan si el problema es el correo o la contraseña (buena práctica de seguridad).

5. **Caché del Navegador**: Si los estilos no se reflejan, presionar `Ctrl+F5` o `Ctrl+Shift+R`.

## Próximos Pasos

- [ ] Implementar sistema de sesiones PHP
- [ ] Proteger todas las páginas del sistema con verificación de sesión
- [ ] Agregar menú de navegación en bienvenido.php
- [ ] Mostrar nombre del usuario logueado
- [ ] Implementar cierre de sesión funcional
- [ ] Agregar redirección automática si ya existe sesión activa
