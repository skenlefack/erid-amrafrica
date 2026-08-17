-- =====================================================================
-- ERID-AMRAfrica — Insertion des 10 articles du dossier ARTICLES
-- + Création du compte auteur Johngwe Mac Juliette (superadmin)
-- =====================================================================
SET NAMES utf8mb4;

-- 1. Créer le compte auteur (mot de passe: ERID-Admin!2026)
INSERT INTO users (full_name, email, password_hash, role, locale, is_active)
VALUES (
    'Johngwe Mac Juliette',
    'mac.johngwe@erid-amrafrica.org',
    '$2b$10$IxmnjCLLuYBqLnteSlkoGujnjvWEUqeGudQ9hCwdKMEqceqxQhcNO',
    'superadmin',
    'fr',
    1
);

SET @author_id = LAST_INSERT_ID();

-- Catégories existantes mapping:
-- AMR -> amr_residues (id=2)
-- One Health / Zoonoses -> spillover (id=1)
-- Infectious Diseases / NTDs -> spillover (id=1)

-- =====================================================================
-- Article First: How Emerging Resistance Patterns are Reshaping Outbreaks
-- =====================================================================
INSERT INTO articles (category_id, author_id, slug, title_fr, title_en, excerpt_fr, excerpt_en, body_fr, body_en, cover_image, status, is_featured, published_at, views) VALUES
(2, @author_id, 'how-emerging-resistance-patterns-reshaping-outbreaks',
'Comment les schémas de résistance émergents redéfinissent les épidémies et les protocoles de traitement',
'How Emerging Resistance Patterns are Reshaping Outbreaks and Treatment Protocols',
'Imaginez un monde où une égratignure de routine ou une césarienne standard devient un pari mortel parce que nos médicaments les plus puissants cessent de fonctionner.',
'Imagine a world where a routine scratch from a garden thorn or a standard C-section becomes a life-threatening gamble because our strongest frontline medicine simply stops working.',
'<p>Le paysage moderne des épidémies a radicalement changé. Pendant des décennies, les infections sévères chez les patients ou les épidémies dans le bétail étaient généralement gérées avec des antibiotiques de première ligne. Cette hypothèse s''effondre désormais face à ce que les experts appellent la pandémie silencieuse : la Résistance Antimicrobienne (RAM).</p>

<p>La RAM est passée d''une préoccupation à combustion lente à une crise mondiale aiguë. Selon l''OMS, 1 infection bactérienne sur 6 dans le monde est résistante aux antibiotiques standards, avec des taux de résistance augmentant de 5 à 15% annuellement pour les pathogènes clés.</p>

<p>Cette poussée transforme les soins de routine. Lors des récentes urgences de santé publique comme Ebola et le COVID-19, les superbactéries résistantes aux médicaments ont transformé des infections secondaires gérables en complications mortelles.</p>

<h3>Le fossé des protocoles de traitement</h3>

<p>Lorsque les médecins et vétérinaires de première ligne ne peuvent plus compter sur les protocoles de traitement empiriques, toute la norme de soins commence à s''effondrer. En Afrique, la faiblesse critique réside dans le délai entre le diagnostic et la livraison des résultats.</p>

<p><strong>En milieu clinique :</strong> Les médecins se tournent souvent vers des options de dernier recours comme les carbapénèmes ou la colistine face à des conditions courantes comme les infections urinaires causées par E. coli et K. pneumoniae. Dans les régions à forte charge, ces pathogènes montrent plus de 70% de résistance aux céphalosporines de troisième génération.</p>

<p><strong>En médecine vétérinaire :</strong> Les agriculteurs font face au même effondrement. Lorsque les traitements de routine échouent, des troupeaux entiers sont perdus, menaçant directement la sécurité alimentaire mondiale.</p>

<h3>Le vecteur One Health et les facteurs environnementaux</h3>

<p>Les microbes résistants ne restent pas confinés dans les murs des hôpitaux ou des fermes. Dans notre monde interconnecté, les mécanismes de résistance circulent continuellement à travers une boucle One Health impliquant les humains, les animaux et l''environnement.</p>

<p>Les ruissellements agricoles portant des antibiotiques résiduels se mélangent aux eaux usées urbaines, créant des terrains de reproduction où les bactéries environnementales échangent des gènes de résistance avec les pathogènes humains.</p>

<p>Le changement climatique accélère la réplication bactérienne et le transfert horizontal de gènes. Les événements météorologiques extrêmes détruisent davantage les infrastructures hydrauliques, forçant les communautés à s''appuyer sur des sources contaminées.</p>

<h3>One Health en action</h3>

<p>Résoudre la crise multisectorielle de la RAM nécessite de passer d''un reporting statique et retardé vers une surveillance en temps réel et des analyses spatiales. Les systèmes modernes intègrent le séquençage génomique avec la cartographie SIG, permettant aux responsables sanitaires de tracer le mouvement géographique des gènes résistants.</p>

<p>La RAM n''est pas une projection future — c''est une réalité actuelle réclamant plus de 1,27 million de vies annuellement. Combler le fossé nécessite une action unifiée à travers la médecine humaine, l''agriculture et la science environnementale.</p>',

'<p>The modern outbreak landscape has changed dramatically. For decades, severe infections in patients or outbreaks in livestock were generally managed with first-line antibiotics. That assumption is now collapsing and we are facing what experts call the silent pandemic: Antimicrobial Resistance (AMR).</p>

<p>AMR has shifted from a slow-burn concern to an acute global crisis. According to WHO, 1 in 6 bacterial infections worldwide are resistant to standard antibiotics, with resistance rates rising 5–15% annually across key pathogens.</p>

<p>This surge is transforming routine care. In recent public health emergencies such as Ebola and COVID-19, drug-resistant superbugs turned manageable secondary infections into fatal complications, undermining the very foundation of modern medicine.</p>

<h3>The treatment protocol gap</h3>

<p>When frontline doctors and veterinarians can no longer rely on empirical treatment protocols, the entire standard of care begins to crumble. In Africa, the critical weakness lies in the time gap between diagnosis and result delivery.</p>

<p><strong>Clinical settings:</strong> Physicians now often turn to last-resort options like carbapenems or colistin when faced with common conditions such as urinary tract infections or bloodstream sepsis caused by E. coli and K. pneumoniae. In high-burden regions, these pathogens show over 70% resistance to third-generation cephalosporins.</p>

<p><strong>Veterinary medicine:</strong> Farmers face the same breakdown. As routine treatments fail, herds are lost, directly threatening global food security and supply chains.</p>

<h3>The One Health vector & environmental drivers</h3>

<p>Resistant microbes do not stay confined inside hospital walls or farms. In our interconnected world, resistance mechanisms circulate continuously across a One Health loop involving humans, animals, and the environment.</p>

<p>Agricultural runoff carrying residual antibiotics mixes with urban wastewater, creating breeding grounds where environmental bacteria trade resistance genes with human pathogens.</p>

<p>Climate change accelerates bacterial replication and horizontal gene transfer. Extreme weather events further destroy water infrastructure, forcing communities to rely on contaminated sources that spread resistant strains far and wide.</p>

<h3>One Health in action</h3>

<p>Solving the multi-sector crisis of AMR requires moving beyond static, delayed reporting toward real-time surveillance and spatial analytics. Modern systems integrate genomic sequencing with GIS mapping, enabling health officials to trace the geographic movement of resistant genes across farms, rivers, and hospital wards.</p>

<p>AMR is not a future projection — it is a present-day reality claiming over 1.27 million lives annually. Closing the gap requires unified action across human medicine, agriculture, and environmental science.</p>',
'/uploads/articles/First-article_1.jpg', 'published', 1, NOW() - INTERVAL 1 DAY, 0);

