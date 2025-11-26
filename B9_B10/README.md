# B9 - Actualización del Menú de Navegación

Esta carpeta contiene la versión B9 del sistema de gestión con menú actualizado y nueva sección de productos.

## Cambios Respecto a B8

### Menú de Navegación Actualizado
- **"Lista de Empleados"** renombrado a **"Empleados"** (más conciso)
- Nueva opción **"Productos"** agregada al menú principal
- Orden del menú: Inicio | Empleados | Productos | Registrar Empleado | Usuario | Cerrar Sesión

### Nueva Página: productos.php
- Página protegida con sesión (incluye menu.php)
- Mensaje de "En Construcción" con estilo visual
- Preparada para futura implementación del módulo de productos

## Estructura del Menú (menu.php)

```
┌─────────────────────────────────────────────────────────────────────────┐
│ Inicio │ Empleados │ Productos │ Registrar Empleado │ Usuario │ Logout │
└─────────────────────────────────────────────────────────────────────────┘
```

### Enlaces del Menú
1. **Inicio** → index.php (página de bienvenida)
2. **Empleados** → empleados_lista.php (listado de empleados)
3. **Productos** → productos.php (en construcción)
4. **Registrar Empleado** → empleados_alta_form.php
5. **Usuario: [correo]** → Información del usuario logueado
6. **Cerrar Sesión** → logout.php

## Archivos Modificados

### menu.php
- Cambio: "Lista de Empleados" → "Empleados"
- Agregado: enlace a productos.php

### productos.php (NUEVO)
- Página protegida con validación de sesión
- Incluye menu.php para navegación
- Mensaje visual de "En Construcción"
- Estilo: fondo amarillo claro con borde dorado y emoji 🚧

## Archivos Heredados de B8 (sin cambios)

Todos los demás archivos permanecen igual que en B8:
- Sistema de autenticación (login.php, login_consulta.php, logout.php)
- Gestión de empleados (lista, alta, editar, detalle, eliminar)
- Validaciones AJAX (correo_consulta.php, correo_consulta_editar.php)
- Soporte (db_connect.php, Subir_foto.php, estilos.css)
- Página principal (index.php, bienvenido_OLD.php)

## Comandos SCP para Despliegue

### Desde Windows PowerShell:

```powershell
# Copiar archivos modificados y nuevos
cd C:\Users\sambo\Documents\Programacion\GitHub\PPI\B9
scp menu.php productos.php rodrigo@169.254.218.17:~/html/ProduccionDB/

# O copiar todo para asegurar sincronización completa
scp *.php *.css rodrigo@169.254.218.17:~/html/ProduccionDB/
```

### En el servidor:

```bash
# Copiar al directorio web
sudo cp ~/html/ProduccionDB/menu.php /var/www/html/ProduccionDB/
sudo cp ~/html/ProduccionDB/productos.php /var/www/html/ProduccionDB/

# Establecer permisos
cd /var/www/html/ProduccionDB/
sudo chown rodrigo:www-data menu.php productos.php
sudo chmod 644 menu.php productos.php

# Verificar
ls -la | grep -E "menu|productos"
```

## Pruebas de Funcionalidad

### 1. Verificar Menú Actualizado
- Iniciar sesión
- Verificar que aparece "Empleados" (no "Lista de Empleados")
- Verificar que aparece nueva opción "Productos"
- Confirmar orden correcto de opciones

### 2. Probar Página Productos
- Hacer clic en "Productos" desde el menú
- Verificar que carga productos.php
- Confirmar mensaje "En Construcción" visible
- Verificar que el menú está presente y funcional
- Probar navegación de regreso a Inicio

### 3. Validar Protección de Sesión
- Intentar acceder directamente a productos.php sin login
- Resultado esperado: Redirección automática a login.php

### 4. Navegación Completa
- Probar todos los enlaces del menú actualizado
- Verificar que todas las páginas mantienen el menú consistente

## Diseño Visual de productos.php

```
┌────────────────────────────────────────┐
│  Menú de Navegación (verde)           │
├────────────────────────────────────────┤
│                                        │
│  Productos                            │
│                                        │
│  ╔════════════════════════════════╗  │
│  ║  🚧 En Construcción 🚧        ║  │
│  ║                                ║  │
│  ║  Esta sección está...          ║  │
│  ║  Próximamente podrás...        ║  │
│  ╚════════════════════════════════╝  │
│  (fondo amarillo claro)               │
│                                        │
└────────────────────────────────────────┘
```

## Próximos Pasos Sugeridos

- [ ] Implementar CRUD de productos (similar a empleados)
- [ ] Agregar tabla `productos` en la base de datos
- [ ] Crear formularios de alta/edición de productos
- [ ] Implementar listado de productos con búsqueda
- [ ] Relacionar productos con empleados (quién registró/modificó)
- [ ] Agregar imágenes de productos (reutilizar Subir_foto.php)

## Notas de Implementación

1. **Consistencia del Menú**: Todos los archivos existentes ya incluyen menu.php, por lo que automáticamente mostrarán las opciones actualizadas
2. **Protección de Sesión**: productos.php está protegida mediante la inclusión de menu.php que valida sesión
3. **Escalabilidad**: La estructura modular permite agregar más secciones fácilmente
4. **Estilo Coherente**: productos.php usa estilos.css existente para mantener consistencia visual

## Testing Checklist

