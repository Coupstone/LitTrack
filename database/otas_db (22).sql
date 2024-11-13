-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 04:50 PM
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
-- Database: `otas_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive_authors`
--

CREATE TABLE `archive_authors` (
  `id` int(11) NOT NULL,
  `archive_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `author_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive_authors`
--

INSERT INTO `archive_authors` (`id`, `archive_id`, `first_name`, `last_name`, `author_order`) VALUES
(0, 32, 'Owen Harvey', 'Balocon', 1),
(0, 32, 'Christian Ivan', 'Bandilla', 2),
(0, 32, 'Biella Mae', 'Mariscotes', 3),
(0, 32, 'Carl', 'Tabuso', 4),
(0, 33, 'Kimjude', 'Amayon', 1),
(0, 33, 'Owen Harvey', 'Balocon', 2),
(0, 33, 'Frederick Edward', 'Fabella', 3),
(0, 33, 'Karylle Marie', 'Justimbaste', 4),
(0, 33, 'Aedriel', 'Velando', 5),
(0, 34, 'Kimjude', 'Amayon', 1),
(0, 34, 'Owen Harvey', 'Balocon', 2),
(0, 34, 'Frederick Edward', 'Fabella', 3),
(0, 34, 'Karylle Marie', 'Justimbaste', 4),
(0, 34, 'Aedriel', 'Velando', 5),
(0, 37, 'Kimjude', 'Amayon', 1),
(0, 37, 'Owen Harvey', 'Balocon', 2),
(0, 38, 'Kimjude', 'Amayon', 1),
(0, 39, 'Owen Harvey', 'Amayon', 1),
(0, 40, 'Kimjude', 'Amayon', 1),
(0, 41, 'Kimjude', 'Balocon', 1),
(0, 42, 'qwer', 'qwer', 1),
(0, 43, 'Owen Harvey', 'Amayon', 1),
(0, 45, 'qwerwqe', 'wqerqwer', 1),
(0, 46, 'Kimjude', 'Amayon', 1),
(0, 47, 'Kimjude', 'qwerwqer', 1),
(0, 48, 'Owen Harvey', 'fdghfgh', 1),
(0, 49, 'Owen Harvey', 'fdghfgh', 1),
(0, 50, 'qwer', 'qwerwqer', 1),
(0, 51, 'qwer', 'qwerwqer', 1),
(0, 52, 'qwerteyu', 'qwerwqer', 1),
(0, 53, 'qwerteyu', 'qwerwqer', 1),
(0, 54, 'qwer', 'fdghfgh', 1),
(0, 55, 'uiytru', 'ytuiytu', 1),
(0, 56, 'Owen Harvey', 'kimjude', 1),
(0, 57, 'qwerwqe', 'wqer', 1),
(0, 58, 'Owen Harvey', 'Balocon', 1),
(0, 59, 'wert', 'terwt', 1),
(0, 66, 'wqer', 'qwer', 1),
(0, 66, 'rety', 'tryutyru', 2),
(0, 67, 'rtyu', 'rtyu', 1),
(0, 68, 'wqer', 'rtyu', 1),
(0, 69, 'rety', 'wertewrt', 1),
(0, 70, 'ertyqwerqw', 'fdghfgh', 1),
(0, 71, 'dfgh', 'Balocon', 1),
(0, 72, 'yrty', 'fdghfgh', 1),
(0, 73, 'qwer', 'qweqwe', 1),
(0, 74, 'erter', 'qweqwt', 1),
(0, 75, 'uytru', 'tryu', 1),
(0, 76, 'wew', 'wow', 0),
(0, 77, 'tret', 'ytrq', 0),
(0, 78, 'Owen Harvey ', 'Amayon', 0),
(0, 79, 'qwer', 'qwer', 0),
(0, 80, 'Andrei', 'Sean', 0),
(0, 80, 'Forlanda', 'Sheesh', 0),
(0, 81, 'Sean', 'Anmdrei', 0),
(0, 81, 'Great', 'Sean', 0),
(0, 82, 'Owen Harvey ', 'Amayon', 1),
(0, 82, 'Kimjude', 'Balocon', 2),
(0, 83, 'Kimjude', 'Andrei', 1),
(0, 83, 'sean', 'pogi', 2),
(0, 84, 'Monkey ', 'Luffy', 1),
(0, 84, 'Monkey', 'Garp', 2),
(0, 85, 'Owen Harvey ', 'Balocon', 1),
(0, 85, ' Christian Ivan ', 'Bandilla', 2),
(0, 85, ' Biella Mae ', 'Mariscotes', 3),
(0, 85, 'Carl ', 'Tabuso', 4),
(0, 86, 'Kimjude ', 'Amayon', 1),
(0, 86, 'Owen Harvey ', 'Balocon', 2),
(0, 86, 'Frederick Edward ', 'Fabella', 3),
(0, 86, 'Karylle Marie ', 'Justimbaste', 4),
(0, 86, 'Aedriel ', 'Velando', 5),
(0, 87, 'Kimjude', 'Amayon', 1),
(0, 88, 'Owen Harvey', 'Balocon', 1),
(0, 88, 'Rhey Yuri', 'Dator', 2),
(0, 88, 'Marie Jeremie', 'Legrama', 3),
(0, 88, 'Abegaile', 'Vicuña', 4),
(0, 89, 'Yihang', 'Bai', 1),
(0, 89, 'Ruoyu ', 'Wang', 2),
(0, 89, 'Linchuan ', 'Yang', 3),
(0, 89, 'Yantao ', 'Ling', 4),
(0, 89, 'Mengqiu ', 'Cao', 5),
(0, 90, 'Xiaoqi', 'Feng', 1),
(0, 90, 'Renin ', 'Toms', 2),
(0, 90, 'Thomas ', 'Astell-Burt', 3),
(0, 91, 'QWERWQE', 'QWER', 1),
(0, 92, 'Kimjude', 'asdf', 1),
(0, 93, 'Xiaoqi', 'Feng', 1),
(0, 93, 'Renin ', 'Toms', 2),
(0, 93, 'Thomas', 'Astell‑Burt', 3),
(0, 94, 'Yihang', 'Bai', 1),
(0, 94, 'Ruoyu', 'Wang', 2),
(0, 94, 'Linchuan ', 'Yang', 3),
(0, 94, 'Yantao ', 'Ling', 4),
(0, 94, 'Mengqiu ', 'Cao', 5),
(0, 95, 'Owen Harvey', 'Balocon', 1),
(0, 95, 'Rhey Yuri', 'Dator', 2),
(0, 95, 'Marie Jeremie ', 'Legrama', 3),
(0, 95, 'Abegaile', 'Vicuña', 4),
(0, 96, 'Owen Harvey', 'Balocon', 1),
(0, 96, 'Christian Ivan ', 'Bandilla', 2),
(0, 96, 'Biella Mae', 'Mariscotes', 3),
(0, 96, 'Carl', 'Tabuso', 4),
(0, 97, 'Kimjude', 'Amayon', 1),
(0, 97, 'Owen Harvey ', 'Balocon', 2),
(0, 97, 'Frederick Edward ', 'Fabella', 3),
(0, 97, 'Karylle Marie ', 'Justimbaste', 4),
(0, 97, 'Aedriel ', 'Velando', 5),
(0, 98, 'Kimjude', 'qwer', 1),
(0, 101, 'John', 'Doe', 1),
(0, 101, 'Jane', 'Smith', 2),
(0, 101, 'Johns', 'Doe', 1),
(0, 101, 'Janse', 'Smith', 2),
(0, 101, 'John', 'Doe', 1),
(0, 101, 'Jane', 'Smith', 2),
(0, 102, 'ssJohn', 'Doe', 1),
(0, 102, 'eeJane', 'Smith', 2),
(0, 103, 'Kimjude', 'ertert', 1),
(0, 104, 'Johnasdf', 'Doe', 1),
(0, 104, 'werwerJane', 'Smith', 2),
(0, 105, 'erwtyJohn', 'Doe', 1),
(0, 105, 'qwerweJane', 'Smith', 2),
(0, 106, 'ewrteJohn', 'Doe', 1),
(0, 106, 'Jytryeane', 'Smith', 2),
(0, 107, 'rtyhfdgh', 'tuyrutyr', 1),
(0, 108, 'Kimjude', 'Amayon', 0),
(0, 108, 'Owen Harvey ', 'Balocon', 0),
(0, 108, 'Frederick Edward ', 'Fabella', 0),
(0, 108, 'Karylle Marie ', 'Justimbaste', 0),
(0, 108, 'Aedriel ', 'Velando', 0),
(0, 109, 'Xiaoqi', 'Feng', 0),
(0, 109, 'Renin ', 'Toms', 0),
(0, 109, 'Thomas', 'Astell‑Burt', 0),
(0, 110, 'ryteer', 'retyuert', 1);

-- --------------------------------------------------------

--
-- Table structure for table `archive_citation`
--

CREATE TABLE `archive_citation` (
  `id` int(11) NOT NULL,
  `archive_id` int(11) NOT NULL,
  `download_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `citation_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive_citation`
--

INSERT INTO `archive_citation` (`id`, `archive_id`, `download_date`, `citation_count`) VALUES
(1, 97, '2024-11-12 16:13:35', 0),
(2, 97, '2024-11-12 16:22:01', 0),
(3, 106, '2024-11-13 09:01:03', 0);

-- --------------------------------------------------------

--
-- Table structure for table `archive_downloads`
--

CREATE TABLE `archive_downloads` (
  `id` int(11) NOT NULL,
  `archive_id` int(11) NOT NULL,
  `download_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive_downloads`
--

INSERT INTO `archive_downloads` (`id`, `archive_id`, `download_date`) VALUES
(1, 106, '2024-11-08 19:00:35'),
(2, 97, '2024-11-13 00:22:28'),
(3, 97, '2024-11-13 00:23:11');

-- --------------------------------------------------------

--
-- Table structure for table `archive_list`
--

CREATE TABLE `archive_list` (
  `id` int(30) NOT NULL,
  `archive_code` varchar(100) NOT NULL,
  `curriculum_id` int(30) NOT NULL,
  `year` year(4) NOT NULL,
  `title` text NOT NULL,
  `abstract` text NOT NULL,
  `members` text NOT NULL,
  `banner_path` text NOT NULL,
  `document_path` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `student_id` int(30) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `pdf_filename` varchar(255) DEFAULT NULL,
  `doi` varchar(255) DEFAULT NULL,
  `authors` text DEFAULT NULL,
  `publication_year` int(11) DEFAULT NULL,
  `is_favorite` tinyint(4) NOT NULL DEFAULT 0,
  `reads` int(11) DEFAULT 0,
  `downloads` int(11) DEFAULT 0,
  `citations` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive_list`
--

INSERT INTO `archive_list` (`id`, `archive_code`, `curriculum_id`, `year`, `title`, `abstract`, `members`, `banner_path`, `document_path`, `status`, `student_id`, `date_created`, `date_updated`, `pdf_filename`, `doi`, `authors`, `publication_year`, `is_favorite`, `reads`, `downloads`, `citations`) VALUES
(93, '20240001', 1, '2024', 'THE NEXUS BETWEEN URBAN GREEN SPACE, HOUSING TYPE, AND MENTAL HEALTH', 'Introduction\r\nMomentum for urban densification is increasing opportunities for apartment-living, but can result in reduced green space availability that negatively influences mental health. However, in contexts where apartment-living is atypical and commonly viewed as secondary to house-ownership, it may be a stressful antecedent condition (or marker of selective processes aligned with psychological distress) wherein occupants could benefit disproportionately from green space.\r\n\r\nMethod\r\nData were extracted from the Sax Institute&rsquo;s 45 and Up Study baseline (2006&ndash;2009, n=267,153). The focus was on subsets of 13,196 people living in apartments and 66,453 people living in households within the cities of Sydney, Newcastle, and Wollongong. Multilevel models adjusted for confounders tested associations between psychological distress (Kessler 10 scale) with percentage total green space, tree canopy, and open grass within 1.6 km road network buffers.\r\n\r\nResults\r\nPsychological distress was higher in occupants of apartments (11.3%) compared with houses (7.9%). More green space was associated with less psychological distress for house-dwellers (OR=0.94, 95% CI=0.91&ndash;0.98), but there was no association for apartment-dwellers. More tree canopy was associated with lower psychological distress for house-dwellers (OR=0.88, 95% CI=0.85&ndash;0.92) and apartment-dwellers (OR=0.87, 95% CI=0.79&ndash;0.96). Open grass was associated with more psychological distress among house-dwellers (OR=1.06, 95% CI=1.00&ndash;1.13) and also for apartment-dwellers (OR=1.20, 95% CI=1.07&ndash;1.35).\r\n\r\nConclusions\r\nOverall, investments in tree canopy may benefit the mental health of house and apartment residents relatively equally. Urban tree canopy in densely populated areas where apartments are common needs to be protected. Further work is needed to understand factors constraining the prevention potential of open grass to unlock its benefits for mental health.', '', '', 'uploads/pdf/archive-93.pdf', 1, 31, '2024-11-02 21:44:30', '2024-11-13 17:39:17', NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(94, '20240002', 1, '2024', 'THE IMPACTS OF VISIBLE GREEN SPACES ON THE MENTAL WELLBEING OF UNIVERSITY STUDENTS', 'The benefits of green spaces on individuals&rsquo; health have been widely acknowledged due to their inherent natural qualities. Currently, university students are experiencing significantly higher levels of mental health problems than other social groups. There is a scarcity of studies examining the association between built environment factors and mental health issues among university students, particularly in the Chinese context. University campuses in China are physically isolated, secluded communities, and in this respect, they differ markedly from the spatial organisation patterns of Western universities.\r\n\r\nTherefore, this study focuses on the correlation between the extent of green space exposure within closed university campuses and the occurrence of mental health issues among resident students. A deep-learning methodology incorporating streetscape images, remote sensing data, and multilevel linear modelling is employed in order to facilitate a comprehensive analysis.\r\n\r\nThe results demonstrate a negative correlation between green space exposure on campus and the level of mental health issues among university students. Individual sociodemographic characteristics, such as whether a person has a partner, are also found to influence the level of mental health issues that they experience. In addition, a significant relationship is found between travel patterns and mental health issues, with students who walked regularly having a lower incidence of mental health issues than those who drove.\r\n\r\nOur research indicates that, in order to foster healthier communities and enhance social inclusion, urban planners should prioritise the development of greener campuses and urban transport services to improve accessibility to green spaces.', '', '', 'uploads/pdf/archive-94.pdf', 1, 31, '2024-11-02 21:53:07', '2024-11-13 17:39:19', NULL, NULL, NULL, NULL, 1, 0, 0, 0),
(95, '20240003', 1, '2024', 'WEB-BASED MATERNITY RECORD SYSTEM WITH APPOINTMENT AND BILLING FOR A LYING IN CLINIC IN LAGUNA', 'In response to challenges faced by the lying-in clinic in employing manual practices for clinic operations, particularly in record management, appointment scheduling, and bill generation, researchers conducted a study using a descriptive-quantitative research design. To understand these challenges comprehensively, both midwives/healthcare providers and affected patients participated. Focusing on the paper-based processes, the researchers identified barriers in the lying-in clinic&#039;s operations and how manual practices impacted patient satisfaction in maternal care. Findings revealed most midwives/healthcare providers were cynical about the effectiveness of manual practices, suggesting the paper-based system needed enhancements. Another finding showed patients receiving maternal care were generally dissatisfied with the clinic&rsquo;s paper-based processes, indicating that integrating the developed system could elevate patient satisfaction. Overall, the study suggested an information system integration could enhance the lying-in clinic&rsquo;s efficiency and raise the standard of patient care. These conclusions aligned with the primary objective of the researchers&ndash;developing the record system with appointment and billing for the lying-in clinic in Santa Rosa, Laguna with a consistent and organized approach to managing patient records, appointment schedules, and bill generation.', '', '', 'uploads/pdf/archive-95.pdf', 1, 31, '2024-11-02 21:55:12', '2024-11-13 17:39:18', NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(96, '20240004', 1, '2024', 'SEAL OF GOOD LOCAL GOVERNANCE FOR BARANGAY (SGLGB) MANAGEMENT SYSTEM FOR THE DILG OF CITY OF SANTA ROSA', 'The Seal of Good Local Governance for Barangay (SGLGB) is an annual performance assessment and recognition system that evaluates barangays based on various governance areas to encourage local service improvement through recognition and incentives. This study explores the city-level administration of the SGLGB program with a primary focus on the operations conducted by the Department of the Interior and Local Government (DILG) office in the City of Santa Rosa. Through a descriptive developmental research approach, the study probes the current manual procedures employed by the Component City/Municipality Performance Assessment Team (CC/M PAT) in manual document verification, record-keeping, and tracking. Prior findings reveal that the existing manual paperwork processes are labor-intensive, prone to errors, and contribute to the office&#039;s space constraints, prompting the researchers to propose and develop the SGLGB Management System. To guarantee the effectiveness of the said system, the researchers conducted usability testing sessions with barangay secretaries in selected barangays, alongside the assessment team in the DILG office, to evaluate the acceptance level of the system&#039;s functionality, usability, and security aspects based on ISO 25010 Software Product Quality. Subsequent findings indicate that the users favor and accept the developed system due to its alignment with their tasks. Therefore, the researchers recommend implementing the SGLGB Management System to optimize operational efficiency in the DILG office. The findings also suggest considering additional security measures and adaptability for future scalability. Further research is recommended to evaluate the system&#039;s usability across all barangays in the City of Santa Rosa to ensure its effectiveness.', '', '', 'uploads/pdf/archive-96.pdf', 1, 31, '2024-11-02 21:57:48', '2024-11-13 23:49:12', NULL, NULL, NULL, NULL, 1, 0, 0, 0),
(97, '20240005', 1, '2024', 'NUTRITION INFORMATION AND AUDIT SUBMISSION PORTAL FOR THE BARANGAYS OF A CERTAIN MUNICIPALITY IN LAGUNA', 'This research underscores the pressing need for establishing a nutrition information and audit submission portal for barangays of a certain municipality in Laguna, to rectify deficiencies in local nutritional assessment and supervision. This system empowers the community to make informed decisions and implement targeted interventions by providing a well-organized framework. The primary objective is to enhance health outcomes by examining current methods for gathering nutritional data, assessing the impact of manual data entry on the Operational Timbang Plus (OTP) system, devising strategies for effectively disseminating accurate nutritional information to citizens and officials, and implementing a uniform audit system to evaluate nutrition programs and monitor health trends in barangays. The research used quantitative research methodology, data were collected and analyzed through thematic analysis and weighted mean calculation. Non-probability purposive sampling was employed, involving interviews and questionnaires distributed across the city and barangays. The study findings highlight challenges with the OTP system, particularly regarding data accuracy due to human input errors. There is an evident need to establish a standardized audit system to enhance data quality and decision-making in nutrition initiatives. Utilizing technological solutions such as web-based systems or mobile apps can improve data collection efficiency.', '', '', 'uploads/pdf/archive-97.pdf', 1, 31, '2024-11-02 21:59:27', '2024-11-13 17:39:05', NULL, NULL, NULL, NULL, 1, 0, 0, 0),
(98, '20240006', 1, '2024', 'werqw', 'ewrewrt', '', '', 'uploads/pdf/archive-98.pdf', 0, 31, '2024-11-05 00:49:57', '2024-11-05 00:49:57', NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(106, '20240007', 1, '2024', 'Seal of Good Local Governance for Barangay SGLGB Management System', 'This is a test abstract for the Seal of Good Local Governance project.', '', '', 'uploads/pdf/archive-96.pdf', 1, 31, '2024-11-05 02:33:12', '2024-11-13 17:35:37', NULL, NULL, NULL, NULL, 1, 0, 0, 0),
(107, '20240008', 1, '2024', 'asdfqwreqwe', 'qwewetqwer', '', '', 'uploads/pdf/archive-107.pdf', 0, 31, '2024-11-08 19:05:22', '2024-11-08 19:05:22', NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(108, '', 0, '2024', 'NUTRITION INFORMATION AND AUDIT SUBMISSION PORTAL FOR THE BARANGAYS OF A CERTAIN MUNICIPALITY IN LAGUNA', '', '', '', '', 0, NULL, '2024-11-11 21:12:21', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(109, '', 0, '2024', 'THE NEXUS BETWEEN URBAN GREEN SPACE, HOUSING TYPE, AND MENTAL HEALTH', '', '', '', '', 0, NULL, '2024-11-11 21:21:28', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(110, '20240009', 1, '2023', 'werqwer', 'wqertetyw', '', '', 'uploads/pdf/archive-110.pdf', 0, 31, '2024-11-13 16:43:53', '2024-11-13 16:43:53', NULL, NULL, NULL, NULL, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `archive_reads`
--

CREATE TABLE `archive_reads` (
  `id` int(11) NOT NULL,
  `archive_id` int(11) DEFAULT NULL,
  `read_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive_reads`
--

INSERT INTO `archive_reads` (`id`, `archive_id`, `read_date`) VALUES
(1, 97, '2024-11-08 10:51:15'),
(2, 97, '2024-11-08 10:51:18'),
(3, 97, '2024-11-08 10:51:54'),
(4, 96, '2024-11-08 10:52:14'),
(5, 97, '2024-11-08 10:52:20'),
(6, 97, '2024-11-08 10:52:30'),
(7, 106, '2024-11-08 10:52:53'),
(8, 106, '2024-11-08 10:53:17'),
(9, 97, '2024-11-08 10:54:11'),
(10, 97, '2024-11-08 10:54:16'),
(11, 97, '2024-11-08 10:56:24'),
(12, 97, '2024-11-08 10:56:24'),
(13, 97, '2024-11-08 10:56:24'),
(14, 97, '2024-11-08 10:56:30'),
(15, 97, '2024-11-08 10:56:30'),
(16, 97, '2024-11-08 10:56:30'),
(17, 97, '2024-11-08 10:56:31'),
(18, 97, '2024-11-08 10:56:31'),
(19, 97, '2024-11-08 10:56:31'),
(20, 97, '2024-11-08 10:56:31'),
(21, 97, '2024-11-08 10:56:32'),
(22, 97, '2024-11-08 10:56:32'),
(23, 97, '2024-11-08 10:56:32'),
(24, 97, '2024-11-08 10:56:32'),
(25, 97, '2024-11-08 10:56:33'),
(26, 97, '2024-11-08 10:56:33'),
(27, 97, '2024-11-08 10:56:33'),
(28, 97, '2024-11-08 10:56:33'),
(29, 97, '2024-11-08 10:56:34'),
(30, 93, '2024-11-08 10:56:50'),
(31, 96, '2024-11-08 10:58:46'),
(32, 106, '2024-11-08 11:00:31'),
(33, 106, '2024-11-08 11:01:03'),
(34, 107, '2024-11-08 11:05:24'),
(35, 106, '2024-11-10 14:16:21'),
(36, 97, '2024-11-10 14:16:26'),
(37, 97, '2024-11-10 14:16:47'),
(38, 96, '2024-11-10 14:20:28'),
(39, 94, '2024-11-10 14:39:38'),
(40, 94, '2024-11-10 14:45:02'),
(41, 96, '2024-11-11 14:13:57'),
(42, 97, '2024-11-12 16:13:15'),
(43, 97, '2024-11-12 16:17:20'),
(44, 97, '2024-11-12 16:17:20'),
(45, 97, '2024-11-12 16:17:21'),
(46, 97, '2024-11-12 16:17:22'),
(47, 97, '2024-11-12 16:17:22'),
(48, 97, '2024-11-12 16:18:40'),
(49, 97, '2024-11-12 16:18:40'),
(50, 97, '2024-11-12 16:18:40'),
(51, 97, '2024-11-12 16:18:41'),
(52, 97, '2024-11-12 16:19:23'),
(53, 97, '2024-11-12 16:19:32'),
(54, 97, '2024-11-12 16:19:33'),
(55, 97, '2024-11-12 16:19:48'),
(56, 97, '2024-11-12 16:20:30'),
(57, 97, '2024-11-12 16:20:36'),
(58, 97, '2024-11-12 16:21:58'),
(59, 97, '2024-11-12 16:22:07'),
(60, 97, '2024-11-12 16:22:07'),
(61, 106, '2024-11-12 16:22:43'),
(62, 97, '2024-11-12 16:22:47'),
(63, 97, '2024-11-12 16:23:15'),
(64, 97, '2024-11-12 16:24:20'),
(65, 96, '2024-11-12 16:24:37'),
(66, 106, '2024-11-13 04:23:09'),
(67, 110, '2024-11-13 08:43:54'),
(68, 106, '2024-11-13 08:44:17'),
(69, 106, '2024-11-13 09:00:15'),
(70, 97, '2024-11-13 09:01:18'),
(71, 106, '2024-11-13 09:05:12'),
(72, 106, '2024-11-13 09:05:23'),
(73, 106, '2024-11-13 09:23:58'),
(74, 95, '2024-11-13 09:32:39'),
(75, 106, '2024-11-13 09:42:36'),
(76, 93, '2024-11-13 14:54:50'),
(77, 94, '2024-11-13 14:54:50'),
(78, 96, '2024-11-13 14:54:50'),
(79, 93, '2024-11-13 14:55:05'),
(80, 96, '2024-11-13 14:55:58'),
(81, 106, '2024-11-13 14:55:58'),
(82, 96, '2024-11-13 14:56:42'),
(83, 93, '2024-11-13 15:02:48'),
(84, 94, '2024-11-13 15:02:48'),
(85, 95, '2024-11-13 15:02:48'),
(86, 96, '2024-11-13 15:02:48'),
(87, 97, '2024-11-13 15:02:48'),
(88, 93, '2024-11-13 15:02:58'),
(89, 96, '2024-11-13 15:03:10'),
(90, 106, '2024-11-13 15:04:10'),
(91, 93, '2024-11-13 15:05:41'),
(92, 94, '2024-11-13 15:05:41'),
(93, 96, '2024-11-13 15:05:41'),
(94, 93, '2024-11-13 15:05:51'),
(95, 93, '2024-11-13 15:07:49'),
(96, 94, '2024-11-13 15:07:49'),
(97, 96, '2024-11-13 15:07:49'),
(98, 93, '2024-11-13 15:12:14'),
(99, 94, '2024-11-13 15:12:14'),
(100, 96, '2024-11-13 15:12:14'),
(101, 93, '2024-11-13 15:12:18'),
(102, 93, '2024-11-13 15:13:36'),
(103, 94, '2024-11-13 15:13:36'),
(104, 96, '2024-11-13 15:13:36'),
(105, 93, '2024-11-13 15:14:44'),
(106, 94, '2024-11-13 15:14:44'),
(107, 96, '2024-11-13 15:14:44'),
(108, 93, '2024-11-13 15:15:13'),
(109, 94, '2024-11-13 15:15:13'),
(110, 96, '2024-11-13 15:15:13'),
(111, 93, '2024-11-13 15:15:20'),
(112, 94, '2024-11-13 15:15:20'),
(113, 96, '2024-11-13 15:15:20'),
(114, 93, '2024-11-13 15:15:28'),
(115, 94, '2024-11-13 15:15:28'),
(116, 96, '2024-11-13 15:15:28'),
(117, 93, '2024-11-13 15:16:20'),
(118, 94, '2024-11-13 15:16:20'),
(119, 96, '2024-11-13 15:16:20'),
(120, 93, '2024-11-13 15:16:21'),
(121, 94, '2024-11-13 15:16:21'),
(122, 96, '2024-11-13 15:16:21'),
(123, 96, '2024-11-13 15:17:42'),
(124, 93, '2024-11-13 15:19:38'),
(125, 94, '2024-11-13 15:19:38'),
(126, 96, '2024-11-13 15:19:38'),
(127, 97, '2024-11-13 15:21:03'),
(128, 96, '2024-11-13 15:21:21'),
(129, 94, '2024-11-13 15:21:30'),
(130, 93, '2024-11-13 15:23:10'),
(131, 94, '2024-11-13 15:23:10'),
(132, 96, '2024-11-13 15:23:10'),
(133, 93, '2024-11-13 15:24:03'),
(134, 94, '2024-11-13 15:24:03'),
(135, 96, '2024-11-13 15:24:03'),
(136, 93, '2024-11-13 15:24:03'),
(137, 94, '2024-11-13 15:24:03'),
(138, 96, '2024-11-13 15:24:03'),
(139, 93, '2024-11-13 15:24:07'),
(140, 94, '2024-11-13 15:24:07'),
(141, 96, '2024-11-13 15:24:07'),
(142, 93, '2024-11-13 15:24:18'),
(143, 94, '2024-11-13 15:24:18'),
(144, 96, '2024-11-13 15:24:18'),
(145, 93, '2024-11-13 15:24:22'),
(146, 94, '2024-11-13 15:24:22'),
(147, 96, '2024-11-13 15:24:22'),
(148, 93, '2024-11-13 15:24:51'),
(149, 94, '2024-11-13 15:24:51'),
(150, 96, '2024-11-13 15:24:51'),
(151, 93, '2024-11-13 15:24:56'),
(152, 94, '2024-11-13 15:24:56'),
(153, 96, '2024-11-13 15:24:56'),
(154, 93, '2024-11-13 15:24:57'),
(155, 94, '2024-11-13 15:24:57'),
(156, 96, '2024-11-13 15:24:57'),
(157, 93, '2024-11-13 15:24:57'),
(158, 94, '2024-11-13 15:24:57'),
(159, 96, '2024-11-13 15:24:57'),
(160, 93, '2024-11-13 15:25:01'),
(161, 94, '2024-11-13 15:25:01'),
(162, 96, '2024-11-13 15:25:01'),
(163, 93, '2024-11-13 15:27:31'),
(164, 94, '2024-11-13 15:27:31'),
(165, 96, '2024-11-13 15:27:31'),
(166, 93, '2024-11-13 15:28:16'),
(167, 94, '2024-11-13 15:28:16'),
(168, 96, '2024-11-13 15:28:16'),
(169, 93, '2024-11-13 15:28:26'),
(170, 94, '2024-11-13 15:28:26'),
(171, 96, '2024-11-13 15:28:26'),
(172, 93, '2024-11-13 15:28:42'),
(173, 94, '2024-11-13 15:28:42'),
(174, 96, '2024-11-13 15:28:42'),
(175, 93, '2024-11-13 15:28:58'),
(176, 94, '2024-11-13 15:28:58'),
(177, 96, '2024-11-13 15:28:58'),
(178, 96, '2024-11-13 15:29:43'),
(179, 96, '2024-11-13 15:30:44'),
(180, 96, '2024-11-13 15:30:49'),
(181, 96, '2024-11-13 15:32:59'),
(182, 93, '2024-11-13 15:33:52'),
(183, 94, '2024-11-13 15:33:52'),
(184, 96, '2024-11-13 15:33:52'),
(185, 93, '2024-11-13 15:38:23'),
(186, 94, '2024-11-13 15:38:23'),
(187, 96, '2024-11-13 15:38:23'),
(188, 93, '2024-11-13 15:40:59'),
(189, 94, '2024-11-13 15:40:59'),
(190, 96, '2024-11-13 15:40:59'),
(191, 93, '2024-11-13 15:41:07'),
(192, 93, '2024-11-13 15:43:48'),
(193, 94, '2024-11-13 15:43:48'),
(194, 96, '2024-11-13 15:43:48'),
(195, 96, '2024-11-13 15:49:27'),
(196, 96, '2024-11-13 15:49:42');

-- --------------------------------------------------------

--
-- Table structure for table `citation_list`
--

CREATE TABLE `citation_list` (
  `id` int(11) NOT NULL,
  `citing_paper_id` int(11) DEFAULT NULL,
  `cited_paper_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `citation_relationships`
--

CREATE TABLE `citation_relationships` (
  `citing_paper_id` int(11) NOT NULL,
  `cited_paper_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `curriculum_list`
--

CREATE TABLE `curriculum_list` (
  `id` int(30) NOT NULL,
  `department_id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curriculum_list`
--

INSERT INTO `curriculum_list` (`id`, `department_id`, `name`, `description`, `status`, `date_created`, `date_updated`) VALUES
(1, 5, 'BSIS', 'Bachelor of Science in Information Systems', 1, '2021-12-07 10:10:20', '2021-12-07 10:12:20'),
(2, 5, 'BSIT', 'Bachelor of Science in Information Technology', 1, '2021-12-07 10:10:56', NULL),
(3, 2, 'BEEd', 'Bachelor of Elementary Education', 1, '2021-12-07 10:12:50', NULL),
(4, 2, 'BSEd', 'Bachelor of Secondary Education', 1, '2021-12-07 10:13:10', NULL),
(5, 2, 'BSNEd', 'Bachelor in Special Needs Education', 1, '2021-12-07 10:14:05', NULL),
(6, 6, 'BSCE', 'Bachelor of Science in Civil Engineering', 1, '2021-12-07 10:14:26', NULL),
(7, 6, 'BS Computer Engineering', 'Bachelor of Science in Computer Engineering', 1, '2021-12-07 10:15:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `department_list`
--

CREATE TABLE `department_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_list`
--

INSERT INTO `department_list` (`id`, `name`, `description`, `status`, `date_created`, `date_updated`) VALUES
(1, 'College of Industrial Technology', 'Develop world-class industrial workers and middle-level managers equipped with scientific knowledge, technological skills, and ethical work values to achieve a desirable quality of life.', 1, '2021-12-07 09:28:16', '2021-12-07 09:36:07'),
(2, 'College of Education', 'Implement Teacher Education Programs for the elementary and secondary levels and endeavor to achieve quality and excellence, relevance and responsiveness, equity and access, and efficiency and effectiveness in instruction, research, extension, and production.', 1, '2021-12-07 09:28:33', '2021-12-07 09:46:57'),
(3, 'College of Arts and Sciences', 'Develop and implement programs in Liberal Arts and Sciences to achieve academic excellence and competencies geared towards the total development of the learners in their specialized fields.', 1, '2021-12-07 09:34:11', NULL),
(4, 'College of Business Management and Accountancy', 'College of Business Management and Accountancy', 1, '2021-12-07 09:34:55', NULL),
(5, 'College of Computer Studies', 'Develop creative innovators with the confidence and courage to seize and transform opportunities for the benefit of the society.', 1, '2021-12-07 09:35:19', '2021-12-07 09:36:35'),
(6, 'College of Engineering', 'To develop scientific and technical knowledge anchored on sustainable fisheries productivity and promote linkages and networking in the implementation of fisheries programs and projects.', 1, '2021-12-07 09:37:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lda_topics`
--

CREATE TABLE `lda_topics` (
  `id` int(11) NOT NULL,
  `paper_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `topic_name` varchar(255) DEFAULT NULL,
  `topic_keywords` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lda_topics`
--

INSERT INTO `lda_topics` (`id`, `paper_id`, `topic_id`, `topic_name`, `topic_keywords`) VALUES
(156, 93, 2, 'Topic 3', 'green, health, space, study, mental, psychological, distress'),
(157, 94, 0, 'Topic 1', 'health, mental, al, et, j, green, https, doi'),
(158, 95, 3, 'Topic 4', 'system, data, research, information, nutrition, researchers, study, clinic, healthcare'),
(159, 96, 0, 'Topic 1', 'health, mental, al, et, j, green, https, doi'),
(160, 96, 3, 'Topic 4', 'system, data, research, information, nutrition, researchers, study, clinic, healthcare'),
(161, 97, 3, 'Topic 4', 'system, data, research, information, nutrition, researchers, study, clinic, healthcare');

-- --------------------------------------------------------

--
-- Table structure for table `recent_student_mappings`
--

CREATE TABLE `recent_student_mappings` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `mapping_id` int(11) DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recent_student_mappings`
--

INSERT INTO `recent_student_mappings` (`id`, `student_id`, `mapping_id`, `viewed_at`) VALUES
(47, 31, 94, '2024-11-13 15:20:57'),
(55, 4, 95, '2024-11-13 14:58:38'),
(64, 31, 93, '2024-11-13 15:32:38'),
(82, 31, 97, '2024-11-13 15:33:17'),
(87, 31, 95, '2024-11-13 15:32:42');

-- --------------------------------------------------------

--
-- Table structure for table `student_list`
--

CREATE TABLE `student_list` (
  `id` int(30) NOT NULL,
  `firstname` text NOT NULL,
  `middlename` text NOT NULL,
  `lastname` text NOT NULL,
  `department_id` int(30) NOT NULL,
  `curriculum_id` int(30) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `gender` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `avatar` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_list`
--

INSERT INTO `student_list` (`id`, `firstname`, `middlename`, `lastname`, `department_id`, `curriculum_id`, `email`, `password`, `gender`, `status`, `avatar`, `date_created`, `date_updated`, `reset_token`, `reset_token_expiration`) VALUES
(1, 'John', 'D', 'Smith', 5, 1, 'jsmith@sample.com', '1254737c076cf867dc53d60a0364f38e', 'Male', 1, 'uploads/student-1.png?v=1639202693', '2021-12-11 12:50:03', '2021-12-11 14:04:53', NULL, NULL),
(3, 'Claire', 'C', 'Blake', 5, 1, 'cblake@sample.com', '4744ddea876b11dcb1d169fadf494418', 'Female', 1, 'uploads/student-3.png?v=1639377518', '2021-12-13 10:42:51', '2021-12-13 14:38:38', NULL, NULL),
(4, 'Sean', 'Andrei', 'Forlanda', 5, 2, 'seanforlanda@gmail.com', 'feed516f8ad036737189efce269d5936', 'Male', 1, 'uploads/avatar-4.png?v=1730444602', '2024-08-16 14:33:25', '2024-11-02 09:14:05', '', '0000-00-00 00:00:00'),
(16, 'andrei', '', 'forlanda', 5, 1, 'seanforlanda0@gmail.com', '3fab8b0071504634dc121d72df523834', 'Male', 1, '', '2024-08-19 12:42:18', '2024-08-19 12:43:07', NULL, NULL),
(24, 'eyyyyy', '', 'andrei', 5, 2, 'jpia.main@gmail.com', 'c694952fc76a5790db81be9700fdef9b', 'Male', 1, '', '2024-08-19 13:00:30', '2024-08-22 13:17:17', NULL, NULL),
(25, 'Sean', '', 'Drei', 2, 4, 'seanforlanda1@gmail.com', '3fab8b0071504634dc121d72df523834', 'Male', 1, '', '2024-09-03 14:43:10', '2024-09-03 14:44:11', NULL, NULL),
(31, 'ashlee', '', 'boy', 5, 1, 'ashleeboygaming@gmail.com', '3fab8b0071504634dc121d72df523834', 'Male', 1, 'uploads/avatar-31.png?v=1730728247', '2024-11-02 12:10:24', '2024-11-04 21:50:47', NULL, NULL),
(32, 'reujen', '', 'gonzalez', 5, 2, 'reujen@gmail.com', '9e6ec52a31832bb9e7d05c1565cb549c', '', 0, '', '2024-11-05 01:27:56', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'PUPSRC LitTrack'),
(6, 'short_name', 'LitTrack'),
(11, 'logo', 'uploads/LitTrack.png'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover-1638840281.png'),
(15, 'content', 'Array'),
(16, 'email', 'info@university101.com'),
(17, 'contact', '09854698789 / 78945632'),
(18, 'from_time', '11:00'),
(19, 'to_time', '21:30'),
(20, 'address', 'Under the Tree, Here Street, There City, Anywhere 1014');

-- --------------------------------------------------------

--
-- Table structure for table `topic_relationships`
--

CREATE TABLE `topic_relationships` (
  `id` int(11) NOT NULL,
  `citing_paper_id` int(11) NOT NULL,
  `cited_paper_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `topic_relationships`
--

INSERT INTO `topic_relationships` (`id`, `citing_paper_id`, `cited_paper_id`, `created_at`) VALUES
(145, 94, 96, '2024-11-02 14:01:03'),
(146, 95, 97, '2024-11-02 14:01:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 1 COMMENT '0=not verified, 1 = verified',
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `status`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', NULL, 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/student-1.png?v=1639202560', NULL, 1, 1, '2021-01-20 14:02:37', '2021-12-11 14:02:40'),
(2, 'Claire', NULL, 'Blake', 'cblake', '4744ddea876b11dcb1d169fadf494418', 'uploads/avatar-2.png?v=1639377482', NULL, 2, 1, '2021-12-13 14:38:02', '2021-12-13 14:38:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive_citation`
--
ALTER TABLE `archive_citation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `archive_downloads`
--
ALTER TABLE `archive_downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `archive_id` (`archive_id`);

--
-- Indexes for table `archive_list`
--
ALTER TABLE `archive_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doi` (`doi`),
  ADD KEY `curriculum_id` (`curriculum_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `archive_reads`
--
ALTER TABLE `archive_reads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `archive_reads_ibfk_1` (`archive_id`);

--
-- Indexes for table `citation_list`
--
ALTER TABLE `citation_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `citing_paper_id` (`citing_paper_id`),
  ADD KEY `cited_paper_id` (`cited_paper_id`);

--
-- Indexes for table `citation_relationships`
--
ALTER TABLE `citation_relationships`
  ADD PRIMARY KEY (`citing_paper_id`,`cited_paper_id`),
  ADD KEY `cited_paper_id` (`cited_paper_id`);

--
-- Indexes for table `curriculum_list`
--
ALTER TABLE `curriculum_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `department_list`
--
ALTER TABLE `department_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `research_id` (`research_id`);

--
-- Indexes for table `lda_topics`
--
ALTER TABLE `lda_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paper_id` (`paper_id`);

--
-- Indexes for table `recent_student_mappings`
--
ALTER TABLE `recent_student_mappings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`mapping_id`);

--
-- Indexes for table `student_list`
--
ALTER TABLE `student_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING HASH,
  ADD KEY `department_id` (`department_id`),
  ADD KEY `curriculum_id` (`curriculum_id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topic_relationships`
--
ALTER TABLE `topic_relationships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `citing_paper_id` (`citing_paper_id`),
  ADD KEY `cited_paper_id` (`cited_paper_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archive_citation`
--
ALTER TABLE `archive_citation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `archive_downloads`
--
ALTER TABLE `archive_downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `archive_list`
--
ALTER TABLE `archive_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `archive_reads`
--
ALTER TABLE `archive_reads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `citation_list`
--
ALTER TABLE `citation_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `curriculum_list`
--
ALTER TABLE `curriculum_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `department_list`
--
ALTER TABLE `department_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `lda_topics`
--
ALTER TABLE `lda_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `recent_student_mappings`
--
ALTER TABLE `recent_student_mappings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `topic_relationships`
--
ALTER TABLE `topic_relationships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `archive_downloads`
--
ALTER TABLE `archive_downloads`
  ADD CONSTRAINT `archive_downloads_ibfk_1` FOREIGN KEY (`archive_id`) REFERENCES `archive_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `archive_list`
--
ALTER TABLE `archive_list`
  ADD CONSTRAINT `archive_list_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_list` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `archive_reads`
--
ALTER TABLE `archive_reads`
  ADD CONSTRAINT `archive_reads_ibfk_1` FOREIGN KEY (`archive_id`) REFERENCES `archive_list` (`id`);

--
-- Constraints for table `citation_list`
--
ALTER TABLE `citation_list`
  ADD CONSTRAINT `citation_list_ibfk_1` FOREIGN KEY (`citing_paper_id`) REFERENCES `archive_list` (`id`),
  ADD CONSTRAINT `citation_list_ibfk_2` FOREIGN KEY (`cited_paper_id`) REFERENCES `archive_list` (`id`);

--
-- Constraints for table `citation_relationships`
--
ALTER TABLE `citation_relationships`
  ADD CONSTRAINT `citation_relationships_ibfk_1` FOREIGN KEY (`citing_paper_id`) REFERENCES `archive_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `citation_relationships_ibfk_2` FOREIGN KEY (`cited_paper_id`) REFERENCES `archive_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `curriculum_list`
--
ALTER TABLE `curriculum_list`
  ADD CONSTRAINT `curriculum_list_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`research_id`) REFERENCES `archive_list` (`id`);

--
-- Constraints for table `lda_topics`
--
ALTER TABLE `lda_topics`
  ADD CONSTRAINT `lda_topics_ibfk_1` FOREIGN KEY (`paper_id`) REFERENCES `archive_list` (`id`);

--
-- Constraints for table `recent_student_mappings`
--
ALTER TABLE `recent_student_mappings`
  ADD CONSTRAINT `recent_student_mappings_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_list` (`id`);

--
-- Constraints for table `student_list`
--
ALTER TABLE `student_list`
  ADD CONSTRAINT `student_list_ibfk_1` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculum_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_list_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `topic_relationships`
--
ALTER TABLE `topic_relationships`
  ADD CONSTRAINT `topic_relationships_ibfk_1` FOREIGN KEY (`citing_paper_id`) REFERENCES `archive_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `topic_relationships_ibfk_2` FOREIGN KEY (`cited_paper_id`) REFERENCES `archive_list` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
