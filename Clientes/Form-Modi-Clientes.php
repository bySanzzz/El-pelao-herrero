<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Cliente</title>
    <link rel="stylesheet" href="../CSS/indexmodi.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="prese">
        <h1>Modificar Cliente</h1>
        <div class="logo">
            <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
        </div>
    </div>
    <nav class="nav-list">
        <ul>
            <h2><li><a href="http://localhost:8080/Sportclub/">Principal</a></li></h2>
            <h2><li><a href="http://localhost:8080/Sportclub/clientes/listarClientes.php">Clientes</a></li></h2>
        </ul>
    </nav>
</header>

<?php
include("../conexion.php");
$con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");

$dni_cliente = isset($_GET['cliente']) ? mysqli_real_escape_string($con, $_GET['cliente']) : null; // Clave corregida
$mostrar_alerta = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modiDNI'])) {
    $estado = $_POST['modiEstado'] == "on" ? 0 : 1;

    $query_update = "UPDATE cliente SET
        nombre = '$_POST[modiNombre]',
        apellido = '$_POST[modiApellido]',
        correo = '$_POST[modiCorreo]',
        telefono = '$_POST[modiTelefono]',
        estado = '$estado',
        planilla_medica = '$_POST[modiPlanillaMedica]'
    WHERE dni = '$_POST[modiDNI]'";

    $resultado_update = mysqli_query($con, $query_update) or die("FALLO DE CONSULTA DE ACTUALIZACIÓN");

    if ($resultado_update) {
        $mostrar_alerta = true;
    }
}

if ($dni_cliente) {
    $query_select = "SELECT * FROM cliente WHERE dni = '$dni_cliente'";
    $result_select = mysqli_query($con, $query_select) or die("ERROR DE CONSULTA");

    if (mysqli_num_rows($result_select) > 0) {
        while ($row = mysqli_fetch_array($result_select)) {
?>
<!-- Aquí comienza el formulario -->
<form method="POST" action="">
    DNI: <input type="text" name="modiDNI" value="<?php echo htmlspecialchars($row['dni']); ?>" readonly> <br>
    Nombre: <input type="text" name="modiNombre" value="<?php echo htmlspecialchars($row['nombre']); ?>"> <br>
    Apellido: <input type="text" name="modiApellido" value="<?php echo htmlspecialchars($row['apellido']); ?>"> <br>
    Correo: <input type="email" name="modiCorreo" value="<?php echo htmlspecialchars($row['correo']); ?>"> <br>
    Teléfono: <input type="text" name="modiTelefono" value="<?php echo htmlspecialchars($row['telefono']); ?>"> <br>
    Estado:
    <div class="form-check form-switch">
        <input type="hidden" name="modiEstado" value="off">
        <input type="checkbox" class="form-check-input" id="modiEstado" name="modiEstado" 
            <?php echo ($row['estado'] == 0) ? 'checked' : ''; ?> onchange="updateLabel(this)">
        <label class="form-check-label" for="modiEstado" id="estadoLabel">
            <?php echo ($row['estado'] == 0) ? 'Activo' : 'Inactivo'; ?>
        </label>
    </div>
    Planilla Médica:
    <select name="modiPlanillaMedica">
        <option value="Sí" <?php if($row['planilla_medica'] == 'Sí') echo 'selected'; ?>>Sí</option>
        <option value="No" <?php if($row['planilla_medica'] == 'No') echo 'selected'; ?>>No</option>
    </select> <br>
    <input type="submit" value="Actualizar">
</form>

<?php
        }
    } else {
        echo "<div class='alert alert-danger'>No se encontraron resultados para el DNI: " . htmlspecialchars($dni_cliente) . "</div>";
    }
}

mysqli_close($con);
?>

<script>
function updateLabel(checkbox) {
    var label = document.getElementById('estadoLabel');
    label.textContent = checkbox.checked ? 'Activo' : 'Inactivo';
}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    <?php if ($mostrar_alerta): ?>
        Swal.fire({
            position: 'mid',
            icon: 'success',
            title: 'Datos actualizados correctamente',
            showConfirmButton: false,
            timer: 1500
        });
    <?php endif; ?>
</script>
</body>
</html>
