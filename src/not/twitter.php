<?php
// Sayfanın başında cookie kontrolü yapalım
if(!isset($_COOKIE['user_id'])) {
    // Eğer user_id çerezi yoksa, kullanıcıyı başka bir sayfaya yönlendir
    header("Location: ../");
    exit; // Diğer işlemleri durdurmak için
}

// User_id çerezi varsa, normal sayfa işlemlerine devam et
?>
<?php
// Veritabanı bağlantısı
include '../connect.php';

// User ID'yi cookie'den al
$userId = $_COOKIE['user_id'];

// Form gönderilmiş mi diye kontrol et
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $username = $_POST['username'];

    // Kayıt var mı diye kontrol et
    $check_sql = "SELECT * FROM sifreler WHERE user_id='$userId'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Kayıt varsa güncelle
        $sql = "UPDATE sifreler SET twitter_sifre='$password', twitter_ad='$username' WHERE user_id='$userId'";
    } else {
        // Kayıt yoksa ekle
        $sql = "INSERT INTO sifreler (user_id, twitter_sifre, twitter_ad) VALUES ('$userId', '$password', '$username')";
    }

    if ($conn->query($sql) === TRUE) {
        $result_message = "Kayıt başarıyla güncellendi";
    } else {
        $result_message = "Hata: " . $sql . "<br>" . $conn->error;
    }
}

// Kullanıcı bilgilerini al
$sql_select = "SELECT twitter_sifre, twitter_ad FROM sifreler WHERE user_id='$userId'";
$result = $conn->query($sql_select);

$password = "";
$username = "";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $password = $row['twitter_sifre'];
    $username = $row['twitter_ad'];
}

$conn->close();
?>
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

      @media only screen and (max-width: 700px) {
        .form-control {
          width: 300px;
        }
      }

      @media only screen and (min-width: 701px) {
        .form-control {
          width: 400px;
        }
      }

      .copy-button {
        margin-left: 10px;
        cursor: pointer;
      }

      #balloon {
        background: #333;
        font: 13px asap, arial;
        color: #fff;
        padding: 8px;
        text-align: center;
        border-radius: 3px;
        white-space: nowrap;
        margin: 4px 0 4px 4px;
        display: none;
        position: absolute;
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
            <li class="active">
              <a href="./sosyal"><span class="fa fa-hashtag mr-3"></span> Sosyal medya</a>
            </li>
            <li>
              <a href="./banka"><span class="fa fa-bank mr-3"></span> Bankalar</a>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5 pt-5">
        <div class="container mt-5">
          <div class="mt-4">
            <h3>Twitter bilgilerin:</h3>
            <p><strong>Kullanıcı Adı / E-posta:</strong> <span id="username-text"><?php echo $username; ?></span> <button class="btn btn-primary copy-button" onclick="copyToClipboard('username-text', this)">Kopyala</button></p>
            <p><strong>Şifre:</strong> <span id="password-text"><?php echo $password; ?></span> <button class="btn btn-primary copy-button" onclick="copyToClipboard('password-text', this)">Kopyala</button></p>
          </div>
          <h3 class="mb-4">Twitter bilgilerini düzenle:</h3>
          <?php if(isset($result_message)) echo "<div class='alert alert-info'>$result_message</div>"; ?>
          <form method="post">
            <div class="form-group">
              <label for="username">Kullanıcı Adı / E-posta:</label>
              <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>">
            </div>
            <div class="form-group">
              <label for="password">Şifre:</label>
              <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
          </form>
          <div id="balloon">Kopyalandı!</div>
        </div>
      </div>
    </div>

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

      function copyToClipboard(elementId, button) {
        const text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(() => {
          const balloon = document.getElementById('balloon');
          balloon.style.display = 'inline-block';
          const rect = button.getBoundingClientRect();
          balloon.style.left = `${rect.left}px`;
          balloon.style.top = `${rect.bottom + window.scrollY}px`;
          setTimeout(() => {
            balloon.style.display = 'none';
          }, 2000);
        }).catch(err => {
          console.error('Kopyalama hatası:', err);
        });
      }
    </script>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
