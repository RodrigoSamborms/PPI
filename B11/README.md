# B11 - Módulo Completo de Gestión de Productos

Esta carpeta contiene la versión B11 del sistema con el módulo completo de gestión de productos implementado, siguiendo el mismo patrón que el módulo de empleados.

## Cambios Respecto a B9/B10

### Nueva Base de Datos: Tabla `productos`

```sql
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(128) NOT NULL,
    codigo VARCHAR(32) NOT NULL UNIQUE,
    descripcion TEXT,
    costo DOUBLE NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255) DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    eliminado TINYINT(1) NOT NULL DEFAULT 0,
    INDEX idx_codigo (codigo),
    INDEX idx_status (status),
    INDEX idx_eliminado (eliminado)
);
```

### Nuevos Archivos del Módulo de Productos

#### Listado y Consulta
- **productos_lista.php** - Lista todos los productos activos
  - Muestra: Código, Nombre, Descripción, Costo, Stock, Estado
  - Alerta visual para stock bajo (< 10 unidades)
  - Formato monetario para costos
  - Acciones: Ver, Editar, Eliminar (AJAX)

#### Alta de Productos
- **productos_alta_form.php** - Formulario de registro
  - Validación AJAX de código único
  - Previsualización de imagen
  - Campos: Nombre, Código, Descripción, Costo, Stock, Imagen, Estado
  
- **productos_alta.php** - Procesa el registro
  - Validación de sesión
  - Soporte para imágenes
  - Traducción de errores MySQL a español

#### Detalle
- **productos_detalle.php** - Información completa del producto
  - Muestra imagen en base64 (igual que empleados)
  - Alerta visual si stock < 10
  - Formato monetario
  - Enlaces a editar y volver

#### Edición
- **productos_editar_form.php** - Formulario de edición
  - Pre-carga datos actuales
  - Validación AJAX de código (excluyendo el actual)
  - Muestra imagen actual
  - Opción de cambiar imagen (opcional)
  
- **productos_editar.php** - Procesa la actualización
  - Actualización condicional de imagen
  - Mantiene imagen actual si no se sube nueva
  - Validación de sesión

#### Eliminación
- **productos_eliminar.php** - Soft delete vía AJAX
  - Marca `eliminado = 1`
  - Retorna 1/0 para confirmación
  - Protegido por sesión

#### Validaciones
- **codigo_consulta.php** - Valida código único en alta
  - Endpoint AJAX
  - Retorna 1 si disponible, 0 si existe
  
- **codigo_consulta_editar.php** - Valida código en edición
  - Excluye el producto actual de la búsqueda
  - Permite mantener el mismo código

### Menú Actualizado

```
┌──────────────────────────────────────────────────────────────────────────────────┐
│ Inicio │ Empleados │ Productos │ Registrar Empleado │ Registrar Producto │ ... │
└──────────────────────────────────────────────────────────────────────────────────┘
```

**Cambios en menu.php:**
1. "Productos" ahora enlaza a `productos_lista.php` (antes productos.php con mensaje en construcción)
2. Nueva opción "Registrar Producto" → `productos_alta_form.php`

## Características del Módulo de Productos

### Validaciones Implementadas
- ✅ Código único (validación AJAX en tiempo real)
- ✅ Campos obligatorios: Nombre, Código, Costo, Stock
- ✅ Costo y Stock deben ser >= 0
- ✅ Formato de imagen validado (igual que empleados)
- ✅ Sesión activa requerida en todas las operaciones

### Funcionalidades Especiales
- **Alerta de Stock Bajo**: Productos con stock < 10 se marcan en rojo
- **Formato Monetario**: Costos mostrados con `$` y 2 decimales
- **Soft Delete**: Productos eliminados mantienen integridad referencial
- **Imágenes en Base64**: Mismo sistema que empleados para evitar problemas de permisos
- **Badges de Estado**: Visual para Activo/Inactivo

### Campos de la Tabla Productos

