<?php
include("../conexion.php");




//-----------------------------------------------------------------PAGINADO
$limite = 8;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $limite;

//----------------------------------------------------------------ORDEN TABLA
$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'nombre';
$status = isset($_GET['status']) ? $_GET['status'] : '0';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$validColumns = ['nombre', 'apellido', 'fecha_inscripcion'];
if (!in_array($orderBy, $validColumns)) {
    $orderBy = 'nombre';
}

$validStatus = ['0', '1']; // Puedes adaptar según tus necesidades
if (!in_array($status, $validStatus)) {
    $status = '0';
}

//-----------------------------------------------------------------CONSULTA PRINCIPAL
$query = "SELECT cliente.dni, cliente.nombre, cliente.apellido, cliente.fecha_inscripcion, cliente.correo, cliente.telefono, cliente.estado
          FROM cliente
          WHERE cliente.estado = $status";

// Agregar búsqueda si se introduce una búsqueda
if (!empty($search)) {
    $query .= " AND (cliente.nombre LIKE '%$search%' OR cliente.apellido LIKE '%$search%')";
}

$query .= " ORDER BY $orderBy LIMIT $limite OFFSET $offset";
$result = mysqli_query($conex, $query) or die("ERROR AL OBTENER CLIENTES");

// Obtener el número total de registros
$queryTotal = "SELECT COUNT(*) AS total FROM cliente WHERE estado = $status";
if (!empty($search)) {
    $queryTotal .= " AND (nombre LIKE '%$search%' OR apellido LIKE '%$search%')";
}
$resultTotal = mysqli_query($conex, $queryTotal) or die("ERROR DE CONTEO");
$rowTotal = mysqli_fetch_assoc($resultTotal);
$total_records = $rowTotal['total'];
$total_paginas = ceil($total_records / $limite);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado Clientes</title>
    <link rel="stylesheet" href="../Style/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: rgba(32,33,40,255); /* Fondo debajo del header */
            color: white; /* Ajustar texto para mejor visibilidad */
        }
        header {
            background-color: rgba(51,51,51,255); /* Color del header */
            padding: 15px;
            color: white;
            text-align: center;
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
        .table {
            background-color: white; /* Fondo blanco para la tabla */
            color: black; /* Texto negro */
            border-radius: 5px; /* Bordes redondeados */
            overflow: hidden;
        }
    </style>
</head>

<body>
    <header>
        <h1 class="header-title">Lista de Clientes</h1>
    </header>

    <!-- Contenedor para botones -->
    <div class="btn-container">
        <!-- Botón para volver al inicio -->
        <a href="http://localhost/Sportclub/index.php" class="btn-custom">Volver al Inicio</a>
        <!-- Botón para agregar cliente -->
        <a href="http://localhost/Sportclub/Clientes/Form-Alta-Clientes.php" class="btn-custom">Agregar Cliente</a>
    </div>

    <!-- Filtros y búsqueda -->
    <div class='container mt-3'>
        <div class='row mb-3'>
            <div class='col-md-3'>
                <label for='orderSelect'>Ordenar por:</label>
                <select class='form-select' id='orderSelect' onchange='changeFilter()'>
                    <option value='nombre' <?php echo $orderBy == 'nombre' ? 'selected' : ''; ?>>Nombre</option>
                    <option value='apellido' <?php echo $orderBy == 'apellido' ? 'selected' : ''; ?>>Apellido</option>
                    <option value='fecha_inscripcion' <?php echo $orderBy == 'fecha_inscripcion' ? 'selected' : ''; ?>>Fecha de Inscripción</option>
                </select>
            </div>
            <div class='col-md-3'>
                <label for='statusSelect'>Estado:</label>
                <select class='form-select' id='statusSelect' onchange='changeFilter()'>
                    <option value='0' <?php echo $status == '0' ? 'selected' : ''; ?>>ACTIVOS</option>
                    <option value='1' <?php echo $status == '1' ? 'selected' : ''; ?>>INACTIVOS</option>
                </select>
            </div>
            <div class='col-md-3'>
                <label for="searchInput"></label>
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar cliente..." value="<?php echo $search; ?>" onkeypress="handleSearchKeypress(event)">
                    <button class="input-group-text" onclick="changeFilter()">Buscar</button>
                </div>
            </div>
        </div>

        <!-- Tabla de clientes -->
        <table class='table table-striped'>
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Fecha de Inscripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($result)) { ?>
                    <tr>
                        <td><?php echo $row['dni']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['apellido']; ?></td>
                        <td><?php echo $row['correo']; ?></td>
                        <td><?php echo $row['telefono']; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($row['fecha_inscripcion'])); ?></td>
                        <td><?php echo $row['estado'] == 0 ? 'Activo' : 'Inactivo'; ?></td>
                        <td class="acciones">
                            <a class="btn-accion" href="Form-Modi-Clientes.php?cliente=<?php echo $row['dni']; ?>">
                                <img src="../SVG/Perfil.svg" alt="Modificar" class="icono" width="24px">
                            </a>
                            <a class="btn-accion" href="pago_cliente.php?cliente=<?php echo $row['dni']; ?>">
                                <img src="../Imagenes/cuota.png"  class="icono" width="24px">
                            </a>
                            <?php if ($row['estado'] == 0) { // Solo si está activo ?>
                                <a class="btn-accion" href="actividades.php?cliente=<?php echo $row['dni']; ?>">
                                    <img src="../Imagenes/Gym.png" alt="Gimnasio" class="icono" width="24px">
                                </a>
                            <?php } else { // Mostrar un ícono deshabilitado si está inactivo ?>
                                <img src="../Imagenes/Close.png" alt="Inactivo" class="icono" width="24px" title="Cliente inactivo">
                            <?php } ?>
                            <a class="btn-accion" href="vista-entrenamiento.php?cliente=<?php echo $row['dni']; ?>">
                                <img src="../Imagenes/Entrenamiento.png" alt="Gimnasio" class="icono" width="24px">
                            </a>
                            <style>
                                .icono {
                                    width: 24px;
                                    height: 24px; /* Asegúrate de que todos los íconos tengan el mismo tamaño */
                                    margin: 0 5px; /* Espaciado entre los íconos */
                                    vertical-align: middle; /* Alinear íconos con el texto */
                                }
                            </style>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <nav>
            <ul class='pagination'>
                <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
                    <li class='page-item <?php if ($i == $pagina_actual) echo 'active'; ?>'>
                        <a class='page-link' href='?pagina=<?php echo $i; ?>&orderBy=<?php echo $orderBy; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>'>
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>

    <script>
        function changeFilter() {
            var orderBy = document.getElementById('orderSelect').value;
            var status = document.getElementById('statusSelect').value;
            var search = document.getElementById('searchInput').value;
            window.location.href = '?orderBy=' + orderBy + '&status=' + status + '&search=' + search;
        }

        function handleSearchKeypress(event) {
            if (event.key === 'Enter') {
                changeFilter();
            }
        }
    </script>
</body>
</html>



