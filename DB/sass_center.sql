-- MySQL dump 10.13  Distrib 5.7.35, for Linux (x86_64)
--
-- Host: localhost    Database: sass_center
-- ------------------------------------------------------
-- Server version	5.7.35-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `administrator`
--

DROP TABLE IF EXISTS `administrator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `administrator` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wechat_openid` char(28) DEFAULT NULL,
  `role_id` int(10) unsigned NOT NULL COMMENT '角色',
  `name` varchar(20) NOT NULL COMMENT '用户名',
  `nickname` varchar(50) DEFAULT NULL,
  `password` char(32) DEFAULT NULL COMMENT '用户密码',
  `is_block` tinyint(1) DEFAULT '0' COMMENT '封号',
  `is_deleted` tinyint(1) DEFAULT '0',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrator`
--

LOCK TABLES `administrator` WRITE;
/*!40000 ALTER TABLE `administrator` DISABLE KEYS */;
INSERT INTO `administrator` VALUES (1,NULL,1,'administrator','超级管理员','53b8a0f7cc9e88016fd4c6f0f01e062c',0,0,'2017-07-13 16:56:06','2017-08-01 09:21:58');
/*!40000 ALTER TABLE `administrator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `administrator_role`
--

DROP TABLE IF EXISTS `administrator_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `administrator_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '角色名',
  `rule_map` varchar(2000) DEFAULT NULL COMMENT '规则数据',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrator_role`
--

LOCK TABLES `administrator_role` WRITE;
/*!40000 ALTER TABLE `administrator_role` DISABLE KEYS */;
INSERT INTO `administrator_role` VALUES (1,'超级管理员',NULL,'2017-07-13 17:24:32','2017-08-09 07:00:57');
/*!40000 ALTER TABLE `administrator_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuration`
--

DROP TABLE IF EXISTS `configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuration` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuration`
--

