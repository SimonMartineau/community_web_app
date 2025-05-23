-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 22, 2025 at 04:06 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `CivicLink_Database`
--

-- --------------------------------------------------------

--
-- Table structure for table `Activities`
--

CREATE TABLE `Activities` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `activity_name` varchar(255) NOT NULL,
  `number_of_places` int(11) NOT NULL,
  `number_of_participants` int(11) NOT NULL,
  `activity_duration` int(11) NOT NULL,
  `activity_location` varchar(255) NOT NULL,
  `activity_date` date NOT NULL,
  `entry_clerk` varchar(255) NOT NULL,
  `additional_notes` text NOT NULL,
  `registration_date` date NOT NULL,
  `trashed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Activities`
--

INSERT INTO `Activities` (`id`, `user_id`, `activity_name`, `number_of_places`, `number_of_participants`, `activity_duration`, `activity_location`, `activity_date`, `entry_clerk`, `additional_notes`, `registration_date`, `trashed`) VALUES
(455, 531003616983, 'AA', 2, 1, 2, 'a', '2025-05-12', 'a', 'a', '2025-05-06', 0),
(471, 2864679956484059464, 'B', 5, 1, 6, 'BB', '2025-05-13', 'bb', 'b', '2025-05-07', 0),
(472, 531003616983, 'AAA', 3, 0, 3, 'A', '2025-05-09', 'a', 'AA', '2025-05-08', 0),
(473, 531003616983, 'AAA', 3, 0, 3, 'A', '2025-05-10', 'a', 'AA', '2025-05-08', 0),
(474, 531003616983, 'AAA', 3, 0, 3, 'A', '2025-05-11', 'a', 'AA', '2025-05-08', 0),
(475, 531003616983, 'AAA', 3, 1, 3, 'A', '2025-05-12', 'a', 'AA', '2025-05-08', 0),
(476, 531003616983, 'AAA', 3, 0, 3, 'A', '2025-05-13', 'a', 'AA', '2025-05-08', 0),
(477, 531003616983, 'AAA', 3, 0, 3, 'A', '2025-05-14', 'a', 'AA', '2025-05-08', 0),
(478, 531003616983, 'AAA', 3, 0, 3, 'A', '2025-05-15', 'a', 'AA', '2025-05-08', 0),
(479, 531003616983, 'AAA', 3, 0, 3, 'A', '2025-05-16', 'a', 'AA', '2025-05-08', 0),
(480, 531003616983, 'AAA', 3, 0, 3, 'A', '2025-05-17', 'a', 'AA', '2025-05-08', 0),
(481, 531003616983, 'AAA', 3, 0, 3, 'A', '2025-05-18', 'a', 'AA', '2025-05-08', 0),
(482, 531003616983, 'AAA', 3, 1, 3, 'A', '2025-05-19', 'a', 'AA', '2025-05-08', 0),
(483, 531003616983, 'AAA', 3, 0, 3, 'A', '2025-05-20', 'a', 'AA', '2025-05-08', 0),
(484, 531003616983, 'AAA', 3, 0, 3, 'A', '2025-05-22', 'a', 'AA', '2025-05-08', 0),
(485, 531003616983, 'AAA', 3, 1, 3, 'A', '2025-05-21', 'a', 'AA', '2025-05-08', 0),
(486, 5233, 'C', 2, 0, 6, 'C', '2025-05-09', 'C', 'C', '2025-05-08', 0),
(487, 5233, 'C Far', 1, 0, 1, 'c', '2035-05-01', 'cc', 'c', '2025-05-08', 0),
(488, 543832863, 'Library Support ', 3, 1, 1, 'Library', '2025-05-12', 'Clark', '', '2025-05-10', 0),
(489, 543832863, 'Library Support ', 3, 0, 2, 'Library', '2025-05-14', 'Clark', '', '2025-05-10', 0),
(490, 543832863, 'Library Support ', 3, 0, 2, 'Library', '2025-05-15', 'Clark', '', '2025-05-10', 0),
(491, 543832863, 'EE', 3, 0, 2, 'e', '2025-05-19', 'e', '', '2025-05-12', 0),
(492, 543832863, 'EE', 3, 0, 2, 'e', '2025-05-20', 'e', '', '2025-05-12', 0),
(493, 543832863, 'EE', 3, 0, 2, 'e', '2025-05-21', 'e', '', '2025-05-12', 0),
(494, 543832863, 'EE', 3, 0, 2, 'e', '2025-05-22', 'e', '', '2025-05-12', 0),
(495, 543832863, 'EE', 3, 0, 2, 'e', '2025-05-24', 'e', '', '2025-05-12', 0),
(496, 543832863, 'EE', 3, 0, 2, 'e', '2025-05-23', 'e', '', '2025-05-12', 0),
(497, 543832863, 'EE', 3, 0, 2, 'e', '2025-05-25', 'e', '', '2025-05-12', 0),
(498, 543832863, 'Ee', 1, 0, 2, '', '2025-05-15', 'e', '', '2025-05-14', 0),
(499, 543832863, 'Ee', 1, 0, 2, '', '2025-05-16', 'e', '', '2025-05-14', 0),
(500, 543832863, 'Ee', 1, 0, 2, '', '2025-05-17', 'e', '', '2025-05-14', 0),
(501, 543832863, 'Ee', 1, 0, 2, '', '2025-05-18', 'e', '', '2025-05-14', 0),
(502, 543832863, 'Ee', 1, 0, 2, '', '2025-05-19', 'e', '', '2025-05-14', 0),
(503, 543832863, 'Ee', 1, 0, 2, '', '2025-05-20', 'e', '', '2025-05-14', 0),
(504, 543832863, 'Ee', 1, 0, 1, '', '2025-05-21', 'e', '', '2025-05-14', 0),
(505, 543832863, 'Ee', 1, 0, 1, '', '2025-05-22', 'e', '', '2025-05-14', 0),
(506, 543832863, 'Ee', 1, 0, 1, '', '2025-05-23', 'e', '', '2025-05-14', 0),
(507, 543832863, 'Ee', 8, 0, 8, '', '2025-05-24', 'e', '', '2025-05-14', 0),
(508, 543832863, 'Ee', 1, 0, 2, '', '2025-05-25', 'e', '', '2025-05-14', 0),
(509, 543832863, 'Ee', 1, 0, 2, '', '2025-05-26', 'e', '', '2025-05-14', 0),
(510, 543832863, 'Ee', 1, 0, 2, '', '2025-05-27', 'e', '', '2025-05-14', 0),
(511, 543832863, 'Ee', 1, 0, 2, '', '2025-05-28', 'e', '', '2025-05-14', 0),
(512, 543832863, 'Ee', 1, 0, 2, '', '2025-05-29', 'e', '', '2025-05-14', 0),
(513, 543832863, 'Ee', 1, 0, 2, '', '2025-05-30', 'e', '', '2025-05-14', 0),
(514, 543832863, 'Ee', 1, 0, 2, '', '2025-05-31', 'e', '', '2025-05-14', 0),
(515, 543832863, 'Ee', 1, 0, 2, '', '2025-06-01', 'e', '', '2025-05-14', 0),
(516, 543832863, 'Ee', 1, 0, 2, '', '2025-07-07', 'e', '', '2025-05-14', 0),
(517, 543832863, 'Ee', 1, 0, 2, '', '2025-06-02', 'e', '', '2025-05-14', 0),
(518, 543832863, 'Ee', 1, 0, 2, '', '2025-06-03', 'e', '', '2025-05-14', 0),
(519, 543832863, 'Ee', 1, 0, 2, '', '2025-06-04', 'e', '', '2025-05-14', 0),
(520, 543832863, 'Ee', 1, 0, 2, '', '2025-06-05', 'e', '', '2025-05-14', 0),
(521, 543832863, 'Ee', 1, 0, 2, '', '2025-06-06', 'e', '', '2025-05-14', 0),
(522, 543832863, 'Ee', 1, 0, 2, '', '2025-06-07', 'e', '', '2025-05-14', 0),
(523, 543832863, 'Ee', 1, 0, 2, '', '2025-06-14', 'e', '', '2025-05-14', 0),
(524, 543832863, 'Ee', 1, 0, 2, '', '2025-06-13', 'e', '', '2025-05-14', 0),
(525, 543832863, 'Ee', 1, 0, 2, '', '2025-06-12', 'e', '', '2025-05-14', 0),
(526, 543832863, 'Ee', 1, 0, 2, '', '2025-06-11', 'e', '', '2025-05-14', 0),
(527, 543832863, 'Ee', 1, 0, 2, '', '2025-06-10', 'e', '', '2025-05-14', 0),
(528, 543832863, 'Ee', 1, 0, 2, '', '2025-06-08', 'e', '', '2025-05-14', 0),
(529, 543832863, 'Ee', 1, 0, 2, '', '2025-06-09', 'e', '', '2025-05-14', 0),
(530, 543832863, 'test', 4, 0, 4, '4', '2025-05-22', 't', '', '2025-05-20', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Activity_Domains`
--

CREATE TABLE `Activity_Domains` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `activity_id` bigint(20) NOT NULL,
  `domain` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Activity_Domains`
--

INSERT INTO `Activity_Domains` (`id`, `user_id`, `activity_id`, `domain`) VALUES
(955, 531003616983, 455, 'Organization of community events'),
(956, 2864679956484059464, 471, 'Library support'),
(957, 531003616983, 472, 'Organization of community events'),
(958, 531003616983, 472, 'Library support'),
(959, 531003616983, 472, 'Help in the community store'),
(960, 531003616983, 472, 'Support in the community grocery store'),
(961, 531003616983, 472, 'Cleaning and maintenance of public spaces'),
(962, 531003616983, 472, 'Participation in urban gardening projects'),
(963, 531003616983, 473, 'Organization of community events'),
(964, 531003616983, 473, 'Library support'),
(965, 531003616983, 473, 'Help in the community store'),
(966, 531003616983, 473, 'Support in the community grocery store'),
(967, 531003616983, 473, 'Cleaning and maintenance of public spaces'),
(968, 531003616983, 473, 'Participation in urban gardening projects'),
(969, 531003616983, 474, 'Organization of community events'),
(970, 531003616983, 474, 'Library support'),
(971, 531003616983, 474, 'Help in the community store'),
(972, 531003616983, 474, 'Support in the community grocery store'),
(973, 531003616983, 474, 'Cleaning and maintenance of public spaces'),
(974, 531003616983, 474, 'Participation in urban gardening projects'),
(975, 531003616983, 475, 'Organization of community events'),
(976, 531003616983, 475, 'Library support'),
(977, 531003616983, 475, 'Help in the community store'),
(978, 531003616983, 475, 'Support in the community grocery store'),
(979, 531003616983, 475, 'Cleaning and maintenance of public spaces'),
(980, 531003616983, 475, 'Participation in urban gardening projects'),
(981, 531003616983, 476, 'Organization of community events'),
(982, 531003616983, 476, 'Library support'),
(983, 531003616983, 476, 'Help in the community store'),
(984, 531003616983, 476, 'Support in the community grocery store'),
(985, 531003616983, 476, 'Cleaning and maintenance of public spaces'),
(986, 531003616983, 476, 'Participation in urban gardening projects'),
(987, 531003616983, 477, 'Organization of community events'),
(988, 531003616983, 477, 'Library support'),
(989, 531003616983, 477, 'Help in the community store'),
(990, 531003616983, 477, 'Support in the community grocery store'),
(991, 531003616983, 477, 'Cleaning and maintenance of public spaces'),
(992, 531003616983, 477, 'Participation in urban gardening projects'),
(993, 531003616983, 478, 'Organization of community events'),
(994, 531003616983, 478, 'Library support'),
(995, 531003616983, 478, 'Help in the community store'),
(996, 531003616983, 478, 'Support in the community grocery store'),
(997, 531003616983, 478, 'Cleaning and maintenance of public spaces'),
(998, 531003616983, 478, 'Participation in urban gardening projects'),
(999, 531003616983, 479, 'Organization of community events'),
(1000, 531003616983, 479, 'Library support'),
(1001, 531003616983, 479, 'Help in the community store'),
(1002, 531003616983, 479, 'Support in the community grocery store'),
(1003, 531003616983, 479, 'Cleaning and maintenance of public spaces'),
(1004, 531003616983, 479, 'Participation in urban gardening projects'),
(1005, 531003616983, 480, 'Organization of community events'),
(1006, 531003616983, 480, 'Library support'),
(1007, 531003616983, 480, 'Help in the community store'),
(1008, 531003616983, 480, 'Support in the community grocery store'),
(1009, 531003616983, 480, 'Cleaning and maintenance of public spaces'),
(1010, 531003616983, 480, 'Participation in urban gardening projects'),
(1011, 531003616983, 481, 'Organization of community events'),
(1012, 531003616983, 481, 'Library support'),
(1013, 531003616983, 481, 'Help in the community store'),
(1014, 531003616983, 481, 'Support in the community grocery store'),
(1015, 531003616983, 481, 'Cleaning and maintenance of public spaces'),
(1016, 531003616983, 481, 'Participation in urban gardening projects'),
(1017, 531003616983, 482, 'Organization of community events'),
(1018, 531003616983, 482, 'Library support'),
(1019, 531003616983, 482, 'Help in the community store'),
(1020, 531003616983, 482, 'Support in the community grocery store'),
(1021, 531003616983, 482, 'Cleaning and maintenance of public spaces'),
(1022, 531003616983, 482, 'Participation in urban gardening projects'),
(1023, 531003616983, 483, 'Organization of community events'),
(1024, 531003616983, 483, 'Library support'),
(1025, 531003616983, 483, 'Help in the community store'),
(1026, 531003616983, 483, 'Support in the community grocery store'),
(1027, 531003616983, 483, 'Cleaning and maintenance of public spaces'),
(1028, 531003616983, 483, 'Participation in urban gardening projects'),
(1029, 531003616983, 484, 'Organization of community events'),
(1030, 531003616983, 484, 'Library support'),
(1031, 531003616983, 484, 'Help in the community store'),
(1032, 531003616983, 484, 'Support in the community grocery store'),
(1033, 531003616983, 484, 'Cleaning and maintenance of public spaces'),
(1034, 531003616983, 484, 'Participation in urban gardening projects'),
(1035, 531003616983, 485, 'Organization of community events'),
(1036, 531003616983, 485, 'Library support'),
(1037, 531003616983, 485, 'Help in the community store'),
(1038, 531003616983, 485, 'Support in the community grocery store'),
(1039, 531003616983, 485, 'Cleaning and maintenance of public spaces'),
(1040, 531003616983, 485, 'Participation in urban gardening projects'),
(1047, 5233, 487, 'Organization of community events'),
(1048, 5233, 487, 'Library support'),
(1049, 5233, 487, 'Help in the community store'),
(1050, 5233, 487, 'Support in the community grocery store'),
(1051, 5233, 487, 'Cleaning and maintenance of public spaces'),
(1052, 5233, 487, 'Participation in urban gardening projects'),
(1065, 5233, 486, 'Organization of community events'),
(1066, 5233, 486, 'Library support'),
(1067, 5233, 486, 'Help in the community store'),
(1068, 5233, 486, 'Support in the community grocery store'),
(1069, 5233, 486, 'Cleaning and maintenance of public spaces'),
(1070, 5233, 486, 'Participation in urban gardening projects'),
(1072, 543832863, 489, 'Library support'),
(1073, 543832863, 490, 'Library support'),
(1074, 543832863, 491, 'Organization of community events'),
(1075, 543832863, 491, 'Library support'),
(1076, 543832863, 491, 'Help in the community store'),
(1077, 543832863, 491, 'Support in the community grocery store'),
(1078, 543832863, 491, 'Cleaning and maintenance of public spaces'),
(1079, 543832863, 491, 'Participation in urban gardening projects'),
(1080, 543832863, 492, 'Organization of community events'),
(1081, 543832863, 492, 'Library support'),
(1082, 543832863, 492, 'Help in the community store'),
(1083, 543832863, 492, 'Support in the community grocery store'),
(1084, 543832863, 492, 'Cleaning and maintenance of public spaces'),
(1085, 543832863, 492, 'Participation in urban gardening projects'),
(1092, 543832863, 494, 'Organization of community events'),
(1093, 543832863, 494, 'Library support'),
(1094, 543832863, 494, 'Help in the community store'),
(1095, 543832863, 494, 'Support in the community grocery store'),
(1096, 543832863, 494, 'Cleaning and maintenance of public spaces'),
(1097, 543832863, 494, 'Participation in urban gardening projects'),
(1098, 543832863, 495, 'Organization of community events'),
(1099, 543832863, 495, 'Library support'),
(1100, 543832863, 495, 'Help in the community store'),
(1101, 543832863, 495, 'Support in the community grocery store'),
(1102, 543832863, 495, 'Cleaning and maintenance of public spaces'),
(1103, 543832863, 495, 'Participation in urban gardening projects'),
(1104, 543832863, 496, 'Organization of community events'),
(1105, 543832863, 496, 'Library support'),
(1106, 543832863, 496, 'Help in the community store'),
(1107, 543832863, 496, 'Support in the community grocery store'),
(1108, 543832863, 496, 'Cleaning and maintenance of public spaces'),
(1109, 543832863, 496, 'Participation in urban gardening projects'),
(1110, 543832863, 497, 'Organization of community events'),
(1111, 543832863, 497, 'Library support'),
(1112, 543832863, 497, 'Help in the community store'),
(1113, 543832863, 497, 'Support in the community grocery store'),
(1114, 543832863, 497, 'Cleaning and maintenance of public spaces'),
(1115, 543832863, 497, 'Participation in urban gardening projects'),
(1117, 543832863, 498, 'Organization of community events'),
(1118, 543832863, 498, 'Library support'),
(1119, 543832863, 498, 'Help in the community store'),
(1120, 543832863, 498, 'Support in the community grocery store'),
(1121, 543832863, 499, 'Organization of community events'),
(1122, 543832863, 499, 'Library support'),
(1123, 543832863, 499, 'Help in the community store'),
(1124, 543832863, 499, 'Support in the community grocery store'),
(1125, 543832863, 500, 'Organization of community events'),
(1126, 543832863, 500, 'Library support'),
(1127, 543832863, 500, 'Help in the community store'),
(1128, 543832863, 500, 'Support in the community grocery store'),
(1129, 543832863, 501, 'Organization of community events'),
(1130, 543832863, 501, 'Library support'),
(1131, 543832863, 501, 'Help in the community store'),
(1132, 543832863, 501, 'Support in the community grocery store'),
(1133, 543832863, 502, 'Organization of community events'),
(1134, 543832863, 502, 'Library support'),
(1135, 543832863, 502, 'Help in the community store'),
(1136, 543832863, 502, 'Support in the community grocery store'),
(1157, 543832863, 508, 'Organization of community events'),
(1158, 543832863, 508, 'Library support'),
(1159, 543832863, 508, 'Help in the community store'),
(1160, 543832863, 508, 'Support in the community grocery store'),
(1161, 543832863, 509, 'Organization of community events'),
(1162, 543832863, 509, 'Library support'),
(1163, 543832863, 509, 'Help in the community store'),
(1164, 543832863, 509, 'Support in the community grocery store'),
(1165, 543832863, 510, 'Organization of community events'),
(1166, 543832863, 510, 'Library support'),
(1167, 543832863, 510, 'Help in the community store'),
(1168, 543832863, 510, 'Support in the community grocery store'),
(1169, 543832863, 511, 'Organization of community events'),
(1170, 543832863, 511, 'Library support'),
(1171, 543832863, 511, 'Help in the community store'),
(1172, 543832863, 511, 'Support in the community grocery store'),
(1173, 543832863, 512, 'Organization of community events'),
(1174, 543832863, 512, 'Library support'),
(1175, 543832863, 512, 'Help in the community store'),
(1176, 543832863, 512, 'Support in the community grocery store'),
(1177, 543832863, 513, 'Organization of community events'),
(1178, 543832863, 513, 'Library support'),
(1179, 543832863, 513, 'Help in the community store'),
(1180, 543832863, 513, 'Support in the community grocery store'),
(1181, 543832863, 514, 'Organization of community events'),
(1182, 543832863, 514, 'Library support'),
(1183, 543832863, 514, 'Help in the community store'),
(1184, 543832863, 514, 'Support in the community grocery store'),
(1185, 543832863, 515, 'Organization of community events'),
(1186, 543832863, 515, 'Library support'),
(1187, 543832863, 515, 'Help in the community store'),
(1188, 543832863, 515, 'Support in the community grocery store'),
(1189, 543832863, 516, 'Organization of community events'),
(1190, 543832863, 516, 'Library support'),
(1191, 543832863, 516, 'Help in the community store'),
(1192, 543832863, 516, 'Support in the community grocery store'),
(1193, 543832863, 517, 'Organization of community events'),
(1194, 543832863, 517, 'Library support'),
(1195, 543832863, 517, 'Help in the community store'),
(1196, 543832863, 517, 'Support in the community grocery store'),
(1197, 543832863, 518, 'Organization of community events'),
(1198, 543832863, 518, 'Library support'),
(1199, 543832863, 518, 'Help in the community store'),
(1200, 543832863, 518, 'Support in the community grocery store'),
(1201, 543832863, 519, 'Organization of community events'),
(1202, 543832863, 519, 'Library support'),
(1203, 543832863, 519, 'Help in the community store'),
(1204, 543832863, 519, 'Support in the community grocery store'),
(1205, 543832863, 520, 'Organization of community events'),
(1206, 543832863, 520, 'Library support'),
(1207, 543832863, 520, 'Help in the community store'),
(1208, 543832863, 520, 'Support in the community grocery store'),
(1209, 543832863, 521, 'Organization of community events'),
(1210, 543832863, 521, 'Library support'),
(1211, 543832863, 521, 'Help in the community store'),
(1212, 543832863, 521, 'Support in the community grocery store'),
(1213, 543832863, 522, 'Organization of community events'),
(1214, 543832863, 522, 'Library support'),
(1215, 543832863, 522, 'Help in the community store'),
(1216, 543832863, 522, 'Support in the community grocery store'),
(1217, 543832863, 523, 'Organization of community events'),
(1218, 543832863, 523, 'Library support'),
(1219, 543832863, 523, 'Help in the community store'),
(1220, 543832863, 523, 'Support in the community grocery store'),
(1221, 543832863, 524, 'Organization of community events'),
(1222, 543832863, 524, 'Library support'),
(1223, 543832863, 524, 'Help in the community store'),
(1224, 543832863, 524, 'Support in the community grocery store'),
(1225, 543832863, 525, 'Organization of community events'),
(1226, 543832863, 525, 'Library support'),
(1227, 543832863, 525, 'Help in the community store'),
(1228, 543832863, 525, 'Support in the community grocery store'),
(1229, 543832863, 526, 'Organization of community events'),
(1230, 543832863, 526, 'Library support'),
(1231, 543832863, 526, 'Help in the community store'),
(1232, 543832863, 526, 'Support in the community grocery store'),
(1233, 543832863, 527, 'Organization of community events'),
(1234, 543832863, 527, 'Library support'),
(1235, 543832863, 527, 'Help in the community store'),
(1236, 543832863, 527, 'Support in the community grocery store'),
(1237, 543832863, 528, 'Organization of community events'),
(1238, 543832863, 528, 'Library support'),
(1239, 543832863, 528, 'Help in the community store'),
(1240, 543832863, 528, 'Support in the community grocery store'),
(1241, 543832863, 529, 'Organization of community events'),
(1242, 543832863, 529, 'Library support'),
(1243, 543832863, 529, 'Help in the community store'),
(1244, 543832863, 529, 'Support in the community grocery store'),
(1249, 543832863, 503, 'Organization of community events'),
(1250, 543832863, 503, 'Library support'),
(1251, 543832863, 503, 'Help in the community store'),
(1252, 543832863, 503, 'Support in the community grocery store'),
(1258, 543832863, 504, 'Organization of community events'),
(1259, 543832863, 504, 'Library support'),
(1260, 543832863, 504, 'Help in the community store'),
(1261, 543832863, 504, 'Support in the community grocery store'),
(1262, 543832863, 505, 'Organization of community events'),
(1263, 543832863, 505, 'Library support'),
(1264, 543832863, 505, 'Help in the community store'),
(1265, 543832863, 505, 'Support in the community grocery store'),
(1266, 543832863, 488, 'Library support'),
(1267, 543832863, 530, 'Organization of community events'),
(1268, 543832863, 530, 'Library support'),
(1269, 543832863, 530, 'Help in the community store'),
(1270, 543832863, 530, 'Support in the community grocery store'),
(1271, 543832863, 506, 'Organization of community events'),
(1272, 543832863, 507, 'Organization of community events'),
(1273, 543832863, 507, 'Library support'),
(1274, 543832863, 507, 'Help in the community store'),
(1275, 543832863, 507, 'Support in the community grocery store'),
(1276, 543832863, 507, 'Cleaning and maintenance of public spaces'),
(1277, 543832863, 507, 'Participation in urban gardening projects'),
(1284, 543832863, 493, 'Organization of community events'),
(1285, 543832863, 493, 'Library support'),
(1286, 543832863, 493, 'Help in the community store'),
(1287, 543832863, 493, 'Support in the community grocery store'),
(1288, 543832863, 493, 'Cleaning and maintenance of public spaces'),
(1289, 543832863, 493, 'Participation in urban gardening projects');

-- --------------------------------------------------------

--
-- Table structure for table `Activity_Time_Periods`
--

CREATE TABLE `Activity_Time_Periods` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `activity_id` bigint(20) NOT NULL,
  `time_period` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Activity_Time_Periods`
--

INSERT INTO `Activity_Time_Periods` (`id`, `user_id`, `activity_id`, `time_period`) VALUES
(494, 531003616983, 455, 'Morning'),
(495, 2864679956484059464, 471, 'Morning'),
(496, 531003616983, 472, 'Morning'),
(497, 531003616983, 472, 'Afternoon'),
(498, 531003616983, 472, 'Evening'),
(499, 531003616983, 473, 'Morning'),
(500, 531003616983, 473, 'Afternoon'),
(501, 531003616983, 473, 'Evening'),
(502, 531003616983, 474, 'Morning'),
(503, 531003616983, 474, 'Afternoon'),
(504, 531003616983, 474, 'Evening'),
(505, 531003616983, 475, 'Morning'),
(506, 531003616983, 475, 'Afternoon'),
(507, 531003616983, 475, 'Evening'),
(508, 531003616983, 476, 'Morning'),
(509, 531003616983, 476, 'Afternoon'),
(510, 531003616983, 476, 'Evening'),
(511, 531003616983, 477, 'Morning'),
(512, 531003616983, 477, 'Afternoon'),
(513, 531003616983, 477, 'Evening'),
(514, 531003616983, 478, 'Morning'),
(515, 531003616983, 478, 'Afternoon'),
(516, 531003616983, 478, 'Evening'),
(517, 531003616983, 479, 'Morning'),
(518, 531003616983, 479, 'Afternoon'),
(519, 531003616983, 479, 'Evening'),
(520, 531003616983, 480, 'Morning'),
(521, 531003616983, 480, 'Afternoon'),
(522, 531003616983, 480, 'Evening'),
(523, 531003616983, 481, 'Morning'),
(524, 531003616983, 481, 'Afternoon'),
(525, 531003616983, 481, 'Evening'),
(526, 531003616983, 482, 'Morning'),
(527, 531003616983, 482, 'Afternoon'),
(528, 531003616983, 482, 'Evening'),
(529, 531003616983, 483, 'Morning'),
(530, 531003616983, 483, 'Afternoon'),
(531, 531003616983, 483, 'Evening'),
(532, 531003616983, 484, 'Morning'),
(533, 531003616983, 484, 'Afternoon'),
(534, 531003616983, 484, 'Evening'),
(535, 531003616983, 485, 'Morning'),
(536, 531003616983, 485, 'Afternoon'),
(537, 531003616983, 485, 'Evening'),
(541, 5233, 487, 'Morning'),
(542, 5233, 487, 'Afternoon'),
(543, 5233, 487, 'Evening'),
(550, 5233, 486, 'Morning'),
(551, 5233, 486, 'Afternoon'),
(552, 5233, 486, 'Evening'),
(554, 543832863, 489, 'Morning'),
(555, 543832863, 490, 'Morning'),
(556, 543832863, 491, 'Morning'),
(557, 543832863, 491, 'Afternoon'),
(558, 543832863, 491, 'Evening'),
(559, 543832863, 492, 'Morning'),
(560, 543832863, 492, 'Afternoon'),
(561, 543832863, 492, 'Evening'),
(565, 543832863, 494, 'Morning'),
(566, 543832863, 494, 'Afternoon'),
(567, 543832863, 494, 'Evening'),
(568, 543832863, 495, 'Morning'),
(569, 543832863, 495, 'Afternoon'),
(570, 543832863, 495, 'Evening'),
(571, 543832863, 496, 'Morning'),
(572, 543832863, 496, 'Afternoon'),
(573, 543832863, 496, 'Evening'),
(574, 543832863, 497, 'Morning'),
(575, 543832863, 497, 'Afternoon'),
(576, 543832863, 497, 'Evening'),
(578, 543832863, 498, 'Morning'),
(579, 543832863, 499, 'Morning'),
(580, 543832863, 500, 'Morning'),
(581, 543832863, 501, 'Morning'),
(582, 543832863, 502, 'Morning'),
(588, 543832863, 508, 'Morning'),
(589, 543832863, 509, 'Morning'),
(590, 543832863, 510, 'Morning'),
(591, 543832863, 511, 'Morning'),
(592, 543832863, 512, 'Morning'),
(593, 543832863, 513, 'Morning'),
(594, 543832863, 514, 'Morning'),
(595, 543832863, 515, 'Morning'),
(596, 543832863, 516, 'Morning'),
(597, 543832863, 517, 'Morning'),
(598, 543832863, 518, 'Morning'),
(599, 543832863, 519, 'Morning'),
(600, 543832863, 520, 'Morning'),
(601, 543832863, 521, 'Morning'),
(602, 543832863, 522, 'Morning'),
(603, 543832863, 523, 'Morning'),
(604, 543832863, 524, 'Morning'),
(605, 543832863, 525, 'Morning'),
(606, 543832863, 526, 'Morning'),
(607, 543832863, 527, 'Morning'),
(608, 543832863, 528, 'Morning'),
(609, 543832863, 529, 'Morning'),
(611, 543832863, 503, 'Morning'),
(612, 543832863, 503, 'Afternoon'),
(617, 543832863, 504, 'Morning'),
(618, 543832863, 504, 'Afternoon'),
(619, 543832863, 504, 'Evening'),
(620, 543832863, 505, 'Morning'),
(621, 543832863, 505, 'Afternoon'),
(622, 543832863, 505, 'Evening'),
(623, 543832863, 488, 'Morning'),
(624, 543832863, 530, 'Morning'),
(625, 543832863, 530, 'Afternoon'),
(626, 543832863, 506, 'Morning'),
(627, 543832863, 506, 'Afternoon'),
(628, 543832863, 506, 'Evening'),
(629, 543832863, 507, 'Morning'),
(630, 543832863, 507, 'Afternoon'),
(631, 543832863, 507, 'Evening'),
(635, 543832863, 493, 'Morning'),
(636, 543832863, 493, 'Afternoon'),
(637, 543832863, 493, 'Evening');

-- --------------------------------------------------------

--
-- Table structure for table `Contracts`
--

CREATE TABLE `Contracts` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `volunteer_id` bigint(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `points_deposit` int(11) NOT NULL,
  `points_spent` int(11) NOT NULL,
  `hours_required` int(11) NOT NULL,
  `hours_completed` int(11) NOT NULL,
  `entry_clerk` varchar(255) NOT NULL,
  `contract_active` tinyint(1) NOT NULL,
  `additional_notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Contracts`
--

INSERT INTO `Contracts` (`id`, `user_id`, `volunteer_id`, `start_date`, `end_date`, `points_deposit`, `points_spent`, `hours_required`, `hours_completed`, `entry_clerk`, `contract_active`, `additional_notes`) VALUES
(102, 531003616983, 99, '2025-05-06', '2025-06-07', 32, 26, 7, 8, 'aa', 1, 'a'),
(103, 2864679956484059464, 126, '2025-05-07', '2025-06-06', 30, 0, 6, 6, 'b', 1, 'bb'),
(104, 531003616983, 127, '2025-05-08', '2025-06-07', 30, 0, 6, 3, 'a', 1, ''),
(105, 5233, 128, '2025-05-08', '2025-06-07', 30, 0, 7, 0, 'c', 1, ''),
(107, 543832863, 130, '2025-05-10', '2025-06-09', 30, 1, 6, 1, 'Clark', 1, ''),
(109, 543832863, 131, '2025-05-20', '2025-06-19', 30, 0, 6, 0, 'a', 1, ''),
(110, 531003616983, 170, '2025-05-22', '2025-06-21', 30, 0, 6, 0, 'a', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `Forgot_Password`
--

CREATE TABLE `Forgot_Password` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `code` varchar(5) NOT NULL,
  `expire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Forgot_Password`
--

INSERT INTO `Forgot_Password` (`id`, `email`, `code`, `expire`) VALUES
(27, 'e@e.com', '18221', 1746269466),
(28, 'e@e.com', '15081', 1746269673),
(29, 'e@e.com', '57104', 1746269703),
(30, 'e@e.com', '43038', 1746270183),
(31, 'e@e.com', '22278', 1746270318),
(32, 'martineausimon20@gmail.com', '16025', 1746271942),
(33, 'martineausimon20@gmail.com', '24519', 1746272334),
(34, 'martineausimon20@gmail.com', '16937', 1746273468),
(35, 'martineausimon20@gmail.com', '79229', 1746273529),
(36, 'martineausimon20@gmail.com', '42757', 1746273720),
(37, 'b@b.com', '15828', 1747752368),
(38, 'b@b.com', '16513', 1747752421),
(39, 'b@b.com', '46081', 1747752451),
(40, 'e@e.com', '25635', 1747753562);

-- --------------------------------------------------------

--
-- Table structure for table `Purchases`
--

CREATE TABLE `Purchases` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `volunteer_id` bigint(20) NOT NULL,
  `contract_id` bigint(20) NOT NULL,
  `item_names` varchar(255) NOT NULL,
  `total_cost` float NOT NULL,
  `purchase_date` date NOT NULL,
  `entry_clerk` varchar(255) NOT NULL,
  `additional_notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Purchases`
--

INSERT INTO `Purchases` (`id`, `user_id`, `volunteer_id`, `contract_id`, `item_names`, `total_cost`, `purchase_date`, `entry_clerk`, `additional_notes`) VALUES
(120, 531003616983, 99, 102, 'AAAA', 26, '2025-05-09', 'aaa', 'aa'),
(121, 543832863, 130, 107, 'Rice, eggs', 1, '2025-05-10', 'Clark', '');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`id`, `user_id`, `email`, `password`) VALUES
(31, 531003616983, 'a@a.com', '417eeef05611a97788e86deaa8801c94b6167ae8ab45950cce84c030e6ec0ae6'),
(32, 2864679956484059464, 'b@b.com', '417eeef05611a97788e86deaa8801c94b6167ae8ab45950cce84c030e6ec0ae6'),
(33, 5233, 'c@c.com', '417eeef05611a97788e86deaa8801c94b6167ae8ab45950cce84c030e6ec0ae6'),
(34, 543832863, 'e@e.com', '417eeef05611a97788e86deaa8801c94b6167ae8ab45950cce84c030e6ec0ae6'),
(36, 96324173281516, 'a@a.com', '417eeef05611a97788e86deaa8801c94b6167ae8ab45950cce84c030e6ec0ae6'),
(37, 8388848985332, 'e@ea.com', '822ff4d58b2fc5171c0db7a5cba50f10badca55780cfd69844ac6818d5367bf3');

-- --------------------------------------------------------

--
-- Table structure for table `Volunteers`
--

CREATE TABLE `Volunteers` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `telephone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `points` int(11) NOT NULL,
  `hours_required` int(11) NOT NULL,
  `hours_completed` int(11) NOT NULL,
  `volunteer_manager` varchar(255) NOT NULL,
  `entry_clerk` varchar(255) NOT NULL,
  `additional_notes` text NOT NULL,
  `registration_date` date NOT NULL,
  `trashed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Volunteers`
--

INSERT INTO `Volunteers` (`id`, `user_id`, `first_name`, `last_name`, `gender`, `date_of_birth`, `address`, `zip_code`, `telephone_number`, `email`, `points`, `hours_required`, `hours_completed`, `volunteer_manager`, `entry_clerk`, `additional_notes`, `registration_date`, `trashed`) VALUES
(99, 531003616983, 'A', 'A', 'Man', '2025-05-02', 'aa', 'a', 'a', 'a@a.com', 6, 7, 8, 'a', 'a', 'a', '2025-05-06', 0),
(126, 2864679956484059464, 'B', 'B', 'Woman', '2025-05-02', 'B', 'B', 'B', 'b@b.com', 30, 6, 6, 'bb', 'b', 'bb', '2025-05-07', 0),
(127, 531003616983, 'AA', 'AAA', 'Man', '2025-05-02', 'A', 'A', 'A', 'aa@aa.com', 30, 6, 3, 'AA', 'A', 'AaaaaA', '2025-05-08', 0),
(128, 5233, 'C', 'C', 'Man', '2025-04-30', 'c', 'c', 'cc', 'c@c.com', 30, 7, 0, 'cc', 'cc', 'c', '2025-05-08', 0),
(129, 5233, 'CC', 'CC', 'Man', '2025-05-16', 'c', 'c', 'c', 'cc@ccc.com', 0, 0, 0, 'c', 'cc', 'c', '2025-05-08', 0),
(130, 543832863, 'Simon', 'Martineau', 'Man', '2025-05-01', 'a', 'a', 'a', 'a@a.com', 29, 6, 1, 'Sarah', 'Clark', '', '2025-05-10', 0),
(131, 543832863, 'E', 'E', 'Man', '2025-05-15', 'E', 'E', 'E', 'e@e.com', 30, 6, 0, 'e', 'e', 'e', '2025-05-20', 0),
(132, 543832863, 'EE', 'EE', 'Woman', '2025-05-01', 'e', 'e', 'e', 'e@e.com', 0, 0, 0, 'e', 'e', 'e', '2025-05-20', 0),
(151, 543832863, 'EE', 'EE', 'Woman', '2025-05-01', 'e', 'e', 'e', 'e@e.com', 0, 0, 0, 'e', 'e', 'e', '2025-05-20', 1),
(170, 531003616983, 'AAa', 'AaA', 'Man', '2025-05-06', 'a', 'a', 'a', 'aa@aa.com', 30, 6, 0, 'a', 'a', 'a', '2025-05-21', 0),
(171, 531003616983, 'A', 'A', 'Man', '2025-05-09', 'a', 'a', 'a', 'a@a.com', 0, 0, 0, 'a', 'a', '', '2025-05-21', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Volunteer_Activity_Junction`
--

CREATE TABLE `Volunteer_Activity_Junction` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `volunteer_id` bigint(20) NOT NULL,
  `contract_id` bigint(20) NOT NULL,
  `activity_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Volunteer_Activity_Junction`
--

INSERT INTO `Volunteer_Activity_Junction` (`id`, `user_id`, `volunteer_id`, `contract_id`, `activity_id`) VALUES
(146, 531003616983, 99, 102, 455),
(147, 2864679956484059464, 126, 103, 471),
(148, 531003616983, 99, 102, 482),
(149, 531003616983, 99, 102, 475),
(150, 531003616983, 127, 104, 485),
(158, 543832863, 130, 107, 488);

-- --------------------------------------------------------

--
-- Table structure for table `Volunteer_Availability`
--

CREATE TABLE `Volunteer_Availability` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `volunteer_id` bigint(20) NOT NULL,
  `weekday` varchar(255) NOT NULL,
  `time_period` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Volunteer_Availability`
--

INSERT INTO `Volunteer_Availability` (`id`, `user_id`, `volunteer_id`, `weekday`, `time_period`) VALUES
(609, 531003616983, 99, 'Monday', 'Morning'),
(610, 531003616983, 100, 'Monday', 'Morning'),
(611, 2864679956484059464, 126, 'Tuesday', 'Morning'),
(612, 531003616983, 127, 'Monday', 'Morning'),
(613, 531003616983, 127, 'Monday', 'Afternoon'),
(614, 531003616983, 127, 'Monday', 'Evening'),
(615, 531003616983, 127, 'Tuesday', 'Morning'),
(616, 531003616983, 127, 'Tuesday', 'Afternoon'),
(617, 531003616983, 127, 'Tuesday', 'Evening'),
(618, 531003616983, 127, 'Wednesday', 'Morning'),
(619, 531003616983, 127, 'Wednesday', 'Afternoon'),
(620, 531003616983, 127, 'Wednesday', 'Evening'),
(621, 531003616983, 127, 'Thursday', 'Morning'),
(622, 531003616983, 127, 'Thursday', 'Afternoon'),
(623, 531003616983, 127, 'Thursday', 'Evening'),
(624, 531003616983, 127, 'Friday', 'Morning'),
(625, 531003616983, 127, 'Friday', 'Afternoon'),
(626, 531003616983, 127, 'Friday', 'Evening'),
(627, 531003616983, 127, 'Saturday', 'Morning'),
(628, 531003616983, 127, 'Saturday', 'Afternoon'),
(629, 531003616983, 127, 'Saturday', 'Evening'),
(630, 531003616983, 127, 'Sunday', 'Morning'),
(631, 531003616983, 127, 'Sunday', 'Afternoon'),
(632, 531003616983, 127, 'Sunday', 'Evening'),
(633, 5233, 128, 'Monday', 'Morning'),
(634, 5233, 128, 'Monday', 'Afternoon'),
(635, 5233, 128, 'Monday', 'Evening'),
(636, 5233, 128, 'Tuesday', 'Morning'),
(637, 5233, 128, 'Tuesday', 'Afternoon'),
(638, 5233, 128, 'Tuesday', 'Evening'),
(639, 5233, 128, 'Wednesday', 'Morning'),
(640, 5233, 128, 'Wednesday', 'Afternoon'),
(641, 5233, 128, 'Wednesday', 'Evening'),
(642, 5233, 128, 'Thursday', 'Morning'),
(643, 5233, 128, 'Thursday', 'Afternoon'),
(644, 5233, 128, 'Thursday', 'Evening'),
(645, 5233, 128, 'Friday', 'Morning'),
(646, 5233, 128, 'Friday', 'Afternoon'),
(647, 5233, 128, 'Friday', 'Evening'),
(648, 5233, 128, 'Saturday', 'Morning'),
(649, 5233, 128, 'Saturday', 'Afternoon'),
(650, 5233, 128, 'Saturday', 'Evening'),
(651, 5233, 128, 'Sunday', 'Morning'),
(652, 5233, 128, 'Sunday', 'Afternoon'),
(653, 5233, 128, 'Sunday', 'Evening'),
(654, 5233, 129, 'Monday', 'Morning'),
(693, 543832863, 132, 'Monday', 'Morning'),
(695, 543832863, 130, 'Monday', 'Morning'),
(696, 543832863, 130, 'Tuesday', 'Morning'),
(697, 543832863, 130, 'Tuesday', 'Afternoon'),
(698, 543832863, 130, 'Tuesday', 'Evening'),
(699, 543832863, 130, 'Wednesday', 'Morning'),
(700, 543832863, 130, 'Thursday', 'Morning'),
(701, 543832863, 130, 'Friday', 'Morning'),
(702, 543832863, 130, 'Saturday', 'Morning'),
(703, 543832863, 130, 'Sunday', 'Morning'),
(704, 543832863, 131, 'Monday', 'Morning'),
(707, 531003616983, 170, 'Tuesday', 'Afternoon'),
(708, 531003616983, 171, 'Wednesday', 'Evening');

-- --------------------------------------------------------

--
-- Table structure for table `Volunteer_Interests`
--

CREATE TABLE `Volunteer_Interests` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `volunteer_id` bigint(20) NOT NULL,
  `interest` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Volunteer_Interests`
--

INSERT INTO `Volunteer_Interests` (`id`, `user_id`, `volunteer_id`, `interest`) VALUES
(633, 531003616983, 99, 'Organization of community events'),
(634, 531003616983, 100, 'Organization of community events'),
(635, 2864679956484059464, 126, 'Library support'),
(636, 531003616983, 127, 'Organization of community events'),
(637, 531003616983, 127, 'Library support'),
(638, 531003616983, 127, 'Help in the community store'),
(639, 531003616983, 127, 'Support in the community grocery store'),
(640, 531003616983, 127, 'Cleaning and maintenance of public spaces'),
(641, 531003616983, 127, 'Participation in urban gardening projects'),
(642, 5233, 128, 'Organization of community events'),
(643, 5233, 128, 'Library support'),
(644, 5233, 128, 'Help in the community store'),
(645, 5233, 128, 'Support in the community grocery store'),
(646, 5233, 128, 'Cleaning and maintenance of public spaces'),
(647, 5233, 128, 'Participation in urban gardening projects'),
(648, 5233, 129, 'Organization of community events'),
(675, 543832863, 132, 'Organization of community events'),
(677, 543832863, 130, 'Organization of community events'),
(678, 543832863, 130, 'Library support'),
(679, 543832863, 130, 'Participation in urban gardening projects'),
(680, 543832863, 131, 'Organization of community events'),
(683, 531003616983, 170, 'Organization of community events'),
(684, 531003616983, 171, 'Cleaning and maintenance of public spaces');

-- --------------------------------------------------------

--
-- Table structure for table `Volunteer_Managers`
--

CREATE TABLE `Volunteer_Managers` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Activities`
--
ALTER TABLE `Activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Activity_Domains`
--
ALTER TABLE `Activity_Domains`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Activity_Time_Periods`
--
ALTER TABLE `Activity_Time_Periods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Contracts`
--
ALTER TABLE `Contracts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Forgot_Password`
--
ALTER TABLE `Forgot_Password`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`),
  ADD KEY `expire` (`expire`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `Purchases`
--
ALTER TABLE `Purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Volunteers`
--
ALTER TABLE `Volunteers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Volunteer_Activity_Junction`
--
ALTER TABLE `Volunteer_Activity_Junction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Volunteer_Availability`
--
ALTER TABLE `Volunteer_Availability`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Volunteer_Interests`
--
ALTER TABLE `Volunteer_Interests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Volunteer_Managers`
--
ALTER TABLE `Volunteer_Managers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Activities`
--
ALTER TABLE `Activities`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=531;

--
-- AUTO_INCREMENT for table `Activity_Domains`
--
ALTER TABLE `Activity_Domains`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1290;

--
-- AUTO_INCREMENT for table `Activity_Time_Periods`
--
ALTER TABLE `Activity_Time_Periods`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=638;

--
-- AUTO_INCREMENT for table `Contracts`
--
ALTER TABLE `Contracts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `Forgot_Password`
--
ALTER TABLE `Forgot_Password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `Purchases`
--
ALTER TABLE `Purchases`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `Volunteers`
--
ALTER TABLE `Volunteers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `Volunteer_Activity_Junction`
--
ALTER TABLE `Volunteer_Activity_Junction`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `Volunteer_Availability`
--
ALTER TABLE `Volunteer_Availability`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=709;

--
-- AUTO_INCREMENT for table `Volunteer_Interests`
--
ALTER TABLE `Volunteer_Interests`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=685;

--
-- AUTO_INCREMENT for table `Volunteer_Managers`
--
ALTER TABLE `Volunteer_Managers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
