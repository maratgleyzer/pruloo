-- phpMyAdmin SQL
-- version 2.11.9.4
-- http://www.phpmyadmin.net
--
-- Server version: 5.0.67
-- PHP Version: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `money_machine`
--

-- CREATE DATABASE `moneymachine`;

USE `moneymachine`;

DROP TABLE IF EXISTS `user2offer`;
DROP TABLE IF EXISTS `user2product`;
DROP TABLE IF EXISTS `user2group`;
DROP TABLE IF EXISTS `user2campaign`;
DROP TABLE IF EXISTS `user2affiliate`;
DROP TABLE IF EXISTS `campaign2click`;
DROP TABLE IF EXISTS `campaign2country`;
DROP TABLE IF EXISTS `campaign2shipping`;
DROP TABLE IF EXISTS `mm_user_order_note`;
DROP TABLE IF EXISTS `mm_user_order`;
DROP TABLE IF EXISTS `mm_user_campaign`;
DROP TABLE IF EXISTS `mm_user_offer`;
DROP TABLE IF EXISTS `mm_user_product`;
DROP TABLE IF EXISTS `mm_user_gateway`;
DROP TABLE IF EXISTS `mm_user_shipping`;
DROP TABLE IF EXISTS `mm_user_group`;
DROP TABLE IF EXISTS `mm_user`;
DROP TABLE IF EXISTS `mm_state`;
DROP TABLE IF EXISTS `mm_currency`;
DROP TABLE IF EXISTS `mm_language`;
DROP TABLE IF EXISTS `mm_country`;
DROP TABLE IF EXISTS `mm_gateway`;

-- --------------------------------------------------------

--
-- Table structure for table `mm_user`
--

