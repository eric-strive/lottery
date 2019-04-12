-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 09 月 20 日 10:08
-- 服务器版本: 5.5.40
-- PHP 版本: 5.4.33

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `oscshop2_weixin`
--

-- --------------------------------------------------------

--
-- 表的结构 `osc_address`
--

CREATE TABLE IF NOT EXISTS `osc_address` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `address` varchar(128) NOT NULL COMMENT '地址',
  `city_id` int(11) NOT NULL COMMENT '市',
  `country_id` int(11) NOT NULL COMMENT '县乡',
  `province_id` int(11) NOT NULL COMMENT '省',
  PRIMARY KEY (`address_id`),
  KEY `customer_id` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户收货地址' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_cart`
--

CREATE TABLE IF NOT EXISTS `osc_cart` (
  `cart_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT 'money',
  `uid` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `goods_option` text NOT NULL,
  `quantity` int(5) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`cart_id`),
  KEY `cart_id` (`uid`,`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_member`
--

CREATE TABLE IF NOT EXISTS `osc_member` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `reg_type` varchar(20) NOT NULL,
  `wechat_openid` varchar(128) DEFAULT NULL,
  `username` char(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(128) NOT NULL DEFAULT '' COMMENT '密码',
  `checked` tinyint(1) NOT NULL COMMENT '是否审核',
  `address_id` int(8) NOT NULL,
  `nickname` char(20) NOT NULL COMMENT '昵称',
  `sex` tinyint(2) NOT NULL,
  `userpic` varchar(255) NOT NULL COMMENT '会员头像',
  `is_agent` tinyint(2) NOT NULL COMMENT '是否是代理商',
  `pid` mediumint(8) NOT NULL COMMENT '上级id',
  `agent_level` mediumint(8) NOT NULL COMMENT '代理级别',
  `total_bonus` decimal(9,3) NOT NULL COMMENT '代理商奖金',
  `points` mediumint(8) NOT NULL COMMENT '积分',
  `cash_points` mediumint(8) NOT NULL COMMENT '已经兑换积分',
  `wish` smallint(5) NOT NULL COMMENT '收藏的数量',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `regip` char(15) NOT NULL DEFAULT '' COMMENT '注册ip',
  `lastip` char(15) NOT NULL DEFAULT '' COMMENT '上次登录ip',
  `loginnum` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `email` char(32) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `telephone` varchar(20) NOT NULL,
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户组id',
  `areaid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '地区id',
  `message` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有短消息',
  `islock` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否锁定',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_member_auth_group`
--

CREATE TABLE IF NOT EXISTS `osc_member_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `type` varchar(20) NOT NULL,
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `osc_member_auth_group`
--

INSERT INTO `osc_member_auth_group` (`id`, `type`, `title`, `description`, `status`, `rules`) VALUES
(2, '', '普通用户', '普通用户', 1, '13,14,15,19,20,21,22,23,25,26,27,28');

-- --------------------------------------------------------

--
-- 表的结构 `osc_member_auth_group_access`
--

CREATE TABLE IF NOT EXISTS `osc_member_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osc_member_auth_rule`
--

CREATE TABLE IF NOT EXISTS `osc_member_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `group_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_member_menu`
--

CREATE TABLE IF NOT EXISTS `osc_member_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `module` varchar(20) NOT NULL,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `icon` varchar(64) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `type` varchar(40) NOT NULL COMMENT 'nav,auth',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='后台菜单' AUTO_INCREMENT=29 ;

--
-- 转存表中的数据 `osc_member_menu`
--

INSERT INTO `osc_member_menu` (`id`, `module`, `pid`, `title`, `url`, `icon`, `sort_order`, `type`) VALUES
(13, 'member', 0, '个人资料', '', 'fa-users fa-lg', 1, 'nav'),
(14, 'member', 13, '我的资料', 'member/account/profile', '', 1, 'nav'),
(15, 'member', 13, '修改密码', 'member/account/password', '', 2, 'nav'),
(19, 'member', 0, '我的订单', '', 'fa-credit-card fa-lg', 0, 'nav'),
(20, 'member', 13, '地址簿', 'member/account/address', '', 3, 'nav'),
(21, 'member', 19, '订单管理', 'member/order_member/index', '', 1, 'nav'),
(22, 'member', 21, '订单详情', 'member/order_member/show_order', '', 1, 'auth'),
(23, 'member', 21, '订单历史', 'member/order_member/history', '', 0, 'auth'),
(25, 'member', 20, '新增', 'member/account/add_address', '', 0, 'auth'),
(26, 'member', 20, '编辑', 'member/account/edit_address', '', 0, 'auth'),
(27, 'member', 20, '删除', 'member/account/del_address', '', 0, 'auth'),
(28, 'member', 21, '取消订单', 'member/order_member/cancel', '', 0, 'auth');

-- --------------------------------------------------------

--
-- 表的结构 `osc_member_wishlist`
--

CREATE TABLE IF NOT EXISTS `osc_member_wishlist` (
  `uid` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  KEY `uid` (`uid`,`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收藏的商品';

-- --------------------------------------------------------

--
-- 表的结构 `osc_order`
--

CREATE TABLE IF NOT EXISTS `osc_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_num_alias` varchar(40) NOT NULL COMMENT '订单编号',
  `pay_subject` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `return_points` int(11) NOT NULL COMMENT '可得积分',
  `pay_points` int(11) NOT NULL COMMENT '兑换需要的积分',
  `points_order` int(11) NOT NULL DEFAULT '0' COMMENT '是否是积分兑换订单',
  `name` varchar(32) NOT NULL COMMENT '购买的会员名字',
  `email` varchar(64) NOT NULL,
  `tel` varchar(64) NOT NULL,
  `shipping_name` varchar(32) NOT NULL COMMENT '收货人姓名',
  `address_id` int(11) NOT NULL,
  `shipping_tel` varchar(20) NOT NULL COMMENT '收货人电话',
  `shipping_city_id` int(11) NOT NULL,
  `shipping_country_id` int(11) NOT NULL,
  `shipping_province_id` int(11) NOT NULL,
  `shipping_method` varchar(128) NOT NULL COMMENT '货运方式',
  `payment_code` varchar(128) NOT NULL COMMENT '支付方式',
  `comment` text NOT NULL,
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `order_status_id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `user_agent` varchar(255) NOT NULL COMMENT '用户系统信息',
  `date_added` int(11) NOT NULL,
  `date_modified` int(11) NOT NULL,
  `pay_time` int(11) NOT NULL COMMENT '付款时间',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_order_goods`
--

CREATE TABLE IF NOT EXISTS `osc_order_goods` (
  `order_goods_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(64) NOT NULL,
  `quantity` int(4) NOT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `reward` int(8) NOT NULL,
  PRIMARY KEY (`order_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_order_history`
--

CREATE TABLE IF NOT EXISTS `osc_order_history` (
  `order_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `order_status_id` int(5) NOT NULL,
  `notify` tinyint(1) NOT NULL,
  `comment` text NOT NULL,
  `date_added` int(11) NOT NULL,
  PRIMARY KEY (`order_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_order_option`
--

CREATE TABLE IF NOT EXISTS `osc_order_option` (
  `order_option_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `order_goods_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `option_value_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(32) NOT NULL,
  PRIMARY KEY (`order_option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_order_status`
--

CREATE TABLE IF NOT EXISTS `osc_order_status` (
  `order_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`order_status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='订单状态' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `osc_order_status`
--

INSERT INTO `osc_order_status` (`order_status_id`, `name`) VALUES
(1, '已付款待发货'),
(3, '待付款'),
(4, '已发货'),
(5, '取消订单');

-- --------------------------------------------------------

--
-- 表的结构 `osc_order_total`
--

CREATE TABLE IF NOT EXISTS `osc_order_total` (
  `order_total_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY (`order_total_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_transport`
--

CREATE TABLE IF NOT EXISTS `osc_transport` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '运费模板ID',
  `title` varchar(30) NOT NULL COMMENT '运费模板名称',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '最后更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='运费模板' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `osc_transport`
--

INSERT INTO `osc_transport` (`id`, `title`, `update_time`) VALUES
(1, '圆通', 1473319103);

-- --------------------------------------------------------

--
-- 表的结构 `osc_transport_extend`
--

CREATE TABLE IF NOT EXISTS `osc_transport_extend` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '运费模板扩展ID',
  `area_id` text COMMENT '市级地区ID组成的串，以，隔开，两端也有，',
  `top_area_id` text COMMENT '省级地区ID组成的串，以，隔开，两端也有，',
  `area_name` text COMMENT '地区name组成的串，以，隔开',
  `snum` mediumint(8) unsigned DEFAULT '1' COMMENT '首重',
  `sprice` decimal(10,2) DEFAULT '0.00' COMMENT '首重运费',
  `xnum` mediumint(8) unsigned DEFAULT '1' COMMENT '续重',
  `xprice` decimal(10,2) DEFAULT '0.00' COMMENT '续重运费',
  `is_default` enum('1','2') DEFAULT '2' COMMENT '是否默认运费1是2否',
  `transport_id` mediumint(8) unsigned NOT NULL COMMENT '运费模板ID',
  `transport_title` varchar(60) DEFAULT NULL COMMENT '运费模板',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='运费模板扩展表' AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `osc_transport_extend`
--

INSERT INTO `osc_transport_extend` (`id`, `area_id`, `top_area_id`, `area_name`, `snum`, `sprice`, `xnum`, `xprice`, `is_default`, `transport_id`, `transport_title`) VALUES
(9, '', '', '全国', 1, '0.01', 1, '0.01', '1', 1, '圆通'),
(10, ',11,180,181,182,183,184,185,179,178,177,175,176,14,212,213,214,215,216,217,218,219,220,221,222,12,186,187,188,189,190,191,202,192,193,194,195,196,197,198,199,200,201,10,162,163,164,165,166,167,168,169,170,171,172,173,174,9,39,15,231,232,233,234,235,236,237,238,239,230,229,228,223,224,225,226,227,5,95,106,96,97,98,99,100,101,102,103,104,105,4,84,85,86,87,88,89,90,91,92,93,94,1,36,3,83,82,81,80,73,74,75,76,77,78,79,2,40,', ',11,14,12,10,9,15,5,4,1,3,2,', '浙江,江西,安徽,江苏,上海,山东,内蒙古,山西,北京,河北,天津', 1, '0.01', 1, '0.01', '2', 1, '圆通'),
(11, ',17,258,259,260,261,262,263,264,265,266,267,268,269,270,271,272,273,274,16,243,257,256,255,254,244,245,246,247,248,249,250,251,252,253,242,240,241,18,283,284,285,286,287,288,282,281,280,275,276,277,278,279,20,310,311,312,313,314,315,316,317,318,319,320,321,322,323,21,334,335,336,337,338,339,340,341,342,343,333,332,331,324,325,326,327,328,329,330,344,19,294,308,309,307,306,305,295,296,297,298,299,300,301,302,303,304,289,290,291,292,293,13,205,206,207,208,209,210,211,204,203,', ',17,16,18,20,21,19,13,', '湖北,河南,湖南,广西,海南,广东,福建', 1, '0.01', 1, '0.01', '2', 1, '圆通'),
(12, ',30,474,470,471,472,473,31,489,490,491,492,488,477,478,479,480,481,482,483,484,485,486,476,475,487,29,462,463,464,465,466,467,468,469,28,451,452,453,454,455,456,457,458,459,460,450,449,448,461,27,438,439,440,441,442,443,444,445,446,447,24,406,407,408,409,410,411,412,413,414,26,431,432,433,434,435,436,437,25,426,427,428,429,430,425,424,423,415,416,417,418,419,420,421,422,23,399,400,401,402,403,404,405,398,397,386,387,388,389,390,391,392,393,394,395,396,385,22,62,', ',30,31,29,28,27,24,26,25,23,22,', '宁夏,新疆,青海,甘肃,陕西,贵州,西藏,云南,四川,重庆', 1, '0.01', 1, '0.01', '2', 1, '圆通');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
