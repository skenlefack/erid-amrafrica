-- Migration v3: Add 'trashed' status to articles
-- Run: mysql -u root -p erid_amrafrica < database/migration_v3_trash.sql

ALTER TABLE articles
    MODIFY COLUMN status ENUM('draft','published','archived','trashed') NOT NULL DEFAULT 'draft';