CREATE TABLE IF NOT EXISTS `mm_user` (
  `user_id`				smallint unsigned NOT NULL auto_increment,
  `referer`				smallint unsigned NOT NULL,
  `ordr_id`				mediumint unsigned NOT NULL,
  `disable`				tinyint(1) unsigned zerofill NOT NULL,
  `decline`				tinyint(1) unsigned zerofill NOT NULL,
  `vacation`			tinyint(1) unsigned zerofill NOT NULL,
  `advertiser`			tinyint(1) unsigned zerofill NOT NULL,
  `affiliate`			tinyint(1) unsigned zerofill NOT NULL,
  `telemarketer`		tinyint(1) unsigned zerofill NOT NULL,
  `business`			char(32) collate utf8_unicode_ci NULL,
  `user_first`			char(24) collate utf8_unicode_ci NOT NULL,
  `user_last`			char(24) collate utf8_unicode_ci NOT NULL,
  `user_address`		char(64) collate utf8_unicode_ci NULL,
  `user_city`			char(32) collate utf8_unicode_ci NULL,
  `user_zip`			char(8) collate utf8_unicode_ci NULL,
  `user_state`			char(4) collate utf8_unicode_ci NULL,
  `user_country`		char(4) collate utf8_unicode_ci NULL,
  `user_phone`			char(24) collate utf8_unicode_ci NOT NULL,
  `user_email`			char(64) collate utf8_unicode_ci NOT NULL,
  `username`			char(64) collate utf8_unicode_ci NOT NULL,
  `password`			char(32) collate utf8_unicode_ci NOT NULL,
  `changed`				date NOT NULL default '0000-00-00',
  `created`				date NOT NULL default '0000-00-00',
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `ui_1_mm_user` (`user_email`),
  UNIQUE INDEX `ui_2_mm_user` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='' AUTO_INCREMENT=12345 ;

-- --------------------------------------------------------

--
-- Table structure for table `mm_state`
--

CREATE TABLE IF NOT EXISTS `mm_state` (
  `abbr` 				char(4) collate utf8_unicode_ci NOT NULL,
  `country`				char(4) collate utf8_unicode_ci NOT NULL,
  `verbose`	 			char(32) collate utf8_unicode_ci NULL,
  `disable` 			tinyint(1) unsigned zerofill NOT NULL,
  UNIQUE KEY `uk_mm_state` (`abbr`,`country`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='';

-- --------------------------------------------------------

--
-- Table structure for table `mm_country`
--

CREATE TABLE IF NOT EXISTS `mm_country` (
  `abbr` 				char(4) collate utf8_unicode_ci NOT NULL,
  `verbose`	 			char(32) collate utf8_unicode_ci NOT NULL,
  `language`			char(4) collate utf8_unicode_ci NULL,
  `currency`			char(4) collate utf8_unicode_ci NULL,
  `disable` 			tinyint(1) unsigned zerofill NOT NULL,
  UNIQUE KEY `uk_mm_country` (`abbr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='';

-- --------------------------------------------------------

--
-- Table structure for table `mm_language`
--

CREATE TABLE IF NOT EXISTS `mm_language` (
  `abbr` 				char(4) collate utf8_unicode_ci NOT NULL,
  `dialect`	 			char(32) collate utf8_unicode_ci NOT NULL,
  `disable` 			tinyint(1) unsigned zerofill NOT NULL,
  UNIQUE KEY `uk_mm_language` (`abbr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='';

-- --------------------------------------------------------

--
-- Table structure for table `mm_currency`
--

CREATE TABLE IF NOT EXISTS `mm_currency` (
  `abbr` 				char(4) collate utf8_unicode_ci NOT NULL,
  `symbol`				char(8) collate utf8_unicode_ci NOT NULL,
  `exchange`			decimal(8,2) unsigned NOT NULL,
  `disable` 			tinyint(1) unsigned zerofill NOT NULL,
  UNIQUE KEY `uk_mm_country` (`abbr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='';

-- --------------------------------------------------------

--
-- Table structure for table `mm_gateway`
--

CREATE TABLE IF NOT EXISTS `mm_gateway` (
  `gate_id`				tinyint unsigned NOT NULL auto_increment,
  `gate_name` 			char(32) collate utf8_unicode_ci NOT NULL,
  `gate_link` 			char(64) collate utf8_unicode_ci NOT NULL,
  `test_link` 			char(64) collate utf8_unicode_ci NOT NULL,
  `test_acct` 			char(32) collate utf8_unicode_ci NULL,
  `test_user` 			char(32) collate utf8_unicode_ci NOT NULL,
  `test_pass` 			char(32) collate utf8_unicode_ci NOT NULL,
  `test_plan` 			char(16) collate utf8_unicode_ci NULL,
  `test_card` 			char(16) collate utf8_unicode_ci NULL,
  `test_type`			char(16) collate utf8_unicode_ci NULL,
  `disable` 			tinyint(1) unsigned zerofill NOT NULL,
  `debug` 				tinyint(1) unsigned zerofill NOT NULL,
  PRIMARY KEY (`gate_id`),
  UNIQUE INDEX `ui_mm_gateway` (`gate_link`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='' AUTO_INCREMENT=123 ;

-- --------------------------------------------------------

--
-- Table structure for table `mm_user_gateway`
--

CREATE TABLE IF NOT EXISTS `mm_user_gateway` (
  `plan_id` 			smallint unsigned NOT NULL auto_increment,
  `user_id`	 			smallint unsigned NOT NULL,
  `gate_id` 			tinyint unsigned NOT NULL,
  `disable`				tinyint(1) unsigned zerofill NOT NULL,
  `checking`			tinyint(1) unsigned zerofill NOT NULL,
  `purchases` 			decimal(8,2) unsigned NOT NULL default '0',
  `threshold` 			decimal(8,2) unsigned NOT NULL default '0',
  `gate_name`			char(32) collate utf8_unicode_ci NULL,
  `gate_acct`			char(32) collate utf8_unicode_ci NULL,
  `gate_user`			char(64) collate utf8_unicode_ci NOT NULL,
  `gate_pass` 			char(64) collate utf8_unicode_ci NOT NULL,
  `gate_plan` 			char(16) collate utf8_unicode_ci NULL,
  `changed`				date NOT NULL default '0000-00-00',
  `created`				date NOT NULL default '0000-00-00',
  PRIMARY KEY (`plan_id`),
  UNIQUE INDEX `ui_mm_user_gateway` (`gate_id`,`gate_acct`,`gate_user`,`gate_pass`,`gate_plan`),
  CONSTRAINT `fk_1_mm_user_gateway` FOREIGN KEY (`gate_id`) REFERENCES `mm_gateway` (`gate_id`),
  CONSTRAINT `fk_2_mm_user_gateway` FOREIGN KEY (`user_id`) REFERENCES `mm_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='' AUTO_INCREMENT=12345 ;

-- --------------------------------------------------------

--
-- Table structure for table `mm_user_group`
--

CREATE TABLE IF NOT EXISTS `mm_user_group` (
  `grou_id` 			smallint unsigned NOT NULL auto_increment,
  `ownr_id`	 			smallint unsigned NOT NULL,
  `disable` 			tinyint(1) unsigned zerofill NOT NULL,
  `group_name`			char(32) collate utf8_unicode_ci NOT NULL,
  `group_desc`			char(255) collate utf8_unicode_ci NOT NULL,
  `changed`				date NOT NULL default '0000-00-00',
  `created`				date NOT NULL default '0000-00-00',
  PRIMARY KEY (`grou_id`),
  CONSTRAINT `fk_mm_user_group` FOREIGN KEY (`ownr_id`) REFERENCES `mm_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='' AUTO_INCREMENT=12345 ;

-- --------------------------------------------------------

--
-- Table structure for table `mm_user_product`
--

CREATE TABLE IF NOT EXISTS `mm_user_product` (
  `prod_id`				mediumint unsigned NOT NULL auto_increment,
  `user_id`				smallint unsigned NOT NULL,
  `disable`				tinyint(1) unsigned zerofill NOT NULL,
  `public`				tinyint(1) unsigned zerofill NOT NULL,
  `private`				tinyint(1) unsigned zerofill NOT NULL,
  `personal`			tinyint(1) unsigned zerofill NOT NULL,
  `product_sku`			char(16) collate utf8_unicode_ci NULL,
  `product_name`		char(64) collate utf8_unicode_ci NOT NULL,
  `product_desc`		char(255) collate utf8_unicode_ci NULL,
  `product_cost`		decimal(5,2) unsigned NOT NULL default '0',
  `product_size`		smallint unsigned NOT NULL default '0',
  `created`				date NOT NULL default '0000-00-00',
  PRIMARY KEY (`prod_id`),
  UNIQUE INDEX `ui_mm_user_product` (`user_id`,`product_name`),
  CONSTRAINT `fk_mm_user_product` FOREIGN KEY (`user_id`) REFERENCES `mm_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='' AUTO_INCREMENT=1234567 ;

-- --------------------------------------------------------

--
-- Table structure for table `mm_user_offer`
--

CREATE TABLE IF NOT EXISTS `mm_user_offer` (
  `offr_id`				mediumint unsigned NOT NULL auto_increment,
  `prod_id`				mediumint unsigned NOT NULL,
  `user_id`				smallint unsigned NOT NULL,
  `disable`				tinyint(1) unsigned zerofill NOT NULL,
  `public`				tinyint(1) unsigned zerofill NOT NULL,
  `private`				tinyint(1) unsigned zerofill NOT NULL,
  `personal`			tinyint(1) unsigned zerofill NOT NULL,
  `offer_name`			char(64) collate utf8_unicode_ci NOT NULL,
  `offer_link`			char(64) collate utf8_unicode_ci NOT NULL,
  `offer_cost`			decimal(5,2) unsigned NOT NULL default '0',
  `trial_cost`			decimal(5,2) unsigned NOT NULL default '0',
  `trial_term`			tinyint(2) unsigned NOT NULL default '0',
  `recur_term`			tinyint(2) unsigned NOT NULL default '0',
  `created`				date NOT NULL default '0000-00-00',
  PRIMARY KEY (`offr_id`),
  UNIQUE INDEX `ui_mm_user_offer` (`user_id`,`offer_name`),
  CONSTRAINT `fk_1_mm_user_offer` FOREIGN KEY (`user_id`) REFERENCES `mm_user` (`user_id`),
  CONSTRAINT `fk_2_mm_user_offer` FOREIGN KEY (`prod_id`) REFERENCES `mm_user_product` (`prod_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='' AUTO_INCREMENT=1234567 ;

-- --------------------------------------------------------

--
-- Table structure for table `mm_user_campaign`
--

CREATE TABLE IF NOT EXISTS `mm_user_campaign` (
  `camp_id`				mediumint unsigned NOT NULL auto_increment,
  `offr_id`				mediumint unsigned NOT NULL,
  `upsl_id`				mediumint unsigned NOT NULL,
  `user_id`				smallint unsigned NOT NULL,
  `disable`				tinyint(1) unsigned zerofill NOT NULL,
  `public`				tinyint(1) unsigned zerofill NOT NULL,
  `private`				tinyint(1) unsigned zerofill NOT NULL,
  `personal`			tinyint(1) unsigned zerofill NOT NULL,
  `campaign`			char(64) collate utf8_unicode_ci NOT NULL,
  `pages`				tinyint(1) unsigned zerofill NOT NULL default '1',
  `created`				date NOT NULL default '0000-00-00',
  PRIMARY KEY (`camp_id`),
  UNIQUE INDEX `ui_mm_user_campaign` (`user_id`,`campaign`),
  CONSTRAINT `fk_1_mm_user_campaign` FOREIGN KEY (`user_id`) REFERENCES `mm_user` (`user_id`),
  CONSTRAINT `fk_2_mm_user_campaign` FOREIGN KEY (`offr_id`) REFERENCES `mm_user_offer` (`offr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='' AUTO_INCREMENT=1234567 ;

-- --------------------------------------------------------

--
-- Table structure for table `campaign2click`
--

CREATE TABLE IF NOT EXISTS `campaign2click` (
  `camp_id`				mediumint unsigned NOT NULL,
  `affi_id`				smallint unsigned NOT NULL,
  `subs_id`				char(64) collate utf8_unicode_ci NULL,
  `ip_address`			char(16) collate utf8_unicode_ci NOT NULL,
  `create_date`			date NOT NULL default '0000-00-00',
  `create_time`			time NOT NULL default '00:00:00',
  UNIQUE KEY `uk_campaign2click` (`camp_id`,`affi_id`,`subs_id`,`ip_address`),
  CONSTRAINT `fk_1_campaign2click` FOREIGN KEY (`camp_id`) REFERENCES `mm_user_campaign` (`camp_id`),
  CONSTRAINT `fk_2_campaign2click` FOREIGN KEY (`affi_id`) REFERENCES `mm_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='' ;

-- --------------------------------------------------------

--
-- Table structure for table `mm_user_shipping`
--

CREATE TABLE IF NOT EXISTS `mm_user_shipping` (
  `ship_id` 			smallint unsigned NOT NULL auto_increment,
  `user_id`	 			smallint unsigned NOT NULL,
  `ship_name`			char(32) collate utf8_unicode_ci NOT NULL,
  `ship_cost`			decimal(4,2) unsigned NOT NULL default '0',
  `disable` 			tinyint(1) unsigned zerofill NOT NULL,
  PRIMARY KEY (`ship_id`),
  UNIQUE INDEX `ui_mm_user_shipping` (`user_id`,`ship_name`,`ship_cost`),
  CONSTRAINT `fk_mm_user_shipping` FOREIGN KEY (`user_id`) REFERENCES `mm_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='' AUTO_INCREMENT=12345 ;

-- --------------------------------------------------------

--
-- Table structure for table `mm_user_order`
--

-- 1       ,2         ,3        ,4           ,5        ,6         ,7       ,8           ,9         ,10        ,11        ,12       ,13          ,14       ,15        ,16      ,17          ,18        ,19        ,20       ,21        ,22    ,23                   ,24                    ,25        ,26          ,27          ,28             ,29     ,30             ,31             ,32                ,33                     ,34         ,35            ,36         ,37               ,38           ,39            ,40       ,41            ,42     ,43         ,44         ,45           ,46            ,47                 ,48          ,49   ,50  ,51    ,52 ,53,54,55 ,56  ,57  ,58              ,59           ,60           ,61           ,62         ,63
-- Order_Id,Bill_First,Bill_Last,Bill_Address,Bill_City,Bill_State,Bill_Zip,Bill_Country,Bill_Phone,Bill_Email,Ship_First,Ship_Last,Ship_Address,Ship_City,Ship_State,Ship_Zip,Ship_Country,Ship_Phone,Ship_Email,Ship_Type,Ship_Price,Weight,Delivery_Confirmation,Signature_Confirmation,Total_Sale,Date_of_Sale,Time_of_Sale,Tracking_Number,Payment,Customer_Number,Prospect_Number,Credit Card Number, Credit Card Expiration, Gateway Id, Gateway Alias, IP Address,IP Address Lookup, Order_Status,Decline Reason, Is Fraud, Is Chargeback, Is RMA, RMA Number, RMA Reason, Is_Recurring,Recurring_Date, Transaction_Number, Auth_Number, AFID, SID, AFFID, C1,C2,C3,BID, AID, OPT, Rebill Discount, Product_Name,Product_Price,Product_Sku #,Description,Quantity
-- 15779   ,Shambho   ,Krish	,3557 Sunnyda, Santa Cl,CA	      ,95051   , United Stat,408-222-22,shambho71@,Shambho   ,Krish	   ,3557 Sunnyda,Santa Cla, CA		 ,95051	  ,United State,408-222-22,shambho71@,NA	   ,0		  ,0	 ,No				   ,No					  ,78.14	 ,9/25/2009	  ,1:02am	   ,			   ,Masterc,4618		   ,0			   ,5.40168E+15		  ,1009					  ,14		  ,Ezgoogl		 ,69.110.4.16, Oakland CA  Unit,NEW			 ,			    , NO	  , NO			 , NO	 ,NA		 ,NA		 ,1			   ,10/25/2009	  ,1047026087		  ,06947P	   ,	 ,2821,101	 ,54 ,40,  ,   ,    ,    ,0				  ,Google 1st Or,78.14		  ,6000001	    ,Google 1st ,1

CREATE TABLE IF NOT EXISTS `mm_user_order` (
  `ordr_id`							mediumint unsigned NOT NULL auto_increment,
  `lead_id`							mediumint unsigned NULL,
  `camp_id`							mediumint unsigned NOT NULL,
  `user_id`							smallint unsigned NOT NULL,
  `plan_id`							smallint unsigned NULL,
  `ship_id`							smallint unsigned NULL,
  `affi_id`							smallint unsigned NULL,
  `subs_id`							char(64) collate utf8_unicode_ci NULL,
  `void_id`							char(64) collate utf8_unicode_ci NULL,
  `bill_first`						char(24) collate utf8_unicode_ci NULL,
  `bill_last`			 			char(24) collate utf8_unicode_ci NULL,
  `bill_address` 					char(64) collate utf8_unicode_ci NULL,
  `bill_city`			 			char(32) collate utf8_unicode_ci NULL,
  `bill_state` 						char(4) collate utf8_unicode_ci NULL,
  `bill_zip` 						char(8) collate utf8_unicode_ci NULL,
  `bill_country` 					char(4) collate utf8_unicode_ci NULL,
  `bill_phone_1` 					char(4) collate utf8_unicode_ci NOT NULL default '000' ,
  `bill_phone_2` 					char(4) collate utf8_unicode_ci NOT NULL default '000' ,
  `bill_phone_3` 					char(4) collate utf8_unicode_ci NOT NULL default '000' ,
  `bill_phone_4` 					char(4) collate utf8_unicode_ci NOT NULL default '000' ,
  `bill_phone_5` 					char(4) collate utf8_unicode_ci NOT NULL default '000' ,
  `bill_phone` 						char(24) collate utf8_unicode_ci NULL,
  `bill_email`			 			char(64) collate utf8_unicode_ci NOT NULL,
  `ship_first` 						char(24) collate utf8_unicode_ci NULL,
  `ship_last` 						char(24) collate utf8_unicode_ci NULL,
  `ship_address` 					char(64) collate utf8_unicode_ci NULL,
  `ship_city` 						char(32) collate utf8_unicode_ci NULL,
  `ship_state` 						char(4) collate utf8_unicode_ci NULL,
  `ship_zip` 						char(8) collate utf8_unicode_ci NULL,
  `ship_country`			 		char(4) collate utf8_unicode_ci NULL,
  `ship_phone_1` 					char(4) collate utf8_unicode_ci NOT NULL default '000' ,
  `ship_phone_2` 					char(4) collate utf8_unicode_ci NOT NULL default '000' ,
  `ship_phone_3` 					char(4) collate utf8_unicode_ci NOT NULL default '000' ,
  `ship_phone_4` 					char(4) collate utf8_unicode_ci NOT NULL default '000' ,
  `ship_phone_5` 					char(4) collate utf8_unicode_ci NOT NULL default '000' ,
  `ship_phone` 						char(24) collate utf8_unicode_ci NULL,
  `ship_email` 						char(64) collate utf8_unicode_ci NULL,
  `tracking`		 				char(32) collate utf8_unicode_ci NULL,
  `same_as_billing`					tinyint(1) unsigned zerofill NOT NULL,
  `delivery`						tinyint(1) unsigned zerofill NOT NULL,
  `signature`						tinyint(1) unsigned zerofill NOT NULL,
  `quantity` 						tinyint unsigned NOT NULL default '1',
  `weight`							smallint unsigned NOT NULL default '0',
  `shipper_sale` 					decimal(8,2) unsigned NOT NULL default '0',
  `product_sale` 					decimal(8,2) unsigned NOT NULL default '0',
  `total_sale`						decimal(8,2) unsigned NOT NULL default '0',
  `country`							char(4) collate utf8_unicode_ci NULL,
  `card_type` 						char(16) collate utf8_unicode_ci NULL,
  `card_number`		 				char(16) collate utf8_unicode_ci NULL,
  `expires_mm` 						char(2) collate utf8_unicode_ci NULL,
  `expires_yy` 						char(2) collate utf8_unicode_ci NULL,
  `expiration` 						char(4) collate utf8_unicode_ci NULL,
  `check_book` 						char(16) collate utf8_unicode_ci NULL,
  `ip_address` 						char(16) collate utf8_unicode_ci NOT NULL,
  `ip_lookup`		 				char(64) collate utf8_unicode_ci NULL,
  `order_status` 					char(16) collate utf8_unicode_ci NULL,
  `decline_reason`					char(64) collate utf8_unicode_ci NULL,
  `transaction` 					char(64) collate utf8_unicode_ci NULL,
  `auth_number` 					char(64) collate utf8_unicode_ci NULL,
  `rma_reason` 						char(255) collate utf8_unicode_ci NULL,
  `rma_number` 						char(16) collate utf8_unicode_ci NULL,
  `bank_routing`					char(32) collate utf8_unicode_ci NULL,
  `bank_account`					char(32) collate utf8_unicode_ci NULL,
  `bank_account_type`				char(16) collate utf8_unicode_ci NULL,
  `bank_account_holder`				char(16) collate utf8_unicode_ci NULL,
  `bank_check` 						tinyint(1) unsigned zerofill NOT NULL,
  `chargeback` 						tinyint(1) unsigned zerofill NOT NULL,
  `blacklist` 						tinyint(1) unsigned zerofill NOT NULL,
  `fraud`	 						tinyint(1) unsigned zerofill NOT NULL,
  `retry`							tinyint(1) unsigned zerofill NOT NULL,
  `rebill`							tinyint(1) unsigned zerofill NOT NULL,
  `disable`							tinyint(1) unsigned zerofill NOT NULL,
  `expired`	 						tinyint(1) unsigned zerofill NOT NULL,
  `rebill_disc`						decimal(8,2) unsigned NOT NULL default '0',
  `rebill_date`						date NULL default '0000-00-00',
  `lead_date`						date NOT NULL default '0000-00-00',
  `lead_time`	 					time NOT NULL default '00:00:00',
  `sale_date`						date NOT NULL default '0000-00-00',
  `sale_time`	 					time NOT NULL default '00:00:00',
  `void_date`						date NOT NULL default '0000-00-00',
  `void_time`	 					time NOT NULL default '00:00:00',
  PRIMARY KEY (`ordr_id`),
  CONSTRAINT `fk_1_mm_user_order` FOREIGN KEY (`user_id`) REFERENCES `mm_user` (`user_id`),
  CONSTRAINT `fk_2_mm_user_order` FOREIGN KEY (`camp_id`) REFERENCES `mm_user_campaign` (`camp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='' AUTO_INCREMENT=1234567 ;

-- --------------------------------------------------------

--
-- Table structure for table `mm_user_order_note`
--

CREATE TABLE IF NOT EXISTS `mm_user_order_note` (
  `ordr_id`							mediumint unsigned NOT NULL,
  `user_id`							smallint unsigned NOT NULL,
  `note`							char(255) collate utf8_unicode_ci NOT NULL,
  `create_date`			 			date NOT NULL default '0000-00-00',
  `create_time`						time NOT NULL default '00:00:00',
  CONSTRAINT `fk_1_mm_user_order_note` FOREIGN KEY (`ordr_id`) REFERENCES `mm_user_order` (`ordr_id`),
  CONSTRAINT `fk_2_mm_user_order_note` FOREIGN KEY (`user_id`) REFERENCES `mm_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='' ;

-- --------------------------------------------------------

--
-- Table structure for table `campaign2country`
--

CREATE TABLE IF NOT EXISTS `campaign2country` (
  `camp_id`				mediumint unsigned NOT NULL,
  `country`	 			char(4) collate utf8_unicode_ci NOT NULL,
  `disable` 			tinyint(1) unsigned zerofill NOT NULL,
  UNIQUE KEY `uk_campaign2country` (`camp_id`,`country`),
  CONSTRAINT `fk_1_campaign2country` FOREIGN KEY (`camp_id`) REFERENCES `mm_user_campaign` (`camp_id`),
  CONSTRAINT `fk_2_campaign2country` FOREIGN KEY (`country`) REFERENCES `mm_country` (`abbr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='';

-- --------------------------------------------------------

--
-- Table structure for table `campaign2shipping`
--

CREATE TABLE IF NOT EXISTS `campaign2shipping` (
  `camp_id` 			mediumint unsigned NOT NULL,
  `ship_id`	 			smallint unsigned NOT NULL,
  `disable`	 			tinyint(1) unsigned zerofill NOT NULL,
  UNIQUE KEY `uk_campaign2shipping` (`camp_id`,`ship_id`),
  CONSTRAINT `fk_1_campaign2shipping` FOREIGN KEY (`camp_id`) REFERENCES `mm_user_campaign` (`camp_id`),
  CONSTRAINT `fk_2_campaign2shipping` FOREIGN KEY (`ship_id`) REFERENCES `mm_user_shipping` (`ship_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='';

-- --------------------------------------------------------

--
-- Table structure for table `user2campaign`
--

CREATE TABLE IF NOT EXISTS `user2campaign` (
  `user_id` 			smallint unsigned NOT NULL,
  `camp_id`	 			mediumint unsigned NOT NULL,
  `disable` 			tinyint(1) unsigned zerofill NOT NULL,
  `changed`				date NOT NULL default '0000-00-00',
  `created`				date NOT NULL default '0000-00-00',
  UNIQUE KEY `uk_user2campaign` (`user_id`,`camp_id`),
  CONSTRAINT `fk_1_user2campaign` FOREIGN KEY (`user_id`) REFERENCES `mm_user` (`user_id`),
  CONSTRAINT `fk_2_user2campaign` FOREIGN KEY (`camp_id`) REFERENCES `mm_user_campaign` (`camp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='';

-- --------------------------------------------------------

--
-- Table structure for table `user2group`
--

CREATE TABLE IF NOT EXISTS `user2group` (
  `user_id` 			smallint unsigned NOT NULL,
  `grou_id`	 			smallint unsigned NOT NULL,
  `disable` 			tinyint(1) unsigned zerofill NOT NULL,
  `changed`				date NOT NULL default '0000-00-00',
  `created`				date NOT NULL default '0000-00-00',
  UNIQUE KEY `uk_user2group` (`user_id`,`grou_id`),
  CONSTRAINT `fk_1_user2group` FOREIGN KEY (`user_id`) REFERENCES `mm_user` (`user_id`),
  CONSTRAINT `fk_2_user2group` FOREIGN KEY (`grou_id`) REFERENCES `mm_user_group` (`grou_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='';

-- --------------------------------------------------------

--
-- Table structure for table `user2product`
--

CREATE TABLE IF NOT EXISTS `user2product` (
  `user_id` 			smallint unsigned NOT NULL,
  `prod_id`	 			mediumint unsigned NOT NULL,
  `disable` 			tinyint(1) unsigned zerofill NOT NULL,
  `changed`				date NOT NULL default '0000-00-00',
  `created`				date NOT NULL default '0000-00-00',
  UNIQUE KEY `uk_user2product` (`user_id`,`prod_id`),
  CONSTRAINT `fk_1_user2product` FOREIGN KEY (`user_id`) REFERENCES `mm_user` (`user_id`),
  CONSTRAINT `fk_2_user2product` FOREIGN KEY (`prod_id`) REFERENCES `mm_user_product` (`prod_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='';

-- --------------------------------------------------------

--
-- Table structure for table `user2offer`
--

CREATE TABLE IF NOT EXISTS `user2offer` (
  `user_id` 			smallint unsigned NOT NULL,
  `offr_id`	 			mediumint unsigned NOT NULL,
  `disable` 			tinyint(1) unsigned zerofill NOT NULL,
  `changed`				date NOT NULL default '0000-00-00',
  `created`				date NOT NULL default '0000-00-00',
  UNIQUE KEY `uk_user2offer` (`user_id`,`offr_id`),
  CONSTRAINT `fk_1_user2offer` FOREIGN KEY (`user_id`) REFERENCES `mm_user` (`user_id`),
  CONSTRAINT `fk_2_user2offer` FOREIGN KEY (`offr_id`) REFERENCES `mm_user_offer` (`offr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='';

-- --------------------------------------------------------

--
-- Table structure for table `user2affiliate`
--

CREATE TABLE IF NOT EXISTS `user2affiliate` (
  `user_id` 			smallint unsigned NOT NULL,
  `affi_id`	 			smallint unsigned NOT NULL,
  `disable` 			tinyint(1) unsigned zerofill NOT NULL,
  `changed`				date NOT NULL default '0000-00-00',
  `created`				date NOT NULL default '0000-00-00',
  UNIQUE KEY `uk_user2affiliate` (`user_id`,`affi_id`),
  CONSTRAINT `fk_1_user2affiliate` FOREIGN KEY (`user_id`) REFERENCES `mm_user` (`user_id`),
  CONSTRAINT `fk_2_user2affiliate` FOREIGN KEY (`affi_id`) REFERENCES `mm_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='';

-- --------------------------------------------------------





	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ASCENSION ISLAND', 'AC');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('AFGHANISTAN', 'AF');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ALBANIA', 'AL');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ALGERIA', 'DZ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ANDORRA', 'AD');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ANGOLA', 'AO');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ANGUILLA', 'AI');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ANTARCTICA', 'AQ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ANTIGUA AND BARBUDA', 'AG');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ARGENTINA', 'AR');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ARMENIA', 'AM');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ARUBA', 'AW');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('AUSTRALIA', 'AU');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('AUSTRIA', 'AT');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('AZERBAIJAN', 'AZ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`,`language`,`currency`) VALUES ('BAHAMAS', 'BS', 'EN', 'USD');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BAHRAIN', 'BH');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BANGLADESH', 'BD');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BARBADOS', 'BB');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BELARUS', 'BY');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BELGIUM', 'BE');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BELIZE', 'BZ');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BENIN', 'BJ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BERMUDA', 'BM');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BHUTAN', 'BT');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BOLIVIA', 'BO');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BOSNIA AND HERZEGOVINA', 'BA');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BOTSWANA', 'BW');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BOUVET ISLAND', 'BV');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BRAZIL', 'BR');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BRITISH INDIAN OCEAN', 'IO');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BRITISH VIRGIN ISLANDS', 'VG');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BRUNEI DARUSSALAM', 'BN');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BULGARIA', 'BG');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BURKINA FASO', 'BF');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('BURUNDI', 'BI');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CAMBODIA', 'KH');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CAMEROON', 'CM');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CANADA', 'CA');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CAPE VERDE', 'CV');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CAYMAN ISLANDS', 'KY');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CENTRAL AFRICAN REPUBLIC', 'CF');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CHAD', 'TD');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CHILE', 'CL');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CHINA', 'CN');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CHRISTMAS ISLANDS', 'CX');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('COCOS ISLANDS', 'CC');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('COLOMBIA', 'CO');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('COMORAS', 'KM');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CONGO', 'CG');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CONGO (DEMOCRATIC REPUBLIC)', 'CD');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('COOK ISLANDS', 'CK');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('COSTA RICA', 'CR');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('COTE D IVOIRE', 'CI');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CROATIA', 'HR');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CUBA', 'CU');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CYPRUS', 'CY');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('CZECH REPUBLIC', 'CZ');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('DENMARK', 'DK');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('DJIBOUTI', 'DJ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('DOMINICA', 'DM');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('DOMINICAN REPUBLIC', 'DO');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('EAST TIMOR', 'TP');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ECUADOR', 'EC');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('EGYPT', 'EG');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('EL SALVADOR', 'SV');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('EQUATORIAL GUINEA', 'GQ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ESTONIA', 'EE');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ETHIOPIA', 'ET');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('FALKLAND ISLANDS', 'FK');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('FAROE ISLANDS', 'FO');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('FIJI', 'FJ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('FINLAND', 'FI');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('FRANCE', 'FR');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('FRANCE METROPOLITAN', 'FX');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('FRENCH GUIANA', 'GF');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('FRENCH POLYNESIA', 'PF');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('FRENCH SOUTHERN TERRITORIES', 'TF');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GABON', 'GA');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GAMBIA', 'GM');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GEORGIA', 'GE');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GERMANY', 'DE');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GHANA', 'GH');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GIBRALTER', 'GI');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GREECE', 'GR');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GREENLAND', 'GL');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GRENADA', 'GD');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GUADELOUPE', 'GP');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GUAM', 'GU');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GUATEMALA', 'GT');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GUINEA', 'GN');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GUINEA-BISSAU', 'GW');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('GUYANA', 'GY');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('HAITI', 'HT');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('HEARD & MCDONALD ISLAND', 'HM');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('HONDURAS', 'HN');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('HONG KONG', 'HK');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('HUNGARY', 'HU');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ICELAND', 'IS');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('INDIA', 'IN');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('INDONESIA', 'ID');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('IRAN', 'IR');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('IRAQ', 'IQ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('IRELAND', 'IE');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ISLE OF MAN', 'IM');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ISRAEL', 'IL');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ITALY', 'IT');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('JAMAICA', 'JM');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('JAPAN', 'JP');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('JORDAN', 'JO');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('KAZAKHSTAN', 'KZ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('KENYA', 'KE');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('KIRIBATI', 'KI');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('KOREA (SOUTH)', 'KP');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('KOREA (NORTH)', 'KR');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('KUWAIT', 'KW');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('KYRGYZSTAN', 'KG');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('LAOS', 'LA');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('LATVIA', 'LV');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('LEBANON', 'LB');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('LESOTHO', 'LS');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('LIBERIA', 'LR');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('LIBYA', 'LY');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('LIECHTENSTEIN', 'LI');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('LITHUANIA', 'LT');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('LUXEMBOURG', 'LU');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MACAO', 'MO');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MACEDONIA', 'MK');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MADAGASCAR', 'MG');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MALAWI', 'MW');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MALAYSIA', 'MY');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MALDIVES', 'MV');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MALI', 'ML');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MALTA', 'MT');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MARSHALL ISLANDS', 'MH');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MARTINIQUE', 'MQ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MAURITANIA', 'MR');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MAURITIUS', 'MU');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MAYOTTE', 'YT');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MEXICO', 'MX');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MICRONESIA', 'FM');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MOLDAVIA', 'MD');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MONACO', 'MC');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MONGOLIA', 'MN');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MONTENEGRO', 'ME');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MONTSERRAT', 'MS');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MOROCCO', 'MA');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MOZAMBIQUE', 'MZ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('MYANMAR', 'MM');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NAMIBIA', 'NA');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NAURU', 'NR');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NEPAL', 'NP');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NETHERLANDS (ANTILLES)', 'AN');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NETHERLANDS', 'NL');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NEW CALEDONIA', 'NC');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NEW ZEALAND', 'NZ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NICARAGUA', 'NI');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NIGER', 'NE');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NIGERIA', 'NG');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NIUE', 'NU');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NORFOLK ISLAND', 'NF');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NORTHERN MARIANA ISLANDS', 'MP');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('NORWAY', 'NO');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('OMAN', 'OM');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('PAKISTAN', 'PK');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('PALAU', 'PW');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('PANAMA', 'PA');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('PAPUA NEW GUINEA', 'PG');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('PARAGUAY', 'PY');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('PERU', 'PE');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('PHILLIPINES', 'PH');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('PITCAIRN', 'PN');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('POLAND', 'PL');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('PORTUGAL', 'PT');

	 INSERT INTO `mm_country` (`verbose`,`abbr`,`language`,`currency`) VALUES ('PUERTO RICO', 'PR', 'EN', 'USD');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('QATAR', 'QA');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('REUNION', 'RE');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ROMANIA', 'RO');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('RUSSIAN FEDERATION', 'RU');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('RWANDA', 'RW');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SAMOA', 'WS');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SAN MARINO', 'SM');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SAO TOME/PRINCIPE', 'ST');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SAUDI ARABIA', 'SA');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SENEGAL', 'SN');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SERBIA', 'SP');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SEYCHELLES', 'SC');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SIERRA LEONE', 'SL');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SINGAPORE', 'SG');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SLOVAKIA', 'SK');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SLOVENIA', 'SI');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SOLOMON ISLANDS', 'SB');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SOMALIA', 'SO');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SOMOA,GILBERT,ELLICE ISLANDS', 'AS');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SOUTH AFRICA', 'ZA');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SOUTH GEORGIA', 'GS');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SOVIET UNION', 'SU');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SPAIN', 'ES');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SRI LANKA', 'LK');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ST. HELENA', 'SH');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ST. KITTS AND NEVIS', 'KN');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ST. LUCIA', 'LC');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ST. PIERRE AND MIQUELON', 'PM');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ST. VINCENT & THE GRENADINES', 'VC');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SUDAN', 'SD');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SURINAME', 'SR');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SVALBARD AND JAN MAYEN', 'SJ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SWAZILAND', 'SZ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SWEDEN', 'SE');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SWITZERLAND', 'CH');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('SYRIA', 'SY');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('TAIWAN', 'TW');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('TAJIKISTAN', 'TJ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('TANZANIA', 'TZ');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('THAILAND', 'TH');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('TOGO', 'TG');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('TOKELAU', 'TK');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('TONGA', 'TO');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('TRINIDAD AND TOBAGO', 'TT');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('TUNISIA', 'TN');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('TURKEY', 'TR');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('TURKMENISTAN', 'TM');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('TURKS AND CALCOS ISLANDS', 'TC');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('TUVALU', 'TV');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('UGANDA', 'UG');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('UKRAINE', 'UA');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('UNITED ARAB EMIRATES', 'AE');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('UNITED KINGDOM (GREAT BRITAIN)', 'GB');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('UNITED KINGDOM', 'UK');
	 INSERT INTO `mm_country` (`verbose`,`abbr`,`language`,`currency`) VALUES ('UNITED STATES', 'US', 'EN', 'USD');

	 INSERT INTO `mm_country` (`verbose`,`abbr`,`language`,`currency`) VALUES ('UNITED STATES (ISLANDS)', 'UM', 'EN', 'USD');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('URUGUAY', 'UY');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('UZBEKISTAN', 'UZ');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('VANUATU', 'VU');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('VATICAN CITY', 'VA');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('VENEZUELA', 'VE');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('VIET NAM', 'VN');
	 INSERT INTO `mm_country` (`verbose`,`abbr`,`language`,`currency`) VALUES ('VIRGIN ISLANDS (USA)', 'VI', 'EN', 'USD');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('WALLIS AND FUTUNA ISLANDS', 'WF');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('WESTERN SAHARA', 'EH');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('YEMEN', 'YE');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('YUGOSLAVIA', 'YU');

	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ZAIRE', 'ZR');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ZAMBIA', 'ZM');
	 INSERT INTO `mm_country` (`verbose`,`abbr`) VALUES ('ZIMBABWE', 'ZW');







