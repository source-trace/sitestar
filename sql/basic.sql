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


DROP TABLE IF EXISTS `ss_admin_menu_categories`;

CREATE TABLE `ss_admin_menu_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

insert  into `ss_admin_menu_categories`(`id`,`category_name`,`module`,`action`) values (1,'Settings','mod_site','admin_dashboard'),(2,'Pages','mod_menu_item','admin_dashboard'),(3,'Contents','mod_article','admin_dashboard'),(4,'Layouts','mod_modules','index'),(5, 'Files', 'mod_filemanager', 'admin_dashboard'),(6,'Users','mod_user','admin_dashboard'),(7,'Licenses','mod_authorization','admin_list');

DROP TABLE IF EXISTS `ss_admin_menu_items`;
CREATE TABLE `ss_admin_menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `priority` int(11) NOT NULL DEFAULT '10',
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module` (`module`),
  KEY `priority` (`priority`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

insert  into `ss_admin_menu_items`(`id`,`module`,`action`,`text`,`level`,`priority`,`category_id`) values (1,'mod_article','admin_list','Articles',1,1,3),(3,'mod_friendlink','admin_list','Hot Links',1,5,1),(25,'mod_backup','admin_list','Data Bakup/Recovery',1,6,1),(6,'mod_product','admin_list','Products',1,2,3),(8,'mod_download','admin_list','Downloads',1,3,3),(9,'mod_static','admin_list','Static Contents',2,2,2),(10,'mod_qq','admin_list','Services',1,2,1),(12,'mod_message','admin_list','Messages',1,4,3),(13,'mod_menu_item','admin_list','Site Columns',1,1,2),(15,'mod_payaccount','admin_list','Payment Accounts',2,3,1),(16,'mod_order','admin_list','Orders',2,2,6),(19,'mod_statistics','admin_list','Statistics',1,4,1),(20,'mod_user','admin_list','Users',1,1,6),(22,'mod_navigation','admin_list','Homepage Guidances',1,3,2),(23,'mod_template','admin_list','Templates',1,4,2),(24,'mod_site','admin_list','Web Settings',1,1,1),(26, 'mod_attachment', 'admin_list', 'Image Watermark/Thumb', '1', '7', '1'),(27, 'mod_advert', 'admin_list','Advert Tool', '1', '8', '1'),(28,'mod_sitestarmaker','admin_list','Picture Tool','1','9','1'),(29, 'mod_bulletin', 'admin_list', 'Bulletins', '1', '5', '3');

DROP TABLE IF EXISTS `ss_admin_shortcuts`;
CREATE TABLE `ss_admin_shortcuts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`),
  KEY `module` (`module`),
  KEY `priority` (`priority`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_article_categories`;
