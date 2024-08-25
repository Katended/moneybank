-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for moneybankonline
DROP DATABASE IF EXISTS `moneybankonline`;
CREATE DATABASE IF NOT EXISTS `moneybankonline` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;
USE `moneybankonline`;

-- Dumping structure for table moneybankonline.accountcodes
DROP TABLE IF EXISTS `accountcodes`;
CREATE TABLE IF NOT EXISTS `accountcodes` (
  `accountcodes_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `accountcode` char(45) NOT NULL DEFAULT '',
  `accountcodes_costant` char(45) NOT NULL DEFAULT '',
  `accountcodes_datecreated` date NOT NULL DEFAULT '0000-00-00',
  `accountcodes_dateupdated` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`accountcodes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.accountsconfig
DROP TABLE IF EXISTS `accountsconfig`;
CREATE TABLE IF NOT EXISTS `accountsconfig` (
  `accountsconfig_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `accountsconfig_key` char(45) NOT NULL DEFAULT '',
  `accountsconfig_value` char(45) DEFAULT NULL,
  `accountsconfig_description` char(100) DEFAULT NULL,
  PRIMARY KEY (`accountsconfig_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.accounttypes
DROP TABLE IF EXISTS `accounttypes`;
CREATE TABLE IF NOT EXISTS `accounttypes` (
  `accounttypes_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `accounttypes_description` char(255) NOT NULL DEFAULT '',
  `accounttypes_name` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`accounttypes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.alerts
DROP TABLE IF EXISTS `alerts`;
CREATE TABLE IF NOT EXISTS `alerts` (
  `alerts_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alerts_subject` char(45) NOT NULL DEFAULT '',
  `alerts_description` char(45) NOT NULL DEFAULT '',
  `alerts_datecreated` date NOT NULL DEFAULT '0000-00-00',
  `alerts_directedto` char(45) NOT NULL DEFAULT '',
  `alerts_dateupdated` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`alerts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='InnoDB free: 15360 kB';

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.appusers
DROP TABLE IF EXISTS `appusers`;
CREATE TABLE IF NOT EXISTS `appusers` (
  `SecID` int(11) NOT NULL AUTO_INCREMENT,
  `User` char(45) DEFAULT NULL,
  `Description` char(45) DEFAULT NULL,
  PRIMARY KEY (`SecID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.areacode
DROP TABLE IF EXISTS `areacode`;
CREATE TABLE IF NOT EXISTS `areacode` (
  `areacode_code` bigint(20) unsigned NOT NULL,
  `areacode_name` char(50) NOT NULL,
  PRIMARY KEY (`areacode_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.bankaccounts
DROP TABLE IF EXISTS `bankaccounts`;
CREATE TABLE IF NOT EXISTS `bankaccounts` (
  `bankaccounts_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bankaccounts_accno` char(45) NOT NULL DEFAULT '',
  `bankbranches_id` int(10) unsigned NOT NULL DEFAULT 0,
  `chartofaccounts_accountcode` char(45) DEFAULT NULL,
  PRIMARY KEY (`bankaccounts_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.bankbranches
DROP TABLE IF EXISTS `bankbranches`;
CREATE TABLE IF NOT EXISTS `bankbranches` (
  `bankbranches_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `banks_id` char(45) NOT NULL DEFAULT '',
  `bankbranches_name` char(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`bankbranches_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.banks
DROP TABLE IF EXISTS `banks`;
CREATE TABLE IF NOT EXISTS `banks` (
  `banks_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `banks_name` char(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`banks_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.banktransactions
DROP TABLE IF EXISTS `banktransactions`;
CREATE TABLE IF NOT EXISTS `banktransactions` (
  `banktransactions_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bankaccounts_id` char(45) NOT NULL DEFAULT '',
  `tcode` char(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`banktransactions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.branchcharges
DROP TABLE IF EXISTS `branchcharges`;
CREATE TABLE IF NOT EXISTS `branchcharges` (
  `branchcharges_id` int(10) NOT NULL AUTO_INCREMENT,
  `branch_code` char(50) DEFAULT NULL,
  `charges_code` char(50) DEFAULT NULL,
  PRIMARY KEY (`branchcharges_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.bussinesssector
DROP TABLE IF EXISTS `bussinesssector`;
CREATE TABLE IF NOT EXISTS `bussinesssector` (
  `bussinesssector_code` char(50) NOT NULL,
  `bussinesssector_name` char(240) NOT NULL,
  PRIMARY KEY (`bussinesssector_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.cashaccounts
DROP TABLE IF EXISTS `cashaccounts`;
CREATE TABLE IF NOT EXISTS `cashaccounts` (
  `cashaccounts_id` tinyint(45) NOT NULL AUTO_INCREMENT,
  `cashaccounts_name` char(50) DEFAULT NULL,
  `chartofaccounts_accountcode` char(45) NOT NULL DEFAULT '',
  `currencies_id` smallint(4) NOT NULL DEFAULT 0,
  `branch_code` char(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cashaccounts_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.cashitems
DROP TABLE IF EXISTS `cashitems`;
CREATE TABLE IF NOT EXISTS `cashitems` (
  `cashitems_id` int(10) NOT NULL AUTO_INCREMENT,
  `cashitems_name` char(200) NOT NULL,
  `chartofaccounts_accountcode` char(20) NOT NULL,
  PRIMARY KEY (`cashitems_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.category1
DROP TABLE IF EXISTS `category1`;
CREATE TABLE IF NOT EXISTS `category1` (
  `category1_id` int(10) NOT NULL AUTO_INCREMENT,
  `category1_name` char(50) DEFAULT NULL,
  `category1_code` char(5) DEFAULT NULL,
  PRIMARY KEY (`category1_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.category2
DROP TABLE IF EXISTS `category2`;
CREATE TABLE IF NOT EXISTS `category2` (
  `category2_id` tinyint(10) NOT NULL AUTO_INCREMENT,
  `category2_name` char(50) DEFAULT NULL,
  `category2_code` char(5) DEFAULT NULL,
  PRIMARY KEY (`category2_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.category3
DROP TABLE IF EXISTS `category3`;
CREATE TABLE IF NOT EXISTS `category3` (
  `category3_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `category3_code` char(5) DEFAULT NULL,
  `category3_name` char(50) DEFAULT NULL,
  PRIMARY KEY (`category3_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.charges
DROP TABLE IF EXISTS `charges`;
CREATE TABLE IF NOT EXISTS `charges` (
  `charges_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `charges_code` char(50) DEFAULT NULL,
  `charges_name_en` char(100) DEFAULT NULL,
  `charges_name_fr` char(100) DEFAULT NULL,
  `charges_name_sp` char(100) DEFAULT NULL,
  `charges_name_sa` char(100) DEFAULT NULL,
  PRIMARY KEY (`charges_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.chargesrates
DROP TABLE IF EXISTS `chargesrates`;
CREATE TABLE IF NOT EXISTS `chargesrates` (
  `chargesrates_id` int(10) NOT NULL AUTO_INCREMENT,
  `chargesrates_from` decimal(15,5) DEFAULT 0.00000,
  `chargesrates_to` decimal(15,5) DEFAULT 0.00000,
  `chargesrates_per` decimal(15,5) DEFAULT 0.00000,
  `chargesrates_amount` decimal(15,5) DEFAULT 0.00000,
  `branch_code` char(50) DEFAULT NULL,
  `chargesrates_datecreated` date DEFAULT NULL,
  `chargesrates_activated` char(1) DEFAULT NULL,
  `chargesrates_vat` decimal(10,0) DEFAULT NULL,
  `chargesrates_fixed` char(1) DEFAULT NULL,
  `charges_id` tinyint(4) DEFAULT NULL,
  `licence_build` char(50) DEFAULT NULL,
  `chargesrates_stage` char(5) DEFAULT NULL,
  PRIMARY KEY (`chargesrates_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.chartofaccounts
DROP TABLE IF EXISTS `chartofaccounts`;
CREATE TABLE IF NOT EXISTS `chartofaccounts` (
  `chartofaccounts_accountcode` char(20) NOT NULL DEFAULT '0',
  `chartofaccounts_name` char(254) NOT NULL DEFAULT '',
  `chartofaccounts_level` int(10) unsigned NOT NULL DEFAULT 0,
  `chartofaccounts_parent` char(20) NOT NULL DEFAULT '0' COMMENT 'parent',
  `chartofaccounts_header` char(1) NOT NULL DEFAULT '0',
  `chartofaccounts_type` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `chartofaccounts_description` char(45) NOT NULL DEFAULT '',
  `chartofaccounts_groupcode` char(50) NOT NULL DEFAULT '',
  `chartofaccounts_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chartofaccounts_bitem` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `chartofaccounts_tgroup` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `currencies_id` int(10) NOT NULL DEFAULT 0,
  `chartofaccounts_revalue` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`chartofaccounts_id`),
  KEY `chartofaccounts_accountcode` (`chartofaccounts_accountcode`)
) ENGINE=InnoDB AUTO_INCREMENT=186 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.cheqs
DROP TABLE IF EXISTS `cheqs`;
CREATE TABLE IF NOT EXISTS `cheqs` (
  `cheqs_no` char(45) NOT NULL DEFAULT '',
  `bankaccounts_accno` char(45) NOT NULL DEFAULT '',
  `bankbranches_id` tinyint(3) unsigned DEFAULT NULL,
  `cheqs_status` char(4) NOT NULL DEFAULT 'Q',
  `cheqs_datecleared` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cheqs_amount` decimal(50,5) NOT NULL DEFAULT 0.00000,
  `cheqs_datecreated` timestamp NULL DEFAULT current_timestamp(),
  `cheqs_type` tinyint(3) unsigned DEFAULT 0,
  `transactioncode` char(50) NOT NULL DEFAULT '',
  `client_idno` char(12) DEFAULT NULL,
  PRIMARY KEY (`cheqs_no`,`bankaccounts_accno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.clientdocuments
DROP TABLE IF EXISTS `clientdocuments`;
CREATE TABLE IF NOT EXISTS `clientdocuments` (
  `clientdocuments_id` int(10) NOT NULL AUTO_INCREMENT,
  `documenttypes_id` int(10) DEFAULT NULL,
  `client_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`clientdocuments_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.clientlanguages
DROP TABLE IF EXISTS `clientlanguages`;
CREATE TABLE IF NOT EXISTS `clientlanguages` (
  `clientlanguages_id` int(10) NOT NULL AUTO_INCREMENT,
  `clientlanguages_name` char(50) DEFAULT NULL,
  PRIMARY KEY (`clientlanguages_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.clients
DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `client_idno` char(50) NOT NULL,
  `client_type` char(1) NOT NULL,
  `branch_code` char(50) NOT NULL,
  `client_regdate` date DEFAULT NULL,
  `client_surname` char(100) DEFAULT '''''',
  `client_firstname` char(100) DEFAULT '''''',
  `client_middlename` char(100) DEFAULT '''''',
  `client_postad` char(100) DEFAULT NULL,
  `client_gender` char(1) DEFAULT NULL,
  `client_city` char(100) DEFAULT NULL,
  `client_addressphysical` char(100) DEFAULT NULL,
  `areacode_code` char(100) DEFAULT NULL,
  `client_maritalstate` char(2) DEFAULT 'U',
  `client_tel1` char(100) DEFAULT NULL,
  `client_tel2` char(100) DEFAULT NULL,
  `clientcode` char(12) DEFAULT NULL,
  `client_emailad` char(100) DEFAULT NULL,
  `client_enddate` date DEFAULT NULL,
  `costcenters_code` char(50) DEFAULT NULL,
  `client_cat1` tinyint(4) DEFAULT 0,
  `client_cat2` tinyint(4) DEFAULT 0,
  `bussinesssector_code` char(50) DEFAULT '0',
  `client_regstatus` char(4) DEFAULT '0',
  `client_kinname` varchar(250) DEFAULT '0',
  `client_occupation` char(100) DEFAULT '0',
  `client_bday` date DEFAULT NULL,
  `client_bussname` varchar(50) DEFAULT NULL,
  `user_accesscode` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`client_idno`,`client_type`,`branch_code`),
  KEY `client_surname_client_firstname_client_middlename` (`client_surname`,`client_firstname`,`client_middlename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.clientsave
DROP TABLE IF EXISTS `clientsave`;
CREATE TABLE IF NOT EXISTS `clientsave` (
  `client_idno` char(12) NOT NULL,
  `savaccounts_account` char(12) NOT NULL,
  `product_prodid` char(5) NOT NULL DEFAULT '',
  `members_idno` char(12) DEFAULT NULL,
  `clientsave_amount` decimal(15,5) NOT NULL,
  `clientsave_freq` char(5) DEFAULT NULL,
  `lproduct_prodid` char(5) DEFAULT NULL,
  `last_updatedate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`client_idno`,`savaccounts_account`,`product_prodid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.closedperiod
DROP TABLE IF EXISTS `closedperiod`;
CREATE TABLE IF NOT EXISTS `closedperiod` (
  `closedperiod_ends` date NOT NULL,
  `closedperiod_starts` date NOT NULL,
  `closedperiod_tablename` char(50) NOT NULL,
  `period` char(4) DEFAULT NULL,
  `branch_code` char(10) DEFAULT NULL,
  `closedperiod_datecreated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.collateral
DROP TABLE IF EXISTS `collateral`;
CREATE TABLE IF NOT EXISTS `collateral` (
  `collateral_id` int(11) NOT NULL AUTO_INCREMENT,
  `collateral_description` char(100) DEFAULT NULL,
  `loan_number` char(10) DEFAULT NULL,
  `collateral_value` decimal(15,5) DEFAULT NULL,
  PRIMARY KEY (`collateral_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.configuration
DROP TABLE IF EXISTS `configuration`;
CREATE TABLE IF NOT EXISTS `configuration` (
  `configuration_id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration_title` char(64) NOT NULL DEFAULT '',
  `configuration_key` char(64) NOT NULL DEFAULT '',
  `configuration_value` char(255) NOT NULL DEFAULT '',
  `configuration_description` char(255) NOT NULL DEFAULT '',
  `configuration_group_id` int(11) NOT NULL DEFAULT 0,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `branch_code` varchar(50) NOT NULL,
  PRIMARY KEY (`configuration_id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.costcenters
DROP TABLE IF EXISTS `costcenters`;
CREATE TABLE IF NOT EXISTS `costcenters` (
  `costcenters_name` char(200) DEFAULT NULL,
  `costcenters_code` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`costcenters_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.countries
DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `countries_id` int(11) NOT NULL AUTO_INCREMENT,
  `countries_name` char(64) NOT NULL DEFAULT '',
  `countries_iso_code_2` char(2) NOT NULL DEFAULT '',
  `countries_iso_code_3` char(3) NOT NULL DEFAULT '',
  `address_format_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`countries_id`),
  KEY `IDX_COUNTRIES_NAME` (`countries_name`)
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.currencies
DROP TABLE IF EXISTS `currencies`;
CREATE TABLE IF NOT EXISTS `currencies` (
  `currencies_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `iso_alpha2` char(2) DEFAULT NULL,
  `iso_alpha3` char(3) DEFAULT NULL,
  `iso_numeric` int(11) DEFAULT NULL,
  `currencies_code` char(5) DEFAULT NULL,
  `currencies_name` char(32) DEFAULT NULL,
  `currencies_symbolleft` char(3) DEFAULT NULL,
  `currencies_symbolright` char(6) DEFAULT NULL,
  `currencies_decimalplaces` tinyint(4) DEFAULT 2,
  `currencies_isbase` char(1) DEFAULT 'N',
  `currencies_decimalpoint` char(1) DEFAULT NULL,
  `flag` char(50) DEFAULT NULL,
  `chartofaccounts_accountcode` char(50) DEFAULT NULL,
  PRIMARY KEY (`currencies_id`)
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.currencydeno
DROP TABLE IF EXISTS `currencydeno`;
CREATE TABLE IF NOT EXISTS `currencydeno` (
  `currencydeno_id` int(11) NOT NULL AUTO_INCREMENT,
  `currencies_id` int(11) DEFAULT NULL,
  `currencydeno_deno` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`currencydeno_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.dashboard
DROP TABLE IF EXISTS `dashboard`;
CREATE TABLE IF NOT EXISTS `dashboard` (
  `translations_id` char(100) DEFAULT NULL,
  `value` decimal(20,5) NOT NULL DEFAULT 0.00000
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.deletedloans
DROP TABLE IF EXISTS `deletedloans`;
CREATE TABLE IF NOT EXISTS `deletedloans` (
  `loan_number` char(10) NOT NULL,
  `deletedloans_date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`loan_number`,`deletedloans_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.deletedtrans
DROP TABLE IF EXISTS `deletedtrans`;
CREATE TABLE IF NOT EXISTS `deletedtrans` (
  `deletedtrans_module` char(3) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transactioncode` char(45) NOT NULL,
  `branch_code` char(2) DEFAULT NULL,
  `deletedtrans_processed` char(1) DEFAULT NULL,
  `deletedtrans_datecreated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.departments
DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `departments_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `departments_name` char(100) DEFAULT NULL,
  PRIMARY KEY (`departments_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.devicemessage
DROP TABLE IF EXISTS `devicemessage`;
CREATE TABLE IF NOT EXISTS `devicemessage` (
  `devicemessage_id` char(80) NOT NULL DEFAULT 'uuid()',
  `devicemessage_date` datetime NOT NULL DEFAULT current_timestamp(),
  `device_id` int(11) DEFAULT NULL,
  `devicemessage_msg` varchar(255) NOT NULL,
  `devicemessage_status` char(2) NOT NULL,
  `clientid` char(50) DEFAULT NULL,
  `tel` char(50) NOT NULL,
  `loan_number` char(10) DEFAULT NULL,
  `devicemessage_response` tinytext DEFAULT NULL,
  PRIMARY KEY (`devicemessage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.disbursements
DROP TABLE IF EXISTS `disbursements`;
CREATE TABLE IF NOT EXISTS `disbursements` (
  `loan_number` char(10) NOT NULL,
  `disbursements_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `transactioncode` char(45) NOT NULL,
  `disbursements_vat` decimal(10,2) DEFAULT NULL,
  `disbursements_voucher` char(50) DEFAULT NULL,
  `disbursements_stationery` decimal(15,2) DEFAULT NULL,
  `disbursements_amount` decimal(15,2) NOT NULL,
  `disbursements_commission` decimal(15,2) NOT NULL,
  `cheqs_no` char(50) DEFAULT NULL,
  `cash` char(4) NOT NULL,
  `cycle` int(11) DEFAULT NULL,
  `members_idno` char(12) NOT NULL,
  `disbursements_type` char(5) DEFAULT NULL COMMENT 'DD: Disbursed,RF:Refinance,PD:Partial Dibursement',
  `paymode` char(5) DEFAULT NULL COMMENT 'DD: Disbursed,RF:Refinance,PD:Partial Dibursement',
  `user_id` char(5) DEFAULT NULL,
  PRIMARY KEY (`disbursements_date`,`loan_number`),
  KEY `disbursements_date` (`disbursements_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.document
DROP TABLE IF EXISTS `document`;
CREATE TABLE IF NOT EXISTS `document` (
  `clientcode` char(12) NOT NULL,
  `document_serial` char(50) NOT NULL,
  `documenttypes_id` tinyint(4) DEFAULT NULL,
  `document_issuedate` date NOT NULL,
  `document_docexpiry` date DEFAULT NULL,
  `document_priority` tinyint(4) DEFAULT NULL,
  `document_issueauthority` char(50) DEFAULT NULL,
  `document_url` tinytext DEFAULT NULL,
  `document_id` varchar(256) NOT NULL DEFAULT 'uuid()',
  PRIMARY KEY (`clientcode`,`document_serial`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.documentpriority
DROP TABLE IF EXISTS `documentpriority`;
CREATE TABLE IF NOT EXISTS `documentpriority` (
  `documentpriority_id` int(11) NOT NULL AUTO_INCREMENT,
  `documentpriority_en` char(10) DEFAULT NULL,
  `documentpriority_fr` char(10) DEFAULT NULL,
  PRIMARY KEY (`documentpriority_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.documenttypes
DROP TABLE IF EXISTS `documenttypes`;
CREATE TABLE IF NOT EXISTS `documenttypes` (
  `documenttypes_id` int(10) NOT NULL AUTO_INCREMENT,
  `documenttypes_name_fr` char(50) DEFAULT NULL,
  `documenttypes_name_en` char(50) DEFAULT NULL,
  PRIMARY KEY (`documenttypes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.donor
DROP TABLE IF EXISTS `donor`;
CREATE TABLE IF NOT EXISTS `donor` (
  `donor_code` char(5) DEFAULT NULL,
  `donor_name` char(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.dues
DROP TABLE IF EXISTS `dues`;
CREATE TABLE IF NOT EXISTS `dues` (
  `loan_number` char(10) NOT NULL,
  `due_date` date NOT NULL,
  `due_id` char(80) NOT NULL DEFAULT '',
  `due_principal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `due_interest` decimal(15,2) NOT NULL DEFAULT 0.00,
  `due_penalty` decimal(15,2) NOT NULL DEFAULT 0.00,
  `due_commission` decimal(15,2) NOT NULL DEFAULT 0.00,
  `due_vat` decimal(15,2) NOT NULL DEFAULT 0.00,
  `members_idno` char(12) DEFAULT NULL,
  `due_status` char(3) DEFAULT '',
  PRIMARY KEY (`loan_number`,`due_date`,`due_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=COMPRESSED;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.educationlevel
DROP TABLE IF EXISTS `educationlevel`;
CREATE TABLE IF NOT EXISTS `educationlevel` (
  `educationlevel_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `educationlevel_level` char(50) DEFAULT NULL,
  PRIMARY KEY (`educationlevel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.entity
DROP TABLE IF EXISTS `entity`;
CREATE TABLE IF NOT EXISTS `entity` (
  `entity_idno` char(50) NOT NULL,
  `branch_code` char(2) NOT NULL,
  `entity_postad` char(100) DEFAULT NULL,
  `entity_tel2` char(100) DEFAULT NULL,
  `entity_regdate` date NOT NULL DEFAULT curdate(),
  `entity_name` char(100) NOT NULL,
  `entity_city` char(100) DEFAULT NULL,
  `entity_addressphysical` char(100) DEFAULT NULL,
  `entity_tel1` char(100) DEFAULT NULL,
  `bussinesssector_code` char(50) NOT NULL,
  `entity_regstatus` char(4) NOT NULL,
  `areacode_code` char(100) DEFAULT NULL,
  `entity_enddate` date DEFAULT NULL,
  `costcenters_code` char(4) DEFAULT NULL,
  `user_accesscode` varchar(50) DEFAULT NULL,
  `entity_regcode` varchar(50) DEFAULT NULL,
  `entity_type` char(1) DEFAULT NULL,
  PRIMARY KEY (`entity_idno`),
  KEY `groups_name` (`entity_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.errors
DROP TABLE IF EXISTS `errors`;
CREATE TABLE IF NOT EXISTS `errors` (
  `err` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for event moneybankonline.evt_clear_xmltrans
DROP EVENT IF EXISTS `evt_clear_xmltrans`;
DELIMITER //
CREATE EVENT `evt_clear_xmltrans` ON SCHEDULE EVERY 1 DAY STARTS '2018-07-02 17:02:21' ON COMPLETION PRESERVE ENABLE DO BEGIN
	DELETE FROM xmltrans  where xmltrans_status='Y';
END//
DELIMITER ;

-- Dumping structure for event moneybankonline.evt_update_outstanding_loans
DROP EVENT IF EXISTS `evt_update_outstanding_loans`;
DELIMITER //
CREATE EVENT `evt_update_outstanding_loans` ON SCHEDULE EVERY 1 DAY STARTS '2024-03-28 17:39:33' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
 SELECT * FROM loans;
END//
DELIMITER ;

-- Dumping structure for table moneybankonline.fees
DROP TABLE IF EXISTS `fees`;
CREATE TABLE IF NOT EXISTS `fees` (
  `fees_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `fees_name` char(50) DEFAULT NULL,
  `fees_code` char(50) DEFAULT NULL,
  PRIMARY KEY (`fees_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.feesconfig
DROP TABLE IF EXISTS `feesconfig`;
CREATE TABLE IF NOT EXISTS `feesconfig` (
  `feesconfig_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fees_id` tinyint(50) DEFAULT NULL,
  `feesconfig_level` char(50) DEFAULT NULL,
  `feesconfig_amt` decimal(10,5) DEFAULT NULL,
  `feesconfig_per` decimal(10,2) DEFAULT NULL,
  `product_prodid` char(50) DEFAULT NULL,
  PRIMARY KEY (`feesconfig_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.forexrates
DROP TABLE IF EXISTS `forexrates`;
CREATE TABLE IF NOT EXISTS `forexrates` (
  `forexrates_id` bigint(100) NOT NULL AUTO_INCREMENT,
  `currencies_id` int(5) NOT NULL,
  `forexrates_buyrate` decimal(10,5) NOT NULL DEFAULT 0.00000,
  `forexrates_midrate` decimal(10,5) NOT NULL DEFAULT 0.00000,
  `forexrates_sellrate` decimal(10,5) NOT NULL DEFAULT 0.00000,
  `forexrates_datecreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `forexrates_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `branchcode` char(5) NOT NULL,
  PRIMARY KEY (`forexrates_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.fund
DROP TABLE IF EXISTS `fund`;
CREATE TABLE IF NOT EXISTS `fund` (
  `fund_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `fund_code` char(5) DEFAULT NULL,
  `fund_name` char(50) DEFAULT NULL,
  PRIMARY KEY (`fund_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.generalledger
DROP TABLE IF EXISTS `generalledger`;
CREATE TABLE IF NOT EXISTS `generalledger` (
  `chartofaccounts_accountcode` char(20) NOT NULL,
  `generalledger_tday` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `transactioncode` char(45) NOT NULL DEFAULT '',
  `generalledger_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `generalledger_description` char(100) NOT NULL DEFAULT '',
  `generalledger_id` char(100) NOT NULL DEFAULT '',
  `fund_code` char(4) DEFAULT '',
  `donor_code` char(5) DEFAULT '',
  `generalledger_credit` decimal(50,2) NOT NULL DEFAULT 0.00,
  `generalledger_voucher` char(45) DEFAULT '''''',
  `user_id` int(10) unsigned NOT NULL DEFAULT 0,
  `generalledger_debit` decimal(50,2) NOT NULL DEFAULT 0.00,
  `branch_code` char(50) NOT NULL DEFAULT '',
  `trancode` char(45) NOT NULL DEFAULT '',
  `generalledger_locked` char(1) NOT NULL DEFAULT 'N',
  `forexrates_id` bigint(100) DEFAULT 0,
  `generalledger_fcamount` decimal(10,5) DEFAULT 0.00000,
  `currencies_id` int(50) DEFAULT 0,
  `client_idno` char(12) DEFAULT NULL,
  `product_prodid` char(5) DEFAULT '',
  `costcenters_code` char(50) DEFAULT '',
  PRIMARY KEY (`chartofaccounts_accountcode`,`generalledger_tday`,`transactioncode`,`generalledger_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.gltrans
DROP TABLE IF EXISTS `gltrans`;
CREATE TABLE IF NOT EXISTS `gltrans` (
  `generalledger_id` int(10) unsigned NOT NULL DEFAULT 0,
  `transactioncode` char(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `generalledger_description` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `fund_code` char(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `donor_code` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `generalledger_credit` decimal(50,5) NOT NULL DEFAULT 0.00000,
  `generalledger_voucher` char(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `users_id` int(10) unsigned NOT NULL DEFAULT 0,
  `generalledger_tday` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `generalledger_debit` decimal(50,5) NOT NULL DEFAULT 0.00000,
  `chartofaccounts_accountcode` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `generalledger_updated` timestamp NOT NULL DEFAULT current_timestamp(),
  `branchcode` char(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `trancode` char(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `generalledger_locked` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'N',
  `forexrates_id` bigint(100) DEFAULT 0,
  `generalledger_fcamount` decimal(10,5) DEFAULT 0.00000,
  `currencies_code` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `client_idno` char(12) DEFAULT NULL,
  `product_prodid` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT ''
) ENGINE=MEMORY DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.groupmembership
DROP TABLE IF EXISTS `groupmembership`;
CREATE TABLE IF NOT EXISTS `groupmembership` (
  `client_id` bigint(20) NOT NULL,
  `groupmembership_id` char(50) NOT NULL,
  `groupmembership_no` int(11) DEFAULT 0,
  `groupmembership_start` date DEFAULT NULL,
  `groupmembership_end` date DEFAULT NULL,
  PRIMARY KEY (`groupmembership_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.guarantors
DROP TABLE IF EXISTS `guarantors`;
CREATE TABLE IF NOT EXISTS `guarantors` (
  `guarantors_datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `loan_number` char(10) NOT NULL,
  `client_idno` char(12) NOT NULL,
  PRIMARY KEY (`loan_number`,`client_idno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='This tables is used to store guarantors';

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.identifications
DROP TABLE IF EXISTS `identifications`;
CREATE TABLE IF NOT EXISTS `identifications` (
  `identifications_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `branchcode` char(50) DEFAULT '',
  `identifications_idno` bigint(20) DEFAULT 0,
  `identifications_idtype` char(50) DEFAULT '',
  `identifications_subtype` char(50) DEFAULT '',
  PRIMARY KEY (`identifications_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1454 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.incomecategories
DROP TABLE IF EXISTS `incomecategories`;
CREATE TABLE IF NOT EXISTS `incomecategories` (
  `incomecategories_id` tinyint(4) NOT NULL DEFAULT 0,
  `incomecategories_bracket` char(50) DEFAULT NULL,
  PRIMARY KEY (`incomecategories_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.interestsav
DROP TABLE IF EXISTS `interestsav`;
CREATE TABLE IF NOT EXISTS `interestsav` (
  `savaccounts_account` char(12) NOT NULL,
  `product_prodid` char(5) NOT NULL,
  `interestsav_date` date NOT NULL,
  `interestsav_period` int(11) NOT NULL,
  `interestsav_amount` decimal(15,5) NOT NULL,
  PRIMARY KEY (`savaccounts_account`,`product_prodid`,`interestsav_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.ipcountry
DROP TABLE IF EXISTS `ipcountry`;
CREATE TABLE IF NOT EXISTS `ipcountry` (
  `IP_FROM` char(200) DEFAULT '0',
  `IP_TO` char(200) DEFAULT NULL,
  `COUNTRY_CODE2` char(5) DEFAULT NULL,
  `COUNTRY_CODE3` char(5) DEFAULT NULL,
  `COUNTRY_NAME` char(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.items
DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` char(200) DEFAULT NULL,
  `item_cd` char(15) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.languages
DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `languages_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(32) NOT NULL DEFAULT '',
  `code` char(2) NOT NULL DEFAULT '',
  `image` char(64) DEFAULT NULL,
  `directory` char(32) DEFAULT NULL,
  `sort_order` int(3) DEFAULT NULL,
  PRIMARY KEY (`languages_id`),
  KEY `IDX_LANGUAGES_NAME` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.lasttableupdatedate
DROP TABLE IF EXISTS `lasttableupdatedate`;
CREATE TABLE IF NOT EXISTS `lasttableupdatedate` (
  `tablename` char(50) NOT NULL,
  `lastupdatedate` datetime NOT NULL,
  PRIMARY KEY (`tablename`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.licence
DROP TABLE IF EXISTS `licence`;
CREATE TABLE IF NOT EXISTS `licence` (
  `licence_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `licence_organisationname` char(70) NOT NULL DEFAULT '',
  `licence_address` char(45) DEFAULT NULL,
  `licence_fax` char(45) DEFAULT NULL,
  `branchcode` char(4) DEFAULT NULL,
  `licence_key` text NOT NULL,
  `licence_build` char(50) NOT NULL,
  `branch_code` char(50) NOT NULL,
  PRIMARY KEY (`licence_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.loan
DROP TABLE IF EXISTS `loan`;
CREATE TABLE IF NOT EXISTS `loan` (
  `loan_number` char(10) NOT NULL,
  `client_idno` char(12) NOT NULL,
  `loan_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `fund_code` char(4) NOT NULL DEFAULT '0000',
  `loan_tint` decimal(10,2) NOT NULL DEFAULT 0.00,
  `loan_intamount` decimal(15,2) unsigned DEFAULT 0.00,
  `user_id` bigint(20) NOT NULL,
  `loan_startdate` date NOT NULL,
  `loan_grace` bigint(20) unsigned DEFAULT 0,
  `loan_noofinst` bigint(20) NOT NULL DEFAULT 0,
  `loan_exp` date DEFAULT NULL,
  `loan_status` char(4) NOT NULL,
  `loan_firstinst` decimal(15,2) DEFAULT NULL,
  `loan_udf1` char(4) NOT NULL,
  `loan_udf2` char(4) NOT NULL,
  `loan_udf3` char(4) NOT NULL,
  `loan_adate` date NOT NULL,
  `loan_inttype` char(3) NOT NULL,
  `loan_insttype` char(3) NOT NULL,
  `loan_alsograce` char(1) DEFAULT 'N',
  `loan_intdays` char(1) DEFAULT 'N',
  `loan_intdeductedatdisb` char(1) DEFAULT 'N',
  `product_prodid` char(10) NOT NULL,
  `donor_code` char(5) NOT NULL DEFAULT '00000',
  `branch_code` char(50) NOT NULL,
  `members_idno` char(12) DEFAULT NULL,
  `loan_intcgrace` char(1) DEFAULT 'N',
  `loan_intfirst` char(1) DEFAULT 'N',
  `loan_lastinstpp` decimal(15,2) DEFAULT 0.00,
  `loan_insintgrac` decimal(15,2) DEFAULT 0.00,
  `loan_comm` decimal(15,2) DEFAULT 0.00,
  `loan_freezedate` date DEFAULT NULL,
  `loan_expdisb` date DEFAULT NULL,
  `loan_gracecompd` char(1) DEFAULT 'N',
  `loan_intindays` char(1) DEFAULT 'N',
  `loanpurpose_id` tinyint(4) DEFAULT NULL,
  `loan_inupfront` char(1) DEFAULT NULL,
  PRIMARY KEY (`loan_number`,`client_idno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.loancategory
DROP TABLE IF EXISTS `loancategory`;
CREATE TABLE IF NOT EXISTS `loancategory` (
  `loancategory_id` int(10) NOT NULL DEFAULT 0,
  `loancategory_code` char(4) DEFAULT NULL,
  `loancategory_name` char(50) DEFAULT NULL,
  PRIMARY KEY (`loancategory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.loanfee
DROP TABLE IF EXISTS `loanfee`;
CREATE TABLE IF NOT EXISTS `loanfee` (
  `loan_number` char(10) NOT NULL,
  `client_idno` char(12) NOT NULL,
  `transactioncode` char(45) NOT NULL,
  `savaccounts_account` char(12) DEFAULT NULL,
  `loanfee_amount` decimal(15,5) NOT NULL,
  `members_idno` char(12) DEFAULT NULL,
  `last_updatedate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `loanfee_type` char(5) DEFAULT NULL,
  `loanfee_date` datetime DEFAULT NULL,
  `loanfee_voucher` char(50) DEFAULT NULL,
  `paymode` char(50) DEFAULT NULL,
  `user_id` char(50) DEFAULT NULL,
  PRIMARY KEY (`loan_number`,`client_idno`,`transactioncode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.loanpayments
DROP TABLE IF EXISTS `loanpayments`;
CREATE TABLE IF NOT EXISTS `loanpayments` (
  `loanpayments_date` datetime NOT NULL,
  `loan_number` char(10) NOT NULL,
  `members_idno` char(12) NOT NULL,
  `loanpayments_principal` decimal(15,2) DEFAULT 0.00,
  `loanpayments_interest` decimal(15,2) DEFAULT 0.00,
  `loanpayments_commission` decimal(15,2) DEFAULT 0.00,
  `loanpayments_penalty` decimal(15,2) DEFAULT 0.00,
  `loanpayments_vat` decimal(15,2) DEFAULT 0.00,
  `transactioncode` char(45) NOT NULL,
  `paymode` char(5) DEFAULT '',
  `loanpayments_voucher` char(100) DEFAULT '',
  `loanpayments_id` char(100) NOT NULL DEFAULT '',
  `user_id` char(50) DEFAULT '',
  `loanpayments_overpay` decimal(15,2) DEFAULT 0.00,
  KEY `loanpayments_date_loan_number` (`loanpayments_date`,`loan_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.loanstatuslog
DROP TABLE IF EXISTS `loanstatuslog`;
CREATE TABLE IF NOT EXISTS `loanstatuslog` (
  `loan_number` char(10) NOT NULL,
  `loan_status` char(2) NOT NULL DEFAULT '',
  `loan_datecreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `loan_amount` decimal(15,5) unsigned DEFAULT 0.00000,
  `user_id` bigint(20) unsigned DEFAULT 0,
  PRIMARY KEY (`loan_number`,`loan_status`,`loan_datecreated`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.loanswrittenoff
DROP TABLE IF EXISTS `loanswrittenoff`;
CREATE TABLE IF NOT EXISTS `loanswrittenoff` (
  `loan_number` char(10) NOT NULL,
  `loan_amount` decimal(15,5) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `members_idno` char(12) DEFAULT NULL,
  `client_idno` char(12) DEFAULT NULL,
  `loanswrittenoff_date` date DEFAULT NULL,
  PRIMARY KEY (`loan_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.memberdocuments
DROP TABLE IF EXISTS `memberdocuments`;
CREATE TABLE IF NOT EXISTS `memberdocuments` (
  `members_idno` char(12) DEFAULT NULL,
  `document_type` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.memberloans
DROP TABLE IF EXISTS `memberloans`;
CREATE TABLE IF NOT EXISTS `memberloans` (
  `loan_number` char(10) NOT NULL,
  `members_idno` char(12) NOT NULL,
  `client_idno` char(12) DEFAULT NULL,
  `memberloans_amount` decimal(15,5) NOT NULL,
  `memberloans_intamount` decimal(10,5) NOT NULL,
  `memberloans_cycle` tinyint(4) NOT NULL,
  PRIMARY KEY (`loan_number`,`members_idno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.members
DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `members_idno` char(12) NOT NULL,
  `members_no` char(10) NOT NULL,
  `members_firstname` char(50) DEFAULT '0',
  `members_middlename` char(50) DEFAULT '0',
  `members_lastname` char(50) DEFAULT '0',
  `members_maritalstate` char(2) NOT NULL DEFAULT '0',
  `members_bday` date DEFAULT NULL,
  `members_regdate` date NOT NULL DEFAULT '0000-00-00',
  `members_enddate` date DEFAULT NULL,
  `members_dependants` tinyint(4) DEFAULT 0,
  `members_children` int(11) DEFAULT 0,
  `members_cat1` int(11) DEFAULT 0,
  `members_cat2` int(11) DEFAULT 0,
  `members_educ` char(2) DEFAULT NULL,
  `members_lang1` char(5) DEFAULT NULL,
  `members_lang2` char(5) DEFAULT NULL,
  `incomecategories_id` tinyint(4) DEFAULT NULL,
  `members_email` char(50) DEFAULT NULL,
  `entity_idno` char(50) NOT NULL,
  `members_regstatus` char(4) DEFAULT NULL,
  `members_postad` char(254) DEFAULT NULL,
  `members_gender` char(10) DEFAULT NULL,
  `members_city` char(254) DEFAULT NULL,
  `members_addressphysical` char(254) DEFAULT NULL,
  `areacode_code` char(254) DEFAULT NULL,
  `bussinesssector_code` char(254) DEFAULT NULL,
  `costcenters_code` char(254) DEFAULT NULL,
  `members_tel1` char(254) DEFAULT NULL,
  `members_tel2` char(254) DEFAULT NULL,
  `user_accesscode` char(254) DEFAULT NULL,
  `branch_code` char(2) NOT NULL,
  `members_income` char(2) NOT NULL,
  PRIMARY KEY (`members_idno`,`members_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.modem
DROP TABLE IF EXISTS `modem`;
CREATE TABLE IF NOT EXISTS `modem` (
  `modem_id` int(11) NOT NULL AUTO_INCREMENT,
  `modem_name` char(100) DEFAULT NULL,
  `modem_bitrate` bigint(20) DEFAULT NULL,
  `modem_port` char(50) DEFAULT NULL,
  PRIMARY KEY (`modem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.moduleaccescodes
DROP TABLE IF EXISTS `moduleaccescodes`;
CREATE TABLE IF NOT EXISTS `moduleaccescodes` (
  `moduleaccescodes_id` bigint(4) NOT NULL AUTO_INCREMENT,
  `modules_code` char(50) DEFAULT NULL,
  `accesscode` tinytext DEFAULT NULL,
  `session_id` tinytext DEFAULT NULL,
  `moduleaccescodes_verified` char(1) DEFAULT NULL,
  PRIMARY KEY (`moduleaccescodes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11623 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.modules
DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `modules_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `modules_code` char(45) NOT NULL DEFAULT '',
  `modules_description` char(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`modules_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.modulesoperations
DROP TABLE IF EXISTS `modulesoperations`;
CREATE TABLE IF NOT EXISTS `modulesoperations` (
  `modulesoperations_id` int(10) NOT NULL AUTO_INCREMENT,
  `operations_id` int(10) DEFAULT NULL,
  `modules_id` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `roles_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`modulesoperations_id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.operations
DROP TABLE IF EXISTS `operations`;
CREATE TABLE IF NOT EXISTS `operations` (
  `operations_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `operations_code` char(45) NOT NULL DEFAULT '',
  `operations_description_eng` char(45) NOT NULL DEFAULT '',
  `operations_description_fr` char(45) NOT NULL DEFAULT '',
  `operations_description_lug` char(45) NOT NULL DEFAULT '',
  `operations_description_sp` char(45) NOT NULL DEFAULT '',
  `operations_description_ja` char(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`operations_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.operatorbranches
DROP TABLE IF EXISTS `operatorbranches`;
CREATE TABLE IF NOT EXISTS `operatorbranches` (
  `bankbranches_id` int(10) NOT NULL AUTO_INCREMENT,
  `bankbranches_code` char(50) DEFAULT NULL,
  `bankbranches_name` char(100) DEFAULT NULL,
  `branch_code` char(50) DEFAULT NULL,
  `licence_build` char(50) DEFAULT NULL,
  `banks_id` tinyint(4) DEFAULT NULL,
  `chartofaccounts_accountcode` char(50) DEFAULT '''''',
  PRIMARY KEY (`bankbranches_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.product
DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `product_name` char(100) DEFAULT NULL,
  `product_prodid` char(10) NOT NULL,
  PRIMARY KEY (`product_prodid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.productconfig
DROP TABLE IF EXISTS `productconfig`;
CREATE TABLE IF NOT EXISTS `productconfig` (
  `product_prodid` char(10) NOT NULL,
  `productconfig_paramname` char(50) NOT NULL,
  `branch_code` char(50) NOT NULL,
  `productconfig_valuetype` char(50) DEFAULT NULL,
  `productconfig_value` char(50) DEFAULT NULL,
  `productconfig_ind` char(200) DEFAULT NULL,
  `productconfig_grp` char(200) DEFAULT NULL,
  `productconfig_datagroup` char(50) DEFAULT NULL,
  `productconfig_description` char(200) DEFAULT NULL,
  PRIMARY KEY (`product_prodid`,`productconfig_paramname`,`branch_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for view moneybankonline.productcurrencies
DROP VIEW IF EXISTS `productcurrencies`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `productcurrencies` (
	`product_prodid` CHAR(10) NOT NULL COLLATE 'latin1_swedish_ci',
	`currencies_id` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for table moneybankonline.publicholidays
DROP TABLE IF EXISTS `publicholidays`;
CREATE TABLE IF NOT EXISTS `publicholidays` (
  `publicholidays_id` int(10) NOT NULL,
  `publicholidays_date` date NOT NULL,
  `publicholidays_description` char(200) DEFAULT NULL,
  `publicholidays_reoccurs` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`publicholidays_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.querycache
DROP TABLE IF EXISTS `querycache`;
CREATE TABLE IF NOT EXISTS `querycache` (
  `querycache_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `querycache_query` longtext NOT NULL,
  `querycache_datecreated` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`querycache_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.recentactivity
DROP TABLE IF EXISTS `recentactivity`;
CREATE TABLE IF NOT EXISTS `recentactivity` (
  `recentactivity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recentactivity_action` char(45) NOT NULL DEFAULT '',
  `recentactivity_description` char(100) NOT NULL DEFAULT '',
  `user_id` int(10) unsigned NOT NULL,
  `recentactivity_ip` char(50) NOT NULL DEFAULT '',
  `recentactivity_servertimestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `operatorbranches_code` char(50) NOT NULL DEFAULT '',
  `transfers_code` tinytext NOT NULL,
  PRIMARY KEY (`recentactivity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.reciepts
DROP TABLE IF EXISTS `reciepts`;
CREATE TABLE IF NOT EXISTS `reciepts` (
  `reciepts_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reciepts_code` char(45) NOT NULL DEFAULT '',
  `reciepts` mediumtext NOT NULL,
  `reciepts_datecreated` datetime NOT NULL,
  PRIMARY KEY (`reciepts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.reconciliationhistory
DROP TABLE IF EXISTS `reconciliationhistory`;
CREATE TABLE IF NOT EXISTS `reconciliationhistory` (
  `reconciliationhistory_id` int(10) NOT NULL AUTO_INCREMENT,
  `tcode` char(45) DEFAULT NULL,
  `debit` decimal(50,4) NOT NULL,
  `credit` decimal(50,4) NOT NULL,
  `bankaccounts_accno` char(50) NOT NULL,
  `chartofaccounts_accountcode` char(50) NOT NULL,
  `bankstatement_datecreated` date DEFAULT NULL,
  `reconciliationhistory_closed` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`reconciliationhistory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.reconciliationperiods
DROP TABLE IF EXISTS `reconciliationperiods`;
CREATE TABLE IF NOT EXISTS `reconciliationperiods` (
  `reconciliationperiods_id` int(10) NOT NULL AUTO_INCREMENT,
  `reconciliationperiods_from` date DEFAULT NULL,
  `reconciliationperiods_to` date DEFAULT NULL,
  `reconciliationperiods_closingbal` decimal(10,4) DEFAULT 0.0000,
  `reconciliationperiods_confirmed` char(1) DEFAULT 'N',
  PRIMARY KEY (`reconciliationperiods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='This table saves all reconciliation periods';

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.refinanced
DROP TABLE IF EXISTS `refinanced`;
CREATE TABLE IF NOT EXISTS `refinanced` (
  `loan_number` char(10) NOT NULL,
  `refinanced_startdate` datetime NOT NULL,
  `refinanced_originalamt` decimal(15,5) NOT NULL,
  `refinanced_addedamt` decimal(15,5) NOT NULL,
  `loan_noofinst` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `refinanced_datecreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `refinanced_status` char(5) NOT NULL,
  PRIMARY KEY (`loan_number`,`refinanced_startdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.refinance_schedule
DROP TABLE IF EXISTS `refinance_schedule`;
CREATE TABLE IF NOT EXISTS `refinance_schedule` (
  `loan_number` char(10) NOT NULL,
  `due_date` date NOT NULL,
  `due_id` char(80) NOT NULL DEFAULT '',
  `due_principal` decimal(15,5) NOT NULL DEFAULT 0.00000,
  `due_interest` decimal(15,5) NOT NULL DEFAULT 0.00000,
  `due_penalty` decimal(15,5) NOT NULL DEFAULT 0.00000,
  `due_commission` decimal(15,5) NOT NULL DEFAULT 0.00000,
  `due_vat` decimal(15,5) NOT NULL DEFAULT 0.00000,
  `groupmembership_id` char(50) DEFAULT '',
  `due_status` char(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`due_id`,`due_date`,`loan_number`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=COMPRESSED;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.rolecashaccounts
DROP TABLE IF EXISTS `rolecashaccounts`;
CREATE TABLE IF NOT EXISTS `rolecashaccounts` (
  `rolecashaccounts_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `roles_id` int(10) unsigned NOT NULL DEFAULT 0,
  `chartofaccounts_accountcode` char(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`rolecashaccounts_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `roles_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `roles_name_eng` char(45) NOT NULL DEFAULT '',
  `roles_name_fr` char(45) NOT NULL DEFAULT '',
  `roles_name_swa` char(45) NOT NULL DEFAULT '',
  `roles_name_sp` char(45) NOT NULL DEFAULT '',
  `roles_name_ja` char(45) NOT NULL DEFAULT '',
  `roles_description` char(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`roles_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.rolesmodules
DROP TABLE IF EXISTS `rolesmodules`;
CREATE TABLE IF NOT EXISTS `rolesmodules` (
  `roles_id` int(10) unsigned NOT NULL DEFAULT 0,
  `modules_id` char(45) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.rolesoperations
DROP TABLE IF EXISTS `rolesoperations`;
CREATE TABLE IF NOT EXISTS `rolesoperations` (
  `roles_id` int(10) unsigned NOT NULL DEFAULT 0,
  `operations_id` int(10) unsigned NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.savaccounts
DROP TABLE IF EXISTS `savaccounts`;
CREATE TABLE IF NOT EXISTS `savaccounts` (
  `client_idno` char(12) NOT NULL,
  `savaccounts_account` char(12) NOT NULL,
  `product_prodid` char(5) NOT NULL,
  `savaccounts_opendate` datetime NOT NULL,
  `savaccounts_closedate` date DEFAULT NULL,
  `savaccounts_id` char(80) NOT NULL DEFAULT 'uuid()',
  `members_idno` char(12) DEFAULT NULL,
  PRIMARY KEY (`client_idno`,`savaccounts_account`,`product_prodid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.savingsbalances
DROP TABLE IF EXISTS `savingsbalances`;
CREATE TABLE IF NOT EXISTS `savingsbalances` (
  `savaccounts_account` char(12) NOT NULL,
  `product_prodid` char(5) NOT NULL,
  `balance` decimal(15,5) DEFAULT NULL,
  `lastupdate` datetime DEFAULT current_timestamp(),
  `clientidno` char(50) DEFAULT NULL,
  PRIMARY KEY (`savaccounts_account`,`product_prodid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.savtransactions
DROP TABLE IF EXISTS `savtransactions`;
CREATE TABLE IF NOT EXISTS `savtransactions` (
  `savaccounts_account` char(12) NOT NULL,
  `product_prodid` char(5) NOT NULL DEFAULT '',
  `savtransactions_tday` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `transactioncode` char(45) NOT NULL,
  `savtransactions_voucher` char(100) DEFAULT '',
  `savtransactions_amount` decimal(50,5) NOT NULL,
  `savtransactions_balance` decimal(50,5) NOT NULL,
  `savtransactions_commission` decimal(50,5) NOT NULL,
  `cheqs_no` char(100) DEFAULT NULL,
  `members_idno` char(12) NOT NULL,
  `transactiontypes_code` char(5) NOT NULL,
  `paymode` char(5) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `last_updatedate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`savaccounts_account`,`savtransactions_tday`,`transactioncode`,`members_idno`,`last_updatedate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.sentmessages
DROP TABLE IF EXISTS `sentmessages`;
CREATE TABLE IF NOT EXISTS `sentmessages` (
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `message` varchar(255) NOT NULL,
  `client_idno` char(12) DEFAULT NULL,
  `tel` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.sessiondata
DROP TABLE IF EXISTS `sessiondata`;
CREATE TABLE IF NOT EXISTS `sessiondata` (
  `sessiondata_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sessiondata_data` mediumint(9) DEFAULT NULL,
  `sessiondata_table` varchar(254) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `sessiondata_date` datetime DEFAULT NULL,
  PRIMARY KEY (`sessiondata_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `sesskey` varchar(64) NOT NULL DEFAULT '',
  `expiry` int(11) unsigned NOT NULL DEFAULT 0,
  `value` mediumblob NOT NULL,
  PRIMARY KEY (`sesskey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.shatransactions
DROP TABLE IF EXISTS `shatransactions`;
CREATE TABLE IF NOT EXISTS `shatransactions` (
  `transactioncode` char(45) DEFAULT NULL,
  `tday` datetime DEFAULT NULL,
  `client_idno` char(12) DEFAULT NULL,
  `cheqs_no` char(100) DEFAULT NULL,
  `paymode` char(5) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `voucher` char(100) DEFAULT NULL,
  `product_prodid` char(5) DEFAULT NULL,
  `sharevalue` decimal(15,2) DEFAULT NULL,
  `noofshares` decimal(15,2) DEFAULT NULL,
  `norminalval` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for procedure moneybankonline.sp_calculate_provisions
DROP PROCEDURE IF EXISTS `sp_calculate_provisions`;
DELIMITER //
CREATE PROCEDURE `sp_calculate_provisions`(
	IN `pDate` DATE,
	IN `branch_code` CHAR(50),
	IN `product_prodid` CHAR(50),
	IN `class1b` BIGINT,
	IN `class2b` BIGINT,
	IN `class3b` BIGINT,
	IN `class4b` BIGINT,
	IN `class5a` BIGINT,
	IN `class1per` INT,
	IN `class2per` INT,
	IN `class3per` INT,
	IN `class4per` INT,
	IN `class5per` INT,
	IN `vpost` TINYINT,
	IN `plang` CHAR(5),
	IN `user_id` INT
)
BEGIN
	-- TODO: Validate situations where foreign currency do not have exchange rates
	-- TODO: terminate this procedure
	
	DECLARE vloan_number CHAR(50) DEFAULT '';
	DECLARE vproduct_prodid CHAR(50) DEFAULT '';			
	DECLARE vAmount NUMERIC(15,5);
	DECLARE vadjbal NUMERIC(15,5);

	DECLARE vDr1  NUMERIC(15,5); 
	DECLARE vCr1  NUMERIC(15,5);
	DECLARE vcurrencies_id INT;
	DECLARE vDr2  NUMERIC(15,5);
	DECLARE vCr2  NUMERIC(15,5);

	
	DECLARE vcostcenters_code CHAR(50) ;
   DECLARE Done1 BIT DEFAULT false;  
   DECLARE curLoans CURSOR FOR SELECT SUM(amtprovided) amtprovided,product_prodid,currencies_id FROM loans_out_table_final1 GROUP BY  product_prodid,currencies_id;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET Done1:= true; 
	
	
	-- GET BASE-CURRENCY
	SELECT c.configuration_value INTO @bcurrency FROM configuration c WHERE c.configuration_key='SETTTING_CURRENCY_ID' AND c.branch_code=branch_code GROUP BY c.branch_code;

	IF pDate IS NULL THEN	
		SET pDate = DATE(NOW());	
	END IF;
	
	-- first get outtsnding balances
	DROP TABLE IF EXISTS loans_out_table_final;
	
	
	CALL `sp_get_outstanding_loan_balances`(branch_code, '', '', '','','',pDate, '','', '', '', '', '', '','','', '', '', true,'','');
	
	-- GET NUMBER OF DAYS THE LOAN ARE IN ARREARS	
	CALL `sp_get_days_in_arrears`('', '', '', '', pDate);
	
	DROP TABLE IF EXISTS loans_out_table_final1;
	
	-- CALCULATE PROVISIONS
	CREATE TEMPORARY TABLE loans_out_table_final1 AS (SELECT loan_number,	(CASE 
			WHEN arrdays <=class1b THEN (pbalance*(class1per/100)) 
			WHEN arrdays <=class2b THEN (pbalance*(class2per/100))
			WHEN arrdays <=class3b THEN (pbalance*(class3per/100))
			WHEN arrdays <=class4b THEN (pbalance*(class4per/100))
			WHEN arrdays >=class5a THEN (pbalance*(class5per/100))
			ELSE 0 END) AS amtprovided,costcenters_code,l.product_prodid,productconfig_value as currencies_id,arrdays,pbalance FROM loans_out_table_final l LEFT JOIN productconfig  p ON p.productconfig_value AND  p.productconfig_paramname='CURRENCIES_ID'  AND arrdays > 0 GROUP BY loan_number);
	
	-- GET CURRENCY OF PRODUCT	
	SELECT  CASE WHEN plang='EN' THEN translations_eng 
		WHEN plang='SP' THEN translations_sp 
		WHEN plang='FR' THEN translations_fr 
		WHEN plang='SWA' THEN translations_swa  END INTO @descip
	FROM translations WHERE translations_id='105';	
			
	DROP TABLE IF EXISTS sp_calculate_provisions_provacc;
	
	-- GET PROVISION ACCOUNTS	
	CREATE TEMPORARY TABLE sp_calculate_provisions_provacc AS (SELECT product_prodid,productconfig_value FROM productconfig	WHERE productconfig_paramname='PROV_BAD_DEBTS_ACC' GROUP BY product_prodid);
		
	-- GET BALANCES ON GL
	DROP TABLE IF EXISTS sp_calculate_provisions_glacc;
		
	CREATE TEMPORARY TABLE sp_calculate_provisions_glacc AS (SELECT p.product_prodid,COALESCE(g.chartofaccounts_accountcode,productconfig_value) acc, SUM(COALESCE(generalledger_debit,0)) - SUM(COALESCE(generalledger_credit,0)) bal FROM sp_calculate_provisions_provacc p LEFT JOIN generalledger g ON p.productconfig_value=g.chartofaccounts_accountcode GROUP BY p.product_prodid,g.chartofaccounts_accountcode);
	

	-- DROP TABLE IF EXISTS loans_out_table_final2;	  
		
	-- CREATE TEMPORARY TABLE loans_out_table_final2 AS (SELECT l.* FROM loans_out_table_final1 l LEFT JOIN sp_calculate_provisions_glacc p ON p.product_prodid=l.product_prodid GROUP BY p.product_prodid);

	DROP TABLE IF EXISTS sp_calculate_provisions_provcost;	
	
	-- GET PROVISION COSTS
	CREATE TEMPORARY TABLE sp_calculate_provisions_provcost AS (SELECT product_prodid,productconfig_value acc FROM  productconfig	WHERE productconfig_paramname='PROV_COST_ACC' GROUP BY product_prodid);  
	

	
	SET  vadjbal =0;
	
	IF vpost='1' THEN	
	
	OPEN curLoans; 
	read_loop: LOOP

	FETCH curLoans INTO vAmount,vproduct_prodid,vcurrencies_id;
		
	IF Done1 =true THEN
		LEAVE read_loop;
	END IF;
	
	-- GET TRANSACTIN CODE
	CALL `sp_generate_transactioncode`(user_id, @newtcode);
	
	-- CHECK SEE IF PROVISIONS ARE ALREADY POSTED FOR PERIOD
	SELECT p.productconfig_value INTO @lpdate FROM  productconfig p WHERE p.productconfig_paramname='LAST_PROVISION_DATE' AND p.product_prodid=vproduct_prodid GROUP BY p.product_prodid;
	
	IF  @lpdate>=pDate THEN
			SELECT 3 INTO @results;
			LEAVE read_loop;	
	END IF;
		 
	-- get exchange rates
	IF vcurrencies_id <> @bcurrency THEN
	
 		DROP TABLE IF EXISTS sp_get_exchange_rate_table1;
		
		CALL sp_get_exchange_rate(branch_code,pDate,vcurrencies_id);
			
		SELECT forexrates_midrate,forexrates_id INTO @rate,@forexrates_id FROM sp_get_exchange_rate_table1;
			
	END IF ;

	SELECT COALESCE(acc,''), COALESCE(bal,0) INTO @paccount, @pcurbal FROM sp_calculate_provisions_glacc WHERE product_prodid=vproduct_prodid;

	SELECT  COALESCE(acc,'') INTO @caccount  FROM sp_calculate_provisions_provcost WHERE product_prodid=vproduct_prodid;	

	
	

	SET vAmount =0;
	
	SET vPost = true;	
	
	-- CHECK SEE IF THERE IS A BALANCE TO POST
	IF @curbal > 0 THEN		
	
	
		IF @pcurbal ='' OR @caccount ='' THEN
			SELECT 2 INTO @results;
			LEAVE read_loop;
		END IF;
	
		SET  vDr1 = 0;
		
		SET  vCr1 = (@curbal - vAmount)*@rate;		
						
		SET vAmount =vCr1;
		
		UPDATE sp_calculate_provisions_glacc SET bal=(bal-vAmount) WHERE product_prodid=vproduct_prodid;
		
	ELSEIF @curbal < 0 THEN	
		
		SET  vDr1 = ABS(@curbal - vAmount)*@rate;	
		SET vAmount = vDr1;
		UPDATE sp_calculate_provisions_glacc SET bal=0  WHERE product_prodid=vproduct_prodid;
		
	ELSEIF @curbal = 0 THEN
	
		SET vPost = false;	
		
	END IF;
	
	SET  vDr2 = vCr1;
	SET  vCr2 = vDr1;
		
	-- POST PROVISION
	

		INSERT INTO generalledger(
					generalledger_id,			   	
					transactioncode,		
					generalledger_description,
					fund_code,
					donor_code,
					generalledger_credit,
					generalledger_voucher,
					user_id,
					generalledger_tday,
					generalledger_debit,
					chartofaccounts_accountcode,
					generalledger_updated,
					branch_code,
					trancode,				
					currencies_id,
					client_idno,
					product_prodid,
					forexrates_id,
					costcenters_code,
					generalledger_fcamount)
		SELECT UUID(),
					@newtcode,
					@descip,
					'0000',
					'00000',
					vCr1,
					'',
					user_id,	
					pDate,
					vDr1,
				   @paccount,
					NOW(),
					branch_code,
					'PB000',
					l.currencies_id,
					'',
					l.product_prodid,
					@forexrates_id,
					'000',
					IF(l.currencies_id<>@bcurrency, vAmount,0)
					FROM loans_out_table_final1 l LEFT JOIN sp_calculate_provisions_provacc p ON p.product_prodid=l.product_prodid;	
	
		INSERT INTO generalledger(
					generalledger_id,		   	
					transactioncode,		
					generalledger_description,
					fund_code,
					donor_code,
					generalledger_credit,
					generalledger_voucher,
					user_id,
					generalledger_tday,
					generalledger_debit,
					chartofaccounts_accountcode,
					generalledger_updated,
					branch_code,
					trancode,				
					currencies_id,
					client_idno,
					product_prodid,
					forexrates_id,
					costcenters_code,
					generalledger_fcamount)
			SELECT UUID(), 
					@newtcode,
					@descip,
					'0000',
					'00000',
					vCr2,
					'',
					user_id,	
					pDate,
					vDr2,
				   @caccount,
					NOW(),
					branch_code,
					'PB000',
					l.currencies_id,
					'',
					l.product_prodid,
					@forexrates_id,
					'000',
					IF(l.currencies_id<>@bcurrency, vAmount,0)
					FROM loans_out_table_final1 l LEFT JOIN sp_calculate_provisions_provcost p ON p.product_prodid=l.product_prodid;
					
					UPDATE productconfig SET productconfig_value = pDate WHERE productconfig_paramname='LAST_PROVISION_DATE' AND product_prodid=vproduct_prodid;
					
					UPDATE productconfig SET productconfig_value =@newtcode WHERE productconfig_paramname='LAST_PROVISION_TCODE' AND product_prodid=vproduct_prodid;
	
	 	SELECT 1 INTO @results;
	 
	END LOOP read_loop;
	
	CLOSE curLoans;
	
	
	
	END IF;
	
	IF vpost='0' THEN	
		SELECT l.loan_number,c.client_idno, CONCAT(c.client_firstname,c.client_middlename,c.client_surname) name ,c.product_prodid,l.pbalance,l.arrdays,l.amtprovided FROM loans_out_table_final1 l,loans_out_table_final c WHERE c.loan_number=l.loan_number;
		
	ELSE

		SELECT @results id;
	
	END IF;
	
	
	
	

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_check_if_exists
DROP PROCEDURE IF EXISTS `sp_check_if_exists`;
DELIMITER //
CREATE PROCEDURE `sp_check_if_exists`(
	IN `branch_code` TINYTEXT,
	IN `theid1` CHAR(50),
	IN `theid2` CHAR(50),
	IN `idtype` CHAR(50)




)
BEGIN

	DECLARE cVal1 CHAR(50);
	DECLARE cVal2 CHAR(50);
	DECLARE cVal3 CHAR(50);
	DECLARE cVal4 CHAR(50);
	DECLARE cVal5 CHAR(50);
	DECLARE cVal6 CHAR(50);
	
	IF idtype='LOANNO' THEN 
		SELECT SQL_CALC_FOUND_ROWS ls.loan_number,ls.loan_amount,l.product_prodid,l.client_idno,fund_code,donor_code INTO @cVal1,@cVal2,@cVal3,@cVal4,@cVal5,@cVal6   FROM loan l,loanstatuslog ls WHERE l.loan_number=ls.loan_number AND ls.loan_status='AP'   AND ls.loan_number =theid1;
		SELECT FOUND_ROWS() INTO  @nCount;
		IF @nCount > 0 THEN
				SELECT '1' response,@cVal1 lnr,@cVal2 amt,@cVal3 prodid,@cVal4 cid,@cVal5 fcode,@cVal6 dcode;				
		 		
		ELSE
				SELECT '0' response,'' lnr,0 amt,'' prodid,'' cid,'' fcode,'' dcode;
		END IF;
		
	ELSEIF idtype='SAVEDETAILS' THEN
		
		SELECT client_idno  FROM savaccounts WHERE savaccounts_account=theid1 AND product_prodid=theid2; 
		
	ELSEIF idtype='BANKDETAILS' THEN
	
		SELECT bankaccounts_id, bankaccounts_accno,chartofaccounts_accountcode,bankbranches_id  FROM bankaccounts; 
		
	ELSEIF idtype='GLACC' THEN
	
		SELECT chartofaccounts_accountcode INTO @cVal1  FROM chartofaccounts WHERE chartofaccounts_accountcode=theid1; 
		IF @cVal1 !='' THEN
			SELECT '1' response,@cVal1 acc;
		ELSE
			SELECT '0' response,@cVal1 acc;
		END IF;
		
	ELSEIF idtype='PRODPARA' THEN

	SELECT COALESCE(productconfig_value,''),COALESCE(productconfig_ind,''),COALESCE(productconfig_grp,'') INTO @cVal1,@cVal2,@cVal3 FROM productconfig WHERE productconfig_paramname IN(theid1) AND product_prodid = theid2;
		
		IF @cVal1 ='' AND @cVal2='' AND @cVal3='' THEN
			SELECT '0' response,@cVal1 val,@cVal2 ind,@cVal3 grp;	
		ELSE
			SELECT '1' response,@cVal1 val,@cVal2 ind,@cVal3 grp;	
		END IF;
			
	END IF;
	

	
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_drop_tables
DROP PROCEDURE IF EXISTS `sp_drop_tables`;
DELIMITER //
CREATE PROCEDURE `sp_drop_tables`(IN `tablenames` TINYTEXT)
BEGIN

 DROP TABLE IF EXISTS tablenames;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_dynamic_sql
DROP PROCEDURE IF EXISTS `sp_dynamic_sql`;
DELIMITER //
CREATE PROCEDURE `sp_dynamic_sql`(
	IN `dynamicsqluuid` VARCHAR(100)




































)
    COMMENT 'This update database using dynamic sql'
BEGIN


	 	  	SELECT xmltrans_keyvalues,xmltrans_data,xmltrans_table INTO @keyvals,@squery,@ttable FROM xmltrans WHERE 	xmltrans_dynamicsqlid = dynamicsqluuid;	
	 	
			CASE UPPER(@ttable)
				WHEN 'DUES' THEN
						
						DELETE FROM xdues  WHERE  FIND_IN_SET(QUOTE(loan_number),@keyvals)>0; 
						 		
						INSERT INTO xdues  (due_id, due_principal,due_interest,due_penalty,due_commission,due_vat,due_date,loan_number,members_idno) SELECT (@row_number:=@row_number+1)due_id,due_principal,due_interest,due_penalty,due_commission,due_vat,due_date,loan_number,members_idno FROM dues,(SELECT @row_number:=0) AS t WHERE  FIND_IN_SET(QUOTE(loan_number),@keyvals)>0;			 					 				 					
				
						PREPARE stmt FROM @squery;
		      		
		   			EXECUTE stmt ;
						
		   			DEALLOCATE PREPARE stmt;
											
		      WHEN 'LOANPAYMENTS' THEN
		      
		      		PREPARE stmt FROM @squery;
		      		
		   			EXECUTE stmt ;
						
		   			DEALLOCATE PREPARE stmt;
		   
		    		
					   DROP TABLE IF EXISTS sp_trans_table1;
		    		
		      	   CREATE TEMPORARY TABLE sp_trans_table1 AS (SELECT SQL_CALC_FOUND_ROWS transactioncode,loan_number  FROM loanpayments  WHERE  FIND_IN_SET(QUOTE(transactioncode),@keyvals)>0);
			
		     
		     		   SET @numrows = FOUND_ROWS();
		     		
					   SET @skip =0;
									
		      	
		      	WHILE(@skip < @numrows) DO		      	
		      		
		      		DROP TABLE IF EXISTS sp_trans_table2;
		      		
		      		SET @s_query ='CREATE TEMPORARY TABLE sp_trans_table2 AS (SELECT t1.loan_number,loanpayments_date,loanpayments_principal,loanpayments_interest FROM loanpayments t1,sp_trans_table1 t2 WHERE t1.loan_number=t2.loan_number AND t1.transactioncode=t2.transactioncode LIMIT ?,1)'; 
		      		
		      		PREPARE stmt FROM @s_query;
		      		
		      		EXECUTE stmt USING @skip;
						
						DEALLOCATE PREPARE stmt;
												
					--	SELECT t1.loan_number,loanpayments_date,loanpayments_principal,loanpayments_interest FROM loanpayments t1,sp_trans_table1 t2 WHERE t1.loan_number=t2.loan_number AND t1.transactioncode=t2.transactioncode LIMIT 4,1;
							
		      		SELECT loan_number,loanpayments_date,loanpayments_principal,loanpayments_interest INTO @Lnr,@ddate,@princ,@tint FROM sp_trans_table2;
						
					--	SELECT @Lnr, @ddate, @princ, @tint;					
							      			 
		      --		CALL `sp_pull_future_dues`(@Lnr, @ddate, @princ, @tint, '0', '0', '0', '');
		      		
						SET @skip = @skip + 1;
		      		
		     		END WHILE;
	
		      ELSE
		        BEGIN
		        		PREPARE stmt FROM @squery;
		      		
		   			EXECUTE stmt ;
						
		   			DEALLOCATE PREPARE stmt;
		   
		        END;
		    END CASE;
	

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_generate_id
DROP PROCEDURE IF EXISTS `sp_generate_id`;
DELIMITER //
CREATE PROCEDURE `sp_generate_id`(
	IN `branch_code` TINYTEXT,
	IN `id_type` CHAR(10),
	IN `sub_type` CHAR(50),
	IN `ccode` CHAR(20),
	OUT `theid` CHAR(50)
)
BEGIN

		DECLARE the_id CHAR(100) DEFAULT '0';

		DECLARE bexitloop BIT DEFAULT FALSE;
	
		START TRANSACTION;
	
		DELETE FROM identifications WHERE identifications_idno IS NULL;
		
	
	-- GET LAST ID
	 SELECT SQL_CALC_FOUND_ROWS  d.identifications_idno INTO @the_id FROM identifications d WHERE TRIM(d.identifications_idtype)=id_type AND TRIM(d.branchcode)=branch_code AND TRIM(d.identifications_subtype)=sub_type ;
		
	
	 
	 SELECT FOUND_ROWS() INTO @cnt;
	 	

		
	 WHILE bexitloop = false  DO
	 
	
		
		IF @cnt= 0 THEN
			SET @the_id = 1;					
		END IF; 
		
	--	SET @cnt = 1;
			 
		IF  id_type='LOANNO' OR id_type='TIMEDEP' THEN		
			SET theid = CONCAT(branch_code,'/',repeat('0',6-LENGTH(@the_id)),@the_id);			
		ELSE		
			SET theid = CONCAT(branch_code,'/',id_type,'/',repeat('0',6-LENGTH(@the_id)),@the_id);	
		END IF;
		
	
		IF sub_type='SAVACC' THEN
		
			IF @the_id >= 1 THEN
		
				SELECT COALESCE(MAX(CAST(substr(savaccounts_account,6,LENGTH(savaccounts_account))AS DECIMAL(15))),0)+1 INTO @the_id FROM savaccounts WHERE LEFT(savaccounts_account,2)=branch_code AND substr(savaccounts_account,4,1)=id_type ;			
				
				SET theid = CONCAT(branch_code,'/',id_type,'/',repeat('0',6-LENGTH(@the_id)),@the_id);
				
											
				SET bexitloop = true;
				
			ELSE
			
				SELECT COUNT(client_idno) INTO @idexits FROM savaccounts WHERE LEFT(savaccounts_account,2)=branch_code AND substr(savaccounts_account,4,1)=id_type AND savaccounts_account=theid ;
					
				
				-- check if selcted ID is laready used
				IF @idexits > 0 THEN
					SELECT COUNT(client_idno) INTO @idexits FROM savaccounts WHERE LEFT(savaccounts_account,2)=branch_code AND substr(savaccounts_account,4,1)=id_type AND savaccounts_account=theid ;	
					
					SET @the_id = @idexits+1;
				
					SET theid = CONCAT(branch_code,'/',id_type,'/',repeat('0',6-LENGTH(@the_id)),@the_id);
					
				ELSE
					SET @the_id = 1;					
					SET theid = CONCAT(branch_code,'/',id_type,'/',repeat('0',6-LENGTH(@the_id)),@the_id);
				END IF;
				
			
				IF @idexits= 0 THEN				
					SET bexitloop = true;
				ELSE
					SET @the_id = @the_id + 1;
				END IF;
				
			END IF;		
			
		END IF;
		IF sub_type='CLIENT' THEN
		
			IF @the_id >= 1 THEN
		
				SELECT COALESCE(MAX(CAST(substr(client_idno,6,LENGTH(client_idno))AS DECIMAL(15))),0)+1 INTO @the_id FROM clients WHERE TRIM(branch_code)=branch_code AND client_type='I' ;			
				
				SET theid = CONCAT(branch_code,'/',id_type,'/',repeat('0',6-LENGTH(@the_id)),@the_id);
				
							
				SET bexitloop = true;
				
			ELSE
			
				SELECT COUNT(client_idno) INTO @idexits FROM clients WHERE branch_code=branch_code AND id_type='I' AND client_idno=theid ;
					
				
				-- check if selcted ID is laready used
				IF @idexits > 0 THEN
					SELECT COUNT(client_idno) INTO @idexits FROM clients where client_type='I' AND branch_code=branch_code ;	
					
					SET @the_id = @idexits+1;
				
					SET theid = CONCAT(branch_code,'/',id_type,'/',repeat('0',6-LENGTH(@the_id)),@the_id);
					
				ELSE
					SET @the_id = 1;					
					SET theid = CONCAT(branch_code,'/',id_type,'/',repeat('0',6-LENGTH(@the_id)),@the_id);
				END IF;
				
			
				IF @idexits= 0 THEN				
					SET bexitloop = true;
				ELSE
					SET @the_id = @the_id + 1;
				END IF;
				
			END IF;
		
			SET id_type = 'I';
		END IF;
		
	
		IF sub_type='GROUP' OR sub_type='BUSINESS'  THEN
		
			IF @the_id >= 1 THEN
				SELECT COALESCE(MAX(CAST(substr(entity_idno,6,LENGTH(entity_idno))AS DECIMAL(15))),0)+1 INTO @the_id FROM entity WHERE branch_code=branch_code AND entity_type=id_type;
				
				SET theid = CONCAT(branch_code,'/',id_type,'/',repeat('0',6-LENGTH(@the_id)),@the_id);
				
							
				SET bexitloop =true;
				
			ELSE
			
			  SELECT COUNT(entity_idno) INTO @idexits FROM entity where entity_idno=theid AND  entity_type=id_type;
			
				-- check if selcted ID is laready used
				IF @idexits > 0 THEN
					SELECT COUNT(entity_idno) INTO @idexits FROM entity where entity_idno=theid AND  entity_type=id_type;	
				ELSE
					SET @the_id = 1;					
					SET theid = CONCAT(branch_code,'/',id_type,'/',repeat('0',6-LENGTH(@the_id)),@the_id);
				END IF;
				
				
				IF @idexits= 0 THEN				
					SET bexitloop = true;
				ELSE
					SET @the_id = @the_id + 1;
				END IF;
			
			END IF;
			
			SET id_type =id_type;
		END IF;
		
		IF sub_type='MEMBER' THEN
		
			
				
			IF @the_id>= 1 THEN
			
				SELECT COALESCE(MAX(CAST(substr(members_idno,6,LENGTH(members_idno))AS DECIMAL(15))),0)+1 INTO @the_id FROM members  WHERE  members.entity_idno=ccode;		
				
			
				
				SET theid = CONCAT(branch_code,'/',id_type,'/',repeat('0',6-LENGTH(@the_id)),@the_id);
													
				SET bexitloop = true;
				
			ELSE
							
				
				SELECT COUNT(members_idno) INTO @idexits FROM members WHERE members.entity_idno=ccode;
				
			
				IF @idexits > 0 THEN	
					-- check if selcted ID is laready used			
					SELECT COUNT(members_idno) INTO @idexits FROM members where TRIM(members_idno)=TRIM(theid) and members.entity_idno=ccode;
				
				ELSE
					SET @the_id = 1;
					
					SET theid = CONCAT(branch_code,'/',id_type,'/',repeat('0',6-LENGTH(@the_id)),@the_id);			
				END IF;					
			
				
				IF @idexits = 0 THEN				
					SET bexitloop = true;
				ELSE
					SET @the_id = @the_id + 1;
				END IF;
			
			END IF;
			
			SET id_type ='M';
			
		END IF;
		
		
		-- MEMBER NO
		IF sub_type='MEMBERNO'  THEN
		

			IF @the_id = 1 THEN
			
				SELECT COALESCE(MAX(CAST(members_no AS DECIMAL(15))),0)+1 INTO @the_id FROM members WHERE entity_idno=ccode AND  TRIM(members_no)=TRIM(theid) ;		
				
				SET theid = CONCAT(repeat('0',4-LENGTH(@the_id)),@the_id);
													
				SET bexitloop = true;
				
			ELSE
							
				
				SELECT COUNT(members_no) INTO @idexits FROM members WHERE entity_idno=ccode AND  TRIM(members_no)=TRIM(theid) ;		
			
				IF @idexits > 0 THEN	
					-- check if selcted ID is laready used			
					SELECT COUNT(members_no) INTO @idexits FROM members where TRIM(members_no)=TRIM(theid) and entity_idno=ccode;
				
				ELSE
					SET @the_id = 1;
					
					SET theid = CONCAT(repeat('0',4-LENGTH(@the_id)),@the_id);			
				END IF;					
			
				
				IF @idexits = 0 THEN				
					SET bexitloop = true;
				ELSE
					SET @the_id = @the_id + 1;
				END IF;
			
			END IF;
				
			SET id_type ='MNO';
			
		END IF;
		
		
		
		IF  sub_type='LOANNO' THEN
		
			IF @the_id = 1 THEN
				
			SELECT COALESCE(MAX(CAST(substr(loan_number,5,LENGTH(loan_number))AS DECIMAL(15))),0)+1 INTO @the_id FROM loan WHERE branch_code=branch_code;
			
			SET theid = CONCAT(branch_code,'/',repeat('0',6-LENGTH(@the_id)),@the_id);
		
				
			SET bexitloop =true;
			
			ELSE
				SELECT COUNT(loan_number) INTO @idexits FROM loan where loan_number=theid;	
				
				IF @idexits= 0 THEN				
					SET bexitloop = true;
				ELSE
					SET @the_id = @the_id + 1;
				END IF;
				
			END IF;
		END IF;
		
		IF  sub_type='TIMEDEP' THEN
		
			IF @the_id = 1 THEN
				
				SELECT COALESCE(MAX(CAST(substr(timedeposit_number,5,LENGTH(timedeposit_number))AS DECIMAL(15))),0)+1 INTO @the_id FROM timedeposit WHERE branch_code=branch_code;
			
				SET theid = CONCAT(branch_code,'/',repeat('0',6-LENGTH(@the_id)),@the_id);		
				
				SET bexitloop = true;
			
			ELSE
			
				-- CHECK SEE IF THIS ID EXISTS
				SELECT COUNT(timedeposit_number) INTO @idexits FROM timedeposit where timedeposit_number=theid;	
			
		 
				IF @idexits = 0 THEN				
					SET bexitloop = true;
				ELSE
					SET @the_id = @the_id + 1;
				END IF;
					
			END IF;
			
		END IF;
		
		
	END WHILE;
		DELETE FROM identifications WHERE  identifications_idtype=TRIM(id_type) AND TRIM(branchcode)=TRIM(branch_code) AND identifications_subtype=TRIM(sub_type) ;
	
		INSERT INTO identifications (branchcode,identifications_idno,identifications_idtype,identifications_subtype) VALUES (branch_code,@the_id,id_type,sub_type);
COMMIT;
	
	
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_generate_id_wrapper
DROP PROCEDURE IF EXISTS `sp_generate_id_wrapper`;
DELIMITER //
CREATE PROCEDURE `sp_generate_id_wrapper`(
	IN `branch_code` TINYTEXT,
	IN `id_type` CHAR(10),
	IN `sub_type` CHAR(50)
,
	IN `ccode` CHAR(20)

)
BEGIN
	DECLARE theid CHAR(100) DEFAULT '';

	CALL `sp_generate_id`(branch_code, id_type,sub_type,ccode, @theid);
	SELECT @theid AS id;
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_generate_transactioncode
DROP PROCEDURE IF EXISTS `sp_generate_transactioncode`;
DELIMITER //
CREATE PROCEDURE `sp_generate_transactioncode`(IN `userid` CHAR(50), IN `branch_code` CHAR(50), OUT `newtcode` CHAR(50))
BEGIN

	SELECT (user_lasttcode + 1),user_usercode INTO @new_tcode, @usercode FROM users WHERE user_id = userid;

	UPDATE users SET user_lasttcode=@new_tcode WHERE user_id = userid;

	SET newtcode = CONCAT(branch_code,@usercode,YEAR(NOW()),@new_tcode);
	
	SELECT newtcode;
	

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_generate_transactioncode_ui
DROP PROCEDURE IF EXISTS `sp_generate_transactioncode_ui`;
DELIMITER //
CREATE PROCEDURE `sp_generate_transactioncode_ui`(IN `userid` CHAR(50), IN `branch_code` CHAR(50))
BEGIN

	SELECT (user_lasttcode + 1),user_usercode INTO @new_tcode, @usercode FROM users WHERE user_id = userid;

	UPDATE users SET user_lasttcode=@new_tcode WHERE user_id = userid;

	SET @newtcode = CONCAT(@usercode,YEAR(NOW()),@new_tcode);
	
	SELECT @newtcode id;
	

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_all_exchange_rates
DROP PROCEDURE IF EXISTS `sp_get_all_exchange_rates`;
DELIMITER //
CREATE PROCEDURE `sp_get_all_exchange_rates`(IN `tdate` CHAR(10))
    COMMENT 'Gets all exchange rates'
BEGIN

	DECLARE rowexists BIGINT;
	
	DECLARE basecurrencies_id BIGINT;
	

	
			DROP TABLE IF EXISTS sp_get_all_exchange_rates_table1;
			CREATE TEMPORARY TABLE sp_get_exchange_rates_table1 AS (SELECT   p.product_prodid,COALESCE(forexrates_id,0) forexrates_id, COALESCE(forexrates_midrate,1) rate,p.currencies_id FROM forexrates r,productcurrencies p WHERE r.forexrates_date<=tdate  AND p.currencies_id=r.currencies_id GROUP BY r.currencies_id,forexrates_id ORDER BY p.product_prodid, r.currencies_id,r.forexrates_id,r.forexrates_date);
	
	
	SELECT COUNT(*) INTO @rowexists FROM sp_get_all_exchange_rates_table1;
	
	
	IF @rowexists=0 THEN
	
		DROP TABLE IF EXISTS sp_get_all_exchange_rates_table1;
		
		CREATE TEMPORARY TABLE sp_get_all_exchange_rates_table1 AS (SELECT 0 forexrates_id,1 forexrates_midrate,0 currencies_id);
	
	END IF;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_balance_sheet
DROP PROCEDURE IF EXISTS `sp_get_balance_sheet`;
DELIMITER //
CREATE PROCEDURE `sp_get_balance_sheet`(
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `branch_codefr` TINYINT,
	IN `branch_codeto` TINYTEXT,
	IN `costcenters_codefr` CHAR(50),
	IN `costcenters_codeto` INT,
	IN `product_prodidfr` CHAR(50),
	IN `product_prodidto` CHAR(50),
	IN `accountcodefr` CHAR(20),
	IN `accountcodeto` INT,
	IN `donor_codefr` CHAR(10),
	IN `donor_codeto` INT,
	IN `trancodes_codefr` CHAR(50),
	IN `trancodes_codeto` CHAR(50),
	IN `currencies_id` INT,
	IN `user_idfr` CHAR(50),
	IN `user_idto` CHAR(50),
	IN `group_by` CHAR(50),
	IN `order_by` CHAR(50),
	IN `plang` CHAR(10)





)
BEGIN

DECLARE cDescription VARCHAR(5) DEFAULT '';
-- set language for oprning balance description
 CASE plang  WHEN 'EN' THEN SET @cDescription :='Profit and Loss' ;
 WHEN 'FR' THEN SET @cDescription :='Profit et Perte';
 WHEN 'SP' THEN SET @cDescription :='';
 ELSE SET @cDescription:= 'Profit and Loss' ;
END CASE;


CALL `sp_get_transactions`(startDate,endDate, branch_codefr, branch_codeto, costcenters_codefr, costcenters_codeto, product_prodidfr,product_prodidto, '','', donor_codefr,donor_codeto,trancodes_codefr, trancodes_codeto,currencies_id,user_idfr,user_idto, true,plang);

-- TO DO : TAKE CARE OF MULTICURRENCY 
-- TO DO : ACCOUNTS WITH ZERO BALANCES
-- TO DO: FRENCH COA ACCOUNTS/UK COA ACCOUNTS
-- TO DO: OFF BALANCESHEET ACCOUNTS
-- NOTE: group 9 represents off-balancesheet accounts

/*DO CASE
		
			CASE m.opgActPas = 1
				SELECT cAllYears.* FROM cAllYears, (p_datapath + "Accounts") ;
					WHERE cAllYears.Account == Accounts.Account AND Accounts.tGroup = "1";
				INTO CURSOR cAllYears
				sELECT * FROM  cAllYears INto TABLE (p_TempFilesDir + "cTestA1")
				ActPas = 1			

			CASE m.opgActPas = 2
				SELECT cAllYears.* FROM cAllYears , (p_datapath + "Accounts") ;
					WHERE cAllYears.Account == Accounts.Account AND Accounts.tGroup = "2";
				INTO CURSOR cAllYears

				ActPas = 2			

			CASE m.opgActPas = 3	&& do nothing

				*-- if Off-Balance Sheet Accounts are excluded, Remove accounts with tgroup = 9
				IF m.OffBal = 0
					SELECT cAllYears.* FROM cAllYears, (p_datapath + "Accounts") ;
						WHERE cAllYears.Account == Accounts.Account AND tgroup <> '9';
					INTO CURSOR cAllYears

				ENDIF 
		
				ActPas = 3	
				
		ENDCASE 
		*/
-- sp_get_transactions_2

 DROP TABLE IF EXISTS sp_get_balance_sheet_table1;

-- get opening balances
CREATE TEMPORARY TABLE sp_get_balance_sheet_table1 AS (SELECT c.chartofaccounts_accountcode account,t.branch_code,
SUM(CASE WHEN t.ttype='OP' AND t.generalledger_debit <>0 THEN COALESCE(t.generalledger_debit,0.00) ELSE 0.00 END)odebit,
SUM(CASE WHEN t.ttype='OP'  AND t.generalledger_credit <>0 THEN COALESCE(t.generalledger_credit,0.00) ELSE 0.00 END) ocredit,
SUM(generalledger_debit) AS debit,
SUM(generalledger_credit)  AS credit,
c.chartofaccounts_groupcode groupcode,c.chartofaccounts_name account_label,c.chartofaccounts_tgroup ctype,c.chartofaccounts_header header
FROM chartofaccounts  c  LEFT OUTER JOIN sp_get_transactions_2 t  ON c.chartofaccounts_accountcode=t.chartofaccounts_accountcode GROUP BY c.chartofaccounts_accountcode);


 DROP TABLE IF EXISTS sp_get_transactions_2; 
 
 -- Profit = Income - Expenditure (used to calculate profit) 
 DROP TABLE IF EXISTS sp_get_balance_sheet_profit_table;
 
CREATE TEMPORARY TABLE sp_get_balance_sheet_profit_table AS ( 
 SELECT 'PL' groupcode,
 p.account, 
 p.branch_code,
 p.account_label,
SUM(COALESCE(p.ocredit,0.00))-SUM(COALESCE(p.odebit,0.00)) as cfirst,
SUM(COALESCE(p.credit,0.00)) - SUM(COALESCE(p.debit,0.00)) as  clast,ctype,header
 FROM sp_get_balance_sheet_table1  p WHERE p.ctype > 2 AND p.ctype!=9);
 
 -- assets and liabilities(A = Liabilites + Equity )  
  DROP TABLE IF EXISTS sp_get_balance_sheet_table2;
  
CREATE TEMPORARY TABLE sp_get_balance_sheet_table2 AS ( 
 SELECT groupcode,
 p.account, 
 p.branch_code,
 p.account_label,
(COALESCE(p.odebit,0.00) - COALESCE(p.ocredit,0.00))   cfirst,
 (COALESCE(p.debit,0.00) - COALESCE(p.credit,0.00)) as  clast,ctype,header
 FROM sp_get_balance_sheet_table1  p WHERE (p.ctype < 3 OR p.ctype=9));
 
 



DROP TABLE IF EXISTS sp_get_balance_sheet_table3;
 
-- add header1
CREATE TEMPORARY TABLE sp_get_balance_sheet_table3 AS ( 
SELECT account_label,t.account, t.branch_code,ctype,abs(cfirst)cfirst,abs(clast)clast,groupcode,header,
CASE WHEN CONCAT(SUBSTR(groupcode,1,3),'000000000000000000') = c.chartofaccounts_groupcode THEN c.chartofaccounts_name ELSE  QUOTE('XXXXXXXXXX') END  header1
 FROM sp_get_balance_sheet_table2  t,chartofaccounts c WHERE  CONCAT(SUBSTR(groupcode,1,3),'000000000000000000') = c.chartofaccounts_groupcode);
 
 
  -- add header2
 DROP TABLE IF EXISTS sp_get_balance_sheet_table2;
 DROP TABLE IF EXISTS sp_get_balance_sheet_table4;
 
 
CREATE TEMPORARY TABLE sp_get_balance_sheet_table4 AS (SELECT t.*,
CASE WHEN CONCAT(SUBSTR(groupcode,1,6),'000000000000000') = c.chartofaccounts_groupcode THEN c.chartofaccounts_name ELSE  QUOTE('XXXXXXXXXX') END  header2
 FROM sp_get_balance_sheet_table3  t,chartofaccounts c 	WHERE  CONCAT(SUBSTR(groupcode,1,6),'000000000000000') = c.chartofaccounts_groupcode );
 
 
 -- add header3
 DROP TABLE IF EXISTS sp_get_balance_sheet_table3; 
  DROP TABLE IF EXISTS sp_get_balance_sheet_table5;
 
  
CREATE TEMPORARY TABLE sp_get_balance_sheet_table5 AS (SELECT t.*,
CASE WHEN CONCAT(SUBSTR(groupcode,1,9),'000000000000') = c.chartofaccounts_groupcode THEN c.chartofaccounts_name ELSE  QUOTE('XXXXXXXXXX') END  header3
 FROM sp_get_balance_sheet_table4  t,chartofaccounts c 	WHERE  CONCAT(SUBSTR(groupcode,1,9),'000000000000') = c.chartofaccounts_groupcode );
 
 
 -- add header4
DROP TABLE IF EXISTS sp_get_balance_sheet_table4;  
DROP TABLE IF EXISTS sp_get_balance_sheet_table6;
 
  
CREATE TEMPORARY TABLE sp_get_balance_sheet_table6 AS (SELECT t.*,
CASE WHEN CONCAT(SUBSTR(groupcode,1,12),'000000000') = c.chartofaccounts_groupcode THEN c.chartofaccounts_name ELSE  QUOTE('XXXXXXXXXX') END  header4
 FROM sp_get_balance_sheet_table5  t,chartofaccounts c 	WHERE  CONCAT(SUBSTR(groupcode,1,12),'000000000') = c.chartofaccounts_groupcode );
 
 
   -- add header5
   DROP TABLE IF EXISTS sp_get_balance_sheet_table5; 
 DROP TABLE IF EXISTS sp_get_balance_sheet_table7;
 

CREATE TEMPORARY TABLE sp_get_balance_sheet_table7 AS (SELECT t.*,
CASE WHEN CONCAT(SUBSTR(groupcode,1,15),'000000') = c.chartofaccounts_groupcode THEN c.chartofaccounts_name ELSE  QUOTE('XXXXXXXXXX') END  header5
 FROM sp_get_balance_sheet_table6  t,chartofaccounts c 	WHERE  CONCAT(SUBSTR(groupcode,1,15),'000000') = c.chartofaccounts_groupcode );
 
 
   -- add header6
 DROP TABLE IF EXISTS sp_get_balance_sheet_table6;
DROP TABLE IF EXISTS sp_get_balance_sheet_table8;
 

CREATE TEMPORARY TABLE sp_get_balance_sheet_table8 AS (SELECT t.*,
CASE WHEN CONCAT(SUBSTR(groupcode,1,18),'000') = c.chartofaccounts_groupcode THEN c.chartofaccounts_name ELSE  QUOTE('XXXXXXXXXX') END  header6
 FROM sp_get_balance_sheet_table7  t,chartofaccounts c 	WHERE  CONCAT(SUBSTR(groupcode,1,18),'000') = c.chartofaccounts_groupcode  ORDER BY ctype,account ASC);
 
 DROP TABLE IF EXISTS sp_get_balance_sheet_table7;
 
 DROP TABLE IF EXISTS sp_get_balance_sheet_table9;

CREATE TEMPORARY TABLE sp_get_balance_sheet_table9 AS(SELECT * FROM ( 
SELECT b.groupcode,b.account_label,b.account, b.branch_code,b.cfirst,b.clast,b.header1,b.header2,b.header3,b.header4,b.header5,b.header6,header FROM sp_get_balance_sheet_table8 b
UNION ALL
SELECT p.groupcode,p.account_label, p.account, p.branch_code,p.cfirst,p.clast,'' header1,''  header2,''  header3,''  header4,''  header5,''  header6,header  FROM sp_get_balance_sheet_profit_table p) tt);











SELECT groupcode,
CASE WHEN header='Y'  AND groupcode!='PL' THEN CONCAT('<b>',account_label,'</b>')  
WHEN groupcode='PL'  THEN CONCAT('<b>',upper(@cDescription),'</b>') ELSE CONCAT('   ',account_label)
END account_label,
CASE WHEN groupcode='PL' THEN 'xxxxxxx' ELSE account END account,
CASE WHEN header='Y' AND groupcode!='PL' THEN CONCAT('') ELSE FORMAT(cfirst,3) END cfirst,
CASE WHEN header='Y' AND groupcode!='PL'  THEN CONCAT('') ELSE FORMAT(clast,3) END clast,
 branch_code
FROM sp_get_balance_sheet_table9  WHERE (cfirst!='0' or clast!='0' or header='Y' ) ORDER BY account ASC,groupcode ASC;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_bank_details
DROP PROCEDURE IF EXISTS `sp_get_bank_details`;
DELIMITER //
CREATE PROCEDURE `sp_get_bank_details`(
	IN `bankbranches_id` INT,
	IN `chartofaccounts_accountcode` CHAR(20)
)
    COMMENT 'This procedure is used to get bank details'
BEGIN

	DECLARE bank_mainquery TINYTEXT DEFAULT '';
	DECLARE bank_query TINYTEXT DEFAULT '';

	drop table IF EXISTS sp_get_bank_details_table1 ;

	SET @bank_mainquery = 'CREATE TEMPORARY TABLE sp_get_bank_details_table1 AS (select ba.chartofaccounts_accountcode ,bb.bankbranches_name,bb.bankbranches_id from bankbranches bb,bankaccounts ba WHERE ba.bankbranches_id=bb.bankbranches_id ';
	
	IF bankbranches_id!='' THEN
		SET @bank_query = CONCAT(@bank_query,' AND bankbranches_id =',QUOTE(bankbranches_id));
	END IF;
		
	IF chartofaccounts_accountcode!='' THEN
		SET @bank_query ='';
		SET @bank_query = CONCAT(@bank_query,' AND chartofaccounts_accountcode =',QUOTE(chartofaccounts_accountcode));
	END IF;
	
	SET @bank_query = CONCAT(@bank_query,')');
	
	SET @bank_mainquery = CONCAT(@bank_mainquery,@bank_query);	
	
--	insert into errors select @bank_mainquery;
		   
	PREPARE stmt FROM @bank_mainquery;
		 	
	EXECUTE stmt;
	
	SELECT * FROM sp_get_bank_details_table1;
	
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_breakdown_per_account
DROP PROCEDURE IF EXISTS `sp_get_breakdown_per_account`;
DELIMITER //
CREATE PROCEDURE `sp_get_breakdown_per_account`(
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `branch_codefr` TINYINT,
	IN `branch_codeto` TINYTEXT,
	IN `costcenters_codefr` CHAR(50),
	IN `costcenters_codeto` INT,
	IN `product_prodidfr` CHAR(50),
	IN `product_prodidto` CHAR(50),
	IN `accountcodefr` CHAR(20),
	IN `accountcodeto` INT,
	IN `donor_codefr` CHAR(10),
	IN `donor_codeto` INT,
	IN `trancodes_codefr` CHAR(50),
	IN `trancodes_codeto` CHAR(50),
	IN `currencies_id` CHAR(4),
	IN `user_idfr` CHAR(50),
	IN `user_idto` CHAR(50),
	IN `group_by` CHAR(50),
	IN `order_by` CHAR(50),
	IN `plang` CHAR(10)








)
BEGIN

DECLARE smainquery LONGTEXT DEFAULT '';
DECLARE squery LONGTEXT DEFAULT '';


 CALL `sp_get_transactions`(startDate,endDate, branch_codefr, branch_codeto, costcenters_codefr, costcenters_codeto, product_prodidfr,product_prodidto, accountcodefr,accountcodeto, donor_codefr,donor_codeto,trancodes_codefr, trancodes_codeto,currencies_id,user_idfr,user_idto, true,plang);


	-- transaction_date, account,account_label,tdescription,transaction_code,debit,credit,balance
	-- you must initialise local variables


DROP TABLE IF EXISTS sp_get_breakdown_per_account_table;
	
CREATE TEMPORARY TABLE sp_get_breakdown_per_account_table AS (
SELECT transaction_date,
CASE WHEN generalledger_id IS NULL AND account IS NOT NULL THEN 'ST' 
WHEN generalledger_id IS NULL AND account IS NULL THEN 'GT'
ELSE account END AS account,
account_label,
CASE WHEN generalledger_id IS NULL THEN '' ELSE tdescription  END AS tdescription,
transaction_code,
 debit,
 credit,
product_prodid,costcenters_code,currencies_id,trancode,branch_code,donor_code,fund_code,balance FROM










(SELECT generalledger_id,generalledger_tday transaction_date,CONCAT('<b>',gl.chartofaccounts_accountcode,'</b>') account,ca.chartofaccounts_name account_label,generalledger_description tdescription,transactioncode transaction_code,SUM(generalledger_debit) debit,SUM(generalledger_credit) credit,product_prodid,costcenters_code,gl.currencies_id,trancode,branch_code,donor_code,fund_code,'000000000000000000000000' balance FROM sp_get_transactions_2 gl LEFT OUTER JOIN chartofaccounts ca ON gl.chartofaccounts_accountcode=ca.chartofaccounts_accountcode GROUP BY gl.chartofaccounts_accountcode ,generalledger_id WITH ROLLUP) AS final);

set @prevbalance =0;


	UPDATE sp_get_breakdown_per_account_table AS w
    SET w.balance =  (@prevbalance:= @prevbalance + w.debit-w.credit)   
    WHERE w.account = w.account 
    ORDER BY w.transaction_date,w.account;
    















 SELECT (CASE WHEN account='ST' or account='GT' THEN '' ELSE transaction_date END)transaction_date,account,account_label,tdescription,transaction_code,FORMAT(debit,2) debit,FORMAT(credit,2) credit,product_prodid, costcenters_code,currencies_id,trancode,branch_code,donor_code,fund_code,FORMAT(balance,2) balance FROM sp_get_breakdown_per_account_table;




END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_check_debit_credit
DROP PROCEDURE IF EXISTS `sp_get_check_debit_credit`;
DELIMITER //
CREATE PROCEDURE `sp_get_check_debit_credit`(
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `branch_codefr` TINYINT,
	IN `branch_codeto` TINYTEXT,
	IN `costcenters_codefr` CHAR(50),
	IN `costcenters_codeto` INT,
	IN `product_prodidfr` CHAR(50),
	IN `product_prodidto` CHAR(50),
	IN `accountcodefr` CHAR(20),
	IN `accountcodeto` INT,
	IN `donor_codefr` CHAR(10),
	IN `donor_codeto` INT,
	IN `trancodes_codefr` CHAR(50),
	IN `trancodes_codeto` CHAR(50),
	IN `currencies_id` INT,
	IN `user_idfr` CHAR(50),
	IN `user_idto` CHAR(50),
	IN `group_by` CHAR(50),
	IN `order_by` CHAR(50),
	IN `plang` CHAR(10)
)
    COMMENT 'THis stored procedure is used to check the validity of douple entry in the general ledger table'
BEGIN

DECLARE smainquery LONGTEXT DEFAULT '';
DECLARE squery LONGTEXT DEFAULT '';


-- Sub Total
SELECT  CASE WHEN plang='EN' THEN translations_eng 
WHEN plang='SP' THEN translations_sp 
WHEN plang='FR' THEN translations_fr 
WHEN plang='SWA' THEN translations_swa  END INTO @descip_sub
FROM translations WHERE translations_id='1324';

-- Grand Total
SELECT  CASE WHEN plang='EN' THEN translations_eng 
WHEN plang='SP' THEN translations_sp 
WHEN plang='FR' THEN translations_fr 
WHEN plang='SWA' THEN translations_swa  END INTO @descip_grand
FROM translations WHERE translations_id='1323';


DROP TABLE IF EXISTS sp_get_check_debit_credit_table1;

	SET @smainquery = CONCAT('CREATE TEMPORARY TABLE sp_get_check_debit_credit_table1 AS (SELECT * FROM (SELECT transactioncode,COALESCE(SUM(generalledger_credit),0) credit,COALESCE(SUM(generalledger_debit),0) debit FROM generalledger');
	

	SET @squery ='';
	
	IF accountcodefr!='' AND accountcodefr IS NOT NULL AND accountcodeto!='' AND accountcodeto IS NOT NULL  THEN 	
 		SET @squery = CONCAT(@squery,' chartofaccounts_accountcode BETWEEN ',QUOTE(accountcodefr),' AND ',QUOTE(accountcodeto));
 	END IF;
 	
 	IF donor_codefr!='' AND donor_codefr IS NOT NULL AND donor_codeto!='' AND donor_codeto IS NOT NULL  THEN 	
 		
		 IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
		
		 SET @squery = CONCAT(@squery,' chartofaccounts_accountcode BETWEEN',QUOTE(donor_codefr),' AND ',QUOTE(donor_codeto));
 	END IF;
 	
	IF branch_codefr!='' AND branch_codeto!=''  AND branch_codeto IS NOT NULL  AND branch_codefr IS NOT NULL  THEN 
		IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
			
 		SET @squery = CONCAT(@squery,' branch_code BETWEEN',QUOTE(branch_codefr),' AND ',QUOTE(branch_codeto));
 	END IF;
 	
 	IF  startDate IS NOT NULL THEN
 		SET endDate = startDate;
 	END IF ;
 	
 	IF endDate IS NOT NULL  THEN
 		SET startDate =endDate;
 	END IF ;
	
	IF startDate!='0000-00-00'  AND startDate IS NOT NULL AND endDate!='0000-00-00'  AND endDate IS NOT NULL  THEN 	
		IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
		
 		SET @squery = CONCAT(@squery,' DATE(generalledger_tday)  BETWEEN ',QUOTE(startDate),' AND ',QUOTE(endDate));
 	END IF;
 	
 	
 	IF product_prodidfr!=''  AND product_prodidfr IS NOT NULL AND product_prodidto!=''  AND product_prodidto IS NOT NULL  THEN	
 		IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
		
 		SET @squery = CONCAT(@squery,' product_prodid BETWEEN ',QUOTE(product_prodidfr),' AND ',QUOTE(product_prodidto));
 	END IF;
 	
 	
 		IF costcenters_codefr!=''  AND costcenters_codefr IS NOT NULL AND costcenters_codeto!=''  AND costcenters_codeto IS NOT NULL  THEN	
 		IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
		
 		SET @squery = CONCAT(@squery,' costcenters_code BETWEEN ',QUOTE(costcenters_codefr),' AND ',QUOTE(costcenters_codeto));
 	END IF;
 	
 	IF user_idfr!=''  AND user_idfr IS NOT NULL AND user_idto!=''  AND user_idto IS NOT NULL  THEN	
 		IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
		
 		SET @squery = CONCAT(@squery,' user_id  BETWEEN ',QUOTE(user_idfr),' AND ',QUOTE(user_idto));
 	END IF;
 	
 	
 	IF currencies_id!=''  AND currencies_id IS NOT NULL THEN	
 		IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
		
 		SET @squery = CONCAT(@squery,' currencies_id=',QUOTE(currencies_id));
 	END IF;
 	  	
 	IF @squery!=''  THEN
 			SET @smainquery = CONCAT(@smainquery,' WHERE ',@squery,' GROUP BY transactioncode) as t WHERE t.debit!=t.credit)');
	ELSE
			SET @smainquery = CONCAT(@smainquery,' GROUP BY transactioncode) t WHERE t.debit=t.credit)');	
 	END IF;
  
 --	INSERT errors (err) VALUES(@smainquery);
 	
   PREPARE stmt FROM @smainquery;
 	
 	 EXECUTE stmt;
 
	 DEALLOCATE PREPARE stmt;

SELECT  
CASE WHEN COALESCE(g.chartofaccounts_accountcode,NULL) IS NULL THEN '' ELSE DATE(g.generalledger_tday) END tday,
coa.chartofaccounts_name account_label,
CASE WHEN COALESCE(g.chartofaccounts_accountcode,NULL) IS NULL THEN  CASE WHEN COALESCE(g.transactioncode,NULL) IS NULL THEN 'GT' ELSE 'ST' END ELSE g.transactioncode END tcode,
 g.chartofaccounts_accountcode account,
g.generalledger_description description,
SUM(generalledger_debit)debit,SUM(generalledger_credit)credit,
branch_code branch 
FROM generalledger g,sp_get_check_debit_credit_table1 d,chartofaccounts coa WHERE d.transactioncode=g.transactioncode AND coa.chartofaccounts_accountcode=g.chartofaccounts_accountcode GROUP BY g.transactioncode, g.chartofaccounts_accountcode WITH ROLLUP;
	


END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_client
DROP PROCEDURE IF EXISTS `sp_get_client`;
DELIMITER //
CREATE PROCEDURE `sp_get_client`(
	IN `branch_code` TINYTEXT,
	IN `client_idno` CHAR(100)
)
BEGIN

SELECT client_idno,client_regdate,bussinesssector_code,costcenters_code,CONCAT(client_surname,' ',client_middlename,' ',client_firstname) NAME,client_addressphysical FROM v_clients c WHERE c.client_idno=client_idno;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_clients_by_areacode
DROP PROCEDURE IF EXISTS `sp_get_clients_by_areacode`;
DELIMITER //
CREATE PROCEDURE `sp_get_clients_by_areacode`(
	IN `branch_code` TINYTEXT,
	IN `areacode_code` CHAR(100)
)
BEGIN

	DECLARE mainquery LONGTEXT DEFAULT '';
	DECLARE squery LONGTEXT DEFAULT '';

IF areacode_code!=''  THEN 	
 	SET @squery = CONCAT('  c.areacode_code = ',QUOTE(areacode_code));
ELSE	
 	SET @squery = CONCAT('  1=1 ');
END IF;


IF branch_code!=''  THEN 	
 	SET @squery = CONCAT(@squery,' AND c.branch_code = ',QUOTE(branch_code));
END IF;
 	

 SET @mainquery = 'SELECT client_idno,client_regdate,bussinesssector_code,costcenters_code,CONCAT(client_surname," ",client_middlename," ", client_firstname) name FROM clients c WHERE ';	


 -- concatinate
	IF @squery!='' AND @squery IS NOT NULL THEN	
		SET @mainquery = CONCAT(@mainquery,@squery);
   END IF;
   
   
--   insert into errors select @mainquery;
		   
	PREPARE stmt FROM @mainquery;
		 	
	EXECUTE stmt;
	
   
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_client_details
DROP PROCEDURE IF EXISTS `sp_get_client_details`;
DELIMITER //
CREATE PROCEDURE `sp_get_client_details`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(5),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `user_id` CHAR(50),
	IN `currencies_id` CHAR(5),
	IN `product_prodid` CHAR(50),
	IN `loancategory1_code` CHAR(50),
	IN `loancategory2_code` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50),
	IN `addtemptable` TINYINT
)
    COMMENT 'This Stores procedure is used to return client details'
BEGIN

-- DEPENDANTS:
-- 1.sp_get_savings_balances_detail
-- 2.sp_get_details


	DECLARE client_query VARCHAR(1000) DEFAULT '';
	DECLARE group_query VARCHAR(1000) DEFAULT '';
	DECLARE where_clause VARCHAR(1000) DEFAULT '';
	 
 	-- you must initialise local variables
 	
 	SET @client_query = "SELECT 
	 c.client_type,
	 c.branch_code,
	 c.client_regdate,
	 c.client_surname,
	 c.client_firstname,
	 c.client_middlename,
	 c.client_idno,
	 c.client_gender,
	 c.areacode_code,
	 c.clientcode,
	 c.costcenters_code,
	 c.client_cat1,
	 c.client_cat2,
	 c.bussinesssector_code,
	 c.client_regstatus,
	 c.client_tel1,
	 c.client_tel2,
	 '' AS client_grpname,
	 '' AS entity_regcode
	 FROM clients 
	 c";
	 
	 
	 SET @group_query = "SELECT 	 
	'G' AS client_type,
	 g.branch_code,	 
	 entity_regdate AS client_regdate,
	 '' client_surname,
	 '' client_firstname,
	 '' client_middlename,
	 g.entity_idno client_idno,
	 'U' client_gender,
	 g.areacode_code,	
	 g.entity_idno clientcode,
	 g.costcenters_code,
	 '' client_cat1,
	 '' client_cat2,
	 g.bussinesssector_code,
	 entity_regstatus client_regstatus,
	 entity_tel1 client_tel1,
	 entity_tel2 client_tel1,
	 entity_name client_grpname,
	 entity_regcode 
	 FROM entity g ";
	 
	 
	 
	
	SET @where_clause ='';

	IF branch_code!=''  AND branch_code IS NOT NULL  THEN 	
 		SET @where_clause = CONCAT(@where_clause,' branch_code =',QUOTE(TRIM(branch_code)));
 	END IF;
 		
 	-- client_cat1
	IF client1_code!='' AND client1_code IS NOT NULL THEN	
		IF @where_clause !='' THEN
			SET @where_clause = CONCAT(@where_clause,' AND ');
		END IF;
		
 		SET @where_clause = CONCAT(@where_clause,' client_cat1 =',QUOTE(client1_code));
 	END IF;
 	
 	-- client_cat2
	IF client2_code!='' AND client2_code IS NOT NULL THEN	
		IF @where_clause !='' THEN
			SET @where_clause = CONCAT(@where_clause,' AND ');
		END IF;
		
 		SET @where_clause = CONCAT(@where_clause,' client_cat2 =',QUOTE(client2_code));
 	END IF;
 	
 	-- bussinesssector_code
 	IF bussinesssector_code!='' AND bussinesssector_code IS NOT NULL THEN
	 	IF @where_clause !='' THEN
			SET @where_clause = CONCAT(@where_clause,' AND ');
		END IF;
		
 		SET @where_clause = CONCAT(@where_clause,' bussinesssector_code =',QUOTE(bussinesssector_code));
 	END IF;
 	
 	-- areacode_code
	 IF areacode_code!=''  AND areacode_code IS NOT NULL THEN
	 	IF @where_clause !='' THEN
			SET @where_clause = CONCAT(@where_clause,' AND ');
		END IF;
			
	 	SET @where_clause = CONCAT(@where_clause,' areacode_code =',QUOTE(areacode_code));
	 END IF;
	  
	-- startDate
	IF startDate IS NOT NULL THEN
		IF @where_clause !='' THEN
			SET @where_clause = CONCAT(@where_clause,' AND ');
		END IF;	
 		SET @where_clause = CONCAT(@where_clause,' DATE(client_regdate) >=',QUOTE(startDate));
 	END IF;
	  	
 	-- endDate
	IF  endDate IS NOT NULL THEN
		IF @where_clause !='' THEN
			SET @where_clause = CONCAT(@where_clause,' AND ');
		END IF;
 		SET @where_clause = CONCAT(@where_clause,' DATE(client_regdate) <=',QUOTE(endDate));
 	END IF;
 	
 	-- client_regstatus
	IF client_regstatus!='' AND client_regstatus!='0' AND client_regstatus IS NOT NULL THEN	
		IF @where_clause !='' THEN
			SET @where_clause = CONCAT(@where_clause,' AND ');
		END IF;
 		SET @where_clause = CONCAT(@where_clause,' client_regstatus =',QUOTE(client_regstatus));
 	END IF;
 	
 	-- costcenters_code
	IF costcenters_code!='' AND costcenters_code IS NOT NULL THEN	
	  IF @where_clause !='' THEN
			  SET @where_clause = CONCAT(@where_clause,' AND ');
		END IF;
 	 SET @where_clause = CONCAT(@where_clause,' costcenters_code =',QUOTE(TRIM(costcenters_code)));
 	END IF;
 	
 	
	SET @mainquery = ' SELECT * FROM ( ';
 	-- concatinate
	IF @where_clause!='' AND @where_clause IS NOT NULL THEN
	
		
		SET @mainquery = CONCAT(@mainquery , @client_query ,' WHERE ',@where_clause);
		SET @mainquery = CONCAT(@mainquery ,' UNION ALL ');
		SET @mainquery = CONCAT(@mainquery ,@group_query ,' WHERE ',REPLACE(@where_clause, 'client_regdate', 'entity_regdate'));
		
	ELSE
	
		SET @mainquery = CONCAT(@mainquery , @client_query );
		SET @mainquery = CONCAT(@mainquery ,' UNION ALL ');
		SET @mainquery = CONCAT(@mainquery ,@group_query);
		
   END IF;
   
   	SET @mainquery = CONCAT(@mainquery ,') c');
   
   
   
   
   IF group_by!='' AND group_by IS NOT NULL THEN	
		SET @mainquery = CONCAT(@mainquery,' GROUP BY ',group_by,',client_idno');
		
		 -- ORDER BY
		IF order_by!='' AND order_by IS NOT NULL THEN	
			SET @mainquery = CONCAT(@mainquery,' ORDER BY ',group_by,',',order_by);
		ELSE
			SET @mainquery = CONCAT(@mainquery,' ORDER BY ',group_by);
	   END IF;		
		
	ELSE
		 -- ORDER BY
		IF order_by!='' AND order_by IS NOT NULL THEN	
			SET @mainquery = CONCAT(@mainquery,' ORDER BY ',order_by);
	   END IF;
	   
   END IF;

   -- chech see if we are to create a temporary table
   -- this part used use by other stored procedures thats call this sp
  	-- IF addtemptable IS NOT NULL THEN
		
	--	IF addtemptable=1 THEN
			
			DROP TABLE IF EXISTS clients_filtered_table; 	
		 	
			SET @mainquery = CONCAT('CREATE TEMPORARY TABLE clients_filtered_table(KEY(client_idno)) AS (',@mainquery,')'); 
	
--		END IF;
--   END IF;
  
  -- ORDER BY	     
 insert into errors select @mainquery;
	-- SELECT @mainquery;
   -- TO DO: Some parameters not relevant yet in this scope	
 	
   PREPARE stmt FROM @mainquery;
 	
 	EXECUTE stmt;
 
	DEALLOCATE PREPARE stmt;
	
	IF addtemptable=0 THEN
	
		DROP TABLE IF EXISTS clients_filtered_table2; 
	
		CREATE TEMPORARY TABLE clients_filtered_table2 AS (
			SELECT *,count(*) as ncount FROM clients_filtered_table GROUP BY client_idno WITH ROLLUP
		) ;
		
		DROP TABLE IF EXISTS clients_filtered_table; 
		
		UPDATE clients_filtered_table2 SET branch_code='GT' ,client_idno=ncount WHERE client_idno IS NULL OR client_idno = '';
		
		
		SELECT * FROM clients_filtered_table2;		

	END IF;
	
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_coa
DROP PROCEDURE IF EXISTS `sp_get_coa`;
DELIMITER //
CREATE PROCEDURE `sp_get_coa`(
	IN `plang` CHAR(2)



)
    COMMENT 'This procedure is used to get all accounts of the chart of accounts'
BEGIN
	
SELECT chartofaccounts_parent paccount,chartofaccounts_accountcode account,chartofaccounts_name description,chartofaccounts_header header from chartofaccounts ORDER BY chartofaccounts_parent,chartofaccounts_accountcode;


END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_dashboard
DROP PROCEDURE IF EXISTS `sp_get_dashboard`;
DELIMITER //
CREATE PROCEDURE `sp_get_dashboard`(
	IN `plang` CHAR(3)
)
BEGIN
	-- GET CLIENTS
/*
update dashboard set value=(select COUNT(client_idno) FROM clients) WHERE  translations_id='1590';

update dashboard set value=(select SUM(savtransactions_amount) FROM savtransactions) WHERE  translations_id='1386';

-- GET OUSTANDING BALANCES
SELECT SUM(loanpayments_principal) INTO @pprincipal FROM loanpayments;

SELECT SUM(disbursements_amount) INTO @ddisbursement FROM disbursements WHERE DATE(disbursements_date)<=NOW();

update dashboard set value=(@ddisbursement-@pprincipal) WHERE  translations_id='1047'; 

-- GET ARREARS
SELECT SUM(due_principal) INTO @dprincipal FROM dues WHERE DATE(due_date)<=NOW();

SELECT SUM(loanpayments_principal) INTO @pprincipal FROM loanpayments;
       

update dashboard set value=(@dprincipal - @pprincipal)  WHERE  translations_id='1547'; 
*/

 
-- SELECT * FROM dashboard;
SELECT 
CASE WHEN plang ='EN' THEN translations_eng
WHEN plang ='SP' THEN translations_sp
WHEN plang = 'FR' THEN translations_fr
ELSE translations_eng END txtlabel,
 d.* FROM dashboard d, translations t WHERE t.translations_id=d.translations_id;
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_days_in_arrears
DROP PROCEDURE IF EXISTS `sp_get_days_in_arrears`;
DELIMITER //
CREATE PROCEDURE `sp_get_days_in_arrears`(
	IN `startDate` DATE
)
BEGIN
	
	DECLARE vloan_number CHAR(50) DEFAULT '';
	DECLARE vproduct_prodid CHAR(10) DEFAULT '';
   DECLARE vmembers_idno CHAR(50) DEFAULT '';  
   DECLARE Done1 BIT DEFAULT false;  
  
   DECLARE totapaidprinc NUMERIC(15,5)  DEFAULT 0;
   DECLARE totaldueint NUMERIC(15,5)  DEFAULT 0;
   DECLARE totalduecomm NUMERIC(15,5)  DEFAULT 0;
   DECLARE totalduepen NUMERIC(15,5)  DEFAULT 0;
   DECLARE totalduevat NUMERIC(15,5)  DEFAULT 0;
   
   DECLARE checkarrprinc CHAR(1)  DEFAULT '1';
   DECLARE checkarrint CHAR(1)  DEFAULT '0';
   DECLARE checkarrcomm CHAR(1)  DEFAULT '0';
   DECLARE checkarrpen CHAR(1)  DEFAULT '0';

      
	DECLARE curLoans CURSOR FOR SELECT c.product_prodid,c.loan_number,c.members_idno,SUM(COALESCE(loanpayments_principal,0)),SUM(COALESCE(loanpayments_interest,0)),  SUM(COALESCE(loanpayments_commission,0)),SUM(COALESCE(loanpayments_penalty,0)),SUM(COALESCE(loanpayments_vat,0)) FROM loans_out_table_final c LEFT OUTER JOIN loanpayments p ON c.members_idno=p.members_idno  AND c.loan_number=p.loan_number AND DATE(p.loanpayments_date)<=DATE(startDate) GROUP BY c.loan_number,c.members_idno;
	
	-- loop status handler for loop1
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET Done1:= true; 
	
	OPEN curLoans; 
	 
	loop1:LOOP 
	
	FETCH curLoans INTO vproduct_prodid,vloan_number, vmembers_idno,totapaidprinc,totaldueint,totalduecomm,totalduepen,totalduevat;
	
	
		SET @Tprinc = totapaidprinc;
		SET @Tint = totaldueint;
		SET @Tcomm = totalduecomm;
		SET @Tpen = totalduepen;
		SET @Tvat = totalduevat;
	
		SET @bdone1:= Done1;
		
	-- parameters on how arrears are determined
		SELECT productconfig_value INTO @checkarrprinc  FROM productconfig WHERE productconfig_paramname='PRI_IN_ARR' AND  product_prodid=vproduct_prodid;
		
		SELECT productconfig_value INTO @checkarrint  FROM productconfig WHERE productconfig_paramname='INT_IN_ARR' AND product_prodid=vproduct_prodid;
		
		SELECT productconfig_value INTO @checkarrcomm  FROM productconfig WHERE productconfig_paramname='COM_IN_ARR' AND product_prodid=vproduct_prodid;
		
		SELECT productconfig_value INTO @checkarrpen  FROM productconfig WHERE productconfig_paramname='PEN_IN_ARR' AND product_prodid=vproduct_prodid;
		
	   SET Done1:= @bdone1;
		 		
	BLOCK2: BEGIN
		DECLARE ddate DATE DEFAULT '0000-00-00';
		DECLARE ndays BIGINT DEFAULT 0;
		DECLARE dueprinc NUMERIC(15,5)  DEFAULT 0;
		DECLARE dueint NUMERIC(15,5)  DEFAULT 0;
		DECLARE duecomm NUMERIC(15,5)  DEFAULT 0;
		DECLARE duepen NUMERIC(15,5)  DEFAULT 0;
		DECLARE duevat NUMERIC(15,5)  DEFAULT 0;
		
		DECLARE arrprinc NUMERIC(15,5)  DEFAULT 0;
		DECLARE arrint NUMERIC(15,5)  DEFAULT 0;
		DECLARE arrcomm NUMERIC(15,5)  DEFAULT 0;
		DECLARE arrpen NUMERIC(15,5)  DEFAULT 0;
		DECLARE arrvat NUMERIC(15,5)  DEFAULT 0;
		
		DECLARE Done2 BIT DEFAULT false; 
		
		-- select all installments for this loan
		DECLARE curDues CURSOR FOR SELECT DATE(d.due_date) due_date,d.due_principal,d.due_interest,d.due_commission,d.due_penalty,d.due_vat FROM dues d WHERE d.loan_number=vloan_number AND DATE(d.due_date)<=DATE(startDate)  ORDER BY due_date ASC;
		
		-- loop status handler for loop1
	   DECLARE CONTINUE HANDLER FOR NOT FOUND SET Done2:= true; 	
		
		OPEN curDues;
		
			loop2: LOOP	
					
			FETCH curDues INTO ddate,dueprinc,dueint,duecomm,duepen,duevat;				
					
					-- check principal 
					SET totapaidprinc = totapaidprinc - dueprinc;						
							
					IF  totapaidprinc < 0 AND dueprinc > 0  AND  checkarrprinc ='1' THEN	
						  			
					  	SET ndays = COALESCE(DATEDIFF(startDate,ddate),0);	
					  						  	
						SELECT 
						    SUM(due_principal),
						    SUM(due_interest) ,
						    SUM(due_commission) ,
						    SUM(due_penalty),
						    SUM(due_vat) 						    
						    INTO @due_principal,@due_interest,@due_commission,@due_penalty,@due_vat
						FROM dues 
						WHERE loan_number = vloan_number 
						AND DATE(due_date)<=DATE(startDate)	GROUP BY loan_number;
				  	
						UPDATE loans_out_table_final l 
						SET l.arrdays = ndays,
							arrprinc = @due_principal - @Tprinc,
							arrint = @due_interest - @Tint,
							arrcomm = @due_commission - @Tcomm,
							arrpen = @due_penalty - @Tpen,
							arrvat = @due_vat - @Tvat 
						WHERE l.loan_number = vloan_number;		  
						
						SET Done2:=true; 					
								  					
					END IF;
					
				-- check interest 
					SET totaldueint := totaldueint - dueint;				
								
					IF  totaldueint < 0 AND dueint > 0  AND  checkarrint ='1' AND Done2=FALSE THEN			  			
					
						SET Done2:=true; 					
								  					
					END IF;
					
					-- check commision 
					SET totalduecomm := totalduecomm - duecomm;				
								
					IF  totalduecomm < 0 AND duecomm > 0  AND  checkarrcomm ='1' AND Done2=FALSE THEN			  			
					
						SET Done2:=true; 					
								  					
					END IF;
					
					
					-- check penalty 
					SET totalduepen := totalduepen - duepen;				
								
					IF  totalduepen < 0 AND duepen > 0  AND  checkarrpen ='1' AND Done2=FALSE THEN			  			
				
						SET Done2:=true; 					
								  					
					END IF;
										
				
					-- check see if we should exit the inner loop
					IF Done2 THEN					 			
						
						CLOSE curDues;
	      			LEAVE loop2;      			
	    			END IF; 
			
			 END LOOP loop2;
				
			END BLOCK2;
		
				IF Done1 THEN
					CLOSE curLoans;
	      		LEAVE loop1;
	    		END IF;
	    		
		END LOOP loop1;
		
		DROP TABLE IF EXISTS dues_filtered_table;
		
		DROP TABLE IF EXISTS loans_payments_table1;
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_dibursements
DROP PROCEDURE IF EXISTS `sp_get_dibursements`;
DELIMITER //
CREATE PROCEDURE `sp_get_dibursements`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(5),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `user_id` CHAR(50),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `loancategory1_code` CHAR(50),
	IN `loancategory2_code` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50),
	IN `addtemptable` BIT
)
    COMMENT 'This procedure is used to get dibursements'
BEGIN

	DECLARE dis_mainquery LONGTEXT DEFAULT '';
	DECLARE dis_squery LONGTEXT DEFAULT '';
	
	DROP TABLE IF EXISTS loans_filtered_table; 

		
	CALL `sp_get_loan_details`(branch_code,client1_code,client2_code, client3_code,bussinesssector_code, areacode_code, '','', client_regstatus, fund_code,costcenters_code,client_type,'', currencies_id, product_prodid, loancategory1_code, loancategory2_code, order_by, group_by, 1, 0, 1,'','');	
	
	SET @dis_mainquery = 'SELECT l.*,d.disbursements_date disb_date,SUM(d.disbursements_amount) amount_disb FROM loans_filtered_table l,disbursements d WHERE d.loan_number=l.loan_number ';


	IF startDate IS NOT NULL  AND endDate IS NOT NULL THEN 	
 		SET @dis_squery = CONCAT(' AND DATE(d.disbursements_date) BETWEEN ',QUOTE(DATE(startDate)),' AND ',QUOTE(DATE(endDate)));
 	END IF;
 	
	 -- concatinate
	IF @dis_squery!='' AND @dis_squery IS NOT NULL THEN	
		SET @dis_mainquery = CONCAT(@dis_mainquery,@dis_squery);
   END IF;
   
 --	insert into errors select @dis_mainquery;
		   
	PREPARE stmt FROM @dis_mainquery;
		 	
	EXECUTE stmt;
 
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_exchange_rate
DROP PROCEDURE IF EXISTS `sp_get_exchange_rate`;
DELIMITER //
CREATE PROCEDURE `sp_get_exchange_rate`(IN `branch_code` TINYTEXT, IN `tdate` CHAR(10), IN `currencies_id` INT)
BEGIN

	DECLARE rowexists BIGINT;
			DROP TABLE IF EXISTS sp_get_exchange_rate_table1;
			CREATE TEMPORARY TABLE sp_get_exchange_rate_table1 AS (SELECT  forexrates_id , COALESCE(forexrates_midrate,1)forexrates_midrate FROM forexrates r WHERE r.forexrates_date<=tdate AND r.currencies_id=currencies_id  ORDER BY r.forexrates_id,r.forexrates_date DESC LIMIT 1,1);
	
	
	SELECT COUNT(*) INTO @rowexists FROM sp_get_exchange_rate_table1;
	
	
	IF @rowexists=0 THEN
	
		DROP TABLE IF EXISTS sp_get_exchange_rate_table1;
		CREATE TEMPORARY TABLE sp_get_exchange_rate_table1 AS (SELECT 0 forexrates_id,1 forexrates_midrate);
	
	END IF;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_expected_payments_dues
DROP PROCEDURE IF EXISTS `sp_get_expected_payments_dues`;
DELIMITER //
CREATE PROCEDURE `sp_get_expected_payments_dues`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(5),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `user_id` CHAR(50),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `loancategory1_code` CHAR(50),
	IN `loancategory2_code` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50)
)
    COMMENT 'This sp is used to get expected payments of dues'
BEGIN

-- get loan details
CALL `sp_get_loan_details`(branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, '',endDate,client_regstatus,fund_code,costcenters_code,client_type,user_id, currencies_id, product_prodid,loancategory1_code, loancategory2_code,order_by,group_by,true , false ,true,'','');

-- client loans
DROP TABLE IF EXISTS loans_out_table1;

CREATE TEMPORARY TABLE loans_out_table1 AS (select l.loan_number,SUM(d.disbursements_amount) disamount  FROM loans_filtered_table l,disbursements d  WHERE d.loan_number=l.loan_number AND DATE(d.disbursements_date)<=DATE(endDate) GROUP BY d.loan_number);
 
   
-- get dues
   CALL `sp_get_loan_dues`('', '', branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, '', endDate, client_regstatus, fund_code, costcenters_code, client_type, user_id, currencies_id, product_prodid, loancategory1_code, loancategory2_code, order_by, group_by, true, false);
   
 -- get repayments 
 	CALL `sp_get_loan_payments`('', '', branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, '', DATE(NOW()), client_regstatus, fund_code, costcenters_code, client_type, user_id, currencies_id, product_prodid, loancategory1_code, loancategory2_code, order_by, group_by, true, false);
 	


-- calculate oustanding  
 DROP TABLE IF EXISTS loans_due_table1;
 
 CREATE TEMPORARY TABLE loans_due_table1 AS (SELECT d.loan_number,d.members_idno,COALESCE(SUM(due_principal),0)due_principal,COALESCE(SUM(due_interest),0)due_interest,COALESCE(SUM(due_penalty),0)due_penalty,COALESCE(SUM(due_commission),0)due_commission,COALESCE(SUM(due_vat),0)due_vat FROM dues_filtered_table d,loans_out_table1 t WHERE d.loan_number=t.loan_number GROUP BY d.loan_number, d.members_idno);
  
 DROP TABLE IF EXISTS loans_payments_table1;
  
 CREATE TEMPORARY TABLE loans_payments_table1 AS (SELECT p.loan_number,p.members_idno,COALESCE(SUM(loanpayments_principal),0)loanpayments_principal,COALESCE(SUM(loanpayments_interest),0)loanpayments_interest,COALESCE(SUM(loanpayments_penalty),0)loanpayments_penalty,COALESCE(SUM(loanpayments_commission),0)loanpayments_commission,COALESCE(SUM(loanpayments_vat),0)loanpayments_vat FROM payments_filtered_table p,loans_out_table1 t WHERE p.loan_number=t.loan_number GROUP BY p.loan_number, p.members_idno);
 
DROP TABLE IF EXISTS  loans_out_table1;
 
DROP TABLE IF EXISTS  dues_filtered_table;
  
DROP TABLE IF EXISTS  payments_filtered_table;
 
DROP TABLE IF EXISTS loans_balances_table1;
  
CREATE TEMPORARY TABLE loans_balances_table1 AS (SELECT 
d.loan_number,
d.members_idno,
(due_principal-COALESCE(loanpayments_principal,0)) principal,
(due_interest-COALESCE(loanpayments_interest,0)) interest,
(due_penalty-COALESCE(loanpayments_penalty,0)) penalty,
(due_commission-COALESCE(loanpayments_commission,0)) commission,
(due_vat-COALESCE(loanpayments_vat,0)) vatbal,
COALESCE(loanpayments_principal,0) ppaid
 FROM loans_due_table1 d LEFT OUTER JOIN loans_payments_table1 p ON d.loan_number=p.loan_number AND d.members_idno=p.members_idno);
	
	
SELECT c.client_firstname,c.client_middlename,c.client_surname,c.client_idno,d.*,(principal+interest+penalty+commission) texpected FROM loans_balances_table1 d, loans_filtered_table c  WHERE  d.loan_number=c.loan_number AND  d.members_idno=c.members_idno AND (principal+interest+penalty+commission)>0;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_income_expenditure
DROP PROCEDURE IF EXISTS `sp_get_income_expenditure`;
DELIMITER //
CREATE PROCEDURE `sp_get_income_expenditure`(
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `branch_codefr` TINYINT,
	IN `branch_codeto` TINYTEXT,
	IN `costcenters_codefr` CHAR(50),
	IN `costcenters_codeto` INT,
	IN `product_prodidfr` CHAR(50),
	IN `product_prodidto` CHAR(50),
	IN `accountcodefr` CHAR(20),
	IN `accountcodeto` INT,
	IN `donor_codefr` CHAR(10),
	IN `donor_codeto` INT,
	IN `trancodes_codefr` CHAR(50),
	IN `trancodes_codeto` CHAR(50),
	IN `currencies_id` INT,
	IN `user_idfr` CHAR(50),
	IN `user_idto` CHAR(50),
	IN `group_by` CHAR(50),
	IN `order_by` CHAR(50),
	IN `plang` CHAR(10)


)
BEGIN


 CALL `sp_get_transactions`(startDate,endDate, branch_codefr, branch_codeto, costcenters_codefr, costcenters_codeto, product_prodidfr,product_prodidto, '','', donor_codefr,donor_codeto,trancodes_codefr, trancodes_codeto,currencies_id,user_idfr,user_idto, true,plang);
 

 
-- sp_get_transactions_2

 DROP TABLE IF EXISTS sp_get_income_exp_table1;

-- get opening balances
CREATE TEMPORARY TABLE sp_get_income_exp_table1 AS (SELECT c.chartofaccounts_accountcode account,t.branch_code,
SUM(CASE WHEN t.ttype='OP' AND t.generalledger_debit <>0 THEN COALESCE(t.generalledger_debit,0.00) ELSE 0.00 END)odebit,


SUM(CASE WHEN t.ttype='OP'  AND t.generalledger_credit <>0 THEN COALESCE(t.generalledger_credit,0.00) ELSE 0.00 END)ocredit,

SUM(CASE WHEN t.ttype!='OP' AND t.generalledger_debit <>0 THEN COALESCE(t.generalledger_debit,0.00) ELSE 0.00 END) debit,
SUM(CASE WHEN t.ttype!='OP'  AND t.generalledger_credit <>0 THEN COALESCE(t.generalledger_credit,0.00) ELSE 0.00 END) credit,c.chartofaccounts_groupcode groupcode,c.chartofaccounts_name account_label,chartofaccounts_type ctype,c.chartofaccounts_header header
FROM chartofaccounts  c  LEFT OUTER JOIN sp_get_transactions_2 t  ON c.chartofaccounts_accountcode=t.chartofaccounts_accountcode GROUP BY c.chartofaccounts_accountcode,t.branch_code);
 

 -- income and expenditure accounts only  
 DROP TABLE IF EXISTS sp_get_income_exp_table2;
  
CREATE TEMPORARY TABLE sp_get_income_exp_table2 AS ( 
 SELECT groupcode,
 p.account, 
 p.branch_code,
 p.account_label,
 p.odebit,
 p.ocredit,
 p.debit,
 p.credit,
CASE WHEN (p.odebit + p.debit - p.credit) > 0 THEN  (p.odebit + p.debit - p.credit) ELSE 0.00 END cdebit,
CASE WHEN (p.ocredit + p.credit - p.credit) > 0 THEN  (p.ocredit + p.credit - p.credit) ELSE 0.00 END ccredit,
 ctype,header
 FROM sp_get_income_exp_table1  p WHERE (p.ctype !=1 AND p.ctype !=2 AND p.ctype !=9));
 
 
 DROP TABLE IF EXISTS sp_get_income_exp_table3;
 
-- add header1
CREATE TEMPORARY TABLE sp_get_income_exp_table3 AS ( 
SELECT account_label,t.account, t.branch_code,t.ctype,t.odebit,t.ocredit,t.debit,t.credit,t.cdebit,t.ccredit,t.groupcode,t.header,
CASE WHEN CONCAT(SUBSTR(groupcode,1,3),'000000000000000000') = c.chartofaccounts_groupcode THEN c.chartofaccounts_name ELSE  QUOTE('XXXXXXXXXX') END  header1  FROM sp_get_income_exp_table2  t,chartofaccounts c WHERE  CONCAT(SUBSTR(groupcode,1,3),'000000000000000000') = c.chartofaccounts_groupcode GROUP BY ctype,account ASC);
 
 
  -- add header2
  
 DROP TABLE IF EXISTS sp_get_income_exp_table4;
 
 
CREATE TEMPORARY TABLE sp_get_income_exp_table4 AS (SELECT t.*,
CASE WHEN CONCAT(SUBSTR(groupcode,1,6),'000000000000000') = c.chartofaccounts_groupcode THEN c.chartofaccounts_name ELSE  QUOTE('XXXXXXXXXX') END  header2
 FROM sp_get_income_exp_table3  t,chartofaccounts c 	WHERE  CONCAT(SUBSTR(groupcode,1,6),'000000000000000') = c.chartofaccounts_groupcode GROUP BY ctype,account ASC);
 
 

 -- add header3
 
  DROP TABLE IF EXISTS sp_get_income_exp_table5;
 
  
CREATE TEMPORARY TABLE sp_get_income_exp_table5 AS (SELECT t.*,
CASE WHEN CONCAT(SUBSTR(groupcode,1,9),'000000000000') = c.chartofaccounts_groupcode THEN c.chartofaccounts_name ELSE  QUOTE('XXXXXXXXXX') END  header3
 FROM sp_get_income_exp_table4  t,chartofaccounts c 	WHERE  CONCAT(SUBSTR(groupcode,1,9),'000000000000') = c.chartofaccounts_groupcode GROUP BY ctype,account ASC);
 
 
 -- add header4

 
DROP TABLE IF EXISTS sp_get_income_exp_table6;
 
  
CREATE TEMPORARY TABLE sp_get_income_exp_table6 AS (SELECT t.*,
CASE WHEN CONCAT(SUBSTR(groupcode,1,12),'000000000') = c.chartofaccounts_groupcode THEN c.chartofaccounts_name ELSE  QUOTE('XXXXXXXXXX') END  header4
 FROM sp_get_income_exp_table5  t,chartofaccounts c 	WHERE  CONCAT(SUBSTR(groupcode,1,12),'000000000') = c.chartofaccounts_groupcode GROUP BY ctype,account ASC);
 
 
   -- add header5

   
 DROP TABLE IF EXISTS sp_get_income_exp_table7;
 

CREATE TEMPORARY TABLE sp_get_income_exp_table7 AS (SELECT t.*,
CASE WHEN CONCAT(SUBSTR(groupcode,1,15),'000000') = c.chartofaccounts_groupcode THEN c.chartofaccounts_name ELSE  QUOTE('XXXXXXXXXX') END  header5
 FROM sp_get_income_exp_table6  t,chartofaccounts c 	WHERE  CONCAT(SUBSTR(groupcode,1,15),'000000') = c.chartofaccounts_groupcode GROUP BY ctype,account ASC);
 
 
   -- add header6

 
DROP TABLE IF EXISTS sp_get_income_exp_table8;
 

CREATE TEMPORARY TABLE sp_get_income_exp_table8 AS (SELECT t.*,
CASE WHEN CONCAT(SUBSTR(groupcode,1,18),'000') = c.chartofaccounts_groupcode THEN c.chartofaccounts_name ELSE  QUOTE('XXXXXXXXXX') END  header6
 FROM sp_get_income_exp_table7  t,chartofaccounts c 	WHERE  CONCAT(SUBSTR(groupcode,1,18),'000') = c.chartofaccounts_groupcode  GROUP BY ctype,account ASC);
 

















 
DROP TABLE IF EXISTS sp_get_income_exp_table9;









CREATE TEMPORARY TABLE sp_get_income_exp_table9 AS(
SELECT b.groupcode,b.account_label,b.account, b.branch_code,b.odebit,b.ocredit,b.debit pdebit,
b.credit pcredit ,b.cdebit,b.ccredit,b.header1,b.header2,b.header3,b.header4,b.header5,b.header6,header FROM sp_get_income_exp_table8 b);














select *  FROM sp_get_income_exp_table9;
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_indicators_report
DROP PROCEDURE IF EXISTS `sp_get_indicators_report`;
DELIMITER //
CREATE PROCEDURE `sp_get_indicators_report`(
	IN `datefrom1` DATE,
	IN `dateto1` DATE,
	IN `datefrom2` DATE,
	IN `dateto2` DATE,
	IN `plang` CHAR(2)











































)
BEGIN

-- create table with lables

drop table IF EXISTS sp_get_indicatorsreport_table1 ;

CREATE TEMPORARY TABLE sp_get_indicatorsreport_table1(
	 lableid tinyint,
    clable CHAR(255) null,
    dtfrm DECIMAL(15,2) null,
    dtto DECIMAL(15,2) null,
    diff DECIMAL(15,2) null
);

-- 1341
-- 1708 No of Loans Disbursed
-- 1711 Amount Outstanding
-- 1709 Average Amount Disbursed
-- 1710 Number of Loans Outstanding
-- 1712 Average Principal Outstanding
-- 1363 Principal Due
-- 1717 Interest Due
-- 1718 Commission Due
-- 1351 Principal Paid
-- 1352 Interest Paid
-- 1713 Commission Paid
-- 1714 Principal Paid in Advance
-- 1715 Interest Paid In Advance
-- 1716 Repayment Rate
-- 1721 Qualitative Repayment Rate
-- 1722 Quantitative Repayment Rate
-- 1413 Principal is in Arrears
-- 1414 Interest in Arrears
-- 1720 Commission in Arrears
-- 1303 Portfolio At Risk
-- 1719 Delinquency Rate (PF at Risk /Total Oustanding)

-- create lables
SET @row =0;

INSERT INTO sp_get_indicatorsreport_table1 (lableid,clable)  VALUES 
(@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1341')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1708')),

 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1711')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1709')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1710')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1712')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1363')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1717')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1718')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1351')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1352')),
 
(@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1713')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1714')),
 
(@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1715')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1716')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1721')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1722')),
 
 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1413')),

 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1414')),

 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1720')),

 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1303')),

 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1303')),

 (@row := @row + 1, (SELECT CASE WHEN plang ='EN' THEN translations_eng
 WHEN plang ='FR' THEN translations_fr WHEN plang ='SP' THEN translations_sp ELSE translations_eng END clable FROM translations WHERE translations_id='1719'));

-- --------------------------------------
-- No of Loans Disbursed
-- Average Amount Disbursed

 SELECT COUNT(loan_number),SUM(disbursements_amount), (SUM(disbursements_amount)/COUNT(loan_number)) INTO @cntdisb1, @amtdisb1,@avgdisb1 FROM disbursements WHERE DATE(disbursements_date) BETWEEN   datefrom1 AND dateto1;

 SELECT COUNT(loan_number),SUM(disbursements_amount), (SUM(disbursements_amount)/COUNT(loan_number)) INTO @cntdisb2,@amtdisb2 ,@avgdisb2 FROM disbursements WHERE DATE(disbursements_date) BETWEEN   datefrom2 AND dateto2;

-- --------------------------------------
-- Amount Outstanding 1
-- Average Principal Outstanding

 DROP TABLE IF EXISTS dateto1Due;
 CREATE TEMPORARY TABLE dateto1Due AS (SELECT d.loan_number,d.members_idno,SUM(d.due_principal) principal,SUM(d.due_interest) interest,SUM(d.due_commission) commission,SUM(d.due_penalty) penalty  FROM dues d  WHERE  DATE(d.due_date)<=dateto1
 AND d.loan_number IN (SELECT dd.loan_number FROM disbursements dd WHERE dd.loan_number=d.loan_number AND DATE(dd.disbursements_date)<=dateto1) GROUP BY d.loan_number,d.members_idno);




-- dateto1Pay
DROP TABLE IF EXISTS dateto1Pay;
CREATE TEMPORARY TABLE dateto1Pay AS  (SELECT d.loan_number,d.members_idno, SUM(d.loanpayments_principal) principal,SUM(d.loanpayments_interest) interest,SUM(d.loanpayments_commission) commission,SUM(d.loanpayments_penalty) penalty  FROM loanpayments d  WHERE  DATE(d.loanpayments_date)<=dateto1
AND d.loan_number IN (SELECT dd.loan_number FROM disbursements dd WHERE dd.loan_number=d.loan_number AND DATE(dd.disbursements_date)<=dateto1) GROUP BY d.loan_number,d.members_idno);

-- dateto1Out
DROP TABLE IF EXISTS dateto1Out;
CREATE TEMPORARY TABLE dateto1Out  (SELECT d.loan_number,d.members_idno, d.principal - COALESCE(p.principal,0) principal,d.interest-COALESCE(p.interest,0) interest,d.commission-COALESCE(p.commission,0) commission,d.penalty-COALESCE(p.penalty,0) penalty FROM dateto1Due d  LEFT OUTER JOIN dateto1Pay p ON d.loan_number=p.loan_number AND d.members_idno=p.loan_number);


DROP TABLE IF EXISTS dateto1Due;

DROP TABLE IF EXISTS dateto1Pay;

SELECT COUNT(loan_number),SUM(principal),SUM(interest),SUM(commission) , SUM(penalty)  INTO @Outprinc1, @Outint1, @Outcomm1, @Outpen1,@countOut1 FROM dateto1Out where principal>0 OR  interest>0 ;

-- Average Principal Outstanding
--  SELECT SUM(principal)/COUNT(loan_number) INTO @avgOut1 FROM dateto1Out;
SELECT SUM(principal)/COUNT(loan_number)  FROM dateto1Out;




-- --------------------------------------
-- Amount Outstanding 2

DROP TABLE IF EXISTS dateto2Due;
CREATE TEMPORARY TABLE dateto2Due AS (SELECT SUM(d.due_principal) principal,SUM(d.due_interest) interest,SUM(d.due_commission) commission,SUM(d.due_penalty) penalty  FROM dues d  WHERE  DATE(d.due_date)<=dateto2
AND d.loan_number IN (SELECT dd.loan_number FROM disbursements dd WHERE dd.loan_number=d.loan_number AND DATE(dd.disbursements_date)<=dateto2) GROUP BY d.loan_number,d.members_idno);

-- dateto1Pay
DROP TABLE IF EXISTS dateto2Pay;
CREATE TEMPORARY TABLE dateto2Pay AS  (SELECT SUM(d.loanpayments_principal) principal,SUM(d.loanpayments_interest) interest,SUM(d.loanpayments_commission) commission,SUM(d.loanpayments_penalty) penalty  FROM loanpayments d  WHERE  DATE(d.loanpayments_date)<=dateto2
AND d.loan_number IN (SELECT dd.loan_number FROM disbursements dd WHERE dd.loan_number=d.loan_number AND DATE(dd.disbursements_date)<=dateto2) GROUP BY d.loan_number,d.members_idno );

-- dateto1Out
DROP TABLE IF EXISTS dateto2Out;
CREATE TEMPORARY TABLE dateto2Out  (SELECT d.principal - COALESCE(p.principal,0) principal,d.interest-COALESCE(p.interest,0) interest,d.commission-COALESCE(p.commission,0) commission,d.penalty-COALESCE(p.penalty,0) penalty FROM dateto1Due d LEFT OUTER JOIN  dateto1Pay p ON d.loan_number=p.loan_number AND d.members_idno=p.loan_number);

SELECT COUNT(loan_number),SUM(principal),SUM(interest),SUM(commission) ,SUM(penalty)  INTO @Outprinc2, @Outint2, @Outcomm2, @Outpen2,@countOut1 FROM dateto2Out WHERE SUM(principal) > 0 OR SUM(interest)>0;

-- Average Principal Outstanding
SELECT SUM(principal)/COUNT(loan_number) INTO @avgOut2 FROM dateto2Out  WHERE SUM(principal) > 0;

DROP TABLE IF EXISTS dateto1Out;
DROP TABLE IF EXISTS dateto2Out;

DROP TABLE IF EXISTS dateto1Pay;
DROP TABLE IF EXISTS dateto2Pay;


-- --------------------------------------
-- amount over paid

 SELECT SUM(loanpayments_overpay) INTO @overpay1  FROM loanpayments  WHERE  DATE(loanpayments_date) BETWEEN datefrom1 AND dateto1;
 
 SELECT SUM(loanpayments_overpay) INTO @overpay2  FROM loanpayments  WHERE  DATE(loanpayments_date) BETWEEN datefrom2 AND dateto2;
 
-- --------------------------------------
-- principal due in period

  SELECT IFNULL(SUM(due_principal),0000000000000000.00),IFNULL(SUM(due_interest),0000000000000000.00),IFNULL(SUM(due_commission),0000000000000000.00),IFNULL(SUM(due_penalty),0000000000000000.00) INTO @dPrinc1,@dInt1,@dComm1,@dPen1  FROM dues d WHERE  DATE(d.due_date) BETWEEN datefrom1 AND dateto1
  AND  d.loan_number IN (SELECT loan_number FROM disbursements dd WHERE dd.loan_number=d.loan_number AND DATE(disbursements_date)<=dateto1);

  SELECT IFNULL(SUM(due_principal),0000000000000000.00),IFNULL(SUM(due_interest),0000000000000000.00),IFNULL(SUM(due_commission),0000000000000000.00),IFNULL(SUM(due_penalty),0000000000000000.00) INTO @dPrinc2,@dInt2,@dComm2,@dPen2  FROM dues d WHERE  DATE(d.due_date) BETWEEN datefrom2 AND dateto2
  AND  d.loan_number IN (SELECT loan_number FROM disbursements dd WHERE dd.loan_number=d.loan_number AND DATE(disbursements_date)<=dateto2);
  

-- paid in period
  SELECT IFNULL(SUM(loanpayments_principal),0000000000000000.00),IFNULL(SUM(loanpayments_interest),0000000000000000.00),IFNULL(SUM(loanpayments_commission),0000000000000000.00),IFNULL(SUM(loanpayments_penalty),0000000000000000.00) INTO @pPrinc1,@pInt1,@pComm1,@pPen1  FROM loanpayments d WHERE  DATE(d.loanpayments_date) BETWEEN datefrom1 AND dateto1
  AND  d.loan_number IN (SELECT dd.loan_number FROM disbursements dd WHERE dd.loan_number=d.loan_number AND DATE(dd.disbursements_date)<=dateto1);

	SELECT IFNULL(SUM(loanpayments_principal),0000000000000000.00),IFNULL(SUM(loanpayments_interest),0000000000000000.00),IFNULL(SUM(loanpayments_commission),0000000000000000.00),IFNULL(SUM(loanpayments_penalty),0000000000000000.00) INTO @pPrinc2,@pInt2,@pComm2,@pPen2  FROM loanpayments d WHERE  DATE(d.loanpayments_date) BETWEEN datefrom2 AND dateto2
  AND  d.loan_number IN (SELECT dd.loan_number FROM disbursements dd WHERE dd.loan_number=d.loan_number AND DATE(dd.disbursements_date)<=dateto2);

  
DROP TABLE IF EXISTS loans_balances_dues1;

CREATE TEMPORARY TABLE loans_balances_dues1 AS (SELECT
d.loan_number,
d.members_idno,
SUM(d.due_principal) dprincipal,
SUM(d.due_interest) dInterest,
SUM(d.due_penalty) dpenalty,
SUM(d.due_commission) dcommission,
SUM(d.due_vat) dvat
FROM dues d WHERE d.due_date<=datefrom1 and d.loan_number  
IN (SELECT b.loan_number FROM disbursements b where b.loan_number=d.loan_number AND DATE(b.disbursements_date)<=DATE(datefrom1)) GROUP BY d.loan_number,
d.members_idno);

 ALTER TABLE `loans_balances_dues1` ADD INDEX `loans_balances_dues1_loan_number` (loan_number,members_idno);

-- payments 1
DROP TABLE IF EXISTS loans_balances_payments1;

CREATE TEMPORARY TABLE loans_balances_payments1 AS (SELECT
p.loan_number,
p.members_idno,
SUM(p.loanpayments_principal) pprincipal,
SUM(p.loanpayments_interest) pinterest,
SUM(p.loanpayments_penalty) ppenalty,
SUM(p.loanpayments_commission) pcommission,
SUM(p.loanpayments_vat) pvat
FROM loanpayments p WHERE DATE(p.loanpayments_date)<=datefrom1 and p.loan_number  IN (SELECT b.loan_number FROM disbursements b where b.loan_number=p.loan_number AND DATE(b.disbursements_date)<=DATE(datefrom1)) 
GROUP BY p.loan_number,p.members_idno);

ALTER TABLE `loans_balances_payments1` ADD INDEX `loans_balances_payments1_loan_number` (loan_number,members_idno);


DROP TABLE IF EXISTS loans_out_table;


CREATE TEMPORARY TABLE loans_out_table AS (
SELECT d.loan_number,
d.members_idno,
d.dprincipal -p.pprincipal AS oprincipal,  
d.dinterest - p.pinterest AS ointerest,
d.dpenalty - p.ppenalty AS openalty,
d.dcommission - p.pcommission AS ocommission,
d.dvat - p.pvat AS ovat,
'00000' arrdays
FROM loans_balances_dues1 d LEFT OUTER JOIN  loans_balances_payments1 p ON d.loan_number=p.loan_number  AND d.members_idno=p.members_idno);

DROP TABLE IF EXISTS loans_balances_dues1 ;

DROP TABLE IF EXISTS loans_balances_payments1;

DROP TABLE IF EXISTS loans_out_table_final;
CREATE TEMPORARY TABLE loans_out_table_final AS ( SELECT d.*,l.product_prodid FROM loans_out_table d,loan  l WHERE  l.loan_number=d.loan_number AND oprincipal>0 OR ointerest>0 OR openalty>0 OR ocommission>0);

DROP TABLE IF EXISTS loans_out_table;

-- select * from loans_out_table_final;



-- CALL `sp_get_days_in_arrears`(dateto1);
 
SELECT * FROM loans_out_table_final;
  
-- get quantitative and qualitative ratios

  	







END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loanledgercard_details
DROP PROCEDURE IF EXISTS `sp_get_loanledgercard_details`;
DELIMITER //
CREATE PROCEDURE `sp_get_loanledgercard_details`(
	IN `loan_number` CHAR(50),
	IN `members_idno` CHAR(50),
	IN `plang` CHAR(5)
)
    COMMENT 'This procedure is used to get loan dues'
BEGIN

-- get the descriptions
SELECT  CASE WHEN plang='EN' THEN translations_eng 
WHEN plang='SP' THEN translations_sp 
WHEN plang='FR' THEN translations_fr 
WHEN plang='SWA' THEN translations_swa  END INTO @descip1
FROM translations WHERE translations_id='1448';


SELECT  CASE WHEN plang='EN' THEN translations_eng 
WHEN plang='SP' THEN translations_sp 
WHEN plang='FR' THEN translations_fr 
WHEN plang='SWA' THEN translations_swa  END INTO @descip2
FROM translations WHERE translations_id='1449';

SELECT  CASE WHEN plang='EN' THEN translations_eng 
WHEN plang='SP' THEN translations_sp 
WHEN plang='FR' THEN translations_fr 
WHEN plang='SWA' THEN translations_swa  END INTO @descip3
FROM translations WHERE translations_id='1229';

SELECT  CASE WHEN plang='EN' THEN translations_eng 
WHEN plang='SP' THEN translations_sp 
WHEN plang='FR' THEN translations_fr 
WHEN plang='SWA' THEN translations_swa  END INTO @descip4
FROM translations WHERE translations_id='1112';


-- get dues union payments
 SELECT SUM(d.due_principal) ,SUM(d.due_interest),SUM(due_commission),SUM(due_penalty),SUM(due_vat) INTO @Principal, @Interest,@Commision,@Penalty,@nVat FROM dues d WHERE d.loan_number=loan_number AND d.members_idno=members_idno;

DROP TABLE IF EXISTS sp_get_loanledgercard_details_table1;

CREATE TEMPORARY TABLE sp_get_loanledgercard_details_table1 AS (
SELECT x.*  FROM (
SELECT l.loan_number, @descip4 descrip4,l.loan_adate tdate, SUM(l.loan_amount) principal,CAST(0.00 AS DECIMAL(15,2)) interest,CAST(0.00 AS DECIMAL(15,2)) penalty,CAST(0.00 AS DECIMAL(15,2)) commission,CAST(0.00 AS DECIMAL(15,2)) vat,CAST(0.00 AS DECIMAL(15,2)) bprincipal,CAST(0.00 AS DECIMAL(15,2)) binterest,CAST(0.00 AS DECIMAL(15,2)) bcommission, CAST(0.00 AS DECIMAL(15,2)) bpenalty,CAST(0.00 AS DECIMAL(15,2)) bvat,CAST(0.00 AS DECIMAL(15,2)) ttotal,CAST(0.00 AS DECIMAL(15,2)) tpaid,CAST(0.00 AS DECIMAL(15,2)) due,'A' rtype FROM loan l WHERE l.loan_number=loan_number	AND l.members_idno=members_idno
UNION ALL
SELECT di.loan_number, @descip3 descrip3,di.disbursements_date tdate, di.disbursements_amount principal,CAST(0.00 AS DECIMAL(15,2)) interest,CAST(0.00 AS DECIMAL(15,2)) penalty,CAST(0.00 AS DECIMAL(15,2)) commission,CAST(0.00 AS DECIMAL(15,2)) vat,CAST(0.00 AS DECIMAL(15,2)) bprincipal,CAST(0.00 AS DECIMAL(15,2)) binterest,CAST(0.00 AS DECIMAL(15,2)) bcommission, CAST(0.00 AS DECIMAL(15,2)) bpenalty,CAST(0.00 AS DECIMAL(15,2)) bvat,CAST(0.00 AS DECIMAL(15,2)) ttotal,CAST(0.00 AS DECIMAL(15,2)) tpaid,CAST(0.00 AS DECIMAL(15,2)) due,'D' rtype FROM disbursements di WHERE di.loan_number=loan_number	
UNION ALL
SELECT d.loan_number loan_number, @descip1 descrip,due_date tdate, due_principal principal,due_interest interest,due_penalty penalty,due_commission commission,due_vat vat,CAST(0.00 AS DECIMAL(15,2)) bprincipal,CAST(0.00 AS DECIMAL(15,2)) binterest,CAST(0.00 AS DECIMAL(15,2)) bcommission, CAST(0.00 AS DECIMAL(15,2)) bpenalty,CAST(0.00 AS DECIMAL(15,2)) bvat,CAST(0.00 AS DECIMAL(15,2)) ttotal,CAST(0.00 AS DECIMAL(15,2)) tpaid,CAST(0.00 AS DECIMAL(15,2)) due,'I' rtype from dues d WHERE d.loan_number=loan_number 
UNION ALL
SELECT p.loan_number loan_number, @descip2 descrip ,loanpayments_date tdate,-1*(loanpayments_principal) principal,-1*(loanpayments_interest) interest,-1*(loanpayments_commission) commission,-1*(loanpayments_penalty) penalty,-1*(loanpayments_vat) vat,CAST(0.00 AS DECIMAL(15,2)) bprincipal,CAST(0.00 AS DECIMAL(15,2)) binterest,CAST(0.00 AS DECIMAL(15,2)) bcommission, CAST(0.00 AS DECIMAL(15,2)) bpenalty,CAST(0.00 AS DECIMAL(15,2)) bvat,CAST(0.00 AS DECIMAL(15,2)) ttotal,CAST(0.00 AS DECIMAL(15,2)) tpaid,CAST(0.00 AS DECIMAL(15,2)) due, 'P' rtype FROM loanpayments p WHERE p.loan_number=loan_number  ORDER BY tdate ASC) x);


	SET  @prevprinc := 0;
	SET  @prevint := 0;
	SET  @prevcomm := 0;
	SET  @prevpen := 0;
	SET  @prevvat := 0;
	SET  @prevr := 0;
	SET  @prevtpaid := 0;
	SET  @due := 0;
 
 	UPDATE sp_get_loanledgercard_details_table1 AS w
    SET w.bprincipal =  (@prevprinc:= @prevprinc + (CASE WHEN w.rtype='P' OR w.rtype='D' THEN  w.principal ELSE 0 END)) ,
    w.interest = (CASE WHEN w.rtype='D' THEN 0 WHEN w.rtype='A' THEN @Interest ELSE w.interest  END) ,
    w.binterest =  (@prevint:= @prevint + (CASE WHEN w.rtype='P' THEN  w.interest WHEN w.rtype='D' THEN @Interest ELSE 0 END)) ,
    w.bcommission =  (@prevcomm:= @prevcomm + (CASE WHEN w.rtype='P' THEN  w.commission WHEN w.rtype='D' THEN @Commision ELSE 0 END)) , 
    w.bpenalty =  (@prevpen:= @prevpen + (CASE WHEN w.rtype='P' THEN  w.penalty  WHEN w.rtype='D' THEN @Penalty ELSE 0 END)) ,
    w.bvat =  (@prevvat:= @prevvat + (CASE WHEN w.rtype='P' THEN  w.vat  WHEN w.rtype='D' THEN @nVat ELSE 0 END)) ,
    w.due = (@due:= @due + (CASE WHEN w.rtype='P' OR w.rtype='I' THEN  (w.principal + w.interest + w.commission + w.penalty + w.vat)  ELSE 0 END)) ,
    w.ttotal =  (@prevr:= @prevr + (CASE WHEN w.rtype='P' THEN  (w.principal + w.interest + w.commission + w.penalty + w.vat)  WHEN w.rtype='D' THEN (w.principal + @Interest + @Commision + @Penalty + @nVat) ELSE 0 END)) ,
  
   
    w.tpaid =  @prevtpaid  + (CASE WHEN w.rtype='P' THEN (w.principal + w.interest + w.commission + w.penalty + w.vat) ELSE 0.00 END) ORDER BY  tdate ASC;
 
    
   -- WHERE w.savaccounts_account = vaccount and w.product_prodid =vproduct_prodid
    -- ORDER BY w.savtransactions_tday,w.savtransactions_id;


SELECT * FROM sp_get_loanledgercard_details_table1;
-- calculate balances









   

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_arrears_details
DROP PROCEDURE IF EXISTS `sp_get_loan_arrears_details`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_arrears_details`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(5),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `user_id` CHAR(50),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `loancategory1_code` CHAR(50),
	IN `loancategory2_code` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50),
	IN `addtemptable` BIT,
	IN `n_days` BIGINT
)
    COMMENT 'This sp is used to get loans in arrears'
BEGIN
	-- first get outtsnding balances
	DROP TABLE IF EXISTS loans_out_table_final;
	
 	CALL `sp_get_outstanding_loan_balances`(branch_code, client1_code, client2_code, client3_code,bussinesssector_code,areacode_code, endDate, client_regstatus,fund_code, costcenters_code, client_type, user_id, currencies_id, product_prodid,loancategory1_code,loancategory2_code, order_by, group_by, false,'','');
	
	IF endDate IS NOT NULL THEN
	
		SET endDate = DATE(NOW());
	
	END IF;


 	CREATE TEMPORARY TABLE loansoutstanding AS (select *  FROM loans_out_table_final LIMIT 2);
 -- get dues
 -- CALL `sp_get_loan_dues_sum`(endDate);
--   
	-- CALL sp_get_loan_dues ('', '', branch_code, client1_code,client2_code,client3_code, bussinesssector_code, areacode_code,startDate,endDate, client_regstatus, fund_code, costcenters_code, client_type,user_id,'',product_prodid,loancategory1_code,loancategory2_code, order_by, group_by, '1','1');
	   
	CALL `sp_get_days_in_arrears`(endDate);

	 IF addtemptable	THEN
	
	  	DROP TABLE IF EXISTS loans_out_arr_table_final;
	 
	 	CREATE TEMPORARY TABLE loans_out_arr_table_final AS (SELECT * FROM loans_out_table_final WHERE arrdays > 0);
	  	
	 --	DROP TABLE IF EXISTS loans_out_table_final;
	
	 ELSE
	
	 	SELECT * FROM loans_out_table_final WHERE CONVERT(arrdays, DECIMAL(15)) > n_days;
	
	 END IF;
	
	 -- SELECT * FROM loans_out_table_final;
	
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_arrears_details_sms
DROP PROCEDURE IF EXISTS `sp_get_loan_arrears_details_sms`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_arrears_details_sms`(
	IN `branch_code` TINYTEXT,
	IN `asatdate` DATE,
	IN `product_prodid` CHAR(50),
	IN `n_days` BIGINT,
	IN `climit` CHAR(50),
	IN `loannumbers` MEDIUMTEXT
)
    COMMENT 'This sp is used to get loans in arrears'
BEGIN
DECLARE n_days BIGINT DEFAULT 0;
DECLARE rowcount BIGINT DEFAULT 0;
DECLARE totalnrows  BIGINT DEFAULT 0;
DECLARE vloan_number  CHAR(50) DEFAULT '';
DECLARE vmembers_idno  CHAR(50) DEFAULT '';
DECLARE vprincipalbal NUMERIC(15,4) DEFAULT 0;
DECLARE varInt NUMERIC(15,4) DEFAULT 0;
DECLARE no_int CHAR(1);
DECLARE recalint CHAR(1);
DECLARE cstatus CHAR(10);
DECLARE smainquery MEDIUMTEXT DEFAULT '';
 
CALL `sp_get_loan_details_for_loans`('');

IF asatdate IS NULL THEN
	SELECT NOW() INTO asatdate;
END IF;

 DROP TABLE IF EXISTS loans_out_table_1;
 
SET @smainquery = 'CREATE TEMPORARY TABLE loans_out_table_1 AS (SELECT s.*,l.client_idno,client_surname,client_firstname,client_middlename,c.client_tel1,c.client_tel2  FROM loansoutstanding s,loan l,clients c WHERE s.loan_number=l.loan_number AND c.client_idno=l.client_idno';

-- CHEK SEE IF LOAN NUMBER HAVE BEEN SENT
IF loannumbers!=''  THEN
	SET @smainquery = CONCAT(@smainquery,' AND FIND_IN_SET (l.loan_number,',QUOTE(loannumbers),')>0');
END IF;

SET @smainquery = CONCAT(@smainquery,')');

PREPARE stmt FROM @smainquery;
 	
EXECUTE stmt;
 
DEALLOCATE PREPARE stmt;

SELECT FOUND_ROWS() INTO @reccount;


-- GET SAVINGS
 DROP TABLE IF EXISTS loans_out_table_sav;

SET @smainquery= CONCAT('CREATE TEMPORARY TABLE loans_out_table_sav AS (SELECT l.*, balance FROM loans_out_table_1 l  LEFT JOIN savingsbalances t ON t.savaccounts_account=l.client_idno GROUP BY t.savaccounts_account,t.product_prodid ', climit,')');

PREPARE stmt FROM @smainquery;
 	
EXECUTE stmt;
 
DEALLOCATE PREPARE stmt;

SELECT loan_number,members_idno,due_principal,due_interest,due_commission,due_penalty,client_idno,client_tel1,client_tel2,CONCAT(client_surname,' ',client_firstname,' ',client_middlename) name,COALESCE(balance,0)balance FROM loans_out_table_sav;

IF loannumbers='' THEN
	SELECT @reccount AS reccount;
END IF;



END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_details
DROP PROCEDURE IF EXISTS `sp_get_loan_details`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_details`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(5),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `user_id` CHAR(50),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `loancategory1_code` CHAR(50),
	IN `loancategory2_code` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50),
	IN `isdisbursed` BIT,
	IN `includewoff` BIT,
	IN `addtemptable` TINYINT,
	IN `loan_number_fr` CHAR(50),
	IN `loan_number_to` CHAR(50)
)
    COMMENT 'This procedure is used to get loan application details'
BEGIN


-- SOURCES:
-- 1.sp_get_client_details
-- 2.

-- DEPENDANTS:
-- 1.sp_get_outstanding_loan_balances
-- 2.

-- DEPENDANTS:
-- 1.sp_get_disbursements
-- 2.

DECLARE loan_smainquery MEDIUMTEXT DEFAULT '';
DECLARE loan_squery MEDIUMTEXT DEFAULT '';
	 

SET @loan_squery ='';

CALL `sp_get_client_details`(branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, '', '', client_regstatus, fund_code, costcenters_code, '', '', '', product_prodid, '', '', '', '', 1);

DROP TABLE IF EXISTS loans_table;

-- get loans
SET @loan_smainquery = 'CREATE TEMPORARY TABLE loans_table  AS (
select c.*,l.loan_number,l.loan_amount,l.fund_code,l.loan_tint,l.user_id,l.loan_intamount,l.loan_status,l.loan_udf1,l.loan_udf2,l.loan_udf3,l.loan_inttype,l.loan_insttype,l.loan_intdays,l.product_prodid,l.donor_code,l.members_idno from loan l,clients_filtered_table c WHERE c.client_idno=l.client_idno '; 

	-- FILTERS

	-- only disbursed loans
	IF isdisbursed =1 THEN
		SET @loan_squery = CONCAT(@loan_squery,' AND l.loan_number  IN (SELECT ls.loan_number FROM loanstatuslog ls WHERE ls.loan_status=',QUOTE('LD'),' AND l.loan_number=ls.loan_number)');
	END IF;
	
	-- exclude writtenof losn
	IF isdisbursed =1 THEN
		SET @loan_squery = CONCAT(@loan_squery,' AND l.loan_number NOT  IN (SELECT lw.loan_number FROM loanswrittenoff lw  WHERE lw.loan_number=l.loan_number)');
	END IF;
	

	IF product_prodid!=''  AND product_prodid IS NOT NULL  THEN 	
 		SET @loan_squery = CONCAT(@loan_squery,' AND l.product_prodid =',QUOTE(product_prodid));
 	END IF;


	IF loancategory1_code!=''  AND loancategory1_code IS NOT NULL  THEN 	
 		SET @loan_squery = CONCAT(@loan_squery,' AND l.loan_udf1 =',QUOTE(loancategory1_code));
 	END IF;
 	
 	IF loancategory2_code!=''  AND loancategory2_code IS NOT NULL  THEN 	
 		SET @loan_squery = CONCAT(@loan_squery,' AND l.loan_udf3 =',QUOTE(loancategory2_code));
 	END IF;
 	
 	IF user_id!=''  AND user_id IS NOT NULL  THEN 	
 		SET @loan_squery = CONCAT(@loan_squery,' AND l.user_id =',QUOTE(user_id));
 	END IF;
 	
 	IF loan_number_fr!=''  AND loan_number_to!=''  THEN 	
 		SET @loan_squery = CONCAT(@loan_squery,' AND l.loan_number BETWEEN ',QUOTE(loan_number_fr),' AND ',QUOTE(loan_number_to));
 	END IF;
 	
 	IF loan_number_fr !='' AND  loan_number_to=''  THEN 	
 		SET @loan_squery = CONCAT(@loan_squery,' AND l.loan_number = ',QUOTE(loan_number_fr));
 	END IF;
 	
 	
	SET @loan_squery = CONCAT(@loan_squery,');');
		  	
 	-- concatinate
	IF @loan_squery!='' AND @loan_squery IS NOT NULL THEN
	
		SET @loan_smainquery = CONCAT(@loan_smainquery,@loan_squery);
   END IF;
   
  	 -- log query
 -- insert into errors select @loan_smainquery;
		   
	PREPARE stmt FROM @loan_smainquery;
		 	
	EXECUTE stmt;
	
	DEALLOCATE PREPARE stmt;	
	
   -- chech see if we are to create a temporary table
   -- this part used use by other stored procedures thats call this sp
  --	IF addtemptable IS NOT NULL THEN
		
IF addtemptable = 1 THEN			
		DROP TABLE IF EXISTS loans_filtered_table; 			
		CREATE TEMPORARY TABLE loans_filtered_table AS (SELECT * FROM loans_table); 
	ELSE			
		SELECT * FROM loans_table;			
	END IF;
		

  
 
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_details_for_loans
DROP PROCEDURE IF EXISTS `sp_get_loan_details_for_loans`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_details_for_loans`(
	IN `branch_code` TINYTEXT








)
    COMMENT 'This prodecure tracks all outstanding loan balances'
BEGIN


SELECT ROUND(time_to_sec((TIMEDIFF(NOW(),COALESCE(lastupdatedate,'2017-09-29 00:00:00')))) / 60) INTO @thetime  FROM lasttableupdatedate WHERE tablename='loansoutstanding';

	-- UPDATE TABLE ONLY OF 2 MINUTE SHAVE PASSED SINCE LAST UPDATE
	IF@thetime > 2 THEN
	
		TRUNCATE TABLE loansoutstanding;
	
		-- GET DUES
		-- get amounts payable
		 DROP TABLE IF EXISTS sp_get_loan_dues;
		 
		--  total dues and dues before current date
		CREATE TEMPORARY TABLE sp_get_loan_dues (PRIMARY KEY(loan_number),INDEX(loan_number)) (select
		d.loan_number,
		d.members_idno,
		SUM(COALESCE(d.due_principal,0))  dprinc,
		 SUM(COALESCE(d.due_interest,0))	dint,
		 SUM(COALESCE(d.due_commission,0)) dcomm,
		 SUM(COALESCE(d.due_penalty,0)) dpen
		
		FROM  dues d WHERE d.loan_number IN (SELECT i.loan_number FROM disbursements  i WHERE d.loan_number=i.loan_number)
		AND d.loan_number NOT IN (SELECT w.loan_number FROM loanswrittenoff w WHERE d.loan_number=w.loan_number )
		 GROUP BY d.loan_number,d.members_idno);
		
		
		-- GET PAYMENTS
		
		-- get all payments
		DROP TABLE IF EXISTS sp_get_loan_payments;
		 
		CREATE TEMPORARY TABLE sp_get_loan_payments (PRIMARY KEY(loan_number),INDEX(loan_number)) (SELECT 
		p.loan_number,
		p.members_idno,
		SUM(COALESCE(p.loanpayments_principal,0)) pprinc,
		SUM(COALESCE(p.loanpayments_interest,0)) pint,
		SUM(COALESCE(p.loanpayments_commission,0)) pcomm,
		SUM(COALESCE(p.loanpayments_penalty,0)) ppen
		
		FROM  loanpayments p  GROUP BY p.loan_number,p.members_idno);
		
		DROP TABLE IF EXISTS loans_filtered_table_loan;
		
		CREATE TEMPORARY TABLE loans_filtered_table_loan(SELECT dd.loan_number,loan.client_idno,dd.members_idno,dprinc,dint,dcomm,dpen  FROM  (SELECT 
		d.loan_number,
		d.members_idno,
		SUM(COALESCE(d.dprinc,0))-SUM(COALESCE(p.pprinc,0)) dprinc,
		SUM(COALESCE(d.dint,0))-SUM(COALESCE(p.pint,0)) dint,
		SUM(COALESCE(d.dcomm,0))-SUM(COALESCE(p.pcomm,0)) dcomm,
		SUM(COALESCE(d.dpen,0))-SUM(COALESCE(p.ppen,0)) dpen
		FROM sp_get_loan_dues d LEFT JOIN sp_get_loan_payments p ON p.loan_number=d.loan_number 
		AND p.members_idno=d.members_idno GROUP BY d.loan_number,d.members_idno) as dd,loan  WHERE (dprinc >0 OR dint >0 OR dcomm > 0 OR dpen>0) AND loan.loan_number=dd.loan_number );
		
		
		DROP TABLE IF EXISTS sp_get_loan_dues;
		 
		DROP TABLE IF EXISTS sp_get_loan_payments;
		
		INSERT INTO loansoutstanding (loan_number,client_idno, members_idno, due_principal,due_interest,due_commission,due_penalty,due_vat) 
		            SELECT loan_number,client_idno, members_idno, dprinc,dint,dcomm,dpen,'0' FROM loans_filtered_table_loan;
		 
		 DELETE FROM lasttableupdatedate WHERE  tablename='loansoutstanding';
		 
		 INSERT INTO lasttableupdatedate (tablename,lastupdatedate) VALUES('loansoutstanding',NOW());        
	
	END IF;
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_details_summary
DROP PROCEDURE IF EXISTS `sp_get_loan_details_summary`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_details_summary`(
	IN `branch_code` TINYTEXT,
	IN `loan_numbers` MEDIUMTEXT,
	IN `asatdate` DATE,
	IN `limit` TINYTEXT,
	IN `product_prodid` CHAR(10)
)
    COMMENT 'This procedure is used to get summarised loan details'
BEGIN


DECLARE rowcount BIGINT DEFAULT 0;
DECLARE totalnrows  BIGINT DEFAULT 0;
DECLARE vloan_number  CHAR(50) DEFAULT '';
DECLARE vproduct_prodid  CHAR(10) DEFAULT '';
DECLARE vmembers_idno  CHAR(50) DEFAULT '';
DECLARE vprincipalbal NUMERIC(15,4) DEFAULT 0;
DECLARE varInt NUMERIC(15,4) DEFAULT 0;
DECLARE no_int CHAR(1);
DECLARE recalint CHAR(1);



DECLARE smainquery TINYTEXT DEFAULT '';
DECLARE squery TINYTEXT DEFAULT '';

-- SET @vproduct_prodid :='L0000';

IF TRIM(product_prodid)!='' AND product_prodid IS NOT NULL THEN
	SET @squery = CONCAT(' l.product_prodid =',QUOTE(@vproduct_prodid),' AND ');
ELSE
	SET @vproduct_prodid :='L0000';
	SET @squery = CONCAT(' l.product_prodid =',QUOTE(@vproduct_prodid),' AND ');
END IF;



DROP TABLE IF EXISTS loans_filtered_table;

IF loan_numbers='' THEN
	SET @smainquery = CONCAT('CREATE TEMPORARY TABLE loans_filtered_table AS (SELECT CONCAT(c.client_firstname," ",c.client_middlename," ",c.client_surname) name, l.client_idno,d.loan_number,d.members_idno,SUM(d.disbursements_amount) loan_amount,l.product_prodid,l.loan_inttype,l.loan_insttype,l.loan_tint,l.loan_grace,l.loan_intcgrace,l.loan_noofinst,l.loan_intfirst,loan_insintgrac,l.loan_alsograce,l.loan_inupfront,(SELECT pc.productconfig_value FROM productconfig pc WHERE pc.productconfig_paramname="REF_PRIORITY" AND pc.product_prodid=l.product_prodid GROUP BY pc.product_prodid) ref_priority FROM disbursements d,clients c,loan l WHERE ',@squery,' c.client_idno=l.client_idno AND d.loan_number=l.loan_number   AND d.loan_number NOT IN (SELECT w.loan_number FROM loanswrittenoff w WHERE l.loan_number=w.loan_number) GROUP BY l.client_idno,d.loan_number,l.product_prodid,d.members_idno)');		   
ELSE
	SET @smainquery = CONCAT('CREATE TEMPORARY TABLE loans_filtered_table AS (SELECT CONCAT(c.client_firstname," ",c.client_middlename," ",c.client_surname) name, l.client_idno,d.loan_number,d.members_idno,SUM(d.disbursements_amount) loan_amount,l.product_prodid,l.loan_inttype,l.loan_insttype,l.loan_tint,l.loan_grace,l.loan_intcgrace,l.loan_noofinst,l.loan_intfirst,loan_insintgrac,l.loan_alsograce,l.loan_inupfront,(SELECT pc.productconfig_value FROM productconfig pc WHERE pc.productconfig_paramname="REF_PRIORITY" AND pc.product_prodid=l.product_prodid GROUP BY pc.product_prodid) ref_priority FROM disbursements d,clients c,loan l WHERE ',@squery,'c.client_idno=l.client_idno AND d.loan_number=l.loan_number   AND FIND_IN_SET(l.loan_number,',QUOTE(loan_numbers),')>0 AND d.loan_number NOT IN (SELECT w.loan_number FROM loanswrittenoff w WHERE l.loan_number=w.loan_number AND FIND_IN_SET(w.loan_number,',QUOTE(loan_numbers),')>0) GROUP BY l.client_idno,d.loan_number,l.product_prodid,d.members_idno)');
END IF;

-- insert into errors select @smainquery;

PREPARE stmt FROM @smainquery;
 	
EXECUTE stmt;

-- get amounts payable
 DROP TABLE IF EXISTS sp_get_charge_int;
 
CREATE TEMPORARY TABLE sp_get_charge_int AS (SELECT COALESCE(product_prodid,'') prodid,COALESCE(productconfig_value,0) value FROM productconfig WHERE productconfig_paramname='CHARGE_INT' GROUP BY product_prodid);

IF asatdate='' OR  asatdate='0000-00-00' OR asatdate IS NULL THEN
	SELECT NOW() INTO asatdate;
END IF;


-- get amounts payable
 DROP TABLE IF EXISTS sp_get_loan_dues;
 
--  total dues and dues before current date
CREATE TEMPORARY TABLE sp_get_loan_dues AS (select
l.loan_number,
d.members_idno,
SUM(COALESCE(d.due_principal,0))  ddprinc,
 SUM(COALESCE(d.due_interest,0))	ddint,
 SUM(COALESCE(d.due_commission,0)) ddcomm,
 SUM(COALESCE(d.due_penalty,0)) ddpen,
SUM(COALESCE(d.due_vat,0))  ddvat,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_principal,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) dprinc,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_interest,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) dint,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_commission,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) dcomm,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_penalty,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) dpen,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_vat,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) dvat,

SUM(CASE WHEN DATE(d.due_date) <= DATE(asatdate) THEN COALESCE(d.due_principal,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) tprinc,
SUM(CASE WHEN DATE(d.due_date) <= DATE(asatdate) THEN COALESCE(d.due_interest,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) tint,
SUM(CASE WHEN DATE(d.due_date) <= DATE(asatdate) THEN COALESCE(d.due_commission,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) tcomm,
SUM(CASE WHEN DATE(d.due_date) <= DATE(asatdate) THEN COALESCE(d.due_penalty,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) tpen,
SUM(CASE WHEN DATE(d.due_date) <= DATE(asatdate) THEN COALESCE(d.due_vat,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) tvat,

SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_principal,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) nprinc,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_interest,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) nint,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_commission,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) ncomm,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_penalty,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) npen,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_vat,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) nvat
FROM  dues d ,loans_filtered_table l WHERE d.loan_number =l.loan_number  GROUP BY d.loan_number);

-- get all payments
DROP TABLE IF EXISTS sp_get_loan_payments;
 
CREATE TEMPORARY TABLE sp_get_loan_payments (SELECT 
p.loan_number,
p.members_idno,
SUM(COALESCE(p.loanpayments_principal,0)) pprinc,
SUM(COALESCE(p.loanpayments_interest,0)) pint,
SUM(COALESCE(p.loanpayments_commission,0)) pcomm,
SUM(COALESCE(p.loanpayments_penalty,0)) ppen, 
SUM(COALESCE(p.loanpayments_vat,0)) pvat
FROM  loanpayments p,loans_filtered_table l WHERE p.loan_number =l.loan_number  GROUP BY p.loan_number);



DROP TABLE IF EXISTS sp_get_out_loan_dues1;

-- GET WHAT IS DUE TODAY 14/09/2017
-- CONSIDER PAYMENTS
-- WHAT IS ACTUALLY DUE TODAY = (WHAT IS DUE UPTO TODAY -  WHAT IS PAID)- WHAT IS DUE UPTO YESTERDAY

-- ARREARS
-- ARREARS = WHAT IS DUE UPTO YESTERDAY - WHAT IS PAID

CREATE TEMPORARY TABLE sp_get_out_loan_dues1 (SELECT 
d.loan_number,
d.members_idno,
d.nprinc,
d.nint,
d.ncomm,
d.npen,
d.nvat,
SUM(COALESCE(p.pprinc,0)) pprinc,
SUM(COALESCE(d.dprinc,0))-SUM(COALESCE(p.pprinc,0)) arprinc,
SUM(COALESCE(d.dint,0))-SUM(COALESCE(p.pint,0)) arint,
SUM(COALESCE(d.dcomm,0))-SUM(COALESCE(p.pcomm,0)) arcomm,
SUM(COALESCE(d.dpen,0))-SUM(COALESCE(p.ppen,0)) arpen,
SUM(COALESCE(d.dvat,0))-SUM(COALESCE(p.pvat,0)) arvat,
SUM(d.dprinc) - SUM(COALESCE(p.pprinc,0)) yprinc,
SUM(d.dint) - SUM(COALESCE(p.pint,0))   yint,
SUM(d.dcomm) - SUM(COALESCE(p.pcomm,0)) ycomm,
SUM(d.dpen)- SUM(COALESCE(p.ppen,0)) ypen,
SUM(d.dvat)- SUM(COALESCE(p.pvat,0))  yvat ,
COALESCE(d.ddprinc,0)-SUM(COALESCE(p.pprinc,0)) ddprinc,
COALESCE(d.ddint,0) - SUM(COALESCE(p.pint,0)) ddint,
COALESCE(d.ddcomm,0)-SUM(COALESCE(p.pcomm,0)) ddcomm,
COALESCE(d.ddpen,0)-SUM(COALESCE(p.ppen,0)) ddpen,
COALESCE(d.ddvat,0) -SUM(COALESCE(p.pvat,0)) ddvat
FROM sp_get_loan_dues d LEFT JOIN sp_get_loan_payments p ON p.loan_number=d.loan_number 
 GROUP BY d.loan_number);

DROP TABLE IF EXISTS sp_get_out_loan_dues2;

CREATE TEMPORARY TABLE sp_get_out_loan_dues2 (
SELECT 
l.name,
l.loan_number,
l.client_idno,
l.loan_amount,
d.members_idno,
l.product_prodid,
l.ref_priority,
l.loan_inttype,l.loan_insttype,l.loan_tint,l.loan_grace,l.loan_intcgrace,l.loan_noofinst,l.loan_intfirst,loan_insintgrac,l.loan_alsograce,l.loan_inupfront,
IF(d.arprinc<=0,0,d.arprinc) arprinc,
IF(d.arint<=0,0,d.arint) arint,
IF(d.arcomm<=0,0,d.arcomm) arcomm,
IF(d.arpen<=0,0,d.arpen) arpen,
IF(d.arvat<=0,0,d.arvat) arvat,
(CASE WHEN d.yprinc >=0 THEN d.nprinc ELSE IF((d.nprinc + d.yprinc)>0,(d.nprinc + d.yprinc),0) END) dprinc,
(CASE WHEN d.yint >=0 THEN d.nint ELSE IF((d.nint + d.yint)>0,(d.nint + d.yint),0) END) dint,
(CASE WHEN d.ycomm >=0 THEN d.ncomm ELSE IF((d.ncomm + d.ycomm)>0,(d.ncomm + d.ycomm),0) END) dcomm,
(CASE WHEN d.ypen >=0 THEN d.npen ELSE IF((d.npen + d.ypen)>0,(d.npen + d.ypen),0) END) dpen,
(CASE WHEN d.yvat >=0 THEN d.nvat ELSE IF((d.nvat + d.yvat)>0,(d.nvat + d.yvat),0) END) ddvat,
COALESCE(d.pprinc,0) princpaid,
ddint outint,
ddcomm outcomm,
ddpen  outpen,
ddvat outvat
FROM sp_get_out_loan_dues1 d, loans_filtered_table l WHERE l.loan_number=d.loan_number);

DROP TABLE IF EXISTS sp_get_loan_dues_payable4;

CREATE TEMPORARY TABLE sp_get_loan_dues_payable4 AS (

SELECT l.*,(SUM(COALESCE(disbursements_amount,0))- COALESCE(princpaid,0)) outprinc FROM sp_get_out_loan_dues2 l,disbursements d WHERE d.loan_number=l.loan_number  GROUP BY d.loan_number);


DROP TABLE IF EXISTS loans_filtered_table;

/*

-- recalculculate interest
SELECT COUNT(loan_number) INTO @totalnrows FROM sp_get_loan_dues_payable4;

 WHILE rowcount <= @totalnrows DO
  	BEGIN     
     SELECT loan_number,outprinc,members_idno,product_prodid INTO @vloan_number,@vprincipalbal,@vmembers_idno,@vproduct_prodid  FROM sp_get_loan_dues_payable4 LIMIT rowcount, 1;
	

SELECT pc.productconfig_value INTO @recalint FROM productconfig pc WHERE productconfig_paramname ='RECALC_INT' AND pc.product_prodid=@vproduct_prodid;

SELECT pc.productconfig_value INTO @no_int FROM productconfig pc WHERE pc.productconfig_paramname ='NO_INT' AND pc.product_prodid=@vproduct_prodid;
	

	-- check see if we should ignore evaluating interest
	IF @recalint='1'  THEN
		
	
			IF @vprincipalbal > 0 THEN
			
	--		insert into errors select @vproduct_prodid;
			
			 CALL `sp_recalculate_int`('', @vloan_number,@vmembers_idno, asatdate, @vprincipalbal,@vproduct_prodid,true);	 
	
	 		SELECT interest INTO @varInt FROM sp_interest_table;
	 	 		
	 	 	END IF;  	
 	 	
 	ELSE
	 SET 	@varInt = 0;			
	END IF;		
    
   IF @varInt < 0 THEN
   	SET @varInt = 0 ;
   END IF; 
   
	UPDATE sp_get_loan_dues_payable4 SET dint = @varInt WHERE loan_number=@vloan_number AND members_idno = @vmembers_idno;	 
      
	SET rowcount  = rowcount + 1;
	
 	END; 
END while; 
*/
DROP TABLE IF EXISTS sp_get_loan_dues_payable5;

CREATE TEMPORARY TABLE sp_get_loan_dues_payable5 (SELECT SQL_CALC_FOUND_ROWS * FROM sp_get_loan_dues_payable4);

 SET @nrows = FOUND_ROWS();

SELECT l.*,@nrows reccount FROM sp_get_loan_dues_payable5 l;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_disbursements
DROP PROCEDURE IF EXISTS `sp_get_loan_disbursements`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_disbursements`(
	IN `userid` CHAR(50),
	IN `ddate` DATE,
	IN `loannumber` CHAR(50)



)
    COMMENT 'This procedure is used to get loan application details'
BEGIN


 SELECT d.loan_number,d.members_idno,SUM(d.disbursements_amount)disbursements_amount,SUM(d.disbursements_stationery) disbursements_stationery,SUM(d.disbursements_commission) disbursements_commission FROM disbursements d WHERE  d.loan_number=loannumber AND DATE(d.disbursements_date)<=ddate GROUP BY d.loan_number,d.members_idno
 UNION
 SELECT m.loan_number,m.members_idno,0.0 disbursements_amount,0.0 disbursements_stationery, 0.0 disbursements_commission FROM memberloans m  WHERE m.loan_number =loannumber AND NOT EXISTS (SELECT s.loan_number  FROM disbursements s WHERE s.loan_number =loannumber LIMIT 1) GROUP BY m.loan_number,m.members_idno;
  
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_dues
DROP PROCEDURE IF EXISTS `sp_get_loan_dues`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_dues`(
	IN `loan_number` CHAR(50),
	IN `members_idno` CHAR(50),
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(5),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `user_id` CHAR(50),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `loancategory1_code` CHAR(50),
	IN `loancategory2_code` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50),
	IN `addtemptable` BIT,
	IN `refresh` BIT
)
    COMMENT 'This procedure is used to get loan dues'
BEGIN

	DECLARE smainquery MEDIUMTEXT DEFAULT '';
	DECLARE squery TINYTEXT DEFAULT '';
	DECLARE table_exists BIT;

	SET smainquery ='';   
	SET @squery ='';


	IF @refresh THEN
		DROP TABLE IF EXISTS loans_filtered_table;
	END IF;


-- check see if table exists
 -- CALL `check_table_existence`('loans_filtered_table', @table_exists);


-- check see if temporaray table exists
-- id not create it
 -- IF @table_exists=0 THEN
	CALL `sp_get_loan_details`(branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, startDate,endDate,client_regstatus,fund_code,costcenters_code,client_type,user_id, currencies_id, product_prodid,loancategory1_code, loancategory2_code,order_by,group_by, 0,0,true,'','');
 -- END IF;

	DROP TABLE IF EXISTS loan_dues1;

	CREATE TEMPORARY TABLE loan_dues1 AS (
	SELECT d.due_id,l.loan_number,d.members_idno, d.due_principal,d.due_interest,d.due_penalty,d.due_commission ,d.due_vat,d.due_date FROM dues d,loans_filtered_table l WHERE  l.loan_number=d.loan_number AND l.members_idno=d.members_idno);

	SET @smainquery ='SELECT * from loan_dues1 WHERE 1=1';
	
	IF  startDate IS NOT NULL THEN			
		SET @squery = CONCAT(@squery,'   AND due_date >=',QUOTE(startDate));
	END IF;
	
	IF  endDate IS NOT NULL THEN		
		SET @squery = CONCAT(@squery,' AND  due_date <=',QUOTE(endDate));
	END IF;
	
	IF loan_number!=''  AND loan_number IS NOT NULL THEN		
		SET @squery = CONCAT(@squery,' AND  loan_number =',QUOTE(loan_number));
	END IF;
	
	IF members_idno!=''  AND members_idno IS NOT NULL THEN		
		SET @squery = CONCAT(@squery,' AND  members_idno =',QUOTE(members_idno));
	END IF;
	
	SET @smainquery = CONCAT(@smainquery,@squery);
	
	 -- chech see if we are to create a temporary table
   -- this part used use by other stored procedures thats call this sp
  	IF addtemptable IS NOT NULL THEN
		
		IF addtemptable=1 THEN
			
			DROP TABLE IF EXISTS dues_filtered_table; 	
 	 	
			SET @smainquery = CONCAT('CREATE TEMPORARY TABLE dues_filtered_table AS (',@smainquery,')'); 
	
		END IF;
   END IF;
   
   
    
 	-- insert into errors select @smainquery;
 	

	-- SELECT @smainquery;
   -- TO DO: Some parameters not relevant yet in this scope	
 	
   PREPARE stmt FROM @smainquery;
 	
 	 EXECUTE stmt;
 
	 DEALLOCATE PREPARE stmt;
   

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_dues_sum
DROP PROCEDURE IF EXISTS `sp_get_loan_dues_sum`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_dues_sum`(
	IN `asatdate` DATE
)
    COMMENT 'This procedure is used to get summarised loan dues'
BEGIN

-- CALLED FROM 
-- sp_get_outstanding_loan_balances

DECLARE squery TINYTEXT DEFAULT '';
SET @smainquery ='';   
SET squery ='';

DROP TABLE IF EXISTS sp_get_loan_dues_table1;

SET @smainquery ='CREATE TEMPORARY TABLE sp_get_loan_dues_table1 AS (
	SELECT l.loan_number,d.members_idno, SUM(d.due_principal) due_principal,SUM(d.due_interest) due_interest,SUM(d.due_penalty) due_penalty,SUM(d.due_commission)due_commission ,SUM(d.due_vat) due_vat FROM dues d,loansoutstanding l WHERE  l.loan_number=d.loan_number AND l.members_idno=d.members_idno' ;

	IF asatdate IS NOT NULL THEN		
		SET squery = CONCAT(squery,' AND  d.due_date <=',QUOTE(asatdate));
	ELSE
		SET squery = CONCAT(squery,' AND  d.due_date <=',QUOTE(DATE(NOW())));
	END IF;
	
	SET @smainquery = CONCAT(@smainquery,squery,' GROUP BY d.loan_number, d.members_idno)');
		
	-- INSERT INTO errors (err) values(@smainquery);
	
   PREPARE stmt FROM @smainquery;
 	
 	EXECUTE stmt;
 
	DEALLOCATE PREPARE stmt;
	 

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_guarantors
DROP PROCEDURE IF EXISTS `sp_get_loan_guarantors`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_guarantors`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(5),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `user_id` CHAR(50),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `loancategory1_code` CHAR(50),
	IN `loancategory2_code` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50),
	IN `addtemptable` BIT
)
    COMMENT 'This sp is used to get details of gurantors and the loan they guarantee'
BEGIN

-- Name ,loan Number ,principal,Interest,Penalty,Commission,Day in arrays
-- get loan details
-- get loan details
CALL `sp_get_loan_details`(branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, endDate,endDate,client_regstatus,fund_code,costcenters_code,client_type,user_id, currencies_id, product_prodid,loancategory1_code, loancategory2_code,order_by,group_by,false , false ,TRUE,'','');


-- client loans
DROP TABLE IF EXISTS loans_out_table1;

CREATE TEMPORARY TABLE loans_out_table1 AS (select ld.*,COALESCE((SELECT SUM(ls.loan_amount)loan_amount FROM loanstatuslog ls WHERE ls.loan_number=ld.loan_number AND ls.loan_status='LD' GROUP BY ls.loan_number),0) disamount  FROM loans_filtered_table ld);
 
   
-- get dues
CALL `sp_get_loan_dues`('', '', branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, startDate, endDate, client_regstatus, fund_code, costcenters_code, client_type, user_id, currencies_id, product_prodid, loancategory1_code, loancategory2_code, order_by, group_by, true, false);
   
 -- get repayments 
CALL `sp_get_loan_payments`('', '', branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, startDate, endDate, client_regstatus, fund_code, costcenters_code, client_type, user_id, currencies_id, product_prodid, loancategory1_code, loancategory2_code, order_by, group_by, true, false);
 	
 -- calculate oustanding  
DROP TABLE IF EXISTS loans_due_table1;
 
CREATE TEMPORARY TABLE loans_due_table1 AS (SELECT d.loan_number,d.members_idno,COALESCE(SUM(due_principal),0)due_principal,COALESCE(SUM(due_interest),0)due_interest,COALESCE(SUM(due_penalty),0)due_penalty,COALESCE(SUM(due_commission),0)due_commission,COALESCE(SUM(due_vat),0)due_vat FROM dues_filtered_table d,loans_out_table1 t WHERE d.loan_number=t.loan_number GROUP BY d.loan_number, d.members_idno);
  
DROP TABLE IF EXISTS loans_payments_table1;
  
CREATE TEMPORARY TABLE loans_payments_table1 AS (SELECT p.loan_number,p.members_idno,COALESCE(SUM(loanpayments_principal),0)loanpayments_principal,COALESCE(SUM(loanpayments_interest),0)loanpayments_interest,COALESCE(SUM(loanpayments_penalty),0)loanpayments_penalty,COALESCE(SUM(loanpayments_commission),0)loanpayments_commission,COALESCE(SUM(loanpayments_vat),0)loanpayments_vat FROM payments_filtered_table p,loans_out_table1 t WHERE p.loan_number=t.loan_number GROUP BY p.loan_number, p.members_idno);
 
 -- calculate balance  
DROP TABLE IF EXISTS loans_balances_table1;
  
CREATE TEMPORARY TABLE loans_balances_table1 AS (SELECT 
d.loan_number,
d.members_idno,
(due_principal-COALESCE(loanpayments_principal,0)) pbalance,
(due_interest-COALESCE(loanpayments_interest,0)) ibalance,
(due_penalty-COALESCE(loanpayments_penalty,0)) penbalance,
(due_commission-COALESCE(loanpayments_commission,0)) combalance,
(due_vat-COALESCE(loanpayments_vat,0)) vbalance
 FROM loans_due_table1 d LEFT OUTER JOIN loans_payments_table1 p ON d.loan_number=p.loan_number AND d.members_idno=p.members_idno
);
	

DROP TABLE IF EXISTS loans_out_table_final;
-- return balances
 CREATE TEMPORARY TABLE loans_out_table_final AS (
select c.*,b.pbalance,b.ibalance,b.penbalance,b.combalance,b.vbalance,'      ' arrdays FROM loans_out_table1 c,loans_balances_table1 b WHERE c.loan_number=b.loan_number AND c.members_idno=b.members_idno AND (pbalance > 0 OR ibalance > 0 OR penbalance > 0 OR combalance > 0));


-- CALL `sp_get_days_in_arrears`(endDate);

-- CALL `sp_get_days_in_arrears`('', '', '', '', startDate);

--	insert into errors select @baddtemptable;
	
IF addtemptable = false THEN
	 SELECT l.* FROM guarantors g,loans_out_table_final l WHERE g.loan_number=l.loan_number;
 END IF;


END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_payments
DROP PROCEDURE IF EXISTS `sp_get_loan_payments`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_payments`(
	IN `loan_number` CHAR(50),
	IN `members_idno` CHAR(50),
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(5),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `user_id` CHAR(50),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `loancategory1_code` CHAR(50),
	IN `loancategory2_code` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50),
	IN `addtemptable` BIT,
	IN `refresh` BIT
)
    COMMENT 'This procedure is used to get dues'
BEGIN

DECLARE smainquery MEDIUMTEXT DEFAULT '';
DECLARE squery TINYTEXT DEFAULT '';
SET smainquery ='';   
SET @squery ='';


IF @refresh=1 THEN
	DROP TABLE IF EXISTS loans_filtered_table;
END IF;


-- check see if table exists
-- CALL `check_table_existence`('loans_filtered_table', @table_exists);


-- check see if temporaray table exists
-- id not create it
IF @table_exists=0 THEN
	CALL `sp_get_loan_details`(branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, startDate,endDate,client_regstatus,fund_code,costcenters_code,client_type,user_id, currencies_id, product_prodid,loancategory1_code, loancategory2_code,order_by,group_by, 1,1,1,'','');
END IF;

DROP TABLE IF EXISTS sp_get_loan_payments_table1;

CREATE TEMPORARY TABLE sp_get_loan_payments_table1 AS (
	SELECT p.loan_number,p.members_idno,p.loanpayments_date,COALESCE(p.loanpayments_principal,CAST(0.00 AS DECIMAL(15,2)))loanpayments_principal,COALESCE(p.loanpayments_interest,CAST(0.00 AS DECIMAL(15,2)))loanpayments_interest,COALESCE(p.loanpayments_commission,0)loanpayments_commission,COALESCE(p.loanpayments_penalty,0)loanpayments_penalty,COALESCE(p.loanpayments_vat,0)loanpayments_vat,p.transactioncode,paymode transactiontypes_code,p.loanpayments_voucher FROM loanpayments p,loans_filtered_table l WHERE  p.loan_number=l.loan_number AND COALESCE(p.members_idno,'')= COALESCE(l.members_idno,''));

	SET @smainquery ='SELECT * from sp_get_loan_payments_table1 WHERE 1=1';
	
	IF  startDate IS NOT NULL THEN	
		
		SET @squery = CONCAT(@squery,'   AND loanpayments_date >=',QUOTE(startDate));
	END IF;
	
	IF  endDate IS NOT NULL THEN		
		SET @squery = CONCAT(@squery,' AND  loanpayments_date <=',QUOTE(endDate));
	END IF;
	
	IF loan_number!=''  AND loan_number IS NOT NULL THEN		
		SET @squery = CONCAT(@squery,' AND  loan_number =',QUOTE(loan_number));
	END IF;
	
	IF members_idno!=''  AND members_idno IS NOT NULL THEN		
		SET @squery = CONCAT(@squery,' AND  members_idno =',QUOTE(members_idno));
	END IF;
	
	SET @smainquery = CONCAT(@smainquery,@squery);
	
	 -- chech see if we are to create a temporary table
   -- this part used use by other stored procedures thats call this sp
  	IF addtemptable IS NOT NULL THEN
		
		IF addtemptable=1 THEN
			
			DROP TABLE IF EXISTS payments_filtered_table; 	
 	 	
			SET @smainquery = CONCAT('CREATE TEMPORARY TABLE payments_filtered_table AS (',@smainquery,')'); 
	
		END IF;
   END IF;
   
  
    
 --	insert into errors select @smainquery;
 	

	-- SELECT @smainquery;
   -- TO DO: Some parameters not relevant yet in this scope	
 	
   PREPARE stmt FROM @smainquery;
 	
 	 EXECUTE stmt;
 
	 DEALLOCATE PREPARE stmt;
   

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_payments_inperiod
DROP PROCEDURE IF EXISTS `sp_get_loan_payments_inperiod`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_payments_inperiod`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(5),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `user_id` CHAR(50),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `loancategory1_code` CHAR(50),
	IN `loancategory2_code` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50)
)
    COMMENT 'This procedure'
BEGIN

 -- get repayments 
 -- drop table if exists payments_filtered_table;
  
DROP TABLE IF EXISTS loans_filtered_table;
 
CALL `sp_get_loan_details`(branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, startDate,endDate,client_regstatus,fund_code,costcenters_code,client_type,user_id, currencies_id, product_prodid,loancategory1_code, loancategory2_code,order_by,group_by, true,true,true,'','');

IF startDate=''  THEN
	SET 	startDate = endDate;
END IF;

DROP TABLE IF EXISTS loan_payments_filter;

CREATE TEMPORARY TABLE loan_payments_filter AS (
SELECT loan_number FROM loanpayments p WHERE loanpayments_date 
BETWEEN   startDate AND endDate GROUP BY loan_number);

DROP TABLE IF EXISTS loans_filtered_table2;

CREATE TEMPORARY TABLE loans_filtered_table2 AS (
SELECT l.* FROM loans_filtered_table l,loan_payments_filter p WHERE l.loan_number = p.loan_number);


DROP TABLE IF EXISTS loans_filtered_table;

CREATE TEMPORARY TABLE loans_filtered_table AS (
SELECT * FROM loans_filtered_table2);

DROP TABLE IF EXISTS loans_filtered_table2;

DROP TABLE IF EXISTS loan_payments_filter;

DROP TABLE IF EXISTS  sp_get_loan_payments_start;

--  payments at start of period
CREATE TEMPORARY TABLE sp_get_loan_payments_start AS (
SELECT l.loan_number,l.members_idno,

CASE WHEN DATE(p.loanpayments_date) < DATE(startDate) AND SUM(p.loanpayments_principal) > 0 THEN SUM(COALESCE(p.loanpayments_principal,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  pprincstart,
CASE WHEN DATE(p.loanpayments_date) < DATE(startDate) AND SUM(p.loanpayments_interest) > 0 THEN SUM(COALESCE(p.loanpayments_interest,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  pintstart,
CASE WHEN DATE(p.loanpayments_date) < DATE(startDate) AND SUM(p.loanpayments_commission) > 0 THEN SUM(COALESCE(p.loanpayments_commission,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  pcommstart,
CASE WHEN DATE(p.loanpayments_date) < DATE(startDate) AND SUM(p.loanpayments_penalty) > 0 THEN SUM(COALESCE(p.loanpayments_penalty,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  ppenstart,
CASE WHEN DATE(p.loanpayments_date) < DATE(startDate) AND SUM(p.loanpayments_vat) > 0 THEN SUM(COALESCE(p.loanpayments_vat,0)) ELSE 0 END  pvatstart,

CASE WHEN DATE(p.loanpayments_date) > DATE(startDate) AND SUM(p.loanpayments_principal) > 0 THEN SUM(COALESCE(p.loanpayments_principal,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  pprincinperiod,
CASE WHEN DATE(p.loanpayments_date) > DATE(startDate) AND SUM(p.loanpayments_interest) > 0 THEN SUM(COALESCE(p.loanpayments_interest,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  pintinperiod,
CASE WHEN DATE(p.loanpayments_date) > DATE(startDate) AND SUM(p.loanpayments_commission) > 0 THEN SUM(COALESCE(p.loanpayments_commission,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  pcomminperiod,
CASE WHEN DATE(p.loanpayments_date) > DATE(startDate) AND SUM(p.loanpayments_penalty) > 0 THEN SUM(COALESCE(p.loanpayments_penalty,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  ppeninperiod,
CASE WHEN DATE(p.loanpayments_date) > DATE(startDate) AND SUM(COALESCE(p.loanpayments_vat,0)) > 0 THEN SUM(COALESCE(p.loanpayments_vat,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  pvatinperiod,

CASE WHEN DATE(p.loanpayments_date) <= DATE(endDate) AND SUM(COALESCE(loanpayments_principal,0)) > 0 THEN SUM(COALESCE(p.loanpayments_principal,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  tprinc,
CASE WHEN DATE(p.loanpayments_date) <= DATE(endDate) AND SUM(COALESCE(p.loanpayments_interest,0)) > 0 THEN SUM(COALESCE(p.loanpayments_interest,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  tint,
CASE WHEN DATE(p.loanpayments_date) <= DATE(endDate) AND SUM(COALESCE(p.loanpayments_commission,0)) > 0 THEN SUM(COALESCE(p.loanpayments_commission,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  tcomm,
CASE WHEN DATE(p.loanpayments_date) <= DATE(endDate) AND SUM(COALESCE(p.loanpayments_penalty,0)) > 0 THEN SUM(COALESCE(p.loanpayments_penalty,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  tpen,
CASE WHEN DATE(p.loanpayments_date) <= DATE(endDate) AND SUM(COALESCE(p.loanpayments_vat,0)) > 0 THEN SUM(COALESCE(p.loanpayments_vat,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  tvat

FROM loans_filtered_table l LEFT JOIN loanpayments p ON   p.loan_number=l.loan_number AND COALESCE(p.members_idno,'')= COALESCE(l.members_idno,'') AND DATE(p.loanpayments_date) <= DATE(endDate) GROUP BY p.loan_number,p.members_idno);

drop table if exists sp_get_loan_dues_start;

--  dues at start of period
CREATE TEMPORARY TABLE sp_get_loan_dues_start AS (
SELECT COALESCE(l.loan_number,'')loan_number,COALESCE(l.members_idno)members_idno,
CASE WHEN DATE(d.due_date) < DATE(startDate) AND SUM(COALESCE(d.due_principal,0)) > 0 THEN SUM(COALESCE(d.due_principal,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  dprincstart,
CASE WHEN DATE(d.due_date) < DATE(startDate) AND SUM(COALESCE(d.due_interest,0)) > 0 THEN SUM(COALESCE(d.due_interest,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  dintstart,
CASE WHEN DATE(d.due_date) < DATE(startDate) AND SUM(COALESCE(d.due_commission,0)) > 0 THEN SUM(COALESCE(d.due_commission,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END dcommstart,
CASE WHEN DATE(d.due_date) < DATE(startDate) AND SUM(COALESCE(d.due_penalty,0)) > 0 THEN SUM(COALESCE(d.due_penalty,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  dpenstart,
CASE WHEN DATE(d.due_date) < DATE(startDate) AND SUM(COALESCE(d.due_vat,0)) > 0 THEN SUM(COALESCE(d.due_vat,0)) ELSE 0 END  dvatstart,

CASE WHEN DATE(d.due_date) > DATE(startDate) AND SUM(COALESCE(d.due_principal,0)) > 0 THEN SUM(COALESCE(d.due_principal,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  dprincinperiod,
CASE WHEN DATE(d.due_date) > DATE(startDate) AND SUM(COALESCE(d.due_interest,0)) > 0 THEN SUM(COALESCE(d.due_interest,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  dintinperiod,
CASE WHEN DATE(d.due_date) > DATE(startDate) AND SUM(COALESCE(d.due_commission,0)) > 0 THEN SUM(COALESCE(d.due_commission,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  dcomminperiod,
CASE WHEN DATE(d.due_date) > DATE(startDate) AND SUM(COALESCE(d.due_penalty,0)) > 0 THEN SUM(COALESCE(d.due_penalty,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  dpeninperiod,
CASE WHEN DATE(d.due_date) > DATE(startDate) AND SUM(COALESCE(d.due_vat,0)) > 0 THEN SUM(COALESCE(d.due_vat,0)) ELSE 0 END  dvatinperiod,

CASE WHEN DATE(d.due_date) <= DATE(startDate) AND SUM(COALESCE(d.due_principal,0)) > 0 THEN SUM(COALESCE(d.due_principal,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  tdprinc,
CASE WHEN DATE(d.due_date) <= DATE(startDate) AND SUM(COALESCE(d.due_interest,0)) > 0 THEN SUM(COALESCE(d.due_interest,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  tdint,
CASE WHEN DATE(d.due_date) <= DATE(startDate) AND SUM(COALESCE(d.due_commission,0)) > 0 THEN SUM(COALESCE(d.due_commission,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  tdcomm,
CASE WHEN DATE(d.due_date) <= DATE(startDate) AND SUM(COALESCE(d.due_penalty,0)) > 0 THEN SUM(COALESCE(d.due_penalty,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  tdpen,
CASE WHEN DATE(d.due_date) <= DATE(startDate) AND SUM(COALESCE(d.due_vat,0)) > 0 THEN SUM(COALESCE(d.due_vat,0)) ELSE CAST(0.00 AS DECIMAL(15,2)) END  tdvat

FROM loans_filtered_table l LEFT JOIN dues d ON  l.loan_number=d.loan_number AND l.members_idno=d.members_idno AND DATE(d.due_date) <= DATE(endDate)
GROUP BY l.loan_number,l.members_idno);


SELECT l.*,
CASE WHEN d.dprincstart >= p.pprincstart THEN (d.dprincstart - p.pprincstart) WHEN p.pprincstart > d.dprincstart THEN CAST(0.00 AS DECIMAL(15,2)) END princpastdue,
CASE WHEN d.dintstart >= p.pintstart THEN (d.dintstart - p.pintstart) WHEN p.pintstart > d.dintstart THEN CAST(0.00 AS DECIMAL(15,2)) END intpastdue,
CASE WHEN d.dcommstart >= p.pcommstart THEN (d.dcommstart - p.pcommstart) WHEN p.pcommstart > d.dcommstart THEN CAST(0.00 AS DECIMAL(15,2)) END commpastdue,
CASE WHEN d.dpenstart >= p.ppenstart THEN (d.dpenstart - p.ppenstart) WHEN p.ppenstart > d.dpenstart THEN CAST(0.00 AS DECIMAL(15,2)) END penpastdue,
CASE WHEN d.dvatstart >= p.pvatstart THEN (d.dvatstart - p.pvatstart) WHEN p.pvatstart > d.dvatstart THEN CAST(0.00 AS DECIMAL(15,2)) END vatpastdue,

CASE WHEN p.pprincstart > d.dprincstart  THEN (p.pprincstart - d.dprincstart) + dprincinperiod ELSE dprincinperiod END dprincinperiod,
CASE WHEN p.pintstart > d.dintstart  THEN (p.pintstart - d.dintstart) + dintinperiod ELSE dintinperiod END dintinperiod,
CASE WHEN p.pcommstart > d.dcommstart THEN (p.pcommstart - d.dcommstart) + dcomminperiod ELSE dcomminperiod END dcomminperiod,
CASE WHEN p.ppenstart > d.dpenstart THEN (p.ppenstart - d.dpenstart) + dpeninperiod ELSE dpeninperiod END dpeninperiod,
CASE WHEN p.pvatstart > d.dvatstart THEN (p.pvatstart - d.dvatstart) + dvatinperiod ELSE dvatinperiod END dvatinperiod,
CASE WHEN p.tprinc > d.tdprinc THEN 100 ELSE ((p.tprinc/d.tdprinc)*100) END rprinc,
CASE WHEN p.tint > d.tdint THEN 100 ELSE ((p.tint/d.tdint)*100) END rint,
CASE WHEN p.tcomm > d.tdcomm THEN 100 ELSE ((p.tcomm/d.tdcomm)*100) END rcomm,
CASE WHEN p.tpen > d.tdpen THEN 100 ELSE ((p.tpen/d.tdpen)*100) END rpen,
CASE WHEN p.tvat > d.tdvat THEN 100 ELSE ((p.tvat/d.tdvat)*100) END rvat,
pprincinperiod,
pintinperiod,
pcomminperiod,
ppeninperiod,
pvatinperiod
FROM sp_get_loan_dues_start d INNER JOIN loans_filtered_table l  ON l.loan_number=d.loan_number LEFT OUTER JOIN sp_get_loan_payments_start p ON COALESCE(p.loan_number,'')=COALESCE(d.loan_number,'');

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_payments_sum
DROP PROCEDURE IF EXISTS `sp_get_loan_payments_sum`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_payments_sum`(
	IN `endDate` DATE
)
    COMMENT 'This procedure is used to get summarized loan payments'
BEGIN


-- CALLED FROM 
-- sp_get_outstanding_loan_balances

SET @smainquery ='';   

DROP TABLE IF EXISTS sp_get_loan_payments_table1;

CREATE TEMPORARY TABLE sp_get_loan_payments_table1 AS (
	SELECT p.loan_number,p.members_idno,SUM(p.loanpayments_principal)loanpayments_principal,SUM(p.loanpayments_interest)loanpayments_interest,SUM(p.loanpayments_commission)loanpayments_commission,SUM(p.loanpayments_penalty)loanpayments_penalty,SUM(p.loanpayments_vat)loanpayments_vat FROM loanpayments p,loansoutstanding l WHERE  p.loan_number=l.loan_number AND COALESCE(p.members_idno,'')= COALESCE(l.members_idno,'') AND DATE(p.loanpayments_date)<=endDate GROUP BY p.loan_number,p.members_idno);
   

	
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_portfolio_at_risk_details
DROP PROCEDURE IF EXISTS `sp_get_loan_portfolio_at_risk_details`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_portfolio_at_risk_details`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(5),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `user_id` CHAR(50),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `loancategory1_code` CHAR(50),
	IN `loancategory2_code` CHAR(50),
	IN `class1a` BIGINT,
	IN `class1b` BIGINT,
	IN `class2a` BIGINT,
	IN `class2b` BIGINT,
	IN `class3a` BIGINT,
	IN `class3b` BIGINT,
	IN `class4a` BIGINT,
	IN `class4b` BIGINT,
	IN `class5a` BIGINT,
	IN `class5b` BIGINT,
	IN `class6a` BIGINT,
	IN `class6b` BIGINT,
	IN `class7` BIGINT,
	IN `group_by` CHAR(50),
	IN `order_by` CHAR(50)
)
    COMMENT 'This sp is used to get portfolio at risk'
BEGIN

	CALL `sp_get_loan_arrears_details`(branch_code, client1_code, client2_code, client3_code,bussinesssector_code,areacode_code, endDate,client_regstatus,fund_code, costcenters_code, client_type, user_id, currencies_id, product_prodid,loancategory1_code,loancategory2_code, order_by, group_by, true,0);
	
-- calculate arrear classes	

SELECT a.*,
CASE WHEN arrdays >=class1a AND arrdays <=class1b THEN arrprinc ELSE CAST(0.00 AS DECIMAL(15,2)) END AS 'class1aclass1b',
CASE WHEN arrdays >=class2a AND arrdays <=class2b THEN arrprinc ELSE CAST(0.00 AS DECIMAL(15,2)) END AS 'class2aclass2b',
CASE WHEN arrdays >=class3a AND arrdays <=class3b THEN arrprinc ELSE CAST(0.00 AS DECIMAL(15,2)) END AS 'class3aclass3b',
CASE WHEN arrdays >=class4a AND arrdays <=class4b THEN arrprinc ELSE CAST(0.00 AS DECIMAL(15,2)) END AS 'class4aclass4b',
CASE WHEN arrdays >=class5a AND arrdays <=class5b THEN arrprinc ELSE CAST(0.00 AS DECIMAL(15,2)) END AS 'class5aclass5b',
CASE WHEN arrdays >=class6a AND arrdays <=class6b THEN arrprinc ELSE CAST(0.00 AS DECIMAL(15,2)) END AS 'class6aclass6b',
CASE WHEN arrdays >=class7 THEN arrprinc ELSE CAST(0.00 AS DECIMAL(15,2)) END AS 'class7' FROM loans_out_arr_table_final a;	
		
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_loan_schedule
DROP PROCEDURE IF EXISTS `sp_get_loan_schedule`;
DELIMITER //
CREATE PROCEDURE `sp_get_loan_schedule`(
	IN `loannumber` CHAR(50)






)
    COMMENT 'This procedure is used to get loan schedule'
BEGIN

	SELECT d.due_id theid,d.due_date ddate,d.due_principal principal,d.due_interest interest,d.due_penalty penalty,d.due_commission commission FROM dues d WHERE d.loan_number=loannumber;
	
SELECT FOUND_ROWS() AS reccount;


 
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_outstanding_loan_balances
DROP PROCEDURE IF EXISTS `sp_get_outstanding_loan_balances`;
DELIMITER //
CREATE PROCEDURE `sp_get_outstanding_loan_balances`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(5),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `user_id` CHAR(50),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `loancategory1_code` CHAR(50),
	IN `loancategory2_code` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50),
	IN `addtemptable` BIT,
	IN `loan_number_fr` CHAR(50),
	IN `loan_number_to` CHAR(50)
)
    COMMENT 'This sp is used to get outtsnfing balances'
BEGIN

-- get loan details
	CALL `sp_get_loan_details`(branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, endDate,endDate,client_regstatus,fund_code,costcenters_code,client_type,user_id, currencies_id, product_prodid,loancategory1_code, loancategory2_code,order_by,group_by,false , false ,true,loan_number_fr,loan_number_to);

DROP TABLE IF EXISTS table_disbursements;

CREATE TEMPORARY TABLE table_disbursements AS (	
SELECT d.loan_number,SUM(d.disbursements_amount) disamount FROM disbursements d WHERE DATE(d.disbursements_date)<=DATE(endDate) 
GROUP BY d.loan_number);

-- client loans
DROP TABLE IF EXISTS loans_out_table1;

CREATE TEMPORARY TABLE loans_out_table1 AS (
select ld.*, d.disamount  FROM loans_filtered_table ld,table_disbursements d WHERE d.loan_number=ld.loan_number);

DROP TABLE IF EXISTS loansoutstanding;

CREATE TEMPORARY TABLE loansoutstanding AS (SELECT * FROM loans_out_table1);

-- get dues
CALL `sp_get_loan_dues_sum`(endDate);
--
-- get repayments
CALL `sp_get_loan_payments_sum`(endDate);

DROP TABLE IF EXISTS loansoutstanding;

DROP TABLE IF EXISTS loans_balances_table1;

CREATE TEMPORARY TABLE loans_balances_table1 AS (SELECT
d.loan_number,
d.members_idno,
(d.due_principal-abs(COALESCE(loanpayments_principal,0))) pbalance,
(d.due_interest-abs(COALESCE(loanpayments_interest,0))) ibalance,
(d.due_penalty-abs(COALESCE(loanpayments_penalty,0))) penbalance,
(d.due_commission-abs(COALESCE(loanpayments_commission,0))) combalance,
(d.due_vat-abs(COALESCE(loanpayments_vat,0)))vbalance
FROM sp_get_loan_dues_table1 d LEFT OUTER JOIN sp_get_loan_payments_table1 p ON d.loan_number=p.loan_number AND d.members_idno=p.members_idno);
--
DROP TABLE IF EXISTS loans_out_table_final;
--
-- return balances
CREATE TEMPORARY TABLE loans_out_table_final AS (
select c.*,b.pbalance,b.ibalance,b.penbalance,b.combalance,b.vbalance,CAST(0 AS DECIMAL(15)) arrdays,CAST(0 AS DECIMAL(15,2)) as arrprinc,CAST(0 AS DECIMAL(15,2)) AS arrint,CAST(0 AS DECIMAL(15,2)) AS arrcomm,CAST(0 AS DECIMAL(15,2)) AS arrpen,CAST(0 AS DECIMAL(15,2)) AS arrvat FROM loans_out_table1 c,loans_balances_table1 b WHERE c.loan_number=b.loan_number AND c.members_idno=b.members_idno AND (pbalance > 0 OR ibalance > 0 OR penbalance > 0 OR combalance > 0));

ALTER TABLE `loans_out_table_final` ADD INDEX `loans_out_table_final_loan_number` (`loan_number`);


IF addtemptable  THEN

SELECT c.*,b.pbalance,b.ibalance,b.penbalance,b.combalance,b.vbalance, arrdays FROM loans_out_table_final b,loans_out_table1 c WHERE c.loan_number=b.loan_number;

 END IF ;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_personal_ledger
DROP PROCEDURE IF EXISTS `sp_get_personal_ledger`;
DELIMITER //
CREATE PROCEDURE `sp_get_personal_ledger`(
	IN `branch_code` TINYTEXT,
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `client_idno` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50),
	IN `plang` CHAR(4)
)
    COMMENT 'This Stores procedure is used to return client details'
BEGIN

-- get shares transactions
-- get savings transactions
-- loan transactions
-- merge transactions

-- TO DO : include share transactions


DECLARE cDescription VARCHAR(5) DEFAULT '';


DROP TABLE IF EXISTS sp_get_personal_savings_acc;
CREATE TEMPORARY TABLE sp_get_personal_savings_acc AS (SELECT l.savaccounts_account,l.client_idno,l.product_prodid FROM savaccounts l WHERE l.client_idno=client_idno group by l.savaccounts_account,l.client_idno,l.product_prodid);

-- get clientlons
DROP TABLE IF EXISTS sp_get_personal_ledger_loans_table1;
CREATE TEMPORARY TABLE sp_get_personal_ledger_loans_table1 AS (SELECT l.loan_number,f.transactioncode,l.client_idno  FROM loan l,loanfee f WHERE l.client_idno=client_idno AND l.loan_number=f.loan_number AND  DATE(f.loanfee_date)>=startDate AND DATE(f.loanfee_date)<=endDate);

-- get savings accounts
DROP TABLE IF EXISTS sp_get_personal_ledger_table1;

CREATE TEMPORARY TABLE sp_get_personal_ledger_table1 AS (SELECT 
CASE 
WHEN translations_id='1027' THEN 'SD'
WHEN translations_id='1201' THEN 'SW'
WHEN translations_id='1377' THEN 'IT'

WHEN translations_id='1350' THEN 'LR'
WHEN translations_id='1379' THEN 'CC'
WHEN translations_id='1105' THEN 'SC'
WHEN translations_id='1380' THEN 'RR'
WHEN translations_id='1381' THEN 'LI'
WHEN translations_id='311' THEN 'CA'
WHEN translations_id='1522' THEN 'LR-SA'
WHEN translations_id='1213' THEN 'SA'
WHEN translations_id='1229' THEN 'DI'
WHEN translations_id='1468' THEN 'LC'
WHEN translations_id='1600' THEN 'LLC'
WHEN translations_id='1591' THEN 'OPP'
WHEN translations_id='1592' THEN 'OPI'
WHEN translations_id='1594' THEN 'LII'
WHEN NULL THEN 'XXX' END labelcode,
CASE   
WHEN plang = 'EN' THEN translations_eng 
WHEN plang = 'SP' THEN translations_sp 
WHEN plang = 'FR' THEN translations_fr 
WHEN plang = 'SW' THEN translations_swa
WHEN plang = '' THEN 'Unknown' END descr

FROM translations WHERE translations_id IN ('1600','1594','1592','1591','373','1229','1350','1105','1027','1201','1376','1229','1377','1378','1379','1380','311','1381','1522','1213','1229','1468'));

	SELECT SQL_CALC_FOUND_ROWS COALESCE(s.savtransactions_balance,CAST(0.00 AS DECIMAL(15,2))) INTO @nBal FROM savtransactions s WHERE s.savaccounts_account = client_idno  AND  DATE(s.savtransactions_tday)<=startDate  ORDER BY s.savtransactions_tday  DESC LIMIT 1; 	
	
	 SELECT FOUND_ROWS()  INTO @nCount;
	
		IF @nCount=0 THEN
		   SET @nBal = CAST(0.00 AS DECIMAL(15,2));
		END IF;
		

		DROP TABLE IF EXISTS sp_get_op_dues;
				 	
		 CREATE TEMPORARY TABLE sp_get_op_dues  AS (SELECT 		  
			client_idno client_idno,				
			SUM(COALESCE(disbursements_amount,CAST(0.00 AS DECIMAL(15,2)))) principal,
			CAST(0.00 AS DECIMAL(15,2)) interest,
			00000000000000 commission		
			FROM disbursements d,loan l WHERE d.loan_number=l.loan_number AND l.client_idno = client_idno AND DATE(d.disbursements_date)< startDate);
			
			
			SELECT SUM(b.due_interest) INTO @d_interest from dues b where b.loan_number IN (select d.loan_number FROM disbursements d,loan l WHERE d.loan_number=l.loan_number AND l.client_idno = client_idno and  d.loan_number=b.loan_number) and DATE(b.due_date)< startDate;
			
			
		DROP TABLE IF EXISTS sp_get_paid;
	
		CREATE TEMPORARY TABLE sp_get_paid  AS (SELECT 
			client_idno  client_idno,				
			SUM(COALESCE(loanpayments_principal,CAST(0.00 AS DECIMAL(15,2)))) principal,
			SUM(COALESCE(loanpayments_interest,CAST(0.00 AS DECIMAL(15,2)))) interest,
			SUM(COALESCE(loanpayments_commission,CAST(0.00 AS DECIMAL(15,2)))) commission		
			FROM loanpayments p, loan l WHERE p.loan_number=l.loan_number  AND TRIM(l.client_idno) =client_idno AND DATE(p.loanpayments_date)<startDate);
			

DROP TABLE IF EXISTS sp_get_personal_ledger_table2;

CREATE TEMPORARY TABLE sp_get_personal_ledger_table2  AS ( SELECT tdate,voucher, descr ttcode,savamount,damount,principal,interest,commission,savbalance,princbal,intbal,commbal,tloanbal,id,product_prodid FROM (


	SELECT * FROM (
	
		SELECT s.savtransactions_tday tdate,
			COALESCE(s.savtransactions_voucher,'') voucher,
			s.transactiontypes_code ttcode,		
			s.product_prodid,	
			CASE WHEN s.savtransactions_amount<0 THEN (s.savtransactions_amount + savtransactions_commission) ELSE  (s.savtransactions_amount - savtransactions_commission) END savamount,
			CAST(0.00 AS DECIMAL(15,2)) damount,
			CAST(0.00 AS DECIMAL(15,2)) principal,
			CAST(0.00 AS DECIMAL(15,2)) interest,
			savtransactions_commission commission,			
			s.savtransactions_balance savbalance,
			CAST(0.00 AS DECIMAL(15,2)) princbal,
			CAST(0.00 AS DECIMAL(15,2)) intbal,
			CAST(0.00 AS DECIMAL(15,2)) commbal,
			CAST(0.00 AS DECIMAL(15,2)) tloanbal,
			uuid() id
			FROM savtransactions s LEFT JOIN loanfee l ON s.transactioncode=l.transactioncode  WHERE TRIM(s.savaccounts_account)=client_idno AND DATE(s.savtransactions_tday)>=startDate AND DATE(s.savtransactions_tday)<=endDate  
			UNION ALL
			SELECT 
			startDate  tdate,
			'' voucher,
			'OPP' ttcode,
			'' product_prodid,
			 @nBal  savamount,	
			(COALESCE(d.principal,CAST(0.00 AS DECIMAL(15,2))) - COALESCE(p.principal,CAST(0.00 AS DECIMAL(15,2)))) damount,	
			(COALESCE(d.principal,CAST(0.00 AS DECIMAL(15,2))) - COALESCE(p.principal,CAST(0.00 AS DECIMAL(15,2)))) principal,
			(COALESCE(@d_interest,CAST(0.00 AS DECIMAL(15,2))) - COALESCE(p.interest,CAST(0.00 AS DECIMAL(15,2)))) interest,
			(COALESCE(d.commission,CAST(0.00 AS DECIMAL(15,2))) -  COALESCE(p.commission,CAST(0.00 AS DECIMAL(15,2)))) commission,	
			CAST(0.00 AS DECIMAL(15,2)) savbalance,		
			00000000000000000000.00 princbal,
			CAST(0.00 AS DECIMAL(15,2)) intbal,
			CAST(0.00 AS DECIMAL(15,2)) commbal,
			CAST(0.00 AS DECIMAL(15,2)) tloanbal,
			uuid() id
		   FROM sp_get_op_dues  d LEFT JOIN  sp_get_paid p ON d.client_idno=p.client_idno			 
			UNION ALL			
			SELECT 
			loanpayments_date  tdate,
			COALESCE(loanpayments_voucher,'') voucher,
			'LR' ttcode,
			l.product_prodid,	

			CAST(0.00 AS DECIMAL(15,2)) savamount,	
			CAST(0.00 AS DECIMAL(15,2)) damount,	
			-(loanpayments_principal) principal,
			-(loanpayments_interest) interest,
			-(loanpayments_commission) commission,	
			CAST(0.00 AS DECIMAL(15,2))  savbalance,		
			00000000000000000000.00 princbal,
			CAST(0.00 AS DECIMAL(15,2)) intbal,
			CAST(0.00 AS DECIMAL(15,2)) commbal,
			CAST(0.00 AS DECIMAL(15,2)) tloanbal,
			uuid() id
			FROM loanpayments p, loan l WHERE p.loan_number=l.loan_number  AND TRIM(l.client_idno) =client_idno AND DATE(p.loanpayments_date)>=startDate AND DATE(p.loanpayments_date)<=endDate 
			UNION ALL
		SELECT 
			DATE(d.due_date) tdate,
			'' voucher,
			'LII' ttcode,
			' ' product_prodid,	
			CAST(0.00 AS DECIMAL(15,2)) savamount,	
			CAST(0.00 AS DECIMAL(15,2)) damount,	
			CAST(0.00 AS DECIMAL(15,2)) principal,
			CAST(0.00 AS DECIMAL(15,2)) interest,
			CAST(0.00 AS DECIMAL(15,2)) commission,	
			CAST(0.00 AS DECIMAL(15,2)) savbalance,		
			00000000000000000000.00 princbal,
			CAST(0.00 AS DECIMAL(15,2)) intbal,
			CAST(0.00 AS DECIMAL(15,2)) commbal,	
			'00000000000000' tloanbal,
			uuid() id
			FROM dues d,loan l WHERE d.loan_number=l.loan_number AND l.client_idno = client_idno 
			AND DATE(d.due_date)>=startDate AND DATE(d.due_date)<=endDate 	 AND  due_interest >0 
			UNION ALL			
			SELECT 
			disbursements_date tdate,
			'' voucher,
			'DI' ttcode,
			l.product_prodid,		
			 CAST(0.00 AS DECIMAL(15,2)) savamount,	
			COALESCE(disbursements_amount, CAST(0.00 AS DECIMAL(15,2))) damount,	
			 CAST(0.00 AS DECIMAL(15,2)) principal,
			 CAST(0.00 AS DECIMAL(15,2)) interest,
			 CAST(0.00 AS DECIMAL(15,2)) commission,	
			 CAST(0.00 AS DECIMAL(15,2)) savbalance,		
			 CAST(0.00 AS DECIMAL(15,2))princbal,
			 CAST(0.00 AS DECIMAL(15,2)) intbal,
			 CAST(0.00 AS DECIMAL(15,2)) commbal,	
			'00000000000000' tloanbal,
			uuid() id
			FROM disbursements d,loan l WHERE d.loan_number=l.loan_number AND l.client_idno =client_idno AND DATE(d.disbursements_date)>=startDate AND DATE(d.disbursements_date)<=endDate
			UNION ALL	
SELECT l.loanfee_date tdate,
			COALESCE(l.loanfee_voucher,'') voucher,
			'LLC' ttcode,		
			'' product_prodid,	
			 CAST(0.00 AS DECIMAL(15,2)) savamount,
			 CAST(0.00 AS DECIMAL(15,2)) damount,
			 CAST(0.00 AS DECIMAL(15,2)) principal,
			 CAST(0.00 AS DECIMAL(15,2)) interest,
			l.loanfee_amount commission,			
			 CAST(0.00 AS DECIMAL(15,2)) savbalance,
			 CAST(0.00 AS DECIMAL(15,2)) princbal,
			 CAST(0.00 AS DECIMAL(15,2)) intbal,
			 CAST(0.00 AS DECIMAL(15,2)) commbal,
			 CAST(0.00 AS DECIMAL(15,2)) tloanbal,
			uuid() id
			FROM loanfee l WHERE l.transactioncode NOT IN (SELECT s.transactioncode  FROM savtransactions s,sp_get_personal_ledger_loans_table1 f WHERE s.transactioncode=f.transactioncode AND s.savaccounts_account=f.client_idno) AND DATE(l.loanfee_date)>=startDate AND DATE(l.loanfee_date)<=endDate  AND l.client_idno=client_idno
		
			) tbals ) plegder LEFT JOIN sp_get_personal_ledger_table1 ON sp_get_personal_ledger_table1.labelcode=plegder.ttcode ORDER BY tdate ASC);

	SET  @prevsavbalance :=  CAST(0.00 AS DECIMAL(15,2));
	SET  @prevprincbal :=  CAST(0.00 AS DECIMAL(15,2));
	SET  @previntbal :=  CAST(0.00 AS DECIMAL(15,2));
	SET  @prevcommbal :=  CAST(0.00 AS DECIMAL(15,2));


	SET  @prevtloanbal =  CAST(0.00 AS DECIMAL(15,2));
	SET @prevprincbal  =  CAST(0.00 AS DECIMAL(15,2));
	SET @previntbal =  CAST(0.00 AS DECIMAL(15,2));
	
	UPDATE sp_get_personal_ledger_table2 AS w
	
	SET 	
	  princbal=(@prevprincbal:= (@prevprincbal + w.damount) - abs(IF(w.principal < 0,w.principal,0))),
	 savbalance=(@prevsavbalance:=  (@prevsavbalance + w.savamount)-commission),		
	 intbal=(@previntbal:= (@previntbal + w.interest)),
	 commbal= CAST(0.00 AS DECIMAL(15,2)),
	 tloanbal =(@prevtloanbal:= (@prevtloanbal +  w.damount  - abs(IF(w.principal < 0,w.principal,0)))) WHERE  w.id=w.id	ORDER BY tdate ASC;

UPDATE sp_get_personal_ledger_table2 SET tloanbal= CAST(0.00 AS DECIMAL(15,2)) WHERE tloanbal < 0;
	
UPDATE sp_get_personal_ledger_table2 SET tdate=NULL WHERE tdate='0000-00-00 00:00:00';
	
 SELECT DATE(tdate) tdate,voucher, CONCAT('<p align=left>',ttcode,'</p>') ttcode,FORMAT(savamount,2) savamount,FORMAT(damount,2) damount,FORMAT(principal,2) principal,FORMAT(interest,2) interest,commission,CONCAT('<b>',FORMAT(savbalance,2),'</b>') savbalance,princbal,FORMAT(intbal,2) intbal,commbal,CONCAT('<b>',FORMAT(tloanbal,2),'</b>') tloanbal,id,product_prodid 
 FROM sp_get_personal_ledger_table2 WHERE (CAST(damount as DECIMAL(15,2)) != 0.00 OR  CAST(commission as DECIMAL(15,2)) != 0.00 OR CAST(interest as DECIMAL(15,2)) != 0.00  OR  CAST(principal as DECIMAL(15,2)) != 0.00 OR CAST(savamount as DECIMAL(15,2))  !=  0.00 OR ttcode='OPP')
UNION ALL 
SELECT 
			DATE(guarantors_datecreated) tdate,
			loan_number voucher,
			'GUA' ttcode,			
			0.00 savamount,	
			0.00 damount,	
			0.00 principal,
			0.00 interest,
			0.00 commission,		
			0.00 savbalance,		
			0.00 princbal,
			0.00 intbal,
			0.00 commbal,		
			0.00 tloanbal,
			'' id,
			'' product_prodid 			
			FROM guarantors g WHERE g.client_idno =client_idno;
			
			
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_personal_ledger_old
DROP PROCEDURE IF EXISTS `sp_get_personal_ledger_old`;
DELIMITER //
CREATE PROCEDURE `sp_get_personal_ledger_old`(
	IN `branch_code` TINYTEXT,
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `client_idno` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50),
	IN `plang` CHAR(4)


)
    COMMENT 'This Stores procedure is used to return client details'
BEGIN
-- get shares transactions
-- get savings transactions
-- loan transactions
-- merge transactions

-- TO DO : include share transactions


DECLARE cDescription VARCHAR(5) DEFAULT '';


DROP TABLE IF EXISTS sp_get_personal_savings_acc;
CREATE TEMPORARY TABLE sp_get_personal_savings_acc AS (SELECT l.savaccounts_account,l.client_idno,l.product_prodid FROM savaccounts l WHERE l.client_idno=client_idno);


-- get savings accounts
DROP TABLE IF EXISTS sp_get_personal_ledger_table1;


CREATE TEMPORARY TABLE sp_get_personal_ledger_table1 AS (SELECT 
CASE 
WHEN translations_id='1027' THEN 'SD'
WHEN translations_id='1376' THEN 'SW'
WHEN translations_id='1377' THEN 'IT'
WHEN translations_id='1378' THEN 'LR'
WHEN translations_id='1379' THEN 'CC'
WHEN translations_id='1380' THEN 'RR'
WHEN translations_id='1381' THEN 'LI'
WHEN translations_id='311' THEN 'CA'
WHEN translations_id='1522' THEN 'LR-SA'
WHEN translations_id='1213' THEN 'SA'
WHEN translations_id='1229' THEN 'DI'
WHEN translations_id='1468' THEN 'LC'
WHEN NULL THEN NULL END labelcode,
CASE   
WHEN plang = 'EN' THEN translations_eng 
WHEN plang = 'SP' THEN translations_sp 
WHEN plang = 'FR' THEN translations_fr 
WHEN plang = 'SW' THEN translations_swa
WHEN plang = '' THEN 'Unknown' END descr

FROM translations WHERE translations_id IN ('1027','1376','1377','1378','1379','1380','311','1381','1522','1213','1229','1468'));

DROP TABLE IF EXISTS sp_get_personal_ledger_table2;

CREATE TEMPORARY TABLE sp_get_personal_ledger_table2 AS ( SELECT tdate,descr,voucher, ttcode,nshares,nshaval,tnshares,savamount,damount,principal,interest,commission,penalty,_vat,savbalance,shabalance,princbal,intbal,commbal,penbal,vatbal,tloanbal,id,product_prodid FROM (

	SELECT * FROM (
		SELECT savtransactions_tday tdate,
			COALESCE(s.savtransactions_voucher,'') voucher,
			CASE WHEN l.transactioncode IS NULL  THEN s.transactiontypes_code ELSE 'LC'  END ttcode,		
			s.product_prodid,	
			0.00 nshares,
			0.00 nshaval,
			0.00 tnshares,
			savtransactions_amount savamount,
			0.00 damount,
			0.00 principal,
			0.00 interest,
			0.00 commission,
			0.00 penalty,
			0.00 _vat,
			s.savtransactions_balance savbalance,
			0.00 shabalance,
			0.00 princbal,
			0.00 intbal,
			0.00 commbal,
			0.00 penbal,
			0.00 vatbal,
			0.00 tloanbal,
			uuid() id
			FROM savtransactions s INNER JOIN sp_get_personal_savings_acc a ON a.product_prodid=s.product_prodid AND a.savaccounts_account=s.savaccounts_account LEFT JOIN loanfee l ON s.transactioncode=l.transactioncode  WHERE a.client_idno=client_idno
			UNION ALL
			SELECT due_date tdate,		
			'' voucher,
			'LI' ttcode,
			l.product_prodid,	
			'0.00' nshares,
			'0.00' nshaval,
			0.00 tnshares,
			0.00 savamount,	
			'0.00' damount,		
			due_principal principal,
			due_interest interest,
			due_commission commission,
			due_penalty penalty,
			due_vat _vat,
			0.0 savbalance,
			0.0 shabalance,
			0.0 princbal,
			0.0 intbal,
			0.0 commbal,
			0.0 penbal,
			0.0 vatbal,
			0.0 tloanbal,
			uuid() id
			FROM dues d , loan l WHERE  l.loan_number=l.loan_number AND TRIM(l.client_idno) = client_idno
			UNION ALL	
			SELECT disbursements_date tdate,		
			'' voucher,
			'TT' ttcode,
			'' product_prodid,	
			'0.00' nshares,
			'0.00' nshaval,
			'0.00' tnshares,
			0 savamount,	
			00 damount,		
			'0' principal,
			 SUM(due_interest) interest,
			 SUM(due_commission) commission,
			 SUM(due_penalty) penalty,
			'0' _vat,
			0.0 savbalance,
			0.0 shabalance,
			0.0 princbal,
			0.00 intbal,
			0.00 commbal,
			0.00 penbal,
			0.00vatbal,
			0.00 tloanbal,
			uuid() id
			FROM dues d , disbursements l,loan ls where d.loan_number=ls.loan_number AND d.loan_number=l.loan_number AND ls.client_idno =client_idno GROUP BY d.loan_number
			UNION ALL		
			SELECT 
			loanpayments_date  tdate,
			COALESCE(loanpayments_voucher,'') voucher,
			COALESCE(paymode,'') ttcode,
			l.product_prodid,			
			0.00 nshares,
			0.00 nshaval,
			0.00 tnshares,
			0.00 savamount,	
			00000000000000 damount,	
			-(loanpayments_principal) principal,
			-(loanpayments_interest) interest,
			-(loanpayments_commission) commission,
			-(loanpayments_penalty) penalty,
			-(loanpayments_vat) _vat,
			0.00 savbalance,
			00000000000000 shabalance,
			00000000000000 princbal,
			00000000000000 intbal,
			00000000000000 commbal,
			00000000000000 penbal,
			00000000000000 vatbal,
			00000000000000 tloanbal,
			uuid() id
			FROM loanpayments p, loan l WHERE p.loan_number=l.loan_number  AND l.client_idno =client_idno
			UNION ALL			
			SELECT 
			DATE(disbursements_date)  tdate,
			'' voucher,
			'DI' ttcode,
			l.product_prodid,			
			0.00 nshares,
			0.00 nshaval,
			0.00 tnshares,
			0.00 savamount,	
			COALESCE(disbursements_amount) damount,	
			00000000000000 principal,
			00000000000000 interest,
			00000000000000 commission,
			00000000000000 penalty,
			00000000000000 _vat,
			0 savbalance,
			00000000000000 shabalance,
			00000000000000 princbal,
			00000000000000 intbal,
			00000000000000 commbal,
			00000000000000 penbal,
			00000000000000 vatbal,
			'00000000000000' tloanbal,
			uuid() id
			FROM disbursements d,loan l WHERE d.loan_number=l.loan_number AND l.client_idno =client_idno
			
			) tbals ) plegder LEFT JOIN sp_get_personal_ledger_table1 ON sp_get_personal_ledger_table1.labelcode=plegder.ttcode ORDER BY tdate ASC);

	SET  @prevsavbalance := 0;
	SET  @prevprincbal := 0;
	SET  @previntbal := 0;
	SET  @prevcommbal := 0;
	SET  @prevpenbal := 0;
	SET  @prevvatbal := 0;
	SET  @prevtloanbal := 0;
	SET  @prevshabalance := 0;
	
	UPDATE sp_get_personal_ledger_table2 AS w
	
	SET tdate =DATE(tdate),
	 savbalance=(@prevsavbalance:= (@prevsavbalance + w.savamount)),	
	 shabalance = (@prevshabalance:= (@prevshabalance +( w.nshaval*w.nshares))),
	 princbal=(@prevprincbal:= (@prevprincbal + w.damount) -abs(IF(w.principal< 0,w.principal,0))),
	 tnshares=(@prevtnshares:= (@prevtnshares + w.tnshares)),
	 intbal=(@previntbal:= (@previntbal + abs(IF(w.ttcode='TT' ,w.interest,0)))-abs(IF(w.interest < 0,w.interest,0))),
	 commbal=(@prevcommbal:= (@prevcommbal + w.commission)),
	 penbal=(@prevpenbal:= (@prevpenbal + w.penalty)),
	 vatbal=(@prevvatbal:= (@prevvatbal + w._vat)),
	 tloanbal =(@prevtloanbal:= (@prevtloanbal +  w.damount  - abs(IF(w.principal < 0,w.principal,0)) -abs(IF(w.interest < 0,w.interest,0)) -abs(IF(w.commission < 0,w.commission,0))-abs(IF(w.penalty < 0,w.penalty,0)) + abs(IF(w.ttcode='TT' ,w.interest,0)) +abs(IF(w.ttcode='TT' ,w.penalty,0)) + abs(IF(w.ttcode='TT' ,w.commission,0)))) WHERE  w.id=w.id	ORDER BY tdate ASC;
	
UPDATE sp_get_personal_ledger_table2 SET tdate=NULL WHERE tdate='0000-00-00 00:00:00';
	
 SELECT * FROM sp_get_personal_ledger_table2
UNION ALL 
SELECT 
			DATE(guarantors_datecreated) tdate,
			loan_number voucher,
			'GUA' ttcode,
			'' product_prodid,			
			'' nshares,
			'' nshaval,
			'' tnshares,
			'' savamount,	
			'' damount,	
			'' principal,
			'' interest,
			'' commission,
			'' penalty,
			'' _vat,
			'' savbalance,
			'' shabalance,
			'' princbal,
			'' intbal,
			'' commbal,
			'' penbal,
			'' vatbal,
			'' tloanbal,
			'' id,
			'' 
			FROM guarantors g WHERE g.client_idno =client_idno;
			

			
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_princ_int_due
DROP PROCEDURE IF EXISTS `sp_get_princ_int_due`;
DELIMITER //
CREATE PROCEDURE `sp_get_princ_int_due`(
	IN `branch_code` TINYTEXT,
	IN `product_prodid` CHAR(10),
	IN `asatdate` DATE,
	IN `regstatus` CHAR(5)
)
BEGIN

DECLARE rowcount BIGINT DEFAULT 0;
DECLARE totalnrows  BIGINT DEFAULT 0;
DECLARE vloan_number  CHAR(50) DEFAULT '';
DECLARE vmembers_idno  CHAR(50) DEFAULT '';
DECLARE vprincipalbal NUMERIC(15,4) DEFAULT 0;
DECLARE varInt NUMERIC(15,4) DEFAULT 0;
DECLARE no_int CHAR(1);
DECLARE recalint CHAR(1);
DECLARE cstatus CHAR(10);


DROP TABLE IF EXISTS loans_filtered_table;
 
CALL `sp_get_loan_details`(branch_code, '', '', '', '', '', '','','','','','','', '','','', '','','', true,true,true,'','');


DROP TABLE IF EXISTS loans_filtered_table_temp;
CREATE TEMPORARY TABLE loans_filtered_table_temp AS (SELECT l.loan_number,l.client_surname,l.client_firstname,l.client_middlename,l.client_idno,l.client_grpname,l.loan_amount,l.client_regstatus,l.product_prodid,l.members_idno,SUM(disbursements_amount) disbursements_amount FROM loans_filtered_table l,disbursements d WHERE DATE(d.disbursements_date) < DATE(asatdate) AND l.loan_number=d.loan_number GROUP BY l.loan_number,l.client_surname,l.client_firstname,l.client_middlename,l.client_idno,l.members_idno);


DROP TABLE IF EXISTS loans_filtered_table;
CREATE TEMPORARY TABLE loans_filtered_table AS (SELECT * FROM loans_filtered_table_temp LIMIT 2);

DROP TABLE IF EXISTS loans_filtered_table_temp;

-- get amounts payable
 -- DROP TABLE IF EXISTS sp_get_charge_int;
 
-- CREATE TEMPORARY TABLE sp_get_charge_int AS (SELECT COALESCE(product_prodid,'') prodid,COALESCE(productconfig_value,0) value FROM productconfig WHERE productconfig_paramname='CHARGE_INT' GROUP BY product_prodid);

IF asatdate='' OR  asatdate='0000-00-00' OR asatdate IS NULL THEN
	SELECT NOW() INTO asatdate;
END IF;

-- get amounts payable
 DROP TABLE IF EXISTS sp_get_loan_dues;
 
--  total dues and dues before current date
CREATE TEMPORARY TABLE sp_get_loan_dues AS (select
l.loan_number,
d.members_idno,
SUM(COALESCE(d.due_principal,0))  ddprinc,
 SUM(COALESCE(d.due_interest,0))	ddint,
 SUM(COALESCE(d.due_commission,0)) ddcomm,
 SUM(COALESCE(d.due_penalty,0)) ddpen,
SUM(COALESCE(d.due_vat,0))  ddvat,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_principal,0) ELSE 0 END) dprinc,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_interest,0) ELSE 0 END) dint,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_commission,0) ELSE 0 END) dcomm,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_penalty,0) ELSE 0 END) dpen,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_vat,0) ELSE 0 END) dvat,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_principal,0) ELSE 0 END) nprinc,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_interest,0) ELSE 0 END) nint,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_commission,0) ELSE 0 END) ncomm,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_penalty,0) ELSE 0 END) npen,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_vat,0) ELSE 0 END) nvat
FROM  dues d ,loans_filtered_table l WHERE d.loan_number =l.loan_number  GROUP BY d.loan_number,d.members_idno);

-- get all payments
DROP TABLE IF EXISTS sp_get_loan_payments;
 
CREATE TEMPORARY TABLE sp_get_loan_payments (SELECT 
p.loan_number,
p.members_idno,
SUM(COALESCE(p.loanpayments_principal,0)) pprinc,
SUM(COALESCE(p.loanpayments_interest,0)) pint,
SUM(COALESCE(p.loanpayments_commission,0)) pcomm,
SUM(COALESCE(p.loanpayments_penalty,0)) ppen, 
SUM(COALESCE(p.loanpayments_vat,0)) pvat
FROM  loanpayments p,loans_filtered_table l WHERE p.loan_number =l.loan_number  GROUP BY p.loan_number,p.members_idno);



DROP TABLE IF EXISTS sp_get_out_loan_dues1;

CREATE TEMPORARY TABLE sp_get_out_loan_dues1 (SELECT 
d.loan_number,
d.members_idno,
SUM(COALESCE(d.dprinc,0))-SUM(COALESCE(p.pprinc,0)) outprinc,
SUM(COALESCE(d.dint,0))-SUM(COALESCE(p.pint,0)) outint,
SUM(COALESCE(d.dcomm,0))-SUM(COALESCE(p.pcomm,0)) outcom,
SUM(COALESCE(d.dpen,0))-SUM(COALESCE(p.ppen,0)) outpen,
SUM(COALESCE(d.dvat,0))-SUM(COALESCE(p.pvat,0)) outvat,
SUM(d.dprinc)+SUM(COALESCE(d.nprinc,0))- SUM(COALESCE(p.pprinc,0)) cprinc,
SUM(d.dint)+SUM(COALESCE(d.nint,0)) - SUM(COALESCE(p.pint,0)) cint,
SUM(d.dcomm)+SUM(COALESCE(d.ncomm,0)) - SUM(COALESCE(p.pcomm,0)) ccomm,
SUM(d.dpen)+(SUM(COALESCE(d.npen,0)) - SUM(COALESCE(p.ppen,0))) cpen,
SUM(d.ddvat)+(SUM(COALESCE(d.nvat,0)) - SUM(COALESCE(p.pvat,0))) cdvat ,
COALESCE(p.pprinc,0) pprinc,
COALESCE(d.ddint,0) ddint,
COALESCE(d.ddcomm,0) ddcomm,
COALESCE(d.ddpen,0) ddpen,
COALESCE(d.ddvat,0) ddvat
FROM sp_get_loan_dues d LEFT JOIN sp_get_loan_payments p ON p.loan_number=d.loan_number 
AND p.members_idno=d.members_idno GROUP BY d.loan_number,d.members_idno);

DROP TABLE IF EXISTS sp_get_loan_dues;

DROP TABLE IF EXISTS sp_get_loan_payments;

DROP TABLE IF EXISTS sp_get_out_loan_dues2;

CREATE TEMPORARY TABLE sp_get_out_loan_dues2 (
SELECT CONCAT(l.client_firstname,' ',l.client_middlename,' ',l.client_surname,l.client_grpname) name,
l.loan_number,
l.client_idno,
l.loan_amount,
d.members_idno,
l.client_regstatus,
l.product_prodid,
(CASE WHEN d.cprinc < 0 THEN  CAST(0.00 AS DECIMAL(15,2)) ELSE d.cprinc END) dprinc,
(CASE WHEN d.cint < 0 THEN  CAST(0.00 AS DECIMAL(15,2)) ELSE d.cint END) dint,
(CASE WHEN d.ccomm < 0 THEN  CAST(0.00 AS DECIMAL(15,2)) ELSE d.ccomm END) dcomm,
(CASE WHEN d.cpen < 0 THEN  CAST(0.00 AS DECIMAL(15,2)) ELSE d.cpen END) dpen,
(CASE WHEN d.cdvat < 0 THEN  CAST(0.00 AS DECIMAL(15,2)) ELSE d.cdvat END) dvat,
COALESCE(d.pprinc, CAST(0.00 AS DECIMAL(15,2))) princpaid,
outprinc,
outint,
outcom,
outpen,
outvat
FROM sp_get_out_loan_dues1 d, loans_filtered_table l WHERE l.loan_number=d.loan_number);

DROP TABLE IF EXISTS sp_get_loan_dues_payable4;

CREATE TEMPORARY TABLE sp_get_loan_dues_payable4 AS (

SELECT l.*,(SUM(COALESCE(disbursements_amount,0))- COALESCE(princpaid,0)) outbal FROM sp_get_out_loan_dues2 l,disbursements d WHERE d.loan_number=l.loan_number  GROUP BY d.loan_number,l.members_idno);

DROP TABLE IF EXISTS sp_get_out_loan_dues2;

-- recalculculate interest
SELECT COUNT(loan_number) INTO @totalnrows FROM sp_get_loan_dues_payable4;

SET rowcount = 1;

 --  SELECT COALESCE(productconfig_value,0) FROM productconfig WHERE productconfig_paramname ='RECALC_INT' AND product_prodid=@vproduct_prodid GROUP BY product_prodid,productconfig_value;
	
 WHILE rowcount <= @totalnrows DO
  	BEGIN   
   
   SELECT loan_number,outbal,members_idno,product_prodid,client_regstatus INTO @vloan_number,@vprincipalbal,@vmembers_idno,@vproduct_prodid,@cstatus FROM sp_get_loan_dues_payable4 LIMIT rowcount, 1;
		
   SELECT COALESCE(productconfig_value,0) INTO @recalint FROM productconfig WHERE productconfig_paramname ='RECALC_INT' AND product_prodid=@vproduct_prodid GROUP BY product_prodid,productconfig_value;

	-- check see if we should ignore evaluating interest
	IF @recalint='1'  THEN
		
		IF @cstatus='EXT' AND @no_int='1' THEN
		
			SELECT 0 INTO @varInt;
			
		ELSE
		
			IF @vprincipalbal > 0 THEN
				CALL `sp_recalculate_int`('', @vloan_number,@vmembers_idno, asatdate, @vprincipalbal,@vproduct_prodid,'1');	 
		
	 	 		SELECT interest INTO @varInt FROM sp_interest_table;
	 	 		
	 	 	END IF; 
 		END IF;
 	 	
 	ELSE
	 SET 	@varInt = 0;			
	END IF;		
    
   IF @varInt < 0 THEN
   	SET @varInt = 0 ;
   END IF; 
   
	UPDATE sp_get_loan_dues_payable4 SET dint = @varInt WHERE loan_number=@vloan_number AND members_idno = @vmembers_idno;	 
      
	SET rowcount  = rowcount + 1;
	
 	END; 
 	
END while; 

-- SELECT SQL_CALC_FOUND_ROWS b.*,s.balance savbal FROM sp_get_loan_dues_payable4 b,savingsbalances s WHERE  b.client_idno=s.savaccounts_account;

SELECT SQL_CALC_FOUND_ROWS b.*,s.balance savbal FROM sp_get_loan_dues_payable4 b LEFT JOIN savingsbalances s ON s.clientidno=b.client_idno;
-- WHERE dprinc > 0 OR dcomm > 0  OR dpen > 0 OR dvat > 0;


SET @reccount = FOUND_ROWS();

SELECT @reccount AS reccount;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_profit_per_period
DROP PROCEDURE IF EXISTS `sp_get_profit_per_period`;
DELIMITER //
CREATE PROCEDURE `sp_get_profit_per_period`(
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `branch_codefr` CHAR(50),
	IN `branch_codeto` CHAR(50),
	IN `costcenters_codefr` CHAR(50),
	IN `costcenters_codeto` CHAR(50),
	IN `product_prodidfr` CHAR(50),
	IN `product_prodidto` CHAR(50),
	IN `accountcodefr` CHAR(20),
	IN `accountcodeto` CHAR(50),
	IN `donor_codefr` CHAR(10),
	IN `donor_codeto` CHAR(50),
	IN `trancodes_codefr` CHAR(50),
	IN `trancodes_codeto` CHAR(50),
	IN `currencies_id` CHAR(50),
	IN `user_idfr` CHAR(50),
	IN `user_idto` CHAR(50),
	IN `plang` CHAR(10)



















)
    COMMENT 'This Sp is used to retrieve the profit per period transaction'
BEGIN
	
	-- GET TRANSACTIONS	
	CALL `sp_get_transactions`(startDate, endDate,branch_codefr,branch_codeto,costcenters_codefr,costcenters_codeto,product_prodidfr,product_prodidto,'', '', '', '', '', '', '', user_idfr, user_idto, TRUE, plang);
	
	-- FITER TRANSACTIONS
	DROP TABLE IF EXISTS sp_get_profit_per_period_table1;
	
	-- LET REMAIN WITH ONLY INCOME AND EXPENDITURE ACCOUNTS		
	CREATE TEMPORARY TABLE sp_get_profit_per_period_table1 AS (SELECT t.chartofaccounts_accountcode accountcode,t.generalledger_tday, t.generalledger_credit,t.generalledger_debit,c.chartofaccounts_type FROM sp_get_transactions_2 t, chartofaccounts c WHERE c.chartofaccounts_type > 2  AND c.chartofaccounts_accountcode=t.chartofaccounts_accountcode);
	
	DROP TABLE IF EXISTS sp_get_transactions_2;
	
	DROP TABLE IF EXISTS sp_get_profit_per_period_table2;
	
	CREATE TEMPORARY TABLE sp_get_profit_per_period_table2 AS (SELECT accountcode, YEAR(generalledger_tday) nyear ,DAY(generalledger_tday) nDay,MONTH(generalledger_tday) nmonth,SUM(generalledger_debit) debit,SUM(generalledger_credit) credit,chartofaccounts_type tgroup FROM sp_get_profit_per_period_table1 GROUP BY accountcode,YEAR(generalledger_tday),MONTH(generalledger_tday),DAY(generalledger_tday),chartofaccounts_type );
	
	DROP TABLE IF EXISTS sp_get_profit_per_period_table3;
	
	CREATE TEMPORARY TABLE sp_get_profit_per_period_table3 AS (SELECT accountcode,tgroup,  nyear ,nDay,nmonth,debit, credit FROM sp_get_profit_per_period_table2);
	
	DROP TABLE IF EXISTS sp_get_profit_per_period_table4;
	
	CREATE TEMPORARY TABLE sp_get_profit_per_period_table4 AS (SELECT nyear ,nmonth,SUM(credit)income ,SUM(debit) expenditure,ROUND(CASE WHEN SUM(credit)=0 THEN '0' ELSE CASE WHEN SUM(debit)=0 THEN '100' ELSE ((SUM(debit)/SUM(credit))*100) END END,0) ppercent FROM sp_get_profit_per_period_table2 GROUP BY nyear,nmonth);
	
	
	SELECT nyear,nmonth,FORMAT(income,2) income,FORMAT(expenditure,2) expenditure,ppercent FROM sp_get_profit_per_period_table4;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_savers_statement
DROP PROCEDURE IF EXISTS `sp_get_savers_statement`;
DELIMITER //
CREATE PROCEDURE `sp_get_savers_statement`(
	IN `product_prodid` CHAR(20),
	IN `savaccounts_account` CHAR(50),
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `user_id` CHAR(50),
	IN `addtemptable` TINYINT
)
BEGIN
	
-- CALLED BY:
-- 1. sp_get_savings_tillsheet

	DECLARE smainquery TINYTEXT DEFAULT '';
	DECLARE squery TINYTEXT DEFAULT '';
   
	SET @squery ='';
	
	SET @smainquery = 'SELECT sa.client_idno,savtransactions_tday  date,transactioncode,tt.transactiontypes_name  description,st.transactiontypes_code transaction_type, st.savaccounts_account,st.product_prodid, 
	  IF(savtransactions_amount<0,savtransactions_amount,0) debit,IF(savtransactions_amount>0,savtransactions_amount,0) credit,savtransactions_balance balance,savtransactions_voucher voucher,user_id,cheqs_no cheque_status FROM savtransactions st,transactiontypes tt,savaccounts sa WHERE sa.product_prodid=st.product_prodid AND sa.savaccounts_account=st.savaccounts_account AND st.transactiontypes_code=tt.transactiontypes_code ';


	-- product
	IF product_prodid!=''  AND product_prodid IS NOT NULL THEN		
		SET @squery = CONCAT(@squery,' AND  st.product_prodid=',QUOTE(product_prodid));
	END IF;
	
	-- account
	IF savaccounts_account!=''  AND savaccounts_account IS NOT NULL THEN		
		SET @squery = CONCAT(@squery,' AND  st.savaccounts_account=',QUOTE(savaccounts_account));
	END IF;

	-- date from
	IF  startDate IS NOT NULL THEN		
		SET @squery = CONCAT(@squery,' AND  savtransactions_tday >=',QUOTE(startDate));
	END IF;
	
	-- date to
	IF  endDate IS NOT NULL THEN		
		SET @squery = CONCAT(@squery,' AND  st.savtransactions_tday <=',QUOTE(endDate));
	END IF;
		
	-- user who posted
	IF user_id!=''  AND user_id IS NOT NULL THEN		
		SET @squery = CONCAT(@squery,' AND  user_id =',QUOTE(user_id));
	END IF;
	
	SET @squery = CONCAT(@squery,' ORDER BY savtransactions_tday ASC');
 		
 	SET @smainquery = CONCAT(@smainquery,@squery);
 	
	
	-- chech see if we are to create a temporary table
   -- this part used use by other stored procedures thats call this sp
  	IF addtemptable IS NOT NULL THEN
		
		IF addtemptable=1 THEN
			
			DROP TABLE IF EXISTS savings_filtered_table;  	 	
		  
			SET @smainquery = CONCAT('CREATE TEMPORARY TABLE savings_filtered_table (KEY(client_idno,savaccounts_account,product_prodid),
			 INDEX(client_idno,savaccounts_account,product_prodid)) AS (',@smainquery,')'); 
	
		END IF;
   END IF;
   

 		
	-- TO DO: 
	--	Uncleared cheques
	-- Loans guaranteed by savings
	 
	-- insert into errors select @smainquery;
 	
   PREPARE stmt FROM @smainquery;
 	
 	 EXECUTE stmt;
 	 
  
	 DEALLOCATE PREPARE stmt;
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_savings_balances
DROP PROCEDURE IF EXISTS `sp_get_savings_balances`;
DELIMITER //
CREATE PROCEDURE `sp_get_savings_balances`(
	IN `productid` CHAR(20),
	IN `account` CHAR(50),
	IN `asat` DATETIME,
	IN `memid` CHAR(50)
)
BEGIN
	DECLARE smainquery TINYTEXT DEFAULT '';
	DECLARE squery TINYTEXT DEFAULT '';
    
 	DROP TABLE IF EXISTS sp_get_savings_balances_table1;

 
  	SET @smainquery = 'CREATE TEMPORARY TABLE sp_get_savings_balances_table1 AS (SELECT savaccounts_account,product_prodid, members_idno,balance FROM savingsbalances WHERE ';

	
	SET @squery = CONCAT(' savaccounts_account =',QUOTE(account));
	
	IF productid!='' AND productid IS NOT NULL THEN
		SET @squery = CONCAT(@squery,' AND  product_prodid =',QUOTE(productid));
	END IF;
	
	IF asat!='0000-00-00'  AND asat IS NOT NULL THEN
		SET @smainquery = 'CREATE TEMPORARY TABLE sp_get_savings_balances_table1 AS (SELECT savaccounts_account,product_prodid,members_idno,COALESCE(SUM(savtransactions_amount),0) balance FROM savtransactions WHERE ';		
		SET @squery = CONCAT(@squery,' AND  savtransactions_tday <=',QUOTE(asat),' GROUP BY savaccounts_account,product_prodid,members_idno');
		
	END IF;
 		
 	SET @smainquery = CONCAT(@smainquery,@squery,')');	
 	
	-- INSERT INTO errors(err)values(@smainquery);	
	-- TO DO: 
	--	Uncleared cheques
	-- Loans guaranteed by savings	 
 
 	
   PREPARE stmt FROM @smainquery;
 	
   EXECUTE stmt;
 	 
  IF FOUND_ROWS()= 0 THEN
	 SELECT account AS  savaccounts_account, productid AS product_prodid,'' members_idno ,0.00 AS balance;
  ELSE
  	 SELECT * FROM sp_get_savings_balances_table1;
  END IF;

 
	 DEALLOCATE PREPARE stmt;
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_savings_balances_by_id
DROP PROCEDURE IF EXISTS `sp_get_savings_balances_by_id`;
DELIMITER //
CREATE PROCEDURE `sp_get_savings_balances_by_id`(
	IN `savaccountsid` CHAR(80)


,
	IN `asat` DATE








)
BEGIN
	DECLARE smainquery TINYTEXT DEFAULT '';
	DECLARE squery TINYTEXT DEFAULT '';

-- GET ACCOUNT

DROP TABLE IF EXISTS sp_get_account;

CREATE TEMPORARY TABLE sp_get_account AS (SELECT s.savaccounts_id,s.client_idno,s.savaccounts_account,s.product_prodid ,(SELECT COALESCE(SUM(t.savtransactions_amount),0) FROM savtransactions t WHERE t.savaccounts_account=s.savaccounts_account AND t.product_prodid=s.product_prodid) balance FROM savaccounts s WHERE s.savaccounts_id=savaccountsid GROUP BY s.client_idno,s.savaccounts_account,s.product_prodid);


SELECT client_idno,savaccounts_account,product_prodid INTO  @clientidno,@savaccountsaccount,@product_prodid FROM sp_get_account WHERE savaccounts_id=savaccountsid;

IF INSTR(@clientidno, 'I')>0 THEN

	SELECT a.*,CONCAT(c.client_surname,' ',c.client_middlename,' ',c.client_firstname) name FROM sp_get_account a,clients c WHERE a.client_idno =c.client_idno;

ELSEIF INSTR(@clientidno, 'G')>0 THEN

	SELECT a.*,b.groups_name name FROM sp_get_account a,groups b WHERE a.client_idno =b.groups_idno;
	
	IF asat!='0000-00-00'  AND asat IS NOT NULL THEN
			
	SELECT s.members_idno, mm.name,mm.members_no, SUM(s.savtransactions_amount) balance FROM (
				SELECT CONCAT(b.members_firstname,' ',b.members_middlename,' ',b.members_lastname) name,b.members_idno,b.members_no FROM members b WHERE b.groups_idno = @clientidno)mm LEFT JOIN savtransactions s ON s.members_idno=mm.members_idno WHERE s.savtransactions_tday <=asat AND s.savaccounts_account = @savaccountsaccount AND s.product_prodid=@product_prodid  GROUP BY s.members_idno, mm.name,mm.members_no;
				
	ELSE
			
			SELECT s.members_idno, mm.name,mm.members_no, SUM(s.savtransactions_amount) balance FROM (
				SELECT CONCAT(b.members_firstname,' ',b.members_middlename,' ',b.members_lastname) name,b.members_idno,b.members_no FROM members b WHERE b.groups_idno = @clientidno)mm LEFT JOIN savtransactions s ON s.members_idno=mm.members_idno WHERE s.savaccounts_account = @savaccountsaccount AND s.product_prodid=@product_prodid  GROUP BY s.members_idno, mm.name,mm.members_no;
	
	END IF; 	
	
END IF; 	
	

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_savings_balances_detail
DROP PROCEDURE IF EXISTS `sp_get_savings_balances_detail`;
DELIMITER //
CREATE PROCEDURE `sp_get_savings_balances_detail`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `user_id` CHAR(50),
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(50)
)
BEGIN
	
		CALL `sp_get_client_details`(branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, '', '', client_regstatus, fund_code, costcenters_code, client_type, '', '', product_prodid, '', '', '', '', 1);
	
	IF endDate IS NULL THEN
		SET endDate = DATE(NOW());
	END IF; 
	
	DROP TABLE IF EXISTS sp_get_savings_balances_table;

	CREATE TEMPORARY TABLE sp_get_savings_balances_table AS (select client_idno,savaccounts_account,bb.product_prodid,balance from
	(
	select client_idno,savaccounts_account, tr.product_prodid, balance from
	

	(SELECT sa.client_idno,st.savaccounts_account, st.product_prodid,sum(st.savtransactions_amount) balance FROM savtransactions st,savaccounts sa WHERE sa.product_prodid=st.product_prodid AND st.savaccounts_account=sa.savaccounts_account AND DATE(st.savtransactions_tday)<=endDate group by sa.client_idno,st.savaccounts_account,st.product_prodid) tr	
	 
	
	) bb);

DROP TABLE IF EXISTS clients_filtered_table_sum;

CREATE TEMPORARY TABLE clients_filtered_table_sum AS (SELECT cc.*,'' product_prodid,'' acc,SUM(c.balance) balance FROM  clients_filtered_table cc,sp_get_savings_balances_table c  WHERE cc.client_idno=c.client_idno);		
	
SELECT cc.*,c.product_prodid,c.savaccounts_account acc,c.balance FROM  clients_filtered_table cc,sp_get_savings_balances_table c  WHERE cc.client_idno=c.client_idno
 UNION
 SELECT s.* FROM clients_filtered_table_sum s;
 
	
	-- select * FROM clients_filtered_table;
	
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_savings_interest_in_period
DROP PROCEDURE IF EXISTS `sp_get_savings_interest_in_period`;
DELIMITER //
CREATE PROCEDURE `sp_get_savings_interest_in_period`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `user_id` CHAR(50),
	IN `client_regstatus` CHAR(50)
,
	IN `startDate` DATE,
	IN `endDate` DATE


)
BEGIN
	CALL `sp_get_client_details`(branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, '', '', client_regstatus, fund_code, costcenters_code, client_type, '', '', product_prodid, '', '', '', '', 1);
	
	DROP TABLE IF EXISTS sp_get_savings_balances_table;

	CREATE TEMPORARY TABLE sp_get_savings_balances_table AS (select client_idno,savaccounts_account,bb.product_prodid,balance from
	(
	select client_idno,savaccounts_account, tr.product_prodid,sum(savtransactions_amount) balance from
	

	(SELECT sa.client_idno,st.savaccounts_account, st.product_prodid,st.savtransactions_amount FROM savtransactions st,savaccounts sa WHERE sa.product_prodid=st.product_prodid AND st.savaccounts_account=sa.savaccounts_account AND DATE(st.savtransactions_tday) BETWEEN  startDate AND endDate AND  st.transactiontypes_code='SI') tr	
	 group by client_idno,savaccounts_account,product_prodid
	
	) bb);
		
	
SELECT cc.*, CONCAT(cc.client_firstname,' ',cc.client_middlename,' ',cc.client_surname) name,c.product_prodid,c.savaccounts_account,c.balance  Interest FROM  clients_filtered_table cc,sp_get_savings_balances_table c WHERE cc.client_idno=c.client_idno;
	

	
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_savings_tillsheet
DROP PROCEDURE IF EXISTS `sp_get_savings_tillsheet`;
DELIMITER //
CREATE PROCEDURE `sp_get_savings_tillsheet`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `user_id` CHAR(50),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `loancategory1_code` CHAR(50),
	IN `loancategory2_code` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50)


)
BEGIN

	-- get cleint details
	CALL `sp_get_client_details`(branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, '', '', client_regstatus, '', costcenters_code, client_type, '', '', '', '', '', '', '', 1);
	
	-- get savings transactions
	CALL `sp_get_savers_statement`(product_prodid, '', startDate, endDate, user_id,1);


	DROP TABLE IF EXISTS table1; 
	
	CREATE TEMPORARY TABLE table1 AS (SELECT s.date,s.transactioncode,s.description,s.user_id,s.savaccounts_account,s.product_prodid,c.branch_code, debit, credit,cheque_status,c.client_firstname,c.client_middlename,c.client_surname ,c.client_grpname,c.client_regstatus FROM savings_filtered_table s, clients_filtered_table c WHERE c.client_idno=s.client_idno);
	
	
	DROP TABLE IF EXISTS savings_filtered_table; 
	
	DROP TABLE IF EXISTS clients_filtered_table; 
	
	SELECT * FROM table1; 	 
	 
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_sav_transactions
DROP PROCEDURE IF EXISTS `sp_get_sav_transactions`;
DELIMITER //
CREATE PROCEDURE `sp_get_sav_transactions`(
	IN `account` CHAR(50),
	IN `productid` CHAR(5),
	IN `memid` CHAR(50),
	IN `asat` DATETIME
)
BEGIN
	DECLARE smainquery LONGTEXT DEFAULT '';

	SET @smainquery = CONCAT('SELECT savtransactions_tday,transactioncode, transactiontypes_code,savtransactions_amount,savtransactions_balance,cheqs_no,paymode FROM savtransactions WHERE savaccounts_account =',QUOTE(account),' AND product_prodid =',QUOTE(productid));

	IF memid!='' AND memid IS NOT NULL THEN	
	 	SET @smainquery = CONCAT(@smainquery,' AND members_idno =',QUOTE(memid));
	END IF;

	IF asat!='' AND asat IS NOT NULL THEN	
	 	SET @smainquery = CONCAT(@smainquery,' AND savtransactions_tday <=',QUOTE(asat));
	END IF;

	SET @smainquery = CONCAT(@smainquery,' ORDER BY savtransactions_tday DESC');

-- insert into errors select @smainquery;
		   
	PREPARE stmt FROM @smainquery;
		 	
	EXECUTE stmt;

	SELECT FOUND_ROWS() reccount;
	
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_timedeposit_detail
DROP PROCEDURE IF EXISTS `sp_get_timedeposit_detail`;
DELIMITER //
CREATE PROCEDURE `sp_get_timedeposit_detail`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `currencies_id` VARCHAR(2),
	IN `product_prodid` CHAR(50),
	IN `tdstatus` CHAR(50),
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(50)
)
BEGIN
	DECLARE squery TINYTEXT DEFAULT '';
	
	CALL `sp_get_client_details`(branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, '', '', client_regstatus, fund_code, costcenters_code, client_type, '', '', product_prodid, '', '', '', '',1);
	
	DROP TABLE IF EXISTS sp_get_timedeposit_detail_table; 
	
	SET @smainquery ='CREATE TEMPORARY TABLE sp_get_timedeposit_detail_table AS (SELECT client_idno,td.timedeposit_date ,td.timedeposit_number,t.product_prodid,timedeposit_amount,timedeposit_interestrate,timedeposit_intamt,timedeposit_matdate,timedeposit_matval,timedeposit_period ,td.timedeposit_status FROM timedeposittrans td,timedeposit t WHERE t.timedeposit_number=td.timedeposit_number';
 	
 	
 	
	SET @squery = CONCAT(' td.timedeposit_status =',QUOTE(tdstatus),' AND ');
		
	 
	 IF startDate IS  NULL THEN
		SET startDate = DATE(NOW());
	END IF; 
 
	IF endDate IS NULL THEN
		SET endDate = DATE(NOW());
	END IF; 
	
	IF TRIM(product_prodid)!='' AND product_prodid IS NOT NULL THEN
		SET @squery = CONCAT(' AND  td.product_prodid =',QUOTE(product_prodid));
	END IF;

	SET @squery = CONCAT(' AND    td.timedeposit_status =',QUOTE(tdstatus));
	

	SET @squery = CONCAT(@squery,'  AND  td.timedeposit_date >=',QUOTE(startDate),' AND  td.timedeposit_date <=',QUOTE(endDate));
	
	
	SET @squery = CONCAT(@squery,' ORDER BY td.timedeposit_number ASC)');
	
	
	SET @smainquery = CONCAT(@smainquery,@squery);
	
 --	insert into errors select @smainquery;
	 
	 PREPARE stmt FROM @smainquery;
 	
 	 EXECUTE stmt;
 	 
  	 DEALLOCATE PREPARE stmt;
	 
	 
	 DROP TABLE IF EXISTS sp_get_timedeposit_detail_table1; 
	 	
	CREATE TEMPORARY TABLE sp_get_timedeposit_detail_table1 AS (SELECT c.client_regdate,c.client_surname,c.client_firstname,c.client_middlename,c.client_grpname, t.client_idno,t.timedeposit_number,t.product_prodid,t.timedeposit_amount,t.timedeposit_interestrate,
 timedeposit_intamt,t.timedeposit_matdate,t.timedeposit_matval,t.timedeposit_period ,t.timedeposit_status FROM clients_filtered_table c,sp_get_timedeposit_detail_table t  where t.client_idno=c.client_idno);

	
	SELECT FORMAT(SUM( CASE WHEN timedeposit_status='TW' THEN (-1*(timedeposit_amount)) ELSE timedeposit_amount END),2), FORMAT(sum(CASE WHEN timedeposit_status='TW' THEN (-1*(timedeposit_intamt)) ELSE timedeposit_intamt END),2),FORMAT(sum( CASE WHEN timedeposit_status='TW' THEN (-1*(timedeposit_matval)) ELSE timedeposit_matval END),2) INTO @amount,@intamt,@matval from sp_get_timedeposit_detail_table1;
	
	
	SELECT client_regdate,client_surname,client_firstname,client_middlename,client_grpname, client_idno,timedeposit_number,product_prodid,timedeposit_amount,timedeposit_interestrate, timedeposit_intamt,timedeposit_matdate,timedeposit_matval,timedeposit_period ,timedeposit_status from sp_get_timedeposit_detail_table1
	UNION ALL
	SELECT '','','','','', '','','',CONCAT('</b>',@amount,'</b>'),'', CONCAT('</b>',@intamt,'</b>'),'',CONCAT('</b>',@matval,'</b>') ,'' ,'';


END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_transaction
DROP PROCEDURE IF EXISTS `sp_get_transaction`;
DELIMITER //
CREATE PROCEDURE `sp_get_transaction`(
	IN `branch_code` TINYTEXT,
	IN `tcode` CHAR(50)
)
BEGIN

	DROP TABLE IF EXISTS sp_get_transaction_table1;
	
	IF tcode='' OR tcode IS NULL THEN	 
	
		SET @smainquery ='CREATE TEMPORARY TABLE sp_get_transaction_table1 AS (SELECT * from 	generalledger)';
 	 	
	--	SELECT * from generalledger;
	ELSE	
	
		SET @smainquery = CONCAT('CREATE TEMPORARY TABLE sp_get_transaction_table1 AS (SELECT  *  from generalledger where (transactioncode=',QUOTE(tcode),' OR generalledger_voucher LIKE ',QUOTE(tcode),' ))');

	END IF;
	
	-- INSERT INTO errors(err) values (@smainquery);	
	
   PREPARE stmt FROM @smainquery;
 	
 	EXECUTE stmt;
 
	DEALLOCATE PREPARE stmt;
	
	SELECT * FROM sp_get_transaction_table1;
	
	SET @reccount = FOUND_ROWS();

	SELECT @reccount AS reccount;
	
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_transactions
DROP PROCEDURE IF EXISTS `sp_get_transactions`;
DELIMITER //
CREATE PROCEDURE `sp_get_transactions`(
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `branch_codefr` CHAR(50),
	IN `branch_codeto` CHAR(50),
	IN `costcenters_codefr` CHAR(50),
	IN `costcenters_codeto` CHAR(50),
	IN `product_prodidfr` CHAR(50),
	IN `product_prodidto` CHAR(50),
	IN `accountcodefr` CHAR(20),
	IN `accountcodeto` CHAR(20),
	IN `donor_codefr` CHAR(10),
	IN `donor_codeto` CHAR(20),
	IN `trancodes_codefr` CHAR(50),
	IN `trancodes_codeto` CHAR(50),
	IN `currencies_id` CHAR(20),
	IN `user_idfr` CHAR(50),
	IN `user_idto` CHAR(50),
	IN `addtemptable` BIT,
	IN `plang` CHAR(10)
)
BEGIN
	DECLARE smainquery LONGTEXT DEFAULT '';
	DECLARE squery LONGTEXT DEFAULT '';

	DECLARE language VARCHAR(5) DEFAULT '';
	-- TO DO: Archived Transactions
	
	-- TO DO: Approved/Unpproved transaction
	
	-- TO DO: Debtors and Creditors

	-- TO DO: Add opening balances to the accounts
	
	-- TO DO: Take care of Branch Access to Transactions
	SET @language = plang;
	
 	
-- set language for oprning balance description
CASE plang  WHEN 'EN' THEN SET @language :='Opening Balance' ;
 WHEN 'SP' THEN SET @language :='Saldo de Apertura';
 WHEN 'FR' THEN SET @language :='Solde d\'Ouverture';
 WHEN 'SP' THEN SET @language :='Saldo de Apertura';
 ELSE SET @language:= 'Opening Balance' ;
END CASE;
	
	
	IF endDate='0000-00-00'  AND startDate!=''  AND startDate!='0000-00-00' THEN
 		SET endDate = startDate;
 	END IF ;
 	
 	IF startDate ='0000-00-00'  AND endDate!=''  AND endDate!='0000-00-00' THEN
 		SET startDate =endDate;
 	END IF ;
 	

 -- get opening balance
	SET @smainquery = CONCAT('(SELECT \'OP\' ttype,@id := @id + 1 generalledger_id,transactioncode,',QUOTE(@language),' generalledger_description
	
	,fund_code,donor_code,COALESCE(SUM(generalledger_credit),0) generalledger_credit,
	generalledger_voucher,user_id,generalledger_tday,COALESCE(SUM(generalledger_debit),0)generalledger_debit,chartofaccounts_accountcode,
	generalledger_updated,branch_code,trancode,generalledger_locked,forexrates_id,generalledger_fcamount,currencies_id,client_idno,product_prodid,costcenters_code FROM generalledger,(SELECT @id := 0) t WHERE DATE(generalledger_tday) <=', QUOTE(startDate),' GROUP BY chartofaccounts_accountcode)');
	
	SET @smainquery = CONCAT(@smainquery,' UNION ALL ');
		
 	-- you must initialise local variables
 	SET @smainquery = CONCAT(@smainquery,'(SELECT \'TR\' ttype,@id := @id + 1 generalledger_id,transactioncode,generalledger_description
		,fund_code,donor_code,generalledger_credit generalledger_credit,
	generalledger_voucher,user_id,generalledger_tday,generalledger_debit generalledger_debit,chartofaccounts_accountcode,
	generalledger_updated,branch_code,trancode,generalledger_locked,forexrates_id,generalledger_fcamount,currencies_id,client_idno,product_prodid,costcenters_code
	  FROM generalledger ');
	  
	SET @squery ='';
	
	IF accountcodefr!='' AND accountcodefr IS NOT NULL AND accountcodeto!='' AND accountcodeto IS NOT NULL  THEN 	
 		SET @squery = CONCAT(@squery,' chartofaccounts_accountcode BETWEEN',QUOTE(accountcodefr),' AND ',QUOTE(accountcodeto));
 	END IF;
 	
 	IF donor_codefr!='' AND donor_codefr IS NOT NULL AND donor_codeto!='' AND donor_codeto IS NOT NULL  THEN 	
 		
		 IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
		
		 SET @squery = CONCAT(@squery,' chartofaccounts_accountcode BETWEEN',QUOTE(donor_codefr),' AND ',QUOTE(donor_codeto));
 	END IF;
 	
	IF branch_codefr!='' AND branch_codeto!=''  AND branch_codeto IS NOT NULL  AND branch_codefr IS NOT NULL  THEN 
		IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
			
 --	SET @squery = CONCAT(@squery,' branch_code BETWEEN ',QUOTE(branch_codefr),' AND ',QUOTE(branch_codeto));
 	END IF;
 	
 
	
	IF  startDate IS NOT NULL AND endDate IS NOT NULL  THEN 	
		IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
		
 		SET @squery = CONCAT(@squery,' DATE(generalledger_tday)  > ',QUOTE(startDate),' AND DATE(generalledger_tday) <=',QUOTE(endDate));
 	END IF;
 	
 	
	IF product_prodidfr!=''  AND product_prodidfr IS NOT NULL AND product_prodidto!=''  AND product_prodidto IS NOT NULL  THEN	
 		IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
		
 		SET @squery = CONCAT(@squery,' product_prodid BETWEEN ',QUOTE(product_prodidfr),' AND ',QUOTE(product_prodidto));
 	END IF;
 	
 	
 		IF costcenters_codefr!=''  AND costcenters_codefr IS NOT NULL AND costcenters_codeto!=''  AND costcenters_codeto IS NOT NULL  THEN	
 		IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
		
 		SET @squery = CONCAT(@squery,' costcenters_code BETWEEN ',QUOTE(costcenters_codefr),' AND ',QUOTE(costcenters_codeto));
 	END IF;
 	
 	IF user_idfr!=''  AND user_idfr IS NOT NULL AND user_idto!=''  AND user_idto IS NOT NULL  THEN	
 		IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
		
 		SET @squery = CONCAT(@squery,' user_id  BETWEEN ',QUOTE(user_idfr),' AND ',QUOTE(user_idto));
 	END IF;
 	
 	
 	IF currencies_id!='0'  AND currencies_id!=''  AND currencies_id IS NOT NULL THEN	
 		IF @squery !='' THEN
			SET @squery = CONCAT(@squery,' AND ');
		END IF;
		
 		SET @squery = CONCAT(@squery,' currencies_id=',QUOTE(currencies_id));
 	END IF;
 	  	 	
 	-- concatinate
	IF @squery!='' AND @squery IS NOT NULL THEN
	
		SET @smainquery = CONCAT(@smainquery,' WHERE ',@squery);
   END IF;
   
   SET @smainquery = CONCAT(@smainquery,' ) ');
   
  -- insert into errors(err) values (@smainquery);
   
   -- chech see if we are to create a temporary table
   -- this part used use by other stored procedures thats call this sp
  	IF addtemptable IS NOT NULL THEN
		
		IF addtemptable= true THEN
		--	SET @smainquery ='select * from generalledger';
			DROP TABLE IF EXISTS sp_get_transactions_2; 	
	 	
			SET @smainquery = CONCAT('CREATE TEMPORARY TABLE sp_get_transactions_2 AS ( select *  FROM (',@smainquery,') t_union)'); 
	
		END IF;
   END IF;
  

   -- TO DO: Some parameters not relevant yet in this scope	 	
   PREPARE stmt FROM @smainquery;
 	
 	 EXECUTE stmt;
 	 
 
 
	 DEALLOCATE PREPARE stmt;


END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_transactions_made
DROP PROCEDURE IF EXISTS `sp_get_transactions_made`;
DELIMITER //
CREATE PROCEDURE `sp_get_transactions_made`(
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `branch_codefr` TINYINT,
	IN `branch_codeto` TINYTEXT,
	IN `costcenters_codefr` CHAR(50),
	IN `costcenters_codeto` INT,
	IN `product_prodidfr` CHAR(50),
	IN `product_prodidto` CHAR(50),
	IN `accountcodefr` CHAR(20),
	IN `accountcodeto` INT,
	IN `donor_codefr` CHAR(10),
	IN `donor_codeto` INT,
	IN `trancodes_codefr` CHAR(50),
	IN `trancodes_codeto` CHAR(50),
	IN `currencies_id` INT,
	IN `user_idfr` CHAR(50),
	IN `user_idto` CHAR(50),
	IN `plang` CHAR(10)
)
BEGIN
	
 CALL `sp_get_transactions`(startDate,endDate, branch_codefr, branch_codeto, costcenters_codefr, costcenters_codeto, product_prodidfr,product_prodidto, '','', donor_codefr,donor_codeto,trancodes_codefr, trancodes_codeto,currencies_id,user_idfr,user_idto, true,plang);

IF  startDate IS NOT NULL THEN
 		SET endDate = startDate;
 	END IF ;
 	
 	IF endDate IS NOT NULL THEN
 		SET startDate =endDate;
 	END IF ;
 	

	SELECT DATE(generalledger_tday) tday,g.chartofaccounts_accountcode account, transactioncode tcode,generalledger_description description,generalledger_debit debit,generalledger_credit credit,g.branch_code branch,c.currencies_code curcode, CONCAT(user_firstname,' ',user_lastname,' ',user_middlename) username,product_prodid,costcenters_code,trancode,donor_code,fund_code  FROM sp_get_transactions_2 g LEFT JOIN users u ON g.user_id=u.user_id LEFT JOIN currencies c ON c.currencies_id=g.currencies_id   WHERE DATE(generalledger_tday) BETWEEN  startDate AND endDate ORDER BY generalledger_tday ,transactioncode ASC;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_trial_balance
DROP PROCEDURE IF EXISTS `sp_get_trial_balance`;
DELIMITER //
CREATE PROCEDURE `sp_get_trial_balance`(
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `branch_codefr` TINYINT,
	IN `branch_codeto` TINYTEXT,
	IN `costcenters_codefr` CHAR(50),
	IN `costcenters_codeto` INT,
	IN `product_prodidfr` CHAR(50),
	IN `product_prodidto` CHAR(50),
	IN `accountcodefr` CHAR(20),
	IN `accountcodeto` INT,
	IN `donor_codefr` CHAR(10),
	IN `donor_codeto` INT,
	IN `trancodes_codefr` CHAR(50),
	IN `trancodes_codeto` CHAR(50),
	IN `currencies_id` INT,
	IN `user_idfr` CHAR(50),
	IN `user_idto` CHAR(50),
	IN `group_by` CHAR(50),
	IN `order_by` CHAR(50),
	IN `plang` CHAR(10)







)
BEGIN


DECLARE cDescription CHAR(50) DEFAULT '';
-- set language for oprning balance description
 CASE plang  WHEN 'EN' THEN SET @cDescription :='Total' ;
 WHEN 'FR' THEN SET @cDescription :='Totaux';
 WHEN 'SP' THEN SET @cDescription :='';
 ELSE SET @cDescription:= 'Total' ;
END CASE;

-- DO TO: add form filters

DROP TABLE IF EXISTS sp_get_trial_balance_table1;

CREATE TEMPORARY TABLE sp_get_trial_balance_table1 AS (SELECT chartofaccounts_accountcode account,
SUM(CASE WHEN generalledger_tday< startDate AND COALESCE(generalledger_debit,0.000) <> 0 THEN  COALESCE(generalledger_debit,0) ELSE 
0.00 END ) AS odebit,
SUM(CASE WHEN generalledger_tday< startDate AND COALESCE(generalledger_debit,0.000) <> 0 THEN  COALESCE(generalledger_debit,0) ELSE 
0.00 END ) AS otdebit,
SUM(CASE WHEN generalledger_tday<startDate AND COALESCE(generalledger_credit,0.000) <> 0 THEN  COALESCE(generalledger_credit,0) ELSE 0.00000 END ) AS ocredit,
SUM(CASE WHEN generalledger_tday > startDate  AND DATE(generalledger_tday) <= endDate AND COALESCE(generalledger_debit,0.000) <> 0 THEN  COALESCE(generalledger_debit,0.000) ELSE 0 END) AS pdebit,
SUM(CASE WHEN generalledger_tday > startDate  AND DATE(generalledger_tday) <= endDate AND COALESCE(generalledger_credit,0.000) <> 0 THEN  COALESCE(generalledger_credit,0.000) ELSE 0.000 END)  AS pcredit, 
SUM(COALESCE(generalledger_debit,0.000))  cdebit,
 SUM(COALESCE(generalledger_credit,0.000))  ccredit ,
 SUM(COALESCE(generalledger_debit,0.000))  tdebit,
 SUM(COALESCE(generalledger_credit,0.000))  tcredit  
 FROM generalledger WHERE DATE(generalledger_tday)<=endDate GROUP BY chartofaccounts_accountcode);

SET  @prevcdebit := 0;
-- update balances
UPDATE sp_get_trial_balance_table1 AS g  
SET g.odebit =(CASE WHEN g.odebit > g.ocredit THEN (g.odebit - ABS(g.ocredit)) ELSE 0.00000 END), 
g.ocredit =( CASE WHEN g.ocredit > g.otdebit THEN (g.ocredit - ABS(g.otdebit)) ELSE 0.000 END),
g.ccredit =( CASE WHEN g.tcredit > g.tdebit THEN (g.tcredit - ABS(g.tdebit)) ELSE 0.000 END),
g.cdebit =(CASE WHEN g.tdebit > g.tcredit THEN (g.tdebit - ABS(g.tcredit)) ELSE 0.000 END)
 ORDER BY g.account;

DROP TABLE IF EXISTS sp_get_trial_balance_table2;

CREATE TEMPORARY TABLE sp_get_trial_balance_table2 AS (
select account,sum(odebit) odebit,sum(ocredit) ocredit,sum(pdebit)rdebit,sum(pcredit)rcredit,sum(cdebit)cdebit,sum(ccredit)ccredit FROM sp_get_trial_balance_table1  GROUP BY account WITH ROLLUP);

DROP TABLE IF EXISTS sp_get_trial_balance_table1;

select 
CASE WHEN account='' THEN CONCAT('<b>',@cDescription,'</b>')  ELSE account END account,
COALESCE(c.chartofaccounts_name,'') account_label,
CASE WHEN account='' THEN CONCAT('<b>',FORMAT(odebit,2),'</b>') ELSE odebit END odebit,
CASE WHEN account='' THEN CONCAT('<b>',FORMAT(ocredit,2),'</b>') ELSE ocredit END ocredit,
CASE WHEN account='' THEN CONCAT('<b>',FORMAT(rdebit,2),'</b>') ELSE rdebit END pdebit,
CASE WHEN account='' THEN CONCAT('<b>',FORMAT(rcredit,2),'</b>') ELSE rcredit END pcredit,
CASE WHEN account='' THEN CONCAT('<b>',FORMAT(cdebit,2),'</b>') ELSE cdebit END cdebit,
CASE WHEN account='' THEN CONCAT('<b>',FORMAT(ccredit,2),'</b>') ELSE ccredit END ccredit

FROM sp_get_trial_balance_table2 t  LEFT OUTER JOIN chartofaccounts c ON  c.chartofaccounts_accountcode=t.account;


END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_get_whats_due
DROP PROCEDURE IF EXISTS `sp_get_whats_due`;
DELIMITER //
CREATE PROCEDURE `sp_get_whats_due`(
	IN `branch_code` TINYTEXT,
	IN `product_prodid` CHAR(10),
	IN `client_type` CHAR(5),
	IN `asatdate` DATE,
	IN `loan_number_fr` CHAR(50),
	IN `loan_number_to` CHAR(50)
)
BEGIN

DECLARE rowcount BIGINT DEFAULT 0;
DECLARE totalnrows  BIGINT DEFAULT 0;
DECLARE vloan_number  CHAR(50) DEFAULT '';
DECLARE vmembers_idno  CHAR(50) DEFAULT '';
DECLARE vprincipalbal NUMERIC(15,4) DEFAULT 0;
DECLARE varInt NUMERIC(15,4) DEFAULT 0;
DECLARE no_int CHAR(1);
DECLARE recalint CHAR(1);
DECLARE cstatus CHAR(10);


DROP TABLE IF EXISTS loans_filtered_table;
 
CALL `sp_get_loan_details`('', '', '', '', '', '', '','','','','','','', '','','', '','','', true,true,true,TRIM(loan_number_fr),TRIM(loan_number_to));


-- get amounts payable
 DROP TABLE IF EXISTS sp_get_charge_int;
 
CREATE TEMPORARY TABLE sp_get_charge_int AS (SELECT COALESCE(product_prodid,'') prodid,COALESCE(productconfig_value,0) value FROM productconfig WHERE productconfig_paramname='CHARGE_INT' GROUP BY product_prodid);


IF  asatdate IS NULL THEN
	SELECT NOW() INTO asatdate;
END IF;

-- get amounts payable
 DROP TABLE IF EXISTS sp_get_loan_dues;
 
--  total dues and dues before current date
CREATE TEMPORARY TABLE sp_get_loan_dues AS (select
l.loan_number,
d.members_idno,
SUM(COALESCE(d.due_principal,0))  ddprinc,
 SUM(COALESCE(d.due_interest,0))	ddint,
 SUM(COALESCE(d.due_commission,0)) ddcomm,
 SUM(COALESCE(d.due_penalty,0)) ddpen,
SUM(COALESCE(d.due_vat,0))  ddvat,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_principal,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) dprinc,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_interest,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) dint,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_commission,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) dcomm,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_penalty,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) dpen,
SUM(CASE WHEN DATE(d.due_date) < DATE(asatdate) THEN COALESCE(d.due_vat,0) ELSE 0 END) dvat,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_principal,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) nprinc,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_interest,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) nint,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_commission,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) ncomm,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_penalty,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) npen,
SUM(CASE WHEN DATE(d.due_date) = DATE(asatdate) THEN COALESCE(d.due_vat,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) nvat
FROM  dues d ,loans_filtered_table l WHERE d.loan_number =l.loan_number  GROUP BY d.loan_number,d.members_idno);

-- get all payments
DROP TABLE IF EXISTS sp_get_loan_payments;
 
CREATE TEMPORARY TABLE sp_get_loan_payments (SELECT 
p.loan_number,
p.members_idno,
SUM(COALESCE(p.loanpayments_principal,0)) pprinc,
SUM(COALESCE(p.loanpayments_interest,0)) pint,
SUM(COALESCE(p.loanpayments_commission,0)) pcomm,
SUM(COALESCE(p.loanpayments_penalty,0)) ppen, 
SUM(COALESCE(p.loanpayments_vat,0)) pvat
FROM  loanpayments p,loans_filtered_table l WHERE p.loan_number =l.loan_number  GROUP BY p.loan_number,p.members_idno);

DROP TABLE IF EXISTS sp_get_out_loan_dues1;

CREATE TEMPORARY TABLE sp_get_out_loan_dues1 (SELECT 
d.loan_number,
d.members_idno,
SUM(COALESCE(d.dprinc,0))-SUM(COALESCE(p.pprinc,0)) arprinc,
SUM(COALESCE(d.dint,0))-SUM(COALESCE(p.pint,0)) arint,
SUM(COALESCE(d.dcomm,0))-SUM(COALESCE(p.pcomm,0)) arcomm,
SUM(COALESCE(d.dpen,0))-SUM(COALESCE(p.ppen,0)) arpen,
SUM(COALESCE(d.dvat,0))-SUM(COALESCE(p.pvat,0)) arvat,
SUM(d.dprinc)+SUM(COALESCE(d.nprinc,0))- SUM(COALESCE(p.pprinc,0)) cprinc,
SUM(d.dint)+SUM(COALESCE(d.nint,0)) - SUM(COALESCE(p.pint,0)) cint,
SUM(d.dcomm)+SUM(COALESCE(d.ncomm,0)) - SUM(COALESCE(p.pcomm,0)) ccomm,
SUM(d.dpen)+(SUM(COALESCE(d.npen,0)) - SUM(COALESCE(p.ppen,0))) cpen,
SUM(d.ddvat)+(SUM(COALESCE(d.nvat,0)) - SUM(COALESCE(p.pvat,0))) cdvat ,
COALESCE(p.pprinc,CAST(0.00 AS DECIMAL(15,2))) pprinc,
COALESCE(d.ddint,CAST(0.00 AS DECIMAL(15,2))) ddint,
COALESCE(d.ddcomm,CAST(0.00 AS DECIMAL(15,2))) ddcomm,
COALESCE(d.ddpen,CAST(0.00 AS DECIMAL(15,2))) ddpen,
COALESCE(d.ddvat,CAST(0.00 AS DECIMAL(15,2))) ddvat
FROM sp_get_loan_dues d LEFT JOIN sp_get_loan_payments p ON p.loan_number=d.loan_number 
AND p.members_idno=d.members_idno GROUP BY d.loan_number,d.members_idno);

DROP TABLE IF EXISTS sp_get_out_loan_dues2;

CREATE TEMPORARY TABLE sp_get_out_loan_dues2 (
SELECT CONCAT(l.client_firstname,' ',l.client_middlename,' ',l.client_surname,l.client_grpname) name,
l.loan_number,
l.client_idno,
l.loan_amount,
d.members_idno,
l.client_regstatus,
l.product_prodid,
d.arprinc,
d.arint,
d.arcomm,
d.arpen,
d.arvat,
(CASE WHEN d.cprinc < 0 THEN CAST(0.00 AS DECIMAL(15,2)) ELSE d.cprinc END) dprinc,
(CASE WHEN d.cint < 0 THEN CAST(0.00 AS DECIMAL(15,2)) ELSE d.cint END) dint,
(CASE WHEN d.ccomm < 0 THEN CAST(0.00 AS DECIMAL(15,2)) ELSE d.ccomm END) dcomm,
(CASE WHEN d.cpen < 0 THEN CAST(0.00 AS DECIMAL(15,2)) ELSE d.cpen END) dpen,
(CASE WHEN d.cdvat < 0 THEN CAST(0.00 AS DECIMAL(15,2)) ELSE d.cdvat END) ddvat,
COALESCE(d.pprinc,0) princpaid,
ddint outint,
ddcomm outcomm,
ddpen  outpen,
ddvat outvat
FROM sp_get_out_loan_dues1 d, loans_filtered_table l WHERE l.loan_number=d.loan_number);

DROP TABLE IF EXISTS sp_get_loan_dues_payable4;

CREATE TEMPORARY TABLE sp_get_loan_dues_payable4 AS (

SELECT l.*,(SUM(COALESCE(disbursements_amount,0))- COALESCE(princpaid,0)) outbal FROM sp_get_out_loan_dues2 l,disbursements d WHERE d.loan_number=l.loan_number  GROUP BY d.loan_number,l.members_idno);


-- recalculculate interest
SELECT COUNT(loan_number) INTO @totalnrows FROM sp_get_loan_dues_payable4;

SET rowcount = 1;

 --  SELECT COALESCE(productconfig_value,0) FROM productconfig WHERE productconfig_paramname ='RECALC_INT' AND product_prodid=@vproduct_prodid GROUP BY product_prodid,productconfig_value;
	
 WHILE rowcount <= @totalnrows DO
  	BEGIN   
   
   SELECT loan_number,outbal,members_idno,product_prodid,client_regstatus INTO @vloan_number,@vprincipalbal,@vmembers_idno,@vproduct_prodid,@cstatus FROM sp_get_loan_dues_payable4 LIMIT rowcount, 1;
		
   SELECT COALESCE(productconfig_value,0) INTO @recalint FROM productconfig WHERE productconfig_paramname ='RECALC_INT' AND product_prodid=@vproduct_prodid GROUP BY product_prodid,productconfig_value;

	-- check see if we should ignore evaluating interest
	IF @recalint='1'  THEN
		
		IF @cstatus='EXT' AND @no_int='1' THEN
		
			SELECT 0 INTO @varInt;
			
		ELSE
		
			IF @vprincipalbal > 0 THEN
				CALL `sp_recalculate_int`('', @vloan_number,@vmembers_idno, asatdate, @vprincipalbal,@vproduct_prodid,'1');	 
		
	 	 		SELECT interest INTO @varInt FROM sp_interest_table;
	 	 		
	 	 	END IF; 
 		END IF;
 	 	
 	ELSE
	 SET 	@varInt = 0;			
	END IF;		
    
   IF @varInt < 0 THEN
   	SET @varInt = 0 ;
   END IF; 
   
	UPDATE sp_get_loan_dues_payable4 SET dint = @varInt WHERE loan_number=@vloan_number AND members_idno = @vmembers_idno;	 

      
	SET rowcount  = rowcount + 1;
	
 	END; 
 	
END while; 

SELECT * FROM sp_get_loan_dues_payable4 ;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_loan_search
DROP PROCEDURE IF EXISTS `sp_loan_search`;
DELIMITER //
CREATE PROCEDURE `sp_loan_search`(
	IN `branch_code` TINYTEXT,
	IN `client1_code` CHAR(5),
	IN `client2_code` CHAR(5),
	IN `client3_code` CHAR(5),
	IN `bussinesssector_code` CHAR(50),
	IN `areacode_code` CHAR(50),
	IN `startDate` DATE,
	IN `endDate` DATE,
	IN `client_regstatus` CHAR(5),
	IN `fund_code` CHAR(5),
	IN `costcenters_code` CHAR(50),
	IN `client_type` CHAR(5),
	IN `user_id` CHAR(50),
	IN `currencies_id` INT,
	IN `product_prodid` CHAR(50),
	IN `loancategory1_code` CHAR(50),
	IN `loancategory2_code` CHAR(50),
	IN `order_by` CHAR(50),
	IN `group_by` CHAR(50),
	IN `isdisbursed` BIT,
	IN `includewoff` BIT,
	IN `addtemptable` TINYINT,
	IN `loan_number_fr` CHAR(50),
	IN `loan_number_to` CHAR(50)












)
    COMMENT 'This procedure is used to get loan application details'
BEGIN
	
	-- SOURCES:
-- 1.sp_get_client_details
-- 2.

-- DEPENDANTS:
-- 1.sp_get_outstanding_loan_balances
-- 2.

-- DEPENDANTS:
-- 1.sp_get_disbursements
-- 2.

DECLARE loan_smainquery MEDIUMTEXT DEFAULT '';
DECLARE loan_squery MEDIUMTEXT DEFAULT '';
	 

SET @loan_squery ='';
	
CALL `sp_get_client_details`(branch_code, client1_code, client2_code, client3_code, bussinesssector_code, areacode_code, '', '', client_regstatus, fund_code, costcenters_code, client_type, '', '', product_prodid, '', '', '', '', 1);

DROP TABLE IF EXISTS loans_table;

-- get loans
SET @loan_smainquery = 'CREATE TEMPORARY TABLE loans_table  AS (
select c.*,l.loan_number,l.loan_amount,l.fund_code,l.loan_tint,l.user_id,l.loan_intamount,l.loan_status,l.loan_udf1,l.loan_udf2,l.loan_udf3,l.loan_inttype,l.loan_insttype,l.loan_intdays,l.product_prodid,l.donor_code,l.members_idno from loan l,clients_filtered_table c WHERE c.client_idno=l.client_idno '; 

	-- FILTERS

	-- only disbursed loans
	IF isdisbursed =1 THEN
		SET @loan_squery = CONCAT(@loan_squery,' AND l.loan_number  IN (SELECT ls.loan_number FROM loanstatuslog ls WHERE ls.loan_status=',QUOTE('LD'),' AND l.loan_number=ls.loan_number)');
	END IF;
	
	-- exclude writtenof losn
	IF isdisbursed =1 THEN
		SET @loan_squery = CONCAT(@loan_squery,' AND l.loan_number NOT  IN (SELECT lw.loan_number FROM loanswrittenoff lw  WHERE lw.loan_number=l.loan_number)');
	END IF;
	

	IF product_prodid!=''  AND product_prodid IS NOT NULL  THEN 	
 		SET @loan_squery = CONCAT(@loan_squery,' AND l.product_prodid =',QUOTE(product_prodid));
 	END IF;


	IF loancategory1_code!=''  AND loancategory1_code IS NOT NULL  THEN 	
 		SET @loan_squery = CONCAT(@loan_squery,' AND l.loan_udf1 =',QUOTE(loancategory1_code));
 	END IF;
 	
 	IF loancategory2_code!=''  AND loancategory2_code IS NOT NULL  THEN 	
 		SET @loan_squery = CONCAT(@loan_squery,' AND l.loan_udf3 =',QUOTE(loancategory2_code));
 	END IF;
 	
 	IF user_id!=''  AND user_id IS NOT NULL  THEN 	
 		SET @loan_squery = CONCAT(@loan_squery,' AND l.user_id =',QUOTE(user_id));
 	END IF;
 	
 	IF loan_number_fr!=''  AND loan_number_to!=''  THEN 	
 		SET @loan_squery = CONCAT(@loan_squery,' AND l.loan_number BETWEEN ',QUOTE(loan_number_fr),' AND ',QUOTE(loan_number_to));
 	END IF;
 	
 	IF loan_number_fr !='' AND  loan_number_to=''  THEN 	
 		SET @loan_squery = CONCAT(@loan_squery,' AND l.loan_number = ',QUOTE(loan_number_fr));
 	END IF;
 	
 	
	SET @loan_squery = CONCAT(@loan_squery,');');
		  	
 	-- concatinate
	IF @loan_squery!='' AND @loan_squery IS NOT NULL THEN
	
		SET @loan_smainquery = CONCAT(@loan_smainquery,@loan_squery);
   END IF;
   
  	 -- log query
	-- insert into errors select @loan_smainquery;
		   
	PREPARE stmt FROM @loan_smainquery;
		 	
	EXECUTE stmt;
	
	DEALLOCATE PREPARE stmt;	
	
   -- chech see if we are to create a temporary table
   -- this part used use by other stored procedures thats call this sp
  --	IF addtemptable IS NOT NULL THEN
		
IF addtemptable = 1 THEN
			
		DROP TABLE IF EXISTS loans_filtered_table; 	 	 	
	--	 		
			CREATE TEMPORARY TABLE loans_filtered_table AS (SELECT * FROM loans_table); 
	--	
	ELSE
			
			SELECT * FROM loans_table;
			
	END IF;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_open_close_period
DROP PROCEDURE IF EXISTS `sp_open_close_period`;
DELIMITER //
CREATE PROCEDURE `sp_open_close_period`(
	IN `branch_code` TINYTEXT,
	IN `startdate` DATE,
	IN `enddate` DATE,
	IN `user_id` CHAR(50),
	IN `plang` CHAR(5),
	IN `cperiod` CHAR(1),
	IN `caction` CHAR(1)
)
proc_label:BEGIN

DECLARE tname CHAR(50);

DECLARE opendate DATE;


IF startdate='' OR startdate IS NULL OR enddate='' OR enddate IS NULL THEN
		select 0 as ouput;	
		LEAVE proc_label;
END IF;
	

-- openingbalance
SELECT  CASE WHEN plang='EN' THEN translations_eng 
WHEN plang='SP' THEN translations_sp 
WHEN plang='FR' THEN translations_fr 
WHEN plang='SWA' THEN translations_swa  END INTO @descip
FROM translations WHERE translations_id='373';

-- prodit and loss descripion
SELECT  CASE WHEN plang='EN' THEN translations_eng 
WHEN plang='SP' THEN translations_sp 
WHEN plang='FR' THEN translations_fr 
WHEN plang='SWA' THEN translations_swa  END INTO @descip1
FROM translations WHERE translations_id='454';

-- verify double entry
IF caction='C' THEN
SELECT  SUM(generalledger_debit) - SUM(generalledger_credit) INTO @nAmt FROM generalledger WHERE DATE(generalledger_tday)<=enddate AND branch_code=branch_code;

	-- if there is a balance then debit and credit do not tally
	IF @nAmt!=0 THEN
		select 3 as ouput;	
		LEAVE proc_label;
	END IF;

END IF;



 -- unlock period
IF caction='C' THEN
	UPDATE generalledger SET generalledger_locked='Y' WHERE DATE(generalledger_tday)<=enddate AND branch_code=branch_code;
ELSE
	UPDATE generalledger SET generalledger_locked='N' WHERE DATE(generalledger_tday)<=enddate AND branch_code=branch_code;
END IF;

-- check see if we are closing days or a financial year
IF cperiod ='D' AND caction='C' THEN
	SELECT 1 as ouput;
	LEAVE proc_label;	 
	
ELSEIF caction ='O' THEN
	
	 SELECT COUNT(closedperiod_tablename) INTO @nRowCount  FROM closedperiod WHERE branch_code=branch_code AND closedperiod_ends<=enddate; 
	
	IF @nRowCount >0 THEN	
		 CALL sp_open_period(branch_code,enddate);	
	END IF ;

	SELECT 1 as ouput;
	
	LEAVE proc_label;	
	
END IF;

-- get beginning of financial year
SELECT DATE(configuration_value)  INTO @begin_fin_year_date FROM configuration c where c.configuration_key='STARTFINYEAR' AND c.branch_code=branch_code;

-- MM/DD/YYYY
-- close all transaction set closed to Y
UPDATE generalledger SET generalledger_locked='Y' WHERE  DATE(generalledger_tday)<=enddate AND branch_code=branch_code;


SET tname = CONCAT(branch_code,'_',CAST(YEAR(enddate) AS CHAR(50)),CAST(MONTH(enddate) AS CHAR(50)),CAST(DAY(enddate) AS CHAR(50)));

-- extract the historical financial data from general ledger into another table
-- save  record(table name )for closedperiods table

SET @squery = CONCAT('DROP TABLE IF EXISTS closed_',tname);

PREPARE stmt FROM @squery;

EXECUTE stmt;

 SET @squery = CONCAT('CREATE TABLE closed_',tname,' SELECT * FROM generalledger WHERE  DATE(generalledger_tday)<=',QUOTE(enddate),'AND branch_code=',QUOTE(branch_code));

PREPARE stmt FROM @squery;

EXECUTE stmt;

--  Computer balances for non I/E accounts i.e '1','2'
--  get non income and expenditure accounts - may be based on product
DROP TABLE IF EXISTS sp_close_period_table1;
CREATE TEMPORARY TABLE sp_close_period_table1 AS (
SELECT 
'0000000000000' transactioncode,
g.chartofaccounts_accountcode,
CASE WHEN (SUM(generalledger_debit)-SUM(generalledger_credit))>0 THEN (SUM(generalledger_debit)-SUM(generalledger_credit)) ELSE CAST(0.00 AS DECIMAL(15,2)) END debit,
CASE WHEN (SUM(generalledger_credit)-SUM(generalledger_debit))>0 THEN (SUM(generalledger_credit)-SUM(generalledger_debit)) ELSE CAST(0.00 AS DECIMAL(15,2)) END credit,
g.branch_code,
g.product_prodid,
g.costcenters_code,
'000'user_id,
'OB000' trancode,
CASE WHEN (SUM(generalledger_debit)-SUM(generalledger_credit))>0 THEN SUM(generalledger_fcamount) ELSE CAST(0.00 AS DECIMAL(15,2)) END fcdebit,
CASE WHEN (SUM(generalledger_credit)-SUM(generalledger_debit))>0 THEN SUM(generalledger_fcamount) ELSE CAST(0.00 AS DECIMAL(15,2)) END fccredit,
g.currencies_id
 FROM generalledger g ,chartofaccounts ca WHERE g.chartofaccounts_accountcode=ca.chartofaccounts_accountcode AND (ca.chartofaccounts_tgroup=1 OR ca.chartofaccounts_tgroup=2) AND DATE(g.generalledger_tday)<= DATE(enddate) AND g.branch_code=branch_code GROUP BY g.branch_code,g.chartofaccounts_accountcode,g.product_prodid,g.costcenters_code,g.currencies_id);
  
-- get beging of year date
SET opendate = ADDDATE(enddate, INTERVAL 1 DAY);

-- insert opening balanaces
 INSERT INTO generalledger(
 chartofaccounts_accountcode,
 generalledger_tday,
  transactioncode,
 generalledger_description,
 generalledger_id,
 fund_code,
 donor_code,
 generalledger_credit,
 generalledger_voucher,
 user_id,
 generalledger_debit,
 branch_code,
 trancode,
 generalledger_locked,
 forexrates_id,
 generalledger_fcamount,
 currencies_id,
 product_prodid,
 costcenters_code)
 SELECT chartofaccounts_accountcode,opendate,transactioncode,@descip,UUID(),'0000','00000',credit,'',user_id,debit,branch_code,trancode,'Y',0,
 CASE WHEN fcdebit > 0 THEN fcdebit ELSE fccredit END ,currencies_id,product_prodid,costcenters_code FROM sp_close_period_table1 WHERE debit >0 OR credit >0;
 
 
 -- get the profit and loss account(s)
SELECT configuration_value  INTO @profit_loss_acc FROM configuration c where c.configuration_key='SETTING_PROFIT_LOSS_ACC' AND c.branch_code=branch_code;

-- compute profit and loss form Income/Expenditure accounts and insert into the general ledger -Profit and loss Account
DROP TABLE IF EXISTS sp_close_profit_loss_table1;
CREATE TEMPORARY TABLE sp_close_profit_loss_table1 AS (
SELECT 
'0000000000000' transactioncode,
@profit_loss_acc chartofaccounts_accountcode,
CASE WHEN (SUM(generalledger_debit)-SUM(generalledger_credit))>0 THEN (SUM(generalledger_debit)-SUM(generalledger_credit)) ELSE CAST(0.00 AS DECIMAL(15,2)) END debit,
CASE WHEN (SUM(generalledger_credit)-SUM(generalledger_debit))>0 THEN (SUM(generalledger_credit)-SUM(generalledger_debit)) ELSE CAST(0.00 AS DECIMAL(15,2)) END credit,
g.branch_code,
g.product_prodid,
g.costcenters_code,
'000'user_id,
'OB000' trancode,
CASE WHEN (SUM(generalledger_debit)-SUM(generalledger_credit))>0 THEN SUM(generalledger_fcamount) ELSE CAST(0.00 AS DECIMAL(15,2)) END fcdebit,
CASE WHEN (SUM(generalledger_credit)-SUM(generalledger_debit))>0 THEN SUM(generalledger_fcamount) ELSE CAST(0.00 AS DECIMAL(15,2)) END fccredit,
g.currencies_id
 FROM generalledger g ,chartofaccounts ca WHERE g.chartofaccounts_accountcode=ca.chartofaccounts_accountcode AND chartofaccounts_tgroup!=1 AND chartofaccounts_tgroup!=2 AND DATE(g.generalledger_tday)<= DATE(enddate) AND 
 g.branch_code=branch_code GROUP BY g.branch_code,g.product_prodid,g.costcenters_code,g.currencies_id);
  
-- insert profit and loss
-- insert opening balanaces
 INSERT INTO generalledger(
 chartofaccounts_accountcode,
 generalledger_tday,
 transactioncode,
 generalledger_description,
 generalledger_id,
 fund_code,
 donor_code,
 generalledger_credit,
 generalledger_voucher,
 user_id,
 generalledger_debit,
 branch_code,
 trancode,
 generalledger_locked,
 forexrates_id,
 generalledger_fcamount,
 currencies_id,
 product_prodid,
 costcenters_code)
 SELECT chartofaccounts_accountcode,opendate,transactioncode,@descip1,UUID(),'0000','00000',credit,'',user_id,debit,branch_code,trancode,'Y',0,
 CASE WHEN fcdebit > 0 THEN fcdebit ELSE fccredit END ,currencies_id,product_prodid,costcenters_code FROM sp_close_profit_loss_table1 WHERE debit >0 OR credit > 0;

 -- Delete the historical data from general ledger
 DELETE FROM generalledger WHERE DATE(generalledger_tday)<= DATE(enddate) AND branch_code=branch_code;

-- set begining of financial year
UPDATE configuration SET configuration_value =opendate WHERE configuration_key='STARTFINYEAR' AND branch_code=branch_code;


INSERT INTO closedperiod(closedperiod_starts,closedperiod_ends,closedperiod_tablename,period,branch_code) VALUES(startdate,enddate,CONCAT('closed_',tname),cperiod,branch_code);

SELECT 1 as ouput;	


END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_open_period
DROP PROCEDURE IF EXISTS `sp_open_period`;
DELIMITER //
CREATE PROCEDURE `sp_open_period`(
	IN `branch_code` TINYTEXT,
	IN `enddate` DATE
)
BEGIN

	DECLARE vends DATE;
	DECLARE vstarts DATE;
	DECLARE vtablename CHAR(100) DEFAULT '';
	DECLARE Done1 BIT DEFAULT false;  
	DECLARE vperiod CHAR(1) DEFAULT '';
	
	DECLARE curClosed CURSOR FOR SELECT closedperiod_starts,closedperiod_ends,closedperiod_tablename,period  FROM closedperiod WHERE branch_code=branch_code AND closedperiod_ends<=enddate;
	
	-- loop status handler for loop1
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET Done1:= true; 
	OPEN curClosed; 

	loop1:LOOP

	FETCH curClosed INTO vstarts,vends,vtablename,vperiod;
		IF Done1 THEN
			CLOSE curClosed;
	      		
			LEAVE loop1;
		END IF;
	
		-- remove opening balances
		DELETE FROM generalledger WHERE transactioncode='0000000000000' AND branch_code=branch_code;

		-- insert transaction back to the general ledger
		SET @squery = CONCAT('INSERT INTO generalledger(
		 chartofaccounts_accountcode,
		 generalledger_tday,
		 transactioncode,
		 generalledger_description,
		 generalledger_id,
		 fund_code,
		 donor_code,
		 generalledger_credit,
		 generalledger_voucher,
		 user_id,
		 generalledger_debit,
		 branch_code,
		 trancode,
		 generalledger_locked,
		 forexrates_id,
		 generalledger_fcamount,
		 currencies_id,
		 product_prodid,
		 costcenters_code) 
		 SELECT 
		 chartofaccounts_accountcode,
		 generalledger_tday,
		 transactioncode,
		 generalledger_description,
		 generalledger_id,
		 fund_code,
		 donor_code,
		 generalledger_credit,
		 generalledger_voucher,
		 user_id,
		 generalledger_debit,
		 branch_code,
		 trancode,
		 generalledger_locked,
		 forexrates_id,
		 generalledger_fcamount,
		 currencies_id,
		 product_prodid,
		 costcenters_code
		 FROM ',vtablename);

	--	INSERT INTO errors (err)values(@squery);
		
		PREPARE stmt FROM @squery;

		EXECUTE stmt;
		
		SET @squery = CONCAT('DROP TABLE IF EXISTS ',vtablename);
			
		PREPARE stmt FROM @squery;
		
		EXECUTE stmt;
		
		IF vperiod='Y' THEN
			UPDATE configuration c SET c.configuration_value=vstarts  WHERE c.configuration_key='STARTFINYEAR' AND c.branch_code=branch_code;
		END IF;
		
		DELETE FROM closedperiod WHERE closedperiod_tablename = vtablename;
		
	IF Done1 THEN
		CLOSE curClosed;
      		
		LEAVE loop1;
	END IF;
    		
	END LOOP loop1;


END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_pull_future_dues
DROP PROCEDURE IF EXISTS `sp_pull_future_dues`;
DELIMITER //
CREATE PROCEDURE `sp_pull_future_dues`(
	IN `loannumber` CHAR(50),
	IN `pdate` DATE
,
	IN `princpaid` NUMERIC(15,4),
	IN `intpaid` NUMERIC(15,4),
	IN `commpaid` NUMERIC(15,4),
	IN `penpaid` NUMERIC(15,4),
	IN `vatpaid` INT,
	IN `memberid` CHAR(50)














)
BEGIN
DECLARE ddate DATE;
	DECLARE d_date DATE;
	DECLARE dprinc NUMERIC(15,5)  DEFAULT 0;
	DECLARE dint NUMERIC(15,5)  DEFAULT 0;
	DECLARE dcomm NUMERIC(15,5)  DEFAULT 0;
	DECLARE dpen NUMERIC(15,5)  DEFAULT 0;
	DECLARE dvat NUMERIC(15,5)  DEFAULT 0;	
	DECLARE dueid CHAR(100) DEFAULT '';

	DECLARE totpay NUMERIC(15,5)  DEFAULT 0;
	
	DECLARE Done1 BIT DEFAULT false; 
	
	DECLARE updatedues BIT DEFAULT false; 
	
	-- select all installments for this loan
  DECLARE curDues CURSOR FOR SELECT due_id,DATE(d.due_date) due_date,d.due_principal,d.due_interest,d.due_commission,d.due_penalty,d.due_vat FROM dues d WHERE d.loan_number=loannumber;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET Done1= true;  
  
  SELECT COALESCE(SUM(p.loanpayments_principal),0),COALESCE(SUM(p.loanpayments_interest),0),COALESCE(SUM(p.loanpayments_commission),0),COALESCE(SUM(p.loanpayments_penalty),0),COALESCE(SUM(p.loanpayments_vat),0) INTO @tprinc,@tint,@tcomm,@tpen,@tvat from loanpayments p WHERE p.loan_number=loannumber;
		
	-- GET TOTAL PAID			
  SET @tprinc =  princpaid;
  SET @tint =  intpaid;
  SET @tcomm  =  commpaid;
  SET @tpen  =  penpaid;
  SET @tvat =  vatpaid;
 
  SET @totpaid = @tprinc + @tint + @tcomm + @tpen+ @tvat;
  
  SET d_date = pdate;
 
  set @nCount :=1;
  
  SET Done1= FALSE;
  
  SET updatedues = FALSE;
  
  OPEN curDues;
	
		loop1: LOOP	
				
		FETCH curDues INTO dueid,ddate,dprinc,dint,dcomm,dpen,dvat;			
    	
    	IF Done1 = true THEN 					
      	LEAVE loop1;      			
    	END IF; 
    	
			set @nCount = @nCount+1;
					
		--	SELECT @totpaid; 
			
			IF ddate > pdate AND  @totpaid >= dprinc THEN
			
				SET updatedues = TRUE;
					
			ELSE
			
				IF updatedues = FALSE THEN							
					SET  @totpaid  = @totpaid -(dprinc + dint + dcomm + dpen + dvat);
				END IF;
			
					
			END IF;
			
		
			IF updatedues = true THEN	
			
					IF @totpaid >= dprinc THEN
					
						UPDATE dues  SET due_date = pdate WHERE DATE(due_date) = ddate AND loan_number=loan_number AND TRIM(due_id)=TRIM(dueid);
					
					ELSE				
					
						-- GET LAST DATE OF NEXT MONTH
						SET d_date = LAST_DAY(DATE_ADD(d_date, INTERVAL 1 MONTH));
						
						-- UPDATE DUES RESPECTIVELY
						UPDATE dues  SET due_date=d_date,due_interest=0 WHERE DATE(due_date) = ddate AND loan_number=loan_number AND TRIM(due_id)=TRIM(dueid);
											
		
					END IF;
							
					
					SET  @totpaid  = @totpaid - dprinc;
			
			END IF;								
		
    	
		END LOOP loop1;
		
		CLOSE curDues;		

			
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_recalculate_int
DROP PROCEDURE IF EXISTS `sp_recalculate_int`;
DELIMITER //
CREATE PROCEDURE `sp_recalculate_int`(
	IN `branch_code` TINYTEXT,
	IN `loan_number` CHAR(50),
	IN `members_idno` CHAR(50),
	IN `paydate` DATE,
	IN `principalbal` NUMERIC(15,4),
	IN `product_prodid` CHAR(10),
	IN `addtemptable` CHAR(1)


)
    COMMENT 'This procedure is used to update databse tables with incoming XML transactions'
BEGIN

	-- declare variables
   DECLARE lastrepaymentdate DATE DEFAULT '';
   DECLARE intrate NUMERIC(10,5) DEFAULT 0;
  	DECLARE damount NUMERIC(15,5) DEFAULT 0;
   DECLARE ndays NUMERIC(15) DEFAULT 0;
   DECLARE intperday NUMERIC(15,5) DEFAULT 0;
   

   DECLARE rounding INT DEFAULT 0;
 	DECLARE weeksinyear INT DEFAULT 52;
 	DECLARE daysinyear INT DEFAULT 365;
 	DECLARE nround INT DEFAULT 0;
 	
	SELECT configuration_value INTO @nround FROM configuration WHERE configuration_key='SETTTING_ROUND_TO' ;
	

	-- get disbursed amount
	SELECT l.loan_tint,SUM(d.disbursements_amount),l.loan_intdays,l.loan_insttype INTO @intrate,@damount,@int_days,@ins_type FROM loan l,disbursements d WHERE  l.loan_number=d.loan_number AND l.loan_number=loan_number GROUP BY d.loan_number;
	
	
	IF @int_days= 'N' AND @ins_type='M' THEN
		
		SET @intperday = @intrate/100/12;
	
	ELSE
		SELECT pc.productconfig_value INTO @daysinyear FROM productconfig pc WHERE pc.productconfig_paramname ='INT_DAYS' AND pc.product_prodid=product_prodid;
 	
		SET @intperday = @intrate/100/@daysinyear;
	
	END IF;
	
	SELECT MAX(lp.loanpayments_date) INTO @lastrepaymentdate FROM loanpayments lp WHERE lp.loan_number=loan_number group by lp.loan_number;
	
	
	-- check see if we have any repayments
	-- else use the dibursement date
	IF @lastrepaymentdate='' OR @lastrepaymentdate IS NULL THEN
		SELECT MAX(d.disbursements_date) INTO @lastrepaymentdate FROM disbursements d WHERE d.loan_number=loan_number GROUP BY d.loan_number ;	
	END IF;
	
	-- get number of days to be paid
	-- TO DO: grace period
	
	IF @int_days= 'N' AND @ins_type='M' THEN
		SET @intdue = ROUND(@intperday * principalbal,@nround);	
	ELSE
	
		SET @ndays = DATEDIFF(paydate, DATE(@lastrepaymentdate));
		SET @intdue = ROUND(@intperday * principalbal * @ndays,@nround);
	END IF;

	-- get last repayment date
	


	       
	IF addtemptable = '1'  THEN
		DROP tABLE IF EXISTS sp_interest_table;
		
		CREATE TEMPORARY TABLE sp_interest_table AS (SELECT @intdue as interest);-- get interest per day;	
		
		-- SELEct @intdue as interest;
		
	ELSE   
		SELEct @intdue as interest;
	END IF;
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_reverse_transactions
DROP PROCEDURE IF EXISTS `sp_reverse_transactions`;
DELIMITER //
CREATE PROCEDURE `sp_reverse_transactions`(
	IN `branch_code` TINYTEXT,
	IN `transactioncodes` CHAR(50),
	IN `ttype` CHAR(3),
	IN `userid` CHAR(50),
	IN `plang` CHAR(5),
	INOUT `bsuccess` BIT





















)
BEGIN

	DECLARE cDescription VARCHAR(5) DEFAULT '';	
	DECLARE vaccount1 CHAR(100) DEFAULT '';
	DECLARE vproduct_prodid1 CHAR(20) DEFAULT ''; 
	DECLARE updateDone1 INT DEFAULT 0;

	-- set language for oprning balance description
	CASE plang  WHEN 'EN' THEN SET @cDescription :='Reversal' ;
	
	WHEN 'FR' THEN SET @cDescription :='Renversement';
	
	WHEN 'SP' THEN SET @cDescription :='InversiÃƒÂ³n';
	
	ELSE SET @cDescription:= 'Reversal' ;
	
	END CASE;
	
	START TRANSACTION;	 
	 
	 -- savings
	 IF ttype  ='S' THEN
	 
				SELECT savaccounts_account INTO @accounts FROM savtransactions  where FIND_IN_SET(CAST(TRIM(transactioncode) AS DECIMAL(50)),trim(transactioncodes))>0  LIMIT 1;
	
	SELECT product_prodid INTO @prodid FROM savtransactions  where FIND_IN_SET(TRIM(transactioncode),trim(transactioncodes))>0  LIMIT 1;
	
	 		DELETE FROM  savtransactions WHERE  FIND_IN_SET(transactioncode,trim(transactioncodes))>0;
	 		
	 		
	 		-- UPDATE BALANCES
	 		 UPDATE savtransactions AS w
		    SET w.savtransactions_balance =  (@prevbalance:= (@prevbalance + w.savtransactions_amount))
		    WHERE w.savaccounts_account = @accounts and w.product_prodid =@prodid
		    ORDER BY w.savtransactions_tday ASC,w.last_updatedate;
    
	END IF;
	
	IF ttype  ='L' THEN	 
		DELETE FROM  loanpayments WHERE FIND_IN_SET(transactioncode,trim(transactioncodes))>0;	
		DELETE FROM  savtransactions WHERE FIND_IN_SET(transactioncode,trim(transactioncodes))>0;
	END IF;
		 
	IF ttype  ='T' THEN	 
		DELETE FROM  timedeposittrans WHERE FIND_IN_SET(transactioncode,trim(transactioncodes))>0;
		DELETE FROM  savtransactions WHERE FIND_IN_SET(transactioncode,trim(transactioncodes))>0;			
	END IF;
		 
		 -- general ledger
	 
		 	INSERT INTO generalledger (
			   generalledger_id,			 
			 	transactioncode,
				generalledger_description,
				fund_code,
				donor_code,
				generalledger_voucher,
				generalledger_tday,
				generalledger_debit,
				generalledger_credit,
			 	chartofaccounts_accountcode,
				branch_code,
				trancode,
				generalledger_locked,
				forexrates_id,
				generalledger_fcamount,
				currencies_id,
				client_idno,
				product_prodid,
				costcenters_code,
				user_id)	 
			SELECT 
				UUID(),
				transactioncode,
				CONCAT(transactioncode,' ',@cDescription) generalledger_description,
				fund_code,
				donor_code,
				generalledger_voucher,
				generalledger_tday,
				CASE WHEN generalledger_credit <> 0 THEN generalledger_credit END generalledger_debit,
				CASE WHEN generalledger_debit <> 0 THEN generalledger_debit END generalledger_credit,
			 	chartofaccounts_accountcode,
				 branch_code,
				 trancode,
				 generalledger_locked,
				 forexrates_id,
				 generalledger_fcamount,
				 currencies_id,
				 client_idno,
				 product_prodid,
				 costcenters_code,
				 user_id FROM generalledger WHERE FIND_IN_SET(transactioncode ,trim(transactioncodes))>0;		    
	 

				
				SET bsuccess = 1;	
		
		
	COMMIT;


END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_reverse_transactions_wrapper
DROP PROCEDURE IF EXISTS `sp_reverse_transactions_wrapper`;
DELIMITER //
CREATE PROCEDURE `sp_reverse_transactions_wrapper`(
	IN `branch_code` TINYTEXT,
	IN `transactioncodes` CHAR(50),
	IN `ttype` CHAR(3),
	IN `userid` CHAR(50),
	IN `plang` CHAR(5)


)
BEGIN

DECLARE bsuccess BIT;

CALL `sp_reverse_transactions`(branch_code, transactioncodes,ttype,userid, plang, @bsuccess);

select @bsuccess bsuccess;


END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_savings_details
DROP PROCEDURE IF EXISTS `sp_savings_details`;
DELIMITER //
CREATE PROCEDURE `sp_savings_details`(
	IN `branch_code` TINYTEXT,
	IN `client_idno` CHAR(100),
	IN `product_prodid` CHAR(50)
)
BEGIN

	-- TO DO:
	-- Overdrafts

DECLARE smainquery MEDIUMTEXT DEFAULT '';

 drop table if exists sp_savings_details_table1;	
 	
 SET @smainquery = CONCAT('CREATE TEMPORARY TABLE sp_savings_details_table1 AS (SELECT COALESCE(t.savaccounts_account,'') savaccounts_account,COALESCE(t.product_prodid,'') product_prodid, SUM(COALESCE(t.savtransactions_amount,0)) balance from savaccounts a,savtransactions t WHERE a.savaccounts_account=t.savaccounts_account AND a.client_idno=', QUOTE(TRIM(client_idno)));
	
	IF product_prodid!=''  AND product_prodid IS NOT NULL  THEN 	
 		SET @smainquery = CONCAT(@smainquery,' AND t.product_prodid =',QUOTE(TRIM(product_prodid)));
 	END IF;

	SET @smainquery = CONCAT(@smainquery,' GROUP BY t.savaccounts_account,t.product_prodid)');
  
--	INSERT  into errors select @smainquery;

	PREPARE stmt FROM @smainquery;
 	
	EXECUTE stmt;
 
	DEALLOCATE PREPARE stmt;
	
	SELECT * FROM sp_savings_details_table1;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_search_loan
DROP PROCEDURE IF EXISTS `sp_search_loan`;
DELIMITER //
CREATE PROCEDURE `sp_search_loan`(
	IN `searchterm` VARCHAR(100)
)
    COMMENT 'This procedure is used to get summarised loan details'
BEGIN


DECLARE rowcount BIGINT DEFAULT 0;
DECLARE totalnrows  BIGINT DEFAULT 0;
DECLARE vloan_number  CHAR(50) DEFAULT '';
DECLARE vproduct_prodid  CHAR(10) DEFAULT '';
DECLARE vmembers_idno  CHAR(50) DEFAULT '';
DECLARE vprincipalbal NUMERIC(15,4) DEFAULT 0;
DECLARE varInt NUMERIC(15,4) DEFAULT 0;
DECLARE no_int CHAR(1);
DECLARE recalint CHAR(1);

DECLARE smainquery TINYTEXT DEFAULT '';
DECLARE squery TINYTEXT DEFAULT '';


DROP TABLE IF EXISTS loans_filtered_table;


CREATE TEMPORARY TABLE loans_filtered_table AS (SELECT CONCAT(c.client_surname," ",c.client_middlename," ",c.client_firstname,c.client_grpname) name, l.client_idno,d.loan_number,d.members_idno,SUM(d.disbursements_amount) loan_amount,l.product_prodid,l.loan_inttype,l.loan_insttype,l.loan_tint,l.loan_grace,l.loan_intcgrace,l.loan_noofinst,l.loan_intfirst,loan_insintgrac,l.loan_alsograce,l.loan_inupfront,(SELECT pc.productconfig_value FROM productconfig pc WHERE pc.productconfig_paramname="REF_PRIORITY" AND pc.product_prodid=l.product_prodid GROUP BY pc.product_prodid) ref_priority FROM disbursements d,clients c,loan l WHERE C.client_idno=l.client_idno AND d.loan_number=l.loan_number   AND  (l.loan_number LIKE  CONCAT('%', searchterm, '%')  OR c.client_firstname LIKE  CONCAT('%', searchterm, '%') OR c.client_middlename LIKE  CONCAT('%', searchterm, '%') OR c.client_surname LIKE  CONCAT('%', searchterm, '%')) AND d.loan_number NOT IN (SELECT w.loan_number FROM loanswrittenoff w WHERE l.loan_number=w.loan_number) GROUP BY d.loan_number);


-- insert into errors select @smainquery;

-- PREPARE stmt FROM @smainquery;
 	
-- EXECUTE stmt;

-- get amounts payable
 DROP TABLE IF EXISTS sp_get_charge_int;
 
CREATE TEMPORARY TABLE sp_get_charge_int AS (SELECT COALESCE(product_prodid,'') prodid,COALESCE(productconfig_value,0) value FROM productconfig WHERE productconfig_paramname='CHARGE_INT' GROUP BY product_prodid);


SELECT NOW() INTO @asatdate;



-- get amounts payable
 DROP TABLE IF EXISTS sp_get_loan_dues;
 
--  total dues and dues before current date
CREATE TEMPORARY TABLE sp_get_loan_dues AS (select
l.loan_number,
d.members_idno,
SUM(COALESCE(d.due_principal,0))  ddprinc,
 SUM(COALESCE(d.due_interest,0))	ddint,
 SUM(COALESCE(d.due_commission,0)) ddcomm,
 SUM(COALESCE(d.due_penalty,0)) ddpen,
SUM(COALESCE(d.due_vat,0))  ddvat,
SUM(CASE WHEN DATE(d.due_date) < DATE(@asatdate) THEN COALESCE(d.due_principal,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) dprinc,
SUM(CASE WHEN DATE(d.due_date) < DATE(@asatdate) THEN COALESCE(d.due_interest,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) dint,
SUM(CASE WHEN DATE(d.due_date) < DATE(@asatdate) THEN COALESCE(d.due_commission,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) dcomm,
SUM(CASE WHEN DATE(d.due_date) < DATE(@asatdate) THEN COALESCE(d.due_penalty,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) dpen,
SUM(CASE WHEN DATE(d.due_date) < DATE(@asatdate) THEN COALESCE(d.due_vat,0) ELSE 0 END) dvat,
SUM(CASE WHEN DATE(d.due_date) = DATE(@asatdate) THEN COALESCE(d.due_principal,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) nprinc,
SUM(CASE WHEN DATE(d.due_date) = DATE(@asatdate) THEN COALESCE(d.due_interest,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) nint,
SUM(CASE WHEN DATE(d.due_date) = DATE(@asatdate) THEN COALESCE(d.due_commission,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) ncomm,
SUM(CASE WHEN DATE(d.due_date) = DATE(@asatdate) THEN COALESCE(d.due_penalty,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) npen,
SUM(CASE WHEN DATE(d.due_date) = DATE(@asatdate) THEN COALESCE(d.due_vat,0) ELSE CAST(0.00 AS DECIMAL(15,2)) END) nvat
FROM  dues d ,loans_filtered_table l WHERE d.loan_number =l.loan_number  GROUP BY d.loan_number);

-- get all payments
DROP TABLE IF EXISTS sp_get_loan_payments;
 
CREATE TEMPORARY TABLE sp_get_loan_payments (SELECT 
p.loan_number,
p.members_idno,
SUM(COALESCE(p.loanpayments_principal,0)) pprinc,
SUM(COALESCE(p.loanpayments_interest,0)) pint,
SUM(COALESCE(p.loanpayments_commission,0)) pcomm,
SUM(COALESCE(p.loanpayments_penalty,0)) ppen, 
SUM(COALESCE(p.loanpayments_vat,0)) pvat
FROM  loanpayments p,loans_filtered_table l WHERE p.loan_number =l.loan_number  GROUP BY p.loan_number);



DROP TABLE IF EXISTS sp_get_out_loan_dues1;

CREATE TEMPORARY TABLE sp_get_out_loan_dues1 (SELECT 
d.loan_number,
d.members_idno,
SUM(COALESCE(d.dprinc,0))-SUM(COALESCE(p.pprinc,0)) arprinc,
SUM(COALESCE(d.dint,0))-SUM(COALESCE(p.pint,0)) arint,
SUM(COALESCE(d.dcomm,0))-SUM(COALESCE(p.pcomm,0)) arcomm,
SUM(COALESCE(d.dpen,0))-SUM(COALESCE(p.ppen,0)) arpen,
SUM(COALESCE(d.dvat,0))-SUM(COALESCE(p.pvat,0)) arvat,
SUM(d.ddprinc)-(SUM(COALESCE(d.dprinc,0))+ SUM(COALESCE(d.nprinc,0))) cprinc,
SUM(d.ddint)-(SUM(COALESCE(d.dint,0)) + SUM(COALESCE(d.nint,0))) cint,
SUM(d.ddcomm)-(SUM(COALESCE(d.dcomm,0)) + SUM(COALESCE(d.ncomm,0))) ccomm,
SUM(d.ddpen)-(SUM(COALESCE(d.dpen,0)) + SUM(COALESCE(d.npen,0))) cpen,
SUM(d.ddvat)-(SUM(COALESCE(d.dvat,0)) + SUM(COALESCE(d.nvat,0))) cdvat ,
COALESCE(p.pprinc,0) pprinc,
COALESCE(d.ddint,0) ddint,
COALESCE(d.ddcomm,0) ddcomm,
COALESCE(d.ddpen,0) ddpen,
COALESCE(d.ddvat,0) ddvat
FROM sp_get_loan_dues d LEFT JOIN sp_get_loan_payments p ON p.loan_number=d.loan_number 
 GROUP BY d.loan_number);

DROP TABLE IF EXISTS sp_get_out_loan_dues2;

CREATE TEMPORARY TABLE sp_get_out_loan_dues2 (
SELECT 
l.name,
l.loan_number,
l.client_idno,
l.loan_amount,
d.members_idno,
l.product_prodid,
l.ref_priority,
l.loan_inttype,l.loan_insttype,l.loan_tint,l.loan_grace,l.loan_intcgrace,l.loan_noofinst,l.loan_intfirst,loan_insintgrac,l.loan_alsograce,l.loan_inupfront,
d.arprinc,
d.arint,
d.arcomm,
d.arpen,
d.arvat,
(CASE WHEN d.arprinc < 0 THEN d.cprinc + d.arprinc ELSE d.cprinc END) dprinc,
(CASE WHEN d.arint < 0 THEN d.cint + d.arint ELSE d.cint END) dint,
(CASE WHEN d.arcomm < 0 THEN d.ccomm + d.arcomm ELSE d.ccomm END) dcomm,
(CASE WHEN d.arpen < 0 THEN d.cpen + d.arpen ELSE d.cpen END) dpen,
(CASE WHEN d.arvat < 0 THEN d.cdvat + d.arvat ELSE d.cdvat END) ddvat,
COALESCE(d.pprinc,0) princpaid,
ddint outint,
ddcomm outcomm,
ddpen  outpen,
ddvat outvat
FROM sp_get_out_loan_dues1 d, loans_filtered_table l WHERE l.loan_number=d.loan_number);

DROP TABLE IF EXISTS sp_get_loan_dues_payable4;

CREATE TEMPORARY TABLE sp_get_loan_dues_payable4 AS (

SELECT l.*,(SUM(COALESCE(disbursements_amount,0))- COALESCE(princpaid,0)) outprinc FROM sp_get_out_loan_dues2 l,disbursements d WHERE d.loan_number=l.loan_number  GROUP BY d.loan_number);

DROP TABLE IF EXISTS sp_get_loan_dues_payable5;

CREATE TEMPORARY TABLE sp_get_loan_dues_payable5 (SELECT SQL_CALC_FOUND_ROWS * FROM sp_get_loan_dues_payable4);

 SET @nrows = FOUND_ROWS();

SELECT l.*,@nrows reccount FROM sp_get_loan_dues_payable5 l;



END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_search_loan_disburse
DROP PROCEDURE IF EXISTS `sp_search_loan_disburse`;
DELIMITER //
CREATE PROCEDURE `sp_search_loan_disburse`(
	IN `searchterm` VARCHAR(100)

,
	IN `user_id` CHAR(50)














)
    COMMENT 'This procedure is used to get loans to disburse'
BEGIN

DECLARE smainquery MEDIUMTEXT DEFAULT '';

	CALL `sp_get_client_details`('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1);

SET @client_squery  ='';

DROP TABLE IF EXISTS sp_search_loan_disburse_table1;

SET @smainquery = 'CREATE TEMPORARY TABLE sp_search_loan_disburse_table1 AS (SELECT CONCAT(c.client_firstname,"",c.client_middlename," ",c.client_surname) name,l.loan_number, l.client_idno,l.loan_amount,l.loan_startdate ,(SELECT x.loan_amount FROM loanstatuslog x WHERE x.loan_number=l.loan_number  AND x.loan_status="RFAP") topup FROM (SELECT p.loan_number,p.client_idno,p.loan_amount,p.loan_startdate FROM loan p WHERE  p.loan_number  NOT IN (SELECT sl.loan_number FROM loanstatuslog sl WHERE sl.loan_number=p.loan_number AND sl.loan_status="LD") OR p.loan_number IN (SELECT s.loan_number FROM loanstatuslog s WHERE s.loan_number=p.loan_number AND s.loan_status="RFAP")) l JOIN clients_filtered_table c ON c.client_idno=l.client_idno';

IF searchterm!='' THEN		
 SET @client_squery = CONCAT(' WHERE l.loan_number LIKE  "%', searchterm, '%"  OR c.client_firstname LIKE  "%', searchterm, '%" OR c.client_middlename LIKE  "%', searchterm, '%" OR c.client_surname LIKE  "%', searchterm, '%"');
END IF;	
	SET @smainquery = CONCAT(@smainquery,@client_squery,')');

 -- insert into errors select @smainquery;
	
	PREPARE stmt FROM @smainquery;
	 	
	EXECUTE stmt;
	
	DEALLOCATE PREPARE stmt;
	 


  SELECT * FROM sp_search_loan_disburse_table1;	
  
  SELECT FOUND_ROWS() reccount;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_update_loan_products_settings
DROP PROCEDURE IF EXISTS `sp_update_loan_products_settings`;
DELIMITER //
CREATE PROCEDURE `sp_update_loan_products_settings`(
	IN `productprodid` CHAR(50),
	IN `currencies_id` INT,
	IN `maximun_loan_amount` DECIMAL(15,5),
	IN `maximun_loan_amount_activated` CHAR(1),
	IN `savings_guarantee_amount` DECIMAL(15,5),
	IN `savings_guarantee_amount_per` DECIMAL(10,2),
	IN `savings_guarantee_amount_activated` CHAR(1),
	IN `number_of_installments` BIGINT,
	IN `number_of_installments_activated` CHAR(1),
	IN `minimum_loan_amount` DECIMAL(15,5),
	IN `interest_type` CHAR(5),
	IN `interest_type_activated` CHAR(1),
	IN `interest_rate` DECIMAL(10,5),
	IN `interest_rate_activated` CHAR(1),
	IN `installment_type` CHAR(50),
	IN `installment_type_activated` CHAR(1),
	IN `pri_in_arr` CHAR(1),
	IN `int_in_arr` CHAR(1),
	IN `com_in_arr` CHAR(1),
	IN `pen_in_arr` CHAR(1),
	IN `charge_int` CHAR(1),
	IN `int_days` TINYINT,
	IN `int_weeks` TINYINT,
	IN `recalc_int` TINYINT,
	IN `no_int` INT,
	IN `pay_priority` CHAR(20),
	IN `ref_priority` CHAR(20),
	IN `service_fee` DECIMAL(15,5),
	IN `service_fee_acc` CHAR(50),
	IN `sav_at_repay` TINYINT,
	IN `saving_at_loan_repay_amt` DECIMAL(15,5)
,
	IN `pull_dues_after_prepayments` CHAR(1)

,
	IN `loan_com_from_sav` CHAR(1)
,
	IN `allow_overpayments` CHAR(1)
,
	IN `branchcode` CHAR(50)







)
    COMMENT 'used to update product settings'
BEGIN

-- VARIABLES WITH NAMES SIMILAR TO COLUMN NAMES MUST BE QUALIFIED IS AN @

-- CURRENCIES_ID

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'CURRENCIES_ID','N',currencies_id,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = currencies_id;

-- MAXIMUM_LOAN_AMOUNT

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'MAXIMUM_LOAN_AMOUNT','N',maximun_loan_amount,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = maximun_loan_amount;

-- MINIMUM_LOAN_AMOUNT

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'MINIMUM_LOAN_AMOUNT','N',minimum_loan_amount,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = minimum_loan_amount;


-- SAVINGS_GUARANTEE_AMOUNT_PER

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'SAVINGS_GUARANTEE_AMOUNT_PER','N',savings_guarantee_amount_per,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = savings_guarantee_amount_per;

-- SAVINGS_GUARANTEE_AMOUNT

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'SAVINGS_GUARANTEE_AMOUNT','N',savings_guarantee_amount,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = savings_guarantee_amount;

-- NUMBER_OF_INSTALLMENTS

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'NUMBER_OF_INSTALLMENTS','N',number_of_installments,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = number_of_installments;

-- NUMBER_OF_INSTALLMENTS_ACTIVATED

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'NUMBER_OF_INSTALLMENTS_ACTIVATED','C',number_of_installments_activated,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'C',
	productconfig_value = number_of_installments_activated;

-- INTEREST_TYPE

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'INTEREST_TYPE','C',interest_type,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'C',
	productconfig_value = interest_type;

-- INTEREST_TYPE_ACTIVATED

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'INTEREST_TYPE_ACTIVATED','C',interest_type_activated,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'C',
	productconfig_value = interest_type_activated;

-- INTEREST_RATE

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'INTEREST_RATE','N',interest_rate,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = interest_rate;

-- INTEREST_RATE_ACTIVATED

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'INTEREST_RATE_ACTIVATED','C',interest_rate_activated,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'C',
	productconfig_value = interest_rate_activated;

-- INSTALLMENT_TYPE

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'INSTALLMENT_TYPE','C',installment_type,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'C',
	productconfig_value = installment_type;

-- INSTALLMENT_TYPE_ACTIVATED

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'INSTALLMENT_TYPE_ACTIVATED','C',installment_type_activated,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'C',
	productconfig_value = installment_type_activated;


-- PRI_IN_ARR

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'PRI_IN_ARR','N',pri_in_arr,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = pri_in_arr;

-- INT_IN_ARR

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'INT_IN_ARR','N',int_in_arr,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = int_in_arr;

-- COM_IN_ARR

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'COM_IN_ARR','N',com_in_arr,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = com_in_arr;

-- PEN_IN_ARR

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES
	(productprodid,'PEN_IN_ARR','N',pen_in_arr,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = pen_in_arr;
 
 -- CHARGE_INT

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES 	(productprodid,'CHARGE_INT','N',charge_int,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = charge_int;
 
  -- DAYS IN A YEAR

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES 	(productprodid,'INT_DAYS','N',int_days,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = int_days;
  
  -- DAYS IN A WEEK

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES 	(productprodid,'INT_WEEKS','N',int_weeks,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = int_weeks;
 
  -- RECALCULATE INTEREST

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES (productprodid,'RECALC_INT','N',recalc_int,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = recalc_int;
  
 -- NO INTEREST

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES (productprodid,'NO_INT','N',no_int,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = no_int;
 
 -- PAY PRIORITY

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES 	(productprodid,'PAY_PRIORITY','C',pay_priority,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = pay_priority;

  -- REFINANCING PRIORITY
	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES 	(productprodid,'REF_PRIORITY','C',ref_priority,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'C',
	productconfig_value = ref_priority;
  
  -- SERVICE FEES

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES 	(productprodid,'SERVICE_FEE','N',service_fee,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = service_fee;
  
-- SERVICE FEE GL ACCOUNT

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES 	(productprodid,'SERVICE_FEE_ACC','C',service_fee_acc,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = service_fee_acc;
 
 -- SAVINGS AT LOAN PAYMENT

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES 	(productprodid,'SAV_AT_REPAY','N',sav_at_repay,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = sav_at_repay;
  
 -- SAVINGS AT LOAN REPAYMENT AMOUNT

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES 	(productprodid,'SAVING_AT_LOAN_REPAY_AMT','N',saving_at_loan_repay_amt,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = saving_at_loan_repay_amt;


 -- PULL DUES AFTER LOAN REPAYMENTS

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES 	(productprodid,'PULL_DUES_AFTER_PREPAYMENTS','N',pull_dues_after_prepayments,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = pull_dues_after_prepayments;

 
  -- LOAN_COM_FROM_SAV

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES 	(productprodid,'LOAN_COM_FROM_SAV','N',loan_com_from_sav,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = loan_com_from_sav;

 
   -- ALLOW_OVERPAYMENTS
   
	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES 	(productprodid,'ALLOW_OVERPAYMENTS','N',allow_overpayments,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = allow_overpayments;

  SELECT  1;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_update_savings_balances
DROP PROCEDURE IF EXISTS `sp_update_savings_balances`;
DELIMITER //
CREATE PROCEDURE `sp_update_savings_balances`(
	IN `products` TINYTEXT,
	IN `accounts` TINYTEXT















)
    COMMENT 'This procedure is used to update Savings Balances'
BEGIN

   DECLARE vaccount CHAR(50) DEFAULT '';
   DECLARE prevVaccount CHAR(50) DEFAULT '';
   DECLARE prevproduct_prodid CHAR(10) DEFAULT '';
   DECLARE prevmemid CHAR(50) DEFAULT '';
	DECLARE given_account CHAR(50) DEFAULT '';
   DECLARE vproduct_prodid CHAR(10) DEFAULT '';
   DECLARE vmemid CHAR(50) DEFAULT '';
   DECLARE smainquery TINYTEXT DEFAULT '';
   DECLARE updateDone INT DEFAULT 0;
     	
	     	
 DECLARE curAccounts CURSOR FOR SELECT a.savaccounts_account,a.product_prodid, COALESCE(m.members_idno,'') members_idno  FROM savaccounts a LEFT JOIN  members m ON m.groups_idno=a.client_idno WHERE  FIND_IN_SET(a.savaccounts_account,accounts)>0 AND FIND_IN_SET(a.product_prodid,products) > 0 GROUP BY a.savaccounts_account,a.product_prodid,m.members_idno;
	
   DECLARE CONTINUE HANDLER FOR NOT FOUND SET updateDone = 1; 
  		
	OPEN curAccounts;
	
	read_loop: LOOP
	
	FETCH curAccounts INTO vaccount, vproduct_prodid, vmemid;	
	
   -- check if we are done with the loop
	IF updateDone =1 THEN
      LEAVE read_loop;
   END IF;

--	IF INSTR(vaccount,'G') = 0 THEN 
 		SET  @prevbalance := 0; 
 --	END IF;
 	
 --	SET @smainquery = CONCAT(vaccount,' ',vproduct_prodid,' ',vmemid);
 		
 	-- insert into errors (err) values (@smainquery);
 	
 	UPDATE savtransactions AS w
    SET w.savtransactions_balance =  (@prevbalance:= (@prevbalance + w.savtransactions_amount))
    WHERE TRIM(w.savaccounts_account) = vaccount AND TRIM(w.product_prodid) = vproduct_prodid AND COALESCE(w.members_idno,'') =vmemid   ORDER BY w.savtransactions_tday ASC,w.last_updatedate;
    
    DELETE FROM savingsbalances WHERE savaccounts_account = vaccount AND product_prodid = vproduct_prodid;

    INSERT INTO savingsbalances (savaccounts_account,product_prodid,balance,lastupdate,clientidno) VALUES(vaccount,vproduct_prodid,@prevbalance,NOW(),vmemid);
 END LOOP read_loop;
 
 CLOSE curAccounts;

 SELECT  1;
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_update_sav_products_settings
DROP PROCEDURE IF EXISTS `sp_update_sav_products_settings`;
DELIMITER //
CREATE PROCEDURE `sp_update_sav_products_settings`(
	IN `productprodid` CHAR(50),
	IN `currencies_id` INT,
	IN `minimun_sav_bal` NUMERIC(15,5),
	IN `minimun_sav_bal_activated` TINYINT,
	IN `minimun_sav_bal_earn` NUMERIC(15,5),
	IN `minimun_sav_bal_earn_activated` TINYINT,
	IN `sav_int_rate` NUMERIC(10,5),
	IN `sav_int_period` TINYINT,
	IN `int_cal_method` CHAR(50),
	IN `int_start_date` DATE,
	IN `clientcode_is_savacc` TINYINT

,
	IN `charge_on_withdraw` DECIMAL(15.2)




,
	IN `per_int_topay` INT

,
	IN `branchcode` CHAR(50)

)
    COMMENT 'used to update savings product settings'
BEGIN

	-- VARIABLES WITH NAMES SIMILAR TO COLUMN NAMES MUST BE QUALIFIED IS AN @
	
	-- CURRENCIES_CODE

		INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) 		VALUES	(productprodid,'CURRENCIES_ID','N',currencies_id,branchcode)	
		ON DUPLICATE KEY UPDATE
		productconfig_valuetype = 'N',
		productconfig_value = currencies_id;
	
	-- MINIMUM_SAV_BAL

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES	(productprodid,'MINIMUM_SAV_BAL','N',minimun_sav_bal,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = minimun_sav_bal;

		-- MINIMUM_SAV_BAL_ACTIVATED

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) 	VALUES	(productprodid,'MINIMUM_SAV_BAL_ACTIVATED','N',minimun_sav_bal_activated,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = minimun_sav_bal_activated;


	-- MINIMUM_SAV_BAL_EARN

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES	(productprodid,'MINIMUM_SAV_BAL_EARN','N',minimun_sav_bal_earn,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = minimun_sav_bal_earn;
	
	-- MINIMUM_SAV_BAL

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES	(productprodid,'MINIMUM_SAV_BAL_EARN_ACTIVATED','N',minimun_sav_bal_earn_activated,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = minimun_sav_bal_earn_activated;


	-- SAV_INT_RATE

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) 	VALUES (productprodid,'SAV_INT_RATE','N',sav_int_rate,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = sav_int_rate;


	-- SAV_INT_PERIOD

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) 		VALUES (productprodid,'SAV_INT_PERIOD','N',sav_int_period,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = sav_int_period;

	-- CHARGE_ON_WITHDRAW

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES (productprodid,'CHARGE_ON_WITHDRAW','N',charge_on_withdraw,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = charge_on_withdraw;


-- INT_CAL_METHOD

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES (productprodid,'INT_CAL_METHOD','C',int_cal_method,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'C',
	productconfig_value = int_cal_method;

-- INT_START_DATE


	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES (productprodid,'INT_START_DATE','D',int_start_date,branchcode)
		ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'D',
	productconfig_value = int_start_date;

-- INT_START_DATE

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) VALUES (productprodid,'CLIENTCODE_IS_SAVACC','C',clientcode_is_savacc,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'C',
	productconfig_value = clientcode_is_savacc;

-- PERCENTAGE OF INCOPE TO PAY AS SAVINGS INTEREST

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,branch_code) 	VALUES (productprodid,'PER_INT_TOPAY','N',per_int_topay,branchcode)
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_value = per_int_topay;

 SELECT  1;


END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_update_schedule
DROP PROCEDURE IF EXISTS `sp_update_schedule`;
DELIMITER //
CREATE PROCEDURE `sp_update_schedule`(
	IN `branch_code` TINYTEXT,
	IN `thDate` DATE
,
	IN `cLnr` CHAR(50)







)
    COMMENT 'This SP is use to update a Schedule after refinancing'
BEGIN
			DELETE FROM xdues  WHERE loan_number=cLnr ;
			
			SELECT COUNT(loan_number) INTO @countdues  FROM dues WHERE loan_number = cLnr ;
				 		
			INSERT INTO xdues  (due_id, due_principal,due_interest,due_penalty,due_commission,due_vat,due_date,loan_number,members_idno) SELECT (@row_number:=@row_number+1)due_id,due_principal,due_interest,due_penalty,due_commission,due_vat,due_date,loan_number,members_idno FROM dues,(SELECT @row_number:=0) AS t WHERE loan_number=cLnr;				 					 				 		
										 		
 					
			SELECT COALESCE(SUM(loanpayments_interest),0) INTO @ptint  FROM loanpayments WHERE loan_number = cLnr;
					 	
		-- REMOVE OUT STANDING INTEREST FROM DUES				 	
		SET @nCount :=1;	
	
		WHILE(@nCount <= @countdues) DO
						
							SELECT due_id,due_date, due_interest INTO @dueid,@ddate, @dint FROM (
							  SELECT due_id,due_date,COALESCE(due_interest,0) due_interest, 
							         @rownum := @rownum + 1 AS rank
							    FROM xdues, 
							         (SELECT @rownum := 0) r WHERE xdues.loan_number=cLnr ORDER BY xdues.due_date ASC
							) d WHERE rank = @nCount ;
						
					
							SET @paidtint = @ptint;
							
							SET @ptint = @ptint - @dint;
						
							IF(@ptint < 0) THEN	
																											
		
								IF ABS(@dint) = ABS(@paidtint) THEN
								
									UPDATE dues SET due_interest=@dint  WHERE TRIM(loan_number)=TRIM(cLnr) AND DATE(due_date)=@ddate;
								ELSE
								
										UPDATE dues SET due_interest=(CASE WHEN @paidtint > 0 THEN @paidtint  ELSE 0 END)  WHERE TRIM(loan_number)=TRIM(cLnr) AND DATE(due_date)=@ddate;
								END IF;
						
								
							ELSE
							
								UPDATE dues SET due_interest=(CASE WHEN @paidtint <= 0 THEN 0 ELSE @dint END)  WHERE loan_number=TRIM(cLnr) AND DATE(due_date)=@ddate;
																	
							END IF;
						
							SET @nCount = @nCount + 1;	
								
		END WHILE;	
					 	
	 DELETE FROM dues  WHERE loan_number=cLnr AND due_date>thDate;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_update_td_products_settings
DROP PROCEDURE IF EXISTS `sp_update_td_products_settings`;
DELIMITER //
CREATE PROCEDURE `sp_update_td_products_settings`(
	IN `product_prodid` CHAR(50),
	IN `interest_rate` FLOAT,
	IN `interest_rate_activated` TINYINT,
	IN `currencies_id` INT



)
    COMMENT 'used to update time deposit product settings'
BEGIN

-- CURRENCY

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,productconfig_datagroup) VALUES	(product_prodid,'CURRENCIES_ID','N',currencies_id,'TD_ACCOUNTS')
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_datagroup='TD_ACCOUNTS',
	productconfig_value = currencies_id;

-- INTEREST RATE ACCOUNT

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,productconfig_datagroup) VALUES
	(product_prodid,'INTEREST_RATE','N',interest_rate,'TD_ACCOUNTS')
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_datagroup='TD_ACCOUNTS',
	productconfig_value = interest_rate;


-- INTEREST RATE ACTIVATED

	INSERT INTO productconfig (product_prodid,productconfig_paramname,productconfig_valuetype,productconfig_value,productconfig_datagroup) VALUES
	(product_prodid,'INTEREST_RATE_ACTIVATED','N',interest_rate_activated,'TD_ACCOUNTS')
	ON DUPLICATE KEY UPDATE
	productconfig_valuetype = 'N',
	productconfig_datagroup='TD_ACCOUNTS',
	productconfig_value = interest_rate_activated;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_users
DROP PROCEDURE IF EXISTS `sp_users`;
DELIMITER //
CREATE PROCEDURE `sp_users`()
BEGIN       
  select * from users;
END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_write_off_loans
DROP PROCEDURE IF EXISTS `sp_write_off_loans`;
DELIMITER //
CREATE PROCEDURE `sp_write_off_loans`(
	IN `loan_numbers` MEDIUMTEXT,
	IN `asatdate` DATE,
	IN `user_id` CHAR(50),
	IN `plang` CHAR(5)
)
    COMMENT 'This procedure is to write off loans'
BEGIN
	-- TODO: Validate situations where foreign currency do not have exchange rates
	-- TODO: terminate this procedure
	
	DECLARE vloan_number CHAR(50) DEFAULT '';
	DECLARE vproduct_prodid CHAR(50) DEFAULT '';			
	DECLARE vprinc NUMERIC(15,5);
	DECLARE vint NUMERIC(15,5);
	DECLARE vcomm NUMERIC(15,5);
	DECLARE vpen NUMERIC(15,5);
	DECLARE vfcode CHAR(10);
	DECLARE vdcode CHAR(10);
	DECLARE vloannumber CHAR(15);
	DECLARE vmembers_idno CHAR(50) DEFAULT '';
	DECLARE vclient_idno	 CHAR(50) DEFAULT '';
	DECLARE vcostcenters_code CHAR(50) DEFAULT '';
	DECLARE vamt  NUMERIC(15,5); 
   DECLARE Done1 BIT DEFAULT false;  
   DECLARE results TINYINT; 
   
   DECLARE cur_Loans CURSOR FOR SELECT loan_number,members_idno,pbalance, ibalance,penbalance,combalance,product_prodid,fund_code,donor_code,client_idno,costcenters_code FROM loans_out_table_final;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET Done1:= true;

	CALL `sp_get_outstanding_loan_balances`('', '', '', '','','', asatdate, '','', '', '', user_id, '', '','','', '', '', '1',loan_numbers,loan_numbers);
	
	SET results =1;
		
	SELECT  CASE WHEN plang='EN' THEN translations_eng 
		WHEN plang='SP' THEN translations_sp 
		WHEN plang='FR' THEN translations_fr 
		WHEN plang='SWA' THEN translations_swa  END INTO @descip
	FROM translations WHERE translations_id='1463';	
	
	
		-- ROUNDING
		SELECT c.configuration_value INTO @brounding FROM configuration c WHERE c.configuration_key='SETTTING_ROUND_TO' 	AND c.branch_code=LEFT(vloan_number,4) GROUP BY c.branch_code;
		
	OPEN cur_Loans; 
	read_loop: LOOP

	FETCH cur_Loans INTO vloan_number,vmembers_idno,vprinc,vint,vpen,vcomm,vproduct_prodid,vfcode,vdcode,vclient_idno,vcostcenters_code;

	
	
	
	-- GET TRANSACTIN CODE
	CALL `sp_generate_transactioncode`(user_id, @newtcode);
	
	SET @forexrates_id =0;	
		
	-- GET BASE CURRENCY
	SELECT c.configuration_value INTO @bcurrency FROM configuration c WHERE c.configuration_key='SETTTING_CURRENCY_ID' 	AND c.branch_code=LEFT(vloan_number,4) GROUP BY c.branch_code;


	IF  @bcurrency IS NULL THEN
	
		SET results = 2;
	
		LEAVE read_loop;	
	END IF;

-- LOCAL CURRENCY
SELECT c.productconfig_value INTO @pcurrency FROM productconfig c WHERE c.productconfig_paramname='CURRENCY_ID' AND c.product_prodid=vproduct_prodid;
		
		
-- LOANS WRITTEN OFF ACC
SELECT CASE WHEN LOCATE('G',vclient_idno)>0 THEN c.productconfig_grp WHEN LOCATE('I',vclient_idno)>0 THEN c.productconfig_ind ELSE c.productconfig_value END INTO @w_acc  FROM productconfig c WHERE c.productconfig_paramname='LOANS_WRITTEN_OFF_ACC' AND c.product_prodid=vproduct_prodid;

-- PORTFOLIO  ACC
SELECT CASE WHEN LOCATE('G',vclient_idno)>0 THEN c.productconfig_grp WHEN LOCATE('I',vclient_idno)>0 THEN c.productconfig_ind ELSE c.productconfig_value END INTO @p_acc  FROM productconfig c WHERE c.productconfig_paramname='PRINCIPAL_OUTSTANDING_ACC'  AND c.product_prodid=vproduct_prodid;

	IF  @p_acc='' OR  @w_acc='' THEN
	
		SET results = 3;
	
		LEAVE read_loop;	
	END IF;

	SET vamt	 = vprinc;
	
	-- GET EXCHANAGE RATE
	IF @pcurrency <> @bcurrency THEN
	
 		DROP TABLE IF EXISTS sp_get_exchange_rate_table1;
		
		CALL sp_get_exchange_rate(LEFT(vloannumber,4),asatdate,@pcurrency);
			
		SELECT forexrates_midrate,forexrates_id INTO @rate,@forexrates_id FROM sp_get_exchange_rate_table1;
		
		SET vamt	 = ROUND(vamt*@rate,@brounding);
			
	END IF ;
	
	-- COMPUTE LOAN BALANCES
	IF vloan_number!='' THEN
	INSERT INTO loanpayments (loan_number,loanpayments_date,members_idno,loanpayments_principal,loanpayments_interest,loanpayments_commission,loanpayments_penalty,transactioncode,loanpayments_id) VALUES (vloan_number,asatdate,vmembers_idno,-1*(vprinc),-1*(vint),-1*(vcomm),-1*(vpen),@newtcode,UUID());
	
	
	
INSERT INTO loanswrittenoff(loan_number,loan_amount,user_id,members_idno,client_idno,loanswrittenoff_date)
	VALUES(vloan_number,vprinc,user_id,vmembers_idno,vclient_idno,asatdate);

	
	-- POST WRITE OFFS	
	INSERT INTO generalledger(
					generalledger_id,			   	
					transactioncode,		
					generalledger_description,
					fund_code,
					donor_code,
					generalledger_credit,
					generalledger_voucher,
					user_id,
					generalledger_tday,
					generalledger_debit,
					chartofaccounts_accountcode,
					generalledger_updated,
					branch_code,
					trancode,				
					currencies_id,
					client_idno,
					product_prodid,
					forexrates_id,
					costcenters_code,
					generalledger_fcamount)
					VALUES(UUID(),
					@newtcode,
					CONCAT(@descip,':',vloan_number),
					vfcode,
					vdcode,
					vamt,
					'',
					user_id,	
					asatdate,
					0,
				   @p_acc,
					NOW(),
					LEFT(vloan_number,4),
					'WO000',
					@pcurrency,
					vclient_idno,
					vproduct_prodid,
					@forexrates_id,
					vcostcenters_code,
					IF(@pcurrency<>@bcurrency, vprinc,'0'));
	
	
		INSERT INTO generalledger(
					generalledger_id,			   	
					transactioncode,		
					generalledger_description,
					fund_code,
					donor_code,
					generalledger_credit,
					generalledger_voucher,
					user_id,
					generalledger_tday,
					generalledger_debit,
					chartofaccounts_accountcode,
					generalledger_updated,
					branch_code,
					trancode,				
					currencies_id,
					client_idno,
					product_prodid,
					forexrates_id,
					costcenters_code,
					generalledger_fcamount)
					VALUES(UUID(),
					@newtcode,
					CONCAT(@descip,':',vloan_number),
					vfcode,
					vdcode,
					0,
					'',
					user_id,	
					asatdate,
					vamt,
				   @w_acc,
					NOW(),
					LEFT(vloan_number,4),
					'WO000',
					@pcurrency,
					vclient_idno,
					vproduct_prodid,
					@forexrates_id,				
					vcostcenters_code,
					IF(@pcurrency<>@bcurrency, vprinc,0));	
	END IF;
			
	IF Done1 THEN	
		SET results = 1;
		LEAVE read_loop;
	END IF;
		
	END LOOP;
	
	CLOSE cur_Loans;
	
	SELECT results as id;

END//
DELIMITER ;

-- Dumping structure for procedure moneybankonline.sp_xml_update_db_new
DROP PROCEDURE IF EXISTS `sp_xml_update_db_new`;
DELIMITER //
CREATE PROCEDURE `sp_xml_update_db_new`(
	IN `xml_content_outer` LONGTEXT
)
BEGIN

	DECLARE xmltransid CHAR(100) DEFAULT '';
	DECLARE table_name CHAR(100) DEFAULT '';
	DECLARE sproid CHAR(100) DEFAULT '';
	DECLARE acc CHAR(100) DEFAULT '';
	DECLARE scid CHAR(100) DEFAULT '';
	DECLARE saction CHAR(10) DEFAULT '';
  	DECLARE lnrclose CHAR(5) DEFAULT ''; 
   DECLARE irow_index_outer INT unsigned default 1; 
   DECLARE nrow_count_outer INT unsigned; 
   DECLARE updateDone INT DEFAULT 0;
  	DECLARE nrow_count_innner INT unsigned; 
  	DECLARE irow_index_innner INT unsigned default 1; 
  	DECLARE `_rollback` BOOL DEFAULT 0;
  	
  	DECLARE thDate CHAR(20) DEFAULT '';
  	DECLARE cLnr CHAR(20) DEFAULT '';

	SET nrow_count_outer = ExtractValue(xml_content_outer,'count(/xml/table)');
	SET irow_index_outer = 1;
	  

	WHILE irow_index_outer <= nrow_count_outer do 
	BEGIN  
	
		-- get number of records in xml table record set
		SET nrow_count_innner = ExtractValue(xml_content_outer,CONCAT('COUNT(/xml/table[',irow_index_outer,']/record)'));
		
		 -- gte the name of the table
	   SET table_name = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/@tname'));
	   
	   SET saction = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/@action'));
	   
	   		
		SET  irow_index_innner = 1;		 
	   
	   WHILE irow_index_innner <= nrow_count_innner do 
		BEGIN
	
		   IF (table_name = 'loanpayments') THEN
		   
		   	SET thDate = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@date'));
				SET cLnr = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr'));
				SET lnrclose = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@close'));
				
				SET @nprinc =	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@princ'));
				SET @nint =	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@int'));
				SET @ncomm =	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@comm'));
				
				SET @npen =	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pen'));
				SET @nvat =	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@vat'));
					
				SELECT  product_prodid INTO @cprodid FROM loan WHERE loan_number=cLnr GROUP BY loan_number;
		   	
				SELECT productconfig_value INTO @recalcint FROM productconfig where productconfig_paramname='RECALC_INT' AND  product_prodid = @cprodid GROUP BY productconfig_value ;
		   
				SELECT productconfig_value INTO @pulldues FROM productconfig pc WHERE pc.productconfig_paramname='PULL_DUES_AFTER_PREPAYMENTS' AND  pc.product_prodid=@cprodid GROUP by productconfig_value;
				
				SET @nint:= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@int'));
				 
				-- CHECK SEE IF ACTION IS COMING FROM  REFINANCING THE LOAN	 
				 IF saction='RF' THEN		
				 
				 		SELECT DATE(CASE  WHEN COALESCE(MAX(p.loanpayments_date),'')='' THEN thDate ELSE p.loanpayments_date END) INTO @pdate   FROM loanpayments p WHERE P.loan_number =  cLnr;
				 		
				 		IF @pdate > thDate THEN
				 			SET thDate = @pdate;
				 		END IF; 
				 
				 		DELETE FROM xdues  WHERE loan_number=cLnr ;
			
						SELECT COUNT(loan_number) INTO @countdues  FROM dues WHERE loan_number = cLnr ;
								 		
						INSERT INTO xdues  (due_id, due_principal,due_interest,due_penalty,due_commission,due_vat,due_date,loan_number,members_idno) SELECT (@row_number:=@row_number+1)due_id,due_principal,due_interest,due_penalty,due_commission,due_vat,due_date,loan_number,members_idno FROM dues,(SELECT @row_number:=0) AS t WHERE loan_number=cLnr;				 					 				 		
														 		
				 					
							SELECT COALESCE(SUM(loanpayments_interest),0) INTO @ptint  FROM loanpayments WHERE loan_number = cLnr;
									 	
						-- REMOVE OUT STANDING INTEREST FROM DUES				 	
						SET @nCount :=1;	
					
						WHILE(@nCount <= @countdues) DO
										
											SELECT due_id,due_date, due_interest INTO @dueid,@ddate, @dint FROM (
											  SELECT due_id,due_date,COALESCE(due_interest,0) due_interest, 
											         @rownum := @rownum + 1 AS rank
											    FROM xdues, 
											         (SELECT @rownum := 0) r WHERE xdues.loan_number=cLnr ORDER BY xdues.due_date ASC
											) d WHERE rank = @nCount ;
										
									
											SET @paidtint = @ptint;
											
											SET @ptint = @ptint - @dint;
										
											IF(@ptint < 0) THEN	
																															
						
												IF ABS(@dint) = ABS(@paidtint) THEN
												
													UPDATE dues SET due_interest=@dint  WHERE TRIM(loan_number)=TRIM(cLnr) AND DATE(due_date)=@ddate;
												ELSE
												
														UPDATE dues SET due_interest=(CASE WHEN @paidtint > 0 THEN @paidtint  ELSE 0 END)  WHERE TRIM(loan_number)=TRIM(cLnr) AND DATE(due_date)=@ddate;
												END IF;
										
												
											ELSE
											
												UPDATE dues SET due_interest=(CASE WHEN @paidtint <= 0 THEN 0 ELSE @dint END)  WHERE loan_number=TRIM(cLnr) AND DATE(due_date)=@ddate;
																					
											END IF;
										
											SET @nCount = @nCount + 1;	
												
						END WHILE;	
									 	
					 DELETE FROM dues  WHERE loan_number=cLnr AND due_date>thDate;	
						
				 	 	
					 SET @nint =0;						 				 
						-- 15/09/2017	
						-- TODO: UPDATE INTEREST AT LOAN APPLICATION								 
				 END IF;
				 
				 
				  INSERT INTO loanpayments(
						loan_number,
						loanpayments_date,
						members_idno,
						loanpayments_principal,
						loanpayments_interest,
						loanpayments_commission,
						loanpayments_penalty,
						loanpayments_vat,
						transactioncode,
						paymode,
						loanpayments_voucher,
						loanpayments_overpay
						
					) VALUES(
					
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@date')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@memid')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@princ')),
						@nint,
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@comm')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pen')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@vat')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tcode')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mode')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@voucher')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ovr')));
			
			
			
				-- CHECK SEE  WE ARE TO UPDATE DUE DATES
				IF @pulldues ='1' THEN									
				--	CALL sp_pull_future_dues(cLnr,thDate,@nprinc,@nint,0,0,0,'');			
								
				--	DECLARE d_date DATE;
				--	DECLARE totpay NUMERIC(15,5)  DEFAULT 0;	
			--		DECLARE updatedues BIT DEFAULT false; 
				
					SELECT COUNT(*) INTO @countdues  FROM dues WHERE loan_number =cLnr;
					
					SELECT loan_inttype,loan_insttype,loan_tint INTO @inttype,@insttype,@intrate  FROM Loan WHERE loan_number = cLnr;
					
					SELECT SUM(disbursements_amount) INTO @damount FROM disbursements WHERE loan_number = cLnr;		
				  
				  SELECT COALESCE(SUM(p.loanpayments_principal),0),COALESCE(SUM(p.loanpayments_interest),0),COALESCE(SUM(p.loanpayments_commission),0),COALESCE(SUM(p.loanpayments_penalty),0),COALESCE(SUM(p.loanpayments_vat),0) INTO @tprinc,@tint,@tcomm,@tpen,@tvat from loanpayments p WHERE p.loan_number=cLnr; 

		-- 		GET TOTAL PAID			
		--		  SET @tprinc = @nprinc;
		--		  SET @tint =  @nint;
		--		  SET @tcomm  = @ncomm;
		--		  SET @tpen  =  @npen;
		--		  SET @tvat =  @nvat;
				  
				  SET @nInterest  = 0;
				  
				  SET @balprinc = @damount;
				 	
				  SET @totpaid = @tprinc + @tint + @tcomm + @tpen+ @tvat;
				  
				  SET @d_date = thDate;
				 
				  set @nCount :=1;
				  
				  
				  SET @updatedues = FALSE;
				  
				
				WHILE(@nCount <= @countdues) DO
						
						SELECT due_id,due_date,due_principal,due_interest,due_commission,due_penalty,due_vat INTO @dueid,@ddate,@dprinc,@dint,@dcomm,@dpen,@dvat FROM (
						  SELECT due_id,due_date,due_principal,due_interest,due_commission,due_penalty,due_vat, 
						         @rownum := @rownum + 1 AS rank
						    FROM dues, 
						         (SELECT @rownum := 0) r WHERE loan_number=cLnr ORDER BY  due_date ASC
						) d WHERE rank = @nCount;
				

									
							IF @ddate > @d_date AND  @totpaid >= @dprinc THEN							
								SET @updatedues = true;									
							ELSE
							
								IF @updatedues = FALSE THEN							
									SET  @totpaid  = @totpaid -(@dprinc + @dint + @dcomm + @dpen + @dvat);
								END IF;			
									
							END IF;
							
							SET @nInterest =0;
							
							
						
							IF @updatedues = true THEN	
							
									IF @totpaid >= @dprinc THEN
									
										UPDATE dues  SET due_date = thDate,due_interest=0 WHERE DATE(due_date) = @ddate AND loan_number=cLnr AND due_date >thDate AND TRIM(due_id)=TRIM(@dueid);														
									
									ELSE				
									
										-- CALCULATE INTEREST FOR FUTURE DUES
										-- MONTHLY
										IF @inttype = 'DD' AND @insttype='M' THEN
											SET @nInterest  =	ROUND(@balprinc * (1 / 12) * (@intrate / 100), 2);										
										END IF;
										
										
										-- GET LAST DATE OF NEXT MONTH
										SET @d_date = LAST_DAY(DATE_ADD(@d_date, INTERVAL 1 MONTH));
										
										-- UPDATE DUES RESPECTIVELY
																			
										UPDATE dues  SET due_date=@d_date,due_interest = @nInterest WHERE DATE(due_date) = @ddate AND loan_number=cLnr AND TRIM(due_id)=TRIM(@dueid);
															
									
									END IF;
													
									SET  @totpaid  = @totpaid - @dprinc;
							
							END IF;
							
							SET @balprinc = @balprinc - @dprinc;
								
							SET @nCount = @nCount + 1;								
						
				    END WHILE;	

				END IF;
				
					 IF lnrclose='1' THEN 
				 
					UPDATE dues  SET due_interest=0 WHERE DATE(due_date)>=thDate AND loan_number=cLnr AND due_status!='1';								
					
				END IF;	
				
			
			
				IF @recalcint='1' THEN
				
						UPDATE dues  SET due_interest=0 WHERE DATE(due_date) <= thDate AND loan_number=cLnr AND due_status!='1';	
						
						SELECT COUNT(due_id) INTO @countdues  FROM dues WHERE loan_number = cLnr;
						
						INSERT INTO dues(
						due_id,
			    		due_principal,
			    		due_interest,
			    		due_penalty,
			    		due_commission,
			    		due_vat,
			    		due_date,
			    		loan_number,
			    		members_idno,
						due_status) VALUES(
						(@countdues+1),		    		
			    		0,
			    		ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@int')),
			    		0,
			    		0,
			    		0,
			    		ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@date')),
			    		ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr')),
			    		ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@memid')),
					 	'1');
					 	
				END IF;
				
		ELSEIF (table_name = 'members') THEN
					
		
				IF saction ='add' THEN
				
						INSERT INTO members(
							members_idno,
							members_firstname,
							members_middlename,
							members_lastname,
							members_maritalstate,
							members_regdate,
							members_enddate,
							members_dependants,
							members_children,
							members_cat1,
							members_cat2,
							members_educ,
							members_income,
							members_lang1,
							members_lang2,
							incomecategories_id,
							members_email,
							entity_idno,
							members_no,			
							members_regstatus,
							branch_code
						)VALUES(
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mid')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@fname')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mname')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lname')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mstat')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@rdate')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@edate')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@dep')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@child')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cat1')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cat2')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@educ')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@income')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lang1')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lang2')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@incomeid')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@email')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@gid')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mno')),			
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@status')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@brcode')));
			
				ELSE
							update  members SET 	members_firstname =	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@fname')),
							members_middlename = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mname')),
							members_lastname = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lname')),
							members_maritalstate = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mstat')),
							members_regdate =  ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@rdate')),
							members_enddate = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@edate')),
							members_dependants = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@dep')),
							members_children = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@child')),
							members_cat1 = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cat1')),
							members_cat2 = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cat2')),
							members_educ = 	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@educ')),
							members_income = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@income')),
							members_lang1 = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lang1')),
							members_lang2 = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lang2')),
							incomecategories_id = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@incomeid')),
							members_email = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@email')),													
							members_regstatus = 	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@status')),
							branch_code = 	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@brcode'))							  
							WHERE members_idno = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mid'));
			
			END IF;	
				
		
		ELSEIF (table_name = 'entity') THEN
				
			
			if saction ='add' THEN
					INSERT INTO entity(
					branch_code,
					entity_regdate,
					entity_name,
					entity_postad,
					entity_city,
					entity_addressphysical,
					entity_tel1,
					entity_tel2,				
					entity_idno,
					bussinesssector_code,
					entity_regstatus,				
					entity_regcode,
					areacode_code,
					entity_type
					
				)VALUES(
						
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@brcode')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@rdate')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ename')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@postad')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@city')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pad')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tel1')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tel2')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@gid')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@bcode')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@status')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@regcode')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@acode')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ctype'))
					
					);
			END IF;
				
		if saction ='update' THEN
			UPDATE entity SET
			branch_code = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@brcode')),
			entity_regdate = CASE WHEN ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@rdate'))='' THEN entity_regdate ELSE ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@rdate')) END ,
			entity_name = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ename')),
			entity_postad = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@postad')),
			entity_city = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@city')),
			entity_addressphysical = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pad')),
			entity_tel1 = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tel1')),
			entity_tel2 = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tel2')),		
			bussinesssector_code = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@bcode')),
			entity_regstatus = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@status')),
			areacode_code = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@acode')),
			entity_type = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ctype'))
		WHERE entity_idno= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@gid'));
		
		END IF;
				
	ELSEIF (table_name = 'memberloans') THEN
				
			
				IF(saction='add') THEN
				
					INSERT INTO memberloans(
						loan_number,
						members_idno,
						client_idno,
						memberloans_amount,
						memberloans_intamount		
					) VALUES(
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mid')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lamt')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@intamt')));
				
				ELSE	
				
					UPDATE memberloans SET 	client_idno = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid')),
					memberloans_amount = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lamt')),
					memberloans_intamount = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@intamt'))
					WHERE loan_number = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr'))
					AND members_idno = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mid'));
				
				END IF;				
					
					
		
		ELSEIF (table_name = 'productconfig') THEN				
		
				DELETE FROM productconfig WHERE product_prodid=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@prodid')) AND productconfig_paramname=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@name'));
				
				INSERT INTO productconfig(
				product_prodid,
				productconfig_paramname,
				productconfig_valuetype,
				productconfig_value,
				productconfig_ind,
				productconfig_grp,
				productconfig_datagroup,
				productconfig_description,
				branch_code)
				VALUES(
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@prodid')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@name')),
				'C',
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@gacc')),				
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@indacc')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@grpacc')),	
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@dgrp')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@desc')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@brcode')));
				
		ELSEIF (table_name = 'timedeposit') THEN
		
		--	SET @dstatus = 	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@status'));
		--	SET @ctcode = 	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@otcode'));
					
			INSERT INTO timedeposit(
				timedeposit_number,
				client_idno,
				product_prodid,
				branch_code,
				members_idno) VALUES(
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tdno')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@prodid')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@brcode')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@memid')));	
				
		ELSEIF (table_name = 'product') THEN
		
			SET @daction = 	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@action'));
			
			IF @daction='add' THEN
				
				SELECT count(product_prodid) INTO @nexists FROM product WHERE product_prodid=	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pcode'));
				
				IF @nexists =0 THEN 
					INSERT INTO product(	
						product_name,
						product_prodid
						)VALUES(
					   ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pname')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pcode'))) ;
				END IF;
			ELSE
			
				UPDATE product SET product_name = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pname')),	product_prodid = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pcode')) WHERE product_prodid=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@opcode'));
			
			END IF;
			
		ELSEIF (table_name = 'devicemessage') THEN
				SET @action_ = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@action'));
				
				IF(@action_='add') THEN			
						
					INSERT INTO sentmessages(date,message,client_idno,tel) 
					SELECT NOW(),devicemessage_msg,clientid,tel FROM devicemessage WHERE devicemessage_id=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@dmid'));
					
						UPDATE devicemessage SET devicemessage_status ='S' WHERE devicemessage_id= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@dmid'));
															
				--	DELETE FROM devicemessage WHERE devicemessage_id = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@dmid'));						
						
				END IF;		
			
	   ELSEIF (table_name = 'timedeposittrans') THEN	
	   
				SET @dstatus = 	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@status'));
				SET @otcode = 	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@otcode'));
				
				
				
				IF (@dstatus ='TR' OR @dstatus ='TD' OR @dstatus ='TM')	THEN	
					
					IF @dstatus ='TM' THEN
					 --	DELETE FROM timedeposittrans WHERE transactioncode = @otcode;
												
						 -- REVERSE TRANSACTION							
						 SET @dstatus = 'TD';
						 
					END IF;
								
					-- WHERE TD IS PREMATURE UPDATE INTEREST AMOUNT TOO				
					INSERT INTO timedeposittrans(	
							timedeposit_number,
							timedeposit_date,
							transactioncode,
							timedeposit_status,
							timedeposit_interestrate,							
							timedeposit_intamt,
							timedeposit_amount,
							cheqs_no,
							timedeposit_voucher,
							timedeposit_period,
							timedeposit_instype,
							timedeposit_freq,
							timedeposit_matval,
							timedeposit_matdate,					
							timedeposit_intcapital,
							members_idno) VALUES(							
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tdno')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@date')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tcode')),
							@dstatus,
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@int')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@intamt')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@amt')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cheqno')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@voucher')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@period')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@instype')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@freq')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@matval')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@matdate')),						
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@intcap')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@memid')));	
					
				END IF;
				
				IF (@dstatus ='TW')	THEN
				
					SET @tcode = 	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tcode'));	
						UPDATE timedeposittrans 
						SET timedeposit_status ='TW',
						timedeposit_intamt = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@intamt')),
						timedeposit_matdate = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@date')),
						timedeposit_withdrawdate = NOW()
					WHERE transactioncode = @tcode;
				
				ELSEIF (@dstatus ='TR') THEN
					
													
						SET @tcode = 	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tcode'));
									
						SET @otcode = 	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@otcode'));
											
						UPDATE timedeposittrans 					
						SET timedeposit_status ='TW',
							timedeposit_intamt = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ointamt')),
							timedeposit_matdate = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@date')),
							timedeposit_matval = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@omatval')),
							timedeposit_intcapital = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@intcap')),
							timedeposit_withdrawdate = NOW()
						WHERE transactioncode = @otcode;
														
			
				END IF;
				
		ELSEIF (table_name = 'memberloans') THEN 
			
				INSERT INTO memberloans(
				loan_number,
				members_idno,
				client_idno,
				branch_code,
				deletedtrans_processed,
				deletedtrans_datecreated) VALUES(
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@module')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@uid')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tcode')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@brcode')),
				'N',
				NOW());
				
		ELSEIF (table_name = 'deletedtrans') THEN
		
			INSERT INTO deletedtrans(
				deletedtrans_module,
				user_id,
				transactioncode,
				branch_code,
				deletedtrans_processed,
				deletedtrans_datecreated) VALUES(
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@module')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@uid')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tcode')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@brcode')),
				'N',
				NOW());				
		
		ELSEIF (table_name = 'chartofaccounts') THEN
		
				 SET saction = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@glacc'));
				
				 IF saction='edit' THEN
				 		DELETE FROM chartofaccounts WHERE chartofaccounts_id=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@id'));
				 END IF;	
		
				 INSERT INTO chartofaccounts(
					chartofaccounts_accountcode,
					chartofaccounts_name,
					chartofaccounts_level,
					chartofaccounts_parent,
					chartofaccounts_header,
					chartofaccounts_type,
					chartofaccounts_description,
					chartofaccounts_groupcode,
					chartofaccounts_bitem,
					chartofaccounts_tgroup,
					currencies_id,
					chartofaccounts_revalue			
					
				) VALUES(
				
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@glacc')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@name')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@level')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@parent')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@header')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tgrp')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@desc')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@gcode')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@bitem')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tgrp')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@curid')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@rval')));
				
		
		ELSEIF (table_name = 'users') THEN
			
			SET saction = 	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@action'));
			
			IF (saction = 'update') THEN
								
					UPDATE users SET
						user_isactive = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@active')),
						user_firstname= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@fname')),
						user_lastname= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lname')),
						user_middlename= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mname')),
						user_password= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pwd')),
						user_email_address= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@email')),
						user_lang= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lang')),
						user_accesscode= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@acode')),
						user_isactive= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@active')),
						user_passexp =user_accesscode= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@exp')) 				
						WHERE user_id = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@uid'));
						
				  		
				ELSE
		
					INSERT INTO users(
						user_firstname,
						user_lastname,
						user_middlename,
						user_username,
						user_password,
						user_email_address,
						datecreated,
						user_lasttcode,
						user_isactive,
						user_lang,
						user_usercode,
						user_accesscode,
						user_passexp
						) VALUES(
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@fname')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lname')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mname')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@uname')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pwd')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@email')),
							NOW(),
							0,
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@active')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lang')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ucode')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@acode')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@exp')));
				END IF;	
						
		ELSEIF (table_name = 'roles') THEN
	
				SET @lang = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lang'));
				SET @theaction = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@action'));
				
				
				IF @theaction='update' THEN
					IF @lang ='EN' THEN				
						UPDATE roles SET roles_name_eng=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@role')) WHERE roles_id=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@roleid'));
					ELSEIF @lang ='FR' THEN
						UPDATE roles SET  roles_name_fr=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@role')) WHERE roles_id=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@roleid'));
					ELSEIF @lang ='SP'  THEN
						UPDATE roles SET  roles_name_sp=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@role')) WHERE roles_id=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@roleid'));
					ELSEIF @lang ='SWA' THEN
						UPDATE roles SET roles_name_swa=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@role')) WHERE roles_id=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@roleid'));
					ELSEIF @lang ='JA' THEN
						UPDATE roles SET  roles_name_ja=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@role')) WHERE roles_id=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@roleid'));			
					END IF;

				ELSE
					IF @lang ='EN' THEN				
						INSERT INTO roles (roles_name_eng) VALUES(ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@role')));
					ELSEIF @lang ='FR' THEN
						INSERT INTO roles (roles_name_fr) VALUES(ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@role')));
					ELSEIF @lang ='SP'  THEN
						INSERT INTO roles (roles_name_sp) VALUES(ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@role')));
					ELSEIF @lang ='SWA' THEN
						INSERT INTO roles (roles_name_swa) VALUES(ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@role')));
					ELSEIF @lang ='JA' THEN
						INSERT INTO roles (roles_name_ja) VALUES(ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@role')));			
					END IF;
				END IF;
		ELSEIF (table_name = 'rolecashaccounts') THEN
				IF irow_index_innner = 1 THEN
					DELETE FROM rolecashaccounts WHERE roles_id=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@roleid'));
				END IF;
				
				INSERT INTO rolecashaccounts(
				roles_id,
				chartofaccounts_accountcode) VALUES(ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@roleid')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@glacc')));
				
		ELSEIF (table_name = 'rolesmodules') THEN			
				IF irow_index_innner = 1 THEN
					DELETE FROM rolesmodules WHERE roles_id=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@roleid'));
				END IF;
				
				INSERT INTO rolesmodules(
				roles_id,
				modules_id) VALUES(ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@roleid')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mid')));
				
		ELSEIF (table_name = 'usersroles') THEN
		
			IF irow_index_innner = 1 THEN
				DELETE FROM usersroles WHERE user_id=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@uid'));
			END IF;
			
			INSERT INTO usersroles(
				roles_id,
				user_id) VALUES(ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@roleid')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@uid')));
				
		ELSEIF (table_name = 'userbranches') THEN
		
			DELETE FROM userbranches WHERE user_usercode= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ucode'));
					
			INSERT INTO userbranches(
				user_accesscode,
				user_usercode,
				branch_code,
				licence_build,
				parentbranch
				) VALUES(
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@acode')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ucode')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@brcode')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lic')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pbrcode')));
				
				
		ELSEIF (table_name = 'loanfee') THEN
		
				 
				 INSERT INTO loanfee(
				loanfee_date,
				loan_number,
				client_idno,
				transactioncode,
				loanfee_amount,
				members_idno,		
				loanfee_type
				) VALUES(
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@date')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tcode')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@amt')),
				ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@memid')),
				'COMM');
				
					
		ELSEIF (table_name = 'cheqs') THEN
		
			SELECT count(cheqs_no) INTO @nexists FROM cheqs WHERE cheqs_no=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cqnr'));
				
				
			IF (@nexists = 0) THEN 
			
				INSERT INTO cheqs(
					cheqs_no,
					bankaccounts_accno,
					bankbranches_id,
					cheqs_status,
					cheqs_datecleared,
					cheqs_amount,			
					cheqs_type,
					transactioncode,
					client_idno) VALUES(
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cheqno')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@accno')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@bid')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cqst')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@date')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@amt')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cqtype')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tcode')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid')));
		ELSE
				UPDATE cheqs SET cheqs_amount = (cheqs_amount + ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@amt'))) WHERE cheqs_no=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cqnr'));
		
		END IF;
		
		    	
		ELSEIF (table_name = 'savtransactions') THEN 
		
					   
		   
		   	SET scid = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid'));
		   	SET sproid = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@prodid'));
		   	SET @nAmount = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@amt'));
		 		SET @acc = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@acc'));
		 		
		   	 SET @ddate 	= CONCAT(TRIM(ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@date'))),' ',DATE_FORMAT(NOW(),'%H:%i:%s'));
		   	
				 SET @nsavBal =0;
				 SELECT COALESCE(sum(savtransactions_amount),0) into @nsavBal FROM savtransactions WHERE savaccounts_account=@acc  AND savtransactions_tday<=@ddate;
				
				 SET @nsavBal = @nsavBal + @nAmount;
				--	INSERT INTO errors(err)values(table_name);
							  
				 INSERT INTO savtransactions(
					savtransactions_tday,
					product_prodid,
					transactioncode,
					savaccounts_account,
					savtransactions_voucher,
					savtransactions_amount,
					cheqs_no,
					members_idno,
					transactiontypes_code,
					paymode,
					user_id,
					savtransactions_balance
				) VALUES(
				
				@ddate,
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@prodid')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tcode')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@acc')),	
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@voucher')),
					@nAmount,
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cheqno')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@memid')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ttype')),						 		 
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mode')),	
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@uid')),
					@nsavBal);
			
			ELSEIF (table_name = 'loanstatuslog') THEN
					
					SET @loanstatus ='';
					
					SELECT loan_number,loan_amount,loan_status INTO @Lnr,@loan_amount,@loanstatus FROM loanstatuslog WHERE loan_number = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr')) AND (loan_status='RF' OR loan_status='RFAP');

								
					IF (@loanstatus='RF') THEN -- APPROVAL
					
						UPDATE loanstatuslog SET loan_status='RFAP' ,loan_amount = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@amt')) WHERE loan_number=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr')) AND loan_status='RF';
											UPDATE refinanced SET refinanced_status ='RFAP' WHERE  loan_number = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr'))  AND refinanced_status='RF';
					ELSEIF(@loanstatus='RFAP') THEN -- DISBURSEMENT
					
						UPDATE loanstatuslog SET loan_status='RFLD',loan_amount = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@amt')) WHERE loan_number=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr')) AND loan_status='RFAP';
							UPDATE refinanced SET refinanced_status ='RFLD' WHERE  loan_number = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr'))  AND refinanced_status='RFAP';
							
					ELSE -- LOAN APPLICATION
					
						INSERT INTO loanstatuslog(
							loan_number,
							loan_status,
							loan_datecreated,
							loan_amount,
							user_id) VALUES(
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lstatus')),
							NOW(),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@amt')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@uid')));
						
					END IF;
					
		ELSEIF (table_name = 'dues') THEN
		
				 IF (saction='DELETE') THEN
				 
				 	DELETE FROM dues WHERE loan_number = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr'));
				 	
				 	
				 	SET saction ='';
			   
			   END IF;
	   
				
		    	INSERT INTO dues(
		    		due_id,
		    		due_principal,
		    		due_interest,
		    		due_penalty,
		    		due_commission,
		    		due_vat,
		    		due_date,
		    		loan_number,
		    		members_idno) VALUES(
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@id')),	    		
		    		ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@princ')),
		    		ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@int')),
		    		ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pen')),
		    		ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@comm')),
		    		ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@vat')),
		    		ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@date')),
		    		ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr')),
		    		ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@memid')));  		
		    		
		   ELSEIF (table_name = 'loan') THEN
		   
						INSERT INTO loan(
						loan_number,
						client_idno,
						loan_amount,
						fund_code,
						loan_tint,
						loan_intamount,
						user_id,
						loan_startdate,
						loan_grace,
						loan_noofinst,
						loan_exp,
						loan_status,
						loan_firstinst,
						loan_udf1,
						loan_udf2,
						loan_udf3,
						loan_adate,
						loan_inttype,
						loan_insttype,
						loan_intdays,
						loan_alsograce,
						loan_intdeductedatdisb,
						product_prodid,
						donor_code,
						branch_code,
						members_idno,
						loan_intcgrace,
						loan_intfirst,
						loan_lastinstpp,
						loan_insintgrac,
						loan_comm,
						loan_freezedate,
						loan_expdisb,
						loan_gracecompd,
						loan_intindays,
						loanpurpose_id,
						loan_inupfront) VALUES(
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lamt')),				 				  	  ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@fcode')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@int')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@intamt')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@uid')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@sdate')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@grace')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@noofinst')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lexp')),
						'',
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@firstinst')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@udf1')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@udf2')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@udf3')),
						NOW(),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@inttype')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@insttype')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@intdays')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@alsograce')),					
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@intdeductedatdisb')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@prodid')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@dcode')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@brcode')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@memid')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@intcgrace')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@intfirst')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lastinstpp')),						
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@intcgrace')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@comm')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@freezed')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@expdisb')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@gracecompd')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@intindays')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pid')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@inupfront')));
						
			ELSEIF (table_name = 'refinanced') THEN
			
					SET @c_Lnr = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr'));
					INSERT INTO refinanced(
						loan_number,
						refinanced_startdate,
						refinanced_originalamt,
						refinanced_addedamt,
						loan_noofinst,
						user_id,
						refinanced_status) VALUES(
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@date')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@amt')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@add')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@inst')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@uid')),'RF');					
						
						UPDATE loan SET loan_intamount=(SELECT sum(d.due_interest) FROM dues d where d.loan_number=@c_Lnr) where loan_number=@c_Lnr;
						
			ELSEIF (table_name = 'savaccounts') THEN
				
				INSERT INTO savaccounts(
					client_idno,
					product_prodid,
					savaccounts_account,
					savaccounts_opendate
					) VALUES(
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@prodid')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@acc')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@odate')));
			
			ELSEIF (table_name = 'document') THEN
			
				
					INSERT INTO document (
						document_docexpiry,	
						documenttypes_id,
						clientcode,
						document_serial,
						document_issuedate,
						document_priority,
						document_issueauthority,
						document_url,
						document_id
					)VALUES(
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@docexp')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@did')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@serial')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@idate')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pri')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@auth')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@url')),uuid()
					) ON DUPLICATE KEY UPDATE
						document_docexpiry=	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@docexp')),
						documenttypes_id =	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@did')),
						document_serial=	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@serial')),
						document_issuedate =	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@idate')),
						document_priority =	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pri')),
						document_issueauthority =	ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@auth')),
						document_url = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@url'));

			ELSEIF (table_name = 'clients') THEN
					
				
				
				if saction ='add' THEN
							
						INSERT INTO clients (
							client_type,
							branch_code,
							client_regdate,
							client_surname,
							client_firstname,
							client_middlename,
							client_idno,
							client_postad,
							client_gender,
							client_city,
							client_addressphysical,
							areacode_code,
							client_maritalstate,
							client_tel1,
							client_tel2,
							client_emailad,
							client_enddate,
							costcenters_code,
							client_cat1,
							client_cat2,
							bussinesssector_code,
							client_regstatus,
							client_kinname,
							client_occupation,
							client_bday)
					VALUES(
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ctype')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@brcode')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@rdate')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@sname')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@fname')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mname')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pad')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@gender')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@city')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pad')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@acode')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mstate')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tel1')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tel2')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@email')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@edate')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ccode')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cat1')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cat2')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@bcode')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@status')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@kin')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ocp')),
							ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@bday')));	
							
				ELSE												
							
						UPDATE clients 
							SET client_type = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ctype')),
							branch_code = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@brcode')),
							client_regdate = CASE WHEN ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@rdate'))='' THEN client_regdate ELSE ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@rdate')) END,
							client_surname = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@sname')),
							client_firstname = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@fname')),
							client_middlename = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mname')),											 client_postad = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pad')),
							client_gender = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@gender')),
							client_city = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@city')),
							client_addressphysical = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@pad')),
							areacode_code = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@acode')),
							client_maritalstate = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mstate')),
							client_tel1 = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tel1')),
							client_tel2 = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tel2')),
							client_emailad = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@email')),
							client_enddate = CASE WHEN ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@edate'))='' THEN client_enddate ELSE ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@edate')) END,
							costcenters_code = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ccode')),
							client_cat1 = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cat1')),
							client_cat2 = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cat2')),
							bussinesssector_code = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@bcode')),
							client_regstatus = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@status')),
							client_kinname = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@kin')),
							client_occupation = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@ocp')),
							client_bday= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@bday'))
						WHERE  client_idno = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid'));
				
				END IF;
				
				
				
											
			ELSEIF (table_name = 'clientsave') THEN
				
				SET @action_ = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@action'));
				
			
					DELETE FROM loanfee WHERE savaccounts_account=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@acc')) AND client_idno=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid')) AND product_prodid=ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@prodid'));
					
					INSERT INTO clientsave(
						client_idno,
						savaccounts_account,
						product_prodid,
						members_idno,
						clientsave_amount,
						clientsave_freq,
						lproduct_prodid) VALUES(
						
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@acc')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@prodid')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@memid')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@amt')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@freq')),
						ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lprodid')));
						
			ELSEIF (table_name = 'disbursements') THEN
				
			
				SET @nLnr = ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@lnr'));
					
				INSERT INTO disbursements(
					paymode,
					transactioncode,
					loan_number,
					disbursements_date,
					disbursements_vat,
					disbursements_voucher,
					disbursements_stationery,
					disbursements_amount,
					disbursements_commission,
					cheqs_no,
					cash,
					cycle,
					members_idno,
					disbursements_type,
					user_id) VALUES(
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@mode')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tcode')),
					@nLnr,
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@date')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@vat')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@voc')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@stat')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@amt')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@comm')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cheqno')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cash')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cycle')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@memid')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@dtype')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@uid')));
							
					SELECT client_idno INTO @clientidno  FROM loan where loan_number=@nLnr GROUP BY client_idno;
					
			SET @namt	= ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@amt'));	
			
			INSERT INTO loansoutstanding(
					loan_number,
					client_idno,
					members_idno,
					due_principal)
					SELECT * FROM(SELECT
					@nLnr,
					@clientidno,
					'',
					@namt)AS tmp WHERE NOT EXISTS(SELECT loan_number FROM loansoutstanding WHERE loan_number=@nLnr);
				
				
			IF(saction='RF') THEN
				-- DELETE XDUES
			
				DELETE FROM xdues WHERE loan_number=@nLnr;
				
				-- INSERT DUES
			
				
				-- UPDATE DUES		
			ELSE
					
					DELETE FROM loansoutstanding WHERE loan_number=@nLnr;
			
					INSERT INTO loansoutstanding (
					loan_number, members_idno, due_principal,due_interest,due_commission,due_penalty,due_vat) VALUES(@nLnr, ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@amt')), '0','0','0','0','0'); 
			END IF;
			
			
						
			ELSEIF (table_name = 'generalledger') THEN			
												
		   	
			   INSERT INTO generalledger(
			   	generalledger_id,
					transactioncode,		
					generalledger_description,
					fund_code,
					donor_code,
					generalledger_credit,
					generalledger_voucher,
					user_id,
					generalledger_tday,
					generalledger_debit,
					chartofaccounts_accountcode,
					generalledger_updated,
					branch_code,
					trancode,
					generalledger_locked,
					currencies_id,
					client_idno,
					product_prodid,
					forexrates_id,
					costcenters_code
				
						
				) VALUES(
					UUID(),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@tcode')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@desc')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@fcode')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@dcode')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cr')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@voucher')),					  		 						  			ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@uid')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@date')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@dr')),				
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@glacc')),
					NOW(),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@brcode')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@trcode')),
					'N',
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@curid')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@cid')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@prodid')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@fxid')),
					ExtractValue(xml_content_outer,CONCAT('/xml/table[',irow_index_outer,']/record[',irow_index_innner,']/@costc')));		   	
				
			   
		  	END IF;
		  	
		  	SET irow_index_innner = irow_index_innner + 1;
		  	
		END; 
		
		END while; 	
	 
		SET irow_index_outer = irow_index_outer + 1;
	   
  END;	
  
  END while; 
  
 	
END//
DELIMITER ;

-- Dumping structure for table moneybankonline.taxes
DROP TABLE IF EXISTS `taxes`;
CREATE TABLE IF NOT EXISTS `taxes` (
  `taxes_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `taxes_name` char(50) NOT NULL,
  `chartofaccounts_accountcode` char(20) NOT NULL,
  PRIMARY KEY (`taxes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.taxrates
DROP TABLE IF EXISTS `taxrates`;
CREATE TABLE IF NOT EXISTS `taxrates` (
  `taxrates_id` int(10) NOT NULL AUTO_INCREMENT,
  `taxes_id` tinyint(10) DEFAULT NULL,
  `taxrates_from` decimal(10,0) DEFAULT NULL,
  `taxrates_to` decimal(10,0) DEFAULT NULL,
  `taxrates_rate` decimal(10,5) DEFAULT NULL,
  `taxrates_datecreated` date DEFAULT NULL,
  `taxrates_updated` date DEFAULT NULL,
  `taxrates_activated` char(1) DEFAULT 'N',
  PRIMARY KEY (`taxrates_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.timedeposit
DROP TABLE IF EXISTS `timedeposit`;
CREATE TABLE IF NOT EXISTS `timedeposit` (
  `timedeposit_number` char(45) NOT NULL,
  `client_idno` char(12) NOT NULL,
  `members_idno` char(12) NOT NULL,
  `product_prodid` char(5) NOT NULL,
  `branch_code` char(50) NOT NULL,
  PRIMARY KEY (`timedeposit_number`,`client_idno`,`members_idno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.timedeposittrans
DROP TABLE IF EXISTS `timedeposittrans`;
CREATE TABLE IF NOT EXISTS `timedeposittrans` (
  `timedeposit_number` char(45) NOT NULL,
  `timedeposit_date` datetime NOT NULL,
  `transactioncode` char(45) NOT NULL,
  `members_idno` char(12) NOT NULL,
  `timedeposit_status` char(2) NOT NULL,
  `timedeposit_interestrate` float(4,2) unsigned NOT NULL,
  `timedeposit_intamt` decimal(10,5) unsigned NOT NULL,
  `timedeposit_amount` decimal(15,5) unsigned NOT NULL,
  `cheqs_no` char(20) DEFAULT NULL,
  `timedeposit_voucher` char(20) NOT NULL,
  `timedeposit_period` smallint(6) unsigned NOT NULL,
  `timedeposit_instype` char(3) NOT NULL,
  `timedeposit_freq` char(5) DEFAULT NULL,
  `timedeposit_matval` decimal(15,5) unsigned NOT NULL,
  `timedeposit_matdate` date NOT NULL,
  `timedeposit_withdrawdate` date DEFAULT NULL,
  `timedeposit_intcapital` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`timedeposit_number`,`timedeposit_date`,`transactioncode`,`members_idno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.trancodes
DROP TABLE IF EXISTS `trancodes`;
CREATE TABLE IF NOT EXISTS `trancodes` (
  `trancodes_code` char(45) DEFAULT NULL,
  `trancodes_description` char(45) NOT NULL DEFAULT '',
  `trancodes_fr` char(45) NOT NULL DEFAULT '',
  `trancodes_swa` char(45) DEFAULT '',
  `trancodes_ru` char(45) DEFAULT '',
  `trancodes_en` char(45) NOT NULL DEFAULT '',
  `trancodes_lug` char(45) NOT NULL DEFAULT '',
  `trancodes_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `trancodes_sp` char(45) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trancodes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='The detail of this table are used to identify transactions in the general Ledger';

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.transactiontypes
DROP TABLE IF EXISTS `transactiontypes`;
CREATE TABLE IF NOT EXISTS `transactiontypes` (
  `transactiontypes_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transactiontypes_code` char(3) NOT NULL DEFAULT '',
  `transactiontypes_name` char(45) NOT NULL DEFAULT '',
  `transactiontypes_display` char(1) NOT NULL DEFAULT '',
  PRIMARY KEY (`transactiontypes_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.transfercharges
DROP TABLE IF EXISTS `transfercharges`;
CREATE TABLE IF NOT EXISTS `transfercharges` (
  `transfercharges_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `charges_code` char(50) DEFAULT NULL,
  `branch_code` varchar(50) DEFAULT NULL,
  `transfercharges_amount` decimal(15,5) unsigned DEFAULT 0.00000,
  `transfers_code` tinytext DEFAULT NULL,
  `transfercharges_vat` decimal(15,5) unsigned DEFAULT 0.00000,
  `transfercharges_pervat` decimal(15,5) unsigned DEFAULT 0.00000,
  PRIMARY KEY (`transfercharges_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.transfercodes
DROP TABLE IF EXISTS `transfercodes`;
CREATE TABLE IF NOT EXISTS `transfercodes` (
  `transfercodes_id` int(10) NOT NULL AUTO_INCREMENT,
  `transfers_code` char(100) DEFAULT NULL,
  `transfercodes_datecreated` datetime DEFAULT NULL,
  `transfercodes_expdate` datetime DEFAULT NULL,
  `transfercodes_lastupdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `operatorbranches_code` tinytext DEFAULT NULL,
  `transfercodes_status` char(1) DEFAULT 'N',
  PRIMARY KEY (`transfercodes_id`),
  UNIQUE KEY `transfers_code` (`transfers_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.transfers
DROP TABLE IF EXISTS `transfers`;
CREATE TABLE IF NOT EXISTS `transfers` (
  `transfers_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transfers_isclient` char(1) DEFAULT 'N',
  `transfers_amount` decimal(15,5) unsigned NOT NULL DEFAULT 0.00000,
  `transfers_vat` decimal(15,5) unsigned DEFAULT 0.00000,
  `transfers_total` decimal(15,5) unsigned NOT NULL DEFAULT 0.00000,
  `operatorcode` char(50) NOT NULL DEFAULT '0',
  `transfers_amountoreceive` decimal(15,5) unsigned DEFAULT 0.00000,
  `transfers_firstname` char(100) DEFAULT '0',
  `transfers_middlename` char(100) DEFAULT '0',
  `transfers_lastname` char(100) DEFAULT '0',
  `transfers_telephone` char(50) DEFAULT NULL,
  `transfers_address` char(50) DEFAULT '0',
  `documenttypes_id` tinyint(4) unsigned DEFAULT 0,
  `transfers_docnum` char(50) DEFAULT '0',
  `transfers_docissuedate` date DEFAULT NULL,
  `transfers_firstname_rec` char(100) NOT NULL,
  `transfers_docexpdate` date DEFAULT NULL,
  `transfers_middlename_rec` char(100) DEFAULT NULL,
  `transfers_telephone_rec` char(50) DEFAULT NULL,
  `transfers_lastname_rec` char(100) NOT NULL,
  `transfers_address_rec` tinytext DEFAULT NULL,
  `transfers_qtn` tinytext NOT NULL,
  `transfers_ans` tinytext NOT NULL,
  `transfers_code` tinytext DEFAULT NULL,
  `branch_code` varchar(50) DEFAULT NULL,
  `currencies_code` char(10) DEFAULT NULL,
  `countries_iso_code_3` char(10) DEFAULT NULL,
  `countries_iso_code_3_rec` char(10) DEFAULT NULL,
  `transfers_datecreated` char(50) DEFAULT NULL,
  `country_origin` char(50) DEFAULT NULL,
  `user_usercode` char(50) DEFAULT NULL,
  PRIMARY KEY (`transfers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.translations
DROP TABLE IF EXISTS `translations`;
CREATE TABLE IF NOT EXISTS `translations` (
  `translations_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `translations_eng` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `translations_fr` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `translations_sp` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `translations_swa` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `translations_lug` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `translations_runya` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `translations_kinya` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `translations_ja` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`translations_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1749 DEFAULT CHARSET=sjis COLLATE=sjis_bin;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.userbranches
DROP TABLE IF EXISTS `userbranches`;
CREATE TABLE IF NOT EXISTS `userbranches` (
  `userbranches_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_accesscode` tinytext DEFAULT NULL,
  `user_usercode` char(50) DEFAULT NULL,
  `branch_code` char(50) DEFAULT NULL,
  `licence_build` char(50) DEFAULT NULL,
  `parentbranch` char(50) NOT NULL DEFAULT '''N''',
  PRIMARY KEY (`userbranches_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.usergroups
DROP TABLE IF EXISTS `usergroups`;
CREATE TABLE IF NOT EXISTS `usergroups` (
  `usergroups_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0,
  `groups_id` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`usergroups_id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_firstname` char(45) NOT NULL DEFAULT '',
  `user_lastname` char(45) NOT NULL DEFAULT '',
  `user_middlename` char(45) NOT NULL DEFAULT '',
  `user_username` char(45) NOT NULL DEFAULT '',
  `user_password` varchar(200) NOT NULL DEFAULT '',
  `user_email_address` char(90) DEFAULT NULL,
  `datecreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_lasttcode` bigint(20) DEFAULT 0,
  `last_login` date DEFAULT NULL,
  `user_isactive` char(1) NOT NULL DEFAULT '',
  `employees_id` int(10) unsigned DEFAULT NULL,
  `user_lang` char(50) DEFAULT NULL,
  `user_usercode` char(50) DEFAULT NULL,
  `user_accesscode` tinytext DEFAULT NULL,
  `user_passexp` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_username` (`user_username`),
  KEY `user_usercode` (`user_usercode`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.usersroles
DROP TABLE IF EXISTS `usersroles`;
CREATE TABLE IF NOT EXISTS `usersroles` (
  `roles_id` int(10) unsigned NOT NULL DEFAULT 0,
  `user_id` char(45) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.vat
DROP TABLE IF EXISTS `vat`;
CREATE TABLE IF NOT EXISTS `vat` (
  `vat_id` tinyint(10) NOT NULL AUTO_INCREMENT,
  `vat_itemcode` char(50) DEFAULT NULL,
  `vat_datecreated` date DEFAULT NULL,
  `vat_percentage` decimal(10,5) DEFAULT NULL,
  PRIMARY KEY (`vat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for view moneybankonline.v_clients
DROP VIEW IF EXISTS `v_clients`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_clients` (
	`client_type` CHAR(2) NOT NULL COLLATE 'utf8mb4_general_ci',
	`branch_code` CHAR(50) NULL COLLATE 'utf8mb4_general_ci',
	`client_regdate` DATE NULL,
	`client_surname` CHAR(100) NULL COLLATE 'utf8mb4_general_ci',
	`client_firstname` VARCHAR(100) NULL COLLATE 'utf8mb4_general_ci',
	`client_middlename` VARCHAR(100) NULL COLLATE 'utf8mb4_general_ci',
	`client_idno` CHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`client_gender` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`areacode_code` CHAR(100) NULL COLLATE 'utf8mb4_general_ci',
	`clientcode` CHAR(50) NULL COLLATE 'utf8mb4_general_ci',
	`costcenters_code` CHAR(50) NULL COLLATE 'utf8mb4_general_ci',
	`client_cat1` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`client_cat2` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`bussinesssector_code` CHAR(50) NULL COLLATE 'utf8mb4_general_ci',
	`client_regstatus` CHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`client_tel1` CHAR(100) NULL COLLATE 'utf8mb4_general_ci',
	`client_tel2` CHAR(100) NULL COLLATE 'utf8mb4_general_ci',
	`client_addressphysical` VARCHAR(100) NULL COLLATE 'utf8mb4_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for table moneybankonline.whos_online
DROP TABLE IF EXISTS `whos_online`;
CREATE TABLE IF NOT EXISTS `whos_online` (
  `user_id` int(11) unsigned DEFAULT NULL,
  `full_name` char(64) NOT NULL DEFAULT '',
  `session_id` char(128) NOT NULL DEFAULT '',
  `ip_address` char(15) NOT NULL DEFAULT '',
  `time_entry` char(14) NOT NULL DEFAULT '',
  `time_last_click` char(14) NOT NULL DEFAULT '',
  `last_page_url` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.withdrawal
DROP TABLE IF EXISTS `withdrawal`;
CREATE TABLE IF NOT EXISTS `withdrawal` (
  `withdrawal_id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `withdrawal_firstname` char(200) DEFAULT NULL,
  `withdrawal_middlename` char(200) NOT NULL,
  `withdrawal_lastname` char(200) DEFAULT NULL,
  `transfers_code` tinytext NOT NULL,
  `transfers_docnum` char(50) NOT NULL,
  `documenttypes_id` tinyint(4) unsigned NOT NULL,
  `withdrawal_scanneddoc` char(50) NOT NULL,
  `receiver_exchangerate` decimal(15,5) unsigned NOT NULL DEFAULT 0.00000,
  `receiver_amountrecieved` decimal(15,5) unsigned NOT NULL DEFAULT 0.00000,
  `withdrawal_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `withdrawal_receipt` mediumtext NOT NULL,
  `branch_code` char(50) NOT NULL,
  PRIMARY KEY (`withdrawal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.xdues
DROP TABLE IF EXISTS `xdues`;
CREATE TABLE IF NOT EXISTS `xdues` (
  `loan_number` char(10) NOT NULL,
  `due_date` date NOT NULL,
  `due_id` char(80) NOT NULL,
  `due_principal` decimal(15,5) NOT NULL DEFAULT 0.00000,
  `due_interest` decimal(15,5) NOT NULL DEFAULT 0.00000,
  `due_penalty` decimal(15,5) NOT NULL DEFAULT 0.00000,
  `due_commission` decimal(15,5) NOT NULL DEFAULT 0.00000,
  `due_vat` decimal(15,5) NOT NULL DEFAULT 0.00000,
  `members_idno` char(50) DEFAULT '',
  `due_status` char(3) DEFAULT '',
  PRIMARY KEY (`loan_number`,`due_date`,`due_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table moneybankonline.xmltrans
DROP TABLE IF EXISTS `xmltrans`;
CREATE TABLE IF NOT EXISTS `xmltrans` (
  `xmltrans_id` char(200) NOT NULL,
  `xmltrans_data` longtext NOT NULL,
  `xmltrans_datecreated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` char(50) NOT NULL,
  `xmltrans_status` char(1) NOT NULL,
  `xmltrans_keyvalues` mediumtext DEFAULT NULL,
  `xmltrans_table` varchar(250) DEFAULT NULL,
  `xmltrans_dynamicsqlid` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`xmltrans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='This table is used to store xaml transactions';

-- Data exporting was unselected.

-- Dumping structure for trigger moneybankonline.deletedtrans_after_insert
DROP TRIGGER IF EXISTS `deletedtrans_after_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `deletedtrans_after_insert` AFTER INSERT ON `deletedtrans` FOR EACH ROW BEGIN

    SET @transactioncodes = NEW.transactioncode; 
    SET @cmodule = NEW.deletedtrans_module; 
    
    	-- set language for oprning balance description
/*	CASE plang  WHEN 'EN' THEN SET @cDescription :='Reversal' ;
	
	WHEN 'FR' THEN SET @cDescription :='Renversement';
	
	WHEN 'SP' THEN SET @cDescription :='InversiÃƒÂ³n';
	
	ELSE SET @cDescription:= 'Reversal' ;
	
	END CASE;*/
	
	
	SET @cDescription:= 'Reversal' ;
	
	 -- SAVINGS
	 IF @cmodule  ='S' THEN
	 
		SELECT savaccounts_account INTO @accounts FROM savtransactions  where FIND_IN_SET(TRIM(transactioncode),trim(@transactioncodes))>0  LIMIT 1;
	
		SELECT product_prodid INTO @prodid FROM savtransactions  where FIND_IN_SET(TRIM(transactioncode),TRIM(@transactioncodes))>0  LIMIT 1;
	
	 	DELETE FROM  savtransactions WHERE  FIND_IN_SET(transactioncode,trim(@transactioncodes))>0;
	 		
	 		
	 	-- UPDATE BALANCES
	 	UPDATE savtransactions AS w
		SET w.savtransactions_balance =  (@prevbalance:= (@prevbalance + w.savtransactions_amount))
		WHERE w.savaccounts_account = @accounts and w.product_prodid =@prodid
		ORDER BY w.savtransactions_tday ASC,w.last_updatedate;
    
	END IF;
	
	IF @cmodule  ='L' THEN	 
		DELETE FROM  loanpayments WHERE FIND_IN_SET(transactioncode,TRIM(@transactioncodes))>0;	
	END IF;
		 
	IF @cmodule  ='T' THEN	 
		DELETE FROM  timedeposittrans WHERE FIND_IN_SET(transactioncode,TRIM(@transactioncodes))>0;			
	END IF;
		 
	 -- general ledger	
	IF @cmodule  ='G' THEN
		INSERT INTO generalledger (
			   generalledger_id,			 
			 	transactioncode,
				generalledger_description,
				fund_code,
				donor_code,
				generalledger_voucher,
				generalledger_tday,
				generalledger_debit,
				generalledger_credit,
			 	chartofaccounts_accountcode,
				branch_code,
				trancode,
				generalledger_locked,
				forexrates_id,
				generalledger_fcamount,
				currencies_id,
				client_idno,
				product_prodid,
				costcenters_code,
				user_id)	 
			SELECT 
				UUID(),
				transactioncode,
				CONCAT(transactioncode,' ',@cDescription) generalledger_description,
				fund_code,
				donor_code,
				generalledger_voucher,
				generalledger_tday,
				CASE WHEN generalledger_credit <> 0 THEN generalledger_credit END generalledger_debit,
				CASE WHEN generalledger_debit <> 0 THEN generalledger_debit END generalledger_credit,
			 	chartofaccounts_accountcode,
				 branch_code,
				 trancode,
				 generalledger_locked,
				 forexrates_id,
				 generalledger_fcamount,
				 currencies_id,
				 client_idno,
				 product_prodid,
				 costcenters_code,
				 user_id FROM generalledger WHERE FIND_IN_SET(transactioncode ,TRIM(@transactioncodes))>0;		    
	 
	END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger moneybankonline.savaccounts_before_insert
DROP TRIGGER IF EXISTS `savaccounts_before_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `savaccounts_before_insert` BEFORE INSERT ON `savaccounts` FOR EACH ROW BEGIN
SET new.savaccounts_id = uuid();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger moneybankonline.trg_dues
DROP TRIGGER IF EXISTS `trg_dues`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_dues` BEFORE INSERT ON `dues` FOR EACH ROW BEGIN 
    IF ASCII(NEW.due_id) = 0 OR  NEW.due_id IS NULL THEN 
        SET NEW.due_id = UUID(); 
    END IF; 
    SET @last_uuid = NEW.due_id; 
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger moneybankonline.trg_loanpayments
DROP TRIGGER IF EXISTS `trg_loanpayments`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_loanpayments` BEFORE INSERT ON `loanpayments` FOR EACH ROW BEGIN 
    IF ASCII(NEW.loanpayments_id) = 0 THEN 
        SET NEW.loanpayments_id = UUID(); 
    END IF; 
    SET @last_uuid = NEW.loanpayments_id; 
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger moneybankonline.trg_xmlstrans
DROP TRIGGER IF EXISTS `trg_xmlstrans`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_xmlstrans` AFTER INSERT ON `xmltrans` FOR EACH ROW BEGIN

	CALL sp_xml_update_db_new(NEW.xmltrans_data) ;	


END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `productcurrencies`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `productcurrencies` AS select product_prodid,COALESCE(productconfig_value,0) currencies_id from productconfig where productconfig_paramname='CURRENCIES_ID' group by product_prodid ;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_clients`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_clients` AS SELECT 
	client_type,
	 branch_code,
	 client_regdate,
	 client_surname,
	 client_firstname,
	 client_middlename,
	 client_idno,
	 client_gender,
	 areacode_code,
	 clientcode,
	 costcenters_code,
	 client_cat1,
	 client_cat2,
	 bussinesssector_code,
	 client_regstatus,
	 client_tel1,
	 client_tel2,
	 client_addressphysical
	 FROM clients FORCE INDEX(client_surname_client_firstname_client_middlename)
	 UNION ALL
SELECT 
	 branch_code,
	 entity_type client_type,
	 entity_regdate client_regdate,
	 entity_name client_surname,
	 '' client_firstname,
	 '' client_middlename,
	 entity_idno client_idno,
	 'U' client_gender,
	 areacode_code,
	 entity_idno clientcode,
	 costcenters_code,
	 '' client_cat1,
	 '' client_cat2,
	 bussinesssector_code,
	 entity_regstatus client_regstatus,
	 entity_tel1 client_tel1,
	 entity_tel2 client_tel2,
	 '' client_addressphysical
	 FROM entity FORCE INDEX(groups_name) ;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
