-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 26, 2012 at 06:04 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fantoon_ci`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id_from` int(11) NOT NULL DEFAULT '0',
  `user_id_to` int(11) DEFAULT '0',
  `page_id_from` int(11) DEFAULT '0',
  `page_id_to` int(11) DEFAULT '0',
  `folder_id` int(11) DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT 'null',
  `collect` enum('0','1') NOT NULL,
  `activity_id` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id_from` (`user_id_from`),
  KEY `user_id_to` (`user_id_to`),
  KEY `page_id_to` (`page_id_to`),
  KEY `page_id_from` (`page_id_from`),
  KEY `folder_id` (`folder_id`),
  KEY `activity_id` (`activity_id`),
  KEY `type` (`type`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15349 ;

-- --------------------------------------------------------

--
-- Table structure for table `additional_info`
--

CREATE TABLE IF NOT EXISTS `additional_info` (
  `type_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL,
  `type` varchar(15) NOT NULL,
  `general_type` varchar(20) NOT NULL,
  `hits` int(20) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `age_similarity`
--

CREATE TABLE IF NOT EXISTS `age_similarity` (
  `user1_id` int(10) NOT NULL,
  `user2_id` int(10) NOT NULL,
  `similarity` int(10) NOT NULL,
  KEY `user1_id` (`user1_id`),
  KEY `user2_id` (`user2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `album_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(10) NOT NULL,
  `loop_id` int(11) NOT NULL,
  `page_id` bigint(10) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `album_name` varchar(128) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`album_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `alpha_users`
--

CREATE TABLE IF NOT EXISTS `alpha_users` (
  `beta_id` int(11) NOT NULL AUTO_INCREMENT,
  `signup_code` varchar(100) NOT NULL,
  `signup_email` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `alpha_key` varchar(50) NOT NULL,
  `password` bigint(100) NOT NULL,
  `fb_firstname` varchar(250) NOT NULL,
  `fb_lastname` varchar(250) NOT NULL,
  `fb_bday` date NOT NULL,
  `fb_gender` varchar(250) NOT NULL,
  `fb_id` blob NOT NULL,
  `t_id` blob NOT NULL,
  `email` varchar(250) NOT NULL,
  `t_name` varchar(250) NOT NULL,
  `t_screen_name` varchar(250) NOT NULL,
  `check` enum('0','1') NOT NULL,
  `used` enum('0','1') NOT NULL,
  `email_count` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  PRIMARY KEY (`beta_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=110 ;

-- --------------------------------------------------------

--
-- Table structure for table `beanstalk_jobs`
--

CREATE TABLE IF NOT EXISTS `beanstalk_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` bigint(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `data` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `started_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `finished_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `job_id` (`job_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=970 ;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE IF NOT EXISTS `city` (
  `city_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `city` varchar(250) NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `time_zone` varchar(250) NOT NULL,
  `dma_id` varchar(250) NOT NULL,
  `code` varchar(250) NOT NULL,
  PRIMARY KEY (`city_id`),
  KEY `country_id` (`country_id`),
  KEY `region_id` (`region_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `color`
--

CREATE TABLE IF NOT EXISTS `color` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `hex` varchar(250) NOT NULL,
  `rgb` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat`
--

CREATE TABLE IF NOT EXISTS `cometchat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` int(10) DEFAULT NULL,
  `to` int(10) DEFAULT NULL,
  `message` text,
  `sent` int(10) unsigned DEFAULT '0',
  `read` int(1) unsigned DEFAULT '0',
  `direction` int(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_online`
--

CREATE TABLE IF NOT EXISTS `cometchat_online` (
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_session`
--

CREATE TABLE IF NOT EXISTS `cometchat_session` (
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `selfonline` int(1) unsigned DEFAULT NULL,
  `soundmute` int(1) unsigned DEFAULT NULL,
  `friendlistid` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_status`
--

CREATE TABLE IF NOT EXISTS `cometchat_status` (
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `message` text,
  `status` varchar(10) DEFAULT NULL,
  `typingto` int(10) DEFAULT NULL,
  `typingtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cometchat_typing`
--

CREATE TABLE IF NOT EXISTS `cometchat_typing` (
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `friend` int(10) unsigned NOT NULL DEFAULT '0',
  `time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`userid`,`friend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `reply_user_id` bigint(10) NOT NULL,
  `reply_page_id` bigint(10) NOT NULL,
  `user_id_from` bigint(10) NOT NULL,
  `user_id_to` bigint(10) NOT NULL,
  `page_id_from` bigint(10) NOT NULL,
  `page_id_to` bigint(10) NOT NULL,
  `post_id` bigint(10) NOT NULL,
  `photo_id` bigint(10) NOT NULL,
  `event_id` bigint(10) NOT NULL,
  `pr_id` bigint(10) NOT NULL,
  `link_id` bigint(10) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`),
  KEY `parent_id` (`parent_id`),
  KEY `user_id_from` (`user_id_from`),
  KEY `user_id_to` (`user_id_to`),
  KEY `page_id_from` (`page_id_from`),
  KEY `page_id_to` (`page_id_to`),
  KEY `post_id` (`post_id`),
  KEY `photo_id` (`photo_id`),
  KEY `event_id` (`event_id`),
  KEY `pr_id` (`pr_id`),
  KEY `link_id` (`link_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1175 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments_children`
--

CREATE TABLE IF NOT EXISTS `comments_children` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `children_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_id` (`comment_id`),
  KEY `children_id` (`children_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL,
  `value` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `connections`
--

CREATE TABLE IF NOT EXISTS `connections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1_id` bigint(10) NOT NULL,
  `user2_id` bigint(10) NOT NULL,
  `similarity` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user1_id` (`user1_id`),
  KEY `user2_id` (`user2_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=528 ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `iso2` char(2) NOT NULL,
  `iso3` char(3) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `iso2` (`iso2`),
  KEY `iso3` (`iso3`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `country_id` int(11) NOT NULL,
  `country` varchar(250) NOT NULL,
  `fips104` varchar(250) NOT NULL,
  `iso2` varchar(250) NOT NULL,
  `iso3` varchar(250) NOT NULL,
  `ison` varchar(250) NOT NULL,
  `internet` varchar(250) NOT NULL,
  `capital` varchar(250) NOT NULL,
  `mapreference` varchar(250) NOT NULL,
  `nationalitysingular` varchar(250) NOT NULL,
  `nationalityplural` varchar(250) NOT NULL,
  `currency` varchar(250) NOT NULL,
  `currencycode` varchar(250) NOT NULL,
  `population` varchar(250) NOT NULL,
  `title` varchar(250) NOT NULL,
  `comment` varchar(250) NOT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custom_tabs`
--

CREATE TABLE IF NOT EXISTS `custom_tabs` (
  `tab_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `page_id` bigint(10) NOT NULL,
  `tab_name` varchar(20) NOT NULL,
  `activated` enum('0','1') NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tab_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1598 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_settings`
--

CREATE TABLE IF NOT EXISTS `email_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` enum('1','0') NOT NULL,
  `comment` enum('1','0') NOT NULL,
  `up_link` enum('1','0') NOT NULL,
  `reply` enum('1','0') NOT NULL,
  `up_comment` enum('1','0') NOT NULL,
  `connection` enum('1','0') NOT NULL,
  `follow_folder` enum('1','0') NOT NULL,
  `follow_list` enum('1','0') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=443 ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `newsfeed_id` bigint(10) NOT NULL,
  `page_id` bigint(10) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `event_name` varchar(100) NOT NULL,
  `location` varchar(55) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(55) NOT NULL,
  `zip_code` int(10) NOT NULL,
  `description` text NOT NULL,
  `privacy` enum('public','private') NOT NULL,
  `attendees` int(11) NOT NULL,
  `notification` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `img` varchar(128) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `favorite_answer`
--

CREATE TABLE IF NOT EXISTS `favorite_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `q_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `order_no` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `q_id` (`q_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `favorite_question`
--

CREATE TABLE IF NOT EXISTS `favorite_question` (
  `q_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `question` varchar(500) NOT NULL,
  `display` enum('1','0') NOT NULL,
  PRIMARY KEY (`q_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fb_activities`
--

CREATE TABLE IF NOT EXISTS `fb_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fb_id` bigint(20) NOT NULL,
  `action` varchar(100) NOT NULL,
  `link_url` varchar(300) NOT NULL,
  `newsfeed_id` int(11) NOT NULL DEFAULT '0',
  `folder_id` int(11) NOT NULL DEFAULT '0',
  `twitter_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fb_id` (`fb_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

-- --------------------------------------------------------

--
-- Table structure for table `fb_drops`
--

CREATE TABLE IF NOT EXISTS `fb_drops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fb_id` bigint(20) NOT NULL,
  `newsfeed_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `username` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `gender` varchar(100) NOT NULL DEFAULT '',
  `birthday` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `folder_id` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fb_id` (`fb_id`),
  KEY `newsfeed_id` (`newsfeed_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=441 ;

-- --------------------------------------------------------

--
-- Table structure for table `fb_friends`
--

CREATE TABLE IF NOT EXISTS `fb_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fb_id` bigint(20) unsigned NOT NULL,
  `friend_id` bigint(20) unsigned NOT NULL,
  `friend_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fb_id` (`fb_id`,`friend_id`),
  KEY `friend_id` (`friend_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2405 ;

-- --------------------------------------------------------

--
-- Table structure for table `fb_links`
--

CREATE TABLE IF NOT EXISTS `fb_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_id` int(11) NOT NULL,
  `fb_id` varchar(100) NOT NULL,
  `link` varchar(200) NOT NULL,
  `twitter_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`),
  KEY `fb_id` (`fb_id`),
  KEY `twitter_id` (`twitter_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=352 ;

-- --------------------------------------------------------

--
-- Table structure for table `fb_newsfeeds`
--

CREATE TABLE IF NOT EXISTS `fb_newsfeeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fb_id` bigint(20) NOT NULL,
  `action` varchar(100) NOT NULL,
  `newsfeed_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fb_id` (`fb_id`),
  KEY `newsfeed_id` (`newsfeed_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- Table structure for table `fb_pages`
--

CREATE TABLE IF NOT EXISTS `fb_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fb_id` bigint(20) unsigned NOT NULL,
  `fb_pageid` bigint(20) unsigned NOT NULL,
  `fb_pagename` varchar(200) NOT NULL,
  `fb_category` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fb_id` (`fb_id`),
  KEY `fb_page_id` (`fb_pageid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9340 ;

-- --------------------------------------------------------

--
-- Table structure for table `folder`
--

CREATE TABLE IF NOT EXISTS `folder` (
  `folder_id` int(11) NOT NULL AUTO_INCREMENT,
  `folder_name` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `private` enum('0','1') NOT NULL,
  `editable` enum('1','0') NOT NULL,
  `drops` int(11) NOT NULL DEFAULT '0',
  `followers` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `uniqview` bigint(20) NOT NULL DEFAULT '0',
  `recent_newsfeeds` text NOT NULL,
  `fb_share_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`folder_id`),
  KEY `user_id` (`user_id`),
  KEY `folder_name` (`folder_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=621799 ;

-- --------------------------------------------------------

--
-- Table structure for table `folder_content`
--

CREATE TABLE IF NOT EXISTS `folder_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `folder_id` (`folder_id`,`link_id`),
  KEY `link_id` (`link_id`),
  KEY `photo_id` (`photo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=457 ;

-- --------------------------------------------------------

--
-- Table structure for table `folder_contributors`
--

CREATE TABLE IF NOT EXISTS `folder_contributors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `folder_id` (`folder_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=187 ;

-- --------------------------------------------------------

--
-- Table structure for table `folder_user`
--

CREATE TABLE IF NOT EXISTS `folder_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `folder_id` (`folder_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4705 ;

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE IF NOT EXISTS `follows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `follow_mode` varchar(20) NOT NULL DEFAULT '',
  `following` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `follow_id` (`following`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `food_type`
--

CREATE TABLE IF NOT EXISTS `food_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=103 ;

-- --------------------------------------------------------

--
-- Table structure for table `ga_cache`
--

CREATE TABLE IF NOT EXISTS `ga_cache` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `func` varchar(255) NOT NULL,
  `param` varchar(255) NOT NULL,
  `val` int(10) unsigned NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `func` (`func`,`param`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interest_category`
--

CREATE TABLE IF NOT EXISTS `interest_category` (
  `id` int(11) NOT NULL,
  `type` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `invitees`
--

CREATE TABLE IF NOT EXISTS `invitees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` bigint(10) NOT NULL,
  `user_id` bigint(10) NOT NULL,
  `response` enum('yes','no','') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
  `like_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(10) NOT NULL,
  `page_id` bigint(10) NOT NULL,
  `post_id` bigint(10) NOT NULL,
  `photo_id` bigint(10) NOT NULL,
  `event_id` bigint(20) NOT NULL,
  `pr_id` bigint(10) NOT NULL,
  `link_id` int(10) NOT NULL,
  `comment_id` bigint(10) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`like_id`),
  KEY `user_id` (`user_id`),
  KEY `page_id` (`page_id`),
  KEY `post_id` (`post_id`),
  KEY `photo_id` (`photo_id`),
  KEY `event_id` (`event_id`),
  KEY `pr_id` (`pr_id`),
  KEY `link_id` (`link_id`),
  KEY `comment_id` (`comment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=933 ;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `user_id_from` int(10) NOT NULL,
  `user_id_to` int(20) NOT NULL,
  `page_id_from` int(10) NOT NULL,
  `page_id_to` int(10) NOT NULL,
  `link` text NOT NULL,
  `source_img` varchar(2048) NOT NULL,
  `img` varchar(255) NOT NULL,
  `media` text NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `content_plain` text NOT NULL,
  `text` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `source` text NOT NULL,
  `img_width` int(11) NOT NULL DEFAULT '0',
  `img_height` int(11) NOT NULL DEFAULT '0',
  `trimmed_left` int(11) NOT NULL DEFAULT '0',
  `trimmed_top` int(11) NOT NULL DEFAULT '0',
  `fb_share_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`link_id`),
  KEY `user_id_from` (`user_id_from`),
  KEY `user_id_to` (`user_id_to`),
  KEY `page_id_from` (`page_id_from`),
  KEY `page_id_to` (`page_id_to`),
  FULLTEXT KEY `content` (`title`,`text`,`content_plain`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12223 ;

-- --------------------------------------------------------

--
-- Table structure for table `link_collects`
--

CREATE TABLE IF NOT EXISTS `link_collects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `new_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `link_id` (`link_id`),
  KEY `user_id` (`user_id`),
  KEY `folder_id` (`folder_id`),
  KEY `new_id` (`new_id`),
  KEY `photo_id` (`photo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=457 ;

-- --------------------------------------------------------

--
-- Table structure for table `lists`
--

CREATE TABLE IF NOT EXISTS `lists` (
  `list_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `list_maker_id` bigint(10) NOT NULL,
  `list_name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `visibility` enum('public','private') NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`list_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1537 ;

-- --------------------------------------------------------

--
-- Table structure for table `list_order`
--

CREATE TABLE IF NOT EXISTS `list_order` (
  `uid` int(10) NOT NULL,
  `order` varchar(100) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `list_page`
--

CREATE TABLE IF NOT EXISTS `list_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` bigint(10) NOT NULL,
  `page_id` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `list_id` (`list_id`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `list_users`
--

CREATE TABLE IF NOT EXISTS `list_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` bigint(10) NOT NULL,
  `list_user_id` bigint(10) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `list_id` (`list_id`),
  KEY `list_user_id` (`list_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `location_similarity`
--

CREATE TABLE IF NOT EXISTS `location_similarity` (
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `similarity` int(11) NOT NULL,
  PRIMARY KEY (`user1_id`,`user2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loops`
--

CREATE TABLE IF NOT EXISTS `loops` (
  `loop_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `loop_name` varchar(100) NOT NULL,
  PRIMARY KEY (`loop_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=531 ;

-- --------------------------------------------------------

--
-- Table structure for table `loop_order`
--

CREATE TABLE IF NOT EXISTS `loop_order` (
  `uid` int(10) NOT NULL,
  `order` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loop_user`
--

CREATE TABLE IF NOT EXISTS `loop_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loop_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=305 ;

-- --------------------------------------------------------

--
-- Table structure for table `majors`
--

CREATE TABLE IF NOT EXISTS `majors` (
  `major_id` int(10) NOT NULL AUTO_INCREMENT,
  `major` varchar(100) NOT NULL,
  PRIMARY KEY (`major_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=351 ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `version` int(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `msg_content`
--

CREATE TABLE IF NOT EXISTS `msg_content` (
  `msg_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `msg_body` text NOT NULL,
  PRIMARY KEY (`msg_id`),
  KEY `thread_id` (`thread_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=243 ;

-- --------------------------------------------------------

--
-- Table structure for table `msg_info`
--

CREATE TABLE IF NOT EXISTS `msg_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg_id` bigint(10) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `from` bigint(10) NOT NULL,
  `to` bigint(10) NOT NULL,
  `erase_type` enum('0','1','2') NOT NULL,
  `display_status` enum('0','1') NOT NULL,
  `number_read` enum('0','1') NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `msg_id` (`msg_id`),
  KEY `thread_id` (`thread_id`),
  KEY `display_status` (`display_status`),
  KEY `number_read` (`number_read`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=360 ;

-- --------------------------------------------------------

--
-- Table structure for table `msg_thread`
--

CREATE TABLE IF NOT EXISTS `msg_thread` (
  `thread_id` int(11) NOT NULL AUTO_INCREMENT,
  `users` varchar(500) NOT NULL,
  PRIMARY KEY (`thread_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

-- --------------------------------------------------------

--
-- Table structure for table `newsfeed`
--

CREATE TABLE IF NOT EXISTS `newsfeed` (
  `newsfeed_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `loop_id` int(11) NOT NULL,
  `activity_user_id` bigint(10) NOT NULL,
  `type` varchar(50) NOT NULL,
  `link_type` varchar(32) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `data` longblob,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `page_type` enum('profile','page') NOT NULL,
  `user_id_from` bigint(10) NOT NULL,
  `user_id_to` bigint(10) NOT NULL,
  `page_id_from` bigint(10) NOT NULL,
  `page_id_to` bigint(10) NOT NULL,
  `location` varchar(250) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `folder_id` int(11) NOT NULL,
  `up_count` int(11) NOT NULL,
  `comment_count` int(11) NOT NULL,
  `collect_count` int(11) NOT NULL,
  `news_rank` varchar(20) NOT NULL DEFAULT '1',
  `hits` bigint(20) NOT NULL DEFAULT '0',
  `complete` enum('1','0') NOT NULL,
  `uniqview` bigint(20) NOT NULL DEFAULT '0',
  `fb_share_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`newsfeed_id`),
  KEY `activity_id` (`activity_id`),
  KEY `type` (`type`),
  KEY `activity_user_id` (`activity_user_id`),
  KEY `link_type` (`link_type`),
  KEY `user_id_from` (`user_id_from`),
  KEY `user_id_to` (`user_id_to`),
  KEY `page_id_from` (`page_id_from`),
  KEY `page_id_to` (`page_id_to`),
  KEY `folder_id` (`folder_id`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12785030 ;

-- --------------------------------------------------------

--
-- Table structure for table `newsfeed_activity`
--

CREATE TABLE IF NOT EXISTS `newsfeed_activity` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `newsfeed_id` bigint(10) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `loop_id` int(11) NOT NULL,
  `activity_user_id` bigint(10) NOT NULL,
  `activity_user_type` enum('users','pages') NOT NULL,
  `activity_id` bigint(10) NOT NULL,
  `reply_user_id` bigint(10) NOT NULL,
  `reply_page_id` bigint(10) NOT NULL,
  `type` varchar(32) NOT NULL,
  `a_data` text NOT NULL,
  `user` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `newsfeed_loops`
--

CREATE TABLE IF NOT EXISTS `newsfeed_loops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `newsfeed_id` int(11) NOT NULL,
  `loop_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `newsfeed_id` (`newsfeed_id`,`loop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `newsfeed_users`
--

CREATE TABLE IF NOT EXISTS `newsfeed_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `newsfeed_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `newsfeed_id` (`newsfeed_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id_from` int(11) NOT NULL,
  `user_id_to` int(11) NOT NULL,
  `page_id_from` int(11) NOT NULL,
  `page_id_to` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `a_id` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_from` (`user_id_from`),
  KEY `user_id_to` (`user_id_to`),
  KEY `page_id_from` (`page_id_from`),
  KEY `page_id_to` (`page_id_to`),
  KEY `type` (`type`),
  KEY `a_id` (`a_id`),
  KEY `m_id` (`m_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2229 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `page_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `thumbnail` varchar(100) NOT NULL,
  `page_name` varchar(100) NOT NULL,
  `uri_name` varchar(100) NOT NULL,
  `official_url` varchar(100) NOT NULL,
  `fb_pageid` bigint(30) NOT NULL,
  `twitter_id` bigint(10) NOT NULL,
  `category_id` bigint(10) NOT NULL,
  `interest_id` bigint(10) NOT NULL,
  `alias_id` int(10) NOT NULL,
  `alias_name` varchar(250) NOT NULL,
  `redirect_url` varchar(250) NOT NULL,
  `topic_lock` enum('0','1') NOT NULL DEFAULT '0',
  `pr_lock` enum('0','1') NOT NULL,
  `hits` int(20) NOT NULL,
  `sign_up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `score` int(11) NOT NULL DEFAULT '0',
  `wiki_time` bigint(20) NOT NULL,
  `avatar_width` int(11) NOT NULL DEFAULT '0',
  `avatar_height` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1542 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages_similarity`
--

CREATE TABLE IF NOT EXISTS `pages_similarity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page1_id` int(11) NOT NULL,
  `page2_id` int(11) NOT NULL,
  `similarity` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page1_id` (`page1_id`),
  KEY `page2_id` (`page2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_aliases_request`
--

CREATE TABLE IF NOT EXISTS `page_aliases_request` (
  `r_id` int(10) NOT NULL AUTO_INCREMENT,
  `page_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `alias` varchar(250) NOT NULL,
  `hits` int(10) NOT NULL,
  PRIMARY KEY (`r_id`),
  KEY `page_id` (`page_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_category`
--

CREATE TABLE IF NOT EXISTS `page_category` (
  `type` varchar(250) NOT NULL,
  `id` bigint(10) NOT NULL,
  `name` varchar(250) NOT NULL,
  `form` varchar(250) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `page_feature`
--

CREATE TABLE IF NOT EXISTS `page_feature` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page1_id` int(10) NOT NULL,
  `page2_id` int(10) NOT NULL,
  `type` enum('1','2','3') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page1_id` (`page1_id`,`page2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_info`
--

CREATE TABLE IF NOT EXISTS `page_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `page_name` varchar(250) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `city` varchar(250) DEFAULT NULL,
  `zip` varchar(250) DEFAULT NULL,
  `phone` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `founded` varchar(250) DEFAULT NULL,
  `about` varchar(250) DEFAULT NULL,
  `description` text,
  `website` varchar(250) DEFAULT NULL,
  `written_by` varchar(250) DEFAULT NULL,
  `hour` varchar(250) DEFAULT NULL,
  `serve` varchar(250) DEFAULT NULL,
  `specialty` varchar(250) DEFAULT NULL,
  `food` varchar(250) DEFAULT NULL,
  `product` varchar(250) DEFAULT NULL,
  `award` varchar(250) DEFAULT NULL,
  `interest` varchar(250) DEFAULT NULL,
  `gender` varchar(250) DEFAULT NULL,
  `birthday` varchar(250) DEFAULT NULL,
  `hometown` varchar(250) DEFAULT NULL,
  `college` varchar(250) DEFAULT NULL,
  `biograph` varchar(250) DEFAULT NULL,
  `current_location` varchar(250) DEFAULT NULL,
  `education` varchar(250) DEFAULT NULL,
  `manager_name` varchar(250) DEFAULT NULL,
  `booking_agent` varchar(250) DEFAULT NULL,
  `press_contact` varchar(250) DEFAULT NULL,
  `influence` varchar(250) DEFAULT NULL,
  `release_date` varchar(250) DEFAULT NULL,
  `record_label` varchar(250) DEFAULT NULL,
  `member` varchar(250) DEFAULT NULL,
  `schedule` varchar(250) DEFAULT NULL,
  `isbn` varchar(250) DEFAULT NULL,
  `genre` varchar(250) DEFAULT NULL,
  `directed_by` varchar(250) DEFAULT NULL,
  `starring` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_merge`
--

CREATE TABLE IF NOT EXISTS `page_merge` (
  `m_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `page1_id` int(11) NOT NULL,
  `page2_id` int(11) NOT NULL,
  PRIMARY KEY (`m_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_official_requests`
--

CREATE TABLE IF NOT EXISTS `page_official_requests` (
  `r_id` int(10) NOT NULL AUTO_INCREMENT,
  `page_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `new_name` varchar(100) NOT NULL,
  PRIMARY KEY (`r_id`),
  KEY `r_id` (`r_id`,`page_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_thread`
--

CREATE TABLE IF NOT EXISTS `page_thread` (
  `t_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `thread_name` varchar(200) NOT NULL,
  `views` int(11) NOT NULL,
  `replies` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`t_id`),
  KEY `page_id` (`page_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_topics`
--

CREATE TABLE IF NOT EXISTS `page_topics` (
  `topic_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `page_id` bigint(10) NOT NULL,
  `topic_name` varchar(100) NOT NULL,
  `user_id` bigint(10) NOT NULL,
  PRIMARY KEY (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_users`
--

CREATE TABLE IF NOT EXISTS `page_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(10) NOT NULL,
  `page_id` bigint(10) NOT NULL,
  `role` enum('OWNER','ADMIN','FAN','EDITOR','MOD') NOT NULL,
  `vibe` enum('0','1','2','3','4','5') NOT NULL,
  `posts` int(11) NOT NULL,
  `photos` int(11) NOT NULL,
  `links` int(11) NOT NULL,
  `comments` int(11) NOT NULL,
  `ups` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_user_relation`
--

CREATE TABLE IF NOT EXISTS `page_user_relation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `page_id` int(10) NOT NULL,
  `user1_id` int(10) NOT NULL,
  `user2_id` int(10) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`,`user1_id`,`user2_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id_from` int(11) NOT NULL DEFAULT '0',
  `page_id_from` int(11) NOT NULL DEFAULT '0',
  `user_id_to` int(11) NOT NULL DEFAULT '0',
  `page_id_to` int(11) NOT NULL DEFAULT '0',
  `full_img` varchar(300) NOT NULL DEFAULT '0',
  `thumbnail` varchar(300) NOT NULL DEFAULT '0',
  `caption` varchar(300) NOT NULL DEFAULT '0',
  `folder_id` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `full_img_width` int(11) NOT NULL DEFAULT '0',
  `full_img_height` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`photo_id`),
  KEY `user_id_from` (`user_id_from`),
  KEY `user_id_to` (`user_id_to`),
  KEY `page_id_from` (`page_id_from`),
  KEY `page_id_to` (`page_id_to`),
  KEY `folder_id` (`folder_id`),
  FULLTEXT KEY `caption` (`caption`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=424 ;

-- --------------------------------------------------------

--
-- Table structure for table `photo_tags`
--

CREATE TABLE IF NOT EXISTS `photo_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` bigint(10) NOT NULL,
  `user_id` bigint(10) NOT NULL,
  `page_id` bigint(10) NOT NULL,
  `time` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `points_system`
--

CREATE TABLE IF NOT EXISTS `points_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` bigint(10) NOT NULL,
  `user_id` bigint(10) NOT NULL,
  `points` int(5) NOT NULL,
  `like_points` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `user_id_from` bigint(10) NOT NULL,
  `user_id_to` bigint(10) NOT NULL,
  `page_id_from` bigint(10) NOT NULL,
  `page_id_to` bigint(10) NOT NULL,
  `post` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `privacy`
--

CREATE TABLE IF NOT EXISTS `privacy` (
  `user_id` bigint(10) NOT NULL,
  `level` enum('open','closed') NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prs`
--

CREATE TABLE IF NOT EXISTS `prs` (
  `pr_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `newsfeed_id` bigint(10) NOT NULL,
  `page_id` bigint(10) NOT NULL,
  `user_id` bigint(10) NOT NULL,
  `source_title` varchar(255) NOT NULL,
  `source_link` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `psk_page_similarity`
--

CREATE TABLE IF NOT EXISTS `psk_page_similarity` (
  `user1_id` int(10) NOT NULL,
  `user2_id` int(10) NOT NULL,
  `similarity` int(10) NOT NULL,
  KEY `user1_id` (`user1_id`,`user2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `psk_topic_similarity`
--

CREATE TABLE IF NOT EXISTS `psk_topic_similarity` (
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `similarity` int(11) NOT NULL,
  KEY `user1_id` (`user1_id`,`user2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `region` varchar(250) NOT NULL,
  `code` varchar(250) NOT NULL,
  `adm1_code` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `region_id` (`region_id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `r_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  PRIMARY KEY (`r_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `request_connection`
--

CREATE TABLE IF NOT EXISTS `request_connection` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator_id` bigint(10) NOT NULL,
  `requested_id` bigint(10) NOT NULL,
  `read_status` enum('0','1') NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=486 ;

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE IF NOT EXISTS `schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(5) NOT NULL,
  `name` varchar(150) NOT NULL,
  `url` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16774 ;

-- --------------------------------------------------------

--
-- Table structure for table `sending_emails`
--

CREATE TABLE IF NOT EXISTS `sending_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `message` blob NOT NULL,
  `notification_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1750 ;

-- --------------------------------------------------------

--
-- Table structure for table `should_know_similarity`
--

CREATE TABLE IF NOT EXISTS `should_know_similarity` (
  `user1_id` int(10) NOT NULL,
  `user2_id` int(10) NOT NULL,
  `similarity` int(10) NOT NULL,
  PRIMARY KEY (`user1_id`,`user2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `srt_sort_category`
--

CREATE TABLE IF NOT EXISTS `srt_sort_category` (
  `id_user` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  KEY `user_id` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `srt_sort_interest`
--

CREATE TABLE IF NOT EXISTS `srt_sort_interest` (
  `id_user` int(11) NOT NULL,
  `id_interest` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  KEY `user_id` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sxsw_comments`
--

CREATE TABLE IF NOT EXISTS `sxsw_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  `comment` varchar(300) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `like_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sxsw_comment_likes`
--

CREATE TABLE IF NOT EXISTS `sxsw_comment_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `like_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sxsw_emails`
--

CREATE TABLE IF NOT EXISTS `sxsw_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `email_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `sxsw_likes`
--

CREATE TABLE IF NOT EXISTS `sxsw_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sxsw_links`
--

CREATE TABLE IF NOT EXISTS `sxsw_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `image` varchar(500) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_count` int(11) NOT NULL DEFAULT '0',
  `like_count` int(11) NOT NULL DEFAULT '0',
  `link_url` varchar(200) NOT NULL DEFAULT '',
  `news_rank` varchar(20) NOT NULL DEFAULT '1',
  `category` enum('1','2','3','4','5') NOT NULL,
  `uniqid` varchar(50) NOT NULL DEFAULT '0',
  `media` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `sxsw_link_urls`
--

CREATE TABLE IF NOT EXISTS `sxsw_link_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `link_url` varchar(500) NOT NULL,
  `category` enum('1','2','3','4','5') NOT NULL,
  `description` varchar(500) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sxsw_photos`
--

CREATE TABLE IF NOT EXISTS `sxsw_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `image` varchar(200) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `category` enum('1','2','3','4','5') NOT NULL,
  `link_url` varchar(200) NOT NULL,
  `description` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sxsw_users`
--

CREATE TABLE IF NOT EXISTS `sxsw_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fb_id` bigint(20) NOT NULL,
  `twitter_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `thumbnail` varchar(200) NOT NULL DEFAULT '0',
  `post_status` enum('0','1') NOT NULL,
  `email_setting` enum('1','0') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `tab_content`
--

CREATE TABLE IF NOT EXISTS `tab_content` (
  `component_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `orderid` bigint(10) NOT NULL,
  `tab_id` bigint(10) NOT NULL,
  `type` enum('text','twitter','google_map','youtube_video') NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`component_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `test_table_users`
--

CREATE TABLE IF NOT EXISTS `test_table_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_name` varchar(256) NOT NULL,
  `hits` int(20) NOT NULL,
  `editable` enum('1','0') NOT NULL DEFAULT '1',
  `uri_name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`topic_id`),
  KEY `editable` (`editable`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_aliases`
--

CREATE TABLE IF NOT EXISTS `topic_aliases` (
  `aliases_id` int(10) NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) NOT NULL,
  `aliases` varchar(100) NOT NULL,
  PRIMARY KEY (`aliases_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_children`
--

CREATE TABLE IF NOT EXISTS `topic_children` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`,`child_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_event`
--

CREATE TABLE IF NOT EXISTS `topic_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) NOT NULL,
  `event_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_folders`
--

CREATE TABLE IF NOT EXISTS `topic_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL DEFAULT '0',
  `folder_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=338 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_link`
--

CREATE TABLE IF NOT EXISTS `topic_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) NOT NULL,
  `link_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `link_id` (`link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_merge`
--

CREATE TABLE IF NOT EXISTS `topic_merge` (
  `merge_id` int(10) NOT NULL AUTO_INCREMENT,
  `topic1_id` int(10) NOT NULL,
  `topic2_id` int(10) NOT NULL,
  `unmerge` enum('0','1') NOT NULL,
  PRIMARY KEY (`merge_id`),
  KEY `merge_id` (`merge_id`,`topic1_id`,`topic2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_newsfeed`
--

CREATE TABLE IF NOT EXISTS `topic_newsfeed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) NOT NULL,
  `newsfeed_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`,`newsfeed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_page`
--

CREATE TABLE IF NOT EXISTS `topic_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) NOT NULL,
  `page_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_photo`
--

CREATE TABLE IF NOT EXISTS `topic_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) NOT NULL,
  `photo_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_points`
--

CREATE TABLE IF NOT EXISTS `topic_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `topic_id` int(11) unsigned NOT NULL,
  `points` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_post`
--

CREATE TABLE IF NOT EXISTS `topic_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) NOT NULL,
  `post_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_pr`
--

CREATE TABLE IF NOT EXISTS `topic_pr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) NOT NULL,
  `pr_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_relationship`
--

CREATE TABLE IF NOT EXISTS `topic_relationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_tracker`
--

CREATE TABLE IF NOT EXISTS `topic_tracker` (
  `track_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `type` enum('merge','unmerge') NOT NULL,
  `id` int(10) NOT NULL,
  PRIMARY KEY (`track_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_user`
--

CREATE TABLE IF NOT EXISTS `topic_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `a_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`,`user_id`),
  KEY `a_id` (`a_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic_user_follow`
--

CREATE TABLE IF NOT EXISTS `topic_user_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `twitter_follow`
--

CREATE TABLE IF NOT EXISTS `twitter_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `twitter_id` bigint(20) unsigned NOT NULL,
  `follow_id` bigint(20) unsigned NOT NULL,
  `follow_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `twitter_id` (`twitter_id`,`follow_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7153 ;

-- --------------------------------------------------------

--
-- Table structure for table `twitter_tokens`
--

CREATE TABLE IF NOT EXISTS `twitter_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `twitter_id` bigint(20) NOT NULL,
  `token` varchar(100) NOT NULL,
  `token_secret` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `twitter_id` (`twitter_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(55) NOT NULL,
  `last_name` varchar(55) NOT NULL,
  `uri_name` varchar(50) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `thumbnail` varchar(100) NOT NULL,
  `gender` enum('m','f') NOT NULL,
  `birthday` date NOT NULL,
  `show_bday` enum('0','1') NOT NULL DEFAULT '1',
  `current_city` varchar(55) NOT NULL,
  `email` varchar(55) NOT NULL,
  `password` varchar(32) NOT NULL,
  `sign_up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fb_id` bigint(20) NOT NULL,
  `twitter_id` bigint(20) unsigned NOT NULL,
  `about` text NOT NULL,
  `quotes` varchar(255) NOT NULL,
  `political_view` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `color_id` varchar(20) NOT NULL,
  `im` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email_activated` enum('0','1') NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `followable` enum('1','0') NOT NULL,
  `connections` int(11) NOT NULL,
  `interests` int(11) NOT NULL,
  `key` varchar(20) NOT NULL,
  `follower` int(11) NOT NULL,
  `following` int(11) NOT NULL,
  `num_topics` int(11) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT '0',
  `avatar_width` int(11) NOT NULL DEFAULT '0',
  `avatar_height` int(11) NOT NULL DEFAULT '0',
  `bookmarklet_settings` tinyint(4) NOT NULL DEFAULT '1',
  `fb_activity` enum('1','0') NOT NULL,
  `twitter_activity` enum('1','0') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=369152 ;

-- --------------------------------------------------------

--
-- Table structure for table `usersimilarity`
--

CREATE TABLE IF NOT EXISTS `usersimilarity` (
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  KEY `user1_id` (`user1_id`,`user2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_similarity`
--

CREATE TABLE IF NOT EXISTS `users_similarity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `similarity` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user1_id` (`user1_id`),
  KEY `user2_id` (`user2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_additional_info`
--

CREATE TABLE IF NOT EXISTS `user_additional_info` (
  `user_id` bigint(10) NOT NULL,
  `type_id` bigint(10) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_company`
--

CREATE TABLE IF NOT EXISTS `user_company` (
  `user_id` int(10) NOT NULL,
  `company` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_links`
--

CREATE TABLE IF NOT EXISTS `user_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `label` varchar(100) NOT NULL,
  `url` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_locations`
--

CREATE TABLE IF NOT EXISTS `user_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `place_name` varchar(100) NOT NULL,
  `options` enum('place','current','travel') NOT NULL DEFAULT 'place',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_percentile`
--

CREATE TABLE IF NOT EXISTS `user_percentile` (
  `user1_id` int(10) NOT NULL,
  `user2_id` int(10) NOT NULL,
  `similarity` int(10) NOT NULL,
  PRIMARY KEY (`user1_id`,`user2_id`),
  KEY `user1_id` (`user1_id`),
  KEY `user2_id` (`user2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_schools`
--

CREATE TABLE IF NOT EXISTS `user_schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `major` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_stats`
--

CREATE TABLE IF NOT EXISTS `user_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collections` int(11) NOT NULL DEFAULT '0',
  `drops` int(11) NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `fb_links` int(11) NOT NULL DEFAULT '0',
  `twitter_links` int(11) NOT NULL DEFAULT '0',
  `public_collections` int(11) NOT NULL DEFAULT '0',
  `image` int(11) NOT NULL DEFAULT '0',
  `text` int(11) NOT NULL DEFAULT '0',
  `screenshot` int(11) NOT NULL DEFAULT '0',
  `video` int(11) NOT NULL DEFAULT '0',
  `clip` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=442 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_updated`
--

CREATE TABLE IF NOT EXISTS `user_updated` (
  `user_id` int(10) NOT NULL,
  `updated` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_visits`
--

CREATE TABLE IF NOT EXISTS `user_visits` (
  `id` int(11) NOT NULL,
  `home` enum('1','0') NOT NULL,
  `profile` enum('1','0') NOT NULL,
  `interest` enum('1','0') NOT NULL,
  `preview` enum('2','1','0') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `wiki`
--

CREATE TABLE IF NOT EXISTS `wiki` (
  `page_url` varchar(100) NOT NULL,
  `page_name` varchar(100) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `image_url` blob NOT NULL,
  `intro` text NOT NULL,
  `abstract` text NOT NULL,
  `main_topic` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `work_similarity`
--

CREATE TABLE IF NOT EXISTS `work_similarity` (
  `user1_id` int(10) NOT NULL,
  `user2_id` int(10) NOT NULL,
  `similarity` int(10) NOT NULL,
  PRIMARY KEY (`user1_id`,`user2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
