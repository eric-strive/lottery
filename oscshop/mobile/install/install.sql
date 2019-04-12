-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 09 月 20 日 10:24
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
-- 表的结构 `osc_agent`
--

CREATE TABLE IF NOT EXISTS `osc_agent` (
  `agent_id` int(11) NOT NULL AUTO_INCREMENT,
  `total_bonus` decimal(9,3) NOT NULL COMMENT '总奖金',
  `cash` decimal(9,3) NOT NULL COMMENT '已经提现的',
  `no_cash` decimal(9,3) NOT NULL COMMENT '未提现',
  `uid` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `tel` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `id_card` varchar(64) NOT NULL,
  `bank_name` varchar(128) NOT NULL COMMENT '收款银行',
  `bank_account` varchar(128) NOT NULL COMMENT '银行账户',
  `alipay` varchar(128) NOT NULL COMMENT '支付宝账号',
  `agent_level` int(11) NOT NULL,
  `member_num` int(11) NOT NULL COMMENT '名下会员数',
  `deal_num` int(11) NOT NULL COMMENT '成交数量',
  `return_percent` decimal(4,2) NOT NULL COMMENT '返点',
  `create_time` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_agent_apply`
--

CREATE TABLE IF NOT EXISTS `osc_agent_apply` (
  `aa_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `deal_time` int(11) NOT NULL COMMENT '处理时间',
  `answer` varchar(255) NOT NULL COMMENT '留言',
  `tel` varchar(20) NOT NULL COMMENT '手机号',
  `name` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `id_cart` varchar(64) NOT NULL COMMENT '身份证',
  PRIMARY KEY (`aa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理商申请表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_agent_bonus`
--

CREATE TABLE IF NOT EXISTS `osc_agent_bonus` (
  `ab_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_num_alias` varchar(40) NOT NULL,
  `buyer_id` int(11) NOT NULL COMMENT '下单人的id',
  `bonus` decimal(9,3) NOT NULL COMMENT '奖金',
  `return_percent` decimal(4,2) NOT NULL COMMENT '提成点数',
  `order_total` decimal(6,2) NOT NULL COMMENT '订单总价',
  `pay_time` int(11) NOT NULL COMMENT '下单时间',
  `create_time` varchar(40) NOT NULL COMMENT '创建时间',
  `month_time` varchar(40) NOT NULL,
  `year_time` varchar(40) NOT NULL,
  `order_status_id` int(11) NOT NULL COMMENT '订单状态',
  PRIMARY KEY (`ab_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理商分红' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_agent_cash_apply`
--

CREATE TABLE IF NOT EXISTS `osc_agent_cash_apply` (
  `aca_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `cash` decimal(9,3) NOT NULL COMMENT '提现金额',
  `bank_name` varchar(64) NOT NULL,
  `bank_account` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `tel` varchar(64) NOT NULL,
  `alipay` varchar(64) NOT NULL,
  `create_time` int(11) NOT NULL,
  `pass_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `admin_user` varchar(40) NOT NULL COMMENT '后台操作者',
  PRIMARY KEY (`aca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理商提现申请表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_agent_level`
--

CREATE TABLE IF NOT EXISTS `osc_agent_level` (
  `al_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL COMMENT '等级名称',
  `return_percent` decimal(4,2) NOT NULL COMMENT '返佣比例',
  `type` int(11) NOT NULL COMMENT '代理等级',
  PRIMARY KEY (`al_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_wechat_news_reply`
--

CREATE TABLE IF NOT EXISTS `osc_wechat_news_reply` (
  `nr_id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`nr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图文回复' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_wechat_rule`
--

CREATE TABLE IF NOT EXISTS `osc_wechat_rule` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL COMMENT '关键字',
  `module` varchar(64) NOT NULL COMMENT '模块',
  `status` int(2) NOT NULL COMMENT '状态',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`rid`),
  KEY `keyword` (`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信回复关键字' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_wechat_share`
--

CREATE TABLE IF NOT EXISTS `osc_wechat_share` (
  `ws_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` varchar(128) NOT NULL,
  `url` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`ws_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `osc_wechat_text_reply`
--

CREATE TABLE IF NOT EXISTS `osc_wechat_text_reply` (
  `tr_id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `create_time` datetime NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`tr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文字回复' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
