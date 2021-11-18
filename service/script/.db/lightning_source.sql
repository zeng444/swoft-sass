SET NAMES utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4;

INSERT INTO `acl_route` VALUES (1,0,15,'创建角色','POST:/userRoles','17667fd304a571b8','2021-08-19 08:52:39','2021-09-30 06:34:44'),(2,0,15,'删除角色','DELETE:/userRoles/{roleId}','611a19b17851a734','2021-08-19 08:52:39','2021-09-30 06:34:44'),(3,0,13,'品牌系统列表','GET:/brands','a964c307ee72b7d8','2021-08-30 07:04:06','2021-09-30 06:34:44'),(4,0,11,'模版编辑','PUT:/messageTemplate','77f15fc8de29929c','2021-08-30 07:04:06','2021-09-30 06:34:44'),(5,0,11,'短息变量列表','GET:/messageTemplateVariables','8276f2b5e3670eb6','2021-08-30 07:04:06','2021-09-30 06:34:44'),(6,0,11,'短信模版','GET:/messageTemplate','8d7348af1b9de843','2021-08-30 07:04:06','2021-09-30 06:34:44'),(7,0,13,'出单工号创建','POST:/orderAccounts','76619fbbc5839398','2021-08-30 07:04:06','2021-09-30 06:34:44'),(8,0,13,'出单工号列表','GET:/orderAccounts','96948fd0eca47b01','2021-08-30 07:04:06','2021-09-30 06:34:44'),(9,0,13,'工号状态切换','PUT:/orderAccountStatus','913fd1a85be65010','2021-08-30 07:04:06','2021-09-30 06:34:44'),(10,0,13,'工号状态详情','POST:/orderAccounts/bacthRemove','752db8d4bba6435b','2021-08-30 07:04:06','2021-09-30 06:34:44'),(11,0,16,'用户状态切换','PUT:/userStatus','53976db786a21502','2021-08-30 07:04:06','2021-09-30 06:34:44'),(12,0,16,'用户列表','GET:/users','579e469e8ac850de','2021-08-30 07:04:06','2021-09-30 06:34:44'),(13,0,16,'创建用户','POST:/users','a71cb59835c613f3','2021-08-30 07:04:06','2021-09-30 06:34:44'),(14,0,17,'创建用户分组','POST:/userGroups','0b56c2afb73f8e8a','2021-08-30 07:04:06','2021-09-30 06:34:44'),(15,0,17,'用户分组列表','GET:/userGroups','468764ba28e2122b','2021-08-30 07:04:06','2021-09-30 06:34:44'),(16,0,15,'角色列表','GET:/userRoles','bfe0d93815eb324a','2021-08-30 07:04:06','2021-09-30 06:34:44'),(17,0,13,'出单工号编辑','PUT:/orderAccounts/{orderAccountId}','b3917bb82f731b34','2021-08-30 07:04:06','2021-09-30 06:34:44'),(18,0,13,'工号状态详情','GET:/orderAccounts/{orderAccountId}','0628e2b52610c0a3','2021-08-30 07:04:06','2021-09-30 06:34:44'),(19,0,16,'编辑用户','PUT:/users/{userId}','55dc1a28dc645b45','2021-08-30 07:04:06','2021-09-30 06:34:44'),(20,0,16,'删除用户','DELETE:/users/{userId}','66047762b72db5e4','2021-08-30 07:04:06','2021-09-30 06:34:44'),(21,0,16,'用户详情','GET:/users/{userId}','53012413d1d95634','2021-08-30 07:04:06','2021-09-30 06:34:44'),(22,0,17,'编辑用户分组','PUT:/userGroups/{groupId}','d458a64979d1b684','2021-08-30 07:04:06','2021-09-30 06:34:44'),(23,0,17,'用户分组删除','DELETE:/userGroups/{groupId}','d6fa825e98fa64d6','2021-08-30 07:04:06','2021-09-30 06:34:44'),(24,0,15,'角色详情','GET:/userRoles/{roleId}','cb000dab5184cc03','2021-08-30 07:04:06','2021-09-30 06:34:44'),(25,0,15,'编辑角色','PUT:/userRoles/{roleId}','7383705308ebb03d','2021-08-30 07:04:06','2021-09-30 06:34:44'),(26,0,5,'资源统计','GET:/customersStatistics','53a6a69431f64c4b','2021-09-14 08:36:29','2021-09-30 06:34:44'),(27,0,5,'资源分配','POST:/customersAllocations','e050e359667f089d','2021-09-14 08:36:29','2021-09-30 06:34:44'),(28,0,6,'资源删除','DELETE:/customers','a605a983f046c117','2021-09-14 08:36:29','2021-09-30 06:34:44'),(29,0,9,'客户列表','GET:/customers','ab58ec368c0547fe','2021-09-14 08:36:29','2021-09-30 06:34:44'),(30,0,7,'资源统计','GET:/customerAnalysis','02432306e639b0f2','2021-09-14 08:36:29','2021-09-30 06:34:44'),(31,0,4,'资源导入','POST:/customerImports','b31d5f1ae3e26c58','2021-09-14 08:36:29','2021-09-30 06:34:44'),(32,0,4,'资源导入列表','GET:/customerImports','53d9bf841f4c50d2','2021-09-14 08:36:29','2021-09-30 06:34:44'),(33,0,16,'出单工号列表','GET:/orderAccountOptions','8c40a433fe3f4dd8','2021-09-14 08:36:29','2021-09-30 06:34:44'),(34,0,13,'用户选择器','GET:/userOptions','04ebae36e0be27c9','2021-09-14 08:36:29','2021-09-30 06:34:44'),(35,0,6,'资源分配用户选择器','GET:/customerAllocate/UserOptions','b1ce5f056f23ee8b','2021-09-14 08:36:29','2021-09-30 06:34:44'),(36,0,6,'资源删除用户选择器','GET:/customerDelete/userOptions','9b0d6bd46ab6c6a1','2021-09-14 08:36:29','2021-09-30 06:34:44'),(37,0,7,'资源统计用户选择器','GET:/customerAnalysis/userOptions','4f2d96897058eb56','2021-09-14 08:36:29','2021-09-30 06:34:44'),(38,0,16,'用户分组选项','GET:/groupOptions','2b42ab90c0a53071','2021-09-14 08:36:29','2021-09-30 06:34:44'),(39,0,7,'资源统计分组选项','GET:/customerAnalysis/groupOptions','4c59414a21957fb0','2021-09-14 08:36:29','2021-09-30 06:34:44'),(40,0,5,'资源分配分组选项','GET:/customerAllocate/groupOptions','095554c1b976e173','2021-09-14 08:36:29','2021-09-30 06:34:44'),(41,0,16,'角色选择器','GET:/userRoleOptions','56cc05c94b7e9c0d','2021-09-14 08:36:29','2021-09-30 06:34:44'),(42,0,4,'资源导入详情','GET:/customerImports/{importId}/customers','9b269634c94934ac','2021-09-14 08:36:29','2021-09-30 06:34:44'),(43,0,9,'资源详情','GET:/customers/{customerId}','2a863ec6517e957b','2021-09-14 08:36:29','2021-09-30 06:34:44'),(44,0,9,'跟进信息列表','GET:/customers/{customerId}/touchInfos','6215efd4937f950b','2021-09-14 08:36:29','2021-09-30 06:34:44'),(45,0,9,'创建跟进信息','POST:/customers/{customerId}/touchInfos','43988d9d19382caa','2021-09-14 08:36:29','2021-09-30 06:34:44'),(46,0,4,'资源池客户列表','GET:/poolCustomers','32fe820fb01ad960','2021-09-14 09:54:53','2021-09-30 06:34:44'),(47,0,6,'资源统计','GET:/customerDelete/customersStatistics','a9722acebf29fb46','2021-09-14 12:48:10','2021-09-30 06:34:44'),(48,0,9,'资源状态统计','GET:/customerStatusAnalysis','884a4ce07bbe0207','2021-09-16 10:07:43','2021-09-30 06:34:44'),(49,0,10,'短信记录','GET:/messages','c9ea696a54d958bd','2021-09-16 10:07:43','2021-09-30 06:34:44'),(50,0,10,'短信统计','GET:/messageAnalysis','820a8e02862db042','2021-09-16 10:07:43','2021-09-30 06:34:44'),(51,0,10,'短信发送','POST:/messages','048d5fa785847d76','2021-09-16 10:07:43','2021-09-30 06:34:44'),(52,0,10,'出单工号列表','GET:/message/orderAccountOptions','86ded555619e111c','2021-09-16 10:07:43','2021-09-30 06:34:44'),(53,0,10,'短信重发','POST:/messageResend','51e61e62d8e69e06','2021-09-22 09:21:33','2021-09-30 06:34:44'),(54,0,9,'询价短信模版','GET:/quoteMessageTemplate','7b8f0b999bc67843','2021-09-22 09:21:33','2021-09-30 06:34:44'),(55,0,9,'获取解析报价数据','GET:/quoteResultPull','d9661c711cedda3e','2021-09-22 09:21:33','2021-09-30 06:34:44'),(56,0,9,'报价工号','GET:/quoteAccounts','47bb652503fc89dd','2021-09-22 09:21:33','2021-09-30 06:34:44'),(57,0,9,'资源修改','PUT:/customers/{customerId}','17a5fa06d15e2b52','2021-09-22 09:21:33','2021-09-30 06:34:44'),(58,0,9,'报价列表','GET:/customers/{customerId}/quotes','00905511c42b8894','2021-09-22 09:21:33','2021-09-30 06:34:44'),(59,0,9,'保存报价','POST:/customers/{customerId}/quotes','d526df707bd64fab','2021-09-22 09:21:33','2021-09-30 06:34:44'),(60,0,18,'批量算价批次列表','GET:/quoteBatchList','046945d11b299c55','2021-09-30 06:29:53','2021-09-30 06:34:44'),(61,0,18,'批量立即发送','POST:/quoteBatches/{quoteBatchId}/sendTasks','f3fdd698cb14d641','2021-09-30 06:29:53','2021-09-30 06:34:44'),(62,0,18,'批量重发','POST:/quoteBatches/{quoteBatchId}/resendTasks','59695be1dd773e06','2021-09-30 06:29:53','2021-09-30 06:34:44'),(63,0,18,'批量算价详情','GET:/quoteBatches/{quoteBatchId}','ea4a790e93b8e1a6','2021-09-30 06:29:53','2021-09-30 06:34:44'),(64,0,18,'批量算价批次导出','GET:/quoteBatches/{quoteBatchId}/taskFiles','ba275c563ade7ec8','2021-09-30 06:29:53','2021-09-30 06:34:44'),(65,0,18,'批量算价批次详情','GET:/quoteBatches/{quoteBatchId}/tasks','9a57a5f02e21ef81','2021-09-30 06:29:53','2021-09-30 06:34:44'),(66,0,18,'批量算价取消发送','PUT:/quoteBatches/{quoteBatchId}','e30a7336ab46b814','2021-09-30 06:29:53','2021-09-30 06:34:44'),(67,0,9,'创建批量报价','POST:/quoteBatches','843050740da83139','2021-09-30 06:34:44','2021-09-30 06:34:44');

