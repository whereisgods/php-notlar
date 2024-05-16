<?php
// Cookie var mı kontrol et
if(isset($_COOKIE['user_id'])) {
    // Cookie var ise yönlendir
    header("Location: ../");
    exit(); // Yönlendirmeden sonra scriptin devam etmemesi için exit() kullanılır
} else {
    // Cookie yok ise farklı bir işlem yapabilir veya kullanıcıyı bilgilendirebilirsiniz
}
?>
<?php
session_start(); // Oturumu başlat

include('connect.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    if (empty($username) || empty($password)) {
        $errors[] = "Tüm alanları doldurun.";
    }

    if (count($errors) === 0) {
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                // Kullanıcı giriş yaptı, oturumu başlat
                $_SESSION['user_id'] = $user['id'];

                // Kullanıcının girişi hatırlaması için çerez oluştur
                if (isset($_POST['remember_me']) && $_POST['remember_me'] == 1) {
                    setcookie("user_id", $user['id'], time() + (30 * 24 * 60 * 60), "/", "", true, true);
                }

                header("Location: ./"); // Başarılı giriş sonrası yönlendir
                exit();
            } else {
                $errors[] = "Şifre yanlış.";
            }
        } else {
            $errors[] = "Kullanıcı bulunamadı.";
        }
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="tr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş yap - Not uygulaması</title>
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
                <a class="nav-link active" aria-current="page" href="./login">Giriş yap</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>    
    </header>
    <main>
      <div class="container mt-5">
        <div class="row justify-content-center"> <!-- Burada değişiklik -->
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                Giriş Yap
              </div>
              <div class="card-body">
                <?php
                if (count($errors) > 0) {
                  foreach ($errors as $error) {
                    echo '<div class="alert alert-danger mb-3">' . $error . '</div>';
                  }
                }
                ?>

                <form id="loginForm" action="" method="POST">
                  <div class="hata form-group">
                    <label for="username">Kullanıcı Adı</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                  </div>
                  <div class="hata form-group">
                    <label for="password">Şifre</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>
                  <div class="hata form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me" value="1">
                    <label class="form-check-label" for="remember_me">Beni Hatırla</label>
                  </div>
                  <div class="hata">
                  <button type="submit" class="btn btn-primary">Giriş Yap</button>
            </div>
                </form>
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
    <script>
      // Formun birden fazla gönderilmesini engellemek için spam koruması ekle
      var formSubmitted = false;
      document.getElementById("loginForm").addEventListener("submit", function(event) {
        if (formSubmitted) {
          event.preventDefault();
          alert("Form zaten gönderildi, lütfen bekleyin.");
        } else {
          formSubmitted = true;
          setTimeout(function() {
            formSubmitted = false;
          }, 5000); // 5 saniye beklet
        }
      });
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </body>
</html>