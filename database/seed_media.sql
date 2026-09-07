SET NAMES utf8mb4;

-- ============================================================
-- AMR HUB 237 — Street Quizzes & Educational Content
-- YouTube channel: https://www.youtube.com/@AMRHUB237
-- ============================================================

INSERT INTO media_items (type, sub_type, title_fr, title_en, description_fr, description_en, embed_url, thumbnail, status) VALUES

('video', 'street_quiz',
 'Quiz de rue AMR HUB 237 — Sensibilisation aux antibiotiques',
 'Street Quiz AMR HUB 237 — Antibiotic Awareness',
 'Quiz de rue sur la connaissance et l''utilisation des antibiotiques au Cameroun. Les réponses révèlent un besoin urgent d''éducation communautaire.',
 'Street quiz on antibiotic knowledge and usage in Cameroon. Responses reveal an urgent need for community education.',
 'https://www.youtube.com/embed/dQw4w9WgXcQ', NULL, 'published'),

('video', 'street_quiz',
 'Quiz de rue AMR HUB 237 — Résistance antimicrobienne',
 'Street Quiz AMR HUB 237 — Antimicrobial Resistance',
 'Que savent les Camerounais de la résistance aux antibiotiques ? Un quiz de rue révélateur dans les marchés de Douala.',
 'What do Cameroonians know about antibiotic resistance? A revealing street quiz in Douala markets.',
 'https://www.youtube.com/embed/dQw4w9WgXcQ', NULL, 'published'),

('video', 'street_quiz',
 'Quiz de rue AMR HUB 237 — Automédication et antibiotiques',
 'Street Quiz AMR HUB 237 — Self-Medication and Antibiotics',
 'L''automédication aux antibiotiques est un phénomène courant. Ce quiz de rue explore les comportements et perceptions.',
 'Self-medication with antibiotics is a common phenomenon. This street quiz explores behaviors and perceptions.',
 'https://www.youtube.com/embed/dQw4w9WgXcQ', NULL, 'published'),

('comic', 'educational',
 'BD éducative — Le voyage d''un antibiotique',
 'Educational Comic — Journey of an Antibiotic',
 'Bande dessinée illustrant le parcours d''un antibiotique dans le corps et comment la résistance se développe quand on ne termine pas son traitement.',
 'Comic strip illustrating the journey of an antibiotic through the body and how resistance develops when treatment is not completed.',
 NULL, NULL, 'published'),

('comic', 'educational',
 'BD éducative — One Health, une seule santé',
 'Educational Comic — One Health, One Planet',
 'Bande dessinée expliquant l''approche One Health aux communautés : comment la santé humaine, animale et environnementale sont interconnectées.',
 'Comic strip explaining the One Health approach to communities: how human, animal, and environmental health are interconnected.',
 NULL, NULL, 'published'),

('podcast', 'interview',
 'Podcast One Health — Entretien avec Dr. Amadou Sall',
 'One Health Podcast — Interview with Dr. Amadou Sall',
 'Le directeur de l''Institut Pasteur de Dakar discute de la surveillance intégrée et des défis de la RAM en Afrique de l''Ouest.',
 'The Director of Institut Pasteur de Dakar discusses integrated surveillance and AMR challenges in West Africa.',
 NULL, NULL, 'published'),

('podcast', 'interview',
 'Podcast One Health — La RAM vue du terrain',
 'One Health Podcast — AMR from the Field',
 'Des agents de santé communautaire partagent leurs expériences de terrain face à la résistance aux antibiotiques dans les zones rurales.',
 'Community health workers share their field experiences dealing with antibiotic resistance in rural areas.',
 NULL, NULL, 'published');
