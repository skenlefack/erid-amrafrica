-- =====================================================================
--  ERID-AMRAfrica — Données de démonstration / Seed data
--  Lancer APRÈS schema.sql.  Run AFTER schema.sql.
-- =====================================================================
SET NAMES utf8mb4;

-- Compte super-admin de démonstration
-- Mot de passe / password : "ChangeMe!2026"  (hash bcrypt — à régénérer en prod)
INSERT INTO users (full_name, email, password_hash, role, locale) VALUES
('Johngwe Mac Juliette N.', 'admin@erid-amrafrica.org',
 '$2y$10$M7v3qkBpxBl6m8v7tauvm.2rtnZ6fjXv9akIokEwewFx0FSdXA1TC', 'superadmin', 'fr');

-- Taxonomie articles (13 catégories bilingues — administrables depuis la console)
INSERT INTO categories (slug, name_fr, name_en, accent_color, sort_order) VALUES
('amr',                'RAM',                                  'AMR',                                  '#00897B', 1),
('infectious_diseases','Maladies Infectieuses',                 'Infectious Diseases',                  '#1565C0', 2),
('one_health',         'Une Seule Santé',                       'One Health',                           '#2E7D32', 3),
('zoonoses',           'Zoonoses',                              'Zoonoses',                             '#6A1B9A', 4),
('spillover',          'Spillover',                             'Spillover',                            '#0D47A1', 5),
('stewardship',        'Usage Raisonné & Gérance',              'Stewardship',                          '#FF6F00', 6),
('surveillance',       'Surveillance',                          'Surveillance',                         '#00695C', 7),
('diagnostics',        'Diagnostics',                           'Diagnostics',                          '#AD1457', 8),
('pharmacovigilance',  'Pharmacovigilance',                     'Pharmacovigilance',                    '#4527A0', 9),
('food_safety',        'Sécurité Sanitaire & Alimentaire',      'Food Safety & Health Security',        '#E65100', 10),
('health_economics',   'Économie & Politiques de Santé',        'Public Health Economics',              '#F9A825', 11),
('climate_change',     'Changement climatique',                 'Climate Change',                       '#43A047', 12),
('ntd',                'MTN',                                   'NTD',                                  '#E53935', 13);

-- Services — 3 piliers (avec pricing ajusté)
INSERT INTO services (pillar, routing_tag, title_fr, title_en, summary_fr, summary_en, price_model, price_from_usd, sort_order) VALUES
('quant','Service_Quant',
 'Analyse de données & Analyse spatiotemporelle',
 'Data Analysis & Spatiotemporal Analysis',
 'Modélisation épidémiologique avancée, analyse de tendances spatiotemporelles et conception de tableaux de bord interactifs intégrant la surveillance indicielle de routine et les diagnostics de laboratoire pour la RAM et les maladies émergentes. Livrables clés : analyse statistique de données, cartes de risques, modèles chronologiques d''épidémies.',
 'Advanced epidemiological data modeling, spatiotemporal trend analysis, and interactive dashboard design integrating routine Indicator-Based Surveillance and laboratory diagnostics for AMR and emerging diseases. Key Deliverables: Statistical data analysis, Risk maps, outbreak time-series models.',
 'quote', 1200.00, 1),
('qual','Service_Qual',
 'Insights de terrain & Communication sanitaire',
 'Field Insights & Health Communication',
 'Analyse qualitative de santé, déterminants sociaux de la santé, Communication des Risques et Engagement Communautaire (RCCE) inclusive, et cadres de Surveillance à Base Communautaire (SBC) pour autonomiser le signalement local et promouvoir un changement comportemental durable autour de l''utilisation des antimicrobiens. Livrables clés : guides de surveillance communautaire, kits SBC, évaluations parties prenantes.',
 'Qualitative health analysis, social determinants of health, inclusive Risk Communication & Community Engagement (RCCE), and Community-Based Surveillance (CBS) frameworks to empower local reporting and promote sustainable behavioral change around antimicrobial usage. Key Deliverables: Community surveillance guidelines, social & behavioral change (SBC) toolkits, stakeholder survey assessments.',
 'fixed', 800.00, 2),
