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

// Si se recibe el formulario para registrar en una actividad
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_actividad'], $_POST['dni_cliente'], $_POST['fecha_ingreso'], $_POST['hora_ingreso'])) {
    $id_actividad = $_POST['id_actividad'];
    $dni_cliente = $_POST['dni_cliente'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $hora_ingreso = $_POST['hora_ingreso'];

    // Obtener el DNI del entrenador asignado a la actividad
    $query_entrenador = "SELECT dni FROM entrenador WHERE id_actividad = '$id_actividad' LIMIT 1";
    $resultado_entrenador = mysqli_query($conex, $query_entrenador);

    if (mysqli_num_rows($resultado_entrenador) > 0) {
        $entrenador = mysqli_fetch_assoc($resultado_entrenador);
        $dni_entrenador = $entrenador['dni'];

        // Insertar los datos en la tabla entrenamiento
        $query_insert = "INSERT INTO entrenamiento (dni_cliente, id_actividad, fecha, dni_entrenador, fecha_ingreso, hora_ingreso) 
                         VALUES ('$dni_cliente', '$id_actividad', CURDATE(), '$dni_entrenador', '$fecha_ingreso', '$hora_ingreso')";

        if (mysqli_query($conex, $query_insert)) {
            echo "<script>
                    Swal.fire({
                        position: 'mid',
                        icon: 'success',
                        title: 'Cliente registrado en la actividad con éxito',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(() => {
                        window.location.href = 'actividades.php?cliente=$dni_cliente';
                    }, 1500);
                  </script>";
        } else {
            echo "<script>
                    alert('Error al registrar. Inténtalo nuevamente.');
                  </script>";
        }
    } else {
        echo "<script>
                alert('No se encontró un entrenador para esta actividad.');
              </script>";
    }
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
    <link rel="stylesheet" href="../Style/main.css">
    <link rel="stylesheet" href="../Style/cards.css">
    <style>
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
if (mysqli_num_rows($result_actividades) > 0) {
    while ($actividad = mysqli_fetch_assoc($result_actividades)) {
        $imagen = match ($actividad['nombre']) {
            'CrossFit' => '../Imagenes/Crossfit.jpeg',
            'Yoga' => '../Imagenes/yoga.jpeg',
            'Musculacion' => '../Imagenes/musculacion.jpg',
            'Bodypump' => '../Imagenes/bodypump.jpg',
            'Zumba' => '../Imagenes/zumba.jpg',
            'Pilates' => '../Imagenes/pilates.jpg',
            'Spinning' => '../Imagenes/spinning.jpg',
            'Kickboxing' => '../Imagenes/Kickboxing.jpg',
            'HIIT' => '../Imagenes/hiit.jpg',
            'AquaGym' => '../Imagenes/aquagym.jpg',
            default => '../Imagenes/default.jpg',
        };
        ?>
        <div class="card">
            <img class="card-image" src="<?php echo $imagen; ?>" alt="Imagen de <?php echo $actividad['nombre']; ?>">
            <div class="card-info">
                <p class="card-name"><?php echo $actividad['nombre']; ?></p>
                <p class="card-desc"><?php echo $actividad['descripcion']; ?></p>
                <p class="card-duration">Duración: <?php echo $actividad['duracion']; ?> minutos</p>
                <p class="card-time">Hora de inicio: <?php echo $actividad['hora_inicio']; ?></p>
                <form method="POST">
                    <input type="hidden" name="id_actividad" value="<?php echo $actividad['id_actividad']; ?>">
                    <input type="hidden" name="dni_cliente" value="<?php echo $cliente['dni']; ?>">

                    <label for="fecha_ingreso_<?php echo $actividad['id_actividad']; ?>">Fecha de ingreso:</label>
                    <input type="date" id="fecha_ingreso_<?php echo $actividad['id_actividad']; ?>" name="fecha_ingreso" required>

                    <label for="hora_ingreso_<?php echo $actividad['id_actividad']; ?>">Hora de ingreso:</label>
                    <input type="time" id="hora_ingreso_<?php echo $actividad['id_actividad']; ?>" name="hora_ingreso" required>

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
