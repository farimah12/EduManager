# 🎓 EduManager

Application web de gestion académique : classes, niveaux, étudiants, modules, notes et bulletins — avec un module de **prédiction du risque de décrochage** basé sur la moyenne de l'étudiant.

Projet personnel réalisé par **Adji Farimata CISSE**, étudiante en Génie Logiciel à l'Institut Supérieur d'Informatique (ISI), Dakar.

---

## ✨ Fonctionnalités

- 🔐 Authentification
- 🏷️ Gestion des niveaux et des classes
- 🧑‍🎓 Gestion des étudiants
- 📚 Gestion des modules
- 📝 Gestion des évaluations et des notes
- 📊 Calcul de moyenne par étudiant et par classe
- 🚨 **Prédiction du risque de décrochage**, basée sur la moyenne générale :
  - ≥ 12 : Faible risque — performance stable
  - 8 à 11.99 : Risque modéré — suivi recommandé
  - < 8 : Risque élevé de décrochage — intervention pédagogique recommandée
- 🧾 Génération de bulletins en PDF (via FPDF)
- 📋 Tableau de bord

## 🛠️ Technologies utilisées

- **PHP** (PDO pour l'accès à la base de données)
- **MySQL / MariaDB**
- **Bootstrap 5**, **Bootstrap Icons**, **Font Awesome**
- **FPDF** (génération des bulletins PDF)

## 📂 Structure du projet

```
gestion_academique/
├── index.php                     # Routeur principal (?page=...)
├── connexion.php / logout.php    # Authentification
├── includes/
│   ├── header.php / footer.php
│   ├── navbar.php / sidebar.php
├── pages/
│   ├── dashboard.php
│   ├── niveaux.php
│   ├── classes.php
│   ├── etudiants.php
│   ├── modules.php
│   ├── evaluations.php
│   ├── moyenne_etudiant.php
│   ├── moyenne_classe.php
│   └── erreur404.php
├── traitements/
│   ├── db.php                    # Connexion à la base de données
│   ├── requetes.php               # Requêtes SQL + logique de prédiction
│   └── action.php
├── fpdf/                          # Librairie FPDF (génération PDF)
├── public/                        # CSS, JS, assets
└── gestion_academique.sql         # Script de création de la base de données
```

## 🚀 Installation locale (XAMPP)

1. Clone ce dépôt dans le dossier `htdocs` de XAMPP :
```bash
git clone https://github.com/farimah12/EduManager.git
```
2. Démarre **Apache** et **MySQL** depuis le panneau de contrôle XAMPP.
3. Importe la base de données :
   - Ouvre **phpMyAdmin**
   - Onglet **Importer** → sélectionne `gestion_academique.sql`
4. Accède à l'application : `http://localhost/EduManager/index.php`

## 🔑 Identifiants de démonstration

| Email | Mot de passe |
|-------|---------------|
| `admin@gmail.com` | `admin123` |




## 👩‍💻 Auteure

**Adji Farimata CISSE**
Étudiante en Génie Logiciel — ISI, Dakar
📧 adjifarimahcisse@gmail.com
