# Proyecto B4 - Ver Detalle de Empleado

## Descripción
Continuación del proyecto B3. Implementación de la funcionalidad "Ver Detalle" para mostrar la información completa de un empleado.

## Archivos Nuevos
- `empleados_detalle.php` - Página que muestra el detalle completo de un empleado

## Archivos Modificados
- `empleados_lista.php` - Actualizado enlace "Ver Detalle" para que apunte a `empleados_detalle.php?id=X`
- `estilos.css` - Agregados estilos para la tarjeta de detalle (`.detalle-card`, `.status`)

## Archivos Copiados de B3
- `db_connect.php` - Conexión a la base de datos
- `empleados_eliminar.php` - Endpoint AJAX para eliminación lógica

## Funcionalidad Implementada

### Ver Detalle del Empleado
- Recibe el ID del empleado por GET: `empleados_detalle.php?id=123`
- Muestra la información del empleado:
  - ID
  - Nombre Completo (nombre + apellidos)
  - Correo Electrónico
  - Rol (Gerente o Ejecutivo)
  - Status (Activo/Inactivo basado en el campo `eliminado`)
- Validaciones:
  - Si no se recibe ID, redirige a `empleados_lista.php`
  - Si el empleado no existe, muestra mensaje de error
- Botón "Volver al Listado" para regresar a `empleados_lista.php`

### Estilos de la Tarjeta de Detalle
```css
.detalle-card - Tarjeta contenedora con sombra y bordes redondeados
.campo - Cada campo de información
.label - Etiqueta del campo (bold)
.valor - Valor del campo
.status.activo - Badge verde para empleados activos
.status.inactivo - Badge rojo para empleados inactivos
```

## Copiar Archivos desde Windows al Servidor (SSH/SCP)

### Copiar archivos específicos de B4
```powershell
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B4\empleados_detalle.php C:\Users\sambo\Documents\Programacion\GitHub\PPI\B4\empleados_lista.php C:\Users\sambo\Documents\Programacion\GitHub\PPI\B4\estilos.css rodrigo@169.254.218.17:/var/www/html/ProduccionDB/
```

### Copiar todos los archivos PHP de B4
```powershell
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B4\*.php rodrigo@169.254.218.17:/var/www/html/ProduccionDB/
```

### Copiar archivos CSS
```powershell
scp C:\Users\sambo\Documents\Programacion\GitHub\PPI\B4\*.css rodrigo@169.254.218.17:/var/www/html/ProduccionDB/
```

## Configuración de Permisos en el Servidor

Después de copiar los archivos, aplicar permisos:

```bash
# Ajustar permisos de archivos (644)
sudo chmod 644 /var/www/html/ProduccionDB/empleados_detalle.php
sudo chmod 644 /var/www/html/ProduccionDB/empleados_lista.php
sudo chmod 644 /var/www/html/ProduccionDB/estilos.css

# O para todos los archivos del proyecto
sudo find /var/www/html/ProduccionDB -type f -exec chmod 644 {} \;
sudo chown -R rodrigo:www-data /var/www/html/ProduccionDB
```

## Pruebas

1. **Verificar listado**: Acceder a `http://169.254.218.17/ProduccionDB/empleados_lista.php`
2. **Probar "Ver Detalle"**: Hacer clic en el enlace "Ver Detalle" de cualquier empleado
3. **Verificar información**: Confirmar que se muestran todos los campos correctamente
4. **Probar status**: Verificar que empleados activos muestren badge verde
5. **Probar botón**: Hacer clic en "Volver al Listado" para regresar

## Tecnologías Utilizadas
- **Backend**: PHP 7.x, MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Librerías**: jQuery 3.3.1 (desde CDN)
- **Servidor**: Apache 2.4.65 en Raspberry Pi (Debian)

## Próximos Pasos (Pendientes)
- [ ] Implementar "Editar" empleado
- [ ] Mostrar imagen del empleado en el detalle
- [ ] Validar que solo se puedan ver empleados activos (eliminado = 0)
