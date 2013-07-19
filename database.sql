-- phpMyAdmin SQL Dump
-- http://www.phpmyadmin.net
--
-- Generation Time: Jul 19, 2013 at 03:23 PM
-- Server version: 5.5.x
-- PHP Version: 5.4.x

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gm_profiel`
--

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `naam` varchar(35) NOT NULL,
  `door_userid` int(11) NOT NULL,
  `tijd_toegevoegd` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(11) NOT NULL,
  `gebruikersnaam` varchar(50) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `naam` varchar(75) NOT NULL,
  `locatiex` int(11) NOT NULL,
  `locatiey` int(11) NOT NULL,
  `geboortedatum` int(11) NOT NULL,
  `profielverificatie` varchar(255) NOT NULL,
  `profiel_publiek` tinyint(4) NOT NULL DEFAULT '1',
  `bio` mediumtext,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `profielverificatie` (`profielverificatie`),
  UNIQUE KEY `gebruikersnaam` (`gebruikersnaam`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users_tags`
--

CREATE TABLE IF NOT EXISTS `users_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `tagid` int(10) unsigned NOT NULL,
  `opmerking` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
