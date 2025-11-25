# B8 - Sistema de Gestión de Empleados con Sesiones y Menú Protegido

Esta carpeta contiene la versión B8 del sistema de gestión de empleados con implementación completa de sesiones PHP y menú de navegación protegido.

## Características Principales

### Sistema de Sesiones
- Creación de variables de sesión al login exitoso (`usuario_id`, `usuario_correo`, `usuario_nombre`, `sesion_activa`)
- Validación de sesión en todas las páginas protegidas
- Redirección automática a login si no hay sesión activa

### Menú Protegido (menu.php)
- Verifica existencia de sesión activa
- Redirige a login.php si no hay sesión
- Muestra información del usuario (correo)
- Enlaces a páginas protegidas (Lista de Empleados, Registrar Empleado)
- Botón de Cerrar Sesión que destruye la sesión
- Incluido en todas las páginas del sistema

### Seguridad
- Todas las páginas verifican sesión activa
- Endpoints de procesamiento validan sesión
- Logout destruye completamente la sesión

## Archivos PHP

### Sistema de Autenticación
- `login.php` - Página de login con validación AJAX
- `login_consulta.php` - Endpoint que crea sesión al login exitoso
- `index.php` - Página principal (antes `bienvenido.php`)
    - Se mantiene copia histórica en `bienvenido_OLD.php`
- `logout.php` - Cierra sesión y redirige a login
- `menu.php` - Menú protegido incluido en todas las páginas

### Gestión de Empleados
- `empleados_lista.php` - Lista empleados activos (protegida)
- `empleados_detalle.php` - Detalle de empleado (protegida)
- `empleados_alta_form.php` - Formulario para registrar (protegida)
- `empleados_alta.php` - Procesa registro (protegida)
- `empleados_editar_form.php` - Formulario de edición (protegida)
- `empleados_editar.php` - Procesa edición (protegida)
- `empleados_eliminar.php` - Endpoint AJAX soft delete (protegido)

### Validaciones
- `correo_consulta.php` - Valida correo único en registros (protegida)
- `correo_consulta_editar.php` - Valida correo único excluyendo empleado actual (protegida)

### Soporte
- `db_connect.php` - Conexión a base de datos
- `Subir_foto.php` - Función para subir imágenes
- `estilos.css` - Estilos CSS unificados

## Flujo de Sesiones

### 1. Inicio de Sesión
```
login.php (formulario)
    ↓ AJAX
login_consulta.php (crea $_SESSION)
    ↓ Éxito
index.php (incluye menu.php)
```

### 2. Navegación Protegida
```
empleados_lista.php
    ↓ Incluye menu.php al inicio
    ↓ menu.php valida $_SESSION
    ↓ Si sesión válida → Carga menú
    ↓ Si sin sesión → Redirige a login.php
```

### 3. Cierre de Sesión
```
logout.php
    ↓ Destruye sesión completamente
    ↓ Redirige a login.php
```

## Variables de Sesión

- `$_SESSION['usuario_id']` - ID del usuario logueado
- `$_SESSION['usuario_correo']` - Correo del usuario
- `$_SESSION['usuario_nombre']` - Nombre completo del usuario
- `$_SESSION['sesion_activa']` - Flag indicador de sesión activa

## Comandos SCP para Copiar Archivos

### Desde Windows PowerShell:

```powershell
# Copiar todos los archivos de una vez
cd C:\Users\sambo\Documents\Programacion\GitHub\PPI\B8
scp *.php *.css rodrigo@169.254.218.17:~/html/ProduccionDB/
```

### En el servidor:

```bash
# Copiar al directorio web
sudo cp ~/html/ProduccionDB/* /var/www/html/ProduccionDB/

# Establecer permisos
cd /var/www/html/ProduccionDB/
sudo chown rodrigo:www-data *.php *.css
sudo chmod 644 *.php *.css

# Verificar
ls -la | grep -E "menu|login|index|logout"
```

## Flujo de Seguridad Completo

### Validación en Cada Página Protegida

```php
<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir menu.php (valida y redirige si es necesario)
include "menu.php";

// Si llegamos aquí, sesión es válida
// Resto del código...
?>
```

### Alternativa: Validación Manual

```php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar sesión manualmente
if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Código protegido...
?>
```

## Pruebas de Seguridad

### 1. Intento de Acceso Directo sin Sesión
```
Acceder a: http://169.254.218.17/ProduccionDB/empleados_lista.php
Resultado: Redirige automáticamente a login.php
```

### 2. Cierre de Sesión
```
Hacer clic en "Cerrar Sesión"
Resultado: Se destruye sesión y redirige a login.php
```

### 3. Intento de Acceso Después de Logout
```
Presionar botón atrás
Resultado: No accede sin nueva autenticación (caché del navegador solo)
Acceder a URL directa: Redirige a login.php
```

### 4. Validación de Endpoints
```
POST a empleados_eliminar.php sin sesión
Resultado: Retorna "0" (fallo)
```

## Variables de Sesión Mostradas

En el menú aparece:
- "Usuario: [correo]" - Identifica al usuario logueado
- "Cerrar Sesión" - Botón para logout

## Mejoras Futuras

- [ ] Implementar timeout de sesión (30 minutos de inactividad)
- [ ] Token CSRF en formularios POST
- [ ] Registro de auditoría (quién hizo qué y cuándo)
- [ ] Roles y permisos diferenciados (Gerente vs Ejecutivo)
- [ ] Recuperación de contraseña
- [ ] Cambio de contraseña desde el perfil
- [ ] Historial de cambios de empleados

## Notas de Implementación

1. **Session Start**: Debe estar al inicio del archivo antes de cualquier output
2. **Menu.php**: Incluirlo después de `<?php ?>` tags para permitir redirección
3. **Validación Doble**: Algunos archivos validan manualmente + incluyen menu.php
4. **Estado de Sesión**: Verificar con `session_status()` para no reiniciar innecesariamente

## Testing Checklist

- [ ] Login con credenciales válidas
- [ ] Login con credenciales inválidas
- [ ] Acceso directo a página protegida sin sesión
- [ ] Logout funcional
- [ ] Menú muestra en todas las páginas protegidas
- [ ] Correo del usuario visible en menú
- [ ] Navegación entre páginas mantiene sesión
- [ ] AJAX en eliminación valida sesión
- [ ] Formularios POST procesan correctamente
