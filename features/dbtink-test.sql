/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table account_owner
# ------------------------------------------------------------

DROP TABLE IF EXISTS `account_owner`;

CREATE TABLE `account_owner` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `identity_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F9EF3F0DFF3ED4A8` (`identity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `account_owner` WRITE;
/*!40000 ALTER TABLE `account_owner` DISABLE KEYS */;

INSERT INTO `account_owner` (`id`, `name`, `identity_id`, `created_at`)
VALUES
	(1,'Owner1','PD001122','2017-11-15 05:27:19'),
	(2,'Owner2','PDddd','2017-11-15 11:28:35'),
	(3,'Owner3','PD34234dd','2017-11-15 13:47:22');

/*!40000 ALTER TABLE `account_owner` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table bank_account
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bank_account`;

CREATE TABLE `bank_account` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `owner_id` bigint(20) DEFAULT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `balance` decimal(16,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'HKD',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `closed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_53A23E0A96901F54` (`number`),
  KEY `IDX_53A23E0A7E3C61F9` (`owner_id`),
  CONSTRAINT `FK_53A23E0A7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `account_owner` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `bank_account` WRITE;
/*!40000 ALTER TABLE `bank_account` DISABLE KEYS */;

INSERT INTO `bank_account` (`id`, `owner_id`, `number`, `balance`, `currency`, `created_at`, `updated_at`, `closed_at`)
VALUES
	(1,1,'400931510694839',68.00,'HKD','2017-11-15 05:27:19','2017-11-15 05:27:19',NULL),
	(2,1,'400931510694840',-85.00,'HKD','2017-11-15 05:27:20','2017-11-15 05:27:20',NULL),
	(3,1,'400931510694841',300.00,'HKD','2017-11-15 05:27:21','2017-11-15 05:27:21','2017-11-15 05:27:30'),
	(5,2,'400931510716515',77.00,'HKD','2017-11-15 11:28:35','2017-11-15 11:28:35',NULL),
	(6,2,'400931510724562',1300.00,'HKD','2017-11-15 13:42:42','2017-11-15 13:42:42',NULL),
	(7,3,'400931510724842',0.00,'HKD','2017-11-15 13:47:22','2017-11-15 13:47:22',NULL);

/*!40000 ALTER TABLE `bank_account` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table general_ledger
# ------------------------------------------------------------

DROP TABLE IF EXISTS `general_ledger`;

CREATE TABLE `general_ledger` (
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `from_account_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_owner_id` bigint(20) DEFAULT NULL,
  `to_owner_id` bigint(20) DEFAULT NULL,
  `to_account_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` decimal(16,2) NOT NULL,
  `service_charge_amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `created` datetime NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `transaction_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_515359F838D38163` (`from_owner_id`),
  KEY `IDX_515359F8B365FF57` (`to_owner_id`),
  CONSTRAINT `FK_515359F838D38163` FOREIGN KEY (`from_owner_id`) REFERENCES `account_owner` (`id`),
  CONSTRAINT `FK_515359F8B365FF57` FOREIGN KEY (`to_owner_id`) REFERENCES `account_owner` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `general_ledger` WRITE;
/*!40000 ALTER TABLE `general_ledger` DISABLE KEYS */;

INSERT INTO `general_ledger` (`uuid`, `from_account_number`, `from_owner_id`, `to_owner_id`, `to_account_number`, `amount`, `service_charge_amount`, `created`, `id`, `transaction_type`)
VALUES
	('34c89023-e44c-4bbe-a45b-7c7fa0414e6f',NULL,NULL,1,'400931510694840',45.00,0.00,'2017-11-15 05:46:27',1,'deposit'),
	('4021284c-1ad9-4041-af29-45ae99b37e33',NULL,NULL,1,'400931510694840',45.00,0.00,'2017-11-15 05:46:50',2,'deposit'),
	('d5034bb2-3f1d-457b-815d-28f6beefeac4',NULL,NULL,1,'400931510694840',45.00,0.00,'2017-11-15 05:46:52',3,'deposit'),
	('5d35f34b-49c2-43b8-a6d7-b976ed30ce43','400931510694840',1,NULL,NULL,-55.00,0.00,'2017-11-15 05:47:03',4,'withdraw'),
	('2d6fedbe-f9b7-4b0e-9228-9021c55be49b','400931510694840',1,NULL,NULL,-55.00,0.00,'2017-11-15 05:47:23',5,'withdraw'),
	('e27888ae-f3fb-4da7-bce2-ec875d0b3365',NULL,NULL,1,'400931510694840',45.00,0.00,'2017-11-15 11:27:29',6,'deposit'),
	('66256eca-0976-4365-be8c-b25677a3061c',NULL,NULL,1,'400931510694840',45.00,0.00,'2017-11-15 11:27:30',7,'deposit'),
	('20793b7c-ebe4-4b28-bbeb-f5a3c445996b','400931510694840',1,1,'400931510694839',37.00,0.00,'2017-11-15 11:27:32',8,'transfer'),
	('f41969b8-32f2-4d8a-894c-6111854fb6e5','400931510694840',1,1,'400931510694839',37.00,0.00,'2017-11-15 11:27:45',9,'transfer'),
	('de860e99-10c2-4206-a738-2be00e1cf842','400931510694840',1,2,'400931510716515',37.00,0.00,'2017-11-15 11:28:50',10,'transfer'),
	('99c0027c-b059-4b25-81f8-47a13c71e1d6','400931510694840',1,NULL,'0000000000',-100.00,0.00,'2017-11-15 11:28:50',11,'service fee'),
	('d2faf2c8-485b-439f-9a05-9e989028540b','400931510694840',1,2,'400931510716515',3.00,0.00,'2017-11-15 11:32:26',12,'transfer'),
	('a42be786-27dd-4b6d-a7dc-d16cd5c30c33','400931510694840',1,NULL,'0000000000',-100.00,0.00,'2017-11-15 11:32:26',13,'service fee'),
	('62e6c223-0c88-4def-92a9-e0264963320d',NULL,NULL,1,'400931510694840',45.00,0.00,'2017-11-15 11:33:18',14,'deposit'),
	('3c06d77e-c17f-4a19-aa8f-ee2624a8aa3c','400931510694840',1,2,'400931510716515',37.00,0.00,'2017-11-15 11:33:26',15,'transfer'),
	('55806e15-fb20-46cc-82d5-c1558e29d163','400931510694840',1,NULL,'0000000000',-100.00,0.00,'2017-11-15 11:33:26',16,'service fee'),
	('8be1b0ab-5d62-438f-9f98-a9ee548032e2','400931510694839',1,1,'400931510694840',1.00,0.00,'2017-11-15 11:34:50',17,'transfer'),
	('380f9af3-d097-4d00-a0c8-910210a9ffa6','400931510694839',1,1,'400931510694840',1.00,0.00,'2017-11-15 11:34:55',18,'transfer'),
	('63192272-8557-4b44-9d9f-17a7f5143be0','400931510694839',1,1,'400931510694840',1.00,0.00,'2017-11-15 11:34:56',19,'transfer'),
	('589cfc55-ed85-4eea-ac1a-3ec35d9c028b','400931510694839',1,1,'400931510694840',1.00,0.00,'2017-11-15 11:58:17',20,'transfer'),
	('ce60bb73-61cd-46a8-8a3b-5148fda89af5','400931510694839',1,1,'400931510694840',1.00,0.00,'2017-11-15 11:58:34',21,'transfer'),
	('53863636-6eb5-419c-b909-c2abd37012e4','400931510694839',1,1,'400931510694840',1.00,0.00,'2017-11-15 11:58:40',22,'transfer');

/*!40000 ALTER TABLE `general_ledger` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
