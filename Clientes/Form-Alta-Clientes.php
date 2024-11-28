<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente/Alta</title>
    <link rel="stylesheet" href="../Style/header.css">
    <link rel="stylesheet" href="../Style/indexinscrip.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: rgba(32,33,40,255); /* Fondo debajo del header */
            color: black; /* Ajustar texto para mejor visibilidad */
        }
        header {
            background-color: rgba(51,51,51,255); /* Color del header */
            padding: 15px;
            color: black;
        }
        .header-title {
            margin: 0;
            font-size: 24px;
        }
        .btn-container {
            text-align: center;
            margin: 15px 0;
        }
        .btn-custom {
            background-color: #6c757d; /* Botón gris */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin: 5px; /* Separación entre botones */
        }
        .btn-custom:hover {
            background-color: #5a6268; /* Hover más oscuro */
        }
        .form-container {
            margin: 20px;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #28a745; /* Botón de guardar */
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
    <header>
        <div class="prese">
            <h1>Alta Cliente</h1>
        </div>
    </header>

    <!-- Contenedor para el botón de "Volver al Inicio" -->
    <div class="btn-container">
        <a href="http://localhost/Sportclub/Clientes/listarClientes.php" class="btn-custom">Volver</a>
    </div>

    <div class="content-wrapper">
        <div class="Tabla">
            <form method="POST" action="">
                <input type="number" name="dni" placeholder="DNI" required min="10000000" max="99999999"> <br>
                <input type="date" id="fechaInscripcion" name="fecha_inscripcion" placeholder="Fecha de Inscripción" required> <br>
                <input type="text" name="nombre" placeholder="Nombre" required><br>
                <input type="text" name="apellido" placeholder="Apellido" required><br>
                <input type="email" name="correo" placeholder="Correo" required><br>
                <input type="text" name="telefono" placeholder="Teléfono" required><br>
                
                <label for="planilla_medica">¿Tiene planilla médica?</label><br>
                    <select name="planilla_medica" required>
                        <option value="Sí">Sí</option>
                        <option value="No">No</option>
                    </select><br>
                <input type="submit" value="Registrar">
            </form>

            <!-- Script para establecer la fecha actual automáticamente -->
            <script>
                // Obtener el campo de fecha por su ID
                const fechaInscripcionInput = document.getElementById('fechaInscripcion');

                // Obtener la fecha actual en formato "YYYY-MM-DD"
                const today = new Date().toISOString().split('T')[0];

                // Establecer el valor inicial del campo de fecha como la fecha actual
                fechaInscripcionInput.value = today;
            </script>

            <?php
            include("../conexion.php");

            // Habilitar errores
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            // Verificar si el formulario fue enviado
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Variables del formulario
                $dni = mysqli_real_escape_string($conex, $_POST['dni']);
                $fecha_inscripcion = mysqli_real_escape_string($conex, $_POST['fecha_inscripcion']);
                $nombre = mysqli_real_escape_string($conex, $_POST['nombre']);
                $apellido = mysqli_real_escape_string($conex, $_POST['apellido']);
                $correo = mysqli_real_escape_string($conex, $_POST['correo']);
                $telefono = mysqli_real_escape_string($conex, $_POST['telefono']);
                
                $planilla_medica = mysqli_real_escape_string($conex, $_POST['planilla_medica']);
                $estado = 1; // Establecer por defecto como inactivo 

                // Verificar si el DNI ya existe
                $check_query = "SELECT dni FROM cliente WHERE dni = ?";
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
                                    text: 'Ya hay un cliente registrado con ese DNI',
                                });
                              </script>";
                    } else {
                        // Insertar los datos del cliente
                        $insert_query = "INSERT INTO cliente (dni, fecha_inscripcion, nombre, apellido, correo, telefono, estado, planilla_medica) 
                                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt_insert = mysqli_prepare($conex, $insert_query);
                        
                        if ($stmt_insert) {
                            // Vincular los parámetros
                            mysqli_stmt_bind_param($stmt_insert, "ssssssss", $dni, $fecha_inscripcion, $nombre, $apellido, $correo, $telefono, $estado, $planilla_medica);
                            
                            // Ejecutar la consulta
                            try {
                                if (mysqli_stmt_execute($stmt_insert)) {
                                    echo "<script>
                                            Swal.fire({
                                                position: 'mid',
                                                icon: 'success',
                                                title: 'Datos insertados correctamente',
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

