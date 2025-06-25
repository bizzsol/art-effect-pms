CREATE TABLE `requisition_attachments`
(
    `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `requisition_id` BIGINT UNSIGNED NOT NULL,
    `file_location`  VARCHAR(256) DEFAULT NULL,
    `created_by`     VARCHAR(255) DEFAULT NULL,
    `updated_by`     VARCHAR(255) DEFAULT NULL,
    `created_at`     TIMESTAMP NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT `requisition_attachments_requisition_id_foreign` FOREIGN KEY (`requisition_id`) REFERENCES `requisitions` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;