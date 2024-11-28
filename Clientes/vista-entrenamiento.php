<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrenamiento del Cliente</title>
    <link rel="stylesheet" href="../CSS/indexmodi.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <header>
        <div class="prese">
            <h1>Entrenamientos del Cliente</h1>
            <div class="logo">
                <img src="../Imagenes/Gym.png" alt="Logo Gimnasio">
            </div>
        </div>
    </header>

    <div class="container mt-4">
        <?php
        include("../conexion.php");

        // Crear conexión
        $con = mysqli_connect($host, $user, $pwd, $BD);

        // Verificar conexión
        if (!$con) {
            die("Conexión fallida: " . mysqli_connect_error());
        }

        // Capturar DNI del cliente desde la URL
        $dni_cliente = isset($_GET['cliente']) ? mysqli_real_escape_string($con, $_GET['cliente']) : null;

        if (!$dni_cliente) {
            die("<div class='alert alert-danger'>No se ha proporcionado un DNI válido.</div>");
        }

        // Consulta para obtener los entrenamientos del cliente
        $query = "SELECT 
                    e.id_entrenamiento,
                    e.fecha,
                    e.hora_ingreso,
                    a.nombre AS actividad,
                    en.nombre AS entrenador
                FROM 
                    entrenamiento e
                INNER JOIN 
                    actividad a ON e.id_actividad = a.id_actividad
                INNER JOIN 
                    entrenador en ON e.dni_entrenador = en.dni
                WHERE 
                    e.dni_cliente = '$dni_cliente'";

        // Ejecutar consulta
        $result = mysqli_query($con, $query);

        // Verificar si hay resultados
        if (mysqli_num_rows($result) > 0) {
        ?>
            <!-- Mostrar los entrenamientos -->
            <div class="d-flex justify-content-between mt-4">
                <h3>Lista de Entrenamientos</h3>
                <a href="../clientes/listarClientes.php" class="btn btn-primary">VOLVER</a>
            </div>

            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>ID Entrenamiento</th>
                        <th>Fecha</th>
                        <th>Hora de Ingreso</th>
                        <th>Actividad</th>
                        <th>Entrenador</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Recorrer los registros y mostrar en la tabla
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["id_entrenamiento"]) . "</td>";
                        echo "<td>" . date('d-m-Y', strtotime($row["fecha"])) . "</td>";
                        echo "<td>" . htmlspecialchars($row["hora_ingreso"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["actividad"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["entrenador"]) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php
        } else {
            echo "<div class='alert alert-warning'>No se encontraron entrenamientos para este cliente.</div>";
        }

        // Cerrar conexión
        mysqli_close($con);
        ?>
    </div>
</body>

</html>