| Campo       | Tipo         | Descripción                           | Validación          |
|-------------|--------------|---------------------------------------|---------------------|
| id          | INT          | Identificador único (autoincrement)   | PK                  |
| nombre      | VARCHAR(128) | Nombre del producto                   | NOT NULL            |
| codigo      | VARCHAR(32)  | Código único del producto             | UNIQUE, NOT NULL    |
| descripcion | TEXT         | Descripción detallada                 | Opcional            |
| costo       | DOUBLE       | Costo del producto                    | >= 0, default 0     |
| stock       | INT          | Cantidad en inventario                | >= 0, default 0     |
| imagen      | VARCHAR(255) | Nombre MD5 de la imagen               | Opcional            |
| status      | TINYINT(1)   | 1=Activo, 0=Inactivo                 | Default 1           |
| eliminado   | TINYINT(1)   | 0=Visible, 1=Eliminado (soft delete) | Default 0           |

## Estructura del CRUD Completo

### Flujo de Alta de Producto
```
productos_alta_form.php (formulario)
    ↓ Validación AJAX de código único
    ↓ codigo_consulta.php
    ↓ Submit POST con imagen (opcional)
productos_alta.php (procesar)
    ↓ Subir_foto.php (si hay imagen)
    ↓ INSERT INTO productos
    ↓ Confirmar → productos_lista.php
```

### Flujo de Edición de Producto
```
productos_lista.php → Editar
    ↓
productos_editar_form.php?id=X
    ↓ Cargar datos actuales
    ↓ Validación AJAX de código (excluyendo actual)
    ↓ codigo_consulta_editar.php
    ↓ Submit POST
productos_editar.php
    ↓ Subir_foto.php (si se cambió imagen)
    ↓ UPDATE productos
    ↓ Confirmar → productos_detalle.php?id=X
```

### Flujo de Eliminación
```
productos_lista.php → Eliminar (confirmación)
    ↓ AJAX POST a productos_eliminar.php
    ↓ UPDATE productos SET eliminado=1
    ↓ Response: "1" (éxito) / "0" (error)
    ↓ Recargar página automáticamente
```

## Comandos SCP para Despliegue

### Desde Windows PowerShell:

```powershell
# Copiar archivos nuevos del módulo de productos
cd C:\Users\sambo\Documents\Programacion\GitHub\PPI\B11
scp productos_*.php codigo_consulta*.php menu.php rodrigo@169.254.218.17:~/html/ProduccionDB/

# O copiar todo para sincronización completa
scp *.php *.css rodrigo@169.254.218.17:~/html/ProduccionDB/
```

### En el servidor:

```bash
# Primero crear la tabla productos (si aún no existe)
mysql -u root -p ProduccionDB < ~/crear_tabla_productos.sql

# Copiar archivos a producción
sudo cp ~/html/ProduccionDB/productos_*.php /var/www/html/ProduccionDB/
sudo cp ~/html/ProduccionDB/codigo_consulta*.php /var/www/html/ProduccionDB/
sudo cp ~/html/ProduccionDB/menu.php /var/www/html/ProduccionDB/

# Establecer permisos
cd /var/www/html/ProduccionDB/
sudo chown rodrigo:www-data productos_*.php codigo_consulta*.php menu.php
sudo chmod 644 productos_*.php codigo_consulta*.php menu.php

# Verificar archivos de productos
ls -la | grep productos
ls -la | grep codigo_consulta
```

## Pruebas de Funcionalidad

### 1. Verificar Tabla en Base de Datos
```sql
USE ProduccionDB;
DESCRIBE productos;
SELECT * FROM productos WHERE eliminado = 0;
```

### 2. Probar Alta de Producto
- [ ] Acceder a "Registrar Producto" desde el menú
- [ ] Ingresar código y verificar validación AJAX
- [ ] Probar con código duplicado (debe alertar)
- [ ] Subir imagen y ver previsualización
- [ ] Enviar formulario y verificar mensaje de éxito
- [ ] Confirmar aparición en productos_lista.php

### 3. Probar Listado
- [ ] Acceder a "Productos" desde el menú
- [ ] Verificar que muestra todos los productos activos
- [ ] Comprobar formato de costo ($X.XX)
- [ ] Verificar alerta de stock bajo (rojo si < 10)
- [ ] Probar enlaces de Ver, Editar, Eliminar

### 4. Probar Detalle
- [ ] Hacer clic en "Ver" de un producto
- [ ] Verificar que muestra toda la información
- [ ] Confirmar que imagen se muestra (si existe)
- [ ] Probar enlace a Editar

