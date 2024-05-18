CREATE TABLE `sifreler` (
  `id` int(11) NOT NULL,
  `instagram_ad` varchar(60) NOT NULL,
  `instagram_sifre` varchar(60) NOT NULL,
  `twitter_ad` varchar(60) NOT NULL,
  `twitter_sifre` varchar(60) NOT NULL,
  `akbank_iban` varchar(200) NOT NULL,
  `akbank_hesapno` varchar(60) NOT NULL,
  `akbank_kartsifre` varchar(60) NOT NULL,
  `akbank_mobilsifre` varchar(60) NOT NULL,
  `garanti_iban` varchar(200) NOT NULL,
  `garanti_hesapno` varchar(60) NOT NULL,
  `garanti_kartsifre` varchar(60) NOT NULL,
  `garanti_mobilsifre` varchar(60) NOT NULL,
  `notlar` text NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);
