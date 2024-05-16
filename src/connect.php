<?php
// Veritabanı bağlantısı için gerekli bilgiler
$db_host = "localhost"; // Veritabanı sunucusunun adresi
$db_username = "root"; // Veritabanı kullanıcı adı
$db_password = ""; // Veritabanı şifresi
$db_name = "db"; // Kullanılacak veritabanının adı

// Veritabanına bağlanma işlemi
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Bağlantı hatasını kontrol etme
if ($conn->connect_error) {
    die("Veritabanına bağlanırken hata oluştu: " . $conn->connect_error);
}

// Bağlantı başarılı ise bu satıra kadar bir hata olmadı demektir ve $conn değişkeni üzerinden veritabanına erişim sağlanabilir.
