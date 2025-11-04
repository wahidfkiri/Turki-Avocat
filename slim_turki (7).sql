-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 16 oct. 2025 à 18:31
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `slim_turki`
--

-- --------------------------------------------------------

--
-- Structure de la table `agendas`
--

CREATE TABLE `agendas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_debut` date NOT NULL,
  `heure_debut` time DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `heure_fin` time DEFAULT NULL,
  `all_day` tinyint(1) NOT NULL DEFAULT 0,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `dossier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `intervenant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `utilisateur_id` bigint(20) UNSIGNED DEFAULT NULL,
  `categorie` enum('rdv','audience','delai','tache','autre') NOT NULL DEFAULT 'rdv',
  `couleur` varchar(20) NOT NULL DEFAULT '#3c8dbc',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agendas`
--

INSERT INTO `agendas` (`id`, `titre`, `description`, `date_debut`, `heure_debut`, `date_fin`, `heure_fin`, `all_day`, `file_path`, `file_name`, `dossier_id`, `intervenant_id`, `utilisateur_id`, `categorie`, `couleur`, `created_at`, `updated_at`) VALUES
(2, 'titre', 'zededfezf', '2025-09-28', NULL, '2025-09-29', NULL, 1, NULL, NULL, NULL, NULL, 1, 'tache', '#3c8dbc', '2025-09-28 18:54:07', '2025-09-28 18:54:07'),
(3, 'titre 1', 'egrgh', '2025-09-28', NULL, '2025-09-29', NULL, 1, NULL, NULL, NULL, NULL, 1, 'audience', '#3c8dbc', '2025-09-28 19:02:04', '2025-09-28 19:04:45'),
(9, 'hhhhhh', 'efefefzfezrfe\"fe\'gf', '2025-10-10', NULL, '2025-10-11', NULL, 0, NULL, NULL, 10, NULL, 5, 'audience', '#3c8dbc', '2025-10-10 14:06:06', '2025-10-10 14:06:28'),
(10, 'Event Task 1111', 'dkvnekjsbvhjbv esibvesbgv', '2025-10-08', NULL, '2025-10-09', NULL, 1, 'agenda_files/rMmf288iuLn6yzi0ALZzItfHRSr2DA1pE7rg75kw.docx', 'new (1) (1).docx', 10, NULL, 1, 'tache', '#dd4b39', '2025-10-12 18:24:21', '2025-10-12 18:38:18'),
(11, 'djvgyuldgv', 'l,nvdlubgvf', '2025-10-02', NULL, '2025-10-03', NULL, 1, NULL, NULL, 10, NULL, 1, 'rdv', '#3c8dbc', '2025-10-12 18:28:13', '2025-10-12 18:28:13'),
(12, 'edv,ebjvjb', NULL, '2025-10-09', NULL, '2025-10-10', NULL, 0, 'agenda_files/D3y3BFyXQ33TiSekVopJG7nPMmRZu3cu9Ec175rk.docx', 'new (1) (1).docx', NULL, NULL, 5, 'rdv', '#3c8dbc', '2025-10-12 18:35:22', '2025-10-12 18:35:22'),
(13, 'senknbvehjb', 'rkmnverkjbf', '2025-10-04', NULL, '2025-10-05', NULL, 1, NULL, NULL, NULL, NULL, NULL, 'rdv', '#3c8dbc', '2025-10-15 11:13:54', '2025-10-15 11:13:54');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `created_at`, `updated_at`) VALUES
(1, 'Consultation juridique', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(2, 'Rédaction d\'actes', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(3, 'Représentation en justice', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(4, 'Négociation', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(5, 'Recherche juridique', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(6, 'Réunion client', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(7, 'Audience', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(8, 'Déplacement', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(9, 'Formation', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(10, 'Administratif', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(11, 'Correspondance', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(12, 'Téléphone', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(13, 'Expertise', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(14, 'Médiation', '2025-09-28 19:11:55', '2025-09-28 19:11:55'),
(15, 'Arbitrage', '2025-09-28 19:11:55', '2025-09-28 19:11:55');

-- --------------------------------------------------------

--
-- Structure de la table `domaines`
--

CREATE TABLE `domaines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `domaines`
--

