<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si existe una sesión activa
// Comprobamos que las variables de sesión contengan información de un usuario válido
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_correo']) || empty($_SESSION['usuario_id'])) {
    // No hay sesión activa, redirigir a login.php
    header('Location: login.php');
    exit;
}

// Sesión activa, cargar el menú
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        nav {
            background-color: #4CAF50;
            padding: 0;
            margin: 0 0 20px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav li {
            margin: 0;
            padding: 0;
        }

        nav a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #45a049;
            text-decoration: none;
        }

        .menu-usuario {
            margin-left: auto;
            display: flex;
            align-items: center;
        }

        .menu-usuario span {
            color: white;
            padding: 14px 20px;
            font-weight: bold;
        }

        .menu-usuario a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
        }

        .menu-usuario a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="empleados_lista.php">Lista de Empleados</a></li>
            <li><a href="empleados_alta_form.php">Registrar Empleado</a></li>
            <li class="menu-usuario">
                <span>Usuario: <?php echo htmlspecialchars($_SESSION['usuario_correo']); ?></span>
                <a href="logout.php">Cerrar Sesión</a>
            </li>
        </ul>
    </nav>
</body>
</html>
