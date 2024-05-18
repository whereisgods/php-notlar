<?php
// Sayfanın başında cookie kontrolü yapalım
if (!isset($_COOKIE['user_id'])) {
    // Eğer user_id çerezi yoksa, kullanıcıyı başka bir sayfaya yönlendir
    header("Location: ../");
    exit; // Diğer işlemleri durdurmak için
}

// Veritabanı bağlantısı
include '../connect.php';

// User ID'yi cookie'den al
$userId = $_COOKIE['user_id'];

// Form gönderilmiş mi diye kontrol et
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $notes = $_POST['notes'];

    // Kayıt var mı diye kontrol et
    $check_sql = "SELECT * FROM sifreler WHERE user_id='$userId'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Kayıt varsa güncelle
        $sql = "UPDATE sifreler SET notlar='$notes' WHERE user_id='$userId'";
    } else {
        // Kayıt yoksa ekle
        $sql = "INSERT INTO sifreler (user_id, notlar) VALUES ('$userId', '$notes')";
    }

    if ($conn->query($sql) === TRUE) {
        $result_message = "Kayıt başarıyla güncellendi";
    } else {
        $result_message = "Hata: " . $sql . "<br>" . $conn->error;
    }
}

// Kullanıcı bilgilerini al
$sql_select = "SELECT notlar FROM sifreler WHERE user_id='$userId'";
$result = $conn->query($sql_select);

$notes = "";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $notes = $row['notlar'];
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
			#notes {
    width: 100%; /* Genişliği yüzde olarak belirleme */
    height: 150px; /* Yüksekliği piksel olarak belirleme */
	resize: both;
}

#balloon {
    background: #333;
    font: 13px asap,arial;
    color: #fff;
    padding: 8px;
    text-align: center;
    border-radius: 3px;
    white-space: nowrap;
    margin: 4px 0 4px 4px;
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
	          <li class="active">
				<a href="./genel"><span class="fa fa-globe mr-3"></span> Genel notlar</a>
				</li>
	          <li>
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
    <h2 class="mb-4">Notların</h2>
    <div class="row">
        <div class="col-md-6">
            <form method="post">
                <div class="form-group">
                    <label for="notes">Notlar:</label>
                    <textarea id="notes" name="notes"><?php echo htmlspecialchars($notes); ?></textarea>
                </div>
				<button type="button" class="btn btn-primary" onclick="copyUrl()">Kopyala</button>
				<button type="submit" class="btn btn-primary">Güncelle</button>
				<div id="balloon" style="display: none;">Kopyalandı!</div>
            </form>
        </div>
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
			
			function copyUrl() {
				var notesTextarea = document.getElementById('notes');
				notesTextarea.select();
				document.execCommand('copy');
				var balloon = document.getElementById('balloon');
				balloon.style.display = 'table';
				setTimeout(function () {
					balloon.style.display = 'none';
				}, 2000);
			}
		</script>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
  </body>
</html>
