-- Migration v3: Create partners table + footer toggle setting
-- Run: mysql -u root -p erid_amrafrica < database/migration_v3_partners.sql

CREATE TABLE IF NOT EXISTS partners (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150)    NOT NULL,
    logo_path   VARCHAR(255)    NULL,
    website_url VARCHAR(500)    NULL,
    is_active   TINYINT(1)      NOT NULL DEFAULT 1,
    sort_order  SMALLINT        NOT NULL DEFAULT 0,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings (setting_key, value_fr, value_en)
VALUES ('footer_partners_enabled', '1', '1')
ON DUPLICATE KEY UPDATE setting_key = setting_key;

INSERT INTO partners (name, sort_order) VALUES
('Africa CDC', 1), ('WHO AFRO', 2), ('AU-IBAR', 3), ('FAO', 4),
('Wellcome', 5), ('Institut Pasteur', 6), ('KEMRI', 7), ('GARDP', 8);
