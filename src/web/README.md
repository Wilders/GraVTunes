# GraVTunes

Projet Tutoré (S3B_S02) - Sources partie Web

## Installation

Utilisez [composer](https://getcomposer.org/) pour installer les dépendances de GraVTunes.

```bash
git clone git@bitbucket.org:depinfoens/s3b_s02_chevalier_pernot_sayer_tardieu.git
cd s3b_s02_chevalier_pernot_sayer_tardieu/src/web
composer install
```

Il faut copier le fichier .env.example, le renommer en .env et remplir les informations de connexion à la BDD.

| Paramètre     | Valeur d'exemple | Description               |
| :------------:|:----------------:|:-------------------------:|
| DB_DRIVER     | mysql            | Driver de votre SGBD      |
| DB_HOST       | localhost        | Hôte de votre BDD         |
| DB_NAME       | gravtunes        | Nom de votre BDD          |
| DB_USER       | root             | Nom d'user de votre BDD   |
| DB_PWD        | root             | Mot de passe de votre BDD |
| DB_CHARSET    | utf8             | Méthode d'encodage        |
| DB_COLLATION  | utf8_unicode_ci  | Collation de la BDD       |
| DB_PREFIX     | gvt              | Préfixe de nom de table   |

## Utilisation

Lancez un serveur XAMP, importez la BDD (fichier **sql/gravtunes.sql**) et connectez-vous sur le site.