# Host: localhost  (Version: 5.7.36)
# Date: 2021-12-15 15:00:23
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "shop_product"
#

DROP TABLE IF EXISTS `shop_product`;
CREATE TABLE `shop_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品品分类ID',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '商品名称',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '商品状态',
  `detail` longtext COMMENT '商品详情',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '商品简介',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `number` int(11) NOT NULL DEFAULT '0' COMMENT '商品数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COMMENT='商品信息';

#
# Data for table "shop_product"
#

INSERT INTO `shop_product` VALUES (1,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 01:59:32',100),(2,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 02:00:11',100),(3,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 02:02:02',100),(4,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 02:02:37',100),(5,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 02:02:49',100),(6,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 02:02:52',100),(7,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 02:03:15',100),(8,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 02:05:34',100),(9,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 02:17:43',100),(10,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 02:17:53',100),(19,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 02:28:40',100),(31,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 02:45:09',100),(32,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 02:55:01',100),(33,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 02:55:38',100),(34,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 03:04:39',100),(35,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 03:05:56',100),(40,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 03:14:54',100),(41,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 03:15:57',100),(42,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 05:14:43',100),(43,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 09:06:23',100),(44,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 09:06:41',100),(45,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 09:07:59',100),(46,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 09:08:10',100),(47,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 09:08:42',100),(48,0,2,'productName',100.00,1,NULL,'测试数据-简介','2021-12-13 09:08:51',100);

#
# Structure for table "shop_product_category"
#

DROP TABLE IF EXISTS `shop_product_category`;
CREATE TABLE `shop_product_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(8) NOT NULL COMMENT '商品分类名称',
  `parent_id` int(11) NOT NULL COMMENT '商品分类的父ID',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态 0 正常 1 禁用',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='商品分类';

#
# Data for table "shop_product_category"
#

INSERT INTO `shop_product_category` VALUES (8,'测试分类名称',-1,1,'2021-12-09 07:50:18');

#
# Structure for table "shop_product_info"
#

DROP TABLE IF EXISTS `shop_product_info`;
CREATE TABLE `shop_product_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL COMMENT '商品ID',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '商品参数名',
  `value` varchar(64) NOT NULL DEFAULT '' COMMENT '商品参数值',
  `type` tinyint(4) DEFAULT '1' COMMENT '参数类型',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COMMENT='商品参数表';

#
# Data for table "shop_product_info"
#

INSERT INTO `shop_product_info` VALUES (1,31,'所属游戏','王',1,'2021-12-13 02:45:17'),(2,31,'尺寸','王',1,'2021-12-13 02:45:17'),(3,32,'所属游戏','王',1,'2021-12-13 02:55:04'),(4,32,'尺寸','王',1,'2021-12-13 02:55:04'),(5,33,'所属游戏','王',1,'2021-12-13 02:55:42'),(6,33,'尺寸','王',1,'2021-12-13 02:55:42'),(7,34,'所属游戏','王',1,'2021-12-13 03:04:39'),(8,34,'尺寸','王',1,'2021-12-13 03:04:39'),(9,35,'所属游戏','王',1,'2021-12-13 03:05:56'),(10,35,'尺寸','王',1,'2021-12-13 03:05:56'),(19,40,'所属游戏','王',1,'2021-12-13 03:14:54'),(20,40,'尺寸','王',1,'2021-12-13 03:14:54'),(21,41,'所属游戏','王',1,'2021-12-13 03:15:57'),(22,41,'尺寸','王',1,'2021-12-13 03:15:57'),(23,42,'所属游戏','王',1,'2021-12-13 05:14:43'),(24,42,'尺寸','王',1,'2021-12-13 05:14:43'),(25,43,'所属游戏','王',1,'2021-12-13 09:06:23'),(26,43,'尺寸','王',1,'2021-12-13 09:06:23'),(27,44,'所属游戏','王',1,'2021-12-13 09:06:41'),(28,44,'尺寸','王',1,'2021-12-13 09:06:41'),(29,45,'所属游戏','王',1,'2021-12-13 09:07:59'),(30,45,'尺寸','王',1,'2021-12-13 09:07:59'),(31,46,'所属游戏','王',1,'2021-12-13 09:08:10'),(32,46,'尺寸','王',1,'2021-12-13 09:08:10'),(33,47,'所属游戏','王',1,'2021-12-13 09:08:42'),(34,47,'尺寸','王',1,'2021-12-13 09:08:42'),(35,48,'所属游戏','王',1,'2021-12-13 09:08:51'),(36,48,'尺寸','王',1,'2021-12-13 09:08:51');

#
# Structure for table "shop_product_related_resource"
#

DROP TABLE IF EXISTS `shop_product_related_resource`;
CREATE TABLE `shop_product_related_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `resource_id` int(11) NOT NULL DEFAULT '0',
  `resource_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '商品资源的类型 1 商品首页展示图 2 商品详情图',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;

#
# Data for table "shop_product_related_resource"
#

INSERT INTO `shop_product_related_resource` VALUES (1,1,1,1),(2,32,1,2),(3,32,2,3),(4,33,1,2),(5,33,2,3),(6,34,1,2),(7,34,2,3),(8,35,1,2),(9,35,2,3),(10,40,1,2),(11,40,2,3),(12,41,1,2),(13,41,2,3),(14,42,1,2),(15,42,2,3),(16,43,1,2),(17,43,2,3),(18,44,1,2),(19,44,2,3),(20,45,1,2),(21,45,2,3),(22,46,1,2),(23,46,2,3),(24,47,1,2),(25,47,2,3),(26,48,1,2),(27,48,2,3);

#
# Structure for table "sys_admin"
#

DROP TABLE IF EXISTS `sys_admin`;
CREATE TABLE `sys_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `salt` varchar(255) NOT NULL DEFAULT '123456',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "sys_admin"
#


#
# Structure for table "sys_resource"
#

DROP TABLE IF EXISTS `sys_resource`;
CREATE TABLE `sys_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '文件的上传者',
  `size` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '文件名称',
  `mine` varchar(16) NOT NULL DEFAULT '' COMMENT '文件类型',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '文件对外访问路径',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

#
# Data for table "sys_resource"
#

INSERT INTO `sys_resource` VALUES (1,0,0,'doemload.jpg','image/jpeg','','2021-12-10 07:27:04'),(2,0,0,'doemload.jpg','image/jpeg','','2021-12-10 07:28:53'),(3,0,0,'doemload.jpg','image/jpeg','','2021-12-10 07:28:58'),(4,0,16705,'doemload.jpg','image/jpeg','','2021-12-10 07:29:47'),(5,0,16705,'doemload.jpg','image/jpeg','20211210/2021121015301561b30207aa1cf3.18059114.jpg','2021-12-10 07:30:14');
