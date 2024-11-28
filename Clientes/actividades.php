<?php
// Incluye el archivo de conexión a la base de datos
include('../conexion.php');

// Obtén el DNI del cliente desde la URL
if (isset($_GET['cliente'])) {
    $dni_cliente = $_GET['cliente'];

    // Consulta para obtener los datos del cliente
    $query_cliente = "SELECT * FROM cliente WHERE dni = '$dni_cliente'";
    $resultado_cliente = mysqli_query($conex, $query_cliente);

    // Verifica si el cliente existe
    if (mysqli_num_rows($resultado_cliente) > 0) {
        $cliente = mysqli_fetch_assoc($resultado_cliente);
    } else {
        echo "Cliente no encontrado.";
        exit();
    }
} else {
    echo "No se proporcionó un cliente.";
    exit();
}

// Consulta para obtener las actividades
$query_actividades = "SELECT * FROM actividad";
$result_actividades = mysqli_query($conex, $query_actividades);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Actividades</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
    <style>
        /* Agregar estilo adicional para mostrar los datos del cliente */
        .cliente-info {
            text-align: center;
            margin-bottom: 2em;
            font-size: 1.2em;
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
    <h1>Actividades Disponibles</h1>
</header>

<!-- Información del cliente -->
<section class="cliente-info">
    <p>Bienvenido, <?php echo $cliente['nombre'] . ' ' . $cliente['apellido']; ?></p>
    <p>DNI: <?php echo $cliente['dni']; ?></p>
    <p>Correo: <?php echo $cliente['correo']; ?></p>
</section>

<section class="product-list">
    <?php
    // Mostrar las actividades disponibles
    if (mysqli_num_rows($result_actividades) > 0) {
        while ($row = mysqli_fetch_assoc($result_actividades)) {
            // Asignar la imagen manualmente según el nombre de la actividad
            $imagen = '';
            switch ($row['nombre']) {
                case 'Crossfit':
                    $imagen = '../Imagenes/Crossfit.jpeg';
                    break;
                case 'Yoga':
                    $imagen = '../Imagenes/yoga.jpeg';
                    break;
                case 'Musculación':
                    $imagen = '../Imagenes/musculacion.png';
                    break;
                default:
                    $imagen = '../Imagenes/default.jpg';
                    break;
            }
            ?>
            <div class="card">
                <img class="card-image" src="<?php echo $imagen; ?>" alt="Imagen de <?php echo $row['nombre']; ?>">
                <div class="card-info">
                    <p class="card-name"><?php echo $row['nombre']; ?></p>
                    <p class="card-desc"><?php echo $row['descripcion']; ?></p>
                    <p class="card-duration">Duración: <?php echo $row['duracion']; ?> minutos</p>
                    <p class="card-time">Hora de inicio: <?php echo $row['hora_inicio']; ?></p>
                    <form method="POST" action="registro.php">
                        <input type="hidden" name="id_actividad" value="<?php echo $row['id_actividad']; ?>">
                        <input type="hidden" name="dni_cliente" value="<?php echo $cliente['dni']; ?>">
                        <button type="submit" class="btn-register">Registrarse</button>
                    </form>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No hay actividades disponibles.</p>";
    }
    ?>
</section>
</body>
</html>