-- =====================================================================
-- Article Second: How Bacteria Outsmart Our Medicines
-- =====================================================================
INSERT INTO articles (category_id, author_id, slug, title_fr, title_en, excerpt_fr, excerpt_en, body_fr, body_en, cover_image, status, is_featured, published_at, views) VALUES
(2, @author_id, 'how-bacteria-outsmart-our-medicines-cross-resistance-co-resistance',
'Comment les bactéries déjouent nos médicaments : résistance croisée vs co-résistance',
'How Bacteria Outsmart Our Medicines: Cross-Resistance vs. Co-Resistance',
'Les bactéries sont des machines de survie, développant des stratégies qui transforment les antibiotiques en armes émoussées.',
'Bacteria are survival machines, evolving tricks that turn antibiotics into blunt weapons. Two of the most dangerous strategies are cross-resistance and co-resistance.',
'<p>Lorsque vous prenez un antibiotique, vous lancez une attaque ciblée contre les bactéries. Mais les microbes sont des machines de survie. Au fil du temps, ils ont développé des stratégies pour résister à nos médicaments, créant le défi mondial de la résistance antimicrobienne (RAM).</p>

<p>Au cœur du problème, la résistance se présente sous deux formes : intrinsèque (boucliers naturels comme les parois cellulaires imperméables) et acquise (mutations ou absorption de gènes permettant la survie sous pression médicamenteuse).</p>

<h3>La résistance croisée</h3>

<p>La résistance croisée survient lorsqu''un seul mécanisme de défense protège les bactéries contre différentes classes d''antibiotiques agissant sur la même cible ou voie. Par exemple, une mutation dans le site de liaison ribosomal peut empêcher plusieurs antibiotiques qui perturbent tous la synthèse protéique (macrolides, lincosamides et streptogramines) de se fixer. En un seul ajustement génétique, de nombreuses classes de médicaments sont neutralisées.</p>

<h3>La co-résistance</h3>

<p>Ici, au lieu qu''une mutation bloque une famille de médicaments, les bactéries empilent plusieurs gènes de résistance distincts ensemble. Les plasmides (petites boucles d''ADN) portent souvent des groupes de gènes de résistance. Par conjugaison bactérienne, ces plasmides sont transférés d''une bactérie à une autre. Le receveur gagne instantanément une protection contre plusieurs antibiotiques non apparentés.</p>

<p>Comprendre si un pathogène repose sur la résistance croisée ou la co-résistance est un guide pratique pour l''action. La résistance croisée nous avertit que les cibles biologiques partagées sont vulnérables, tandis que la co-résistance révèle comment les réseaux d''échange de gènes accélèrent la survie multi-médicaments.</p>

<p>Protéger les antibiotiques signifie comprendre comment les bactéries ripostent et garder une longueur d''avance sur leur évolution.</p>',

'<p>When you take an antibiotic, you are launching a targeted strike against bacteria. But microbes are survival machines. Over time, they have evolved strategies to resist our drugs, creating the global health challenge of antimicrobial resistance (AMR).</p>

<p>At the core, resistance comes in two forms: intrinsic (natural shields like impermeable cell walls) and acquired (mutations or gene uptake that allow survival under drug pressure). Where things get especially dangerous is when resistance spills over into multiple drug classes.</p>

<h3>Cross-Resistance</h3>

<p>Cross-resistance occurs when a single defense mechanism protects bacteria against different antibiotic classes that act on the same target or pathway. For example, a mutation in the ribosomal binding site can prevent several antibiotics that all disrupt protein synthesis (macrolides, lincosamides, and streptogramins) from attaching. In just one genetic tweak, many drug classes become neutralized.</p>

<h3>Co-Resistance</h3>

<p>Here, instead of one mutation blocking a family of drugs, bacteria stack multiple distinct resistance genes together. Plasmids (small DNA loops) often carry clusters of resistance genes. Through bacterial conjugation, these plasmids are transferred from one bacterium to another. The recipient instantly gains protection against several unrelated antibiotics, turning into a new donor that can spread resistance further.</p>

<p>Understanding whether a pathogen relies on cross-resistance or co-resistance is a practical guide for action. Cross-resistance warns us that shared biological targets are vulnerable, while co-resistance reveals how gene exchange networks accelerate multi-drug survival. Recognizing these patterns helps us anticipate resistance before it spreads.</p>

<p>Protecting antibiotics means understanding how bacteria fight back and staying one step ahead of their evolution.</p>',
'/uploads/articles/Second-article_1.jpg', 'published', 0, NOW() - INTERVAL 2 DAY, 0);

