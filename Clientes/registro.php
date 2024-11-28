<?php
include('../conexion.php');

// Verificar si se enviaron los datos necesarios
if (!isset($_POST['dni_cliente'], $_POST['id_actividad'])) {
    die("No se recibieron los datos necesarios para el registro.");
}

// Obtener datos del cliente y la actividad
$dni_cliente = $_POST['dni_cliente'];
$id_actividad = $_POST['id_actividad'];

// Consultar el DNI del entrenador asociado a la actividad
$query_entrenador = "SELECT dni FROM entrenador WHERE id_actividad = ?";
$stmt = $conex->prepare($query_entrenador);
$stmt->bind_param("i", $id_actividad);
$stmt->execute();
$result = $stmt->get_result();
$entrenador = $result->fetch_assoc();

// Verificar si se encontró un entrenador
if (!$entrenador) {
    die("No se encontró un entrenador para esta actividad.");
}
$dni_entrenador = $entrenador['dni'];

// Registrar el entrenamiento en la base de datos
$fecha_actual = date('Y-m-d');
$query_insert = "INSERT INTO entrenamiento (dni_cliente, id_actividad, fecha, dni_entrenador) VALUES (?, ?, ?, ?)";
$stmt_insert = $conex->prepare($query_insert);
$stmt_insert->bind_param("iisi", $dni_cliente, $id_actividad, $fecha_actual, $dni_entrenador);

if ($stmt_insert->execute()) {
    echo "¡Registro exitoso en la actividad!";
    header("Location: actividades.php?cliente=$dni_cliente");
} else {
    echo "Error al registrar la actividad: " . $stmt_insert->error;
}
?>
