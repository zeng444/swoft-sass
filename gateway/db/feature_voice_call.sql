ALTER TABLE `message` ADD COLUMN `voiceId` varchar(30)  COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '语音Id,为空为不使用' after `rows` , CHANGE `createdAt` `createdAt` timestamp   NULL COMMENT '发送时间' after `voiceId` ;
CREATE TABLE `voice_statistics`(`id` int(11) unsigned NOT NULL  auto_increment ,`tenantId` int(11) unsigned NOT NULL  ,`code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL  COMMENT '接口模板代号' ,`times` tinyint(3) unsigned NOT NULL  DEFAULT 1 COMMENT '发送次数' ,`remain` int(11) NULL  COMMENT '剩余次数' ,`createdAt` timestamp NULL  on update CURRENT_TIMESTAMP ,`updatedAt` timestamp NULL  ,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET='utf8mb4' COLLATE='utf8mb4_general_ci';
CREATE TABLE `voice_recharge_log` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`tenantId` int(11) unsigned DEFAULT NULL COMMENT '租户',`quantity` int(10) unsigned DEFAULT NULL COMMENT '短息数量',`operatorType` enum('ADMIN','TENANT') DEFAULT NULL COMMENT '充值人类型',`operatorId` int(10) unsigned NOT NULL COMMENT '充值人，管理员ID或者用户Id',`remark` varchar(30) DEFAULT NULL COMMENT '充值备注',`createdAt` timestamp NULL DEFAULT NULL,`updatedAt` timestamp NULL DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
ALTER TABLE `message` CHANGE `isVoiced` `isVoiced` TINYINT(3) UNSIGNED DEFAULT 0 NOT NULL COMMENT '语音是否接听';
ALTER TABLE `message` ADD INDEX `voice` (`voiceId`);
ALTER TABLE `quote_batch` ADD COLUMN `isPhone` TINYINT UNSIGNED DEFAULT 0 NOT NULL COMMENT '是否电话' AFTER `total`;
ALTER TABLE `lightning`.`quote_batch_customer` ADD COLUMN `isPhone` TINYINT UNSIGNED DEFAULT 0 NOT NULL COMMENT '是否电话' AFTER `error`;
INSERT INTO `voice_statistics` (`tenantId`,`code`,`times`,`remain`,`createdAt`,`updatedAt`) SELECT tenantId,0,0,0,`createdAt`,`updatedAt` FROM `message_statistics`;