INSERT INTO `domaines` (`id`, `nom`, `created_at`, `updated_at`) VALUES
(1, 'Droit Civil', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(2, 'Droit Commercial', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(3, 'Droit Pénal', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(4, 'Droit Social', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(5, 'Droit Administratif', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(6, 'Droit Fiscal', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(7, 'Droit Immobilier', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(8, 'Droit de la Famille', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(9, 'Droit des Successions', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(10, 'Droit des Contrats', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(11, 'Droit des Sociétés', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(12, 'Droit de la Consommation', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(13, 'Droit Bancaire et Financier', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(14, 'Droit de la Propriété Intellectuelle', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(15, 'Droit International', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(16, 'Droit Maritime', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(17, 'Droit de l\'Environnement', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(18, 'Droit des Assurances', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(19, 'Droit des Technologies de l\'Information', '2025-09-27 08:40:29', '2025-09-27 08:40:29'),
(20, 'Droit Médical', '2025-09-27 08:40:29', '2025-09-27 08:40:29');

-- --------------------------------------------------------

--
-- Structure de la table `dossiers`
--

CREATE TABLE `dossiers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero_dossier` varchar(20) NOT NULL,
  `nom_dossier` varchar(255) NOT NULL,
  `objet` text DEFAULT NULL,
  `date_entree` timestamp NOT NULL DEFAULT current_timestamp(),
  `domaine_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sous_domaine_id` bigint(20) UNSIGNED DEFAULT NULL,
  `conseil` tinyint(1) NOT NULL DEFAULT 0,
  `contentieux` tinyint(1) NOT NULL DEFAULT 0,
  `numero_role` varchar(50) DEFAULT NULL,
  `chambre` enum('civil','commercial','social','pénal') DEFAULT NULL,
  `numero_chambre` varchar(50) DEFAULT NULL,
  `numero_parquet` varchar(50) DEFAULT NULL,
  `numero_instruction` varchar(50) DEFAULT NULL,
  `numero_plainte` varchar(50) DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `date_archive` date DEFAULT NULL,
  `boite_archive` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dossiers`
--

INSERT INTO `dossiers` (`id`, `numero_dossier`, `nom_dossier`, `objet`, `date_entree`, `domaine_id`, `sous_domaine_id`, `conseil`, `contentieux`, `numero_role`, `chambre`, `numero_chambre`, `numero_parquet`, `numero_instruction`, `numero_plainte`, `archive`, `note`, `date_archive`, `boite_archive`, `created_at`, `updated_at`) VALUES
(10, 'DOS-2025-005', 'Dossier 05', 'Test', '2025-10-08 22:00:00', 1, 3, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-09 10:34:17', '2025-10-09 10:34:17'),
(11, 'DOS-2025-007', 'Dossier 07', NULL, '2025-10-08 22:00:00', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2025-10-09 11:15:13', '2025-10-09 11:25:38'),
(19, 'djlscbkjsbce', 'skevnerskjbv', NULL, '2025-10-08 22:00:00', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-09 11:49:26', '2025-10-13 09:55:05'),
(21, 'DOS-2025-008', 'dsvdvdvbfr', NULL, '2025-10-08 22:00:00', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-09 17:03:20', '2025-10-09 17:03:20'),
(22, 'DOS-2025-009', 'kdvnhrkjdvnhj', NULL, '2025-10-08 22:00:00', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-09 17:05:43', '2025-10-09 17:05:43'),
(23, 'DOS-2025-0015', 'lsevjrsdbv', NULL, '2025-10-08 22:00:00', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-09 17:14:35', '2025-10-13 09:48:51'),
(26, 'DOS-2025-0011', 'Dossier 2025-11', NULL, '2025-10-12 22:00:00', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-13 10:21:00', '2025-10-13 10:21:00'),
(27, 'djlscbkjsbcef', 'Dossier 07', NULL, '2025-10-12 22:00:00', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-13 13:14:30', '2025-10-13 13:14:30'),
(28, 'DOS-2025-0070', 'Dossier 070', NULL, '2025-10-12 22:00:00', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-13 13:15:53', '2025-10-13 13:15:53'),
(29, 'DOS-2025-00150', 'Dossier 050', NULL, '2025-10-12 22:00:00', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-13 13:18:24', '2025-10-13 13:18:24'),
(30, 'DOS-2025-00755', 'Dossier 755', 'sdvdrv', '2025-10-13 22:00:00', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-14 11:36:21', '2025-10-14 11:36:21'),
(33, 'DOS-2025-00700', 'ihghgb', NULL, '2025-10-13 22:00:00', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-14 11:39:32', '2025-10-14 11:39:32'),
(35, 'DOS-2025-001500', '120520', 'esvcesvc', '2025-10-13 22:00:00', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-14 11:43:07', '2025-10-14 11:43:07'),
(36, 'DOS-2025-0015555', '2025-15555', 'sdcenbhde', '2025-10-13 22:00:00', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-14 12:49:17', '2025-10-14 12:49:17'),
(41, 'Dossier-123', 'Dossier-123', NULL, '2025-10-13 22:00:00', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-14 13:31:46', '2025-10-14 13:31:46');

-- --------------------------------------------------------

--
-- Structure de la table `dossier_dossier`
--

CREATE TABLE `dossier_dossier` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `dossier_lie_id` bigint(20) UNSIGNED NOT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dossier_dossier`
--

INSERT INTO `dossier_dossier` (`id`, `dossier_id`, `dossier_lie_id`, `relation`, `created_at`, `updated_at`) VALUES
(8, 35, 26, 'sdecvdsrvrdv', '2025-10-14 12:46:24', '2025-10-14 12:47:50'),
(10, 36, 26, 'esdcecfdes', '2025-10-14 12:49:18', '2025-10-14 12:49:18'),
(11, 36, 23, 'dscedsc', '2025-10-14 12:49:18', '2025-10-14 12:49:18'),
(16, 41, 26, 'escfrecf', '2025-10-14 13:31:46', '2025-10-14 13:31:46');

-- --------------------------------------------------------

--
-- Structure de la table `dossier_intervenant`
--

CREATE TABLE `dossier_intervenant` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `intervenant_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dossier_intervenant`
--

INSERT INTO `dossier_intervenant` (`id`, `dossier_id`, `intervenant_id`, `role`, `created_at`, `updated_at`) VALUES
(56, 10, 23, 'client', '2025-10-09 10:34:17', '2025-10-09 10:34:17'),
(61, 11, 23, 'client', '2025-10-09 11:25:38', '2025-10-09 11:25:38'),
(62, 21, 22, 'client', '2025-10-09 17:03:20', '2025-10-09 17:03:20'),
(63, 22, 22, 'client', '2025-10-09 17:05:43', '2025-10-09 17:05:43'),
(104, 19, 22, 'client', '2025-10-13 09:55:05', '2025-10-13 09:55:05'),
(106, 19, 27, 'client', '2025-10-13 09:55:05', '2025-10-13 09:55:05'),
(154, 27, 27, 'client', '2025-10-13 13:14:31', '2025-10-13 13:14:31'),
(155, 27, 23, 'ezferfg', '2025-10-13 13:14:31', '2025-10-13 13:14:31'),
(156, 27, 35, 'egeggbgr', '2025-10-13 13:14:31', '2025-10-13 13:14:31'),
(157, 28, 22, 'client', '2025-10-13 13:15:53', '2025-10-13 13:15:53'),
(158, 28, 27, 'Client 1', '2025-10-13 13:15:53', '2025-10-13 13:15:53'),
(159, 28, 34, 'Cnt 2', '2025-10-13 13:15:53', '2025-10-13 13:15:53'),
(160, 29, 27, 'client', '2025-10-13 13:18:24', '2025-10-13 13:18:24'),
(161, 29, 22, 'client', '2025-10-13 13:18:24', '2025-10-13 13:18:24'),
(162, 29, 23, 'Client 2', '2025-10-13 13:18:24', '2025-10-13 13:18:24'),
(166, 35, 35, 'dscdsvc', '2025-10-14 12:47:49', '2025-10-14 12:47:49'),
(167, 35, 23, 'escedcv', '2025-10-14 12:47:49', '2025-10-14 12:47:49'),
(168, 36, 22, 'client', '2025-10-14 12:49:18', '2025-10-14 12:49:18'),
(169, 36, 27, 'dvdfvdf', '2025-10-14 12:49:18', '2025-10-14 12:49:18'),
(170, 36, 35, 'sdcedsc', '2025-10-14 12:49:18', '2025-10-14 12:49:18'),
(179, 41, 23, 'client', '2025-10-14 13:31:46', '2025-10-14 13:31:46'),
(180, 41, 27, 'sdecdce', '2025-10-14 13:31:46', '2025-10-14 13:31:46'),
(186, 23, 22, 'client', '2025-10-14 14:26:19', '2025-10-14 14:26:18'),
(187, 23, 27, 'Cnt 2', '2025-10-14 14:26:19', '2025-10-14 14:26:18'),
(188, 23, 35, 'esdvrvg', '2025-10-14 14:26:19', '2025-10-14 14:26:18');

-- --------------------------------------------------------

--
-- Structure de la table `dossier_user`
--

CREATE TABLE `dossier_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ordre` int(11) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dossier_user`
--

INSERT INTO `dossier_user` (`id`, `dossier_id`, `user_id`, `ordre`, `role`, `created_at`, `updated_at`) VALUES
(32, 10, 5, 3, 'avocat', '2025-10-09 10:34:17', '2025-10-09 10:34:17'),
(33, 10, 6, 3, 'avocat', '2025-10-09 10:34:17', '2025-10-09 10:34:17'),
(38, 11, 5, 3, 'avocat', '2025-10-09 11:25:38', '2025-10-09 11:25:38'),
(42, 21, 5, 3, 'avocat', '2025-10-09 17:03:20', '2025-10-09 17:03:20'),
(43, 22, 5, 3, 'avocat', '2025-10-09 17:05:44', '2025-10-09 17:05:44'),
(44, 23, 5, 1, 'avocat', '2025-10-09 17:14:35', '2025-10-14 14:26:19'),
(50, 26, 5, 3, 'avocat', '2025-10-13 10:21:01', '2025-10-13 10:21:01'),
(51, 27, 5, 3, 'avocat', '2025-10-13 13:14:31', '2025-10-13 13:14:31'),
(52, 28, 5, 3, 'avocat', '2025-10-13 13:15:53', '2025-10-13 13:15:53'),
(53, 29, 5, 3, 'avocat', '2025-10-13 13:18:24', '2025-10-13 13:18:24'),
(54, 30, 5, 3, 'avocat', '2025-10-14 11:36:22', '2025-10-14 11:36:22'),
(55, 33, 5, 3, 'avocat', '2025-10-14 11:39:32', '2025-10-14 11:39:32'),
(56, 35, 5, 3, 'avocat', '2025-10-14 11:43:07', '2025-10-14 11:43:07'),
(57, 35, 6, 3, 'avocat', '2025-10-14 12:45:47', '2025-10-14 12:45:47'),
(58, 35, 1, 3, 'avocat', '2025-10-14 12:45:47', '2025-10-14 12:45:47'),
(59, 36, 5, 3, 'avocat', '2025-10-14 12:49:18', '2025-10-14 12:49:18'),
(64, 41, 5, 3, 'avocat', '2025-10-14 13:31:46', '2025-10-14 13:31:46'),
(65, 41, 1, 1, 'efcdezfcsdc', '2025-10-14 13:31:46', '2025-10-14 13:31:46'),
(66, 23, 6, 1, 'fghfd', '2025-10-14 14:26:19', '2025-10-14 14:26:19');

-- --------------------------------------------------------

--
-- Structure de la table `email_settings`
--

CREATE TABLE `email_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT 465,
  `smtp_encryption` varchar(255) DEFAULT 'ssl',
  `smtp_username` varchar(255) DEFAULT NULL,
  `smtp_password` text DEFAULT NULL,
  `imap_host` varchar(255) DEFAULT NULL,
  `imap_port` int(11) DEFAULT 993,
  `imap_encryption` varchar(255) DEFAULT 'ssl',
  `imap_username` varchar(255) DEFAULT NULL,
  `imap_password` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `email_settings`
--

INSERT INTO `email_settings` (`id`, `smtp_host`, `smtp_port`, `smtp_encryption`, `smtp_username`, `smtp_password`, `imap_host`, `imap_port`, `imap_encryption`, `imap_username`, `imap_password`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'mail.peakmind-solutions.com', 465, '', 'wahid.fkiri@peakmind-solutions.com', 'PeakMindSolutions@2025$$', 'mail.peakmind-solutions.com', 993, NULL, 'wahid.fkiri@peakmind-solutions.com', 'PeakMindSolutions@2025$$', 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type_piece` enum('facture','note_frais','note_provision','avoir') NOT NULL DEFAULT 'facture',
  `numero` varchar(100) NOT NULL,
  `date_emission` date NOT NULL,
  `montant_ht` decimal(12,2) NOT NULL DEFAULT 0.00,
  `montant_tva` decimal(12,2) NOT NULL DEFAULT 0.00,
  `montant` decimal(12,2) NOT NULL DEFAULT 0.00,
  `statut` enum('payé','non_payé') NOT NULL DEFAULT 'non_payé',
  `commentaires` text DEFAULT NULL,
  `piece_jointe` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `factures`
--

INSERT INTO `factures` (`id`, `dossier_id`, `client_id`, `type_piece`, `numero`, `date_emission`, `montant_ht`, `montant_tva`, `montant`, `statut`, `commentaires`, `piece_jointe`, `file_name`, `created_at`, `updated_at`) VALUES
(5, 10, 22, 'facture', 'FACT-2025-0001', '2025-10-10', '1250.00', '125.00', '1375.00', 'payé', NULL, '1760096699_facture-FAC2025-0002 (2).pdf', NULL, '2025-10-10 09:45:00', '2025-10-10 09:45:00'),
(6, NULL, NULL, 'facture', 'FACT-2025-0006', '2025-10-10', '0.00', '0.00', '0.00', 'non_payé', NULL, NULL, NULL, '2025-10-10 13:57:26', '2025-10-10 13:57:26'),
(7, 10, 23, 'facture', 'FACT-2025-0007', '2025-10-10', '1250.00', '125.00', '1375.00', 'payé', NULL, NULL, NULL, '2025-10-10 16:16:10', '2025-10-10 16:16:10'),
(8, 10, 23, 'facture', 'FACT-2025-0008', '2025-10-11', '1250.00', '200.00', '1450.00', 'payé', NULL, '1760180615_example.docx', 'example.docx', '2025-10-11 09:03:36', '2025-10-11 09:03:36'),
(9, 10, 23, 'facture', 'FACT-2025-0009', '2025-10-11', '5000.00', '500.00', '5500.00', 'non_payé', NULL, '1760180743_new (1).docx', 'new (1).docx', '2025-10-11 09:05:43', '2025-10-11 09:05:43');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fichiers`
--

CREATE TABLE `fichiers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_module` enum('intervenant','facture','agenda','tache','timesheet') NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `nom_fichier` varchar(255) NOT NULL,
  `chemin_fichier` varchar(500) NOT NULL,
  `type_mime` varchar(100) NOT NULL,
  `taille` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `fichiers`
--

INSERT INTO `fichiers` (`id`, `type_module`, `module_id`, `nom_fichier`, `chemin_fichier`, `type_mime`, `taille`, `description`, `date_upload`, `created_at`, `updated_at`) VALUES
(1, 'facture', 4, 'facture-F2025-001 (4).pdf', 'dossiers/4/1758975199_facture-F2025-001 (4).pdf', 'application/pdf', 32555, 'Fichier joint au dossier', '2025-09-27 12:13:19', '2025-09-27 10:13:19', '2025-09-27 10:13:19'),
(2, 'facture', 1, 'KING REC.pdf', 'dossiers/DOS-2025-001/1759407832_KING REC.pdf', 'application/pdf', 273758, 'Fichier joint au dossier', '2025-10-02 12:23:53', '2025-10-02 10:23:53', '2025-10-02 10:23:53'),
(3, 'facture', 7, 'ChatGPT Image 1 oct. 2025, 20_09_32.png', 'dossiers/DOS-2025-007/1759410180_ChatGPT Image 1 oct. 2025, 20_09_32.png', 'image/png', 1830916, 'Fichier joint au dossier', '2025-10-02 13:03:00', '2025-10-02 11:03:00', '2025-10-02 11:03:00'),
(4, 'facture', 8, 'PrivacyPolicy.pdf', 'dossiers/DOS-2025-001/1759578425_PrivacyPolicy.pdf', 'application/pdf', 638206, 'Fichier joint au dossier', '2025-10-04 11:47:06', '2025-10-04 09:47:06', '2025-10-04 09:47:06'),
(5, 'facture', 9, 'facture-FAC2025-0002 (1).pdf', 'dossiers/DOS-2025-002/1759581078_facture-FAC2025-0002 (1).pdf', 'application/pdf', 32455, 'Fichier joint au dossier', '2025-10-04 12:31:18', '2025-10-04 10:31:18', '2025-10-04 10:31:18');

-- --------------------------------------------------------

--
-- Structure de la table `forme_sociales`
--

CREATE TABLE `forme_sociales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `forme_sociales`
--

INSERT INTO `forme_sociales` (`id`, `nom`, `created_at`, `updated_at`) VALUES
(26, 'Entrepreneur individuel', NULL, NULL),
(27, 'Société à responsabilité limitée', NULL, NULL),
(28, 'Société Unipersonnelle à responsabilité limitée', NULL, NULL),
(29, 'Société Anonyme', NULL, NULL),
(30, 'Auto entrepreneur', NULL, NULL),
(31, 'Société mutuelle des services agricoles', NULL, NULL),
(32, 'Société en commandite simple', NULL, NULL),
(33, 'Société en nom collectif', NULL, NULL),
(34, 'Société civile', NULL, NULL),
(35, 'Société en commandite par actions', NULL, NULL),
(36, 'Société professionnelle', NULL, NULL),
(37, 'Société de personnes', NULL, NULL),
(38, 'Société civile professionnelle', NULL, NULL),
(39, 'Etablissement public', NULL, NULL),
(40, 'Etablissement stable', NULL, NULL),
(41, 'Groupement d\'intérêt économique à caractère commercial', NULL, NULL),
(42, 'Groupement d\'intérêt économique à caractère civil', NULL, NULL),
(43, 'Etablissement public à caractère non administratif', NULL, NULL),
(44, 'Succursale d\'une société étrangère (Bureau de liaison)', NULL, NULL),
(45, 'Centre d\'affaires d\'intérêt public économique', NULL, NULL),
(46, 'Coopérative', NULL, NULL),
(47, 'Société civile immobilière', NULL, NULL),
(48, 'Société de promotion immobilière', NULL, NULL),
(49, 'Société agricole', NULL, NULL),
(50, 'Société de mise en valeur et de développement agricole', NULL, NULL),
(51, 'Société contractuelle', NULL, NULL),
(52, 'Construction juridique', NULL, NULL),
(53, 'Groupement de médecine du travail', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `intervenants`
--

CREATE TABLE `intervenants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `identite_fr` varchar(255) NOT NULL,
  `identite_ar` varchar(255) DEFAULT NULL,
  `type` enum('personne physique','personne morale','entreprise individuelle') NOT NULL,
  `numero_cni` varchar(50) DEFAULT NULL,
  `rne` varchar(50) DEFAULT NULL,
  `numero_cnss` varchar(50) DEFAULT NULL,
  `forme_sociale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `categorie` enum('contact','client','avocat','notaire','huissier','juridiction','administrateur_judiciaire','mandataire_judiciaire','adversaire','expert_judiciaire') NOT NULL DEFAULT 'contact',
  `fonction` varchar(255) DEFAULT NULL,
  `adresse1` varchar(255) DEFAULT NULL,
  `adresse2` varchar(255) DEFAULT NULL,
  `portable1` varchar(30) DEFAULT NULL,
  `portable2` varchar(30) DEFAULT NULL,
  `mail1` varchar(255) DEFAULT NULL,
  `mail2` varchar(255) DEFAULT NULL,
  `site_internet` varchar(255) DEFAULT NULL,
  `fixe1` varchar(30) DEFAULT NULL,
  `fixe2` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `piece_jointe` varchar(255) DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `intervenants`
--

INSERT INTO `intervenants` (`id`, `identite_fr`, `identite_ar`, `type`, `numero_cni`, `rne`, `numero_cnss`, `forme_sociale_id`, `categorie`, `fonction`, `adresse1`, `adresse2`, `portable1`, `portable2`, `mail1`, `mail2`, `site_internet`, `fixe1`, `fixe2`, `fax`, `notes`, `piece_jointe`, `archive`, `created_at`, `updated_at`) VALUES
(22, 'Foulen', NULL, 'personne physique', NULL, NULL, NULL, NULL, 'client', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-07 11:11:35', '2025-10-07 11:11:35'),
(23, 'dkv,ksdnv', NULL, 'personne physique', NULL, NULL, NULL, NULL, 'client', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-07 11:19:38', '2025-10-07 11:19:38'),
(27, 'Houcine', NULL, 'personne physique', NULL, NULL, NULL, NULL, 'client', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-08 05:40:49', '2025-10-08 05:40:49'),
(34, 'sdviherubv', 'evbhbv', 'personne physique', NULL, NULL, NULL, NULL, 'contact', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-13 10:48:23', '2025-10-13 10:48:23'),
(35, 'sdvdnv', 'ksnvdenhb', 'personne physique', NULL, NULL, NULL, NULL, 'contact', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-13 10:49:05', '2025-10-13 10:49:05'),
(36, 'Wahid Fkiri 1111', 'orbvhyuj', 'personne physique', NULL, NULL, NULL, NULL, 'contact', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-15 12:36:09', '2025-10-15 12:36:09');

-- --------------------------------------------------------

--
-- Structure de la table `intervenant_files`
--

CREATE TABLE `intervenant_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `intervenant_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `intervenant_files`
--

INSERT INTO `intervenant_files` (`id`, `intervenant_id`, `file_path`, `file_name`, `description`, `created_at`, `updated_at`) VALUES
(9, 23, 'intervenants/23/1759843178_1759448484-facture-fact-2025-0001-2.pdf', NULL, NULL, '2025-10-07 11:19:39', '2025-10-07 11:19:39'),
(10, 27, 'intervenants/27/1759909249_new-1.docx', 'new (1).docx', NULL, '2025-10-08 05:40:49', '2025-10-08 05:40:49'),
(24, 36, 'intervenants/36/1760538970_facture-6.pdf', 'facture (6).pdf', NULL, '2025-10-15 12:36:11', '2025-10-15 12:36:11');

-- --------------------------------------------------------

--
-- Structure de la table `intervenant_intervenant`
--

CREATE TABLE `intervenant_intervenant` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `intervenant_id` bigint(20) UNSIGNED NOT NULL,
  `intervenant_lie_id` bigint(20) UNSIGNED NOT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_09_26_125319_create_permission_tables', 1),
(6, '2025_09_26_125731_create_forme_sociales_table', 1),
(7, '2025_09_26_130036_create_intervenants_table', 1),
(8, '2025_09_26_130045_create_domaines_table', 1),
(9, '2025_09_26_130053_create_sous_domaines_table', 1),
(10, '2025_09_26_130102_create_dossiers_table', 1),
(11, '2025_09_26_130113_create_fichiers_table', 1),
(12, '2025_09_26_130605_create_dossier_users_table', 1),
(13, '2025_09_26_130718_create_dossier_dossiers_table', 1),
(14, '2025_09_26_130754_create_dossier_intervenants_table', 1),
(15, '2025_09_26_130855_create_intervenant_intervenants_table', 1),
(16, '2025_09_26_130948_create_categories_table', 1),
(17, '2025_09_26_131022_create_types_table', 1),
(18, '2025_09_26_131052_create_time_sheets_table', 1),
(19, '2025_09_26_131116_create_agendas_table', 1),
(20, '2025_09_26_131140_create_tasks_table', 1),
(21, '2025_09_26_131159_create_factures_table', 1),
(22, '2025_09_29_101908_add_piece_jointe_to_factures_table', 2),
(23, '2025_09_30_120051_create_jobs_table', 3),
(24, '2025_10_02_202808_add_piece_jointe_to_intervenants_table', 4),
(27, '2025_10_02_233541_create_intervenant_files_table', 5),
(28, '2025_10_04_125344_add_note_to_dossiers_table', 6),
(29, '2025_10_04_130929_create_email_settings_table', 7),
(30, '2025_10_06_113234_create_notifications_table', 8),
(31, '2025_10_08_073157_add_file_name_to_intervenant_files_table', 9),
(32, '2025_10_10_123309_add_file_name_to_factures_table', 10),
(33, '2025_10_11_111844_add_file_columns_to_tasks_table', 11),
(34, '2025_10_12_201523_add_file_columns_to_agendas_table', 12),
(35, '2025_10_12_204419_add_file_columns_to_time_sheets_table', 13);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 5),
(1, 'App\\Models\\User', 6),
(2, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 5),
(4, 'App\\Models\\User', 5),
(5, 'App\\Models\\User', 1),
(5, 'App\\Models\\User', 5),
(5, 'App\\Models\\User', 6),
(6, 'App\\Models\\User', 5),
(6, 'App\\Models\\User', 6),
(7, 'App\\Models\\User', 5),
(8, 'App\\Models\\User', 5),
(9, 'App\\Models\\User', 1),
(9, 'App\\Models\\User', 5),
(10, 'App\\Models\\User', 5),
(11, 'App\\Models\\User', 5),
(12, 'App\\Models\\User', 5),
(13, 'App\\Models\\User', 1),
(13, 'App\\Models\\User', 5),
(14, 'App\\Models\\User', 5),
(15, 'App\\Models\\User', 5),
(16, 'App\\Models\\User', 5),
(17, 'App\\Models\\User', 1),
(17, 'App\\Models\\User', 5),
(17, 'App\\Models\\User', 6),
(18, 'App\\Models\\User', 5),
(18, 'App\\Models\\User', 6),
(19, 'App\\Models\\User', 5),
(19, 'App\\Models\\User', 6),
(20, 'App\\Models\\User', 5),
(20, 'App\\Models\\User', 6),
(21, 'App\\Models\\User', 1),
(21, 'App\\Models\\User', 5),
(21, 'App\\Models\\User', 6),
(22, 'App\\Models\\User', 5),
(22, 'App\\Models\\User', 6),
(23, 'App\\Models\\User', 5),
(23, 'App\\Models\\User', 6),
(24, 'App\\Models\\User', 5),
(24, 'App\\Models\\User', 6),
(25, 'App\\Models\\User', 1),
(25, 'App\\Models\\User', 5),
(25, 'App\\Models\\User', 6),
(26, 'App\\Models\\User', 5),
(26, 'App\\Models\\User', 6),
(27, 'App\\Models\\User', 5),
(27, 'App\\Models\\User', 6),
(28, 'App\\Models\\User', 5),
(29, 'App\\Models\\User', 5),
(30, 'App\\Models\\User', 5),
(31, 'App\\Models\\User', 5),
(32, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 6);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `is_read` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_intervenants', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(2, 'create_intervenants', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(3, 'edit_intervenants', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(4, 'delete_intervenants', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(5, 'view_dossiers', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(6, 'create_dossiers', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(7, 'edit_dossiers', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(8, 'delete_dossiers', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(9, 'view_users', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(10, 'create_users', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(11, 'edit_users', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(12, 'delete_users', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(13, 'view_factures', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(14, 'create_factures', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(15, 'edit_factures', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(16, 'delete_factures', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(17, 'view_agendas', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(18, 'create_agendas', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(19, 'edit_agendas', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(20, 'delete_agendas', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(21, 'view_tasks', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(22, 'create_tasks', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(23, 'edit_tasks', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(24, 'delete_tasks', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(25, 'view_timesheets', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(26, 'create_timesheets', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(27, 'edit_timesheets', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(28, 'delete_timesheets', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(29, 'access_admin_panel', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(30, 'manage_settings', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(31, 'view_reports', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(32, 'export_data', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(33, 'manage_backups', 'web', '2025-10-01 13:02:53', '2025-10-01 13:02:53');

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32'),
(2, 'utilisateur', 'web', '2025-09-26 19:00:32', '2025-09-26 19:00:32');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(17, 1),
(17, 2),
(18, 1),
(18, 2),
(19, 1),
(19, 2),
(20, 1),
(20, 2),
(21, 1),
(21, 2),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 1),
(24, 2),
(25, 1),
(25, 2),
(26, 1),
(26, 2),
(27, 1),
(27, 2),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(31, 2),
(32, 1),
(32, 2);

-- --------------------------------------------------------

--
-- Structure de la table `sous_domaines`
--

CREATE TABLE `sous_domaines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `domaine_id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sous_domaines`
--

INSERT INTO `sous_domaines` (`id`, `domaine_id`, `nom`, `created_at`, `updated_at`) VALUES
(1, 1, 'Responsabilité civile', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(2, 1, 'Droit des obligations', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(3, 1, 'Droit des biens', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(4, 1, 'Droit des personnes', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(5, 2, 'Droit commercial général', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(6, 2, 'Procédures collectives', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(7, 2, 'Concurrence et distribution', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(8, 2, 'Transport et logistique', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(9, 3, 'Droit pénal général', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(10, 3, 'Droit pénal des affaires', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(11, 3, 'Procédure pénale', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(12, 3, 'Droit pénal international', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(13, 4, 'Droit du travail', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(14, 4, 'Droit de la sécurité sociale', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(15, 4, 'Droit de la protection sociale', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(16, 4, 'Relations collectives du travail', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(17, 5, 'Contentieux administratif', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(18, 5, 'Droit des marchés publics', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(19, 5, 'Droit de l\'urbanisme', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(20, 5, 'Droit de la fonction publique', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(21, 6, 'Fiscalité des entreprises', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(22, 6, 'Fiscalité des particuliers', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(23, 6, 'Fiscalité internationale', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(24, 6, 'Contentieux fiscal', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(25, 7, 'Transaction immobilière', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(26, 7, 'Promotion immobilière', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(27, 7, 'Construction et urbanisme', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(28, 7, 'Copropriété', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(29, 8, 'Mariage et divorce', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(30, 8, 'Filiation et adoption', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(31, 8, 'Autorité parentale', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(32, 8, 'Obligation alimentaire', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(33, 9, 'Successions légales', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(34, 9, 'Testaments et donations', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(35, 9, 'Liquidation de succession', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(36, 9, 'Règlement des indivisions', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(37, 10, 'Contrats civils', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(38, 10, 'Contrats commerciaux', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(39, 10, 'Contrats internationaux', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(40, 10, 'Résolution des litiges contractuels', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(41, 11, 'Création de sociétés', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(42, 11, 'Fusion et acquisition', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(43, 11, 'Droit des groupes de sociétés', '2025-09-27 08:40:31', '2025-09-27 08:40:31'),
(44, 11, 'Conseil aux dirigeants', '2025-09-27 08:40:31', '2025-09-27 08:40:31');

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `priorite` enum('basse','normale','haute','urgente') NOT NULL DEFAULT 'normale',
  `statut` enum('a_faire','en_cours','terminee','en_retard') NOT NULL DEFAULT 'a_faire',
  `dossier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `intervenant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `utilisateur_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tasks`
--

INSERT INTO `tasks` (`id`, `titre`, `description`, `date_debut`, `date_fin`, `file_path`, `file_name`, `priorite`, `statut`, `dossier_id`, `intervenant_id`, `utilisateur_id`, `note`, `created_at`, `updated_at`) VALUES
(5, 'Event Task', 'sxzsxdzdx', '2025-10-10', '2025-10-11', NULL, NULL, 'basse', 'en_cours', 10, NULL, 1, NULL, '2025-10-10 12:57:58', '2025-10-10 14:32:27'),
(6, 'svelnekjsvbhj', NULL, '2025-10-10', '2025-10-11', NULL, NULL, 'basse', 'a_faire', 10, NULL, 1, NULL, '2025-10-10 16:38:44', '2025-10-10 16:38:44'),
(7, 'Event Task', 's;vncfkejsvnf', '2025-10-11', '2025-11-12', 'tasks/files/yIYXDxN9RqYaN0oziucxxTt3hzkN3d9ePq3xvkpO.docx', 'new (1).docx', 'basse', 'en_cours', 10, NULL, 1, 'sdklvnekjsv', '2025-10-11 09:39:22', '2025-10-11 09:43:46');

-- --------------------------------------------------------

--
-- Structure de la table `time_sheets`
--

CREATE TABLE `time_sheets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_timesheet` timestamp NOT NULL DEFAULT current_timestamp(),
  `utilisateur_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dossier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `categorie` bigint(20) UNSIGNED DEFAULT NULL,
  `type` bigint(20) UNSIGNED DEFAULT NULL,
  `quantite` decimal(12,2) NOT NULL DEFAULT 0.00,
  `prix` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `time_sheets`
--

INSERT INTO `time_sheets` (`id`, `date_timesheet`, `utilisateur_id`, `dossier_id`, `description`, `categorie`, `type`, `quantite`, `prix`, `total`, `file_path`, `file_name`, `created_at`, `updated_at`) VALUES
(6, '2025-10-09 22:00:00', 1, 10, 'sxcscc zxzsczs zsczsc', 9, 7, '5.00', '500.00', '2500.00', NULL, NULL, '2025-10-09 22:18:43', '2025-10-09 22:18:43'),
(7, '2025-10-10 22:00:00', 1, 10, 'iudsjfgvb qzufgezyf zugfyufe zufgytf\"ed', 1, 1, '4.00', '250.00', '1000.00', 'agenda_files/vqhDZrV3e2HbV5U9E1Dru7LvpZqGBZu5Mf8C6WO3.docx', 'new (1) (2).docx', '2025-10-11 09:15:01', '2025-10-12 18:54:40');

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

CREATE TABLE `types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types`
--

INSERT INTO `types` (`id`, `nom`, `created_at`, `updated_at`) VALUES
(1, 'Honoraires', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(2, 'Frais de déplacement', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(3, 'Frais de dossier', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(4, 'Frais d\'expertise', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(5, 'Frais de justice', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(6, 'Frais de photocopie', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(7, 'Frais de communication', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(8, 'Frais de timbre', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(9, 'Frais d\'huissier', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(10, 'Frais d\'enregistrement', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(11, 'Frais de publication', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(12, 'Frais de traduction', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(13, 'Frais de consultation', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(14, 'Frais de médiation', '2025-09-28 19:11:57', '2025-09-28 19:11:57'),
(15, 'Frais divers', '2025-09-28 19:11:57', '2025-09-28 19:11:57');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `fonction` enum('admin','avocat','secrétaire','clerc','stagiaire') NOT NULL DEFAULT 'avocat',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `fonction`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'wahid fkiri', 'wahidfkiri5@gmail.com', NULL, '$2y$10$pNvXHmEQDXLXC5nJkJYkb.U4cei2gdRZ2h1kJKA423Bkfgv4ac2bK', 'avocat', 1, NULL, '2025-09-26 12:17:08', '2025-10-13 10:17:09'),
(5, 'Foulen ben foulen', 'wahidfkiri777@gmail.com', NULL, '$2y$10$nmw27nwplFM4bmUufA0z/ey7ekFFUhQVL.CZYYJZ1Xktxskuwv78S', 'admin', 1, NULL, '2025-09-28 09:21:01', '2025-09-29 10:50:47'),
(6, 'Wahid F', 'wahidfkiri6@gmail.com', NULL, '$2y$10$owpnlLsf60SirwT/.FyjCuls8J9WVTTdIB3uEhDLnm9VS9JbO.poC', 'avocat', 1, NULL, '2025-10-02 11:05:22', '2025-10-07 10:26:11');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agendas`
--
ALTER TABLE `agendas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agendas_dossier_id_foreign` (`dossier_id`),
  ADD KEY `agendas_intervenant_id_foreign` (`intervenant_id`),
  ADD KEY `agendas_utilisateur_id_foreign` (`utilisateur_id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_nom_unique` (`nom`);

--
-- Index pour la table `domaines`
--
ALTER TABLE `domaines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `domaines_nom_unique` (`nom`);

--
-- Index pour la table `dossiers`
--
ALTER TABLE `dossiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dossiers_numero_dossier_unique` (`numero_dossier`),
  ADD KEY `dossiers_domaine_id_foreign` (`domaine_id`),
  ADD KEY `dossiers_sous_domaine_id_foreign` (`sous_domaine_id`);

--
-- Index pour la table `dossier_dossier`
--
ALTER TABLE `dossier_dossier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dossier_dossier_dossier_id_dossier_lie_id_unique` (`dossier_id`,`dossier_lie_id`),
  ADD KEY `dossier_dossier_dossier_lie_id_foreign` (`dossier_lie_id`);

--
-- Index pour la table `dossier_intervenant`
--
ALTER TABLE `dossier_intervenant`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dossier_intervenant_dossier_id_intervenant_id_unique` (`dossier_id`,`intervenant_id`),
  ADD KEY `dossier_intervenant_intervenant_id_foreign` (`intervenant_id`);

--
-- Index pour la table `dossier_user`
--
ALTER TABLE `dossier_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dossier_user_dossier_id_user_id_unique` (`dossier_id`,`user_id`),
  ADD KEY `dossier_user_user_id_foreign` (`user_id`);

--
-- Index pour la table `email_settings`
--
ALTER TABLE `email_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_settings_user_id_foreign` (`user_id`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factures_dossier_id_foreign` (`dossier_id`),
  ADD KEY `factures_client_id_foreign` (`client_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `fichiers`
--
ALTER TABLE `fichiers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `forme_sociales`
--
ALTER TABLE `forme_sociales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `forme_sociales_nom_unique` (`nom`);

--
-- Index pour la table `intervenants`
--
ALTER TABLE `intervenants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `intervenants_forme_sociale_id_foreign` (`forme_sociale_id`);

--
-- Index pour la table `intervenant_files`
--
ALTER TABLE `intervenant_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `intervenant_files_intervenant_id_foreign` (`intervenant_id`);

--
-- Index pour la table `intervenant_intervenant`
--
ALTER TABLE `intervenant_intervenant`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `intervenant_intervenant_intervenant_id_intervenant_lie_id_unique` (`intervenant_id`,`intervenant_lie_id`),
  ADD KEY `intervenant_intervenant_intervenant_lie_id_foreign` (`intervenant_lie_id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`),
  ADD KEY `notifications_task_id_foreign` (`task_id`);

--
-- Index pour la table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Index pour la table `sous_domaines`
--
ALTER TABLE `sous_domaines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sous_domaines_domaine_id_foreign` (`domaine_id`);

--
-- Index pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_dossier_id_foreign` (`dossier_id`),
  ADD KEY `tasks_intervenant_id_foreign` (`intervenant_id`),
  ADD KEY `tasks_utilisateur_id_foreign` (`utilisateur_id`);

--
-- Index pour la table `time_sheets`
--
ALTER TABLE `time_sheets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_sheets_utilisateur_id_foreign` (`utilisateur_id`),
  ADD KEY `time_sheets_dossier_id_foreign` (`dossier_id`),
  ADD KEY `time_sheets_categorie_foreign` (`categorie`),
  ADD KEY `time_sheets_type_foreign` (`type`);

--
-- Index pour la table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `types_nom_unique` (`nom`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agendas`
--
ALTER TABLE `agendas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `domaines`
--
ALTER TABLE `domaines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `dossiers`
--
ALTER TABLE `dossiers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `dossier_dossier`
--
ALTER TABLE `dossier_dossier`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `dossier_intervenant`
--
ALTER TABLE `dossier_intervenant`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT pour la table `dossier_user`
--
ALTER TABLE `dossier_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT pour la table `email_settings`
--
ALTER TABLE `email_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fichiers`
--
ALTER TABLE `fichiers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `forme_sociales`
--
ALTER TABLE `forme_sociales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `intervenants`
--
ALTER TABLE `intervenants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `intervenant_files`
--
ALTER TABLE `intervenant_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `intervenant_intervenant`
--
ALTER TABLE `intervenant_intervenant`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `sous_domaines`
--
ALTER TABLE `sous_domaines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT pour la table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `time_sheets`
--
ALTER TABLE `time_sheets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `types`
--
ALTER TABLE `types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `agendas`
--
ALTER TABLE `agendas`
  ADD CONSTRAINT `agendas_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `agendas_intervenant_id_foreign` FOREIGN KEY (`intervenant_id`) REFERENCES `intervenants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `agendas_utilisateur_id_foreign` FOREIGN KEY (`utilisateur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `dossiers`
--
ALTER TABLE `dossiers`
  ADD CONSTRAINT `dossiers_domaine_id_foreign` FOREIGN KEY (`domaine_id`) REFERENCES `domaines` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `dossiers_sous_domaine_id_foreign` FOREIGN KEY (`sous_domaine_id`) REFERENCES `sous_domaines` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `dossier_dossier`
--
ALTER TABLE `dossier_dossier`
  ADD CONSTRAINT `dossier_dossier_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dossier_dossier_dossier_lie_id_foreign` FOREIGN KEY (`dossier_lie_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dossier_intervenant`
--
ALTER TABLE `dossier_intervenant`
  ADD CONSTRAINT `dossier_intervenant_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dossier_intervenant_intervenant_id_foreign` FOREIGN KEY (`intervenant_id`) REFERENCES `intervenants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dossier_user`
--
ALTER TABLE `dossier_user`
  ADD CONSTRAINT `dossier_user_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dossier_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `email_settings`
--
ALTER TABLE `email_settings`
  ADD CONSTRAINT `email_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `intervenants` (`id`),
  ADD CONSTRAINT `factures_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `intervenants`
--
ALTER TABLE `intervenants`
  ADD CONSTRAINT `intervenants_forme_sociale_id_foreign` FOREIGN KEY (`forme_sociale_id`) REFERENCES `forme_sociales` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `intervenant_files`
--
ALTER TABLE `intervenant_files`
  ADD CONSTRAINT `intervenant_files_intervenant_id_foreign` FOREIGN KEY (`intervenant_id`) REFERENCES `intervenants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `intervenant_intervenant`
--
ALTER TABLE `intervenant_intervenant`
  ADD CONSTRAINT `intervenant_intervenant_intervenant_id_foreign` FOREIGN KEY (`intervenant_id`) REFERENCES `intervenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `intervenant_intervenant_intervenant_lie_id_foreign` FOREIGN KEY (`intervenant_lie_id`) REFERENCES `intervenants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `sous_domaines`
--
ALTER TABLE `sous_domaines`
  ADD CONSTRAINT `sous_domaines_domaine_id_foreign` FOREIGN KEY (`domaine_id`) REFERENCES `domaines` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_intervenant_id_foreign` FOREIGN KEY (`intervenant_id`) REFERENCES `intervenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_utilisateur_id_foreign` FOREIGN KEY (`utilisateur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `time_sheets`
--
ALTER TABLE `time_sheets`
  ADD CONSTRAINT `time_sheets_categorie_foreign` FOREIGN KEY (`categorie`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `time_sheets_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `time_sheets_type_foreign` FOREIGN KEY (`type`) REFERENCES `types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `time_sheets_utilisateur_id_foreign` FOREIGN KEY (`utilisateur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
