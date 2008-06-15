-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 15, 2008 at 05:30 PM
-- Server version: 5.0.60
-- PHP Version: 5.2.6-pl1-gentoo

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `dbupdate`
--

CREATE TABLE IF NOT EXISTS `dbupdate` (
  `id` int(11) NOT NULL auto_increment,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `progress` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE IF NOT EXISTS `downloads` (
  `id` int(11) NOT NULL auto_increment,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `ip` varchar(16) NOT NULL,
  `uid` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `fid` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `fsize` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `downloads`:
--   `fid`
--       `files` -> `id`
--   `uid`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL auto_increment,
  `file` varchar(255) NOT NULL,
  `size` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `file` (`file`),
  KEY `size` (`size`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `file_title`
--

CREATE TABLE IF NOT EXISTS `file_title` (
  `file_id` int(11) NOT NULL,
  `title_id` int(11) NOT NULL,
  UNIQUE KEY `file_id` (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `file_title`:
--   `file_id`
--       `files` -> `id`
--   `title_id`
--       `titles` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL auto_increment,
  `title_id` int(11) NOT NULL,
  `site` varchar(255) NOT NULL,
  `name` varchar(255) default NULL,
  `url` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `title_id` (`title_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `links`:
--   `title_id`
--       `titles` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `preferences`
--

CREATE TABLE IF NOT EXISTS `preferences` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `pref` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `preferences`:
--   `uid`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `titles`
--

CREATE TABLE IF NOT EXISTS `titles` (
  `id` int(11) NOT NULL auto_increment,
  `path` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `path` (`path`),
  KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `title_tag`
--

CREATE TABLE IF NOT EXISTS `title_tag` (
  `title_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  UNIQUE KEY `title_id` (`title_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `title_tag`:
--   `tag_id`
--       `tags` -> `id`
--   `title_id`
--       `titles` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `privilege` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
