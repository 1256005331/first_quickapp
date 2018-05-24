/*
Navicat MySQL Data Transfer

Source Server         : h.8090app.com_3306
Source Server Version : 50163
Source Host           : h.8090app.com:3306
Source Database       : h8090app

Target Server Type    : MYSQL
Target Server Version : 50163
File Encoding         : 65001

Date: 2018-05-24 15:42:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for h5_kyy_admin_list
-- ----------------------------
DROP TABLE IF EXISTS `h5_kyy_admin_list`;
CREATE TABLE `h5_kyy_admin_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gname` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '游戏名缩写',
  `gamename` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '游戏名',
  `ico` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '游戏图标',
  `gserver` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '区服',
  `category` tinyint(255) DEFAULT NULL COMMENT '游戏分类  1、热门 2、传奇 3、魔幻 4、仙侠 5、其他',
  `open_time` int(11) DEFAULT NULL COMMENT '开服时间',
  `creation_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=187 DEFAULT CHARSET=latin1;
