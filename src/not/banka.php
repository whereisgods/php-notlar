<!doctype html>
<html lang="tr">
<head>
    <title>Notlar - Anasayfa</title>
    <link rel="icon" href="../logo.jpg">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Dark theme styles */
        body[data-bs-theme="dark"] {
            background-color: #222;
            color: #fff;
        }

        body[data-bs-theme="dark"] h1,
        body[data-bs-theme="dark"] h2,
        body[data-bs-theme="dark"] h3,
        body[data-bs-theme="dark"] h4,
        body[data-bs-theme="dark"] h5,
        body[data-bs-theme="dark"] h6 {
            color: #fff;
        }

        body[data-bs-theme="dark"] a {
            color: #fff;
        }

        body[data-bs-theme="dark"] a:hover {
            color: #ccc;
        }

        /* Dark theme for cards */
        body[data-bs-theme="dark"] .card {
            background-color: #333;
            border-color: #444;
            min-height: 400px; /* Adjust the minimum height */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        body[data-bs-theme="dark"] .card-title,
        body[data-bs-theme="dark"] .card-text {
            color: #fff;
        }

        /* Adjusting the card image size */
        .card-img-top {
            height: 300px; /* Increase the height of the image */
            max-width: 100%;
            object-fit: cover;
        }

        .card-body {
            padding: 20px; /* Adjust the padding */
            flex: 1; /* Allow the card body to take up available space */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
    </style>
</head>
<body data-bs-theme="dark">
<div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar" class="active">
        <div class="custom-menu">
            <button type="button" id="sidebarCollapse" class="btn btn-primary">
                <i class="fa fa-bars"></i>
                <span class="sr-only">Toggle Menu</span>
            </button>
        </div>
        <div class="p-4">
            <h1><a href="../" class="logo">Notlar</a></h1>
            <ul class="list-unstyled components mb-5">
                <li>
                    <button class="btn btn-outline-light" id="toggle-theme">Light</button>
                </li>
                <li>
                    <a href="./"><span class="fa fa-home mr-3"></span> Anasayfa</a>
                </li>
                <li>
                    <a href="./genel"><span class="fa fa-globe mr-3"></span> Genel notlar</a>
                </li>
                <li>
                    <a href="./sosyal"><span class="fa fa-hashtag mr-3"></span> Sosyal medya</a>
                </li>
                <li class="active">
                    <a href="./banka"><span class="fa fa-bank mr-3"></span> Bankalar</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5 pt-5">
        <div class="container mt-5">
            <h2 class="mb-4">Bankalar</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <img class="card-img-top img-fluid" src="4.png" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">Akbank</h5>
                            <p class="card-text">Akbank hesabına ait bilgileri görüntülemek için buraya git.</p>
                            <a href="./akbank" class="btn btn-primary">Git</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <img class="card-img-top" src="5.png" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">Garanti BBVA</h5>
                            <p class="card-text">Garanti hesabına ait bilgileri görüntülemek için buraya git.</p>
                            <a href="./garanti" class="btn btn-primary">Git</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const toggleButton = document.getElementById('toggle-theme');
    const body = document.body;
    let currentTheme = 'dark';
    toggleButton.addEventListener('click', function () {
        if (body.getAttribute('data-bs-theme') === 'light') {
            body.setAttribute('data-bs-theme', 'dark');
            toggleButton.textContent = 'Light';
            currentTheme = 'dark';
        } else {
            body.setAttribute('data-bs-theme', 'light');
            toggleButton.textContent = 'Dark';
            currentTheme = 'light';
        }
        localStorage.setItem('theme', currentTheme);
    });
    window.addEventListener('DOMContentLoaded', function () {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            body.setAttribute('data-bs-theme', savedTheme);
            if (savedTheme === 'dark') {
                toggleButton.textContent = 'Light';
            } else {
                toggleButton.textContent = 'Dark';
            }
            currentTheme = savedTheme;
        }
    });
</script>
<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>