<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Actividades</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <!-- Agrega tu estilo personalizado aquí -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 1em 0;
            text-align: center;
        }
        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5em;
            margin: 2em auto;
            max-width: 1200px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 300px;
            text-align: center;
        }
        .card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-info {
            padding: 1em;
        }
        .card-name {
            font-size: 1.5em;
            font-weight: bold;
            margin: 0.5em 0;
        }
        .card-desc {
            font-size: 0.9em;
            color: #666;
            margin: 0.5em 0;
        }
        .card-duration, .card-time {
            font-size: 0.9em;
            margin: 0.2em 0;
        }
        .btn-register {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 0.5em 1em;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        .btn-register:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<header>
    <h1>Actividades Disponibles</h1>
</header>

<section class="product-list">
    <?php
    include('../conexion.php'); // Archivo para conectar a la base de datos

    // Consulta a la base de datos
    $query = "SELECT * FROM actividad";
    $result = mysqli_query($conex, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Asignar la imagen manualmente según el nombre de la actividad
            $imagen = '';
            switch ($row['nombre']) {
                case 'Crossfit':
                    $imagen = '../Imagenes/Crossfit.jpg';
                    break;
                case 'Yoga':
                    $imagen = '../Imagenes/yoga.jpeg';
                    break;
                case 'Musculación':
                    $imagen = 'images/musculacion.jpg';
                    break;
                case 'Bodypump':
                    $imagen = 'images/bodypump.jpg';
                    break;
                case 'Zumba':
                    $imagen = 'images/zumba.jpg';
                    break;
                case 'Pilates':
                    $imagen = 'images/pilates.jpg';
                    break;
                default:
                    $imagen = 'images/default.jpg'; // Imagen predeterminada
                    break;
            }
            ?>
            <div class="card">
                <!-- Imagen asignada manualmente -->
                <img class="card-image" src="<?php echo $imagen; ?>" alt="Imagen de <?php echo $row['nombre']; ?>">
                <div class="card-info">
                    <!-- Nombre de la actividad -->
                    <p class="card-name"><?php echo $row['nombre']; ?></p>
                    <!-- Descripción de la actividad -->
                    <p class="card-desc"><?php echo $row['descripcion']; ?></p>
                    <!-- Duración -->
                    <p class="card-duration">Duración: <?php echo $row['duracion']; ?> minutos</p>
                    <!-- Hora de inicio -->
                    <p class="card-time">Hora de inicio: <?php echo $row['hora_inicio']; ?></p>
                    <!-- Botón de registro -->
                    <form method="POST" action="registro.php">
                        <input type="hidden" name="id_actividad" value="<?php echo $row['id_actividad']; ?>">
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