-- =====================================================================
-- Article Third: Collateral Sensitivity
-- =====================================================================
INSERT INTO articles (category_id, author_id, slug, title_fr, title_en, excerpt_fr, excerpt_en, body_fr, body_en, cover_image, status, is_featured, published_at, views) VALUES
(2, @author_id, 'collateral-sensitivity-evolutionary-trade-offs-rewriting-rules-medicine',
'Sensibilité collatérale : comment les compromis évolutifs réécrivent les règles de la médecine',
'Collateral Sensitivity: How Evolutionary Trade-Offs Are Rewriting the Rules of Medicine',
'Les compromis évolutifs révèlent des faiblesses cachées dans les souches résistantes.',
'Evolutionary trade-offs reveal hidden weaknesses in resistant strains.',
'<p>La résistance aux antibiotiques ressemble souvent à une partie d''échecs où les bactéries gagnent toujours le prochain coup. Mais les compromis évolutifs nous montrent que le plateau n''est pas entièrement incliné contre nous. Lorsque les bactéries développent une résistance à un médicament, elles exposent parfois de nouvelles faiblesses — un phénomène appelé sensibilité collatérale.</p>

<p>La sensibilité collatérale survient lorsqu''une mutation qui protège les bactéries contre un antibiotique les rend plus vulnérables à un autre. Les mutations des pompes d''efflux qui éjectent les aminoglycosides peuvent involontairement permettre aux bêta-lactamines de pénétrer plus facilement. Cliniquement, ce compromis est exploitable.</p>

<p>Cependant, tous les compromis évolutifs ne nous sont pas bénéfiques. Parfois, la résistance à un antibiotique confère également une résistance à un autre. Les mutations ribosomales, par exemple, peuvent bloquer simultanément plusieurs inhibiteurs de synthèse protéique.</p>

<h3>Tactiques cliniques</h3>

<p><strong>Thérapie combinée :</strong> prescrire deux médicaments avec des faiblesses réciproques ensemble, ne laissant aux bactéries aucune voie d''échappée génétique sûre.</p>

<p><strong>Cyclage de médicaments :</strong> changer de traitement au moment où la résistance émerge, prenant les bactéries au dépourvu quand elles sont les plus vulnérables.</p>

<p>La promesse de la sensibilité collatérale dépend de la surveillance. En transformant la science évolutive en stratégie pratique, nous pouvons passer d''une course désespérée contre les superbactéries à une nouvelle ère de gestion de précision.</p>',

'<p>Antibiotic resistance often feels like a game of chess where bacteria always win the next move. But evolutionary trade-offs show us that the board is not tilted entirely against us. When bacteria evolve resistance to one drug, they sometimes expose new weaknesses — a phenomenon called collateral sensitivity. This flips the narrative from checkmate to counter-play.</p>

<p>Collateral sensitivity occurs when a mutation that protects bacteria against one antibiotic makes them more vulnerable to another. Efflux pump mutations that eject aminoglycosides can inadvertently allow beta-lactams to penetrate more easily, creating a broad, predictable weakness across resistant populations. Clinically, this trade-off is exploitable.</p>

<p>Not all evolutionary trade-offs benefit us. Sometimes, resistance to one antibiotic also confers resistance to another. Ribosomal mutations, for example, can block multiple protein-synthesis inhibitors simultaneously.</p>

<h3>Clinical tactics</h3>

<p><strong>Combination therapy:</strong> prescribing two drugs with reciprocal weaknesses together, leaving bacteria with no safe genetic escape route.</p>

<p><strong>Drug cycling:</strong> switching treatments at the moment resistance emerges, catching bacteria off-guard when they are most vulnerable.</p>

<p>The promise of collateral sensitivity depends on surveillance. By turning evolutionary science into practical strategy, collateral sensitivity transforms bacterial adaptation from our greatest threat into our sharpest weapon.</p>',
'/uploads/articles/Third_article_1.jpg', 'published', 0, NOW() - INTERVAL 3 DAY, 0);

