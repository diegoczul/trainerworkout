-- Table to store AI Trainer chat messages per user per exercise
CREATE TABLE IF NOT EXISTS `exercise_chats` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `sender` enum('user','ai') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exercise_chats_user_id_index` (`user_id`),
  KEY `exercise_chats_exercise_id_index` (`exercise_id`),
  KEY `exercise_chats_user_exercise_index` (`user_id`, `exercise_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
