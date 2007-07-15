-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jul 14, 2007 at 11:17 PM
-- Server version: 5.0.44
-- PHP Version: 5.2.2-pl1-gentoo

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `mdb`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `animenfo`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- 
-- RELATIONS FOR TABLE `animenfo`:
--   `title_id`
--       `titles` -> `id`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `downloads`
-- 

CREATE TABLE `downloads` (
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

-- --------------------------------------------------------

-- 
-- Table structure for table `files`
-- 

CREATE TABLE `files` (
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
-- Table structure for table `tags`
-- 

CREATE TABLE `tags` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `titles`
-- 

CREATE TABLE `titles` (
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

CREATE TABLE `title_tag` (
  `title_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  UNIQUE KEY `title_id` (`title_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `privilege` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