-- =====================================================================
-- Article 1: What People Think About AMR (Video article)
-- =====================================================================
INSERT INTO articles (category_id, author_id, slug, title_fr, title_en, excerpt_fr, excerpt_en, body_fr, body_en, cover_image, status, is_featured, published_at, views) VALUES
(2, @author_id, 'what-people-think-about-amr-street-quiz',
'Ce que les gens pensent de la RAM',
'What People Think About AMR',
'Quand on demande « Qu''est-ce que la résistance antimicrobienne ? », un répondant a pensé à la résistance Bangwa, la résistance Bakweri — toutes des résistances historiques au Cameroun !',
'When asked "What is antimicrobial resistance?", one respondent thought of the Bangwa resistance, Bakweri resistance — all historical resistances in Cameroon!',
'<p><strong>Vidéo :</strong> <a href="https://youtu.be/JuSD2ap2Dmk" target="_blank">Regarder sur YouTube</a></p>

<p>Ce malentendu hilarant met en lumière un fossé très réel dans la sensibilisation du public. Quand nous entendons le mot « résistance » ici au Cameroun, nos esprits dérivent naturellement vers notre riche histoire de résistance contre les puissances coloniales, comme les courageux combattants Bangwa ou Bakweri. Cependant, alors que ces luttes historiques ont façonné notre passé, cette résistance biologique moderne menace silencieusement notre avenir.</p>

<h3>Alors, qu''est-ce que la RAM exactement ?</h3>

<p>En termes simples, la résistance antimicrobienne se produit lorsque les minuscules germes qui nous rendent malades (bactéries, virus, champignons ou parasites) évoluent et s''adaptent. Au fil du temps, ils trouvent comment survivre aux médicaments mêmes (antibiotiques, antiviraux et antifongiques) que nous utilisons pour les tuer. Cela transforme des infections ordinaires et traitables en dangereux « superbactéries ».</p>

<p>La RAM n''est pas juste un malentendu local ou un pépin mineur de santé — c''est une crise sanitaire mondiale à part entière. Si nos médicaments cessent de fonctionner, même une simple coupure, un accouchement ou une chirurgie de routine pourrait soudainement devenir mortel.</p>

<p>Au niveau mondial, le Global Antibiotic Research & Development Partnership (GARDP) a lancé sa plateforme REVIVE, qui connecte les chercheurs du monde entier pour partager des connaissances vitales et accélérer la découverte de nouveaux antibiotiques. Plus près de nous, l''African Society for Laboratory Medicine (ASLM) renforce activement la surveillance à travers le continent.</p>

<p>Vaincre la RAM est une responsabilité partagée qui commence par nos choix quotidiens : ne prendre des antibiotiques que lorsqu''un professionnel de santé qualifié les prescrit, toujours terminer le cours complet, et ne jamais partager les pilules restantes.</p>',

'<p><strong>Video:</strong> <a href="https://youtu.be/JuSD2ap2Dmk" target="_blank">Watch on YouTube</a></p>

<p>This hilarious mix-up highlights a very real gap in public awareness. When we hear the word "resistance" here in Cameroon, our minds naturally drift to our rich history of standing up against colonial powers, like the brave Bangwa or Bakweri fighters. However, while those historical struggles shaped our past, this modern biological resistance is quietly threatening our future.</p>

<h3>So, what exactly is AMR?</h3>

<p>In plain terms, antimicrobial resistance happens when the tiny germs that make us sick (bacteria, viruses, fungi, or parasites) evolve and adapt. Over time, they figure out how to survive the very medicines (antibiotics, antivirals, and antifungals) we use to kill them. This turns ordinary, treatable infections into dangerous, unyielding "superbugs".</p>

<p>AMR is not just a local misunderstanding or a minor healthcare glitch — it is a full-blown global health crisis. If our medicines stop working, even a simple cut, childbirth, or routine surgery could suddenly become life-threatening.</p>

<p>Globally, the Global Antibiotic Research & Development Partnership (GARDP) has launched its REVIVE platform, which connects researchers worldwide to share vital knowledge and speed up the discovery of new antibiotics. Closer to home, the African Society for Laboratory Medicine (ASLM) is actively strengthening surveillance across the continent.</p>

<p>Beating AMR is a shared responsibility that starts with our daily choices: only taking antibiotics when a qualified healthcare professional prescribes them, always finishing the full course, and never sharing leftover pills.</p>',
'/uploads/articles/Article-1.jpg', 'published', 0, NOW() - INTERVAL 4 DAY, 0);

