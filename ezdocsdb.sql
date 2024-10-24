-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2024 at 05:08 AM
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
-- Database: `ezdocsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `ezdadmintbl`
--

CREATE TABLE `ezdadmintbl` (
  `id` bigint(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ezdadmintbl`
--

INSERT INTO `ezdadmintbl` (`id`, `name`, `email`, `password`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$yNIzF5rOwA1LrgzYzhz1QePMDm09w7/8Znr0aEs41kr6sYc2sCLsG');

-- --------------------------------------------------------

--
-- Table structure for table `ezdrequesttbl`
--

CREATE TABLE `ezdrequesttbl` (
  `id` int(255) NOT NULL,
  `studentLRN` bigint(255) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `gradelvl` varchar(255) NOT NULL,
  `reqDoc` varchar(255) NOT NULL,
  `reqDate` date NOT NULL,
  `status` enum('pending','processing','ready','claimed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ezdrequesttbl`
--

INSERT INTO `ezdrequesttbl` (`id`, `studentLRN`, `fullName`, `gradelvl`, `reqDoc`, `reqDate`, `status`) VALUES
(4, 112543134069, 'Rose Ann Camarao Rivera ', 'Grade 12', 'Form 137', '2024-10-23', 'pending'),
(5, 323789137123, 'Reanne Sevilla Roquero ', 'Grade 12', 'Form 137', '2024-10-23', 'pending'),
(6, 534987131987, 'Rhazel Lagarer Bulos ', 'Grade 11', 'Form 138', '2024-10-23', 'pending'),
(7, 150167133458, 'Princess Ayumi Esquivel Torres ', 'Grade 11', 'Form 137', '2024-10-23', 'pending'),
(8, 516420136542, 'John Carlo Sacro Cruz ', 'Grade 11', 'Form 138', '2024-10-23', 'pending'),
(9, 221598128901, 'Aimee S Balagtas ', 'Grade 10', 'Form 137', '2024-10-23', 'pending'),
(10, 330654127306, 'Zarina H Tadeo ', 'Grade 10', 'Form 137', '2024-10-23', 'pending'),
(11, 152348122614, 'Nash Daniel Cabrera Lising ', 'Grade 10', 'Form 137', '2024-10-23', 'pending'),
(12, 217890125831, 'Jhon Jeffrey M Casino ', 'Grade 10', 'Form 138', '2024-10-23', 'pending'),
(13, 524156164720, 'Marl Lorence P Pinlac ', 'Grade 11', 'Form 137', '2024-10-23', 'pending'),
(14, 135891169186, 'Mico Rivera Rivera ', 'Grade 11', 'Form 137', '2024-10-23', 'pending'),
(15, 350786163045, 'Hasmine M Hipolito  ', 'Grade 10', 'Form 137', '2024-10-23', 'pending'),
(16, 213245161298, 'Mayca De Guzman Pring ', 'Grade 12', 'Certificate of Good Moral', '2024-10-23', 'pending'),
(17, 526901157459, 'Marinel I Santiago ', 'Grade 10', 'Certificate of Good Moral', '2024-10-23', 'pending'),
(18, 137012178602, 'Maralyn A Alfaro ', 'Grade 11', 'Form 137', '2024-10-23', 'pending'),
(19, 258673143147, 'Kate Adhy Valdez Casino ', 'Grade 11', 'Form 137', '2024-10-23', 'pending'),
(20, 510468175790, 'Joy S Balagtas ', 'Grade 10', 'Certificate of Good Moral', '2024-10-23', 'pending'),
(21, 121576162361, 'Althea Mhay D Clemente ', 'Grade 8', 'Form 137', '2024-10-23', 'pending'),
(22, 532654154875, 'Isabella Marie Cruz Castillo ', 'Grade 12', 'Form 138', '2024-10-23', 'pending'),
(23, 351480141023, 'Rafael Joseph Santiago Pacheco  Pacheco ', 'Grade 12', 'Form 138', '2024-10-23', 'pending'),
(24, 183947142156, 'Juan Carlos Reyes Mendoza Reyes Mendoza ', 'Grade 8', 'Certificate of Good Moral', '2024-10-23', 'pending'),
(25, 156478141239, 'Antonio Cruz Quiambao Quiambao ', 'Grade 9', 'Certificate of Enrollment', '2024-10-23', 'pending'),
(26, 110234145678, 'Miguel Dela Rosa Santos ', 'Grade 8', 'Certificate of Good Moral', '2024-10-23', 'pending'),
(27, 298765154321, 'Joseph Villanueva Arevalo ', 'Grade 8', 'Certificate of Enrollment', '2024-10-23', 'pending'),
(28, 321398157645, 'Luis Garcia Yumul ', 'Grade 10', 'Certificate of Good Moral', '2024-10-23', 'pending'),
(29, 547329151580, 'Carlo Emmanuel  Ramos ', 'Grade 9', 'Certificate of Enrollment', '2024-10-23', 'pending'),
(30, 565247168913, 'Enrique David Santos Rojas ', 'Grade 9', 'Form 137', '2024-10-23', 'pending'),
(31, 384136169725, 'Belle De Leon Rosales ', 'Grade 12', 'Form 138', '2024-10-23', 'pending'),
(32, 230615168942, 'Tessa Joy Bautista Angeles ', 'Grade 12', 'Form 138', '2024-10-23', 'pending'),
(33, 179420145138, 'Sienna Serrano Aquino ', 'Grade 11', 'Form 137', '2024-10-23', 'pending'),
(34, 112938144576, 'Isla Grace Cruz Lumibao ', 'Grade 10', 'Certificate of Good Moral', '2024-10-23', 'pending'),
(35, 397513144820, 'Mia Elise Torres Aguas ', 'Grade 10', 'Certificate of Enrollment', '2024-10-23', 'pending'),
(36, 136091142784, 'Eli Lim Cruz ', 'Grade 10', 'Certificate of Enrollment', '2024-10-23', 'pending'),
(37, 125864159731, 'Samuel Reyes Estrada ', 'Grade 10', 'Certificate of Good Moral', '2024-10-23', 'pending'),
(38, 364731159580, 'Elaine Marie Serrano Salazar ', 'Grade 10', 'Form 138', '2024-10-24', 'pending'),
(39, 282074155196, 'Tessa Rae Alvarado Mendoza ', 'Grade 12', 'Form 138', '2024-10-24', 'pending'),
(40, 147385156902, 'Finnian Rex Reyes Mangubat ', 'Grade 10', 'Form 137', '2024-10-24', 'pending'),
(41, 159124167368, 'Theo Blaze Cortez De Leon ', 'Grade 9', 'Certificate of Good Moral', '2024-10-24', 'pending'),
(42, 368241170395, 'Jaden Maximo Alonzo Salcedo ', 'Grade 11', 'Certificate of Good Moral', '2024-10-24', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `requesthistory`
--

CREATE TABLE `requesthistory` (
  `id` int(11) NOT NULL,
  `reqID` int(11) NOT NULL,
  `reqHistoryDesc` text NOT NULL,
  `dateCreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_tbl`
--

CREATE TABLE `student_tbl` (
  `id` int(255) NOT NULL,
  `studentId` bigint(12) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `suffix` varchar(10) NOT NULL,
  `gradeLevel` varchar(50) NOT NULL,
  `phoneNumber` bigint(20) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_tbl`
--

INSERT INTO `student_tbl` (`id`, `studentId`, `firstname`, `middlename`, `lastname`, `suffix`, `gradeLevel`, `phoneNumber`, `emailAddress`, `password`) VALUES
(1, 482914, 'Darwin', 'Bulgado', 'Labiste', '', '9', 9278285895, 'personal.darwinlabiste@gmail.com', '$2y$10$l0kBLAVi99xUkyX.YuzISe4PVdLJ.kOqoeDm0SXesKB.esSQdBiRm'),
(5, 345345, 'Jamesrold', 'Damaso', 'Baliscao', '', '12', 9887776666, 'jamesrold@gmail.com', '$2y$10$yNIzF5rOwA1LrgzYzhz1QePMDm09w7/8Znr0aEs41kr6sYc2sCLsG'),
(12, 112543134069, 'Rose Ann', 'Camarao', 'Rivera', '', '12', 639151234567, 'roseannrivera680@gmail.com', '$2y$10$xCRLyPgZuCo.oYjTbKYfWO9OuAbdwd98X86wQMUBvy5.aEG/cBEUG'),
(13, 323789137123, 'Reanne', 'Sevilla', 'Roquero', '', '12', 639172345678, 'roqueroreanne@gmail.com', '$2y$10$F8Anj/C7rDY08asi5Ti27uRad58MiKFl.5C88O/1925mV/P7HKp7a'),
(14, 534987131987, 'Rhazel', 'Lagarer', 'Bulos', '', '11', 639203456789, 'lagaretranzel@gmail.com', '$2y$10$jKSa7j8IkwJPGdBiOnj5Xe07tIaL/ALX2YObBRk8JY/e5s7Jf3duq'),
(15, 150167133458, 'Princess Ayumi', 'Esquivel', 'Torres', '', '11', 639187654321, 'ayumiprincess19@gmail.com', '$2y$10$mDQKqWEdBJy215jMvuB67.3czA5yyesnqs0i717z6/oEYCrI/HyGC'),
(16, 516420136542, 'John Carlo', 'Sacro', 'Cruz', '', '11', 639216789012, 'cruzjohncarlo940@gmail.com', '$2y$10$pOcHEPQIfMzpJF6xsRV5t.xNdfgKm3oo6MJWVXqGGNUKGl4QCf2z2'),
(17, 221598128901, 'Aimee', 'S', 'Balagtas', '', '10', 639165432109, 'aimeebalagtas31@gmail.com', '$2y$10$8GNcydtjSmVAwR.pj1bYUu0YPfC7QJDrPkxuJjQ0Fv9/xMWDIFzTW'),
(18, 330654127306, 'Zarina', 'H', 'Tadeo', '', '10', 639229876543, 'zarinatadeo49@gmail.com', '$2y$10$Qed3Ee27Zd6FDgYZzdgmIOIRbDnrxqKYL3n12WJNOrX9lMAjz0XWK'),
(19, 152348122614, 'Nash Daniel', 'Cabrera', 'Lising', '', '10', 639134567890, 'nashdaniellising08@gmail.com', '$2y$10$5xie7FjStw26JrX.LfLo0uyyexlnuIJp5JlPC5IKgEklkCHY4ciqy'),
(20, 217890125831, 'Jhon Jeffrey', 'M', 'Casino', '', '10', 639232345678, 'freyvver@gmail.com', '$2y$10$OeMJeIsqU0oQ5rEipe7PnuNrzy6s4ASTbzi2MjL6FWozEpH1HjzCq'),
(21, 524156164720, 'Marl Lorence', 'P', 'Pinlac', '', '11', 639143210987, 'marlpinlac17@gmail.com', '$2y$10$gqY43YY8nWnZ0eMAHKjinudtfYz2l08urR8ZTDQcc.f6wsiFZpddW'),
(22, 135891169186, 'Mico', 'Rivera', 'Rivera', '', '11', 639256789013, 'cmicorivera@gmail.com', '$2y$10$cQQyQu70/85g3HoguZjBretOaEpurlFBO19E4qDl1/7dvFaHIqDJS'),
(23, 350786163045, 'Hasmine', 'M', 'Hipolito ', '', '10', 639190123456, 'hasminehipolito@gmail.com', '$2y$10$iN.QOJpNVn3Cs2/N7/fas..TP7kib0Cfz.TMLj/PKywSn0zbsM986'),
(24, 213245161298, 'Mayca', 'De Guzman', 'Pring', '', '10', 639245678901, 'maycapring222008@gmail.com', '$2y$10$kfSARj/dGZnZDfpsyQjscu/POvKFHFeUkiMSQ.cZkoK33NPtwNljW'),
(25, 526901157459, 'Marinel', 'I', 'Santiago', '', '10', 639108765432, 'marinelsamaniego@gmail.com', '$2y$10$s6WSUrCO8cN0xN2CjgNupOy1NXZnPxP1viDIbteYT6LYd3/BFIJrS'),
(26, 137012178602, 'Maralyn', 'A', 'Alfaro', '', '11', 639260456789, 'maralynalfaro06@gmail.com', '$2y$10$nvFwKPkD7BljJhtcYegtZOsgGQAcdv8b0stLbgE6eZ61G/ato8UHm'),
(27, 258673143147, 'Kate Adhy', 'Valdez', 'Casino', '', '11', 639124567891, 'kateadhyValdezCasino@gmail.com', '$2y$10$.kJHMTBcMFfrGDii.lywpOoyBOOkkHXEWPsfALJIRWnc/0lcCWQEu'),
(28, 510468175790, 'Joy', 'S', 'Balagtas', '', '10', 639289012345, 'jsblgts88@gmail.com', '$2y$10$W8aCuCElpXZbLgSoBSDS..2VLTHTcgDGDxuc52sGwOQN2WWy5E7XW'),
(29, 121576162361, 'Althea Mhay', 'D', 'Clemente', '', '8', 639112345678, 'altheamhay88@gmail.com', '$2y$10$EkO.urAS.jIFP0VvpudkU.v8Qh8sjM1auTaLECZaFWDVzB8DI0PD2'),
(30, 532654154875, 'Isabella Marie', 'Cruz', 'Castillo', '', '12', 639278901234, 'isabellamariecruz@gmail.com', '$2y$10$w1Y0P4JzZVnHVj7ek6iYoODTtRF4XtRfcq/GaVKJKW8c4j2ZTRGpi'),
(31, 351480141023, 'Rafael Joseph', 'Santiago Pacheco', ' Pacheco', '', '12', 639123456780, 'rafaeljosephsantiago@gmail.com', '$2y$10$G/HUOCT/SXd7u7P2NXCSNeMWyXhp7UmP/idi11fFffQdyeURymQSW'),
(32, 183947142156, 'Juan Carlos Reyes Mendoza', 'Reyes', 'Mendoza', '', '8', 639156789012, 'juancarlosreyes@gmail.com', '$2y$10$uON6shUkbXnGS4JbzXuwqu4WIxCfrPBqYL.1wQuL.kFKIal8RHFBy'),
(33, 156478141239, 'Antonio', 'Cruz', 'Quiambao', '', '9', 639126789015, 'antoniocruz@gmail.com', '$2y$10$sRUn4a9gjj59TMqubB0nWO.aac/sI7w3012XQVAJGgil88ZKL3bji'),
(34, 110234145678, 'Miguel', 'Dela Rosa', 'Santos', '', '8', 639272345671, 'migueldelarosa@gmail.com', '$2y$10$SiCyrMp6Ob0UJIzXD.2NyOs8jcIpYmVXjxfn9PjsU/qDnG2.zxTye'),
(35, 298765154321, 'Joseph', 'Villanueva', 'Arevalo', '', '8', 639117890124, 'josephvillanueva@gmail.com', '$2y$10$O/VjYoRLKK1DHaLhw8NLNu0xOBGS0fWy566KoX/tR0yRSAQLE8hdu'),
(36, 321398157645, 'Luis', 'Garcia', 'Yumul', '', '10', 639285678903, 'luisgarcia@gmail.com', '$2y$10$a4VLGJk6akGVMncW1vtQAuIe8N2KlZFe7XZIcpKb.8D4Q4mwoXx6K'),
(37, 547329151580, 'Carlo Emmanuel', '', 'Ramos', '', '9', 639268901236, 'carloemmanuelramos@gmail.com', '$2y$10$H.Frquwh/ywJpX2DJPo4L.jVCAwOmCZbrm5PClueFn9HmsRE9onim'),
(38, 565247168913, 'Enrique David', 'Santos', 'Rojas', '', '9', 639106789011, 'davidenriquesantos@gmail.com', '$2y$10$9eCH.KbBQTjgSkpAa2WZ8.YbjrJLmrL2RIO5MPhJbSdjo8doi.2mG'),
(39, 384136169725, 'Belle', 'De Leon', 'Rosales', '', '12', 639243456780, 'belledeleon@gmail.com', '$2y$10$8DSmKz4L5Ig1P8Df/WEc2eVAyLcaACEFPk2HAEP0uVjjjYDqoIOXG'),
(40, 230615168942, 'Tessa Joy', 'Bautista', 'Angeles', '', '12', 639198902345, 'tessajoybautista@gmail.com', '$2y$10$x6VdMIbU/8rPmV9x3pJIqeuRLPaclRZ0xzS45t1dwU5TtkXo/WVm6'),
(41, 179420145138, 'Sienna', 'Serrano', 'Aquino', '', '11', 639251234567, 'siennaserrano@gmail.com', '$2y$10$hpjYWoo/TJ3dJsp4n1XaquOf1hVUS3zc8Z9ZXkaSSzm4ibxn1OHke'),
(42, 112938144576, 'Isla Grace', 'Cruz', 'Lumibao', '', '10', 639146789013, 'islagracecruz@gmail.com', '$2y$10$to3.SUaZgkvwlBRkP1zzYOEDxGkIEoQN2rH7Gb/t3yPhJCmZdN4q6'),
(43, 145867142913, 'Ava Marie', 'Lopez', 'Aquino', '', '10', 639251234567, 'avamarielopez@gmail.com', '$2y$10$GTA4qeA3jhVwqPoVALja4uxCruJVFDMDLJ7uextbyholaz8eLxVbK'),
(44, 397513144820, 'Mia Elise', 'Torres', 'Aguas', '', '10', 639146789013, 'miaelisetorres@gmail.com', '$2y$10$P90qdi9OdVpH.zc/.bBcRuoZfpVXfdHyQ/YKv78q2ZXT6Qi9nkCoC'),
(45, 136091142784, 'Eli', 'Lim', 'Cruz', '', '10', 639235678902, 'cruzelim@gmail.com', '$2y$10$mHwYErMIWFeJLKkt9odUUex6EDIGytAjrjqYETmfDjC7VR88TehQW'),
(46, 125864159731, 'Samuel', 'Reyes', 'Estrada', '', '10', 639135432109, 'samuelreyes@gmail.com', '$2y$10$6tqRjWq3WdAiZZdnP2TaGuSm3U2DztAvJsvc2xDGU1rEp4fwwNHvq'),
(47, 364731159580, 'Elaine Marie', 'Serrano', 'Salazar', '', '10', 639226789012, 'elainemarieserrano@gmail.com', '$2y$10$uNMC45/EFtte/baG6j.YV.RYjmSZKfZyO/opsU82CQBeeOGgrXqa2'),
(48, 282074155196, 'Tessa Rae', 'Alvarado', 'Mendoza', '', '9', 639160123456, 'tessaraealvarado@gmail.com', '$2y$10$YtL5x5Ve08/z14TFuad4BuNQ31F.8GM4JTjUo2qZy.BRdryyEcr96'),
(49, 147385156902, 'Finnian Rex', 'Reyes', 'Mangubat', '', '10', 639218901234, 'finnianrexreyes@gmail.com', '$2y$10$Heh4FbNfYxo8cagK3ZkOFOjUpbKJW3./zHWP9a2ACxKsJyetavCiu'),
(50, 159124167368, 'Theo Blaze', 'Cortez', 'De Leon', '', '9', 639182345678, 'theoblazecortez@gmail.com', '$2y$10$HnC3nHVjMa2sUozVY/PT4OfeJF5Maqhk00WtLfu3Xnslk3YeBWbSu'),
(51, 368241170395, 'Jaden Maximo', 'Alonzo', 'Salcedo', '', '11', 639205678901, 'jadenmaximoalonzo@gmail.com', '$2y$10$p0byuF9JsJKti1giY4UKi.oSujWQI4n2eb89OJAeBsaT6SV2ZGjiq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ezdadmintbl`
--
ALTER TABLE `ezdadmintbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezdrequesttbl`
--
ALTER TABLE `ezdrequesttbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ezdrequesttbl_ibfk_1` (`studentLRN`);

--
-- Indexes for table `requesthistory`
--
ALTER TABLE `requesthistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reqID` (`reqID`);

--
-- Indexes for table `student_tbl`
--
ALTER TABLE `student_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `studentId` (`studentId`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ezdadmintbl`
--
ALTER TABLE `ezdadmintbl`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ezdrequesttbl`
--
ALTER TABLE `ezdrequesttbl`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `requesthistory`
--
ALTER TABLE `requesthistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_tbl`
--
ALTER TABLE `student_tbl`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
