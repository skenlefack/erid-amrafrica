-- =====================================================================
--  ERID-AMRAfrica — Schéma de base de données / Database schema
--  Moteur : MariaDB 10.6+  ·  Charset : utf8mb4  ·  Moteur table : InnoDB
--  Tout le contenu du site public est piloté depuis ces tables via la
--  console d'administration (CMS). All public content is admin-driven.
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- 1. UTILISATEURS & RBAC  (Role-Based Access Control)
-- ---------------------------------------------------------------------
CREATE TABLE users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name       VARCHAR(150)    NOT NULL,
    email           VARCHAR(190)    NOT NULL UNIQUE,
    password_hash   VARCHAR(255)    NOT NULL,
    role            ENUM('superadmin','editor','consultant','analyst') NOT NULL DEFAULT 'editor',
    locale          ENUM('fr','en') NOT NULL DEFAULT 'fr',
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    last_login_at   DATETIME        NULL,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 2. PARAMÈTRES GLOBAUX  (éditables depuis l'admin → "site updated from console")
-- ---------------------------------------------------------------------
CREATE TABLE settings (
    setting_key     VARCHAR(100)    PRIMARY KEY,
    value_fr        TEXT            NULL,
    value_en        TEXT            NULL,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 3. MODULE 1 — PUBLIC MEDIA ENGINE
--    News Hub (taxonomie à 4 canaux), Galerie média, Publications
-- ---------------------------------------------------------------------
CREATE TABLE categories (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug            VARCHAR(80)     NOT NULL UNIQUE,   -- spillover | amr_residues | policy_trade | tech_innovation
    name_fr         VARCHAR(120)    NOT NULL,
    name_en         VARCHAR(120)    NOT NULL,
    accent_color    VARCHAR(9)      NOT NULL DEFAULT '#0E7C7B',
    sort_order      SMALLINT        NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE articles (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id     INT UNSIGNED    NOT NULL,
    author_id       INT UNSIGNED    NULL,
    slug            VARCHAR(200)    NOT NULL UNIQUE,
    title_fr        VARCHAR(255)    NOT NULL,
    title_en        VARCHAR(255)    NOT NULL,
    excerpt_fr      TEXT            NULL,
    excerpt_en      TEXT            NULL,
    body_fr         MEDIUMTEXT      NULL,
    body_en         MEDIUMTEXT      NULL,
    cover_image     VARCHAR(255)    NULL,
    status          ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
    is_featured     TINYINT(1)      NOT NULL DEFAULT 0,
    published_at    DATETIME        NULL,
    views           INT UNSIGNED    NOT NULL DEFAULT 0,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_articles_cat    FOREIGN KEY (category_id) REFERENCES categories(id),
    CONSTRAINT fk_articles_author FOREIGN KEY (author_id)   REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_articles_status (status, published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE media_items (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type            ENUM('video','comic','podcast','image') NOT NULL,
    title_fr        VARCHAR(255)    NOT NULL,
    title_en        VARCHAR(255)    NOT NULL,
    description_fr  TEXT            NULL,
    description_en  TEXT            NULL,
    embed_url       VARCHAR(500)    NULL,            -- YouTube / HTML5 source
    thumbnail       VARCHAR(255)    NULL,
    status          ENUM('draft','published') NOT NULL DEFAULT 'published',
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE publications (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title_fr        VARCHAR(255)    NOT NULL,
    title_en        VARCHAR(255)    NOT NULL,
    pub_type        ENUM('peer_reviewed','whitepaper','field_blog','policy_brief') NOT NULL,
    authors         VARCHAR(500)    NULL,
    abstract_fr     TEXT            NULL,
    abstract_en     TEXT            NULL,
    file_path       VARCHAR(255)    NULL,            -- PDF optimisé faible bande passante
    is_gated        TINYINT(1)      NOT NULL DEFAULT 0,  -- monétisation : contenu premium
    published_at    DATE            NULL,
    downloads       INT UNSIGNED    NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 4. RUMOUR MANAGEMENT SYSTEM  (Event-Based Surveillance intake)
-- ---------------------------------------------------------------------
CREATE TABLE rumours (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    source_channel  ENUM('web_form','whatsapp','x','facebook','linkedin','partner') NOT NULL DEFAULT 'web_form',
    is_anonymous    TINYINT(1)      NOT NULL DEFAULT 1,
    reporter_contact VARCHAR(190)   NULL,
    country         VARCHAR(80)     NULL,
    sector          ENUM('human','animal','environment','agriculture','pharma','unknown') NOT NULL DEFAULT 'unknown',
    raw_signal      TEXT            NOT NULL,
    -- Triage automatique (moteur de classification rule-based + NLP)
    triage_status   ENUM('new','triaged','escalated','dismissed') NOT NULL DEFAULT 'new',
    risk_score      TINYINT         NULL,            -- 0–100 calculé par le moteur
    nlp_keywords    VARCHAR(500)    NULL,
    assigned_to     INT UNSIGNED    NULL,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_rumours_user FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_rumours_triage (triage_status, risk_score)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 5. MODULE 2 — SERVICES PORTFOLIO  (3 piliers, éditables depuis l'admin)
-- ---------------------------------------------------------------------
CREATE TABLE services (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pillar          ENUM('quant','qual','systems') NOT NULL,
    routing_tag     VARCHAR(40)     NOT NULL,        -- Service_Quant | Service_Qual | Service_Systems
    title_fr        VARCHAR(200)    NOT NULL,
    title_en        VARCHAR(200)    NOT NULL,
    summary_fr      TEXT            NULL,
    summary_en      TEXT            NULL,
    -- Monétisation : packaging & tarification indicative pilotables depuis l'admin
    price_model     ENUM('quote','fixed','retainer','sta') NOT NULL DEFAULT 'quote',
    price_from_usd  DECIMAL(10,2)   NULL,
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    sort_order      SMALLINT        NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 6. CRM — LEADS / INTAKE  (Data Analytics + Expert Advisory portals)
--    Routage contextuel + déclenchement de l'e-mail de triage automatique
-- ---------------------------------------------------------------------
CREATE TABLE leads (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    intake_type     ENUM('Service_Quant','Service_Qual','Service_Systems','Data_Analytics','Advisory_Partnership') NOT NULL,
    lead_name       VARCHAR(150)    NOT NULL,
    organisation    VARCHAR(200)    NOT NULL,
    email           VARCHAR(190)    NOT NULL,
    phone           VARCHAR(60)     NULL,
    project_title   VARCHAR(255)    NULL,
    description     MEDIUMTEXT      NULL,
    extra_json      JSON            NULL,            -- champs spécifiques au pilier (DAP, secteurs, ToR...)
    uploaded_file   VARCHAR(255)    NULL,
    -- Pipeline commercial (monétisation)
    status          ENUM('new','reviewing','scoping','quoted','won','lost') NOT NULL DEFAULT 'new',
    est_value_usd   DECIMAL(12,2)   NULL,
    assigned_to     INT UNSIGNED    NULL,
    triage_sent_at  DATETIME        NULL,            -- horodatage e-mail auto 48h
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_leads_user FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_leads_status (status, intake_type),
    INDEX idx_leads_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 7. MONÉTISATION — Abonnés & produits de données premium
-- ---------------------------------------------------------------------
CREATE TABLE subscribers (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email           VARCHAR(190)    NOT NULL UNIQUE,
    full_name       VARCHAR(150)    NULL,
    organisation    VARCHAR(200)    NULL,
    tier            ENUM('free','intelligence','enterprise') NOT NULL DEFAULT 'free',
    locale          ENUM('fr','en') NOT NULL DEFAULT 'fr',
    confirmed       TINYINT(1)      NOT NULL DEFAULT 0,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 8. CONTENU CMS GÉNÉRIQUE  (pages statiques éditables : Vision, Mission...)
-- ---------------------------------------------------------------------
CREATE TABLE pages (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug            VARCHAR(120)    NOT NULL UNIQUE,
    title_fr        VARCHAR(200)    NOT NULL,
    title_en        VARCHAR(200)    NOT NULL,
    body_fr         MEDIUMTEXT      NULL,
    body_en         MEDIUMTEXT      NULL,
    status          ENUM('draft','published') NOT NULL DEFAULT 'published',
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 9. MODÈLES D'E-MAIL  (triage auto white-labellé, éditables depuis l'admin)
-- ---------------------------------------------------------------------
CREATE TABLE email_templates (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    template_key    VARCHAR(80)     NOT NULL UNIQUE, -- triage_quant | triage_systems | advisory_ack ...
    subject_fr      VARCHAR(255)    NOT NULL,
    subject_en      VARCHAR(255)    NOT NULL,
    body_fr         MEDIUMTEXT      NOT NULL,
    body_en         MEDIUMTEXT      NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 10. JOURNAL D'AUDIT IMMUABLE  (data governance : every read/update/export)
-- ---------------------------------------------------------------------
CREATE TABLE audit_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED    NULL,
    action          VARCHAR(60)     NOT NULL,        -- login, create, update, delete, export, read
    entity          VARCHAR(60)     NOT NULL,
    entity_id       VARCHAR(60)     NULL,
    ip_address      VARCHAR(45)     NULL,
    meta_json       JSON            NULL,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_audit_entity (entity, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
