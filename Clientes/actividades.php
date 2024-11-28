<?php
include('../conexion.php'); // Conexión a la base de datos

// Verificar si el DNI del cliente está presente en la URL
if (isset($_GET['cliente'])) {
    $dni_cliente = $_GET['cliente'];

    // Consultar los datos del cliente
    $query_cliente = "SELECT * FROM cliente WHERE dni = '$dni_cliente'";
    $resultado_cliente = mysqli_query($conex, $query_cliente);

    if (mysqli_num_rows($resultado_cliente) > 0) {
        $cliente = mysqli_fetch_assoc($resultado_cliente);
    } else {
        echo "<script>
                alert('No se encontró el cliente con el DNI proporcionado.');
                window.location.href = 'listarClientes.php'; // Redirige si no hay cliente
              </script>";
        exit;
    }
} else {
    echo "<script>
            alert('No se proporcionó el DNI del cliente.');
            window.location.href = 'listarClientes.php'; // Redirige si no hay parámetro
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades de <?php echo $cliente['nombre'] . ' ' . $cliente['apellido']; ?></title>
    <link rel="stylesheet" href="styles/main.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        header { background-color: #007bff; color: white; padding: 1em 0; text-align: center; }
        .cliente-info { text-align: center; margin: 1em; font-size: 1.2em; }
        .product-list { display: flex; flex-wrap: wrap; justify-content: center; gap: 1.5em; margin: 2em auto; max-width: 1200px; }
        .card { border: 1px solid #ccc; border-radius: 8px; overflow: hidden; width: 300px; text-align: center; }
        .card-image { width: 100%; height: 200px; object-fit: cover; }
        .card-info { padding: 1em; }
        .btn-register { background-color: #28a745; color: white; border: none; padding: 0.5em 1em; border-radius: 4px; cursor: pointer; }
        .btn-back { background-color: black; color: white; text-decoration: none; padding: 0.5em 1em; border-radius: 4px; }
    </style>
</head>
<body>
<header>
    <h1>Actividades Disponibles</h1>
    <div class="cliente-info">
        <p><strong>Cliente:</strong> <?php echo $cliente['nombre'] . ' ' . $cliente['apellido']; ?></p>
        <p><strong>Correo:</strong> <?php echo $cliente['correo']; ?></p>
        <p><strong>Teléfono:</strong> <?php echo $cliente['telefono']; ?></p>
    </div>
    <div style="text-align: center; margin: 1em 0;">
        <a href="http://localhost/Sportclub/clientes/listarClientes.php" class="btn-back">Volver</a>
    </div>
</header>

<section class="product-list">
    <?php
    // Mostrar actividades
    $query_actividades = "SELECT * FROM actividad";
    $result_actividades = mysqli_query($conex, $query_actividades);

    if (mysqli_num_rows($result_actividades) > 0) {
        while ($actividad = mysqli_fetch_assoc($result_actividades)) {
            $imagen = match ($actividad['nombre']) {
                'CrossFit' => '../Imagenes/Crossfit.jepg', // Imagen para CrossFit
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
            <div class="card">
                <img class="card-image" src="<?php echo $imagen; ?>" alt="Imagen de <?php echo $actividad['nombre']; ?>">
                <div class="card-info">
                    <p class="card-name"><?php echo $actividad['nombre']; ?></p>
                    <p class="card-desc"><?php echo $actividad['descripcion']; ?></p>
                    <form method="POST" action="actividades.php?cliente=<?php echo $dni_cliente; ?>">
                        <input type="hidden" name="id_actividad" value="<?php echo $actividad['id_actividad']; ?>">
                        <input type="hidden" name="dni_cliente" value="<?php echo $dni_cliente; ?>">
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
