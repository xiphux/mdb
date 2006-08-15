-- phpMyAdmin SQL Dump
-- version 2.8.2.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Aug 15, 2006 at 06:19 AM
-- Server version: 5.0.22
-- PHP Version: 5.1.4-pl6-gentoo
-- 
-- Database: `mdb`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `animenfo`
-- 
-- Creation: Jun 26, 2006 at 03:36 PM
-- Last update: Aug 15, 2006 at 05:38 AM
-- Last check: Aug 01, 2006 at 04:58 AM
-- 

CREATE TABLE `animenfo` (
  `id` int(11) NOT NULL auto_increment,
  `title_id` int(11) NOT NULL,
  `name` varchar(255) default NULL,
  `nfo_type` varchar(255) NOT NULL,
  `nfo_id` varchar(255) NOT NULL,
  `nfo_n` varchar(255) NOT NULL,
  `nfo_t` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- RELATIONS FOR TABLE `animenfo`:
--   `title_id`
--       `titles` -> `id`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `file_title`
-- 
-- Creation: Jun 05, 2006 at 06:08 PM
-- Last update: Aug 14, 2006 at 07:01 PM
-- Last check: Aug 01, 2006 at 04:58 AM
-- 

CREATE TABLE `file_title` (
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
-- Table structure for table `files`
-- 
-- Creation: Jun 05, 2006 at 06:08 PM
-- Last update: Aug 14, 2006 at 07:00 PM
-- Last check: Aug 01, 2006 at 04:58 AM
-- 

CREATE TABLE `files` (
  `id` int(11) NOT NULL auto_increment,
  `file` varchar(255) NOT NULL,
  `size` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `file` (`file`),
  KEY `size` (`size`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `tags`
-- 
-- Creation: Jun 10, 2006 at 10:01 PM
-- Last update: Aug 14, 2006 at 07:31 PM
-- Last check: Aug 01, 2006 at 04:58 AM
-- 

CREATE TABLE `tags` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `title_tag`
-- 
-- Creation: Aug 15, 2006 at 06:02 AM
-- Last update: Aug 15, 2006 at 06:05 AM
-- 

CREATE TABLE `title_tag` (
  `title_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  UNIQUE KEY `title_id` (`title_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `titles`
-- 
-- Creation: Jun 05, 2006 at 06:08 PM
-- Last update: Aug 14, 2006 at 06:57 PM
-- Last check: Aug 01, 2006 at 04:58 AM
-- 

CREATE TABLE `titles` (
  `id` int(11) NOT NULL auto_increment,
  `path` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `path` (`path`),
  KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 
-- Creation: Jun 05, 2006 at 06:08 PM
-- Last update: Jun 26, 2006 at 03:08 PM
-- Last check: Aug 01, 2006 at 04:58 AM
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `privilege` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