CREATE TABLE `brand` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL,
  `brand` varchar(40) COLLATE utf8mb4_bin DEFAULT NULL,
  `systemCode` varchar(40) COLLATE utf8mb4_bin NOT NULL,
  `proxy` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '代理地址',
  `port` varchar(5) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '端口',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `tenantId` (`tenantId`,`systemCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=DYNAMIC;

CREATE TABLE `customer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `isDeleted` int(11) unsigned NOT NULL DEFAULT '0',
  `tenantId` int(11) unsigned NOT NULL,
  `importId` int(11) unsigned DEFAULT NULL COMMENT '导入文件Id',
  `tag1` varchar(40) DEFAULT NULL COMMENT '搜索标识1',
  `tag2` varchar(40) DEFAULT NULL COMMENT '搜索标识2',
  `tag3` varchar(40) DEFAULT NULL COMMENT '搜索标识3',
  `status` enum('FAIL','PAID','PENDING','QUOTE','UNPAID','FIRST','KEY','SUCCESS') NOT NULL DEFAULT 'PENDING' COMMENT '跟单状态',
  `smsStatus` enum('PENDING','SUCCESS','FAIL') NOT NULL DEFAULT 'PENDING' COMMENT '短息发送状态',
  `smsCount` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `license` varchar(15) NOT NULL COMMENT '车牌号',
  `vin` varchar(19) NOT NULL COMMENT '车架号',
  `engineNo` varchar(30) DEFAULT NULL COMMENT '发动机号',
  `brandName` varchar(50) DEFAULT NULL COMMENT '车辆品牌名称',
  `modelCode` varchar(40) DEFAULT NULL COMMENT '车型编码',
  `firstRegisterDate` date DEFAULT NULL COMMENT '初登时间',
  `ownerName` varchar(30) DEFAULT NULL COMMENT '车主名称',
  `ownerCard` varchar(30) DEFAULT NULL COMMENT '车主证件',
  `mobile` char(11) DEFAULT NULL COMMENT '电话',
  `lastBrand` varchar(60) DEFAULT NULL COMMENT '上年公司',
  `lastPolicy` varchar(40) DEFAULT NULL COMMENT '上年保单号',
  `lastBeginAt` timestamp NULL DEFAULT NULL COMMENT '上年起保时间',
  `lastEndAt` timestamp NULL DEFAULT NULL COMMENT '上年终保时间',
  `creatorId` int(11) unsigned NOT NULL COMMENT '创建员Id',
  `lastUserId` int(10) unsigned DEFAULT NULL COMMENT '之前所属用户',
  `userId` int(11) unsigned DEFAULT NULL COMMENT '所属用户',
  `groupId` int(11) unsigned DEFAULT NULL COMMENT '所属分组',
  `touchId` int(11) unsigned DEFAULT NULL COMMENT '更新Id',
  `touchAt` timestamp NULL DEFAULT NULL COMMENT '跟进时间',
  `updatedAt` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `createdAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`tenantId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `customer_extend` (
  `customerId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL,
  `address` varchar(200) DEFAULT NULL,
  `lastCoverages` varchar(2000) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`customerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `customer_import` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) unsigned NOT NULL,
  `tenantId` int(11) unsigned NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT '名称',
  `original` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '原始文件名',
  `success` int(11) unsigned DEFAULT '0' COMMENT '成功',
  `fail` int(11) unsigned DEFAULT '0' COMMENT '失败',
  `repeat` int(11) unsigned DEFAULT '0' COMMENT '重复条数',
  `total` int(11) unsigned DEFAULT '0' COMMENT '总条数',
  `status` enum('SUCCESS','FAIL','IMPORTING') COLLATE utf8mb4_bin DEFAULT NULL COMMENT '状态',
  `remark` text COLLATE utf8mb4_bin COMMENT '备注',
  `updatedAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL COMMENT '上传时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='资源池导入';

CREATE TABLE `customer_touch_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL COMMENT 'SaaSId',
  `status` enum('FAIL','PAID','QUOTE','UNPAID','FIRST','KEY','SUCCESS') DEFAULT NULL,
  `lastStatus` varchar(10) NOT NULL,
  `customerId` int(11) unsigned NOT NULL COMMENT '工单Id',
  `type` enum('SMS','MANUAL') DEFAULT 'MANUAL' COMMENT '跟进类型',
  `record` varchar(2000) DEFAULT NULL COMMENT '跟单记录',
  `log` varchar(255) DEFAULT NULL,
  `touchAt` timestamp NULL DEFAULT NULL COMMENT '跟进时间',
  `userId` int(11) unsigned NOT NULL COMMENT '操作人Id',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

INSERT INTO `menu` VALUES (1,0,100,1,'数据解析',NULL,NULL,NULL,'2021-08-15 04:54:20','2021-08-15 04:54:20'),(2,0,100,1,'解析列表',1,NULL,'/dataAnalysis','2021-08-15 04:54:20','2021-08-15 04:54:20'),(3,0,200,1,'资源管理',NULL,NULL,NULL,'2021-08-15 04:54:20','2021-08-15 04:54:20'),(4,0,100,1,'资源池',3,NULL,'/resourceM/pool','2021-08-15 04:54:20','2021-08-15 04:54:20'),(5,0,200,1,'资源分配',3,NULL,'/resourceM/distribution','2021-08-15 04:54:20','2021-08-15 04:54:20'),(6,0,300,1,'资源删除',3,NULL,'/resourceM/del','2021-08-15 04:54:20','2021-08-15 04:54:20'),(7,0,400,1,'数据统计',3,NULL,'/resourceM/statistics','2021-08-15 04:54:20','2021-08-15 04:54:20'),(8,0,300,1,'电销模块',NULL,NULL,NULL,'2021-08-15 04:54:20','2021-08-15 04:54:20'),(9,0,100,1,'工作台',8,NULL,'/electricModule/workbench','2021-08-15 04:54:20','2021-08-15 04:54:20'),(10,0,200,1,'短信记录',8,NULL,'/electricModule/smsRecord','2021-08-15 04:54:20','2021-08-15 04:54:20'),(11,0,300,1,'短息模板',8,NULL,'/electricModule/smsTemplate','2021-08-15 04:54:20','2021-08-15 04:54:20'),(12,0,400,1,'工号管理',NULL,NULL,NULL,'2021-08-15 04:54:20','2021-08-15 04:54:20'),(13,0,100,1,'工号列表',12,NULL,'/jobNumberM','2021-08-15 04:54:20','2021-08-15 04:54:20'),(14,0,500,1,'账户管理',NULL,NULL,NULL,'2021-08-15 04:54:20','2021-08-15 04:54:20'),(15,0,100,1,'角色管理',14,NULL,'/accountM/role','2021-08-15 04:54:20','2021-08-15 04:54:20'),(16,0,200,1,'人员管理',14,NULL,'/accountM/user','2021-08-15 04:54:20','2021-08-15 04:54:20'),(17,0,300,1,'分组管理',14,NULL,'/accountM/group','2021-08-15 04:54:20','2021-08-15 04:54:20'),(18,0,101,1,'批量算价批次',8,NULL,'/electricModule/batchPricing',NULL,NULL);

CREATE TABLE `message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL,
  `customerId` int(11) unsigned DEFAULT NULL,
  `userId` int(11) unsigned DEFAULT NULL COMMENT '操作人',
  `license` varchar(15) DEFAULT NULL COMMENT '车牌号',
  `ownerName` varchar(30) DEFAULT NULL COMMENT '车主',
  `orderAccountNo` varchar(50) DEFAULT NULL COMMENT '工号',
  `systemCode` varchar(40) DEFAULT NULL COMMENT '品牌',
  `mobile` char(11) NOT NULL COMMENT '电话号码',
  `content` varchar(500) NOT NULL COMMENT '短信消息',
  `status` enum('SUCCESS','FAIL') DEFAULT NULL COMMENT '结果，返回的单号',
  `returnId` varchar(40) DEFAULT NULL,
  `type` enum('BATCH','QUOTE') DEFAULT NULL COMMENT '短信类型',
  `rows` tinyint(3) NOT NULL DEFAULT '1' COMMENT '条数',
  `createdAt` timestamp NULL DEFAULT NULL COMMENT '发送时间',
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `message_recharge_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned DEFAULT NULL COMMENT '租户',
  `quantity` int(10) unsigned DEFAULT NULL COMMENT '短息数量',
  `operatorType` enum('ADMIN','TENANT') DEFAULT NULL COMMENT '充值人类型',
  `operatorId` int(10) unsigned NOT NULL COMMENT '充值人，管理员ID或者用户Id',
  `remark` varchar(30) DEFAULT NULL COMMENT '充值备注',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `message_statistics` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL,
  `times` int(11) unsigned DEFAULT NULL COMMENT '发送次数',
  `rows` int(11) unsigned DEFAULT NULL COMMENT '发送条数',
  `remain` int(11) DEFAULT NULL COMMENT '剩余条数',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `message_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL COMMENT '租客',
  `userId` int(11) unsigned NOT NULL COMMENT '用户',
  `template` varchar(2000) NOT NULL COMMENT '短信模板',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短信模板';

CREATE TABLE `message_template_variable` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '租户',
  `name` varchar(30) DEFAULT NULL COMMENT '中文',
  `code` varchar(30) DEFAULT NULL COMMENT '代号',
  `type` enum('FLOAT','STRING','INT') DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COMMENT='短信模版变量';

INSERT INTO `message_template_variable` VALUES (1,0,'车牌号','license','STRING','2021-08-27 06:13:13','2021-08-27 06:13:13'),(2,0,'总保费','total','FLOAT','2021-08-27 06:13:13','2021-08-27 06:13:13'),(3,0,'商业险保费','VCITotal','FLOAT','2021-08-27 06:13:13','2021-08-27 06:13:13'),(4,0,'交强险保费','TCITotal','FLOAT','2021-08-27 06:13:13','2021-08-27 06:13:13'),(5,0,'车船税','taxTotal','FLOAT','2021-08-27 06:13:13','2021-08-27 06:13:13'),(6,0,'车损险保费','3000_total','FLOAT','2021-08-27 06:13:13','2021-08-27 06:13:13'),(11,0,'第三方责任险保费','4000_total','FLOAT','2021-08-27 06:13:13','2021-08-27 06:13:13'),(12,0,'司机险保费','5002_total','FLOAT','2021-08-27 06:13:13','2021-08-27 06:13:13'),(13,0,'乘客险保费','5001_total','FLOAT','2021-08-27 06:13:13','2021-08-27 06:13:13'),(16,0,'第三方责任险保额','4000_insuredDesc','FLOAT','2021-08-27 06:13:13','2021-08-27 06:13:13'),(17,0,'司机险保额','5002_insuredDesc','STRING','2021-08-27 06:13:13','2021-08-27 06:13:13'),(18,0,'乘客险保额','5001_insuredDesc','STRING','2021-08-27 06:13:13','2021-08-27 06:13:13'),(22,0,'车损险保额','3000_insuredDesc','STRING','2021-08-27 06:13:13','2021-08-27 06:13:13');

CREATE TABLE `order_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL COMMENT '租户',
  `isDeleted` int(11) unsigned NOT NULL DEFAULT '0',
  `isAvailable` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `brand` varchar(40) DEFAULT NULL COMMENT '品牌',
  `systemCode` varchar(40) DEFAULT NULL COMMENT '系统代号',
  `type` enum('ANALYSIS','QUOTE','ALL') NOT NULL COMMENT '类型',
  `mac` char(48) DEFAULT NULL COMMENT 'MAC地址',
  `account` varchar(50) NOT NULL COMMENT '帐号',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `attribute` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL COMMENT '描述',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenantId` (`tenantId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='出单工号';

CREATE TABLE `quote` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL COMMENT 'SaaSId',
  `customerId` int(11) unsigned NOT NULL COMMENT '工单id',
  `orderAccountId` int(11) unsigned DEFAULT NULL COMMENT '工号Id',
  `orderAccountNo` varchar(50) DEFAULT NULL,
  `systemCode` varchar(40) DEFAULT NULL COMMENT '品牌id',
  `price` decimal(10,2) unsigned DEFAULT NULL COMMENT '报价',
  `userId` varchar(255) DEFAULT NULL COMMENT '操作员',
  `result` text COMMENT '报价参数',
  `independentPriceRatio` decimal(4,3) DEFAULT NULL COMMENT '商业自主定价系数',
  `createdAt` timestamp NULL DEFAULT NULL COMMENT '报价时间',
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `quote_batch` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL,
  `status` enum('QUOTING','QUOTED','SENT','CANCELED','QUOTE_CANCELED') NOT NULL DEFAULT 'QUOTING' COMMENT '状态',
  `sendType` enum('IMMEDIATE','TIMING') NOT NULL DEFAULT 'IMMEDIATE' COMMENT '发送类型',
  `brand` varchar(40) NOT NULL COMMENT '报价品牌',
  `quoteBrand` varchar(40) DEFAULT NULL COMMENT '询价品牌',
  `systemCode` varchar(40) NOT NULL COMMENT '询价系统',
  `type` enum('STANDARD','CUSTOM','FILTER') NOT NULL DEFAULT 'STANDARD' COMMENT '询价类型',
  `batch` char(16) NOT NULL COMMENT '批次号',
  `account` varchar(50) NOT NULL COMMENT '工号帐号',
  `accountAttribute` varchar(255) DEFAULT NULL COMMENT '工号属性',
  `password` varchar(50) NOT NULL COMMENT '工号密码',
  `discount` decimal(4,3) unsigned DEFAULT NULL COMMENT '优惠折扣',
  `independentPriceRatio` decimal(4,3) unsigned DEFAULT NULL COMMENT '商业自主定价系数',
  `template` text NOT NULL COMMENT '短信模板',
  `quote` text NOT NULL COMMENT '询价参数',
  `VCIBeginAt` timestamp NULL DEFAULT NULL COMMENT '商业险起保时间',
  `TCIBeginAt` timestamp NULL DEFAULT NULL COMMENT '交强险起保时间',
  `sendAt` timestamp NULL DEFAULT NULL COMMENT '定时发送',
  `userId` int(11) unsigned DEFAULT NULL COMMENT '操作人',
  `finish` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '成功条数',
  `error` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '失败条数',
  `total` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '总条数',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`batch`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='批量询价		';

CREATE TABLE `quote_batch_customer` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL,
  `userId` int(11) unsigned NOT NULL COMMENT '操作者',
  `quoteBatchId` int(11) unsigned NOT NULL,
  `batch` char(16) NOT NULL COMMENT '批量批次号',
  `customerId` int(10) unsigned NOT NULL COMMENT '订单id',
  `status` enum('FINISH','ERROR','WAITING') NOT NULL COMMENT '状态',
  `license` varchar(15) DEFAULT NULL COMMENT '车牌号',
  `ownerName` varchar(30) DEFAULT NULL COMMENT '车主名称',
  `mobile` char(11) DEFAULT NULL COMMENT '电话',
  `isPulled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已获取',
  `isParsed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否解析',
  `parsed` varchar(2000) DEFAULT NULL COMMENT '解析结果',
  `error` varchar(100) DEFAULT NULL COMMENT '失败信息',
  `createdAt` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

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

CREATE TABLE `system_brand` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8mb4_bin NOT NULL COMMENT '名称',
  `code` varchar(40) COLLATE utf8mb4_bin NOT NULL COMMENT '品牌代码',
  `systemCode` varchar(40) COLLATE utf8mb4_bin NOT NULL COMMENT '系统代码（单品牌多系统）',
  `type` enum('ANALYSIS','QUOTE','ALL') COLLATE utf8mb4_bin DEFAULT NULL COMMENT '类型',
  `logo` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'logo',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `system_brand` VALUES (1,0,'中国人保营销系统','PICC','PICCXY','QUOTE','','2021-08-30 07:26:32','2021-08-30 07:26:32'),(2,0,'天安财险','TIAN','TIAN','ALL','','2021-08-30 07:26:32','2021-08-30 07:26:32'),(3,0,'太平洋保险','CPIC','CPIC','ANALYSIS','','2021-08-30 07:26:32','2021-08-30 07:26:32'),(4,0,'珠峰保险','ZFBX','ZFBX','ALL','','2021-08-30 07:26:32','2021-08-30 07:26:32'),(5,0,'中国平安','PingAn','PingAn','QUOTE','','2021-08-30 07:26:32','2021-08-30 07:26:32'),(6,0,'永诚保险','ALL_TRUST','ALL_TRUST','ALL','','2021-08-30 07:26:32','2021-08-30 07:26:32'),(7,0,'太平财险','CHINATP','CHINATP_AUTOPP','ALL','','2021-08-30 07:26:32','2021-08-30 07:26:32'),(8,0,'中国人保三代系统','PICC','PICC','ALL','','2021-08-30 07:26:32','2021-08-30 07:26:32'),(9,0,'泰山保险','TAISHAN','TAISHAN','ALL','','2021-08-30 07:26:32','2021-08-30 07:26:32');

CREATE TABLE `system_coverage` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL DEFAULT '0',
  `parentId` int(10) unsigned DEFAULT NULL,
  `type` enum('TCI','VCI') NOT NULL DEFAULT 'VCI' COMMENT '类型',
  `code` varchar(30) DEFAULT NULL COMMENT '编号',
  `prerequisiteCode` varchar(30) DEFAULT NULL COMMENT '先决条件ID',
  `name` varchar(50) NOT NULL COMMENT '险种名',
  `unit` char(1) DEFAULT NULL COMMENT '保额单位',
  `shortName` varchar(20) NOT NULL COMMENT '险种短名',
  `sort` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `inputType` enum('INPUT','DATE','DEFAULT','SELECT') NOT NULL DEFAULT 'DEFAULT',
  `inputSelectOptions` varchar(255) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

INSERT INTO `system_coverage` VALUES (3,0,NULL,'VCI','3000','2000','车辆损失险',NULL,'车损险',300,'DEFAULT',NULL,'2018-09-23 11:21:49','2018-09-23 11:21:49'),(4,0,NULL,'VCI','4000','2000','商业第三者责任保险',NULL,'三者险',400,'SELECT','{\"5\":\"5万\",\"10\":\"10万\",\"15\":\"15万\",\"20\":\"20万\",\"30\":\"30万\",\"50\":\"50万\",\"100\":\"100万\",\"150\":\"150万\",\"200\":\"200万\"}','2018-09-23 11:21:49','2018-09-23 11:21:49'),(5,0,16,'VCI','5001',NULL,'车上人员责任险（乘客）','座','乘客险',200,'SELECT','{\"1\":\"1万\",\"2\":\"2万\",\"3\":\"3万\",\"5\":\"5万\",\"10\":\"10万\",\"20\":\"20万\",\"50\":\"50万\"}','2018-09-23 11:21:49','2018-09-23 11:21:49'),(12,0,16,'VCI','5002',NULL,'车上人员责任险（司机）','座','司机险',100,'SELECT','{\"0.5\":\"5千\",\"1\":\"1万\",\"2\":\"2万\",\"3\":\"3万\",\"4\":\"4万\",\"5\":\"5万\",\"10\":\"10万\",\"20\":\"20万\"}','2018-09-23 11:21:49','2018-09-23 11:21:49'),(16,0,NULL,'VCI',NULL,'2000','车上人员责任险',NULL,'车上人员责任险',600,'DEFAULT',NULL,'2018-09-23 11:21:49','2018-09-23 11:21:49'),(17,0,NULL,'TCI','1000',NULL,'交强险/车船税',NULL,'交强险/车船税',100,'DATE',NULL,'2018-09-23 11:21:49','2018-09-23 11:21:49'),(18,0,NULL,'VCI','2000',NULL,'商业险',NULL,'商业险',200,'DATE',NULL,'2018-09-23 11:21:49','2018-09-23 11:21:49');

CREATE TABLE `system_setting` (
  `tenantId` int(11) unsigned NOT NULL,
  `allowedUsers` smallint(6) unsigned NOT NULL COMMENT '坐席数',
  `dailySmsLimit` int(11) unsigned NOT NULL COMMENT '单日最大短信数',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tenantId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='租客系统配置';

CREATE TABLE `system_sp_brand_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '租户',
  `name` varchar(30) DEFAULT NULL COMMENT '标签名',
  `spVendorId` int(10) unsigned NOT NULL COMMENT 'sp渠道',
  `brand` varchar(40) NOT NULL COMMENT '保险品牌',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `spVendorId` (`spVendorId`,`brand`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

INSERT INTO `system_sp_brand_tag` VALUES (1,0,'【人保】',1,'PICC','2021-09-03 15:58:52','2021-09-03 15:58:54'),(2,0,'【天安】',1,'TIAN',NULL,NULL),(3,0,'【太平洋】',1,'CPIC',NULL,NULL),(4,0,'【珠峰】',1,'ZFBX',NULL,NULL),(5,0,'【中国平安】',1,'PingAn',NULL,NULL),(6,0,'【永诚保险】',1,'ALL_TRUST',NULL,NULL),(7,0,'【太平财险】',1,'CHINATP',NULL,NULL),(8,0,'【泰山保险】',1,'TAISHAN',NULL,NULL),(10,0,'【人保】',2,'PICC',NULL,NULL),(11,0,'【天安】',2,'TIAN',NULL,NULL),(12,0,'【太平洋】',2,'CPIC',NULL,NULL),(13,0,'【珠峰】',2,'ZFBX',NULL,NULL),(14,0,'【中国平安】',2,'PingAn',NULL,NULL),(15,0,'【永诚保险】',2,'ALL_TRUST',NULL,NULL),(16,0,'【太平财险】',2,'CHINATP',NULL,NULL),(17,0,'【泰山保险】',2,'TAISHAN',NULL,NULL);

CREATE TABLE `system_sp_vendor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '租户',
  `name` varchar(128) NOT NULL,
  `code` varchar(30) NOT NULL COMMENT '渠道代号',
  `config` varchar(255) DEFAULT NULL COMMENT '渠道配置',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

INSERT INTO `system_sp_vendor` VALUES (1,0,'漫道','Mandao','{\"sn\":\"SDK-CSW-010-00106\",\"password\":\"jBIirmzM\"}','2021-09-03 15:58:17','2021-09-21 09:56:27'),(2,0,'漫道','Mandao1','{\"sn\":\"SDK-CSW-010-00226\",\"password\":\"535770\"}','2021-09-21 09:56:27','2021-09-21 09:56:27');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user_analysis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL,
  `userId` int(11) unsigned NOT NULL,
  `data` int(11) unsigned NOT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户数据';

CREATE TABLE `user_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL COMMENT '租户',
  `name` varchar(40) NOT NULL COMMENT '分组名',
  `users` mediumint(10) unsigned NOT NULL DEFAULT '0' COMMENT '人数',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user_order_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tenantId` int(11) unsigned NOT NULL COMMENT '租户',
  `userId` int(11) unsigned NOT NULL COMMENT '账户',
  `orderAccountId` int(11) unsigned NOT NULL COMMENT '下单账号',
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userId` (`userId`,`orderAccountId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户工号';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

