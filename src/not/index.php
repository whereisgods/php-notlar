<?php
// Sayfanın başında cookie kontrolü yapalım
if(!isset($_COOKIE['user_id'])) {
    // Eğer user_id çerezi yoksa, kullanıcıyı başka bir sayfaya yönlendir
    header("Location: ../");
    exit; // Diğer işlemleri durdurmak için
}

// User_id çerezi varsa, normal sayfa işlemlerine devam et
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
	          <li class="active">
	            <a href="./"><span class="fa fa-home mr-3"></span> Anasayfa</a>
	          </li>
	          <li>
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
        <h2 class="mb-4">Hoş geldin</h2>
		<img class="img-fluid" src="1.png">
        <p>Hoş geldin, sol bardan sosyal medya veya bankalar kısmını seçip çeşitli banka veya sosyal medya uygulamalarında olan bilgilerini kaydedebilir ve kaydettiğin notları görüntüleyebilirsin. Başka platformlar kullanıyorsan Genel Notlar kısmına giderek koyduğun diğer şifreleri görüntüleyebilirsin. Merak etme, güvendeler.</p>
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
		  </script>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>