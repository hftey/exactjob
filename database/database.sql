CREATE DATABASE  `exact_job` ;



CREATE TABLE IF NOT EXISTS `ACLUsers` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `ACLRole` varchar(255) NOT NULL,
  `Name` varchar(255) default NULL,
  `Username` varchar(64) NOT NULL,
  `Password` varchar(100) default NULL,
  `Email` varchar(255) NOT NULL,
  `Active` tinyint(4) NOT NULL default '1',
  `LastLogin` datetime NOT NULL,
  `UserCreated` varchar(64) NOT NULL,
  `DateCreated` datetime default NULL,
  `UserModified` varchar(64) NOT NULL,
  `DateModified` datetime default NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


INSERT INTO `ACLUsers` (`ID`, `ACLRole`, `Name`, `Username`, `Password`, `Email`, `Active`, `LastLogin`, `UserCreated`, `DateCreated`, `UserModified`, `DateModified`) VALUES
(1, 'admin', 'System Admin', 'admin', '67164a34f6a9ec4d82b87184d33e8ca6', 'admin@email.com', 1, '0000-00-00 00:00:00', '', NULL, '', NULL );


CREATE TABLE IF NOT EXISTS `ACLRole` (
  `ID` int(11) NOT NULL auto_increment,
  `Name` varchar(30) NOT NULL,
  `Description` varchar(100) NOT NULL,
  `ParentName` varchar(255) default NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


INSERT INTO `ACLRole` (`Name`, `Description`, `ParentName`) VALUES
('guest', 'Public User',  NULL),
('admin', 'Administrator', "guest");


CREATE TABLE IF NOT EXISTS `ACLPriviledges` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(255) default NULL,
  `Description` varchar(255) default NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `ACLPriviledges` (`ID`, `Name`, `Description`) VALUES
(1, 'view', 'view');


CREATE TABLE IF NOT EXISTS `ACLResources` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(255) default NULL,
  `Description` varchar(255) default NULL, 
  `Category` varchar(255) default NULL,
  `ParentName` varchar(255) default NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `ACLResources` (`ID`, `Name`, `Description`, `Category`, `ParentName`) VALUES
(1, 'public', 'Public Sections', 'Public', NULL),
(2, 'private', 'Admin Sections', 'Private', NULL);


CREATE TABLE IF NOT EXISTS `ACLMap` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Role` varchar(255) default NULL,
  `Resources` varchar(255) default NULL, 
  `Priviledges` varchar(255) default NULL,
  `Allow` int(1) default 1,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY (`Role`,`Resources`,`Priviledges`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `ACLMap` (`ID`, `Role`, `Resources`, `Priviledges`, `Allow`) VALUES
(1, 'guest', 'public', 'view', 1),
(2, 'admin', 'public', 'view', 1),
(3, 'admin', 'private', 'view', 1);


CREATE TABLE IF NOT EXISTS `SYSLog` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) DEFAULT NULL,
  `role` varchar(32) DEFAULT NULL,
  `logtime` DATETIME DEFAULT NULL,
  `zendmodule` varchar(32) DEFAULT NULL,
  `zendcontroller` varchar(32) DEFAULT NULL,
  `zendaction` varchar(32) DEFAULT NULL,
  `postdata` BLOB DEFAULT NULL,
  `getdata` varchar(64) DEFAULT NULL,
  `IP` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `Notification` (
  `ID` int(11) NOT NULL auto_increment,
  `ACLUserID` int(11) DEFAULT NULL,
  `Message` text DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `MessageDate` datetime DEFAULT NULL,
  `MessageRead` int(1) DEFAULT 0,
  `Triggerby` int(11) DEFAULT NULL,
  `MessageReadDate` datetime DEFAULT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `Job` (
  `ID` int(11) NOT NULL auto_increment,
  `JobNo` varchar(32) DEFAULT NULL,
  `JobType` varchar(8) DEFAULT NULL,
  `CustomerPOReceivedDate` date DEFAULT NULL,
  `CustomerName` varchar(255) DEFAULT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `Items` varchar(2048) DEFAULT NULL,
  `Completed` int(2) DEFAULT 0,
  `CompletedDate` date DEFAULT NULL,
  `CreatedOn` datetime DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `JobSales` (
  `ID` int(11) NOT NULL auto_increment,
  `JobID` int(11) DEFAULT NULL,
  `CustomerPOReceivedDate` date DEFAULT NULL,
  `EOGSTSBPO` varchar(128) DEFAULT NULL,
  `CustomerPO` varchar(128) DEFAULT NULL,
  `SalesCurrency` varchar(32) DEFAULT NULL,
  `SalesCurrencyID` int(11) DEFAULT NULL,
  `SalesPriceExchangeRate` float(12,2) DEFAULT NULL,
  `SalesPrice` float(14,2) DEFAULT NULL,
  `SalesTerms` varchar(32) DEFAULT NULL,
  `SalesTermsID` int(11) DEFAULT NULL,
  `SalesInspReportNo` varchar(128) DEFAULT NULL,
  `SalesOrderAckNo` varchar(128) DEFAULT NULL,
  `SalesExpDate` date DEFAULT NULL,
  `SalesReadyDate` date DEFAULT NULL,
  `SalesInvoiceDate` date DEFAULT NULL,
  `SalesInvoiceNo` varchar(32) DEFAULT NULL,
  `SalesDO` varchar(32) DEFAULT NULL,
  `EOGSTSBDO` varchar(32) DEFAULT NULL,
  `ServiceReportNo` varchar(32) DEFAULT NULL,
  `DrawingApprovedDate` date DEFAULT NULL,
  `SalesPersonID` int(11) DEFAULT NULL,
  `Remarks` varchar(2048) DEFAULT NULL,
  `CreatedOn` datetime DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `JobPurchase` (
  `ID` int(11) NOT NULL auto_increment,
  `JobID` int(11) DEFAULT NULL,
  `PONo` varchar(128) DEFAULT NULL,
  `PODate` date DEFAULT NULL,
  `POFaxedDate` date DEFAULT NULL,
  `SupplierName` varchar(128) DEFAULT NULL,
  `SupplierCode` varchar(32) DEFAULT NULL,
  `SupplierID` int(11) DEFAULT NULL,
  `PurchaseCurrency` varchar(32) DEFAULT NULL,
  `PurchaseCurrencyID` int(11) DEFAULT NULL,
  `PurchasePrice` float(14,2) DEFAULT NULL,
  `PurchasePriceExchangeRate` float(14,2) DEFAULT NULL,
  `PurchaseTerms` varchar(32) DEFAULT NULL,
  `PurchaseTermsID` int(11) DEFAULT NULL,
  `PurchaseAckNO` varchar(32) DEFAULT NULL,
  `PurchaseShippingDate` date DEFAULT NULL,
  `PurchaseShippingActualDate` date DEFAULT NULL,
  `PurchaseInvoiceNo` varchar(32) DEFAULT NULL,
  `PurchasePaymentDate` date DEFAULT NULL,
  `CreatedOn` datetime DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `JobPurchaseDelivery` (
  `ID` int(11) NOT NULL auto_increment,
  `JobID` int(11) DEFAULT NULL,
  `JobPurchaseID` int(11) DEFAULT NULL,
  `DeliveryAWB` varchar(64) DEFAULT NULL,
  `DeliveryReceivedDate` date DEFAULT NULL,
  `DutyTax` float(14,2) DEFAULT NULL,
  `FreightCost` float(14,2) DEFAULT NULL,
  `Remarks` varchar(2048) DEFAULT NULL,
  `CreatedOn` datetime DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `Customers` (
  `ID` int(11) NOT NULL auto_increment,
  `Code` varchar(32) DEFAULT NULL,
  `Name` varchar(255) NOT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  `FaxNo` varchar(50) DEFAULT NULL,
  `PaymentTerms` varchar(50) DEFAULT NULL,
  `Email` varchar(125) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `Supplier` (
  `ID` int(11) NOT NULL auto_increment,
  `Code` varchar(32) DEFAULT NULL,
  `Name` varchar(255) NOT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  `FaxNo` varchar(50) DEFAULT NULL,
  `PaymentTerms` varchar(50) DEFAULT NULL,
  `Email` varchar(125) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `JobDocuments` (
  `ID` int(11) NOT NULL auto_increment,
  `JobID` int(12) DEFAULT NULL,
  `JobPurchaseID` int(12) DEFAULT NULL,
  `JobSalesID` int(12) DEFAULT NULL,
  `DocType` varchar(32) DEFAULT NULL,
  `Name` varchar(128) NOT NULL,
  `Description` varchar(5000) NOT NULL,
  `DateSubmitted` DateTime DEFAULT NULL,
  `SubmittedBy` int(12) DEFAULT NULL,
  `FilePath` varchar(255) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `JobClaims` (
  `ID` int(11) NOT NULL auto_increment,
  `JobID` int(12) DEFAULT NULL,
  `ClaimDate` date DEFAULT NULL,
  `ClaimDescription` varchar(2048) DEFAULT NULL,
  `ClaimCurrency` varchar(32) NOT NULL,
  `ClaimCurrencyExchangeRate` float(14,2) DEFAULT NULL,
  `ClaimAmount` float(14,2) DEFAULT NULL,
  `ClaimUserID` int(12) DEFAULT NULL,
  `DateSubmitted` DateTime DEFAULT NULL,
  `SubmittedBy` int(12) DEFAULT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `Currency` (
  `ID` int(11) NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL,
  `Code` varchar(32) NOT NULL,
  `Rate` float(12,2) DEFAULT 1,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO Currency SET ID=1, Name="Malaysia Ringgit", Code="RM", Rate="1";
INSERT INTO Currency SET ID=2, Name="US Dollar", Code="USD", Rate="";
INSERT INTO Currency SET ID=3, Name="Singapore Dollar", Code="SGD", Rate="";
INSERT INTO Currency SET ID=4, Name="Euro", Code="Euro", Rate="";
INSERT INTO Currency SET ID=5, Name="British Pound", Code="GBP", Rate="";
INSERT INTO Currency SET ID=6, Name="Japanese Yen", Code="JPY", Rate="";
INSERT INTO Currency SET ID=7, Name="China Renminbi", Code="RMB", Rate="";





CREATE TABLE IF NOT EXISTS `Terms` (
  `ID` int(11) NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL,
  `Code` varchar(32) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO Terms SET ID=1, Name="Cash On Delivery", Code="COD";
INSERT INTO Terms SET ID=2, Name="30 Days", Code="30 Days";


/* 2016-04-23 */

CREATE TABLE IF NOT EXISTS `PublicHoliday` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `PHDate` date NOT NULL,
  `PHDescription` varchar(1024) default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


/* 2016-05-07 */

CREATE TABLE IF NOT EXISTS `JobPayments` (
  `ID` int(11) NOT NULL auto_increment,
  `JobID` int(12) DEFAULT NULL,
  `PaymentReceive` int(2) DEFAULT 1,
  `PaymentDate` date DEFAULT NULL,
  `PaymentDescription` varchar(2048) DEFAULT NULL,
  `PaymentCurrency` varchar(32) DEFAULT NULL,
  `PaymentCurrencyID` int(11) DEFAULT NULL,
  `PaymentCurrencyExchangeRate` float(14,2) DEFAULT NULL,
  `PaymentAmount` float(14,2) DEFAULT NULL,
  `PaymentAmountRM` float(14,2) DEFAULT NULL,
  `PaymentInvoice` varchar(255) DEFAULT NULL,
  `JobDocumentID` int(11) DEFAULT NULL,
  `EntryDate` DateTime DEFAULT NULL,
  `EntryBy` int(12) DEFAULT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

ALTER TABLE Currency Add LastModified TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP; 


/* 2016-05-11 */

CREATE TABLE IF NOT EXISTS `ReportObjectives` (
  `ID` int(11) NOT NULL auto_increment,
  `ObjectiveYear` int(4) NOT NULL,
  `ObjectiveValue` float(12,2) NOT NULL,
  `ObjectiveType` enum('deliveryobjective','purchaseobjective','purchaseobjectivedays','latedelivery','drawingapproval','drawingapprovalcases' ) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

/* ----- UPDATE USERS ------- */

TRUNCATE TABLE `ACLPriviledges`;
INSERT INTO `ACLPriviledges` (`ID`, `Name`, `Description`) VALUES
(1, 'view', 'view'),
(2, 'edit', 'edit'),
(3, 'add', 'add'),
(4, 'delete', 'delete');


TRUNCATE TABLE `ACLRole`;
INSERT INTO `ACLRole` (`ID`, `Name`, `Description`, `ParentName`) VALUES
(1, 'User', 'Normal User', 'NULL'),
(4, 'AdminSystem', 'System Administrator', 'User'),
(3, 'Admin', 'Administrator', 'User'),
(5, 'Sales', 'Sales and Manager', 'User');


TRUNCATE TABLE `ACLResources`;
INSERT INTO `ACLResources` (`ID`, `Name`, `Description`, `Category`, `ParentName`) VALUES
(1, 'public', 'Public Sections', 'Public', NULL),
(2, 'private', 'Admin Sections', 'Private', NULL);


TRUNCATE TABLE `ACLMap`;
INSERT INTO `ACLMap` (`ID`, `Role`, `Resources`, `Priviledges`, `Allow`) VALUES
(1, 'User', 'public', 'view', 1),
(2, 'AdminSystem', 'public', 'view', 1),
(3, 'AdminSystem', 'private', 'view', 1),
(4, 'Admin', 'public', 'view', 1),
(5, 'Admin', 'private', 'view', 1),
(6, 'Sales', 'public', 'view', 1),
(7, 'Sales', 'private', 'view', 1),
(8, 'User', 'private', 'view', 1);

TRUNCATE TABLE `ACLUsers`;
INSERT INTO `ACLUsers` (`ID`, `ACLRole`, `Name`, `Username`, `Password`, `Email`, `Active`, `LastLogin`, `UserCreated`, `DateCreated`, `UserModified`, `DateModified`) VALUES
(7, 'AdminSystem', 'David Tey', 'davidtey', '4cf681a06a10bc451026bdcb6f1c2edc', 'david.tey@exactanalytical.com.my', 1, '2016-05-10 16:16:32', 'system_admin', '2014-04-24 16:17:10', '', NULL),
(8, 'Sales', 'Rahimah', 'rahimah', '4de1594cb818a4f7b9bdd8a7e42def5a', 'rahimah@exactanalytical.com.my', 1, '2016-04-19 06:16:41', 'davidtey', '2014-04-24 16:32:23', '', NULL),
(3, 'AdminSystem', 'System Admin', 'system_admin', '67164a34f6a9ec4d82b87184d33e8ca6', 'system_admin@email.com', 1, '2016-05-11 16:12:41', 'admin', '2014-03-08 23:45:19', '', NULL),
(4, 'Admin', 'Misbah', 'misbah', '477ba08a4d40ab811c55c410b7f6fb4c', 'misbah.rahman@exactanalytical.com.my', 1, '2016-05-11 16:02:01', 'system_admin', '2014-04-01 14:56:49', '', NULL),
(5, 'Admin', 'Kimmy Yap', 'kimmy', '5d010a42335f298f8f227833a56ad860', 'kimmy.yap@exactanalytical.com.my', 1, '2016-05-10 10:22:17', 'system_admin', '2014-04-01 14:58:05', '', NULL),
(6, 'Sales', 'Kew Kai Li', 'kaili', '1c12fc7b92300f4c08b6750c8bca2acd', 'kaili.kew@exactanalytical.com.my', 1, '2016-05-05 10:54:06', 'system_admin', '2014-04-03 13:50:02', '', NULL),
(9, 'User', 'Muhammad Zaid', 'zaid', '2810f971a66974e2cf6a444a98ddfa2c', 'muhammad.zaid@exactanalytical.com.my', 1, '2016-04-25 16:40:51', 'davidtey', '2014-04-24 16:35:33', '', NULL),
(10, 'User', 'Chin Boon King', 'boonking', '0a86afcab54bbb5867394bc3c637c6ee', 'boonking.chin@exactanalytical.com.my', 1, '2016-05-11 14:22:59', 'davidtey', '2014-04-24 16:44:19', '', NULL),
(11, 'User', 'Hafiz Aizad', 'hafiz', '26bafe86eca1056adfbeaa90c2229b1b', 'hafiz.aizad@exactanalytical.com.my', 1, '2016-04-06 11:03:24', 'davidtey', '2014-04-24 16:47:01', '', NULL),
(12, 'User', 'Daniel Sii', 'daniel', '366091004f79eda708f236e14d4320d5', 'daniel.sii@exactanalytical.com.my', 1, '2016-04-06 09:47:56', 'davidtey', '2014-04-24 16:48:30', '', NULL),
(38, 'User', 'kevin chong', 'kevin', '25d5b9185fd31979e73fa83fa2e9b93b', 'kevin.chong@exactanalytical.com.my', 1, '2015-04-21 09:54:15', 'davidtey', '2015-04-15 13:54:51', '', NULL),
(14, 'User', 'Ng Kuan Seng', 'kuanseng', '854bc8c4ff8738c9debafaabf2e8a5af', 'kuanseng.ng@exactanalytical.com.my', 1, '2016-03-16 15:09:49', 'davidtey', '2014-04-24 16:52:28', '', NULL),
(15, 'User', 'Nurul Hilaliah', 'hilaliah', '40aae02bfa3d503955ecf627dd8830ae', 'nurul.hilaliah@exactanalytical.com.my', 1, '2014-10-28 14:01:48', 'davidtey', '2014-04-24 16:54:16', '', NULL),
(16, 'User', 'Fazillah Zulkeflee', 'fazillah', 'c3b5a175c43bd47ad0adfa19ea6c1f34', 'fazillah.zulkeflee@exactanalytical.com.my', 1, '2014-10-27 11:56:25', 'davidtey', '2014-04-24 16:57:10', '', NULL),
(17, 'User', 'Paul Pereira', 'paul', 'e9c653f5cf180d4ec49c0f9d4fdad80c', 'paul.pereira@exactanalytical.com.my', 1, '2014-08-11 16:25:06', 'davidtey', '2014-04-24 16:58:40', '', NULL),
(18, 'User', 'Mohd Firdaus', 'firdaus', 'ceb7109665f29a3c28cea1aa803b44f8', 'mohd.firdaus@exactanalytical.com.my', 1, '0000-00-00 00:00:00', 'davidtey', '2014-04-24 17:00:15', '', NULL),
(37, 'User', 'Nik Syairah', 'syairah', 'c63ce08b17b8d5897f233a31e78eccd6', 'nik.syairah@exactanalytical.com.my', 1, '2015-12-18 09:00:34', 'davidtey', '2015-04-03 16:41:54', '', NULL),
(28, 'User', 'Nur Fazilah', 'nurfazilah', '8b7f3aa66d6db3f4be4205e0b5ace9f2', 'nur.fazilah@exactanalytical.com.my', 1, '2014-11-03 18:40:23', 'davidtey', '2014-07-24 17:24:44', '', NULL),
(21, 'User', 'Omar Salim', 'omar', 'fc4d477402d75c8cf7c37e07d68ada9c', 'omar.salim@exactanalytical.com.my', 1, '2015-06-25 10:07:34', 'davidtey', '2014-04-24 17:04:56', '', NULL),
(22, 'User', 'Shyful Nizam', 'shyful', '894430b5f896b870596811771de3f00f', 'shyful.nizam@exactanalytical.com.my', 1, '2016-03-31 16:06:32', 'davidtey', '2014-04-24 17:06:24', '', NULL),
(23, 'Sales', 'Sales', 'Sales', '67164a34f6a9ec4d82b87184d33e8ca6', 'sales@email.com', 1, '2014-11-18 17:53:39', 'system_admin', '2014-04-30 21:30:37', '', NULL),
(24, 'User', 'Normal User', 'user', '67164a34f6a9ec4d82b87184d33e8ca6', 'user@email.com', 1, '2014-11-18 21:36:12', 'system_admin', '2014-04-30 21:56:48', '', NULL),
(25, 'Admin', 'Admin', 'admin', '67164a34f6a9ec4d82b87184d33e8ca6', 'admin@email.com', 1, '2015-01-25 12:06:25', 'system_admin', '2014-04-30 22:06:59', '', NULL),
(31, 'User', 'Nurul Syakirin', 'syakirin', '91288958e7022b4f92021be152b93789', 'nurul.syakirin@exactanalytical.com.my', 1, '2016-05-11 09:00:20', 'davidtey', '2014-09-02 10:55:50', '', NULL),
(27, 'User', 'Jeffrey Gan', 'jeffrey', 'd1fae8f46a1ffe5bae9de04023966500', 'jeffrey.gan@exactanalytical.com.my', 1, '2015-09-03 12:15:41', 'davidtey', '2014-05-26 22:06:01', '', NULL),
(29, 'User', 'Lai Guo Yi', 'guoyi', 'a8efacc9198d19ed54d055c91f4e75ad', 'guoyi.lai@exactanalytical.com.my', 1, '2014-10-20 11:08:47', 'davidtey', '2014-07-26 13:24:52', '', NULL),
(30, 'Sales', 'Yong Shwu Foong', 'shwufoong', '56a8fdaa5ffce80bb29c474758952795', 'shwufoong.yong@exactanalytical.com.my', 1, '2014-09-24 16:32:56', 'davidtey', '2014-08-29 14:40:30', '', NULL),
(32, 'User', 'Priscilla Sii', 'priscilla', '7649441c48923176c6b9057a376001b7', 'priscilla.sii@exactanalytical.com.my', 1, '2016-05-04 10:17:12', 'davidtey', '2014-09-04 11:51:30', '', NULL),
(33, 'Sales', 'Susan Lim', 'susan', 'c04113c1a268a890b919a5acce807a38', 'susan.lim@exactanalytical.com.my', 1, '2014-10-07 14:55:05', 'davidtey', '2014-10-07 14:54:45', '', NULL),
(34, 'User', 'Mohd Haizam', 'haizam', '78df53c1b6b37f7ed690e57ceb9e887f', 'mohd.haizam@exactanalytical.com.my', 1, '0000-00-00 00:00:00', 'davidtey', '2014-10-16 23:01:12', '', NULL),
(35, 'User', 'Ting wei Chuon', 'weichuon', 'c09bdbe0015c3569ddda3b058eecf6e8', 'weichuon.ting@exactanalytical.com.my', 1, '2014-11-07 17:38:20', 'davidtey', '2014-11-07 16:46:17', '', NULL),
(43, 'AdminSystem', 'Jonathan', 'jontey88', 'cefa5ea8c4b9fe617847e5bfc35e999c', 'jontey88@gmail.com', 1, '2015-12-14 05:33:01', 'davidtey', '2015-11-21 13:20:37', '', NULL),
(44, 'User', 'NgaiFoong Choy', 'choy', '65755bdc3d12d80503e3ac3ea6937a74', 'ngaifoong.choy@exactanalytical.com.my', 1, '2016-03-10 10:54:47', 'davidtey', '2016-01-04 16:55:23', '', NULL),
(47, 'User', 'aizat farhan', 'aizat', 'd5de589d84d9d9f9696c370de6608be9', 'aizat.farhan@exactanalytical.com.my', 1, '0000-00-00 00:00:00', 'davidtey', '2016-05-05 19:18:26', '', NULL),
(41, 'User', 'Jurinawati Suwadi', 'jurinawati', '5b0ae0d94c9b65804888aea4cfe06df2', 'jurinawati@exactanalytical.com.my', 1, '2016-05-09 09:57:18', 'davidtey', '2015-07-23 14:29:10', '', NULL),
(42, 'User', 'Fayyadah Ashikin', 'fayyadah', '3cc72f5d98d647930c9dc553eaa44180', 'fayyadah.ashikin@exactanalytical.com.my', 1, '2016-05-10 14:01:24', 'davidtey', '2015-07-23 14:30:01', '', NULL),
(45, 'User', 'Beatrice Hannah', 'beatrice', '120911f88fa5efa33dbd34e362fc5d95', 'beatrice.hannah@exactanalytical.com.my', 1, '2016-03-09 08:49:50', 'davidtey', '2016-02-04 18:15:18', '', NULL),
(46, 'User', 'Wong Ming Chung', 'mingchung', 'd3aac8fa1c3fdcfff871aba4230b7ffd', 'mingchung.wong@exactanalytical.com.my', 1, '2016-03-16 17:40:45', 'davidtey', '2016-03-16 15:27:05', '', NULL),
(48, 'User', 'mohd eshar', 'eshar', '39176789a3b55fa14f7dec6945277c10', 'mohd.eshar@exactanalytical.com.my', 1, '2016-05-05 21:05:26', 'davidtey', '2016-05-05 19:19:33', '', NULL),
(49, 'User', 'noor fadhilah', 'fadhilah', 'f3907cd3f8d1f8aab4ec354dce0a4674', 'noor.fadhilah@exactanalytical.com.my', 1, '0000-00-00 00:00:00', 'davidtey', '2016-05-06 17:15:02', '', NULL);

/* 2016-06-11 */

ALTER TABLE `Job` ADD Cancelled int(2) default 0;
ALTER TABLE `Job` ADD PrincipleName varchar(255) default NULL;

/* 20 July 2016 - Apply to ExactSys, ExactJob and Inventory */

ALTER TABLE `Job` ADD `Closed` int(2) DEFAULT 0;
ALTER TABLE `Job` ADD `ClosedDate` date DEFAULT NULL;

/* 06 Aug 2016 */
ALTER TABLE `Customers` ADD `CreditLimit` varchar(255) default NULL;

/* 07 Sept 2016 */
ALTER TABLE `ReportObjectives` CHANGE `ObjectiveType` `ObjectiveType` enum('deliveryobjective','purchaseobjective','purchaseobjectivedays','latedelivery','drawingapproval','drawingapprovalcases','purchaseobjective2','purchaseobjectivedays2') NOT NULL;

/* 30 Sept 2017 */
ALTER TABLE `ACLUsers` ADD `AbbrName` varchar(5) default NULL;


/* --------------*/
/* 12 March 2018 */
ALTER TABLE `Customers` ADD `Attn` varchar(255) default NULL;

/* 23 March 2018 */
ALTER TABLE `JobSales` ADD INDEX(`JobID`);
ALTER TABLE `JobPurchase` ADD INDEX(`JobID`);
ALTER TABLE `JobSales` ADD INDEX(`SalesCurrencyID`);
ALTER TABLE `JobPurchase` ADD INDEX(`PurchaseCurrencyID`);

ALTER TABLE `Job` ADD INDEX(`CreatedBy`);
ALTER TABLE `Job` ADD INDEX(`CustomerID`);

/* 01 Nov 2018 */
ALTER TABLE `Job` ADD InitialGrossMargin float(14,2) default NULL;

/* 18 Feb 2019 */
ALTER TABLE `JobPurchase` ADD PartialDelivery int(2) default 0;
ALTER TABLE `JobPurchaseDelivery` ADD PartialDelivery int(2) default 0;
ALTER TABLE `JobPurchaseDelivery` ADD PartialDeliveryAmount float(14,2) default NULL;

ALTER TABLE `JobSales` ADD PartialDelivery int(2) default 0;


CREATE TABLE IF NOT EXISTS `JobSalesDelivery` (
  `ID` int(11) NOT NULL auto_increment,
  `JobID` int(11) DEFAULT NULL,
  `JobSalesID` int(11) DEFAULT NULL,
  `SalesReadyDatePartial` date DEFAULT NULL,
  `PartialDeliveryAmount` float(14,2) DEFAULT NULL,
  `Remarks` varchar(2048) DEFAULT NULL,
  `CreatedOn` datetime DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


/* 1 Sept 2019 */
ALTER TABLE `JobPurchase` ADD PurchaseDONo varchar(32) default NULL;
ALTER TABLE `JobPurchaseDelivery` ADD PurchaseDONo varchar(32) default NULL;

ALTER TABLE `JobDocuments` ADD JobPurchaseDeliveryID int(12) default NULL;

/* 24 Jan 2020 */
ALTER TABLE `JobPurchase` ADD PurchasePLNo varchar(32) default NULL;
ALTER TABLE `JobPurchaseDelivery` ADD PurchasePLNo varchar(32) default NULL;

-- 20210321 -
ALTER TABLE `Job` ADD `ExchangeProgram` int(2) default 0;

ALTER TABLE `Job` ADD `ExchangePreviousJobID` int(2) default NULL;
ALTER TABLE `Job` ADD `ExchangeReturnDate` Date default NULL;
