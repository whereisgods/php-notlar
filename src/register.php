<?php
// Cookie var mı kontrol et
if(isset($_COOKIE['user_id'])) {
    // Cookie var ise yönlendir
    header("Location: ./");
    exit(); // Yönlendirmeden sonra scriptin devam etmemesi için exit() kullanılır
} else {
    // Cookie yok ise farklı bir işlem yapabilir veya kullanıcıyı bilgilendirebilirsiniz
}
?>
<?php
session_start();

// Veritabanı bağlantısını yapılandırma
include('connect.php');

// Hata dizisi
$errors = [];

// Form gönderildiğinde CAPTCHA'yı kontrol etmek için
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kullanıcı adı ve şifre alınır
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // CAPTCHA'yı kontrol et
    $captcha_response = $_POST['g-recaptcha-response'];
    $secret_key = '6Lemq-ApAAAAAEMHwh_Uk0BMoF5caeAizzJ96pJr'; // Google reCAPTCHA gizli anahtarınız
    $verify_response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha_response");
    $response_data = json_decode($verify_response);
    
    if (!$response_data->success) {
        // CAPTCHA doğrulanmadıysa, hata ekleyin
        $errors[] = "Lütfen CAPTCHA'yı doğru şekilde doldurun.";
    }

    // Diğer form doğrulamaları burada devam eder...
    
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
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Bu kullanıcı adı zaten kullanılıyor. Lütfen farklı bir kullanıcı adı seçin.";
        } else {
            // Şifreyi hashleyin ve veritabanına ekleyin
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Rastgele bir ID oluşturun
            $user_id = bin2hex(random_bytes(16)); // 32 karakter uzunluğunda rastgele bir SHA-256 ID

            $stmt = $conn->prepare("INSERT INTO users (id, username, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $user_id, $username, $hashed_password);

            if ($stmt->execute()) {
                // Kayıt başarılı, kullanıcıyı hoş geldiniz sayfasına yönlendirin
                header("Location: ./login");
                exit();
            } else {
                $errors[] = "Kayıt sırasında bir hata oluştu: " . $conn->error;
            }
        }
        $stmt->close();
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
    <meta name="description" content="Kayıt ol.">
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
                            <div class="hata form-group">
                                <!-- Google reCAPTCHA alanı -->
                                <div class="g-recaptcha" data-sitekey="6Lemq-ApAAAAAJsrR5NdlF5enc06eHnCRxn6Rp_u"></div>
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
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
<script>
    // Formun birden fazla gönderilmesini engellemek için spam koruması ekle
    var formSubmitted = false;
    document.getElementById("registerForm").addEventListener("submit", function (event) {
        if (formSubmitted) {
            event.preventDefault();
            alert("Form zaten gönderildi, lütfen bekleyin.");
        } else {
            formSubmitted = true;
            setTimeout(function () {
                formSubmitted = false;
            }, 10000); // 10 saniye beklet
        }
    });
</script>
</body>
</html>
