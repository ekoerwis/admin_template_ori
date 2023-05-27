-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.14-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.1.0.6116
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table admin_template_ci.akta
CREATE TABLE IF NOT EXISTS `akta` (
  `id_akta` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `no_akta` smallint(6) DEFAULT NULL,
  `tgl_akta` date DEFAULT NULL,
  `nama_akta` varchar(255) DEFAULT NULL,
  `nama_customer` varchar(255) DEFAULT NULL,
  `minuta` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id_akta`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table admin_template_ci.akta: ~4 rows (approximately)
/*!40000 ALTER TABLE `akta` DISABLE KEYS */;
INSERT INTO `akta` (`id_akta`, `no_akta`, `tgl_akta`, `nama_akta`, `nama_customer`, `minuta`) VALUES
	(1, 1, '2020-11-12', 'Akta Pendirian', 'PT. Pasir Putih', 'Y'),
	(5, 2, '2020-11-19', 'Akta Fidusia', 'Andiansyah Putra', 'Y'),
	(6, 3, '2020-11-20', 'Akta Perjanjian Sewa', 'Budiawan', 'Y'),
	(7, 4, '2020-10-15', 'Akta Jual Beli', 'Muryatiningsih', 'N');
/*!40000 ALTER TABLE `akta` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.akta_file
CREATE TABLE IF NOT EXISTS `akta_file` (
  `id_akta_file` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_akta` int(10) unsigned DEFAULT NULL,
  `judul_file` varchar(255) DEFAULT NULL,
  `deskripsi_file` text DEFAULT NULL,
  `nama_file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_akta_file`),
  KEY `akta_file_akta` (`id_akta`),
  CONSTRAINT `akta_file_akta` FOREIGN KEY (`id_akta`) REFERENCES `akta` (`id_akta`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='File pendukung ketika akta dibuat';

-- Dumping data for table admin_template_ci.akta_file: ~8 rows (approximately)
/*!40000 ALTER TABLE `akta_file` DISABLE KEYS */;
INSERT INTO `akta_file` (`id_akta_file`, `id_akta`, `judul_file`, `deskripsi_file`, `nama_file`) VALUES
	(2, 1, 'Akta Pendirian', 'Akta Pendirian', 'akta.pdf'),
	(3, 1, 'Dokumen Pendukung', 'Dokumen Pendukung Akta Pendirian', 'Salinan Dokumen Pendukung Akta No. 01.pdf'),
	(4, 5, 'Akta Fidusia', 'Salinan Akta Fidusia', 'Salinan Akta Fidusia.pdf'),
	(5, 5, 'Lampiran Pendukung', 'Lampiran Pendukung Akta Fidusia No 02', 'Lampiran Pendukung Akta No. 02.pdf'),
	(6, 6, 'Salinan Akta Sewa', 'Salinan Akta Sewa Leasing', 'Akta Sewa No.03.pdf'),
	(7, 6, 'Keterangan Pendukung Akta Sewa', 'Keterangan Pendukung Akta Sewa', 'Keterangan Pendukung Akta Sewa.pdf'),
	(8, 7, 'Akta Jual Beli No. 04', 'Salinan Akta Jual Beli No. 04', 'Akta Jual Beli No.04.pdf'),
	(9, 1, 'Kartu Identitas (KTP)', 'Kartu identitas penghadap (KTP)', 'ktp.png');
/*!40000 ALTER TABLE `akta_file` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.akta_penanggung_jawab
CREATE TABLE IF NOT EXISTS `akta_penanggung_jawab` (
  `id_akta` int(11) unsigned NOT NULL,
  `id_penanggung_jawab` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_akta`,`id_penanggung_jawab`) USING BTREE,
  CONSTRAINT `akta_penanggung_jawab` FOREIGN KEY (`id_akta`) REFERENCES `akta` (`id_akta`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table admin_template_ci.akta_penanggung_jawab: ~8 rows (approximately)
/*!40000 ALTER TABLE `akta_penanggung_jawab` DISABLE KEYS */;
INSERT INTO `akta_penanggung_jawab` (`id_akta`, `id_penanggung_jawab`) VALUES
	(1, 8),
	(1, 9),
	(5, 8),
	(5, 10),
	(6, 7),
	(6, 10),
	(7, 7),
	(7, 10);
/*!40000 ALTER TABLE `akta_penanggung_jawab` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.akta_penghadap
CREATE TABLE IF NOT EXISTS `akta_penghadap` (
  `id_akta` int(11) unsigned NOT NULL,
  `id_penghadap` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_akta`,`id_penghadap`) USING BTREE,
  CONSTRAINT `akta_penghadap_akta` FOREIGN KEY (`id_akta`) REFERENCES `akta` (`id_akta`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table admin_template_ci.akta_penghadap: ~9 rows (approximately)
/*!40000 ALTER TABLE `akta_penghadap` DISABLE KEYS */;
INSERT INTO `akta_penghadap` (`id_akta`, `id_penghadap`) VALUES
	(1, 1),
	(1, 2),
	(1, 9),
	(5, 2),
	(5, 9),
	(6, 9),
	(6, 10),
	(7, 2),
	(7, 9);
/*!40000 ALTER TABLE `akta_penghadap` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.customer
CREATE TABLE IF NOT EXISTS `customer` (
  `id_customer` int(11) NOT NULL AUTO_INCREMENT,
  `nama_customer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_customer`) USING BTREE,
  UNIQUE KEY `nama_supplier` (`nama_customer`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table admin_template_ci.customer: ~3 rows (approximately)
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` (`id_customer`, `nama_customer`) VALUES
	(3, 'CV. Usaha Makmur'),
	(2, 'PT. Pasir Putih'),
	(1, 'PT. Sinar Mas');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.mahasiswa
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `id_mahasiswa` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `npm` varchar(255) DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `prodi` varchar(255) DEFAULT NULL,
  `fakultas` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `tgl_input` date DEFAULT NULL,
  `id_user_input` mediumint(8) unsigned DEFAULT NULL,
  `tgl_edit` date DEFAULT NULL,
  `id_user_edit` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_mahasiswa`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table admin_template_ci.mahasiswa: ~14 rows (approximately)
/*!40000 ALTER TABLE `mahasiswa` DISABLE KEYS */;
INSERT INTO `mahasiswa` (`id_mahasiswa`, `nama`, `npm`, `tempat_lahir`, `tgl_lahir`, `prodi`, `fakultas`, `alamat`, `foto`, `tgl_input`, `id_user_input`, `tgl_edit`, `id_user_edit`) VALUES
	(1, 'Wicaksono Catur', '55555', 'Solo', '1998-03-11', 'Sistem Informasi Modern', 'Teknik Informatika', 'Jl. Kencur No. 19, Sukoharjo', 'Wicaksono Catur.png', '2020-03-28', 1, '2020-11-15', 1),
	(4, 'Dinda Ayuninda', '222222', 'Semarang', '1999-10-10', 'Sistem Informasi Modern', 'Teknik Informatika', 'Jl. Pemuda, No. 02, Semarang', 'Dinda Ayuninda.png', '2020-11-15', 1, NULL, NULL),
	(8, 'Sony Haryanto', '22222', 'Surabaya', '1997-02-01', 'Sistem Perangkat Lunak', 'Teknik Informatika', 'Jl. Kenanga No, 137, Surabaya', 'Sony Haryanto.png', '2020-11-15', 1, NULL, NULL),
	(9, 'Budi Kurniawan', '77777', 'Jakarta', '2000-07-05', 'Desain Kreatif', 'Desain Komunikasi Visual', 'Jl. Anggrek No. 09, Jakarta', 'Budi Kurniawan.png', '2020-11-15', 1, NULL, NULL),
	(10, 'Santi Anggraeni', '11111', 'Medan', '2001-02-01', 'Desain Kreatif', 'Desain Komunikasi Visual', 'Jl. Tori No. 19, Medan', 'Santi Anggraeni.png', '2020-11-15', 1, NULL, NULL),
	(11, 'Bima Siregar', '12345', 'Medan', '2002-07-05', 'Desain Kreatif', 'Desain Komunikasi Visual', 'Jl. Delima N0.07 Medan', 'Bima Siregar.png', '2020-11-15', 1, NULL, NULL),
	(12, 'Intan Nurwiyati', '12345', 'Semarang', '2001-03-12', 'Sistem Informasi Modern', 'Teknik Informatika', 'Jl. Imam Bonjol No. 72, Semarang', 'Intan Nurwiyati.png', '2020-11-15', 1, NULL, NULL),
	(13, 'Tendi Anshori', '12345', 'Banyuwangi', '2001-08-06', 'Desain Kreatif', 'Desain Komunikasi Visual', 'Jl. Semarak No. 03, Banyuwangi', 'Tendi Anshori.png', '2020-11-15', 1, NULL, NULL),
	(14, 'Anton Junaedi', '12345', 'Serang', '2000-05-22', 'Desain Kreatif', 'Desain Komunikasi Visual', 'Jl. Sidorang No. 15, Serang', 'Anton Junaedi.png', '2020-11-15', 1, NULL, NULL),
	(15, 'Ahmad Basuki', '55555', 'Surakarta', '2000-03-10', 'Sistem Informasi Modern', 'Teknik Informatika', 'Jl. Bendar No. 77, Surakarta', 'Ahmad Basuki.png', '2020-11-15', 1, NULL, NULL),
	(16, 'Dinda Fitrianti', '12345', 'Jakarta', '2001-03-03', 'Desain Kreatif', 'Desain Komunikasi Visual', 'Jl. P. Diponegoro No. 01, Jakarta', 'Dinda Fitrianti.png', '2020-11-15', 1, NULL, NULL),
	(17, 'Ahmad Fathoni', '234545', 'Jember', '2000-07-04', 'Sistem Informasi Modern', 'Teknik Informatika', 'Jl. Ahmad Dahlan No. 03, Jember', 'Ahmad Fathoni.png', '2020-11-15', 1, NULL, NULL),
	(18, 'Boni Simanjuntak', '55555', 'Semarang', '2000-07-05', 'Desain Kreatif', 'Desain Komunikasi Visual', 'Jl. Bistamar No. 33, Semarang', 'Boni Simanjuntak.png', '2020-11-15', 1, NULL, NULL),
	(19, 'Tanti Irawati', '22233', 'Sukoharjo', '2000-09-05', 'Desain Kreatif', 'Desain Komunikasi Visual', 'Jl. Bersinar No. 77, Sukoharjo', 'Tanti Irawati.png', '2020-11-15', 1, NULL, NULL),
	(20, 'Dendi Suandi', '33554', 'Jakarta', '2000-11-25', 'Desain Kreatif', 'Desain Komunikasi Visual', 'Jl. Semanggi No. 10, Jakarta', 'Dendi Suandi.png', '2020-11-15', 1, NULL, NULL);
/*!40000 ALTER TABLE `mahasiswa` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.menu
CREATE TABLE IF NOT EXISTS `menu` (
  `id_menu` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(50) NOT NULL,
  `class` varchar(50) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
  `id_module` smallint(5) unsigned DEFAULT NULL,
  `id_parent` smallint(5) unsigned DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `new` tinyint(1) NOT NULL DEFAULT 0,
  `urut` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_menu`) USING BTREE,
  KEY `menu_module` (`id_module`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT COMMENT='Tabel menu aplikasi';

-- Dumping data for table admin_template_ci.menu: ~18 rows (approximately)
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` (`id_menu`, `nama_menu`, `class`, `url`, `id_module`, `id_parent`, `aktif`, `new`, `urut`) VALUES
	(1, 'User', 'fas fa-users', 'builtin/user', 5, 8, 1, 0, 1),
	(2, 'Assign Role', 'fas fa-link', '#', 0, 8, 1, 0, 5),
	(3, 'User Role', 'fas fa-user-tag', 'builtin/user-role', 7, 2, 1, 0, 1),
	(4, 'Module', 'fas fa-network-wired', 'builtin/module', 2, 8, 1, 0, 2),
	(5, 'Module Role', 'fas fa-desktop', 'builtin/module-role', 3, 2, 1, 0, 2),
	(6, 'Menu', 'fas fa-clone', 'builtin/menu', 1, 8, 1, 0, 4),
	(7, 'Menu Role', 'fas fa-folder-minus', 'builtin/menu-role', 8, 2, 1, 0, 3),
	(8, 'Website', 'fas fa-globe', '#', 1, 0, 1, 0, 1),
	(17, 'Role', 'fas fa-briefcase', 'builtin/role', 4, 8, 1, 0, 3),
	(18, 'Setting Website', 'fas fa-cog', 'builtin/setting-web', 16, 8, 1, 0, 6),
	(20, 'Layout Setting', 'fas fa-brush', 'builtin/setting', 15, 8, 1, 0, 7),
	(24, 'Data Tables Ajax', 'fas fa-address-card', 'data-tables-ajax', 27, NULL, 1, 0, 4),
	(25, 'Forms - CRUD', 'fab fa-wpforms', 'input-dinamis', 0, NULL, 1, 0, 2),
	(26, 'Input Dinamis', 'fab fa-wpforms', 'input-dinamis/add', 22, 25, 1, 0, 1),
	(27, 'Multiple File Upload', 'fas fa-file-upload', 'multiple-fileupload/edit?id=1', 26, 25, 1, 0, 3),
	(28, 'Options Dinamis', 'fas fa-caret-square-down', 'options-dinamis/edit?id=1', 21, 25, 1, 0, 2),
	(29, 'Image Upload', 'fas fa-image', 'image-upload/edit?id=1', 9, 25, 1, 0, 4),
	(30, 'Data Tables', 'fas fa-table', 'data-tables', 28, NULL, 1, 0, 3),
	(31, 'Produk', 'fas fa-shopping-bag', 'produk', 29, NULL, 1, 0, 5);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.menu_role
CREATE TABLE IF NOT EXISTS `menu_role` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_menu` smallint(5) unsigned NOT NULL,
  `id_role` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_role_module` (`id_menu`),
  KEY `module_role_role` (`id_role`),
  CONSTRAINT `menu_role_menu` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `menu_role_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT COMMENT='Tabel hak akses dari menu aplikasi';

-- Dumping data for table admin_template_ci.menu_role: ~31 rows (approximately)
/*!40000 ALTER TABLE `menu_role` DISABLE KEYS */;
INSERT INTO `menu_role` (`id`, `id_menu`, `id_role`) VALUES
	(1, 5, 1),
	(6, 2, 1),
	(7, 3, 1),
	(8, 4, 1),
	(9, 6, 1),
	(19, 1, 1),
	(20, 1, 2),
	(23, 8, 1),
	(25, 7, 1),
	(26, 7, 2),
	(27, 2, 2),
	(28, 8, 2),
	(29, 17, 1),
	(30, 18, 1),
	(32, 20, 1),
	(39, 24, 1),
	(41, 24, 2),
	(42, 24, 3),
	(43, 25, 1),
	(44, 25, 2),
	(45, 25, 3),
	(46, 26, 1),
	(47, 26, 2),
	(48, 27, 1),
	(49, 27, 2),
	(50, 28, 1),
	(51, 28, 2),
	(52, 29, 1),
	(54, 29, 2),
	(55, 30, 1),
	(56, 30, 2),
	(57, 30, 3),
	(58, 31, 1),
	(59, 31, 2);
/*!40000 ALTER TABLE `menu_role` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.module
CREATE TABLE IF NOT EXISTS `module` (
  `id_module` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nama_module` varchar(50) DEFAULT NULL,
  `judul_module` varchar(50) DEFAULT NULL,
  `id_module_status` tinyint(1) DEFAULT NULL,
  `login` tinyint(1) DEFAULT NULL,
  `deskripsi` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_module`),
  UNIQUE KEY `module_nama` (`nama_module`),
  KEY `module_module_status` (`id_module_status`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='Tabel modul aplikasi';

-- Dumping data for table admin_template_ci.module: ~20 rows (approximately)
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` (`id_module`, `nama_module`, `judul_module`, `id_module_status`, `login`, `deskripsi`) VALUES
	(1, 'builtin/menu', 'Menu Manager', 1, 1, 'Administrasi Menu'),
	(2, 'builtin/module', 'Module Manager', 1, 1, 'Pengaturan Module'),
	(3, 'builtin/module-role', 'Assign Role ke Module', 1, 1, 'Assign Role ke Module'),
	(4, 'builtin/role', 'Role Manager', 1, 1, 'Pengaturan Role'),
	(5, 'builtin/user', 'User Manager', 1, 1, 'Pengaturan user'),
	(6, 'login', 'Login', 1, 0, 'Login ke akun Anda'),
	(7, 'builtin/user-role', 'Assign Role ke User', 1, 1, 'Assign role ke user'),
	(8, 'builtin/menu-role', 'Menu - Role', 1, 1, 'Assign role ke menu'),
	(9, 'image-upload', 'Image Upload', 1, 1, 'Image Upload'),
	(15, 'builtin/setting', 'Setting Layout', 1, NULL, 'Web Setting'),
	(16, 'builtin/setting-web', 'Setting Web', 1, NULL, 'Pengaturan website seperti nama web, logo, dll'),
	(21, 'options-dinamis', 'Options Dinamis', 1, NULL, 'Options Dinamis'),
	(22, 'input-dinamis', 'Input Dinamis', 1, NULL, 'Input Dinamis'),
	(23, 'penanggungjawab', 'Penanggung Jawab', 1, NULL, 'Penanggung Jawab'),
	(24, 'kleper', 'Kleper', 1, NULL, 'Kleper'),
	(25, 'home', 'Home', 1, NULL, 'Home'),
	(26, 'multiple-fileupload', 'Multiple File Upload', 1, NULL, 'Multiple File Upload'),
	(27, 'data-tables-ajax', 'Data Tables Ajax', 1, NULL, 'Data Tables Ajax'),
	(28, 'data-tables', 'Data Tables', 1, NULL, 'Data Tables'),
	(29, 'produk', 'Produk', 1, NULL, 'Produk');
/*!40000 ALTER TABLE `module` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.module_role
CREATE TABLE IF NOT EXISTS `module_role` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_module` smallint(5) unsigned NOT NULL,
  `id_role` smallint(5) unsigned NOT NULL,
  `read_data` varchar(255) NOT NULL DEFAULT '',
  `create_data` varchar(255) NOT NULL DEFAULT '',
  `update_data` varchar(255) NOT NULL DEFAULT '',
  `delete_data` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `module_role_module` (`id_module`),
  KEY `module_role_role` (`id_role`),
  CONSTRAINT `module_role_module` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `module_role_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='Tabel hak akses module aplikasi, module aplikasi boleh diakses oleh user yang mempunyai role apa saja';

-- Dumping data for table admin_template_ci.module_role: ~34 rows (approximately)
/*!40000 ALTER TABLE `module_role` DISABLE KEYS */;
INSERT INTO `module_role` (`id`, `id_module`, `id_role`, `read_data`, `create_data`, `update_data`, `delete_data`) VALUES
	(1, 3, 1, 'all', 'yes', 'all', 'all'),
	(2, 8, 1, 'all', 'yes', 'all', 'all'),
	(3, 4, 1, 'all', 'yes', 'all', 'all'),
	(5, 2, 1, 'all', 'yes', 'all', 'all'),
	(6, 1, 1, 'all', 'yes', 'all', 'all'),
	(7, 7, 1, 'all', 'yes', 'all', 'all'),
	(16, 9, 1, 'all', 'yes', 'all', 'all'),
	(17, 9, 2, 'own', 'yes', 'no', 'no'),
	(24, 5, 1, 'all', 'yes', 'all', 'all'),
	(25, 5, 2, 'own', 'no', 'own', 'no'),
	(26, 15, 1, 'all', 'yes', 'all', 'all'),
	(27, 15, 2, 'own', 'no', 'own', 'own'),
	(28, 16, 1, 'all', 'yes', 'all', 'all'),
	(30, 21, 1, 'all', 'yes', 'all', 'all'),
	(31, 21, 2, 'all', 'yes', 'all', 'all'),
	(32, 22, 1, 'all', 'yes', 'all', 'all'),
	(33, 22, 2, 'all', 'yes', 'all', 'all'),
	(34, 23, 1, 'all', 'yes', 'all', 'all'),
	(35, 23, 2, 'all', 'yes', 'all', 'all'),
	(36, 24, 1, 'all', 'yes', 'all', 'all'),
	(37, 24, 2, 'all', 'yes', 'all', 'all'),
	(38, 25, 1, 'all', 'yes', 'all', 'all'),
	(39, 25, 2, 'all', 'yes', 'all', 'all'),
	(40, 25, 3, 'all', 'yes', 'all', 'all'),
	(41, 26, 1, 'all', 'yes', 'all', 'all'),
	(42, 26, 2, 'all', 'yes', 'all', 'all'),
	(43, 27, 1, 'all', 'yes', 'all', 'all'),
	(44, 27, 2, 'all', 'yes', 'all', 'all'),
	(45, 27, 3, 'all', 'yes', 'all', 'all'),
	(46, 28, 1, 'all', 'yes', 'all', 'all'),
	(47, 28, 2, 'all', 'yes', 'all', 'all'),
	(48, 28, 3, 'all', 'yes', 'all', 'all'),
	(49, 29, 1, 'all', 'yes', 'all', 'all'),
	(50, 29, 2, 'all', 'yes', 'all', 'all');
/*!40000 ALTER TABLE `module_role` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.module_status
CREATE TABLE IF NOT EXISTS `module_status` (
  `id_module_status` tinyint(1) NOT NULL AUTO_INCREMENT,
  `nama_status` varchar(50) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_module_status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='Tabel status modul, seperti: aktif, non aktif, dalam perbaikan';

-- Dumping data for table admin_template_ci.module_status: ~3 rows (approximately)
/*!40000 ALTER TABLE `module_status` DISABLE KEYS */;
INSERT INTO `module_status` (`id_module_status`, `nama_status`, `keterangan`) VALUES
	(1, 'Aktif', NULL),
	(2, 'Dalam Perbaikan', 'Hanya role developer yang dapat mengakses module dengan status ini'),
	(3, 'Non Aktif', NULL);
/*!40000 ALTER TABLE `module_status` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.penanggung_jawab
CREATE TABLE IF NOT EXISTS `penanggung_jawab` (
  `id_penanggung_jawab` int(11) NOT NULL AUTO_INCREMENT,
  `nama_penanggung_jawab` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_penanggung_jawab`) USING BTREE,
  UNIQUE KEY `nama_penanggung_jawab` (`nama_penanggung_jawab`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table admin_template_ci.penanggung_jawab: ~4 rows (approximately)
/*!40000 ALTER TABLE `penanggung_jawab` DISABLE KEYS */;
INSERT INTO `penanggung_jawab` (`id_penanggung_jawab`, `nama_penanggung_jawab`) VALUES
	(8, 'Dibyo'),
	(9, 'Peni'),
	(7, 'Putra'),
	(10, 'Suryo');
/*!40000 ALTER TABLE `penanggung_jawab` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.penghadap
CREATE TABLE IF NOT EXISTS `penghadap` (
  `id_penghadap` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `jenis_kelamin` enum('L','P') NOT NULL DEFAULT 'L',
  `gelar_depan` varchar(50) DEFAULT NULL,
  `nama_penghadap` varchar(255) DEFAULT NULL,
  `gelar_belakang` varchar(50) DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  PRIMARY KEY (`id_penghadap`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table admin_template_ci.penghadap: ~4 rows (approximately)
/*!40000 ALTER TABLE `penghadap` DISABLE KEYS */;
INSERT INTO `penghadap` (`id_penghadap`, `jenis_kelamin`, `gelar_depan`, `nama_penghadap`, `gelar_belakang`, `tempat_lahir`, `tanggal_lahir`, `alamat`) VALUES
	(1, 'L', 'Drs.', 'Handoyo', 'S.H.', NULL, NULL, NULL),
	(2, 'P', '', 'Ningsih', '', NULL, NULL, NULL),
	(9, 'P', '', 'Sumiyati', '', NULL, NULL, NULL),
	(10, 'L', '', 'Binardi', 'M.M.', NULL, NULL, NULL);
/*!40000 ALTER TABLE `penghadap` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.produk
CREATE TABLE IF NOT EXISTS `produk` (
  `id_produk` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(255) DEFAULT NULL,
  `deskripsi_produk` text DEFAULT NULL,
  PRIMARY KEY (`id_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table admin_template_ci.produk: ~3 rows (approximately)
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;
INSERT INTO `produk` (`id_produk`, `nama_produk`, `deskripsi_produk`) VALUES
	(1, 'Bluetooth Multi-Device Keyboard K480', 'Keyboard meja wiresless untuk komputer, tablet, dan smartphone'),
	(2, 'USB Unifying Receiver', 'Receiver USB yang bisa digunakan untuk sebuah mouse atau keyboard unifying'),
	(3, 'M590 Multi-Device Silent', 'Mouse wiresless hening untuk power user');
/*!40000 ALTER TABLE `produk` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.role
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nama_role` varchar(50) NOT NULL,
  `judul_role` varchar(50) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `id_module` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `role_nama` (`nama_role`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='Tabel yang berisi daftar role, role ini mengatur bagaimana user mengakses module, role ini nantinya diassign ke user';

-- Dumping data for table admin_template_ci.role: ~3 rows (approximately)
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` (`id_role`, `nama_role`, `judul_role`, `keterangan`, `id_module`) VALUES
	(1, 'admin', 'Administrator', 'Administrator', 5),
	(2, 'user', 'User', 'Pengguna umum', 5),
	(3, 'webdev', 'Web Developer', 'Pengembang aplikasi', 5);
/*!40000 ALTER TABLE `role` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.role_detail
CREATE TABLE IF NOT EXISTS `role_detail` (
  `id_role_detail` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nama_role_detail` varchar(255) DEFAULT NULL,
  `judul_role_detail` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_role_detail`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table admin_template_ci.role_detail: ~3 rows (approximately)
/*!40000 ALTER TABLE `role_detail` DISABLE KEYS */;
INSERT INTO `role_detail` (`id_role_detail`, `nama_role_detail`, `judul_role_detail`) VALUES
	(1, 'all', 'Boleh Akses Semua Data'),
	(2, 'no', 'Tidak Boleh Akses Semua Data'),
	(3, 'own', 'Hanya Data Miliknya Sendiri');
/*!40000 ALTER TABLE `role_detail` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.setting_app_tampilan
CREATE TABLE IF NOT EXISTS `setting_app_tampilan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `param` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=236 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Dumping data for table admin_template_ci.setting_app_tampilan: 5 rows
/*!40000 ALTER TABLE `setting_app_tampilan` DISABLE KEYS */;
INSERT INTO `setting_app_tampilan` (`id`, `param`, `value`) VALUES
	(1, 'color_scheme', 'purple'),
	(2, 'sidebar_color', 'dark'),
	(3, 'logo_background_color', 'default'),
	(4, 'font_family', 'poppins'),
	(5, 'font_size', '15');
/*!40000 ALTER TABLE `setting_app_tampilan` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.setting_app_user
CREATE TABLE IF NOT EXISTS `setting_app_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `param` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Dumping data for table admin_template_ci.setting_app_user: 1 rows
/*!40000 ALTER TABLE `setting_app_user` DISABLE KEYS */;
INSERT INTO `setting_app_user` (`id`, `id_user`, `param`) VALUES
	(7, 2, '{"color_scheme":"blue-dark","sidebar_color":"dark","logo_background_color":"default","font_family":"poppins","font_size":"15"}');
/*!40000 ALTER TABLE `setting_app_user` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.setting_web
CREATE TABLE IF NOT EXISTS `setting_web` (
  `id_setting` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `param` varchar(255) DEFAULT NULL,
  `value` tinytext DEFAULT NULL,
  PRIMARY KEY (`id_setting`)
) ENGINE=InnoDB AUTO_INCREMENT=210 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table admin_template_ci.setting_web: ~9 rows (approximately)
/*!40000 ALTER TABLE `setting_web` DISABLE KEYS */;
INSERT INTO `setting_web` (`id_setting`, `param`, `value`) VALUES
	(201, 'logo_login', 'logo_login.png'),
	(202, 'logo_app', 'logo_aplikasi_.png'),
	(203, 'footer_login', '© {{YEAR}} <a href="https://jagowebdev.com" target="_blank">Jagowebdev.com</a>'),
	(204, 'btn_login', 'btn-danger'),
	(205, 'footer_app', '© {{YEAR}} <a href="https://jagowebdev.com" target="_blank">www.Jagowebdev.com</a>'),
	(206, 'background_logo', 'transparent'),
	(207, 'judul_web', 'Admin Template Jagowebdev'),
	(208, 'deskripsi_web', 'Template administrasi lengkap dengan fitur penting dalam pengembangan aplikasi seperti pengatuan web, layout, dll'),
	(209, 'favicon', 'favicon.png');
/*!40000 ALTER TABLE `setting_web` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.user
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `aktif` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `created` datetime NOT NULL DEFAULT curdate(),
  `id_role` smallint(6) unsigned NOT NULL DEFAULT 0,
  `avatar` varchar(255) NOT NULL,
  PRIMARY KEY (`id_user`),
  KEY `user_role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT COMMENT='Tabel user untuk login';

-- Dumping data for table admin_template_ci.user: ~4 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id_user`, `email`, `username`, `nama`, `password`, `aktif`, `created`, `id_role`, `avatar`) VALUES
	(1, 'prawoto.hadi@gmail.com', 'admin', 'Agus Prawoto Hadi', '$2y$10$luCbrAdrDWgxckIYfjQymeeN.6WuV656.MNUth8AxF94H8Mb.enmK', 1, '2018-09-20 16:04:35', 1, ''),
	(2, 'user.administrasi@gmail.com', 'user', 'User Administrasi', '$2y$10$n2hGjOQUW2EYRskCF2rgaOJR5w0dR3h8hqWln/5OXkAW2XT6vpujO', 0, '0000-00-00 00:00:00', 2, 'Ahmad Basuki.png'),
	(4, 'superuser@gmail.com', 'superuser', 'Super User', '$2y$10$zhOI1vNLoGnnu/1e.9R/zusTvOz2WHiZ5plTVID860pRQAc8Xb.8G', 1, '0000-00-00 00:00:00', 1, ''),
	(5, 'mail@email.com', 'mail', 'Mail', '$2y$10$DKT/rqiPTHOHEIwBG85MlulM2yyiQGNmcmNmtT8//aMdzTE0kE6PS', 0, '2020-11-20 00:00:00', 1, 'Bima Siregar.png');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.user_cookie
CREATE TABLE IF NOT EXISTS `user_cookie` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL DEFAULT 0,
  `selector` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cookie_auth` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT COMMENT='Tabel cookie untuk fitur remember me';

-- Dumping data for table admin_template_ci.user_cookie: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_cookie` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_cookie` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.user_login_activity
CREATE TABLE IF NOT EXISTS `user_login_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_activity` tinyint(4) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2106 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Dumping data for table admin_template_ci.user_login_activity: ~32 rows (approximately)
/*!40000 ALTER TABLE `user_login_activity` DISABLE KEYS */;
INSERT INTO `user_login_activity` (`id`, `id_user`, `id_activity`, `time`) VALUES
	(2073, 1, 1, '2020-11-07 14:40:21'),
	(2074, 1, 1, '2020-11-07 15:09:00'),
	(2075, 1, 1, '2020-11-07 15:14:26'),
	(2076, 1, 1, '2020-11-14 08:01:01'),
	(2077, 1, 1, '2020-11-14 12:37:59'),
	(2078, 1, 1, '2020-11-14 20:11:39'),
	(2079, 1, 1, '2020-11-15 13:25:08'),
	(2080, 1, 1, '2020-11-18 05:26:28'),
	(2081, 1, 1, '2020-11-18 20:12:18'),
	(2082, 1, 1, '2020-11-19 04:53:49'),
	(2083, 1, 1, '2020-11-19 05:38:39'),
	(2084, 1, 1, '2020-11-19 20:42:29'),
	(2085, 1, 1, '2020-11-20 04:48:03'),
	(2086, 1, 1, '2020-11-20 05:51:38'),
	(2087, 1, 1, '2020-11-20 05:55:43'),
	(2088, 2, 1, '2020-11-20 05:57:06'),
	(2089, 2, 1, '2020-11-20 06:01:37'),
	(2090, 2, 1, '2020-11-20 19:56:04'),
	(2091, 2, 1, '2020-11-21 04:38:47'),
	(2092, 1, 1, '2020-11-21 04:41:34'),
	(2093, 1, 1, '2020-11-21 04:43:27'),
	(2094, 1, 1, '2020-11-21 12:30:24'),
	(2095, 1, 1, '2020-11-21 12:31:59'),
	(2096, 1, 1, '2020-11-21 16:20:11'),
	(2097, 1, 1, '2020-12-19 18:23:44'),
	(2098, 1, 1, '2020-12-19 18:24:04'),
	(2099, 1, 1, '2020-12-19 18:32:23'),
	(2100, 2, 1, '2020-12-19 20:13:11'),
	(2101, 1, 1, '2020-12-19 20:15:17'),
	(2102, 1, 1, '2021-01-04 15:40:00'),
	(2103, 1, 1, '2021-01-04 15:54:54'),
	(2104, 1, 1, '2021-01-04 16:07:36'),
	(2105, 1, 1, '2021-01-04 17:16:54');
/*!40000 ALTER TABLE `user_login_activity` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.user_login_activity_ref
CREATE TABLE IF NOT EXISTS `user_login_activity_ref` (
  `id_activity` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id_activity`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table admin_template_ci.user_login_activity_ref: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_login_activity_ref` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_login_activity_ref` ENABLE KEYS */;

-- Dumping structure for table admin_template_ci.user_role
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` smallint(5) unsigned NOT NULL,
  `id_role` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_role_module` (`id_user`),
  KEY `module_role_role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT COMMENT='Tabel yang berisi role yang dimili oleh masing masing user';

-- Dumping data for table admin_template_ci.user_role: ~4 rows (approximately)
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` (`id`, `id_user`, `id_role`) VALUES
	(1, 1, 1),
	(11, 2, 2),
	(17, 4, 1);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
