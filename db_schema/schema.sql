-- phpMyAdmin SQL Dump
-- version 2.9.1.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jan 05, 2010 at 01:58 PM
-- Server version: 5.0.27
-- PHP Version: 5.2.11
-- 
-- Database: `prefixcc`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `prefixcc_access`
-- 

CREATE TABLE `prefixcc_access` (
  `prefix` varchar(10) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY  (`prefix`),
  KEY `count` (`count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `prefixcc_ip_block`
-- 

CREATE TABLE `prefixcc_ip_block` (
  `ip` varchar(15) NOT NULL,
  `date` datetime NOT NULL,
  `scope` int(11) NOT NULL,
  PRIMARY KEY  (`ip`,`scope`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `prefixcc_namespaces`
-- 

CREATE TABLE `prefixcc_namespaces` (
  `prefix` varchar(10) NOT NULL,
  `uri` varchar(100) character set utf8 collate utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `upvotes` int(11) NOT NULL default '0',
  `downvotes` int(11) NOT NULL default '0',
  PRIMARY KEY  (`prefix`,`uri`),
  KEY `upvotes` (`upvotes`),
  KEY `downvotes` (`downvotes`),
  KEY `date` (`date`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `prefixcc_rejected_uris`
-- 

CREATE TABLE `prefixcc_rejected_uris` (
  `prefix` varchar(10) NOT NULL,
  `uri` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `reason` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `prefixcc_vote_log`
-- 

CREATE TABLE `prefixcc_vote_log` (
  `prefix` varchar(10) NOT NULL,
  `uri` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `up` BOOL NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