-- =====================================================================
-- Article 2: Antibiotics vs Antimicrobials (Video article)
-- =====================================================================
INSERT INTO articles (category_id, author_id, slug, title_fr, title_en, excerpt_fr, excerpt_en, body_fr, body_en, cover_image, status, is_featured, published_at, views) VALUES
(2, @author_id, 'antibiotics-vs-antimicrobials-what-students-say',
'ANTIBIOTIQUES vs ANTIMICROBIENS : Ce que les étudiants de UB en disent',
'ANTIBIOTICS vs ANTIMICROBIALS: What UB Students Had to Say!',
'Vous êtes-vous déjà demandé si un escargot pouvait guérir une infection bactérienne, ou si l''ibuprofène est secrètement un antibiotique ?',
'Ever wondered if a snail could cure a bacterial infection, or if ibuprofen is secretly an antibiotic?',
'<p><strong>Vidéo :</strong> <a href="https://youtu.be/yWO4u1bYmRs" target="_blank">Regarder sur YouTube</a></p>

<p>Si vous avez vu ce quiz de rue filmé sur le campus de UB, vous savez déjà que lorsqu''il s''agit de définir des termes médicaux, les étudiants universitaires peuvent être incroyablement créatifs. Du mélange entre antifongiques et savons spéciaux au fait de citer avec confiance des exemples de médicaments incorrects, le vox pop a révélé une vérité hilarante mais très reconnaissable.</p>

<h3>Clarifions la confusion</h3>

<p>Pensez au mot <strong>antimicrobien</strong> comme un immense parapluie. C''est une classe large et fourre-tout de médicaments conçus pour détruire ou arrêter la croissance de toutes sortes de minuscules organismes pathogènes. Cette famille massive couvre les traitements pour les bactéries, virus, champignons et parasites.</p>

<p>De l''autre côté, un <strong>antibiotique</strong> n''est qu''une escouade spécialisée assise sous ce grand parapluie. Les antibiotiques ont un travail hautement spécifique : ils ciblent et tuent UNIQUEMENT les bactéries. Ils sont complètement inutiles contre une grippe virale, un rhume ou un parasite.</p>

<p>Cette distinction peut sembler mineure, mais la comprendre est crucial pour la santé mondiale. Lorsque nous abusons des antibiotiques pour traiter des choses qu''ils ne peuvent pas guérir, comme une grippe virale, nous n''allons pas mieux — au contraire, nous exposons involontairement les microbes survivants au médicament, les aidant à apprendre à riposter. Cela alimente la montée dangereuse des superbactéries.</p>',

'<p><strong>Video:</strong> <a href="https://youtu.be/yWO4u1bYmRs" target="_blank">Watch on YouTube</a></p>

<p>If you caught this street quiz video filmed on campus at UB, you already know that when it comes to defining medical terms, university students can get incredibly creative. From mixing up antifungals with specialty soaps to confidently throwing out incorrect drug examples, the vox pop revealed a hilarious but highly relatable truth.</p>

<h3>Let us clear up the confusion</h3>

<p>Think of the word <strong>antimicrobial</strong> as a massive umbrella. It is a broad, catch-all class of drugs designed to destroy or stop the growth of all kinds of tiny, disease-causing organisms. This massive family covers treatments for bacteria, viruses, fungi, and parasites alike.</p>

<p>On the flip side, an <strong>antibiotic</strong> is just one specialized squad sitting under that big umbrella. Antibiotics have one highly specific job: they ONLY target and kill bacteria. They are completely useless against a common viral flu, a cold, or a parasite.</p>

<p>This distinction might seem like minor wordplay, but getting it right is a massive deal for global health. When we misuse antibiotics to treat things they cannot cure, like a viral flu, we do not get better — instead, we inadvertently expose surviving bugs to the drug, helping them learn how to fight back. This fuels the dangerous rise of superbugs and drug resistance across communities.</p>',
'/uploads/articles/Article2_1.jpg', 'published', 0, NOW() - INTERVAL 5 DAY, 0);

-- =====================================================================
-- Article 3: One Health — Beyond the Silos
-- =====================================================================
INSERT INTO articles (category_id, author_id, slug, title_fr, title_en, excerpt_fr, excerpt_en, body_fr, body_en, cover_image, status, is_featured, published_at, views) VALUES
(1, @author_id, 'beyond-the-silos-one-health-awareness-gap',
'Au-delà des silos : ce qu''un vox pop de campus révèle sur le fossé de sensibilisation « One Health »',
'Beyond the Silos: What a Campus Vox Pop Reveals About the "One Health" Awareness Gap',
'Imaginez étudier pour devenir un professionnel de santé de première ligne, pour être complètement déconcerté quand on vous interroge sur l''approche la plus critique pour prévenir la prochaine pandémie.',
'Imagine studying to become a frontline healthcare professional, only to be left completely stumped when asked about the most critical approach to preventing the next global pandemic.',
'<p><strong>Vidéo :</strong> <a href="https://youtu.be/lJBnk9_qUo4" target="_blank">Regarder sur YouTube</a></p>

<h3>Vérification de la réalité</h3>

<p>Une récente vidéo vox pop capturant des interviews de rue avec des étudiants en sciences de la santé et en soins infirmiers a fourni un rappel à la fois humoristique et révélateur sur la littératie actuelle en santé publique. Quand on leur a posé la question apparemment simple « Qu''est-ce que One Health ? », les futurs professionnels médicaux ont fait face à la caméra avec des regards vides.</p>

<h3>Moderniser le cadre</h3>

<p>Selon le Panel d''Experts de Haut Niveau One Health : « One Health est une approche intégrée et unificatrice. Elle vise à équilibrer et optimiser durablement la santé des personnes, des animaux et des écosystèmes. » Ces trois domaines ne vivent pas dans des vides séparés — ils sont profondément liés et interdépendants.</p>

<h3>Pourquoi est-ce si important ?</h3>

<p>Parce que la grande majorité des menaces infectieuses émergentes sont zoonotiques, nées précisément à l''interface volatile où l''expansion humaine, les populations animales et les environnements changeants entrent en collision. Continuer à former les travailleurs de la santé dans des silos académiques rigides est un luxe que le monde moderne ne peut plus se permettre.</p>

<p>Intégrer les principes One Health directement dans les programmes universitaires n''est pas juste une mise à niveau éducative — c''est un pilier fondamental de la sécurité sanitaire mondiale.</p>',

'<p><strong>Video:</strong> <a href="https://youtu.be/lJBnk9_qUo4" target="_blank">Watch on YouTube</a></p>

<h3>Reality check</h3>

<p>A recent, lighthearted vox pop video capturing street interviews with health science and nursing students provided a humorous yet eye-opening reality check on current public health literacy. When asked a seemingly straightforward question "What is One Health?" tomorrow''s medical professionals met the camera with blank stares.</p>

<h3>Modernizing the framework</h3>

<p>According to the One Health High-Level Expert Panel: "One Health is an integrated, unifying approach. It aims to sustainably balance and optimize the health of people, animals, and ecosystems." These three domains do not exist in separate vacuums — they are profoundly linked and interdependent.</p>

<h3>Why does it matter?</h3>

<p>Because the vast majority of emerging infectious threats are zoonotic, born precisely at the volatile interface where human expansion, animal populations, and changing environments collide. Continuing to train healthcare workers in rigid academic silos is a luxury the modern world can no longer afford.</p>

<p>Integrating One Health principles directly into university curricula is not just an educational upgrade — it is a core pillar of global health security. Turning the initial confusion of today''s students into proactive, cross-disciplinary collaboration is exactly how we build a resilient workforce.</p>',
'/uploads/articles/Article3.jpg', 'published', 1, NOW() - INTERVAL 6 DAY, 0);

