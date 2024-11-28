<?php
include('../conexion.php');

// Verifica si se recibieron los datos del formulario
if (isset($_POST['id_actividad']) && isset($_POST['dni_cliente'])) {
    $id_actividad = $_POST['id_actividad'];
    $dni_cliente = $_POST['dni_cliente'];

    // Obtener el DNI del entrenador asignado a la actividad
    $query_entrenador = "SELECT dni FROM entrenador WHERE id_actividad = '$id_actividad' LIMIT 1";
    $resultado_entrenador = mysqli_query($conex, $query_entrenador);

    if (mysqli_num_rows($resultado_entrenador) > 0) {
        $entrenador = mysqli_fetch_assoc($resultado_entrenador);
        $dni_entrenador = $entrenador['dni'];

        // Insertar los datos en la tabla entrenamiento
        $fecha_actual = date('Y-m-d');
        $query_insert = "INSERT INTO entrenamiento (dni_cliente, id_actividad, fecha, dni_entrenador) 
                         VALUES ('$dni_cliente', '$id_actividad', '$fecha_actual', '$dni_entrenador')";

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
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al registrar',
                        text: 'Inténtalo nuevamente.',
                    });
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'No se encontró un entrenador para esta actividad',
                    text: 'Inténtalo nuevamente.',
                });
              </script>";
    }
} else {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Datos incompletos',
                text: 'Inténtalo nuevamente.',
            });
          </script>";
}
?>