CREATE TABLE `ss_article_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `article_category_id` int(11) NOT NULL DEFAULT '0',
  `i_order` int(11) NOT NULL DEFAULT '0',
  `s_locale` varchar(50) NOT NULL,
  `published` enum('0','1') NOT NULL DEFAULT '1',
  `for_roles` varchar(50) NOT NULL DEFAULT '{guest}',
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`),
  KEY `article_category_id` (`article_category_id`),
  KEY `i_order` (`i_order`),
  KEY `s_locale` (`s_locale`),
  KEY `published` (`published`),
  KEY `for_roles` (`for_roles`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_articles`;
CREATE TABLE `ss_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `i_order` int(11) NOT NULL DEFAULT '0',
  `source` text,
  `tags` varchar(255) DEFAULT NULL,
  `intro` text,
  `content` LongText,
  `create_time` bigint(20) NOT NULL,
  `s_locale` varchar(50) NOT NULL,
  `pub_start_time` bigint(20) NOT NULL,
  `pub_end_time` bigint(20) NOT NULL,
  `published` enum('0','1') NOT NULL DEFAULT '1',
  `for_roles` varchar(50) NOT NULL DEFAULT '{guest}',
  `v_num` bigint(20) NOT NULL DEFAULT '0',
  `article_category_id` int(11) NOT NULL,
  `is_seo` enum('0','1') DEFAULT '0',
  `description` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `s_locale` (`s_locale`),
  KEY `published` (`published`),
  KEY `for_roles` (`for_roles`),
  KEY `article_category_id` (`article_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_bulletins`;
CREATE TABLE `ss_bulletins` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `content` text,
  `create_time` bigint(20) NOT NULL,
  `s_locale` varchar(50) NOT NULL,
  `pub_start_time` bigint(20) NOT NULL,
  `pub_end_time` bigint(20) NOT NULL,
  `published` enum('0','1') NOT NULL default '1',
  `for_roles` varchar(50) NOT NULL default '{guest}',
  PRIMARY KEY  (`id`),
  KEY `title` (`title`),
  KEY `create_time` (`create_time`),
  KEY `s_locale` (`s_locale`),
  KEY `pub_start_time` (`pub_start_time`),
  KEY `pub_end_time` (`pub_end_time`),
  KEY `published` (`published`),
  KEY `for_roles` (`for_roles`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_delivery_addresses`;
CREATE TABLE `ss_delivery_addresses` (
  `id` int(11) NOT NULL auto_increment,
  `reciever_name` varchar(255) NOT NULL,
  `prov_id` varchar(6) NOT NULL,
  `city_id` varchar(6) NOT NULL,
  `dist_id` varchar(6) NOT NULL,
  `detailed_addr` text NOT NULL,
  `postal` varchar(6) NOT NULL,
  `phone` varchar(24) default NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `prov_id` (`prov_id`),
  KEY `city_id` (`city_id`),
  KEY `dist_id` (`dist_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_delivery_methods`;
CREATE TABLE `ss_delivery_methods` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_download_categories`;
CREATE TABLE `ss_download_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `i_order` int(11) NOT NULL default '0',
  `s_locale` varchar(50) NOT NULL,
  `published` enum('0','1') NOT NULL default '1',
  `for_roles` varchar(50) NOT NULL default '{guest}',
  PRIMARY KEY  (`id`),
  KEY `alias` (`alias`),
  KEY `i_order` (`i_order`),
  KEY `s_locale` (`s_locale`),
  KEY `published` (`published`),
  KEY `for_roles` (`for_roles`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_downloads`;
CREATE TABLE `ss_downloads` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) default NULL,
  `create_time` bigint(20) NOT NULL,
  `pub_start_time` bigint(20) NOT NULL,
  `pub_end_time` bigint(20) NOT NULL,
  `s_locale` varchar(50) NOT NULL,
  `published` enum('0','1') NOT NULL default '1',
  `for_roles` varchar(50) NOT NULL default '{guest}',
  `download_category_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `create_time` (`create_time`),
  KEY `s_locale` (`s_locale`),
  KEY `published` (`published`),
  KEY `for_roles` (`for_roles`),
  KEY `download_category_id` (`download_category_id`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_friendlinks`;
CREATE TABLE `ss_friendlinks` (
  `id` int(11) NOT NULL auto_increment,
  `fl_name` varchar(50) default NULL,
  `fl_img` varchar(50) default NULL,
  `fl_addr` varchar(255),
  `s_locale` varchar(50) NOT NULL,
  `create_time` bigint(20) NOT NULL,
  `i_order` INT( 5 ) NOT NULL DEFAULT '0',
  `for_roles` varchar(50) NOT NULL default '{guest}',
  `published` enum('0','1') NOT NULL default '1',
  `fl_type` char(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `for_roles` (`for_roles`),
  KEY `s_locale` (`s_locale`),
  KEY `published` (`published`),
  KEY `create_time` (`create_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_languages`;
CREATE TABLE `ss_languages` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `locale` varchar(50) NOT NULL,
  `published` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `locale` (`locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `ss_languages` VALUES (2,'简体中文','zh_CN','1');

DROP TABLE IF EXISTS `ss_menu_items`;
CREATE TABLE `ss_menu_items` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL default '#',
  `mi_category` varchar(50) NOT NULL default 'outer_url',
  `link_type` varchar(50) NOT NULL default '',
  `selected_content` varchar(255) NOT NULL default '',
  `menu_item_id` int(11) NOT NULL default '0',
  `i_order` int(11) NOT NULL default '0',
  `s_locale` varchar(50) NOT NULL,
  `published` varchar(20) NOT NULL default '1',
  `for_roles` varchar(50) NOT NULL default '{guest}',
  `menu_id` int(11) NOT NULL default '0',
  `layout` varchar(100) NOT NULL default 'default',
  `meta_key` text,
  `meta_desc` text,
  `title` varchar(100) default '',
  `url` varchar(255) default '',
  `content_id` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `menu_item_id` (`menu_item_id`),
  KEY `i_order` (`i_order`),
  KEY `s_locale` (`s_locale`),
  KEY `for_roles` (`for_roles`),
  KEY `mi_category` (`mi_category`),
  KEY `published` (`published`),
  KEY `menu_id` (`menu_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_menus`;
CREATE TABLE `ss_menus` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `s_locale` varchar(50) NOT NULL,
  `published` enum('0','1') NOT NULL default '1',
  `for_roles` varchar(50) NOT NULL default '{guest}',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `s_locale` (`s_locale`),
  KEY `published` (`published`),
  KEY `for_roles` (`for_roles`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_messages`;
CREATE TABLE `ss_messages` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tele` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `param` text NULL,
  `create_time` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_module_blocks`;
CREATE TABLE `ss_module_blocks` (
  `id` int(11) NOT NULL auto_increment,
  `module` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `title` varchar(255) default NULL,
  `show_title` enum('0','1') NOT NULL default '1',
  `s_pos` varchar(50) NOT NULL,
  `s_param` longtext,
  `s_locale` varchar(50) NOT NULL,
  `s_query_hash` char(40) NOT NULL,
  `i_order` int(11) NOT NULL default '0',
  `published` enum('0','1') NOT NULL default '1',
  `for_roles` varchar(50) NOT NULL default '{guest}',
  `s_token` varchar(40) DEFAULT NULL,
  `perpage_show` varchar(40) DEFAULT NULL,
  PRIMARY KEY  (`id`),
  KEY `s_pos` (`s_pos`),
  KEY `s_locale` (`s_locale`),
  KEY `s_query_hash` (`s_query_hash`),
  KEY `i_order` (`i_order`),
  KEY `published` (`published`),
  KEY `for_roles` (`for_roles`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_navigations`;
CREATE TABLE `ss_navigations` (
  `id` int(11) NOT NULL auto_increment,
  `navigation` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `navigation` (`navigation`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_online_orders`;
CREATE TABLE `ss_online_orders` (
  `id` int(11) NOT NULL auto_increment,
  `oid` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reciever_name` varchar(255) default NULL,
  `prov_id` varchar(6) default NULL,
  `city_id` varchar(6) default NULL,
  `dist_id` varchar(6) default NULL,
  `detailed_addr` text,
  `postal` varchar(6) default NULL,
  `phone` varchar(24) default NULL,
  `delivery_fee` decimal(16,2) NOT NULL default '0.00',
  `total_price` decimal(16,2) NOT NULL default '0.00',
  `discount_price` decimal(16,2) NOT NULL default '0.00',
  `total_amount` decimal(16,2) NOT NULL default '0.00',
  `message` text default NULL,
  `order_time` bigint(20) NOT NULL,
  `order_status` enum('1','2','3','100','101') NOT NULL default '1',
  `anonymous_passwd` char(40) default NULL,
  PRIMARY KEY  (`id`),
  KEY `oid` (`oid`),
  KEY `user_id` (`user_id`),
  KEY `order_time` (`order_time`),
  KEY `order_status` (`order_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_online_qqs`;
CREATE TABLE `ss_online_qqs` (
  `id` int(11) NOT NULL auto_increment,
  `account` varchar(255) NOT NULL,
  `category` enum('0','1','2','3','4','5','6') default NULL,
  `i_order` int(3)  NOT NULL default '0', 
  `published` enum('0','1') NOT NULL default '1',
  `qqname` varchar(255) NOT NULL,
  `s_locale` varchar(50) NOT NULL default 'zh_CN',
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_onlinepay_histories`;
CREATE TABLE `ss_onlinepay_histories` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `outer_oid` varchar(255) NOT NULL,
  `payment_provider_id` int(11) NOT NULL,
  `send_time` bigint(20) NOT NULL,
  `return_time` bigint(20) NOT NULL,
  `finished` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `outer_oid` (`outer_oid`),
  KEY `payment_provider_id` (`payment_provider_id`),
  KEY `send_time` (`send_time`),
  KEY `return_time` (`return_time`),
  KEY `finished` (`finished`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_order_products`;
CREATE TABLE `ss_order_products` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `online_order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_thumb` varchar(255) default NULL,
  `price` decimal(16,2) NOT NULL default '0.00',
  `amount` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_parameters`;
CREATE TABLE `ss_parameters` (
  `id` int(11) NOT NULL auto_increment,
  `key` varchar(50) NOT NULL,
  `val` text,
  PRIMARY KEY  (`id`),
  KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

insert  into `ss_parameters`(`id`,`key`,`val`) values (1,'DEFAULT_MODULE','frontpage'),(2,'DEFAULT_ACTION','index'),(4,'USE_SMTP','0'),(8,'MAIL_CHARSET','utf-8'),(9,'ENABLE_SMTP_DEBUG','0'),(10,'MAGIC_QUOTES_GPC_ON','0'),(11,'PAGE_SIZE','16'),(12,'PAGE_404','404.html'),(13,'DB_SESSION','0'),(14,'DEFAULT_LOCALE','zh_CN'),(15,'LOCALE_CHARSET','UTF-8'),(16,'AUTO_LOCALE','0'),(17,'PIC_ALLOW_EXT','gif|jpg|png|bmp'),(18,'FILE_ALLOW_EXT','doc|xls|ppt|zip|gz|rar|pdf|gif|jpg|png|bmp'),(19,'SITE_OFFLINE','0'),(20,'SITE_OFFLINE_MSG','网站正在维护中，请稍候访问！谢谢！'),(26,'USER_STEP','1'),(28,'SITE_COUNTER_NUM','808'),(27,'SITE_COUNTER','0'),(25,'SVC_TPL','http://template.sitestar.cn/sitestar27/'),(30,'EZSITE_LEVEL','2'),(31,'ALLOWED_MOD','frontpage,mod_article,mod_auth,mod_cart,mod_category_a,mod_category_p,mod_counter,mod_download,mod_download_category,mod_friendlink,mod_lang,mod_media,mod_menu,mod_message,mod_navigation,mod_offline,mod_onlinepay,mod_order,mod_product,mod_qq,mod_static,mod_template,mod_tool,mod_user,mod_menu_item,mod_modules,mod_param,mod_payaccount,mod_site,mod_news,mod_statistics,mod_database,mod_filemanager,mod_backup,mod_wizard,mod_about,mod_marquee,mod_attachment,mod_advert,mod_sitestarmaker,mod_bulletin,mod_page,mod_content,mod_roles,mod_category_d,mod_bshare,mod_user_field,mod_third_account,mod_email,mod_msg_field,'),(33,'SITE_LOGIN_VCODE','1'),(35,'SYSVER','sitestar_v2.7_build131012'),(36,'SITE_HAOSH',''),(37,'COPYRIGHT',''),(29,'EZSITE_S','83d5de05e4d9a6537a97bd6c70768db71a93596dca73880d42552c84b403786d'),(38,'DEFAULT_TPL_ID','0'),(39,'MUSIC_ALLOW_EXT','mp3|wma'),(40,'SERVICE53',''),(41,'EZSITE_UID','cndns'),(42,'DEFAULT_NAV','0'),(43,'BANNER_ISLINK','no'),(44,'BANNER_LINK_ADDR','#'),(47,'WEB_ICP','沪ICP备XXXXXX号'),(48,'MOD_REWRITE','1'),(49, 'THUMB_STATUS','2'), (50, 'THUMB_QUALITY', '75'),(51, 'THUMB_WIDTH', '400'), (52, 'THUMB_HEIGHT', '300'), (53, 'WATERMARK_STATUS', '0'), (54, 'WATERMARK_MIN_WIDTH', '400'),(55, 'WATERMARK_MIN_HEIGHT', '300'), (56, 'WATERMARK_TYPE', '2'), (57, 'WATERMARK_QUALITY', '75'),(58, 'WATERMARK_TEXT', '水印文字'), (59, 'WATERMARK_TEXT_SIZE', '24'),(60, 'WATERMARK_TEXT_ANGLE', '0'), (61, 'WATERMARK_TEXT_COLOR', '#000000'),(62, 'WATERMARK_TEXT_SHADOWX', '1'), (63, 'WATERMARK_TEXT_SHADOWY', '1'), (64, 'WATERMARK_TEXT_SHADOW_COLOR', '#000000'),(65, 'WATERMARK_PNG', 'images/watermark.png'),(66, 'ADVERT_STATUS', '0'), (67, 'ADVERT_THEME', ''), (68, 'ADVERT_TEXT', ''), (69, 'ADVERT_TEXT_SIZE', '32'), (70, 'ADVERT_TEXT_COLOR', '#000000'), (71, 'ADVERT_URL', 'http://www.example.com'),  (72, 'ADVERT_RTHEME', ''), (73, 'ADVERT_RTEXT', ''),  (74, 'ADVERT_RTEXT_SIZE', '32'), (75, 'ADVERT_RTEXT_COLOR', '#000000'),  (76, 'ADVERT_RURL', 'http://www.example.com'), (77, 'TABLE_CACHE', '1'),(78,'EXCHANGE_SWITCH','1'), (79, 'QQ_ONLINE', '0'), (80, 'QQ_ONLINE_TITLE', ''), (81, 'QQ_ONLINE_POS', 'left'), (82, 'ADVERT_LTARGET', '_blank'), (83, 'ADVERT_RTARGET', '_blank'),(84, 'LICENCE_TIME', ''),(85, 'LICENCE_CHECK_SUM', ''),(86, 'ERR_LOG', ''),(87, 'MEMBER_VERIFY', '0'),(88, 'CURRENCY', 'CNY'),(89, 'CURRENCY_SIGN', '￥'),(90, 'BACKGROUND_INFO', ''),(91, 'INIT3', '1'),(92, 'QQ_SHOW_TYPE', '1');

DROP TABLE IF EXISTS `ss_payment_accounts`;
CREATE TABLE `ss_payment_accounts` (
  `id` int(11) NOT NULL auto_increment,
  `payment_provider_id` int(11) NOT NULL,
  `seller_site_url` varchar(255) default NULL,
  `seller_account` varchar(255) default NULL,
  `partner_id` varchar(255) NOT NULL,
  `partner_key` varchar(255) NOT NULL,
  `enabled` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `ss_payment_accounts` VALUES (1,1,'','','','',''),(2,2,'','','','',''),(6,6,'','','','',''),(7,7,'','','','',''),(8,8,'','','','',''),(9,9,'','','','','');
INSERT INTO `ss_payment_accounts` VALUES (3, 3, '', '', '', '', '');
INSERT INTO `ss_payment_accounts` VALUES (4, 4, '', '', '', '', '');
INSERT INTO `ss_payment_accounts` VALUES (5, 5, '', '', '', '', '');

DROP TABLE IF EXISTS `ss_payment_providers`;
CREATE TABLE `ss_payment_providers` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `disp_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `ss_payment_providers` VALUES (1,'alipay','支付宝'),(2,'99bill','快钱'),(6,'alipaymed','支付宝中介担保'),(7,'alipayimd','支付宝即时到账'),(8,'paypalen','PayPal(外卡帐号)'),(9,'moneybookers','MONEYBOOKERS');
INSERT INTO `ss_payment_providers` VALUES (3, 'paypal', '中国贝宝');
INSERT INTO `ss_payment_providers` VALUES (4, 'tencentmed', '财付通中介担保');
INSERT INTO `ss_payment_providers` VALUES (5, 'tencentimd', '财付通立即到账');

DROP TABLE IF EXISTS `ss_product_categories`;
CREATE TABLE `ss_product_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `product_category_id` int(11) NOT NULL default '0',
  `i_order` int(11) NOT NULL default '0',
  `s_locale` varchar(50) NOT NULL,
  `published` enum('0','1') NOT NULL default '1',
  `for_roles` varchar(50) NOT NULL default '{guest}',
  PRIMARY KEY  (`id`),
  KEY `alias` (`alias`),
  KEY `product_category_id` (`product_category_id`),
  KEY `i_order` (`i_order`),
  KEY `s_locale` (`s_locale`),
  KEY `published` (`published`),
  KEY `for_roles` (`for_roles`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_product_pics`;
CREATE TABLE `ss_product_pics` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `pic` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ss_products`;
CREATE TABLE `ss_products` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `i_order` int(11) NOT NULL default '0',
  `feature_img` varchar(255) default NULL,
  `feature_smallimg` varchar(255) default NULL,
  `introduction` text,
  `description` longtext,
  `price` decimal(16,2) NOT NULL default '0.00',
  `discount_price` decimal(16,2) NOT NULL default '0.00',
  `delivery_fee` decimal(16,2) NOT NULL default '0.00',
  `online_orderable` enum('0','1') NOT NULL default '1',
  `recommended` enum('0','1') NOT NULL default '0',
  `create_time` bigint(20) NOT NULL,
  `product_category_id` int(11) NOT NULL default '0',
  `s_locale` varchar(50) NOT NULL,
  `pub_start_time` bigint(20) NOT NULL,
  `pub_end_time` bigint(20) NOT NULL,
  `published` enum('0','1') NOT NULL default '1',
  `for_roles` varchar(50) NOT NULL default '{guest}',
  `is_seo` enum('0','1') DEFAULT '0',
  `meta_key` text,
  `meta_desc` text,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `recommended` (`recommended`),
  KEY `create_time` (`create_time`),
  KEY `product_category_id` (`product_category_id`),
  KEY `s_locale` (`s_locale`),
  KEY `pub_start_time` (`pub_start_time`),
  KEY `pub_end_time` (`pub_end_time`),
  KEY `published` (`published`),
  KEY `for_roles` (`for_roles`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_roles`;
CREATE TABLE `ss_roles` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `desc` varchar(255) NOT NULL,
 `permission`  text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `ss_roles` VALUES (1,'guest','Guest',NULL),(2,'member','Member',NULL),(3,'admin','Admin',NULL);

DROP TABLE IF EXISTS `ss_site_infos`;
CREATE TABLE `ss_site_infos` (
  `id` int(11) NOT NULL auto_increment,
  `site_name` text default NULL,
  `keywords` varchar(255) default NULL,
  `description` text,
  `s_locale` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `s_locale` (`s_locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_static_contents`;
CREATE TABLE `ss_static_contents` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `content` longtext,
  `create_time` bigint(20) NOT NULL,
  `s_locale` varchar(50) NOT NULL,
  `published` enum('0','1') NOT NULL default '1',
  `for_roles` varchar(50) NOT NULL default '{guest}',
  `flag` varchar(45) NOT NULL default 'static',
  PRIMARY KEY  (`id`),
  KEY `title` (`title`),
  KEY `create_time` (`create_time`),
  KEY `s_locale` (`s_locale`),
  KEY `published` (`published`),
  KEY `for_roles` (`for_roles`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_template_categories`;
CREATE TABLE `ss_template_categories` (
  `id` int(11) NOT NULL auto_increment,
  `category` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_templates`;
CREATE TABLE `ss_templates` (
  `id` int(11) NOT NULL auto_increment,
  `template` varchar(50) NOT NULL,
  `template_category_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `template_category_id` (`template_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_transactions`;
CREATE TABLE `ss_transactions` (
  `id` int(11) NOT NULL auto_increment,
  `amount` decimal(16,2) NOT NULL default '0.00',
  `type` enum('1','2') NOT NULL default '1',
  `memo` text,
  `action_time` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `action_time` (`action_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_user_extends`;
CREATE TABLE `ss_user_extends` (
  `id` int(11) NOT NULL auto_increment,
  `total_saving` decimal(16,2) NOT NULL default '0.00',
  `total_payment` decimal(16,2) NOT NULL default '0.00',
  `balance` decimal(16,2) NOT NULL default '0.00',
  `user_id` int(11) NOT NULL,
  `total_point` int(11) NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_users`;
CREATE TABLE `ss_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(25) NOT NULL,
  `passwd` char(40) NOT NULL,
  `full_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `lastlog_time` bigint(20) DEFAULT NULL,
  `lastlog_ip` varchar(15) DEFAULT NULL,
  `rstpwdreq_time` bigint(20) DEFAULT NULL,
  `rstpwdreq_rkey` char(128) DEFAULT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `s_role` varchar(255) NOT NULL DEFAULT '{guest}',
  `wizard` enum('0','1') DEFAULT '1',
  `mobile` varchar(32) DEFAULT NULL,
  `member_verify` enum('0','1') DEFAULT '1',
  `nickname` varchar(50) DEFAULT NULL,
  `gender` varchar(2) DEFAULT NULL,
  `birthday` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `addr1` varchar(255) DEFAULT NULL,
  `addr2` varchar(255) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `telphone` varchar(50) DEFAULT NULL,
  `params` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`),
  KEY `passwd` (`passwd`),
  KEY `active` (`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_background_musics`;
CREATE TABLE `ss_background_musics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `music_path` varchar(255) NOT NULL,
  `play` int(11) NOT NULL DEFAULT '1',
  `music_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_backups`;
CREATE TABLE `ss_backups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` bigint(20) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_marquees`;
CREATE TABLE `ss_marquees` (
  `id` int(11) NOT NULL auto_increment,
  `module_id` int(11) NOT NULL default '0' COMMENT '模块id',
  `marquee_type` enum('text','pic','picText') NOT NULL default 'pic' COMMENT '走马灯类型',
  `title` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `flag` enum('1','2','3') NOT NULL default '1' COMMENT '1自定义2是库',
  `link` varchar(255) NOT NULL COMMENT '链接地址',
  `i_order` INT( 5 ) NOT NULL default '0',
  `create_time` VARCHAR( 20 ) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_user_fields`;
CREATE TABLE `ss_user_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_type` int(11) NOT NULL,
  `showinlist` enum('0','1') NOT NULL DEFAULT '0',
  `required` enum('0','1') NOT NULL DEFAULT '0',
  `i_order` int(11) NOT NULL,
  `label` text NOT NULL,
  `options` text,
  PRIMARY KEY (`id`),
  KEY `i_order` (`i_order`),
  KEY `list_with_order` (`showinlist`,`i_order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `ss_user_fields` (`id`, `field_type`, `showinlist`, `required`, `i_order`, `label`, `options`) VALUES (1, 0, '1', '0', 1, 'full_name', NULL),(2, 0, '0', '0', 3, 'nickname', NULL),(3, 0, '0', '0', 7, 'country', NULL),(4, 0, '0', '0', 8, 'city', NULL),(5, 0, '0', '0', 5, 'birthday', NULL),(6, 0, '0', '0', 9, 'addr1', NULL),(7, 0, '0', '0', 10, 'zipcode', NULL),(8, 0, '1', '0', 2, 'mobile', NULL),(9, 0, '0', '0', 4, 'gender', NULL),(10, 0, '0', '0', 6, 'telphone', NULL);

DROP TABLE IF EXISTS `ss_third_accounts`;
CREATE TABLE `ss_third_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_type` varchar(50) NOT NULL,
  `appid` varchar(100) NOT NULL DEFAULT '',
  `appsecret` varchar(100) NOT NULL DEFAULT '',
  `active` smallint(6) NOT NULL DEFAULT '0',
  `options` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_type` (`account_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_user_oauths`;
CREATE TABLE `ss_user_oauths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auth_type` varchar(50) NOT NULL,
  `auth_key` varchar(200) NOT NULL,
  `access_code` varchar(200) NOT NULL DEFAULT '',
  `nickname` varchar(200) NOT NULL DEFAULT '',
  `options` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `auth_type` (`auth_type`,`auth_key`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ss_emails`;
CREATE TABLE `ss_emails` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `is_mail` tinyint(1) NOT NULL,
  `send_id` int(6) NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `is_ok` tinyint(1) NOT NULL default '1',
  `create_time` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;


DROP TABLE IF EXISTS `ss_msg_fields`;
CREATE TABLE `ss_msg_fields` (
  `id` int(11) NOT NULL auto_increment,
  `field_type` int(11) NOT NULL,
  `showinlist` enum('0','1') NOT NULL default '0',
  `required` enum('0','1') NOT NULL default '0',
  `i_order` int(11) NOT NULL,
  `label` text NOT NULL,
  `options` text,
  PRIMARY KEY  (`id`),
  KEY `i_order` (`i_order`),
  KEY `list_with_order` (`showinlist`,`i_order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- 导出表中的数据 `ss_msg_fields`
-- 

INSERT INTO `ss_msg_fields` VALUES (1, 0, '1', '1', 1, 'username', NULL);
INSERT INTO `ss_msg_fields` VALUES (2, 0, '1', '1', 2, 'email', NULL);
INSERT INTO `ss_msg_fields` VALUES (3, 0, '1', '1', 3, 'tele', NULL);
INSERT INTO `ss_msg_fields` VALUES (4, 0, '1', '1', 4, 'message', NULL);

DROP TABLE IF EXISTS `ss_users_points`;
CREATE TABLE `ss_users_points` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `orderid` int(11) NOT NULL default '0',
  `type` varchar(5) collate utf8_unicode_ci NOT NULL default 'in',
  `point` int(11) NOT NULL default '0',
  `momo` varchar(255) collate utf8_unicode_ci default NULL,
  `create_time` bigint(8) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