('systems','Service_Systems',
 'Politique One Health & Modélisation stratégique',
 'One Health Policy & Strategy Modeling',
 'Nous appliquons la pensée systémique pour aider les organisations à cartographier les défis complexes de santé humaine-animale-environnementale. En modélisant les interactions multi-sectorielles, nous aidons les décideurs à identifier les leviers politiques critiques, résoudre les goulots d''étranglement opérationnels et concevoir des interventions de biosécurité durables. Livrables clés : cartographie systémique, analyse de scénarios, feuilles de route stratégiques One Health et évaluations d''impact.',
 'We apply systems thinking to help organizations map complex human-animal-environmental health challenges. By modeling multi-sectoral interactions, we help leaders identify critical policy leverage points, resolve operational bottlenecks, and design sustainable biosecurity interventions before major funding is deployed. Key Deliverables: System mapping, Scenario analysis, One Health strategic roadmaps & program impact evaluations.',
 'retainer', 1000.00, 3);

-- Paramètres globaux du site (pilotés depuis l'admin)
INSERT INTO settings (setting_key, value_fr, value_en) VALUES
('site_name', 'ERID-AMRAfrica', 'ERID-AMRAfrica'),
('tagline',
 'Transformer la surveillance de terrain, les signaux communautaires et l''intelligence systémique en sécurité sanitaire continentale.',
 'Translating field surveillance, community signals, and systems intelligence into continental health security.'),
('contact_email', 'consulting@erid-amrafrica.org', 'consulting@erid-amrafrica.org'),
('hero_cta_fr', 'Demander une consultation', NULL),
('hero_cta_en', NULL, 'Request a consultation');

-- Modèle d'e-mail de triage automatique (white-labellé, du brief §8.2)
INSERT INTO email_templates (template_key, subject_fr, subject_en, body_fr, body_en) VALUES
('triage_default',
 'Accusé de réception — ERID-AMRAfrica',
 'Acknowledgement of receipt — ERID-AMRAfrica',
 'Bonjour {{lead_name}},\n\nNous vous remercions d''avoir sollicité une consultation auprès d''ERID-AMRAfrica. Notre Consultante Principale et son équipe technique ont bien reçu vos objectifs et paramètres de projet « {{project_title}} ».\n\nNous examinons votre calendrier et reviendrons vers vous sous 48 heures ouvrées afin de planifier une session de cadrage technique approfondie. Les jeux de données supplémentaires, codebooks de variables ou ToR élargis seront examinés lors de cet appel de cadrage initial.\n\nCordialement,\nL''équipe ERID-AMRAfrica',
 'Dear {{lead_name}},\n\nThank you for requesting a consultation with ERID-AMRAfrica. Our Principal Consultant and technical team have received your project objectives and parameters for "{{project_title}}".\n\nWe are reviewing your timeline and will reach out within 48 business hours to schedule a deep-dive technical scoping call. Supplementary datasets, variable codebooks, or expanded ToRs will be reviewed during this initial 48-hour technical scoping session.\n\nKind regards,\nThe ERID-AMRAfrica team');

-- Pages CMS de base
INSERT INTO pages (slug, title_fr, title_en, body_fr, body_en) VALUES
('vision-mission', 'Vision & Mission', 'Vision & Mission',
 'Un continent où chaque signal de maladie infectieuse émergente est capté, contextualisé et converti en action opportune sous un cadre africain unifié One Health.',
 'A continent where every emerging infectious-disease signal is captured, contextualised, and converted into timely action under a unified African One Health framework.');

-- Articles de démonstration
INSERT INTO articles (category_id, author_id, slug, title_fr, title_en, excerpt_fr, excerpt_en, status, is_featured, published_at) VALUES
(1, 1, 'amr-water-food-interface',
 'Surveillance basée sur les événements à l''interface eau-aliment-santé',
 'Event-Based Surveillance at the Water–Food–Health Interface',
 'Comment les signaux communautaires anticipent l''émergence de la résistance antimicrobienne.',
 'How community signals anticipate the emergence of antimicrobial resistance.',
 'published', 1, NOW()),
(5, 1, 'zoonotic-spillover-patterns',
 'Schémas de transmission zoonotique transfrontalière',
 'Cross-border Zoonotic Spillover Patterns',
 'Cartographie des vecteurs de transmission entre santé animale et humaine.',
 'Mapping transmission vectors between animal and human health.',
 'published', 0, NOW());
