-- MySQL dump 10.13  Distrib 5.7.34, for Linux (x86_64)
--
-- Host: localhost    Database: sass
-- ------------------------------------------------------
-- Server version	5.7.34-log

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
-- Table structure for table `acl_route`
--

DROP TABLE IF EXISTS `acl_route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_route` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL,
  `menuId` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `route` varchar(255) DEFAULT NULL,
  `key` varchar(32) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_route`
--

LOCK TABLES `acl_route` WRITE;
/*!40000 ALTER TABLE `acl_route` DISABLE KEYS */;
INSERT INTO `acl_route` VALUES (1,0,16,'用户状态切换','PUT:/userStatus','53976db786a21502','2021-11-18 07:45:57','2021-11-18 07:53:28'),(2,0,16,'用户列表','GET:/users','579e469e8ac850de','2021-11-18 07:45:57','2021-11-18 07:53:28'),(3,0,16,'用户选择器','GET:/userOptions','04ebae36e0be27c9','2021-11-18 07:45:57','2021-11-18 07:53:28'),(7,0,16,'创建用户','POST:/users','a71cb59835c613f3','2021-11-18 07:45:57','2021-11-18 07:53:28'),(8,0,17,'创建用户分组','POST:/userGroups','0b56c2afb73f8e8a','2021-11-18 07:45:57','2021-11-18 07:53:28'),(9,0,17,'用户分组列表','GET:/userGroups','468764ba28e2122b','2021-11-18 07:45:57','2021-11-18 07:53:28'),(10,0,16,'用户分组选项','GET:/groupOptions','2b42ab90c0a53071','2021-11-18 07:45:57','2021-11-18 07:53:28'),(13,0,15,'角色列表','GET:/userRoles','bfe0d93815eb324a','2021-11-18 07:45:57','2021-11-18 07:53:28'),(14,0,16,'角色选择器','GET:/userRoleOptions','56cc05c94b7e9c0d','2021-11-18 07:45:57','2021-11-18 07:53:28'),(15,0,15,'创建角色','POST:/userRoles','17667fd304a571b8','2021-11-18 07:45:57','2021-11-18 07:53:28'),(16,0,16,'编辑用户','PUT:/users/{userId}','55dc1a28dc645b45','2021-11-18 07:45:57','2021-11-18 07:53:28'),(17,0,16,'删除用户','DELETE:/users/{userId}','66047762b72db5e4','2021-11-18 07:45:57','2021-11-18 07:53:28'),(18,0,16,'用户详情','GET:/users/{userId}','53012413d1d95634','2021-11-18 07:45:57','2021-11-18 07:53:28'),(19,0,17,'编辑用户分组','PUT:/userGroups/{groupId}','d458a64979d1b684','2021-11-18 07:45:57','2021-11-18 07:53:28'),(20,0,17,'用户分组删除','DELETE:/userGroups/{groupId}','d6fa825e98fa64d6','2021-11-18 07:45:57','2021-11-18 07:53:28'),(21,0,15,'角色详情','GET:/userRoles/{roleId}','cb000dab5184cc03','2021-11-18 07:45:57','2021-11-18 07:53:28'),(22,0,15,'编辑角色','PUT:/userRoles/{roleId}','7383705308ebb03d','2021-11-18 07:45:57','2021-11-18 07:53:28'),(23,0,15,'删除角色','DELETE:/userRoles/{roleId}','611a19b17851a734','2021-11-18 07:45:57','2021-11-18 07:53:28');
/*!40000 ALTER TABLE `acl_route` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '租户Id',
  `sort` int(11) unsigned NOT NULL DEFAULT '100',
  `isVisible` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可见',
  `name` varchar(50) NOT NULL COMMENT '栏目名',
  `parentId` int(11) unsigned DEFAULT NULL COMMENT '父级栏目',
  `icon` varchar(50) DEFAULT NULL COMMENT '图标',
  `link` varchar(200) DEFAULT NULL COMMENT '链接',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (14,0,500,1,'账户管理',NULL,NULL,NULL,'2021-08-15 04:54:20','2021-08-15 04:54:20'),(15,0,100,1,'角色管理',14,NULL,'/accountM/role','2021-08-15 04:54:20','2021-08-15 04:54:20'),(16,0,200,1,'人员管理',14,NULL,'/accountM/user','2021-08-15 04:54:20','2021-08-15 04:54:20'),(17,0,300,1,'分组管理',14,NULL,'/accountM/group','2021-08-15 04:54:20','2021-08-15 04:54:20');
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `selector_option`
--

DROP TABLE IF EXISTS `selector_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `selector_option` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL,
  `type` enum('VEHICLE_BRAND','INSURANCE_BRAND','TAG1','TAG2','TAG3') NOT NULL,
  `value` varchar(50) NOT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenantId` (`tenantId`,`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='控件选项';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `selector_option`
