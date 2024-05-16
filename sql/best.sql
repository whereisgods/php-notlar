CREATE TABLE `sifreler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instagram_ad` varchar(60) NOT NULL,
  `instagram_sifre` varchar(60) NOT NULL,
  `twitter_ad` varchar(60) NOT NULL,
  `twitter_sifre` varchar(60) NOT NULL,
  `notlar` text NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
);