INSERT INTO `mm_gateway` (`gate_name`,`gate_link`,`test_link`,`test_acct`,`test_user`,`test_pass`,`test_card`,`test_type`,`test_plan`,`debug`) VALUES ('Symmetric Systems (VitalPay)','http://api.evenpay.com/api/v14/','http://api.evenpay.com/api/v14/','','','','','','',1);
INSERT INTO `mm_gateway` (`gate_name`,`gate_link`,`test_link`,`test_acct`,`test_user`,`test_pass`,`test_card`,`test_type`,`test_plan`,`debug`) VALUES ('MaxPayments (CurePay)','https://secure.curepay.com/api','https://secure.curepay.com/api','494155','dtm_api','coiporeo','4200000000000000','Purchase','02128',1);
INSERT INTO `mm_gateway` (`gate_name`,`gate_link`,`test_link`,`test_acct`,`test_user`,`test_pass`,`test_card`,`test_type`,`test_plan`,`debug`) VALUES ('Network Merchants (NMI)','https://secure.networkmerchants.com/api/transact.php','https://secure.networkmerchants.com/api/transact.php','demo','demo','password','4111111111111111','sale','',1);
INSERT INTO `mm_gateway` (`gate_name`,`gate_link`,`test_link`,`test_acct`,`test_user`,`test_pass`,`test_card`,`test_type`,`test_plan`,`debug`) VALUES ('Wirecard','https://c3-test.wirecard.com/secure/ssl-gateway','https://c3-test.wirecard.com/secure/ssl-gateway','56500','56500','TestXAPTER','4200000000000000','Purchase','',1);

