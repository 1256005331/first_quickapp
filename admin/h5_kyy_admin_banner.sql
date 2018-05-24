/*
Navicat MySQL Data Transfer

Source Server         : h.8090app.com_3306
Source Server Version : 50163
Source Host           : h.8090app.com:3306
Source Database       : h8090app

Target Server Type    : MYSQL
Target Server Version : 50163
File Encoding         : 65001

Date: 2018-05-24 15:42:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for h5_kyy_admin_banner
-- ----------------------------
DROP TABLE IF EXISTS `h5_kyy_admin_banner`;
CREATE TABLE `h5_kyy_admin_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imagePath` varchar(255) NOT NULL COMMENT '图片地址',
  `url` varchar(255) NOT NULL COMMENT 'banner跳转链接',
  `status` tinyint(1) NOT NULL COMMENT '状态  1、不显示   2、显示',
  `creation_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
