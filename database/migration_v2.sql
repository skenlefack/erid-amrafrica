-- =====================================================================
--  ERID-AMRAfrica — Migration v2
--  Run on existing databases to apply: author role, SEO keywords,
--  user profile fields, updated categories & services.
-- =====================================================================
SET NAMES utf8mb4;

-- 1. Users: add 'author' role + profile fields
ALTER TABLE users
    MODIFY COLUMN role ENUM('superadmin','editor','author','consultant','analyst') NOT NULL DEFAULT 'editor',
    ADD COLUMN bio TEXT NULL AFTER is_active,
    ADD COLUMN photo VARCHAR(255) NULL AFTER bio,
    ADD COLUMN affiliation VARCHAR(200) NULL AFTER photo;

-- 2. Articles: add SEO keywords
ALTER TABLE articles
    ADD COLUMN seo_keywords VARCHAR(500) NULL AFTER cover_image;

-- 3. Update categories to 13-entry taxonomy
--    First, update existing category_id references to new IDs:
--    old spillover(1) → keep as spillover
--    old amr_residues(2) → map to amr(1)
--    old policy_trade(3) → map to health_economics
--    old tech_innovation(4) → keep or map
--    old interviews(5) → remove (articles reassigned)

-- Disable FK checks for category migration
SET FOREIGN_KEY_CHECKS = 0;

-- Clear and re-seed categories
DELETE FROM categories;
INSERT INTO categories (id, slug, name_fr, name_en, accent_color, sort_order) VALUES
(1,  'amr',                'RAM',                                  'AMR',                                  '#00897B', 1),
(2,  'infectious_diseases','Maladies Infectieuses',                 'Infectious Diseases',                  '#1565C0', 2),
(3,  'one_health',         'Une Seule Santé',                       'One Health',                           '#2E7D32', 3),
(4,  'zoonoses',           'Zoonoses',                              'Zoonoses',                             '#6A1B9A', 4),
(5,  'spillover',          'Spillover',                             'Spillover',                            '#0D47A1', 5),
(6,  'stewardship',        'Usage Raisonné & Gérance',              'Stewardship',                          '#FF6F00', 6),
(7,  'surveillance',       'Surveillance',                          'Surveillance',                         '#00695C', 7),
(8,  'diagnostics',        'Diagnostics',                           'Diagnostics',                          '#AD1457', 8),
(9,  'pharmacovigilance',  'Pharmacovigilance',                     'Pharmacovigilance',                    '#4527A0', 9),
(10, 'food_safety',        'Sécurité Sanitaire & Alimentaire',      'Food Safety & Health Security',        '#E65100', 10),
(11, 'health_economics',   'Économie & Politiques de Santé',        'Public Health Economics',              '#F9A825', 11),
(12, 'climate_change',     'Changement climatique',                 'Climate Change',                       '#43A047', 12),
(13, 'ntd',                'MTN',                                   'NTD',                                  '#E53935', 13);

-- Reassign orphaned articles (old category IDs that no longer exist)
UPDATE articles SET category_id = 1 WHERE category_id NOT IN (SELECT id FROM categories);

SET FOREIGN_KEY_CHECKS = 1;

-- 4. Update services with new content and pricing
UPDATE services SET
    title_fr = 'Analyse de données & Analyse spatiotemporelle',
    title_en = 'Data Analysis & Spatiotemporal Analysis',
    summary_fr = 'Modélisation épidémiologique avancée, analyse de tendances spatiotemporelles et conception de tableaux de bord interactifs intégrant la surveillance indicielle de routine et les diagnostics de laboratoire pour la RAM et les maladies émergentes. Livrables clés : analyse statistique de données, cartes de risques, modèles chronologiques d''épidémies.',
    summary_en = 'Advanced epidemiological data modeling, spatiotemporal trend analysis, and interactive dashboard design integrating routine Indicator-Based Surveillance and laboratory diagnostics for AMR and emerging diseases. Key Deliverables: Statistical data analysis, Risk maps, outbreak time-series models.',
    price_model = 'quote',
    price_from_usd = 1200.00
WHERE pillar = 'quant';

UPDATE services SET
    title_fr = 'Insights de terrain & Communication sanitaire',
    title_en = 'Field Insights & Health Communication',
    summary_fr = 'Analyse qualitative de santé, déterminants sociaux de la santé, Communication des Risques et Engagement Communautaire (RCCE) inclusive, et cadres de Surveillance à Base Communautaire (SBC) pour autonomiser le signalement local et promouvoir un changement comportemental durable autour de l''utilisation des antimicrobiens. Livrables clés : guides de surveillance communautaire, kits SBC, évaluations parties prenantes.',
    summary_en = 'Qualitative health analysis, social determinants of health, inclusive Risk Communication & Community Engagement (RCCE), and Community-Based Surveillance (CBS) frameworks to empower local reporting and promote sustainable behavioral change around antimicrobial usage. Key Deliverables: Community surveillance guidelines, social & behavioral change (SBC) toolkits, stakeholder survey assessments.',
    price_model = 'fixed',
    price_from_usd = 800.00
WHERE pillar = 'qual';

UPDATE services SET
    title_fr = 'Politique One Health & Modélisation stratégique',
    title_en = 'One Health Policy & Strategy Modeling',
    summary_fr = 'Nous appliquons la pensée systémique pour aider les organisations à cartographier les défis complexes de santé humaine-animale-environnementale. En modélisant les interactions multi-sectorielles, nous aidons les décideurs à identifier les leviers politiques critiques, résoudre les goulots d''étranglement opérationnels et concevoir des interventions de biosécurité durables. Livrables clés : cartographie systémique, analyse de scénarios, feuilles de route stratégiques One Health et évaluations d''impact.',
    summary_en = 'We apply systems thinking to help organizations map complex human-animal-environmental health challenges. By modeling multi-sectoral interactions, we help leaders identify critical policy leverage points, resolve operational bottlenecks, and design sustainable biosecurity interventions before major funding is deployed. Key Deliverables: System mapping, Scenario analysis, One Health strategic roadmaps & program impact evaluations.',
    price_model = 'retainer',
    price_from_usd = 1000.00
WHERE pillar = 'systems';
