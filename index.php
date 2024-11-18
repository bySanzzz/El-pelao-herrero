<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="../Sportclub/Style/header.css">
    <link rel="stylesheet" href="../Sportclub/Style/index.css">
    <link rel="stylesheet" href="../Sportclub/Style/bodyindex.css">
    <link rel="stylesheet" href="../Sportclub/Style/indexinscrip.css">
    <link rel="stylesheet" href="../Sportclub/Style/footer.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>

<body>
    <header>
        <div class="logo">
            <img src="../Sportclub/Imagenes/SportClub.svg" alt="Logo San Miguel">
            
            </div>
        </div>
        <div class="menu-buttons">
            <button id="openMenu" class="botone">
                <div class="svg-container">
                    <svg width="80px" height="80px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
                        <g id="SVGRepo_bgCarrier" stroke-width="0">
                            <rect x="-2.4" y="-2.4" width="28.80" height="28.80" rx="0" fill="#2b8aaf" stroke-width="0" />
                        </g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
                        <g id="SVGRepo_iconCarrier">
                            <rect x="0" fill="none" width="24" height="24" />
                            <g>
                                <path d="M4 19h16v-2H4v2zm16-6H4v2h16v-2zM4 9v2h16V9H4zm16-4H4v2h16V5z" />
                            </g>
                        </g>
                    </svg>
                </div>
            </button>
        </div>
        <nav class="nav-list">
            <div class="menu-buttons">
                <button id="closeMenu" class="botone2">
                    <div class="svg-container">
                        <svg width="80px" height="80px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
                            <g id="SVGRepo_bgCarrier" stroke-width="0">
                                <rect x="-2.4" y="-2.4" width="28.80" height="28.80" rx="0" fill="#333" stroke-width="0" />
                            </g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
                            <g id="SVGRepo_iconCarrier">
                                <rect x="0" fill="none" width="24" height="24" />
                                <g>
                                    <path d="M4 19h16v-2H4v2zm16-6H4v2h16v-2zM4 9v2h16V9H4zm16-4H4v2h16V5z" />
                                </g>
                            </g>
                        </svg>
                    </div>
                </button>
            </div>
            <ul>
                <h2>
                    <li><a href="http://localhost/Sportclub/Clientes/listarClientes.php">Clientes</a></li>
                </h2>
                <h2>
                    <li><a href="http://localhost:8080/escuela1/profesores/listarProfesor.php">Profesor</a></li>
                </h2>
            </ul>
            <div class="logo2">
                <img src="Imagenes/sanmiguel.png" alt="Logo San Miguel">
            </div>
        </nav>
    </header>

    <script src="../Sportclub/JavaScript/menu.js"></script>

    <div class="query-container">
        <h2>Resumen de Alumnos</h2>
        <!-- Aquí puedes incluir contenido adicional de HTML o JavaScript en lugar del bloque PHP -->
    </div>
    <footer>
        <div class="left-side">
            <div class="social-media">
                <h2>Redes sociales</h2>
                <div class="social-item">
                    <img src="images/instagram.png">
                    <p>Instagram</p>
                </div>
                <div class="social-item">
                    <img src="images/twitter.png">
                    <p>Twitter</p>
                </div>
                <div class="social-item">
                    <img src="images/youtube.png">
                    <p>Youtube</p>
                </div>
                <div class="social-item">
                    <img src="images/whatsapp.png">
                    <p>Whatsapp</p>
                </div>
            </div>
            <div class="technical-service">
                <h2>Soporte de ayuda</h2>
                <p>+54 11 4564 2354</p>
                <p>+54 11 7564 3453</p>
                <p>+54 11 8764 2342</p>
            </div>
        </div>
        <div class="right-side">
            <div class="logo-container-footer">
                <img class="logo-footer" src="images/logito.svg" alt="">
                <p class="name-footer">SportClub</p>
            </div>
            <div>
            <div class="info">
                <p>Política de Publicidad</p>
                <p>Política de Privacidad</p>
                <p>Copyright</p>
                <p>Aviso legal</p>
            </div>
        </div>
    </footer>

    <style>
        .query-container {
            background-color: #ffffff;
            border: 2px solid #2b8aaf;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            margin: 40px auto;
        }

        .query-container h2 {
            color: #2b8aaf;
            text-align: center;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .query-container p {
            font-size: 1.2em;
            color: #333333;
            margin-bottom: 10px;
        }
    </style>
</body>
</html>