-- =====================================================================
-- Article 4: Beyond the Bedroom — STDs
-- =====================================================================
INSERT INTO articles (category_id, author_id, slug, title_fr, title_en, excerpt_fr, excerpt_en, body_fr, body_en, cover_image, status, is_featured, published_at, views) VALUES
(1, @author_id, 'beyond-the-bedroom-surprising-reality-stds-spread',
'Au-delà de la chambre : la réalité surprenante de la transmission des IST',
'Beyond the Bedroom: The Surprising Reality of How STDs Actually Spread',
'Quand vous entendez « IST », votre esprit va probablement directement à un endroit : les rapports protégés. Mais que se passerait-il si vous pouviez contracter une infection sans jamais vous déshabiller ?',
'When you hear "STD," your mind probably goes straight to one place: safe sex. But what if you could contract an infection without ever taking off your clothes?',
'<p><strong>Vidéo :</strong> <a href="https://youtu.be/LR36SmAo4h0" target="_blank">Regarder sur YouTube</a></p>

<p>Pour vraiment comprendre comment ces pathogènes se déplacent, nous devons examiner de près la mécanique biologique des infections elles-mêmes. Des maladies comme le VIH, la syphilis et la gonorrhée sont causées par des virus et bactéries volatils qui ne se soucient pas du contexte du comportement humain — ils cherchent simplement à trouver un chemin vers votre circulation sanguine ou vos muqueuses.</p>

<p>Cela signifie que les voies non sexuelles sont des routes de transmission entièrement viables. Par exemple, la <strong>transmission verticale</strong> passe une infection directement d''une mère enceinte à son enfant lors de l''accouchement ou de l''allaitement. De même, le contact <strong>sang-à-sang</strong> via le partage d''objets tranchants fournit une autoroute directe pour les pathogènes.</p>

<p>Certaines infections hautement contagieuses, comme le virus de l''herpès simplex (HSV) et le papillomavirus humain (HPV), prospèrent principalement par le contact peau-à-peau. Les professionnels de santé font face à un vecteur entièrement séparé : l''<strong>exposition professionnelle</strong>.</p>

<p>Confronter ces faits change toute notre approche de la santé publique et de la prévention personnelle. Connaître son statut ne devrait pas être une source de honte, mais un acte de littératie sanitaire de base.</p>',

'<p><strong>Video:</strong> <a href="https://youtu.be/LR36SmAo4h0" target="_blank">Watch on YouTube</a></p>

<p>To truly understand how these pathogens move, we have to look closely at the biological mechanics of the infections themselves. Diseases like HIV, syphilis, and gonorrhea are caused by volatile viruses and bacteria that do not care about the context of human behavior — they simply care about finding a pathway into your bloodstream or mucous membranes.</p>

<p>This means that non-sexual pathways are entirely viable routes for transmission. For instance, <strong>vertical transmission</strong> passes an infection directly from a pregnant mother to her unborn child during childbirth or through breastfeeding. Similarly, <strong>blood-to-blood contact</strong> via sharing of sharp objects provides a direct highway for pathogens.</p>

<p>Certain highly contagious infections, such as Herpes Simplex Virus (HSV) and Human Papillomavirus (HPV), thrive primarily through skin-to-skin contact. Healthcare professionals face an entirely separate vector: <strong>occupational exposure</strong>.</p>

<p>Confronting these facts changes our entire approach to public health and personal prevention. Knowing your status should not be a source of shame or fear, but an act of basic health literacy.</p>',
'/uploads/articles/Article4.jpg', 'published', 0, NOW() - INTERVAL 7 DAY, 0);

