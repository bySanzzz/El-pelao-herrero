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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
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
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
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
    </style>
</head>
<body>
    <h1>Registro de Pago</h1>
    <?php if (!empty($mensaje)) : ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if ($cliente): ?>
        <div class="cliente-info">
            <h2>Cliente: <?php echo $cliente['nombre'] . " " . $cliente['apellido']; ?></h2>
            <p>DNI: <?php echo $cliente['dni']; ?></p>
            <p>Correo: <?php echo $cliente['correo']; ?></p>
            <p>Estado: <?php echo $cliente['estado'] == 0 ? 'Activo' : 'Inactivo'; ?></p>
        </div>

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
    </a>
                <!-- Botón para regresar -->
                <a class="btn btn-primary btn-back" href="http://localhost/Sportclub/clientes/listarClientes.php">
            Volver a la Página Principal
        </a>
</body>
</html>
