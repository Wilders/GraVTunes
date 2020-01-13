# GraVTunes

Projet Tutoré (S3B_S02) - Sources partie Web

## Installation

Utilisez [composer](https://getcomposer.org/) pour installer les dépendances de GraVTunes.

```bash
git clone git@bitbucket.org:depinfoens/s3b_s02_chevalier_pernot_sayer_tardieu.git
cd s3b_s02_chevalier_pernot_sayer_tardieu/src/web
composer install
```

Il faut créer un fichier de configuration pour la base de donnée nommé **database.ini** dans le répertoire app/config.
En insérant:

| Paramètre     | Valeur d'exemple | Description               |
| :------------:|:----------------:|:-------------------------:|
| driver        | mysql            | Driver de votre SGBD      |
| host          | localhost        | Hôte de votre BDD         |
| database      | gravtunes        | Nom de votre BDD          |
| username      | root             | Nom d'user de votre BDD   |
| password      | root             | Mot de passe de votre BDD |
| charset       | utf8             | Méthode d'encodage        |
| collation     | utf8_unicode_ci  | Collation de la BDD       |

## Utilisation

Lancez un serveur XAMP, importez la BDD (fichier **sql/gravtunes.sql**) et connectez-vous sur le site.

## Technologies

- [x] *[PHP ^7.2](https://github.com/php/php-src)*
- [x] *[Slim 3](https://github.com/slimphp/Slim)*
- [x] *[Eloquent ORM](https://github.com/illuminate/database)*
- [x] *[FIG Cookies](https://github.com/dflydev/dflydev-fig-cookies)*
- [x] *[Twig-View (fork Slim)](https://github.com/slimphp/Twig-View)*
- [x] *[Slim-Flash](https://github.com/slimphp/Slim-Flash)*
- [x] *[Bootstrap](https://github.com/twbs/bootstrap)*
- [x] *[jQuery](https://github.com/jquery/jquery)*
- [x] *[jQuery-UI](https://github.com/jquery/jquery-ui)*
