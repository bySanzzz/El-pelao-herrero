<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrenadores/Alta</title>
    <link rel="stylesheet" href="../Style/header.css">
    <link rel="stylesheet" href="../Style/indexinscrip.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="prese">
        <h1>Alta Entrenador</h1>
        <div class="logo">
            <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
        </div>
    </div>
    <div class="menu-buttons">
        <button id="openMenu" class="botone">
            <div class="svg-container">
                <!-- Botón de menú -->
            </div>
        </button>
    </div>
    <nav class="nav-list">
        <ul>
            <h2><li><a href="http://localhost:8080/Sportclub/">Principal</a></li></h2>
            <h2><li><a href="http://localhost:8080/Sportclub/entrenadores/listarEntrenadores.php">Entrenadores</a></li></h2>
        </ul>
        <div class="logo">
            <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
        </div>
    </nav>
</header>
<script src="../JavaScript/menu.js"></script>
<div class="content-wrapper">
    <div class="Tabla">
        <form method="POST" action="">
            <input type="number" name="dni" placeholder="DNI" required min="10000000" max="99999999"> <br>
            <input type="text" name="nombre" placeholder="Nombre" required><br>
            <input type="text" name="apellido" placeholder="Apellido" required><br>
            <input type="text" name="telefono" placeholder="Teléfono" required><br>

            <!-- Select para especialidad con las actividades registradas -->
            <label for="especialidad">Especialidad (Actividad):</label>
            <select name="especialidad" id="especialidad" required>
                <option value="" disabled selected>Selecciona una especialidad</option>
                <?php
                include("../conexion.php");

                // Consulta para obtener las actividades registradas
                $query_actividades = "SELECT id_actividad, nombre FROM actividad";
                $result_actividades = mysqli_query($conex, $query_actividades);

                if ($result_actividades) {
                    while ($row = mysqli_fetch_assoc($result_actividades)) {
                        echo "<option value='" . $row['id_actividad'] . "'>" . $row['nombre'] . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>No hay actividades disponibles</option>";
                }
                ?>
            </select><br>

            <input type="date" name="fecha_contrato" placeholder="Fecha de Contrato" required> <br>
            <input type="submit" value="Registrar">
        </form>

        <?php
        // Verificar si el formulario fue enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Variables del formulario
            $dni = mysqli_real_escape_string($conex, $_POST['dni']);
            $nombre = mysqli_real_escape_string($conex, $_POST['nombre']);
            $apellido = mysqli_real_escape_string($conex, $_POST['apellido']);
            $telefono = mysqli_real_escape_string($conex, $_POST['telefono']);
            $especialidad = mysqli_real_escape_string($conex, $_POST['especialidad']); // Ahora se guarda el ID de actividad
            $fecha_contrato = mysqli_real_escape_string($conex, $_POST['fecha_contrato']);

            // Verificar si el DNI ya existe
            $check_query = "SELECT dni FROM entrenador WHERE dni = ?";
            $stmt_check = mysqli_prepare($conex, $check_query);

            if ($stmt_check) {
                mysqli_stmt_bind_param($stmt_check, "s", $dni);
                mysqli_stmt_execute($stmt_check);
                mysqli_stmt_store_result($stmt_check);

                if (mysqli_stmt_num_rows($stmt_check) > 0) {
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Ya hay un entrenador registrado con ese DNI',
                            });
                          </script>";
                } else {
                    // Insertar los datos del entrenador
                    $insert_query = "INSERT INTO entrenador (dni, nombre, apellido, telefono, id_actividad, fecha_contrato) 
                                     VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt_insert = mysqli_prepare($conex, $insert_query);

                    if ($stmt_insert) {
                        // Vincular los parámetros
                        mysqli_stmt_bind_param($stmt_insert, "ssssss", $dni, $nombre, $apellido, $telefono, $especialidad, $fecha_contrato);

                        // Ejecutar la consulta
                        try {
                            if (mysqli_stmt_execute($stmt_insert)) {
                                echo "<script>
                                        Swal.fire({
                                            position: 'mid',
                                            icon: 'success',
                                            title: 'Entrenador registrado correctamente',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                      </script>";
                            }
                        } catch (Exception $e) {
                            echo "<div class='alert alert-danger'>Error al insertar los datos: " . $e->getMessage() . "</div>";
                        }

                        mysqli_stmt_close($stmt_insert);
                    }
                }

                mysqli_stmt_close($stmt_check);
            }

            mysqli_close($conex);
        }
        ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