### 5. Probar Edición
- [ ] Modificar nombre y guardar
- [ ] Cambiar código (verificar validación AJAX)
- [ ] Actualizar stock y costo
- [ ] Cambiar imagen
- [ ] Verificar que mantiene imagen si no se sube nueva
- [ ] Confirmar actualización en detalle

### 6. Probar Eliminación
- [ ] Hacer clic en "Eliminar" de un producto
- [ ] Confirmar mensaje de advertencia
- [ ] Verificar que desaparece de la lista
- [ ] Comprobar en BD que `eliminado = 1`

### 7. Validaciones de Sesión
- [ ] Intentar acceder a productos_lista.php sin login
- [ ] Intentar POST a productos_eliminar.php sin sesión
- [ ] Verificar redirección a login.php

### 8. Navegación Completa
- [ ] Probar todos los enlaces del menú actualizado
- [ ] Verificar flujo: Lista → Detalle → Editar → Lista
- [ ] Confirmar botones "Volver" y "Cancelar"

## Comparación: Empleados vs Productos

| Característica           | Empleados                    | Productos                      |
|-------------------------|------------------------------|--------------------------------|
| **Campo único**         | correo                       | codigo                         |
| **Campos obligatorios** | nombre, apellidos, correo, pass | nombre, codigo, costo, stock |
| **Campo especial**      | pass (MD5, opcional en edición) | stock (alerta si < 10)      |
| **Imagen**              | Opcional                     | Opcional                       |
| **Soft delete**         | ✅                           | ✅                             |
| **Estado activo/inactivo** | ✅                        | ✅                             |
| **Validación AJAX**     | correo_consulta.php          | codigo_consulta.php            |
| **Formato especial**    | -                            | Monetario ($X.XX)              |

## Archivos Compartidos (sin cambios)

Los siguientes archivos se mantienen igual que en B9/B10:
- Sistema de autenticación (login.php, login_consulta.php, logout.php)
- Gestión de empleados (todos los empleados_*.php)
- Soporte (db_connect.php, Subir_foto.php, estilos.css)
- Página principal (index.php, bienvenido_OLD.php)
- Validaciones de empleados (correo_consulta.php, correo_consulta_editar.php)

## Próximas Mejoras Sugeridas

### Para el Módulo de Productos
- [ ] Agregar categorías de productos
- [ ] Sistema de búsqueda y filtros
- [ ] Historial de cambios de precio
- [ ] Control de stock (entradas/salidas)
- [ ] Múltiples imágenes por producto
- [ ] Código de barras
- [ ] Alertas automáticas de stock bajo
- [ ] Reportes de inventario

### Relaciones entre Módulos
- [ ] Relacionar productos con empleados (quién registró/modificó)
- [ ] Sistema de ventas (empleados venden productos)
- [ ] Comisiones por ventas
- [ ] Auditoría completa de cambios

## Notas Técnicas

1. **Reutilización de Código**: El módulo de productos reutiliza completamente `Subir_foto.php` sin modificaciones
2. **Patrón MVC Implícito**: Separación clara entre vistas (*_form.php), controladores (*_alta.php, *_editar.php) y consultas AJAX
3. **Consistencia Visual**: Usa los mismos estilos CSS que empleados
4. **Seguridad**: Todas las páginas protegidas por sesión mediante `menu.php`
5. **Escalabilidad**: Estructura permite agregar fácilmente más campos o funcionalidades

## Testing Checklist

- [ ] Tabla `productos` creada correctamente en MariaDB
- [ ] Menu actualizado con opciones de productos
- [ ] Alta de productos funcional (con y sin imagen)
- [ ] Listado muestra productos correctamente
- [ ] Detalle muestra información completa
- [ ] Edición actualiza datos (con y sin cambio de imagen)
- [ ] Eliminación marca como `eliminado = 1`
- [ ] Validación de código único funciona
- [ ] Alerta de stock bajo visible
- [ ] Formato monetario correcto
- [ ] Protección de sesión en todas las páginas
- [ ] Sin errores en consola del navegador
- [ ] Imágenes se muestran correctamente (base64)
