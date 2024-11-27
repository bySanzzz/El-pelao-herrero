<?php
include("../conexion.php");

//-----------------------------------------------------------------PAGINADO
$limite = 8;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $limite;

//----------------------------------------------------------------ORDEN TABLA
$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'nombre';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$validColumns = ['nombre', 'apellido', 'fecha_contrato'];
if (!in_array($orderBy, $validColumns)) {
    $orderBy = 'nombre';
}

//-----------------------------------------------------------------CONSULTA PRINCIPAL
$query = "
    SELECT 
        entrenador.dni, 
        entrenador.nombre, 
        entrenador.apellido, 
        entrenador.telefono, 
        actividad.nombre AS especialidad, 
        entrenador.fecha_contrato
    FROM 
        entrenador
    LEFT JOIN 
        actividad 
    ON 
        entrenador.id_actividad = actividad.id_actividad"; // Relación ajustada


if (!empty($search)) {
    $query .= " WHERE 
        (entrenador.nombre LIKE '%$search%' OR 
        entrenador.apellido LIKE '%$search%' OR 
        actividad.nombre LIKE '%$search%')";
}

$query .= " ORDER BY $orderBy LIMIT $limite OFFSET $offset";
$result = mysqli_query($conex, $query) or die("ERROR AL OBTENER ENTRENADORES");

//-----------------------------------------------------------------OBTENER TOTAL DE REGISTROS
$queryTotal = "
    SELECT 
        COUNT(DISTINCT entrenador.dni) AS total 
    FROM 
        entrenador
    LEFT JOIN 
        actividad 
    ON 
        entrenador.dni = actividad.Dni_entrenador";

if (!empty($search)) {
    $queryTotal .= " WHERE 
        (entrenador.nombre LIKE '%$search%' OR 
        entrenador.apellido LIKE '%$search%' OR 
        actividad.nombre LIKE '%$search%')";
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
    <title>Listado Entrenadores</title>
    <link rel="stylesheet" href="../SportClub/Style/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <h1 class='text-center-titulo'>Lista de Entrenadores</h1><br>
        <a class="btn-accion" href="Form-Alta-Entrenador.php">
            <img src="../SVG/Agregar.svg" alt="Agregar" class="icono" width="24px">
        </a>
    </header>
    <!-- Filtros y búsqueda -->
    <div class='container'>
        <div class='row mb-3'>
            <div class='col-md-3'>
                <label for='orderSelect'>Ordenar por:</label>
                <select class='form-select' id='orderSelect' onchange='changeFilter()'>
                    <option value='nombre' <?php echo $orderBy == 'nombre' ? 'selected' : ''; ?>>Nombre</option>
                    <option value='apellido' <?php echo $orderBy == 'apellido' ? 'selected' : ''; ?>>Apellido</option>
                    <option value='fecha_contrato' <?php echo $orderBy == 'fecha_contrato' ? 'selected' : ''; ?>>Fecha de Contrato</option>
                </select>
            </div>
            <div class='col-md-3'>
                <label for="searchInput"></label>
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar entrenador..." value="<?php echo $search; ?>" onkeypress="handleSearchKeypress(event)">
                    <button class="input-group-text" onclick="changeFilter()">Buscar</button>
                </div>
            </div>
        </div>

        <!-- Tabla de entrenadores -->
        <table class='table table-striped'>
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Especialidad</th>
                    <th>Fecha de Contrato</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($result)) { ?>
                    <tr>
                        <td><?php echo $row['dni']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['apellido']; ?></td>
                        <td><?php echo $row['telefono']; ?></td>
                        <td><?php echo $row['especialidad'] ? $row['especialidad'] : 'Sin actividad asignada'; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($row['fecha_contrato'])); ?></td>
                        <td class="acciones">
                            <a class="btn-accion" href="Form-Modi-Entrenador.php?entrenador=<?php echo $row['dni']; ?>">
                                <img src="../SVG/Perfil.svg" alt="Modificar" class="icono" width="24px">
                            </a>
                            <form method="POST" action="Form-Eliminar-Entrenador.php" style="display:inline;">
                                <input type="hidden" name="DNI" value="<?php echo $row['dni']; ?>">
                                <button type="submit" class="btn-accion">
                                    <img src="../SVG/Eliminar.svg" alt="Eliminar" class="icono">
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <nav>
            <ul class='pagination'>
                <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
                    <li class='page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>'>
                        <a class='page-link' href='listado_entrenadores.php?pagina=<?php echo $i; ?>&orderBy=<?php echo $orderBy; ?>&search=<?php echo $search; ?>'><?php echo $i; ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>

    <script>
        function changeFilter() {
            let orderBy = document.getElementById('orderSelect').value;
            let search = document.getElementById('searchInput').value;
            let url = `listado_entrenadores.php?pagina=1&orderBy=${orderBy}&search=${search}`;
            window.location.href = url;
        }

        function handleSearchKeypress(event) {
            if (event.key === 'Enter') {
                changeFilter();
            }
        }
    </script>
</body>
</html>
