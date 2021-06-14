CREATE TABLE IF NOT EXISTS `list_admin_status` (
  `admin_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_status` varchar(50) NOT NULL,
  PRIMARY KEY (`admin_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `list_admin_status` (`admin_status_id`, `admin_status`) VALUES
	(1, 'Aktif'),
	(2, 'Tidak Aktif'),
	(3, 'Banned');

CREATE TABLE IF NOT EXISTS `list_division` (
  `division_id` int(11) NOT NULL AUTO_INCREMENT,
  `division_name` varchar(50) NOT NULL,
  PRIMARY KEY (`division_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `list_division` (`division_id`, `division_name`) VALUES
	(1, 'System Administrator');


-- Dumping structure for table template_lte.list_access_control
CREATE TABLE IF NOT EXISTS `list_access_control` (
  `admin_tier_id` int(11) NOT NULL AUTO_INCREMENT,
  `access_level` int(11) NOT NULL,
  `access_divisionId` int(11) NOT NULL,
  `access_levelName` varchar(50) NOT NULL,
  PRIMARY KEY (`admin_tier_id`),
  KEY `FK__list_division` (`access_divisionId`),
  CONSTRAINT `FK__list_division` FOREIGN KEY (`access_divisionId`) REFERENCES `list_division` (`division_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table template_lte.list_access_control: ~0 rows (approximately)
/*!40000 ALTER TABLE `list_access_control` DISABLE KEYS */;
INSERT INTO `list_access_control` (`admin_tier_id`, `access_level`, `access_divisionId`, `access_levelName`) VALUES
	(1, 100, 1, 'Super Admin');
/*!40000 ALTER TABLE `list_access_control` ENABLE KEYS */;

-- Dumping structure for table template_lte.list_admin
CREATE TABLE IF NOT EXISTS `list_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(125) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_email` varchar(80) NOT NULL,
  `admin_phone` varchar(20) NOT NULL,
  `admin_lastLogin` datetime DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `admin_statusId` int(11) NOT NULL,
  `admin_tierId` int(11) NOT NULL,
  PRIMARY KEY (`admin_id`),
  KEY `FK_list_admin_list_admin_status` (`admin_statusId`),
  KEY `FK_list_admin_list_access_control` (`admin_tierId`),
  CONSTRAINT `FK_list_admin_list_access_control` FOREIGN KEY (`admin_tierId`) REFERENCES `list_access_control` (`admin_tier_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_list_admin_list_admin_status` FOREIGN KEY (`admin_statusId`) REFERENCES `list_admin_status` (`admin_status_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table template_lte.list_admin: ~1 rows (approximately)
/*!40000 ALTER TABLE `list_admin` DISABLE KEYS */;
INSERT INTO `list_admin` (`admin_id`, `admin_name`, `admin_password`, `admin_email`, `admin_phone`, `admin_lastLogin`, `created_date`, `updated_date`, `admin_statusId`, `admin_tierId`) VALUES
	(1, 'Sanjaya', '$2y$10$QEo45sr7r.Cus/cepy9WgOIuCbRwMYkdvcATGoP9xsN2EHXYUA2EK', 'rickysanjaya411@gmail.com', '08', NULL, NULL, NULL, 1, 1);

-- Dumping structure for table template_lte.list_session_token
CREATE TABLE IF NOT EXISTS `list_session_token` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_token` varchar(100) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `active_time` datetime NOT NULL,
  `expire_time` datetime NOT NULL,
  `is_login` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`session_id`),
  KEY `FK__list_admin` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;



-- Added date 20 Desember 2020
CREATE TABLE IF NOT EXISTS `list_aboutme` (
  `aboutme_id` int(11) NOT NULL AUTO_INCREMENT,
  `aboutme_fullName` varchar(50) NOT NULL,
  `aboutme_profesionalName` varchar(50) NOT NULL,
  `aboutme_description` text NOT NULL,
  `aboutme_linkedin` varchar(100) DEFAULT NULL,
  `aboutme_github` varchar(100) DEFAULT NULL,
  `aboutme_dribbble` varchar(100) DEFAULT NULL,
  `photo_path` text DEFAULT NULL,
  PRIMARY KEY (`aboutme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Added date 28 Desember 2020
CREATE TABLE IF NOT EXISTS `list_skill_kategori` (
  `kategori_id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori` varchar(50) NOT NULL,
  PRIMARY KEY (`kategori_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `list_skill_kategori` (`kategori_id`, `kategori`) VALUES
	(1, 'Design'),
	(2, 'Development');

CREATE TABLE IF NOT EXISTS `list_skill` (
  `skill_id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  PRIMARY KEY (`skill_id`),
  KEY `FK__list_skill_kategori` (`kategori_id`),
  CONSTRAINT `FK__list_skill_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `list_skill_kategori` (`kategori_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `list_portofolio` (
  `portofolio_id` int(11) NOT NULL AUTO_INCREMENT,
  `portofolio_name` varchar(100) NOT NULL,
  `portofolio_description` text NOT NULL,
  `portofolio_image` text NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 0,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`portofolio_id`),
  KEY `kategori_id` (`kategori_id`),
  CONSTRAINT `FK_list_portofolio_list_skill_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `list_skill_kategori` (`kategori_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `list_kontak` (
  `kontak_id` int(11) NOT NULL AUTO_INCREMENT,
  `kontak_name` varchar(100) NOT NULL,
  `kontak_value` varchar(50) NOT NULL,
  `kontak_icon` varchar(50) NOT NULL,
  `kontak_link` varchar(255) NOT NULL,
  PRIMARY KEY (`kontak_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Added date 29 Desember 2020
CREATE TABLE IF NOT EXISTS `list_inquiry` (
  `inquiry_id` int(11) NOT NULL AUTO_INCREMENT,
  `inquiry_name` varchar(75) NOT NULL,
  `inquiry_email` varchar(100) NOT NULL,
  `inquiry_message` text NOT NULL,
  `is_response` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`inquiry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;