- [ ] Login exitoso muestra menú actualizado
- [ ] Opción "Empleados" funciona correctamente
- [ ] Opción "Productos" carga la página en construcción
- [ ] Menú visible en productos.php
- [ ] Navegación entre secciones funcional
- [ ] Protección de sesión en productos.php
- [ ] Estilos CSS aplicados correctamente
- [ ] Sin errores en consola del navegador

## Planes Futuros

### Mejoras en el Flujo de Desarrollo

#### 1. Script de Despliegue Automático
Crear un script PowerShell para automatizar el proceso completo de despliegue:

```powershell
# deploy.ps1 - Script de despliegue automático
param([string]$carpeta = "B9")

# Copiar archivos al servidor
scp -r "C:\Users\sambo\Documents\Programacion\GitHub\PPI\$carpeta\*.php" `
       "C:\Users\sambo\Documents\Programacion\GitHub\PPI\$carpeta\*.css" `
       rodrigo@169.254.218.17:~/html/ProduccionDB/

# Ejecutar comandos en el servidor
ssh rodrigo@169.254.218.17 @"
    sudo cp ~/html/ProduccionDB/*.php /var/www/html/ProduccionDB/
    sudo cp ~/html/ProduccionDB/*.css /var/www/html/ProduccionDB/
    sudo chown rodrigo:www-data /var/www/html/ProduccionDB/*.php /var/www/html/ProduccionDB/*.css
    sudo chmod 644 /var/www/html/ProduccionDB/*.php /var/www/html/ProduccionDB/*.css
    ls -la /var/www/html/ProduccionDB/ | tail -20
"@

Write-Host "Despliegue completado exitosamente" -ForegroundColor Green
```

**Beneficios:**
- Un solo comando para desplegar
- Reduce errores humanos
- Acelera el ciclo de desarrollo
- Muestra confirmación visual

**Uso:**
```powershell
.\deploy.ps1 -carpeta "B9"
```

#### 2. Integración con MCP (Model Context Protocol)

**Servidor MCP para MariaDB**
Configurar un servidor MCP que permita acceso directo a la base de datos ProduccionDB desde VS Code.

**Capacidades que ofrecería:**
- Consultas SQL directas sin necesidad de phpMyAdmin
- Validación de estructura de tablas en tiempo real
- Generación automática de código PHP basado en esquema DB
- Debugging de consultas durante desarrollo
- Verificación de datos sin acceso web

**Configuración propuesta:**
```json
{
  "mcpServers": {
    "mariadb-produccion": {
      "command": "npx",
      "args": ["-y", "@modelcontextprotocol/server-mysql"],
      "env": {
        "MYSQL_HOST": "169.254.218.17",
        "MYSQL_USER": "tu_usuario",
        "MYSQL_PASSWORD": "tu_password",
        "MYSQL_DATABASE": "ProduccionDB"
      }
    }
  }
}
```

**Casos de uso:**
- Verificar correos duplicados antes de codificar validaciones
- Consultar estructura de tablas para formularios
- Probar queries complejas antes de integrar en PHP
- Análisis de datos para reportes

#### 3. Script de Backup Automatizado

```powershell
# backup.ps1 - Backup de base de datos y archivos
ssh rodrigo@169.254.218.17 @"
    # Backup de base de datos
    mysqldump -u root -p ProduccionDB > ~/backups/ProduccionDB_$(date +%Y%m%d_%H%M%S).sql
    
    # Backup de imágenes
    tar -czf ~/backups/archivos_$(date +%Y%m%d_%H%M%S).tar.gz /home/rodrigo/html/archivos/
    
    # Backup de código
    tar -czf ~/backups/ProduccionDB_web_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/html/ProduccionDB/
"@
```

### Mejoras del Sistema Web

#### Módulo de Productos Completo
- [ ] Crear tabla `productos` en base de datos
- [ ] Implementar CRUD completo de productos
- [ ] Agregar imágenes de productos
- [ ] Sistema de categorías
- [ ] Control de inventario básico
- [ ] Relación con empleados (quién registró/modificó)

#### Sistema de Roles y Permisos
- [ ] Tabla de roles (Administrador, Gerente, Empleado)
- [ ] Restricción de acceso según rol
- [ ] Auditoría de acciones por rol

#### Seguridad Avanzada
- [ ] Timeout de sesión (30 minutos de inactividad)
- [ ] Tokens CSRF en formularios
- [ ] Registro de auditoría (log de acciones)
- [ ] Recuperación de contraseña por email
- [ ] Cambio de contraseña desde perfil

#### Mejoras de UX/UI
- [ ] Paginación en listados largos
- [ ] Búsqueda y filtros avanzados
- [ ] Ordenamiento de columnas
- [ ] Exportación a Excel/PDF
- [ ] Notificaciones visuales mejoradas
- [ ] Tema oscuro/claro

### Infraestructura

#### Monitoreo y Logging
- [ ] Sistema de logs centralizado
- [ ] Monitoreo de errores PHP
- [ ] Estadísticas de uso del sistema
- [ ] Alertas por email en errores críticos

#### Performance
- [ ] Cache de consultas frecuentes
- [ ] Optimización de imágenes
- [ ] Compresión gzip en Apache
- [ ] CDN para recursos estáticos

### Documentación
- [ ] Manual de usuario
- [ ] Documentación técnica completa
- [ ] Diagramas de flujo
- [ ] Guía de troubleshooting
