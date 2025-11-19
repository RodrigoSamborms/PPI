Comenzamos creando la base de datos

-- Crear la base de datos
```bash
CREATE DATABASE ProduccionDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
-- Crear el usuario con contraseña (reemplaza 'tu_contraseña_segura' por una contraseña fuerte)
```bash
CREATE USER 'ProduccionDBAdmin'@'localhost' IDENTIFIED BY '1234';
```
-- Otorgar todos los permisos sobre ProduccionDB al usuario
```bash
GRANT ALL PRIVILEGES ON ProduccionDB.* TO 'ProduccionDBAdmin'@'localhost';
```
-- Aplicar los cambios
```bash
FLUSH PRIVILEGES;
```
-- Verificar que el usuario fue creado correctamente
```bash
SELECT User, Host FROM mysql.user WHERE User = 'ProduccionDBAdmin';
```