INSERT INTO `mm_language` (`abbr`,`dialect`) VALUES('EN','English');
INSERT INTO `mm_currency` (`abbr`,`symbol`,`exchange`) VALUES('USD','USD','1.00');

INSERT INTO `mm_user` (`user_id`,`referer`,`ordr_id`,`advertiser`,`user_first`,`user_last`,`user_address`,`user_city`,`user_zip`,`user_state`,`user_country`,`user_phone`,`user_email`,`username`,`password`,`created`) VALUES (12345,12345,1234567,1,'Waynard','Schmidt','address','city','zip','UT','US','000-000-0000','waynard@pruloo.com','waynard','schmidt',NOW());

INSERT INTO `mm_user_product` (`prod_id`,`user_id`,`product_name`,`product_desc`,`product_sku`,`product_cost`,`created`) VALUES (1234567,12345,'Resell the Pruloo CRM!','Your first product, is the Pruloo CRM itself! Get more marketers to join the Pruloo community to grow and manage their businesses..','5999999','74.95',NOW());
INSERT INTO `mm_user_product` (`prod_id`,`user_id`,`product_name`,`product_desc`,`product_sku`,`product_cost`,`created`) VALUES (1234568,12345,'Google Activation','Google Activation','6000000','0',NOW());
INSERT INTO `mm_user_product` (`prod_id`,`user_id`,`product_name`,`product_desc`,`product_sku`,`product_cost`,`created`) VALUES (1234569,12345,'Google 1st Order','Google 1st Order','6000001','0',NOW());
INSERT INTO `mm_user_product` (`prod_id`,`user_id`,`product_name`,`product_desc`,`product_sku`,`product_cost`,`created`) VALUES (1234569,12345,'Google 1st Order','Google 1st Order','6000001','0',NOW());