-- =====================================================================
-- Article 5: Buruli Ulcer — NTDs
-- =====================================================================
INSERT INTO articles (category_id, author_id, slug, title_fr, title_en, excerpt_fr, excerpt_en, body_fr, body_en, cover_image, status, is_featured, published_at, views) VALUES
(1, @author_id, 'shadows-along-nyong-buruli-ulcer-akonolinga-ayos',
'Les ombres le long du Nyong : démasquer la menace silencieuse de l''ulcère de Buruli à Akonolinga et Ayos',
'The Shadows Along the Nyong: Unmasking the Silent Threat of Buruli Ulcer in Akonolinga and Ayos',
'Les eaux pittoresques de la rivière Nyong au Cameroun central paraissent paisibles. Mais sous ce décor idyllique se cache une réalité obsédante : une bactérie mangeuse de chair invisible.',
'The scenic waters of the Nyong River in Central Cameroon appear peaceful. But underneath hides a haunting reality: an invisible, flesh-eating bacterium that has silently paralyzed communities.',
'<p><strong>Vidéo :</strong> <a href="https://youtu.be/6MEx-MKPgnU" target="_blank">Regarder sur YouTube</a></p>

<h3>Le prédateur invisible du bassin de la rivière Nyong</h3>

<p>Dans les villes d''Akonolinga et d''Ayos, une maladie tropicale négligée (MTN) dévastatrice connue localement sous le nom d''« atôm » a laissé de profondes cicatrices physiques et économiques. Le coupable est <em>Mycobacterium ulcerans</em>, une bactérie environnementale apparentée aux organismes qui causent la lèpre et la tuberculose.</p>

<p>Prospérant dans les eaux à mouvement lent comme la rivière Nyong, la bactérie pénètre le corps humain par des coupures apparemment anodines. Ce qui rend ce pathogène exceptionnellement cruel est son arme évolutive : une toxine lipidique unique appelée <strong>mycolactone</strong>. Cette toxine détruit les cellules cutanées et engourdit complètement les nerfs locaux, permettant une mort tissulaire massive sans causer de fièvre ou de douleur immédiate.</p>

<h3>Le visage de l''« ATÔM » et la réalité du handicap</h3>

<p>Pour la plupart des familles, la manifestation de l''ulcère de Buruli est une horloge terrifiante qui cible de manière disproportionnée les enfants de moins de 15 ans. Cela commence de manière trompeuse comme un petit nodule indolore sous la peau. En quelques semaines, ce gonflement se rompt en une plaie ouverte massive et malodorante.</p>

<h3>Interventions modernes de santé publique</h3>

<p>Historiquement, la gestion de l''ulcère de Buruli était une épreuve exigeante reposant fortement sur des chirurgies invasives. Cependant, les initiatives modernes de santé publique ont radicalement transformé le paradigme de traitement. Les cas précoces sont hautement traitables avec un cours définitif de 8 semaines de double antibiothérapie (rifampicine orale combinée avec la clarithromycine).</p>',

'<p><strong>Video:</strong> <a href="https://youtu.be/6MEx-MKPgnU" target="_blank">Watch on YouTube</a></p>

<h3>The invisible predator of the Nyong River basin</h3>

<p>In the towns of Akonolinga and Ayos, a devastating neglected tropical disease (NTD) known locally as "atom" has left deep physical and economic scars. The culprit is <em>Mycobacterium ulcerans</em>, an environmental bacterium related to the organisms that cause leprosy and tuberculosis.</p>

<p>Thriving in slow-moving waters like the Nyong River, the bacterium enters the human body through seemingly harmless cuts. What makes this pathogen exceptionally cruel is its evolutionary weapon: a unique lipid toxin called <strong>mycolactone</strong>. This toxin destroys skin cells and completely numbs local nerves, allowing massive tissue death to progress without causing fever or immediate pain.</p>

<h3>The face of "ATOM" and the reality of disability</h3>

<p>For most families, the manifestation of Buruli ulcer is a terrifying ticking clock that disproportionately targets children under the age of 15. It begins deceptively as a small, painless nodule underneath the skin. Within weeks, this swelling ruptures into a massive, foul-smelling open wound.</p>

<h3>Modern public health interventions</h3>

<p>Historically, managing Buruli ulcer was a grueling ordeal heavily reliant on invasive surgeries. However, modern public health initiatives have radically transformed the treatment paradigm. Early-stage cases are highly treatable using a definitive 8-week course of dual antibiotics (oral rifampicin combined with clarithromycin).</p>',
'/uploads/articles/Article5_1.jpg', 'published', 0, NOW() - INTERVAL 8 DAY, 0);

-- =====================================================================
-- Article 6: Zoonotic Knowledge Gap
-- =====================================================================
INSERT INTO articles (category_id, author_id, slug, title_fr, title_en, excerpt_fr, excerpt_en, body_fr, body_en, cover_image, status, is_featured, published_at, views) VALUES
(1, @author_id, 'shocking-zoonotic-knowledge-gap-surprising-answers',
'Le fossé choquant de connaissances zoonotiques : révéler les réponses surprenantes',
'The Shocking Zoonotic Knowledge Gap: Revealing the Surprising Answers',
'Et si la plus grande menace pour la santé publique mondiale était un mot que la plupart des gens ne peuvent même pas définir ?',
'What if the biggest threat to global public health is a word most people cannot even define?',
'<p><strong>Vidéo :</strong> <a href="https://youtu.be/Xv4TkEhWBUU" target="_blank">Regarder sur YouTube</a></p>

<h3>Les confessions du campus et la comédie des erreurs</h3>

<p>Le vox pop viral a parfaitement capturé la déconnexion du public vis-à-vis des termes de santé vétérinaire et environnementale. Alors qu''un étudiant en Sciences de Laboratoire Médical a correctement expliqué comment la proximité des élevages de volailles sans EPI approprié peut exposer à des risques respiratoires, d''autres étaient complètement perdus.</p>

<h3>Combler le fossé : qu''est-ce réellement qu''une zoonose ?</h3>

<p>Les maladies zoonotiques sont des infections transmises directement entre animaux et humains. Elles ne sont pas une anomalie médicale de niche — elles sont causées par des pathogènes courants incluant bactéries, champignons, virus et parasites. Quand les humains vivent en proximité étroite avec le bétail ou manipulent de la volaille sans masques ni gants, ils deviennent hautement susceptibles.</p>

<h3>Pourquoi ce vide dans notre vocabulaire compte</h3>

<p>Ce manque de sensibilisation n''est pas qu''un oubli académique — c''est une vulnérabilité critique. Les données de santé publique montrent systématiquement qu''environ 60% des maladies infectieuses connues chez l''humain, et jusqu''à 75% des maladies infectieuses émergentes, sont d''origine zoonotique. Combler ce fossé de connaissances, une conversation à la fois, est une étape vitale vers la défense communautaire.</p>',

'<p><strong>Video:</strong> <a href="https://youtu.be/Xv4TkEhWBUU" target="_blank">Watch on YouTube</a></p>

<h3>The campus confessions and the comedy of errors</h3>

<p>The viral vox pop perfectly captured how disconnected the public is from veterinary and environmental health terms. While one Medical Laboratory Science student accurately broke down how proximity to poultry farms without proper PPE can expose someone to respiratory risks, others were left entirely guessing.</p>

<h3>Bridging the gap: What actually is a zoonosis?</h3>

<p>Zoonotic diseases are infections transmitted directly between animals and humans. They are not a niche medical anomaly — they are driven by everyday pathogens including bacteria, fungi, viruses, and parasites. When humans live in close proximity with livestock or handle poultry without masks or gloves, they become highly susceptible.</p>

<h3>Why this empty spot in our vocabulary matters</h3>

<p>This lack of awareness is not just an academic oversight — it is a critical vulnerability. Public health data consistently shows that roughly 60% of known infectious diseases in humans, and up to 75% of emerging infectious diseases, are zoonotic in origin. Closing this knowledge gap, one conversation at a time, is a vital step toward community defense.</p>',
'/uploads/articles/Article6.jpg', 'published', 0, NOW() - INTERVAL 9 DAY, 0);

