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
</head>
<body>
<header>
    <div class="prese">
        <h1>Alta Cliente</h1>
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
            <h2><li><a href="http://localhost:8080/Sportclub/clientes/listarClientes.php">Clientes</a></li></h2>
        </ul>
        <div class="logo">
            <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
        </div>
    </nav>
</header>

<!-- Botón para volver al apartado anterior -->
<div class="container mt-3">
    <a class="btn btn-secondary" href="http://localhost/Sportclub/clientes/listarClientes.php">
        Volver a la lista de clientes
</a>

</a>

    </a>
</div>

<script src="../JavaScript/menu.js"></script>
<div class="content-wrapper">
    <div class="Tabla">
        <form method="POST" action="">
            <input type="number" name="dni" placeholder="DNI" required min="10000000" max="99999999"> <br>
            <!-- Campo de fecha con ID agregado -->
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
    $estado = mysqli_real_escape_string($conex, $_POST['estado']);
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
