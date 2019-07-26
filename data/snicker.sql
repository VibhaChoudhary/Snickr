-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2019 at 05:34 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `snicker`
--

-- --------------------------------------------------------

--
-- Table structure for table `channel`
--

CREATE TABLE `channel` (
  `cid` bigint(20) NOT NULL,
  `wid` bigint(20) NOT NULL,
  `cname` varchar(255) NOT NULL,
  `ccreator` bigint(20) NOT NULL,
  `cpurpose` mediumtext NOT NULL,
  `cprivate` tinyint(1) NOT NULL,
  `cdefault` tinyint(1) NOT NULL DEFAULT '0',
  `create_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `channel`
--

INSERT INTO `channel` (`cid`, `wid`, `cname`, `ccreator`, `cpurpose`, `cprivate`, `cdefault`, `create_ts`) VALUES
(1, 1, 'general', 1, 'For General Announcements', 0, 1, '2019-03-02 05:00:00'),
(2, 2, 'general', 3, 'For General Announcements', 0, 1, '2019-03-11 04:00:00'),
(3, 3, 'general', 7, 'For General Announcements', 0, 1, '2019-03-11 14:00:00'),
(4, 1, 'DeadWalker', 1, 'Discuss plans and policies to defeat the army of dead.', 1, 0, '2019-03-13 22:29:17'),
(5, 1, 'Winter is here', 5, 'Discuss suggestions and recommendations to survice the winter', 0, 0, '2019-03-13 23:21:14'),
(6, 2, 'Save Throne', 3, 'Post here to discuss strategies needed to protect the throne.', 0, 0, '2019-03-13 23:29:17'),
(9, 1, 'FacelessTraining', 2, 'Post your ideas to conduct successful training sessions', 0, 0, '2019-03-14 16:35:14'),
(68, 39, 'general', 14, ' For General Announcements', 0, 1, '2019-05-10 17:14:10'),
(69, 39, 'demo public', 14, 'demo', 1, 0, '2019-05-10 17:16:28'),
(70, 1, 'Dead defeated', 1, 'sample', 1, 0, '2019-05-10 17:35:46'),
(72, 1, 'Demo channel', 2, 'chankk', 0, 0, '2019-05-10 19:35:30'),
(73, 3, 'DeadWalker', 1, 'This is a test', 0, 0, '2019-05-11 01:26:52'),
(74, 3, 'DeadWalker Defeat', 1, 'Defeat of deadwalker', 1, 0, '2019-05-11 01:27:39');

--
-- Triggers `channel`
--
DELIMITER $$
CREATE TRIGGER `after_insert_channel` AFTER INSERT ON `channel` FOR EACH ROW BEGIN
	INSERT INTO channel_member(cid,cstarred,uid,join_ts) VALUES 	(NEW.cid,0,NEW.ccreator,NEW.create_ts);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `channel_invite`
--

CREATE TABLE `channel_invite` (
  `chid` bigint(20) NOT NULL,
  `cid` bigint(20) NOT NULL,
  `touid` bigint(20) NOT NULL,
  `fromuid` bigint(20) NOT NULL,
  `invite_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `attended` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `channel_invite`
--

INSERT INTO `channel_invite` (`chid`, `cid`, `touid`, `fromuid`, `invite_ts`, `attended`) VALUES
(1, 4, 5, 1, '2019-05-10 17:36:18', 1),
(2, 4, 2, 1, '2019-05-10 19:06:38', 1),
(3, 6, 8, 3, '2019-03-13 23:30:19', 0),
(7, 5, 1, 5, '2019-03-14 16:24:43', 0),
(8, 5, 2, 5, '2019-03-14 16:24:43', 0),
(9, 5, 6, 5, '2019-03-14 16:24:43', 0),
(10, 9, 1, 2, '2019-03-14 16:38:59', 0),
(11, 9, 5, 2, '2019-03-14 16:38:59', 0),
(12, 9, 6, 2, '2019-03-14 16:38:59', 0),
(15, 1, 5, 1, '2019-05-08 20:57:33', 0),
(16, 1, 2, 1, '2019-05-08 20:57:34', 0),
(17, 1, 6, 1, '2019-05-08 20:59:55', 0),
(18, 1, 1, 1, '2019-05-08 20:59:56', 0),
(19, 1, 5, 1, '2019-05-08 21:00:51', 0),
(20, 1, 5, 1, '2019-05-08 21:03:43', 0),
(21, 1, 5, 1, '2019-05-08 21:06:44', 0),
(76, 68, 14, 14, '2019-05-10 17:17:14', 0),
(77, 68, 14, 14, '2019-05-10 17:18:52', 0),
(78, 68, 14, 14, '2019-05-10 17:19:29', 0),
(79, 68, 14, 14, '2019-05-10 17:20:10', 0),
(80, 68, 14, 14, '2019-05-10 17:20:11', 0),
(81, 68, 14, 14, '2019-05-10 17:21:33', 0),
(82, 68, 14, 14, '2019-05-10 17:21:34', 0),
(83, 68, 14, 14, '2019-05-10 17:24:25', 0),
(84, 1, 2, 1, '2019-05-10 19:06:31', 0),
(85, 1, 2, 1, '2019-05-10 19:40:15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `channel_member`
--

CREATE TABLE `channel_member` (
  `uid` bigint(20) NOT NULL,
  `cid` bigint(20) NOT NULL,
  `cstarred` tinyint(1) NOT NULL DEFAULT '0',
  `join_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `channel_member`
--

INSERT INTO `channel_member` (`uid`, `cid`, `cstarred`, `join_ts`) VALUES
(1, 1, 0, '2019-03-02 05:00:00'),
(1, 3, 0, '2019-03-15 04:00:00'),
(1, 5, 0, '2019-05-08 23:14:56'),
(1, 9, 0, '2019-05-10 17:44:49'),
(1, 70, 0, '2019-05-10 17:35:46'),
(1, 73, 0, '2019-05-11 01:49:20'),
(1, 74, 0, '2019-05-11 01:27:39'),
(2, 1, 0, '2019-03-13 22:33:06'),
(2, 5, 1, '2019-05-10 19:39:21'),
(2, 9, 0, '2019-03-14 16:35:14'),
(2, 72, 0, '2019-05-10 19:35:30'),
(3, 2, 0, '2019-03-11 04:00:00'),
(3, 6, 0, '2019-03-13 23:29:17'),
(4, 2, 0, '2019-03-13 22:35:11'),
(5, 1, 0, '2019-03-13 22:33:06'),
(5, 5, 1, '2019-03-13 23:29:17'),
(6, 1, 0, '2019-03-13 22:33:06'),
(7, 3, 0, '2019-03-11 14:00:00'),
(8, 2, 0, '2019-03-13 22:35:11'),
(14, 68, 0, '2019-05-10 17:14:10'),
(14, 69, 0, '2019-05-10 17:16:28');

-- --------------------------------------------------------

--
-- Table structure for table `channel_message`
--

CREATE TABLE `channel_message` (
  `mid` bigint(20) NOT NULL,
  `cid` bigint(20) NOT NULL,
  `fromuid` bigint(20) NOT NULL,
  `mcontent` text NOT NULL,
  `message_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `channel_message`
--

INSERT INTO `channel_message` (`mid`, `cid`, `fromuid`, `mcontent`, `message_ts`) VALUES
(1, 5, 5, 'This would be the longest winter ever!', '2019-03-14 00:59:22'),
(2, 5, 5, 'Have advised the smiths to cover the breastplate in leather. We would need it when the real cold comes.', '2019-03-14 16:00:00'),
(3, 5, 5, 'We don\'t have enough food, especially not if the armies of the north come back to defend Winterfell.\r\n', '2019-03-15 18:09:23'),
(4, 5, 2, 'Yeah most likely not. This could result in a real threat.', '2019-03-15 20:09:23'),
(5, 5, 2, 'We need to start building up our grain stores.', '2019-03-16 18:09:23'),
(6, 5, 5, 'We should have regular shipments from every keep in the north. If we don\'t use it by winter\'s end we\'ll give it back to them.', '2019-03-17 18:09:23'),
(7, 5, 2, 'Very wise!  If the entire north has to flee to Winterfell there won\'t be enough time to bring wagonloads of grain with them.', '2019-03-18 00:09:23'),
(18, 1, 2, 'The North remembers! Winter came for house Frey.', '2019-03-20 21:42:43'),
(19, 1, 1, '@Arya You are a true fighter.', '2019-03-21 13:19:14'),
(20, 2, 3, 'It\'s time to take revenge. Great work done by Qyburn.', '2019-03-21 14:00:00'),
(21, 4, 1, 'The dead are coming for us all.', '2019-03-25 04:00:00'),
(22, 3, 7, 'Yay!! Jorah is Back!', '2019-03-25 18:50:15'),
(23, 3, 1, 'We need to visit Dragostone to mine Dragonglass.', '2019-03-26 20:06:35'),
(24, 3, 1, 'Dragonstone sits perpendicular to the mountain of Dragonglass.\r\n', '2019-04-15 00:27:41'),
(25, 1, 1, 'We need to forge a weapon with blade perpendicular to the handle.', '2019-03-27 00:00:00'),
(26, 4, 1, 'We need to target the night king.ðŸ˜Ž', '2019-05-02 16:53:23'),
(30, 4, 1, 'hello', '2019-05-02 17:09:51'),
(31, 1, 1, 'hello general', '2019-05-02 17:10:15'),
(32, 1, 1, 'hello again', '2019-05-02 17:22:47'),
(57, 5, 5, 'We are the last starks left', '2019-05-10 17:44:08'),
(58, 1, 1, 'Hello from jon snow', '2019-05-10 19:01:38'),
(59, 1, 1, 'hello from jon snow', '2019-05-10 19:02:01'),
(60, 1, 2, 'hello from arya', '2019-05-10 19:06:04'),
(61, 1, 2, 'Hello From arya at 3 36', '2019-05-10 19:36:09');

-- --------------------------------------------------------

--
-- Table structure for table `direct_message`
--

CREATE TABLE `direct_message` (
  `dmid` bigint(20) NOT NULL,
  `wid` bigint(20) NOT NULL,
  `fromuid` bigint(20) NOT NULL,
  `touid` bigint(20) NOT NULL,
  `dmcontent` text NOT NULL,
  `message_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `direct_message`
--

INSERT INTO `direct_message` (`dmid`, `wid`, `fromuid`, `touid`, `dmcontent`, `message_ts`) VALUES
(1, 1, 2, 5, 'The command suits you.', '2019-03-17 04:00:00'),
(2, 1, 5, 2, 'Thanks!', '2019-03-17 11:00:00'),
(3, 1, 6, 2, 'Hey. I wanna give you the dagger I have.', '2019-03-18 04:00:00'),
(4, 1, 2, 6, 'Where did you get it from?', '2019-03-18 14:00:00'),
(5, 1, 6, 2, 'Littlefinger', '2019-03-18 15:00:00'),
(6, 1, 6, 1, 'We need to forge a weapon with blade perpendicular to the handle.', '2019-03-19 04:00:00'),
(7, 3, 1, 7, 'I am really sorry for your loss.', '2019-03-26 04:00:00'),
(9, 1, 1, 6, 'hello', '2019-05-02 17:18:26'),
(10, 1, 1, 6, 'hello', '2019-05-02 17:22:04'),
(11, 1, 1, 6, 'hello again', '2019-05-02 17:22:57'),
(39, 1, 1, 6, 'Hello', '2019-05-10 19:41:58'),
(40, 1, 1, 6, 'HelloðŸ˜€ðŸ˜ŒðŸ‘Œ', '2019-05-10 19:42:14');

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `pid` int(11) NOT NULL,
  `pname` varchar(100) NOT NULL,
  `pdescp` varchar(100) DEFAULT 'permission',
  `default_allowed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`pid`, `pname`, `pdescp`, `default_allowed`) VALUES
(1, 'add_pub_channel', 'Add Public Channel', 1),
(2, 'add_pvt_channel', 'Add Private Channel', 1),
(3, 'archive_channel', 'Archive a Channel', 0),
(4, 'remove_pub_member', 'Remove member from public channel', 0),
(5, 'remove_pvt_member', 'Remove member from private channel', 0),
(6, 'send_invite', 'Send workspace invites', 1);

-- --------------------------------------------------------

--
-- Table structure for table `preferences`
--

CREATE TABLE `preferences` (
  `prid` int(11) NOT NULL,
  `prname` varchar(100) NOT NULL,
  `default_value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `preferences`
--

INSERT INTO `preferences` (`prid`, `prname`, `default_value`) VALUES
(1, 'display_full_name', '0'),
(2, 'get_desktop_notification', '1'),
(3, 'user_status', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `uid` bigint(20) NOT NULL,
  `uname` varchar(255) DEFAULT NULL,
  `upassword` varchar(255) NOT NULL,
  `uemail` varchar(100) NOT NULL,
  `unickname` varchar(100) DEFAULT NULL,
  `uphone` varchar(20) DEFAULT NULL,
  `ujob` varchar(200) DEFAULT NULL,
  `udp` varchar(255) DEFAULT NULL,
  `utoken` varchar(255) NOT NULL,
  `uverified` tinyint(1) NOT NULL DEFAULT '0',
  `join_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `uname`, `upassword`, `uemail`, `unickname`, `uphone`, `ujob`, `udp`, `utoken`, `uverified`, `join_ts`) VALUES
(1, 'Jon Targaryen', 'jon', 'jon.snow@watch.com', 'Jon', '9999999999', 'King of North', '1_dp.jpg', 'd97c908ed44c25fdca302612c70584c8d5acd47a', 1, '2019-05-10 04:15:28'),
(2, 'Arya Stark', 'arya', 'arya.stark@winterfell.com', 'AS', '9999999992', 'Trainee at house of black and white', '2_dp.jpg', 'a50f71f97620945ab2250778b0379c459e9c63a5', 1, '2019-04-17 01:41:48'),
(3, 'Cersei Lannister', 'cersei', 'cersei.lannister@westerland.com', 'Cl', '9999999993', 'Protector of Seven Kingdoms', '3_dp.jpg', 'c658ffdd8d26875d2539cf78c9050c258a3f00e1', 1, '2019-04-17 01:41:48'),
(4, 'Tyrion Lannister', 'tyrion', 'tyrion.lannister@casterlyrock.com', 'TL', '9999999994', 'Hand of the Queen', '4_dp.jpg', 'd2014886ca0337e5d9196cfc5fecb4aa4892710d', 1, '2019-04-17 01:41:48'),
(5, 'Sansa Stark', 'sansa', 'sansa.stark@winterfell.com', 'SS', '9999999995', 'Acting regent at Winterfell', '5_dp.jpg', '3bc6b2b1d8551b6a27146a8f0fa68ae4155c4f41', 1, '2019-04-17 01:41:48'),
(6, 'Bran Stark', 'bran', 'bran.stark@winterfell.com', 'BS', '9999999996', 'Three eyed raven', '6_dp.jpg', '4f655272c911c4fd6561959e416c4d3ec69b79aa', 1, '2019-04-17 01:41:48'),
(7, 'Daenerys Targaryen', 'daenerys', 'daenerys.targaryen@dragonstone.com', 'DT', '9999999997', 'Mother of dragons', '7_dp.jpg', '13ca0cb775b4e79582e4d3f3577ad0f2b462a086', 1, '2019-04-17 01:41:48'),
(8, 'Jamie Lannister', 'jamie', 'jamie.lannister@winterfell.com', 'JL', '9999999998', 'Commander of lannister armies', '8_dp.jpg', '6305ebaefc4fb9e3275589dd420b390eabba9d63', 1, '2019-04-17 01:41:48'),
(9, 'Theon Greyjoy', 'theon', 'theon.greyjoy@ironland.com', 'TG', '9999999999', 'Survivor', '9_dp.jpg', 'f444df3ad683faa0529e79f3f554521340b52d98', 1, '2019-04-17 01:41:48'),
(10, 'Samwell Tarly ', 'samwell', 'samwell.tarly@citadel.com', 'ST', '9999999990', 'Researcher at Citadel', '10_dp.jpg', 'c655d33adf13a12ecaa76b6f79dbe12a03fddb66', 1, '2019-04-17 01:41:48'),
(14, 'Vibha Choudhary', '$2y$10$w1TqqbkNk1AOQcPFFiaFr.2igO/qjiaLQoQ1P5l0VNwnjAylw6ILG', 'vibha.nit09@gmail.com', NULL, NULL, NULL, NULL, 'd25abd263fea1a7a524acdadc9bfc0a5a04859b1d07282cd0b8e933c98d93ddba515e8f15f365636585cfcc269924764a685', 0, '2019-05-10 16:42:40'),
(15, 'Mi Wa', '$2y$10$haGZjVlkbHcCGGWukCKUe.xdzIRGSEY96Pj3MES3hsU6TeJ6Rw.lO', 'mw4096@nyu.edu', NULL, NULL, NULL, NULL, 'fe26ab1e4bd2598d105b21f59961b6997a46a993a9e0f20d340cdc65b9c17374cda057f81841cd1c19310075318c907b1152', 0, '2019-05-10 19:25:23'),
(16, 'Mi wa', '$2y$10$WbKrFoQfwG1Z/XjnE6/zRu4isNcNCDQs2AB6q0rT3new8Rz8pRMLa', 'mw4097@nyu.edu', 'MW', '9999999999', 'TA', NULL, 'e9df7dc371d64013be3c31e7f251558bffc7e9ba49b2052088132afc4702eec0161552847f3d6211e7ab33a52c0801267a13', 1, '2019-05-10 19:26:44');

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `uid` bigint(20) NOT NULL,
  `wid` bigint(20) NOT NULL,
  `prid` int(11) NOT NULL,
  `prvalue` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_preferences`
--

INSERT INTO `user_preferences` (`uid`, `wid`, `prid`, `prvalue`) VALUES
(1, 1, 1, '1'),
(1, 1, 2, '1'),
(1, 1, 3, 'Available'),
(1, 3, 1, '0'),
(1, 3, 2, '1'),
(1, 3, 3, 'Away'),
(2, 1, 1, '0'),
(2, 1, 2, '1'),
(2, 1, 3, 'Out sick'),
(3, 2, 1, '0'),
(3, 2, 2, '1'),
(3, 2, 3, 'Available'),
(4, 2, 1, '0'),
(4, 2, 2, '1'),
(4, 2, 3, 'On vacation'),
(5, 1, 1, '0'),
(5, 1, 2, '1'),
(5, 1, 3, 'Available'),
(6, 1, 1, '0'),
(6, 1, 2, '1'),
(6, 1, 3, 'Available'),
(7, 3, 1, '0'),
(7, 3, 2, '1'),
(7, 3, 3, 'Available'),
(8, 2, 1, '0'),
(8, 2, 2, '1'),
(8, 2, 3, 'Available'),
(14, 39, 1, '0'),
(14, 39, 2, '1'),
(14, 39, 3, 'Available'),
(16, 40, 1, '0'),
(16, 40, 2, '1'),
(16, 40, 3, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `workspace`
--

CREATE TABLE `workspace` (
  `wid` bigint(20) NOT NULL,
  `wname` varchar(255) NOT NULL,
  `wpurpose` mediumtext,
  `wcreator` bigint(20) NOT NULL,
  `wurl` text NOT NULL,
  `create_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `workspace`
--

INSERT INTO `workspace` (`wid`, `wname`, `wpurpose`, `wcreator`, `wurl`, `create_ts`) VALUES
(1, 'North', 'All the projects and activities happening in the North discussed.', 1, 'the-north@snickr.com', '2019-05-10 15:51:59'),
(2, 'King\'s Landing', 'All the projects and activities happening in the King\'s Landing are discussed.', 3, 'king\'s-landing@snickr.com', '2019-05-09 01:27:43'),
(3, 'Dragonstone', 'All the projects and activities happening in the Dragonstone are discussed.', 7, 'draganstone@snickr.com', '2019-05-09 01:27:43'),
(39, 'Demo workspace', NULL, 14, 'Demo-workspace5cd5b16259c92@snickr.com', '2019-05-10 17:14:10'),
(40, 'Demo workspace', NULL, 16, 'Demo-workspace5cd5d15087bd5@snickr.com', '2019-05-10 19:30:24');

--
-- Triggers `workspace`
--
DELIMITER $$
CREATE TRIGGER `after_insert_workspace` AFTER INSERT ON `workspace` FOR EACH ROW BEGIN
	INSERT INTO workspace_member(wid,uid,join_ts) VALUES (NEW.wid,NEW.wcreator,NEW.create_ts);
    INSERT INTO workspace_admin(wid,uid,add_ts) VALUES (NEW.wid,NEW.wcreator,NEW.create_ts);
   INSERT INTO channel(wid,ccreator,cname,cpurpose,cprivate,cdefault,create_ts) VALUES (NEW.wid,NEW.wcreator,"general"," For General Announcements",0,1,NEW.create_ts);
   INSERT INTO workspace_permission(wid,pid,pallowed) SELECT NEW.wid,pid,default_allowed from permission;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `workspace_admin`
--

CREATE TABLE `workspace_admin` (
  `wid` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `add_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `workspace_admin`
--

INSERT INTO `workspace_admin` (`wid`, `uid`, `add_ts`) VALUES
(1, 1, '2019-03-02 05:00:00'),
(1, 2, '2019-05-10 19:43:50'),
(2, 3, '2019-03-11 04:00:00'),
(3, 7, '2019-03-11 14:00:00'),
(39, 14, '2019-05-10 17:14:10');

-- --------------------------------------------------------

--
-- Table structure for table `workspace_invite`
--

CREATE TABLE `workspace_invite` (
  `wsid` bigint(20) NOT NULL,
  `wid` bigint(20) NOT NULL,
  `toemail` varchar(255) NOT NULL,
  `fromuid` bigint(20) NOT NULL,
  `invite_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `workspace_invite`
--

INSERT INTO `workspace_invite` (`wsid`, `wid`, `toemail`, `fromuid`, `invite_ts`) VALUES
(1, 1, 'arya.stark@winterfell.com', 1, '2019-03-10 05:00:00'),
(2, 1, 'bran.stark@winterfell.com', 1, '2019-03-10 16:12:36'),
(3, 1, 'sansa.stark@winterfell.com', 1, '2019-03-10 16:12:36'),
(4, 3, 'tyrion.lannister@casterlyrock.com', 7, '2019-03-11 16:12:36'),
(5, 3, 'jon.snow@watch.com', 7, '2019-03-11 16:12:36'),
(8, 2, 'jamie.lannister@winterfell.com', 3, '2019-03-12 04:00:00'),
(9, 2, 'tyrion.lannister@casterlyrock.com', 3, '2019-03-12 04:00:00'),
(16, 1, 'hacker1hacker2@gmail.com', 1, '2019-05-08 17:33:27'),
(17, 1, 'hacker1hacker2@gmail.com', 1, '2019-05-08 17:33:29'),
(18, 1, 'rohitkhatri007@gmail.com', 1, '2019-05-08 23:33:33'),
(19, 1, 'hacker1hacker2@gmail.com', 1, '2019-05-09 16:32:05'),
(20, 1, 'hacker1hacker2@gmail.com', 1, '2019-05-09 17:58:03'),
(23, 3, 'vibha.nit09@gmail.com', 1, '2019-05-09 18:44:36'),
(27, 1, 'hacker1hacker2@gmail.com', 1, '2019-05-10 23:06:39'),
(28, 1, 'hacker1hacker2@gmail.com', 1, '2019-05-10 23:12:16');

-- --------------------------------------------------------

--
-- Table structure for table `workspace_member`
--

CREATE TABLE `workspace_member` (
  `uid` bigint(20) NOT NULL,
  `wid` bigint(20) NOT NULL,
  `join_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `workspace_member`
--

INSERT INTO `workspace_member` (`uid`, `wid`, `join_ts`) VALUES
(1, 1, '2019-03-02 05:00:00'),
(1, 3, '2019-03-15 04:00:00'),
(2, 1, '2019-03-13 22:33:06'),
(3, 2, '2019-03-11 04:00:00'),
(4, 2, '2019-03-13 22:35:11'),
(5, 1, '2019-03-13 22:33:06'),
(6, 1, '2019-03-13 22:33:06'),
(7, 3, '2019-03-11 14:00:00'),
(8, 2, '2019-03-13 22:35:11'),
(14, 39, '2019-05-10 17:14:10');

--
-- Triggers `workspace_member`
--
DELIMITER $$
CREATE TRIGGER `after_insert_workspace_member` AFTER INSERT ON `workspace_member` FOR EACH ROW BEGIN
	INSERT INTO channel_member(cid,join_ts,uid) SELECT DISTINCT cid,NEW.join_ts,NEW.uid FROM channel WHERE cdefault=1 AND wid = NEW.wid;
    INSERT INTO user_preferences(uid,wid,prid,prvalue) SELECT NEW.UID,NEW.WID,prid,default_value FROM preferences;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `workspace_permission`
--

CREATE TABLE `workspace_permission` (
  `wid` bigint(20) NOT NULL,
  `pid` int(11) NOT NULL,
  `pallowed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `workspace_permission`
--

INSERT INTO `workspace_permission` (`wid`, `pid`, `pallowed`) VALUES
(1, 1, 1),
(1, 2, 0),
(1, 3, 0),
(1, 4, 0),
(1, 5, 0),
(1, 6, 1),
(2, 1, 0),
(2, 2, 0),
(2, 3, 0),
(2, 4, 0),
(2, 5, 0),
(2, 6, 1),
(3, 1, 1),
(3, 2, 1),
(3, 3, 0),
(3, 4, 0),
(3, 5, 0),
(3, 6, 1),
(39, 1, 1),
(39, 2, 1),
(39, 3, 0),
(39, 4, 0),
(39, 5, 0),
(39, 6, 1),
(40, 1, 1),
(40, 2, 1),
(40, 3, 0),
(40, 4, 0),
(40, 5, 0),
(40, 6, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `channel`
--
ALTER TABLE `channel`
  ADD PRIMARY KEY (`cid`),
  ADD KEY `CHANNEL_FK2` (`wid`),
  ADD KEY `CHANNEL_FK1` (`ccreator`,`wid`);

--
-- Indexes for table `channel_invite`
--
ALTER TABLE `channel_invite`
  ADD PRIMARY KEY (`chid`),
  ADD KEY `CHINVITE_FK1` (`cid`),
  ADD KEY `CHINVITE_FK2` (`fromuid`),
  ADD KEY `CHINVITE_FK3` (`touid`);

--
-- Indexes for table `channel_member`
--
ALTER TABLE `channel_member`
  ADD PRIMARY KEY (`uid`,`cid`),
  ADD KEY `CM_FK1` (`cid`);

--
-- Indexes for table `channel_message`
--
ALTER TABLE `channel_message`
  ADD PRIMARY KEY (`mid`),
  ADD KEY `MESSAGE_FK1` (`fromuid`),
  ADD KEY `MESSAGE_FK2` (`cid`);
ALTER TABLE `channel_message` ADD FULLTEXT KEY `mcontent` (`mcontent`);
ALTER TABLE `channel_message` ADD FULLTEXT KEY `mcontent_2` (`mcontent`);

--
-- Indexes for table `direct_message`
--
ALTER TABLE `direct_message`
  ADD PRIMARY KEY (`dmid`),
  ADD KEY `DIRECTCHANNEL_FK1` (`fromuid`,`wid`),
  ADD KEY `DIRECTCHANNEL_FK2` (`touid`,`wid`),
  ADD KEY `DIRECTCHANNEL_FK3` (`wid`);
ALTER TABLE `direct_message` ADD FULLTEXT KEY `dmcontent` (`dmcontent`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `preferences`
--
ALTER TABLE `preferences`
  ADD PRIMARY KEY (`prid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`uid`,`wid`,`prid`),
  ADD KEY `UP_FK2` (`wid`),
  ADD KEY `UP_FK3` (`prid`);

--
-- Indexes for table `workspace`
--
ALTER TABLE `workspace`
  ADD PRIMARY KEY (`wid`),
  ADD KEY `WORKSPACE_FK1` (`wcreator`);

--
-- Indexes for table `workspace_admin`
--
ALTER TABLE `workspace_admin`
  ADD PRIMARY KEY (`wid`,`uid`),
  ADD KEY `WA_FK1` (`uid`,`wid`);

--
-- Indexes for table `workspace_invite`
--
ALTER TABLE `workspace_invite`
  ADD PRIMARY KEY (`wsid`),
  ADD KEY `WSINVITE_FK1` (`fromuid`),
  ADD KEY `WSINVITE_FK2` (`wid`);

--
-- Indexes for table `workspace_member`
--
ALTER TABLE `workspace_member`
  ADD PRIMARY KEY (`uid`,`wid`),
  ADD KEY `WM_FK2` (`wid`);

--
-- Indexes for table `workspace_permission`
--
ALTER TABLE `workspace_permission`
  ADD PRIMARY KEY (`wid`,`pid`),
  ADD KEY `WP_FK2` (`pid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `channel`
--
ALTER TABLE `channel`
  MODIFY `cid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `channel_invite`
--
ALTER TABLE `channel_invite`
  MODIFY `chid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `channel_message`
--
ALTER TABLE `channel_message`
  MODIFY `mid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `direct_message`
--
ALTER TABLE `direct_message`
  MODIFY `dmid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `preferences`
--
ALTER TABLE `preferences`
  MODIFY `prid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `workspace`
--
ALTER TABLE `workspace`
  MODIFY `wid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `workspace_invite`
--
ALTER TABLE `workspace_invite`
  MODIFY `wsid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `channel`
--
ALTER TABLE `channel`
  ADD CONSTRAINT `CHANNEL_FK1` FOREIGN KEY (`ccreator`,`wid`) REFERENCES `workspace_member` (`uid`, `wid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `CHANNEL_FK2` FOREIGN KEY (`wid`) REFERENCES `workspace` (`wid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `channel_invite`
--
ALTER TABLE `channel_invite`
  ADD CONSTRAINT `CHINVITE_FK1` FOREIGN KEY (`cid`) REFERENCES `channel` (`cid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `CHINVITE_FK2` FOREIGN KEY (`fromuid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `CHINVITE_FK3` FOREIGN KEY (`touid`) REFERENCES `user` (`uid`);

--
-- Constraints for table `channel_member`
--
ALTER TABLE `channel_member`
  ADD CONSTRAINT `CM_FK1` FOREIGN KEY (`cid`) REFERENCES `channel` (`cid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `CM_FK2` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `channel_message`
--
ALTER TABLE `channel_message`
  ADD CONSTRAINT `MESSAGE_FK1` FOREIGN KEY (`fromuid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `MESSAGE_FK2` FOREIGN KEY (`cid`) REFERENCES `channel` (`cid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `direct_message`
--
ALTER TABLE `direct_message`
  ADD CONSTRAINT `DIRECTCHANNEL_FK1` FOREIGN KEY (`fromuid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `DIRECTCHANNEL_FK2` FOREIGN KEY (`touid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `DIRECTCHANNEL_FK3` FOREIGN KEY (`wid`) REFERENCES `workspace` (`wid`);

--
-- Constraints for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `UP_FK1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `UP_FK2` FOREIGN KEY (`wid`) REFERENCES `workspace` (`wid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `UP_FK3` FOREIGN KEY (`prid`) REFERENCES `preferences` (`prid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `workspace`
--
ALTER TABLE `workspace`
  ADD CONSTRAINT `WORKSPACE_FK1` FOREIGN KEY (`wcreator`) REFERENCES `user` (`uid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `workspace_admin`
--
ALTER TABLE `workspace_admin`
  ADD CONSTRAINT `WA_FK1` FOREIGN KEY (`uid`,`wid`) REFERENCES `workspace_member` (`uid`, `wid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `workspace_invite`
--
ALTER TABLE `workspace_invite`
  ADD CONSTRAINT `WSINVITE_FK1` FOREIGN KEY (`fromuid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `WSINVITE_FK2` FOREIGN KEY (`wid`) REFERENCES `workspace` (`wid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `workspace_member`
--
ALTER TABLE `workspace_member`
  ADD CONSTRAINT `WM_FK1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE,
  ADD CONSTRAINT `WM_FK2` FOREIGN KEY (`wid`) REFERENCES `workspace` (`wid`) ON DELETE CASCADE;

--
-- Constraints for table `workspace_permission`
--
ALTER TABLE `workspace_permission`
  ADD CONSTRAINT `WP_FK1` FOREIGN KEY (`wid`) REFERENCES `workspace` (`wid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `WP_FK2` FOREIGN KEY (`pid`) REFERENCES `permission` (`pid`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