INSERT INTO `mm_user_offer` (`offr_id`,`prod_id`,`user_id`,`offer_name`,`offer_cost`,`recur_term`,`trial_cost`,`trial_term`,`created`) VALUES (1234567,1234567,12345,'Pruloo reseller offer..','99.95','30','8.97','3',NOW());
INSERT INTO `mm_user_offer` (`offr_id`,`prod_id`,`user_id`,`offer_name`,`offer_cost`,`recur_term`,`trial_cost`,`trial_term`,`created`) VALUES (1234568,1234568,12345,'Google Activation','78.14','30','1.97','3',NOW());
INSERT INTO `mm_user_offer` (`offr_id`,`prod_id`,`user_id`,`offer_name`,`offer_cost`,`recur_term`,`trial_cost`,`trial_term`,`created`) VALUES (1234569,1234569,12345,'Google 1st Order','78.14','30','0','0',NOW());

INSERT INTO `mm_user_campaign` (`camp_id`,`offr_id`,`user_id`,`campaign`,`created`) VALUES (1234567,1234567,12345,'Pruloo reseller campaign..',NOW());
INSERT INTO `mm_user_campaign` (`camp_id`,`offr_id`,`user_id`,`campaign`,`created`) VALUES (1234568,1234568,12345,'Google activation campaign',NOW());
INSERT INTO `mm_user_campaign` (`camp_id`,`offr_id`,`user_id`,`campaign`,`created`) VALUES (1234569,1234569,12345,'Google 1st order campaign',NOW());

INSERT INTO `mm_user_order` (`ordr_id`,`camp_id`,`user_id`,`bill_first`,`bill_last`,`bill_email`,`sale_date`,`sale_time`) VALUES (1234567,1234567,12345,'Waynard','Schmidt','waynard@pruloo.com', CURDATE(), CURTIME());

INSERT INTO `campaign2country` (`camp_id`,`country`) VALUES (1234567,'US');
INSERT INTO `campaign2country` (`camp_id`,`country`) VALUES (1234568,'US');
INSERT INTO `campaign2country` (`camp_id`,`country`) VALUES (1234569,'US');
