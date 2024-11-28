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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
    <style>
        .cliente-info {
            text-align: center;
            margin-bottom: 2em;
            font-size: 1.2em;
            font-weight: bold;
        }
        .card {
            margin: 1em;
            padding: 1em;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<header class="text-center my-4" style="background-color: rgba(51,51,51,255); color: white; padding: 1rem; border-radius: 5px;">
    <h1>Actividades Disponibles</h1>
    <!-- Botón para volver atrás -->
    <button class="btn btn-secondary mt-3" onclick="history.back()">Volver Atrás</button>
</header>



<!-- Información del cliente -->
<section class="cliente-info">
    <p>Bienvenido, <?php echo $cliente['nombre'] . ' ' . $cliente['apellido']; ?></p>
    <p>DNI: <?php echo $cliente['dni']; ?></p>
    <p>Correo: <?php echo $cliente['correo']; ?></p>
</section>

<section class="container">
    <div class="row">
    <?php
    // Mostrar actividades
    $query_actividades = "SELECT * FROM actividad";
    $result_actividades = mysqli_query($conex, $query_actividades);

    if (mysqli_num_rows($result_actividades) > 0) {
        while ($actividad = mysqli_fetch_assoc($result_actividades)) {
            $imagen = match ($actividad['nombre']) {
                'CrossFit' => '../Imagenes/Crossfit.jpeg', // Imagen para CrossFit
                'Yoga' => '../Imagenes/yoga.jpeg',         // Imagen para Yoga
                'Musculacion' => '../Imagenes/musculacion.jpg', // Imagen para Musculación
                'Bodypump' => '../Imagenes/bodypump.jpg',  // Imagen para Bodypump
                'Zumba' => '../Imagenes/zumba.jpg',        // Imagen para Zumba
                'Pilates' => '../Imagenes/pilates.jpg',    // Imagen para Pilates
                'Spinning' => '../Imagenes/spinning.jpg',  // Imagen para Spinning
                'Kickboxing' => '../Imagenes/Kickboxing.jpg', // Imagen para Kickboxing
                'HIIT' => '../Imagenes/hiit.jpg',          // Imagen para HIIT
                'AquaGym' => '../Imagenes/aquagym.jpg',    // Imagen para AquaGym
                default => 'images/default.jpg',           // Imagen predeterminada
            };
            ?>
                <div class="col-md-4">
                    <div class="card">
                        <img class="card-img-top" src="<?php echo $imagen; ?>" alt="Imagen de <?php echo $actividad['nombre']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $actividad['nombre']; ?></h5>
                            <p class="card-text"><?php echo $actividad['descripcion']; ?></p>
                            <p>Duración: <?php echo $actividad['duracion']; ?> minutos</p>
                            <p>Hora de inicio: <?php echo $actividad['hora_inicio']; ?></p>
                            <form method="POST" action="registro.php">
                                <input type="hidden" name="id_actividad" value="<?php echo $actividad['id_actividad']; ?>">
                                <input type="hidden" name="dni_cliente" value="<?php echo $cliente['dni']; ?>">
                                
                                <div class="mb-3">
                                    <label for="fecha_ingreso" class="form-label">Fecha de ingreso:</label>
                                    <input type="date" id="fecha_ingreso" name="fecha_ingreso" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="hora_ingreso" class="form-label">Hora de ingreso:</label>
                                    <input type="time" id="hora_ingreso" name="hora_ingreso" class="form-control" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Registrarse</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No hay actividades disponibles.</p>";
        }
        ?>
    </div>
</section>

<script>
    // Configurar fecha mínima como hoy
    document.querySelectorAll("#fecha_ingreso").forEach(input => {
        const today = new Date().toISOString().split("T")[0];
        input.min = today;
    });

    // Configurar restricciones de hora
    document.querySelectorAll("#hora_ingreso").forEach(input => {
        input.addEventListener("focus", function () {
            const minTime = "<?php echo $horaInicio; ?>"; // Hora inicial desde la base de datos
            const maxTime = "22:00"; // Hora máxima permitida
            input.min = minTime;
            input.max = maxTime;
        });
    });
</script>
</body>
</html>
