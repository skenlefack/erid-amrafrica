-- Migration v4: Multi-category support + "Veille stratégique" category
-- Run: mysql -u root -p erid_amrafrica < database/migration_v4_multi_categories.sql

-- Table pivot for many-to-many article <-> categories
CREATE TABLE IF NOT EXISTS article_categories (
    article_id  INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (article_id, category_id),
    CONSTRAINT fk_ac_article  FOREIGN KEY (article_id)  REFERENCES articles(id) ON DELETE CASCADE,
    CONSTRAINT fk_ac_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create "Veille stratégique" category
INSERT INTO categories (slug, name_fr, name_en, accent_color, sort_order)
VALUES ('veille_strategique', 'Veille stratégique', 'Strategic Intelligence', '#8E44AD', 0);
