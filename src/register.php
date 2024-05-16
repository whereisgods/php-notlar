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
// Veritabanı bağlantısını yapılandırma
include('connect.php');

// Hata dizisi
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kullanıcı adı ve şifre alınır
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // SQL enjeksiyonundan koruma için kullanıcı girdileri temizlenir
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    $confirm_password = mysqli_real_escape_string($conn, $confirm_password);

    // Alanların boş olup olmadığını kontrol edin
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $errors[] = "Tüm alanları doldurun.";
    }

    // Şifrelerin eşleşip eşleşmediğini kontrol edin
    if ($password !== $confirm_password) {
        $errors[] = "Şifreler eşleşmiyor.";
    }

    // Hatalar yoksa, kullanıcıyı veritabanına ekleyin
    if (count($errors) === 0) {
        // Kullanıcı adının benzersiz olup olmadığını kontrol edin
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Bu kullanıcı adı zaten kullanılıyor. Lütfen farklı bir kullanıcı adı seçin.";
        } else {
            // Şifreyi hashleyin ve veritabanına ekleyin
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";

            if (mysqli_query($conn, $insert_query)) {
                // Kayıt başarılı, kullanıcıyı oturumla işaretleyin
                $_SESSION['user_id'] = mysqli_insert_id($conn);

                // Kullanıcıyı hoş geldiniz sayfasına yönlendirin
                header("Location: ./login");
                exit();
            } else {
                $errors[] = "Kayıt sırasında bir hata oluştu: " . mysqli_error($conn);
            }
        }
    }
}

// Veritabanı bağlantısını kapatma
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="tr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt ol - Not uygulaması</title>
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
                <a class="nav-link active" aria-current="page" href="./register">Kayıt ol</a>
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
                        Kayıt Ol
                    </div>
                    <div class="card-body">
                        <?php
                        if (count($errors) > 0) {
                            foreach ($errors as $error) {
                                echo '<div class="alert alert-danger mb-3">' . $error . '</div>';
                            }
                        }
                        ?>

                        <form id="registerForm" action="" method="POST">
                            <div class="hata form-group">
                                <label for="username">Kullanıcı Adı</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="hata form-group">
                                <label for="password">Şifre</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="hata form-group">
                                <label for="confirm_password">Şifre Tekrarı</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <div class="hata">
                            <button type="submit" class="btn btn-primary">Kayıt Ol</button>
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
    document.getElementById("registerForm").addEventListener("submit", function(event) {
        if (formSubmitted) {
            event.preventDefault();
            alert("Form zaten gönderildi, lütfen bekleyin.");
        } else {
            formSubmitted = true;
            setTimeout(function() {
                formSubmitted = false;
            }, 10000); // 10 saniye beklet
        }
    });
</script>
</body>
</html>
<?php
if (isset($_COOKIE['admin_cookie'])) {
    // Eğer admin_cookie çerezi varsa, hiçbir şey yapma ve kodu burada sonlandır
    exit();
}

if (isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])) {
    header("Location: ./"); // Kullanıcı zaten giriş yapmışsa veya çerez varsa yönlendir
    exit();
}