LOCK TABLES `configuration` WRITE;
/*!40000 ALTER TABLE `configuration` DISABLE KEYS */;
/*!40000 ALTER TABLE `configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_manager`
--

DROP TABLE IF EXISTS `customer_manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_manager` (
  `tenantId` int(10) unsigned NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `mobile` char(11) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tenantId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='客服经理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_manager`
--

LOCK TABLES `customer_manager` WRITE;
/*!40000 ALTER TABLE `customer_manager` DISABLE KEYS */;
INSERT INTO `customer_manager` VALUES (1,'','','2021-12-02 14:11:17','2021-12-02 14:11:17');
/*!40000 ALTER TABLE `customer_manager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operation_log`
--

DROP TABLE IF EXISTS `operation_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operation_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event` enum('CREATE','DELETE','UPDATE') DEFAULT NULL,
  `ip` varchar(15) NOT NULL COMMENT 'IP地址',
  `action` varchar(255) DEFAULT NULL COMMENT '行为',
  `data` text NOT NULL COMMENT '数据',
  `administrator_id` varchar(50) DEFAULT NULL COMMENT '操作人ID',
  `administrator_name` varchar(50) DEFAULT NULL COMMENT '操作人姓名',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='操作日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operation_log`
--

LOCK TABLES `operation_log` WRITE;
/*!40000 ALTER TABLE `operation_log` DISABLE KEYS */;
INSERT INTO `operation_log` VALUES (1,'CREATE','10.0.2.2','/tenant/post','{\"id\":\"1\",\"isAvailable\":\"1\",\"name\":\"janfish\",\"account\":\"admin\",\"province\":\"\",\"city\":\"\",\"linkman\":\"\",\"contact\":\"\",\"beginAt\":\"2021-12-01 00:00:00\",\"endAt\":\"2023-12-29 00:00:00\",\"createdAt\":\"2021-12-02 22:11:17\",\"updatedAt\":\"2021-12-02 22:11:17\"}','1','administrator','2021-12-02 14:11:17','2021-12-02 14:11:17');
/*!40000 ALTER TABLE `operation_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `platform_menu`
--

DROP TABLE IF EXISTS `platform_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `platform_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `link` varchar(255) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `sort` int(10) unsigned DEFAULT '100',
  `parent_id` int(10) unsigned DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `platform_menu`
--

LOCK TABLES `platform_menu` WRITE;
/*!40000 ALTER TABLE `platform_menu` DISABLE KEYS */;
INSERT INTO `platform_menu` VALUES (2,NULL,'系统管理','gear-b',1300,NULL,'2021-08-18 05:27:28','2021-08-18 05:27:28'),(27,'administrator/index','管理员管理',NULL,100,2,'2021-08-18 05:27:28','2021-08-18 05:27:28'),(28,'administratorRole/index','管理员角色管理',NULL,100,2,'2021-08-18 05:27:28','2021-08-18 05:27:28'),(29,'configuration/index','参数配置',NULL,99,2,'2021-08-18 05:27:28','2021-08-18 05:27:28'),(30,'operationLog/index','操作日志',NULL,98,2,'2021-08-18 05:27:28','2021-08-18 05:27:28'),(31,NULL,'服务器管理','social-windows',200,NULL,'2021-08-18 05:27:28','2021-08-18 05:27:28'),(32,'server/index','服务器列表',NULL,100,31,'2021-08-18 05:27:28','2021-08-18 05:27:28'),(33,NULL,'服务管理','cloud',300,NULL,'2021-08-18 05:27:28','2021-08-18 05:27:28'),(34,'service/index','服务列表',NULL,100,33,'2021-08-18 05:27:28','2021-08-18 05:27:28'),(35,NULL,'分库管理','social-buffer',400,NULL,'2021-08-18 05:27:29','2021-08-18 05:27:29'),(36,'serviceDatabase/index','数据库列表',NULL,100,35,'2021-08-18 05:27:29','2021-08-18 05:27:29'),(37,NULL,'租客管理','person-stalker',100,NULL,'2021-08-18 05:27:29','2021-08-18 05:27:29'),(38,'tenant/index','租客列表',NULL,100,37,'2021-08-18 05:27:29','2021-08-18 05:27:29'),(39,NULL,'充值明细','cash',110,NULL,'2021-08-18 05:27:30','2021-08-18 05:27:30'),(40,'tenantBalance/index','明细列表',NULL,100,39,'2021-08-18 05:27:30','2021-08-18 05:27:30'),(41,NULL,'版本管理','social-vimeo-outline',1200,NULL,'2021-10-20 03:57:43','2021-10-20 03:57:43');
/*!40000 ALTER TABLE `platform_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `server`
--

DROP TABLE IF EXISTS `server`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `server` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '服务器名',
  `domain` varchar(200) DEFAULT NULL COMMENT '服务器域名',
  `ip` varchar(20) DEFAULT NULL COMMENT '服务器IP',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `server`
--

LOCK TABLES `server` WRITE;
/*!40000 ALTER TABLE `server` DISABLE KEYS */;
INSERT INTO `server` VALUES (5,'dura','cat.cn','127.0.0.1','2021-12-02 12:24:51','2021-12-02 12:24:51');
/*!40000 ALTER TABLE `server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `isAvailable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `serverId` int(10) unsigned NOT NULL COMMENT '服务器',
  `name` varchar(50) DEFAULT NULL COMMENT '服务名称',
  `code` varchar(50) DEFAULT NULL COMMENT '服务代号',
  `tag` varchar(255) DEFAULT NULL COMMENT '服务tag，逗号隔开',
  `host` varchar(200) DEFAULT NULL COMMENT '服务地址',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='服务列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES (2,1,5,'service-rosa','service-rosa',NULL,'sass-service:18307','2021-12-02 12:26:21','2021-12-02 12:28:13');
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_database`
--

DROP TABLE IF EXISTS `service_database`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_database` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `serverId` int(10) unsigned NOT NULL,
  `serviceId` int(10) unsigned NOT NULL COMMENT '服务',
  `database` varchar(50) NOT NULL COMMENT '数据库',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `database` (`database`,`serviceId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_database`
--

LOCK TABLES `service_database` WRITE;
/*!40000 ALTER TABLE `service_database` DISABLE KEYS */;
INSERT INTO `service_database` VALUES (2,5,2,'sass','2021-12-02 12:26:40','2021-12-02 12:26:40');
/*!40000 ALTER TABLE `service_database` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenant`
--

DROP TABLE IF EXISTS `tenant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tenant` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `isAvailable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可用',
  `name` varchar(30) NOT NULL COMMENT '公司登录名',
  `account` varchar(30) DEFAULT NULL COMMENT '初始账号',
  `province` varchar(30) DEFAULT NULL COMMENT '省份',
  `city` varchar(30) DEFAULT NULL COMMENT '城市',
  `linkman` varchar(50) DEFAULT NULL COMMENT '联系人',
  `contact` varchar(20) DEFAULT NULL COMMENT '联系电话',
  `beginAt` timestamp NULL DEFAULT NULL COMMENT '有效期开始',
  `endAt` timestamp NULL DEFAULT NULL COMMENT '有效期结束',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='租客';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenant`
--

LOCK TABLES `tenant` WRITE;
/*!40000 ALTER TABLE `tenant` DISABLE KEYS */;
INSERT INTO `tenant` VALUES (1,1,'janfish','admin','','','','','2021-11-30 16:00:00','2023-12-28 16:00:00','2021-12-02 14:11:17','2021-12-02 14:11:17');
/*!40000 ALTER TABLE `tenant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenant_balance`
--

DROP TABLE IF EXISTS `tenant_balance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tenant_balance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(10) unsigned NOT NULL COMMENT '租客Id',
  `outTradeNo` varchar(40) NOT NULL COMMENT '本地订单号',
  `transactionId` varchar(40) NOT NULL COMMENT '支付渠道订单号',
  `biz` enum('SMS','LICENSE') NOT NULL COMMENT '业务类型',
  `bizData` varchar(200) DEFAULT NULL COMMENT '业务数据',
  `fee` int(10) unsigned NOT NULL COMMENT '金额',
  `remark` varchar(200) DEFAULT NULL COMMENT '业务备注',
  `paidAt` timestamp NULL DEFAULT NULL COMMENT '支付时间',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='资金明细';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenant_balance`
--

LOCK TABLES `tenant_balance` WRITE;
/*!40000 ALTER TABLE `tenant_balance` DISABLE KEYS */;
/*!40000 ALTER TABLE `tenant_balance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenant_service`
--

DROP TABLE IF EXISTS `tenant_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tenant_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(10) unsigned NOT NULL COMMENT '租户Id',
  `serverId` int(11) DEFAULT NULL COMMENT '服务器ID',
  `serviceId` int(200) unsigned NOT NULL COMMENT '服务ID',
  `databaseId` int(10) unsigned NOT NULL COMMENT '数据库ID',
  `dbName` varchar(80) DEFAULT NULL COMMENT '数据库',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenantId` (`tenantId`,`serviceId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenant_service`
--

LOCK TABLES `tenant_service` WRITE;
/*!40000 ALTER TABLE `tenant_service` DISABLE KEYS */;
INSERT INTO `tenant_service` VALUES (1,1,5,2,2,'sass','2021-12-02 14:11:17','2021-12-02 14:11:17');
/*!40000 ALTER TABLE `tenant_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `version`
--

DROP TABLE IF EXISTS `version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `isAvailable` tinyint(1) unsigned NOT NULL COMMENT '是否推送',
  `version` varchar(40) DEFAULT NULL COMMENT '版本号',
  `summary` varchar(800) DEFAULT NULL COMMENT '迭代内容',
  `patchUrl` varchar(255) DEFAULT NULL COMMENT '升级包地址',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='版本管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `version`
--

LOCK TABLES `version` WRITE;
/*!40000 ALTER TABLE `version` DISABLE KEYS */;
/*!40000 ALTER TABLE `version` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-12-02 22:13:41
