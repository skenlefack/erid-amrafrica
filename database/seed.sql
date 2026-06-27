-- =====================================================================
--  ERID-AMRAfrica — Données de démonstration / Seed data
--  Lancer APRÈS schema.sql.  Run AFTER schema.sql.
-- =====================================================================

-- Compte super-admin de démonstration
-- Mot de passe / password : "ChangeMe!2026"  (hash bcrypt — à régénérer en prod)
INSERT INTO users (full_name, email, password_hash, role, locale) VALUES
('Johngwe Mac Juliette N.', 'admin@erid-amrafrica.org',
 '$2y$10$M7v3qkBpxBl6m8v7tauvm.2rtnZ6fjXv9akIokEwewFx0FSdXA1TC', 'superadmin', 'fr');

-- Taxonomie News Hub (4 canaux du brief)
INSERT INTO categories (slug, name_fr, name_en, accent_color, sort_order) VALUES
('spillover',      'Interface Spillover',     'Spillover Interface',      '#1F3A5F', 1),
('amr_residues',   'RAM & Résidus',           'AMR & Residues',           '#0E7C7B', 2),
('policy_trade',   'Politique & Sécurité commerciale', 'Policy & Trade Security', '#C9A227', 3),
('tech_innovation','Tech & Innovation',       'Tech & Innovation',        '#5B8C5A', 4);

-- Services — 3 piliers (avec packaging monétisation)
INSERT INTO services (pillar, routing_tag, title_fr, title_en, summary_fr, summary_en, price_model, price_from_usd, sort_order) VALUES
('quant','Service_Quant',
 'Data Science quantitative & épidémiologie spatiale',
 'Quantitative Data Science & Spatial Epidemiology',
 'Architecture de données, modélisation épidémiologique sur mesure, cartographie SIG des indicateurs RAM/ERID, tableaux de bord R-Shiny.',
 'Data architecture, bespoke epidemiological modelling, GIS mapping of AMR/ERID indicators, R-Shiny dashboards.',
 'quote', 8500.00, 1),
('qual','Service_Qual',
 'Analyse qualitative & intelligence comportementale',
 'Qualitative Analysis & Behavioural Health Intelligence',
 'Cadres de communication des risques socio-culturels, conception d''interventions comportementales, cartographie de réseaux sémantiques (NLP).',
 'Socio-cultural risk-communication frameworks, behavioural intervention design, NLP word-network mapping.',
 'quote', 6000.00, 2),
('systems','Service_Systems',
 'Pensée systémique & architecture de simulation',
 'Systems Thinking & Simulation Architecture',
 'Diagrammes de boucles causales interactifs, modélisation system-dynamics, analyse des points de levier politiques avant financement.',
 'Interactive causal loop diagrams, system-dynamics modelling, policy-leverage-point analysis before funding.',
 'retainer', 4500.00, 3);

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
 'Bonjour {{lead_name}},\n\nNous vous remercions d''avoir sollicité une consultation auprès d''ERID-AMRAfrica. Notre Consultante Principale et son équipe technique ont bien reçu vos objectifs et paramètres de projet « {{project_title}} ».\n\nNous examinons votre calendrier et reviendrons vers vous sous 48 heures ouvrées afin de planifier une session de cadrage approfondie.\n\nCordialement,\nL''équipe ERID-AMRAfrica',
 'Dear {{lead_name}},\n\nThank you for requesting a consultation with ERID-AMRAfrica. Our Principal Consultant and technical team have received your project objectives and parameters for "{{project_title}}".\n\nWe are reviewing your timeline and will reach out within 48 business hours to schedule a deep-dive scoping session.\n\nKind regards,\nThe ERID-AMRAfrica team');

-- Pages CMS de base
INSERT INTO pages (slug, title_fr, title_en, body_fr, body_en) VALUES
('vision-mission', 'Vision & Mission', 'Vision & Mission',
 'Un continent où chaque signal de maladie infectieuse émergente est capté, contextualisé et converti en action opportune sous un cadre africain unifié One Health.',
 'A continent where every emerging infectious-disease signal is captured, contextualised, and converted into timely action under a unified African One Health framework.');

-- Articles de démonstration
INSERT INTO articles (category_id, author_id, slug, title_fr, title_en, excerpt_fr, excerpt_en, status, is_featured, published_at) VALUES
(2, 1, 'amr-water-food-interface',
 'Surveillance basée sur les événements à l''interface eau-aliment-santé',
 'Event-Based Surveillance at the Water–Food–Health Interface',
 'Comment les signaux communautaires anticipent l''émergence de la résistance antimicrobienne.',
 'How community signals anticipate the emergence of antimicrobial resistance.',
 'published', 1, NOW()),
(1, 1, 'zoonotic-spillover-patterns',
 'Schémas de transmission zoonotique transfrontalière',
 'Cross-border Zoonotic Spillover Patterns',
 'Cartographie des vecteurs de transmission entre santé animale et humaine.',
 'Mapping transmission vectors between animal and human health.',
 'published', 0, NOW());
