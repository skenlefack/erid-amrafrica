# ERID-AMRAfrica — Plateforme web (PHP 8 + MariaDB)

> Réseau continental de renseignement sanitaire **One Health** — Résistance
> antimicrobienne (RAM) & maladies infectieuses émergentes/ré-émergentes (ERID).
> Site public + console d'administration. Tout le contenu public est piloté
> depuis l'admin. Bilingue FR/EN.
>
> *Continental One Health health-intelligence platform. Public site + admin
> console; all public content is admin-driven. Bilingual FR/EN.*

---

## 🇫🇷 Démarrage rapide

**Prérequis :** PHP 8.1+ (`pdo_mysql`), MariaDB 10.6+ (ou MySQL 8), un serveur web.

```bash
# 1. Configuration
cp .env.example .env          # puis éditez les identifiants DB

# 2. Base de données (IMPORTANT : forcer UTF-8 à l'import)
mysql -u root -p -e "CREATE DATABASE erid_amrafrica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql --default-character-set=utf8mb4 -u root -p erid_amrafrica < database/schema.sql
mysql --default-character-set=utf8mb4 -u root -p erid_amrafrica < database/seed.sql

# 3. Lancement (développement)
php -S localhost:8080 -t public
```

> **⚠ Utilisateurs Windows :** n'importez **jamais** les fichiers SQL depuis
> `cmd.exe` en page de code OEM (CP850/CP437) — les accents seront corrompus.
> Utilisez **toujours** l'option `--default-character-set=utf8mb4` ou un client
> graphique configuré en UTF-8 (HeidiSQL, DBeaver, phpMyAdmin).

Ouvrez **http://localhost:8080** (site public) et **http://localhost:8080/admin/login** (console).

**Identifiants de démonstration :** `admin@erid-amrafrica.org` · `ChangeMe!2026`
*(À changer immédiatement en production.)*

En production, pointez la racine web (Apache/Nginx) sur le dossier `public/`
uniquement — jamais sur la racine du projet. Le `.htaccess` fourni gère la
réécriture d'URL et bloque l'accès à `.env`, `.sql`, `.md`.

---

## 🇬🇧 Quick start

**Requirements:** PHP 8.1+ (`pdo_mysql`), MariaDB 10.6+ (or MySQL 8), a web server.

```bash
cp .env.example .env          # then edit DB credentials
mysql -u root -p -e "CREATE DATABASE erid_amrafrica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql --default-character-set=utf8mb4 -u root -p erid_amrafrica < database/schema.sql
mysql --default-character-set=utf8mb4 -u root -p erid_amrafrica < database/seed.sql
php -S localhost:8080 -t public
```

> **Warning (Windows):** never import SQL files from `cmd.exe` under OEM code
> page (CP850/CP437) — accented characters will be corrupted. Always use
> `--default-character-set=utf8mb4` or a UTF-8 aware client (HeidiSQL, DBeaver).

Demo login: `admin@erid-amrafrica.org` / `ChangeMe!2026`. In production, point the
web root at `public/` only and change all credentials.

---

## Architecture

Micro-framework MVC maison, **sans dépendance externe** (le starter tourne tel quel).

```
erid-amrafrica/
├── public/                 ← RACINE WEB (la seule exposée)
│   ├── index.php           ← front controller (autoloader, routage, sécurité)
│   ├── .htaccess
│   └── assets/             ← CSS / JS
├── app/
│   ├── Core/               ← Database (PDO), Router, View, Auth (RBAC),
│   │                         Csrf, Lang (i18n FR/EN), Mailer, Audit, Config
│   ├── Controllers/
│   │   ├── Public/         ← Home, News, Services, Intake (tunnel de conversion)
│   │   └── Admin/          ← Auth, Dashboard, Content (CMS), Leads (CRM)
│   ├── Views/              ← layouts + vues public/ + admin/
│   └── lang/               ← fr.php / en.php
├── config/routes.php       ← toutes les routes
└── database/               ← schema.sql + seed.sql
```

### Modules (transposés du brief stratégique)

| Brief | Implémentation |
|-------|----------------|
| **Module 1 — Public Media Engine** | News Hub à 4 canaux (`categories` + `articles`), galerie (`media_items`), publications (`publications`) |
| **Rumour Management System** | `rumours` (intake EBS anonyme + multicanal, triage + score de risque) |
| **Module 2 — Services Portfolio** | 3 piliers (`services`) éditables avec packaging tarifaire |
| **Intake & routage contextuel** | `IntakeController` → `leads` avec `routing_tag`, e-mail de triage auto 48 h |
| **CRM Dashboard** | Console admin : pipeline commercial, qualification, valeur estimée |
| **Data governance** | `audit_logs` immuables, RBAC 4 rôles, requêtes préparées, CSRF, en-têtes de sécurité |

---

## Pourquoi PHP + MariaDB (vs WordPress/PostgreSQL du brief)

- **Maîtrise & sécurité** : pas de surface d'attaque liée aux extensions tierces ;
  chaque requête est préparée (anti-injection), chaque formulaire protégé CSRF.
- **Souveraineté des données** : MariaDB auto-hébergeable sur infrastructure
  africaine, sans dépendance à un CMS propriétaire.
- **Performance faible bande passante** : rendu serveur léger, pages compactes.
- **Évolutivité** : architecture MVC claire, prête à recevoir une API REST/JSON
  pour les produits de données premium.

---

## Monétisation (intégrée au code)

- **Conseil B2B** — 3 piliers avec modèles tarifaires (`quote` / `fixed` /
  `retainer` / `sta`) pilotables depuis l'admin.
- **Abonnements produits de données** — `subscribers.tier` : `free` /
  `intelligence` / `enterprise` (voir `/pricing`).
- **Tunnel de conversion** — Media Engine (haut de tunnel) → interactivité →
  CTA → `leads` (CRM) → contrat. Chaque lead porte une `est_value_usd` pour
  suivre le pipeline depuis le tableau de bord.

---

## Réparation UTF-8 (données corrompues)

Si les accents apparaissent cassés (ex : "├®pid├®miologie" au lieu de
"épidémiologie"), les données ont été importées sous une mauvaise page de code.
La seule réparation fiable est de recréer la base :

```bash
# 1. Supprimer et recréer la base
mysql -u root -p -e "DROP DATABASE erid_amrafrica;"
mysql -u root -p -e "CREATE DATABASE erid_amrafrica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Réimporter avec le charset forcé
mysql --default-character-set=utf8mb4 -u root -p erid_amrafrica < database/schema.sql
mysql --default-character-set=utf8mb4 -u root -p erid_amrafrica < database/seed.sql
```

> Sur Infomaniak ou tout hébergement distant, adaptez l'hôte, le user et le nom
> de la base selon votre `.env`.

---

## Sécurité — à compléter avant production

1. Changer le mot de passe admin et les identifiants DB.
2. Activer HTTPS (TLS 1.3) et `session.cookie_secure`.
3. Brancher un vrai SMTP dans `app/Core/Mailer.php` (PHPMailer recommandé)
   en remplacement de la journalisation de démonstration.
4. Mettre le dossier d'upload hors racine web + validation MIME stricte.
5. Sauvegardes chiffrées (AES-256 au repos) et résidence des données.

---

## Test rapide vérifié

Ce starter a été testé de bout en bout : import schéma/seed, rendu des pages
publiques, soumission d'un lead avec routage contextuel et e-mail de triage,
connexion admin et affichage des KPIs, bascule FR/EN, journal d'audit.

© ERID-AMRAfrica. Auteur du brief : Johngwe Mac Juliette N., Consultante Principale.
