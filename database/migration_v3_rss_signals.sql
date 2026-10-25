-- Migration v3: RSS signal capture system
-- Run: mysql -u root -p erid_amrafrica < database/migration_v3_rss_signals.sql

-- Extend rumours table for RSS ingestion
ALTER TABLE rumours
    MODIFY COLUMN source_channel ENUM('web_form','whatsapp','x','facebook','linkedin','partner','rss') NOT NULL DEFAULT 'web_form';

ALTER TABLE rumours ADD COLUMN source_url VARCHAR(500) NULL AFTER media_file;
ALTER TABLE rumours ADD UNIQUE INDEX idx_rumours_source_url (source_url);

-- RSS feed sources
CREATE TABLE IF NOT EXISTS rss_sources (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(200)    NOT NULL,
    url             VARCHAR(500)    NOT NULL,
    source_type     ENUM('rss','atom','custom') NOT NULL DEFAULT 'rss',
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    poll_interval   SMALLINT        NOT NULL DEFAULT 360 COMMENT 'minutes between polls',
    last_polled_at  DATETIME        NULL,
    last_error      VARCHAR(500)    NULL,
    items_ingested  INT UNSIGNED    NOT NULL DEFAULT 0,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Keyword matching rules
CREATE TABLE IF NOT EXISTS keyword_rules (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(200)    NOT NULL,
    pattern         VARCHAR(500)    NOT NULL COMMENT 'comma-separated keywords',
    language        ENUM('en','fr','both') NOT NULL DEFAULT 'both',
    category        VARCHAR(80)     NULL COMMENT 'maps to rumours.sector or free tag',
    risk_weight     TINYINT         NOT NULL DEFAULT 50 COMMENT 'base risk score when matched 0-100',
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed RSS sources
INSERT INTO rss_sources (name, url, source_type) VALUES
('WHO Disease Outbreak News', 'https://www.who.int/feeds/entity/don/en/rss.xml', 'rss'),
('Africa CDC News', 'https://africacdc.org/feed/', 'rss'),
('ProMED-mail', 'https://promedmail.org/feed/', 'rss'),
('CIDRAP News', 'https://www.cidrap.umn.edu/news/rss.xml', 'rss'),
('ReAct Group', 'https://www.reactgroup.org/feed/', 'rss'),
('ASLM Portal', 'https://aslm.org/feed/', 'rss'),
('FAO Animal Health', 'https://www.fao.org/animal-health/news-events/rss-feeds/en/', 'rss'),
('ECDC News', 'https://www.ecdc.europa.eu/en/rss.xml', 'rss'),
('WHONET / Microbiology', 'https://whonet.org/rss.xml', 'rss');

-- Seed keyword rules (bilingual AMR/One Health matrices)
INSERT INTO keyword_rules (name, pattern, language, category, risk_weight) VALUES
('Entity & Network Filter', 'WHONET,OHLEP,ReAct,ICARS,Fleming Fund,ASLM,GARDP,One Health Foundation,WANEL,AFROHUN', 'both', 'unknown', 40),
('AMR core EN', 'antimicrobial resistance,AMR,antibiotic resistance,drug resistant,MDR-TB,colistin resistance,ESBL,AST data,treatment failure', 'en', 'human', 60),
('AMR core FR', 'résistance aux antimicrobiens,antibiorésistance,RAM,bactérie multirésistante,antibiogramme,échec thérapeutique', 'fr', 'human', 60),
('Outbreak EN', 'outbreak,epidemic,cluster,surge,emerging disease', 'en', 'human', 75),
('Outbreak FR', 'épidémie,flambée,foyer,émergence,maladie émergente', 'fr', 'human', 75),
('Zoonotic EN', 'zoonotic,spillover,avian influenza,H5N1,Lassa fever,Marburg,Ebola,Mpox,Rift Valley fever,anthrax,rabies,transboundary animal disease,TAD', 'en', 'animal', 65),
('Zoonotic FR', 'zoonose,zoonotique,grippe aviaire,fièvre de Lassa,Marburg,Ebola,variole simienne,fièvre de la vallée du Rift', 'fr', 'animal', 65),
('Surveillance EN', 'surveillance,sentinel,early warning,genomic sequencing,GLASS', 'en', 'human', 50),
('Surveillance FR', 'surveillance,sentinelle,alerte précoce,séquençage génomique', 'fr', 'human', 50),
('Agriculture EN', 'livestock,poultry,aquaculture,farm,veterinary,antimicrobial use in animals', 'en', 'agriculture', 55),
('Agriculture FR', 'bétail,volaille,aquaculture,élevage,vétérinaire,usage antimicrobien animal', 'fr', 'agriculture', 55),
('Africa geography', 'Africa,African,Sub-Saharan,West Africa,East Africa,Central Africa,Southern Africa,Sahel', 'both', 'unknown', 40),
('Pharma EN', 'pharmaceutical,drug quality,substandard,falsified,counterfeit medicine', 'en', 'pharma', 70);
