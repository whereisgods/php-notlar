<?php
session_start();

include('connect.php');

$user_id = null;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} elseif (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
}

$username = ""; // Varsayılan kullanıcı adı

if ($user_id !== null) {
    $query = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        htmlspecialchars($username = $user['username']);
    }
}

?>
<!DOCTYPE html>
<html lang="tr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoş geldiniz - Not uygulaması</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="./logo.jpg">
    <style>
      .dark {
          color: white;
      }

      .hata {
        margin-top: 10px;
      }
    </style>
  </head>
  <body data-bs-theme="dark">
    <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="./">
            <img src="./logo.jpg" alt="Linux Logo" height="40">
          </a>
          <div class="dark">
            <li>
              <button class="btn btn-outline-light" id="toggle-theme">Light</button>
            </li>
          </div>
          <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="./register">Kayıt ol</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="./login">Giriş yap</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>    
    </header>
    <main>
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <?php
                    if ($username !== "") {
                        echo "Hoş Geldin, " . $username . "!<br>Notlarına mı bakmak istiyorsun? <a style='text-decoration: none;' href='not'>Hemen başla.</a>";
                    } else {
                        echo "Hoş Geldiniz!";
                    }
                    ?>
                </div>
                <div class="card-body">
                    <?php
                    if ($username !== "") {
                        echo '<a href="logout" class="btn btn-danger">Çıkış Yap</a><br><hr>
                        Uygulamalarda ki şifrelerini ve kullanıcı adlarını barındırmak için basit ve modern bir not uygulaması, hızlıdır ve güvenlidir.<br><br>Servis sıkıntısız çalışıyor: 18.05.2024';
                    } else {
                        echo '<p>Bu bir not uygulaması, önce giriş yapmalısın veya kayıt olmalısın.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
    </main>
    <script>
      const toggleButton = document.getElementById('toggle-theme');
      const body = document.body;
      let currentTheme = 'dark';
      toggleButton.addEventListener('click', function() {
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
      window.addEventListener('DOMContentLoaded', function() {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </body>
</html>