-- =====================================================================
-- Article 7: Herbal Remedies vs Modern Medicine
-- =====================================================================
INSERT INTO articles (category_id, author_id, slug, title_fr, title_en, excerpt_fr, excerpt_en, body_fr, body_en, cover_image, status, is_featured, published_at, views) VALUES
(2, @author_id, 'bridging-gap-balance-herbal-remedies-modern-medicine',
'Combler le fossé : trouver l''équilibre entre remèdes traditionnels et médecine moderne',
'Bridging the Gap: Finding the Balance Between Herbal Remedies and Modern Medicine',
'Pendant des générations, la médecine traditionnelle a servi de première ligne de soins communautaires. Pourtant, si vous demandez à quelqu''un s''il préfère le guérisseur traditionnel ou l''hôpital moderne, la réponse est complexe.',
'For generations, herbal medicine has served as the frontline of community care. Yet if you ask the average person whether they prefer the traditional healer or the modern hospital, the answer is complex.',
'<p><strong>Vidéo :</strong> <a href="https://youtu.be/Hz7Pod5GqPk" target="_blank">Regarder sur YouTube</a></p>

<p>Pour beaucoup, le choix entre médecine traditionnelle et moderne est entièrement situationnel, dicté par la gravité perçue et la nature de l''affection. La croyance populaire oriente fréquemment des conditions spécifiques comme les fractures complexes ou les défis reproductifs vers les praticiens traditionnels.</p>

<p>Les partisans des remèdes traditionnels défendent passionnément leur choix en pointant vers la nature, considérant les traitements à base de plantes comme une forme pure de guérison culturellement alignée. Cependant, même les partisans les plus dévoués reconnaissent un obstacle majeur : <strong>le manque de mesure standardisée</strong>. Sans diagnostics exacts, la médecine traditionnelle peut parfois reposer sur l''approximation.</p>

<p>À l''inverse, un nombre croissant de personnes plaide strictement pour la médecine moderne, soulignant son immédiateté clinique et son approche basée sur les données. Pourtant, la médecine moderne n''est pas sans inconvénients — coûts élevés, résistance émergente aux médicaments, et accessibilité limitée en zones rurales.</p>

<p>Reconnaissant que jusqu''à 80% de la population dépend encore des remèdes traditionnels comme soins primaires, les paysages de santé publique passent du conflit à la collaboration. L''OMS a déployé des stratégies mondiales pour valider scientifiquement et intégrer en toute sécurité les connaissances indigènes dans les systèmes de santé formels. L''objectif ultime n''est pas de choisir un chemin plutôt qu''un autre, mais d''établir une réalité hybride et sûre.</p>',

'<p><strong>Video:</strong> <a href="https://youtu.be/Hz7Pod5GqPk" target="_blank">Watch on YouTube</a></p>

<p>For many, the choice between traditional and modern medicine is entirely situational, dictated by the perceived severity and nature of the ailment. Popular belief frequently routes specific conditions such as complex bone fractures or long-term reproductive challenges straight to traditional practitioners.</p>

<p>Proponents of herbal remedies passionately defend their choice by pointing to nature, viewing plant-based treatments as a pure, culturally aligned form of healing. However, even the most dedicated supporters acknowledge a major hurdle: <strong>the lack of standard measurement</strong>. Without exact diagnostics, traditional medicine can sometimes rely on guesswork.</p>

<p>Conversely, a growing number of people strictly advocate for modern medicine, pointing to its clinical immediacy and data-driven approach. Yet, modern medicine is not without its own setbacks — high costs, emerging drug resistance, and limited accessibility in rural areas.</p>

<p>Recognizing that up to 80% of the population still relies on traditional remedies as primary care, public health landscapes are shifting from conflict to collaboration. The WHO has rolled out global strategies to scientifically validate and safely integrate indigenous knowledge into formal healthcare systems. The ultimate goal is not to choose one path over the other, but to establish a safe, hybrid reality.</p>',
'/uploads/articles/Article7.jpg', 'published', 0, NOW() - INTERVAL 10 DAY, 0);
