CREATE TABLE `duck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `statut` tinyint(4) DEFAULT 0,
  `expiration` timestamp NULL DEFAULT NULL,
  `ts_vente` timestamp NULL DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `gsm` varchar(20) DEFAULT NULL,
  `surnom` varchar(100) DEFAULT NULL,
  `id_txn` int(11) DEFAULT NULL,
  `token` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `txn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NULL DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `gsm` varchar(20) DEFAULT NULL,
  `payment_id` varchar(50) DEFAULT NULL,
  `expiration_ts` timestamp NULL DEFAULT NULL,
  `payload` text DEFAULT NULL,
  `payment_trace` text DEFAULT NULL,
  `statut` tinyint(4) DEFAULT 0,
  `token` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
);