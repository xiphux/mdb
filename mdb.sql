-- phpMyAdmin SQL Dump
-- version 2.8.0.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jun 11, 2006 at 09:29 PM
-- Server version: 5.0.21
-- PHP Version: 5.1.4-pl1-gentoo
-- 
-- Database: `mdb`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `file_title`
-- 
-- Creation: Jun 05, 2006 at 06:08 PM
-- Last update: Jun 11, 2006 at 04:26 AM
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
-- Last update: Jun 11, 2006 at 04:25 AM
-- 

CREATE TABLE `files` (
  `id` int(11) NOT NULL auto_increment,
  `file` varchar(255) NOT NULL,
  `size` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `file` (`file`),
  KEY `size` (`size`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=16861 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `tags`
-- 
-- Creation: Jun 10, 2006 at 10:01 PM
-- Last update: Jun 11, 2006 at 12:57 AM
-- Last check: Jun 10, 2006 at 10:01 PM
-- 

CREATE TABLE `tags` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `title_tag`
-- 
-- Creation: Jun 10, 2006 at 10:04 PM
-- Last update: Jun 11, 2006 at 09:21 PM
-- 

CREATE TABLE `title_tag` (
  `title_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `titles`
-- 
-- Creation: Jun 05, 2006 at 06:08 PM
-- Last update: Jun 11, 2006 at 04:22 AM
-- 

CREATE TABLE `titles` (
  `id` int(11) NOT NULL auto_increment,
  `path` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `path` (`path`),
  KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=455 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 
-- Creation: Jun 05, 2006 at 06:08 PM
-- Last update: Jun 05, 2006 at 06:10 PM
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `privilege` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