--

LOCK TABLES `selector_option` WRITE;
/*!40000 ALTER TABLE `selector_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `selector_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_setting`
--

DROP TABLE IF EXISTS `system_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_setting` (
  `tenantId` int(11) unsigned NOT NULL,
  `allowedUsers` smallint(6) unsigned NOT NULL COMMENT '坐席数',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tenantId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='租客系统配置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_setting`
--

LOCK TABLES `system_setting` WRITE;
/*!40000 ALTER TABLE `system_setting` DISABLE KEYS */;
INSERT INTO `system_setting` VALUES (9,112,'2021-11-18 05:14:07','2021-11-18 05:14:07'),(12,112,'2021-11-18 05:18:16','2021-11-18 05:18:16'),(14,1,'2021-11-18 05:21:02','2021-11-18 05:30:43'),(15,1123,'2021-11-18 07:18:25','2021-11-18 07:18:25');
/*!40000 ALTER TABLE `system_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `isAvailable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `isDeleted` int(11) unsigned NOT NULL DEFAULT '0',
  `isSuper` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'OAA',
  `tenantId` int(11) unsigned NOT NULL COMMENT '租户',
  `account` varchar(30) NOT NULL COMMENT '账户',
  `nickname` varchar(30) NOT NULL COMMENT '姓名',
  `mobile` char(11) NOT NULL COMMENT '手机号',
  `password` char(40) NOT NULL COMMENT '密码',
  `roleId` int(11) unsigned NOT NULL COMMENT '角色',
  `groupId` int(11) unsigned DEFAULT NULL COMMENT '分组',
  `latestLoginAt` timestamp NULL DEFAULT NULL COMMENT '最后登录时间',
  `latestLoginVer` int(11) DEFAULT '0',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenantId` (`tenantId`,`account`,`isDeleted`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,1,0,1,9,'admin','超级管理员','18547852365','admin',1,NULL,NULL,0,'2021-11-18 05:14:07','2021-11-18 05:14:07'),(2,1,0,1,12,'admin','超级管理员','18547852365','admin',2,NULL,NULL,0,'2021-11-18 05:18:16','2021-11-18 05:18:16'),(3,1,0,1,14,'admin','超级管理员','18547852365','admin',3,NULL,NULL,0,'2021-11-18 05:21:02','2021-11-18 07:10:45'),(4,1,0,1,15,'admin','超级管理员','','admin',4,NULL,'2021-11-18 07:36:53',15,'2021-11-18 07:18:25','2021-11-18 07:36:53');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_analysis`
--

DROP TABLE IF EXISTS `user_analysis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_analysis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL,
  `userId` int(11) unsigned NOT NULL,
  `data` int(11) unsigned NOT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户数据';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_analysis`
--

LOCK TABLES `user_analysis` WRITE;
/*!40000 ALTER TABLE `user_analysis` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_analysis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_group`
--

DROP TABLE IF EXISTS `user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL COMMENT '租户',
  `name` varchar(40) NOT NULL COMMENT '分组名',
  `users` mediumint(10) unsigned NOT NULL DEFAULT '0' COMMENT '人数',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_group`
--

LOCK TABLES `user_group` WRITE;
/*!40000 ALTER TABLE `user_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_menu`
--

DROP TABLE IF EXISTS `user_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '租户Id',
  `menuId` int(10) unsigned NOT NULL COMMENT '开通menuId',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_menu`
--

LOCK TABLES `user_menu` WRITE;
/*!40000 ALTER TABLE `user_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `isDeleted` int(11) unsigned NOT NULL DEFAULT '0',
  `tenantId` int(11) unsigned NOT NULL COMMENT '租户',
  `reader` enum('PERSONAL','GROUP','FULL') NOT NULL,
  `name` varchar(40) NOT NULL COMMENT '角色名称',
  `users` mediumint(5) unsigned NOT NULL DEFAULT '0' COMMENT '用户数',
  `remark` varchar(128) DEFAULT NULL COMMENT '备注',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (1,0,9,'FULL','超级管理组',1,'','2021-11-18 05:14:07','2021-11-18 05:14:07'),(2,0,12,'FULL','超级管理组',1,'','2021-11-18 05:18:16','2021-11-18 05:18:16'),(3,0,14,'FULL','超级管理组',1,'','2021-11-18 05:21:02','2021-11-18 05:21:02'),(4,0,15,'FULL','超级管理组',1,'','2021-11-18 07:18:25','2021-11-18 07:18:25'),(5,0,15,'FULL','阅读者2',0,'','2021-11-18 07:53:55','2021-11-18 07:53:55');
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role_route`
--

DROP TABLE IF EXISTS `user_role_route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_role_route` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL COMMENT '租户',
  `userRoleId` int(11) unsigned NOT NULL COMMENT '角色Id',
  `route` varchar(128) DEFAULT NULL COMMENT '路由',
  `key` varchar(16) DEFAULT NULL COMMENT '路由key',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userRoleId` (`userRoleId`,`key`,`tenantId`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role_route`
--

LOCK TABLES `user_role_route` WRITE;
/*!40000 ALTER TABLE `user_role_route` DISABLE KEYS */;
INSERT INTO `user_role_route` VALUES (1,15,5,'PUT:/userStatus','53976db786a21502','2021-11-18 07:53:55','2021-11-18 07:53:55'),(2,15,5,'GET:/users','579e469e8ac850de','2021-11-18 07:53:55','2021-11-18 07:53:55'),(3,15,5,'GET:/userOptions','04ebae36e0be27c9','2021-11-18 07:53:55','2021-11-18 07:53:55'),(4,15,5,'POST:/users','a71cb59835c613f3','2021-11-18 07:53:55','2021-11-18 07:53:55'),(5,15,5,'POST:/userGroups','0b56c2afb73f8e8a','2021-11-18 07:53:55','2021-11-18 07:53:55'),(6,15,5,'GET:/userGroups','468764ba28e2122b','2021-11-18 07:53:55','2021-11-18 07:53:55'),(7,15,5,'GET:/groupOptions','2b42ab90c0a53071','2021-11-18 07:53:55','2021-11-18 07:53:55'),(8,15,5,'GET:/userRoles','bfe0d93815eb324a','2021-11-18 07:53:55','2021-11-18 07:53:55'),(9,15,5,'GET:/userRoleOptions','56cc05c94b7e9c0d','2021-11-18 07:53:55','2021-11-18 07:53:55'),(10,15,5,'POST:/userRoles','17667fd304a571b8','2021-11-18 07:53:55','2021-11-18 07:53:55'),(11,15,5,'PUT:/users/{userId}','55dc1a28dc645b45','2021-11-18 07:53:55','2021-11-18 07:53:55'),(12,15,5,'DELETE:/users/{userId}','66047762b72db5e4','2021-11-18 07:53:55','2021-11-18 07:53:55'),(13,15,5,'GET:/users/{userId}','53012413d1d95634','2021-11-18 07:53:55','2021-11-18 07:53:55'),(14,15,5,'PUT:/userGroups/{groupId}','d458a64979d1b684','2021-11-18 07:53:55','2021-11-18 07:53:55'),(15,15,5,'DELETE:/userGroups/{groupId}','d6fa825e98fa64d6','2021-11-18 07:53:55','2021-11-18 07:53:55'),(16,15,5,'GET:/userRoles/{roleId}','cb000dab5184cc03','2021-11-18 07:53:55','2021-11-18 07:53:55'),(17,15,5,'PUT:/userRoles/{roleId}','7383705308ebb03d','2021-11-18 07:53:55','2021-11-18 07:53:55'),(18,15,5,'DELETE:/userRoles/{roleId}','611a19b17851a734','2021-11-18 07:53:55','2021-11-18 07:53:55');
/*!40000 ALTER TABLE `user_role_route` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-18 15:56:33
