insert into `acl_route` (`tenantId`, `menuId`, `name`, `route`, `key`, `createdAt`, `updatedAt`) values('0','2','解析导入','POST:/parse','e14088c41546fb9b','2021-11-11 17:10:04','2021-11-11 17:10:07'),('0','2','解析列表','GET:/parse','903591220a6b5b6b','2021-11-11 17:10:04','2021-11-11 17:10:07'),('0','2','详情列表','GET:/parse/{importId}','786f74260fe1fd02','2021-11-11 17:10:04','2021-11-11 17:10:07'),('0','2','解析开始','GET:/parse/analysis/{importId}','06c458ffa84ea24e','2021-11-11 17:10:04','2021-11-11 17:10:07'),('0','2','解析数据下载','GET:/parse/download/{importId}','f0c1704f70020177','2021-11-11 17:10:04','2021-11-11 17:10:07'),('0','2','解析删除','DELETE:/parse/{importId}','cb8c7b57b17b98b1','2021-11-11 17:10:04','2021-11-11 17:10:07'),('0','2','解析数据接收','POST:/parse/analysis/{importId}','de690d43dd2bc089','2021-11-11 17:10:04','2021-11-11 17:10:07');
CREATE TABLE `parse`  (
                        `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                        `tenantId` int(11) UNSIGNED NOT NULL COMMENT 'SaaSId',
                        `fileId` int(10) UNSIGNED NOT NULL COMMENT '所在文件Id',
                        `license` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '车牌号',
                        `engineNo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '发动机号',
                        `vin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '车架号',
                        `modelCode` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '车型编码',
                        `firstRegisterDate` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '初登时间',
                        `ownerName` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '车主姓名',
                        `ownerCard` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '车主证件号',
                        `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '手机号',
                        `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '地址',
                        `lastSignTime` timestamp NULL DEFAULT NULL COMMENT '上年签单日期',
                        `lastCompany` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '上年保险公司',
                        `lastPolicy` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '上年保单号',
                        `lastStartDate` timestamp NULL DEFAULT NULL COMMENT '上年起保日期',
                        `lastEndDate` timestamp NULL DEFAULT NULL COMMENT '上年终保日期',
                        `coverages` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '险种',
                        `status` enum('TOPARSE','FINISH','ERROR') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'TOPARSE' COMMENT '解析状态',
                        PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35009 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

CREATE TABLE `parse_file`  (
                             `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                             `tenantId` int(11) UNSIGNED NOT NULL COMMENT 'SaaSId',
                             `fileName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '文件名字',
                             `total` int(11) NOT NULL DEFAULT 0 COMMENT '总数',
                             `finish` int(11) NOT NULL DEFAULT 0 COMMENT '成功数',
                             `error` int(11) NOT NULL DEFAULT 0 COMMENT '失败数',
                             `status` enum('IMPORTING','FINISH','ERROR','PARSING','TOPARSE','PARTLY','FAILURE') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'IMPORTING',
                             `isDelete` tinyint(4) NOT NULL DEFAULT 0,
                             `parseAt` timestamp NULL DEFAULT NULL COMMENT '解析时间',
                             `createAt` timestamp NULL DEFAULT NULL,
                             PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 103 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

ALTER TABLE `lightning`.`parse` MODIFY COLUMN `lastStartDate` timestamp NULL DEFAULT NULL COMMENT '上年起保日期' AFTER `lastPolicy`;

ALTER TABLE `lightning`.`parse` MODIFY COLUMN `lastEndDate` timestamp NULL DEFAULT NULL COMMENT '上年终保日期' AFTER `lastStartDate`;
