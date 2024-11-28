<?php
// Conexión a la base de datos
include("../conexion.php");

// Variables para manejar el cliente y mensajes
$cliente = null;
$mensaje = "";

// Verificar si se recibió un DNI en la URL
if (isset($_GET['cliente'])) {
    $dni_cliente = $_GET['cliente'];

    // Consultar información del cliente
    $sql_cliente = "SELECT * FROM cliente WHERE dni = $dni_cliente";
    $resultado = $conex->query($sql_cliente);

    if ($resultado->num_rows > 0) {
        $cliente = $resultado->fetch_assoc();
    } else {
        $mensaje = "No se encontró información para el cliente con DNI: $dni_cliente";
    }
}

// Procesar el formulario si se realiza un pago
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni_cliente = $_POST['dni_cliente'];
    $cuota = $_POST['cuota'];
    $fecha_pago = date('Y-m-d');

    // Calcular fecha de vencimiento según la cuota seleccionada
    $meses = $cuota == '1' ? 1 : 3; // 1 mes o 3 meses
    $monto = $cuota == '1' ? 20000 : 45000; // 20 mil o 45 mil
    $fecha_vencimiento = date('Y-m-d', strtotime("+$meses months"));

    // Insertar el pago en la tabla pago_cliente
    $sql_pago = "INSERT INTO pago_cliente (monto, dni_cliente, fecha_de_pago, fecha_vencimiento) 
                 VALUES ($monto, $dni_cliente, '$fecha_pago', '$fecha_vencimiento')";
    if ($conex->query($sql_pago) === TRUE) {
        // Actualizar el estado del cliente a 0 (Activo)
        $sql_cliente = "UPDATE cliente SET estado=0 WHERE dni=$dni_cliente";
        if ($conex->query($sql_cliente) === TRUE) {
            $mensaje = "¡Pago registrado y cliente activado correctamente!";
        } else {
            $mensaje = "Error al actualizar el estado del cliente: " . $conex->error;
        }
    } else {
        $mensaje = "Error al registrar el pago: " . $conex->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Cliente</title>
    <style>
        /* Reset de márgenes y padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 0;
            padding-top: 150px; /* Agregar espacio para que el contenido no quede tapado por el header */
        }

        /* Estilos para el Header */
        header {
            background-color: #333; /* Fondo gris oscuro */
            color: white;
            padding: 25px 0; /* Aumentar el padding para el header más grande */
            text-align: left;
            width: 100%;
            height: 120px; /* Aumentar la altura del header */
            margin: 0;
            position: absolute;
            top: 0;
        }

        header h1 {
            margin-left: 20px;
            font-size: 2rem; /* Aumentar el tamaño del texto */
            line-height: 1.5; /* Centrar verticalmente */
            margin-top: 10px; /* Ajustar margen superior para centrar el título */
        }

        /* Estilos para el formulario */
        form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 20px auto;
        }

        input, select, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
        }

        button {
            background-color: #28a745; /* Verde */
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838; /* Verde más oscuro */
        }

        .mensaje {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            color: green;
        }

        .cliente-info {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Estilo para el botón 'Atrás' */
        .btn-back {
            display: block;
            margin: 20px auto;
            background-color: #6c757d; /* Gris */
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            width: auto; /* Ajustar el tamaño */
            max-width: 200px; /* Limitar el tamaño máximo */
        }

        .btn-back:hover {
            background-color: #5a6268; /* Gris más oscuro */
        }

    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Registro de Pago</h1>
    </header>

    <!-- Mensaje de confirmación o error -->
    <?php if (!empty($mensaje)) : ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <!-- Información del cliente -->
    <?php if ($cliente): ?>
        <div class="cliente-info">
            <h2>Cliente: <?php echo $cliente['nombre'] . " " . $cliente['apellido']; ?></h2>
            <p>DNI: <?php echo $cliente['dni']; ?></p>
            <p>Correo: <?php echo $cliente['correo']; ?></p>
            <p>Estado: <?php echo $cliente['estado'] == 0 ? 'Activo' : 'Inactivo'; ?></p>
        </div>

        <!-- Formulario para registrar el pago -->
        <form action="" method="POST">
            <input type="hidden" name="dni_cliente" value="<?php echo $cliente['dni']; ?>">
            
            <label for="cuota">Seleccionar cuota:</label>
            <select name="cuota" id="cuota" required>
                <option value="1">1 Mes - $20,000</option>
                <option value="3">3 Meses - $45,000</option>
            </select>

            <button type="submit">Registrar Pago</button>
        </form>
    <?php else: ?>
        <p>Por favor selecciona un cliente desde la tabla principal.</p>
    <?php endif; ?>

    <!-- Botón Atrás -->
    <a href="http://localhost/Sportclub/Clientes/listarClientes.php" class="btn-back">Atrás</a>

</body>
</html>

