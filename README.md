# Utilisation du component [workflow](https://symfony.com/doc/current/workflow.html) de symfony

#### [PHP 7.4] [SYMFONY 4.4] [DOCKER] [SQLITE] [MailerCatcher] [BOOTSTRAP 5]
Création d'une application web qui va permettre à un enfant de demander un cadeau à ses parents

- trois Rôles: Kid, Dad et Mum
- La possibilité de s'inscrire, d'ouvrir et fermer une session
- être notifié par mail au fure et à mesure des étapes
- deux URL 
    /kid (pour formuler la demande)
    /parents (pour faire avancer la demande)

# Les différents étapes mise en place lors de l'élaboration du projet

### Création du projet ToyReqest (avec le binaire symfony) et toutes les dépendances (--full) [symfony Releases Calendar](https://symfony.com/releases)


```bash
symfony new ToyRequest --version=4.4 --full
```

### SQLite (base de donnée)
dans le fichier *.env* 

```php
/ --
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
# DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=13&charset=utf8"
/ --
```

- Création de la base de donnée sqlite dans le fichier var/data.db

> raccourci pour doctrine:database:create

```bash
$ symfony console d:d:c
```

### Docker (docker et docker-compose doivent déjà être installés)

- Création d'un docker-container qui fera office de serveur mail

> Démarrage des container

```bach
$ docker-compose up -d
```
---

### Création de la gestion des utilisateurs

```bach
symfony console make:user User
```

- migration de la bdd User

```bach
symfony console make:migration
``` 

- application de la migration: 

> raccourci pour doctrine:migration:migrate

```bach
symfony console  d:m:m
``` 

- Création d'une authentification

```bach
symfony console make:auth
> 1
> AppAuthenticator
> [valeur defaut]
> [valeur defaut]
```

- Création d'un formulaire d'enregistrement 

```bach
symfony console  make:registration-form
> [valeur defaut]
> no
> [valeur defaut]
> 11
``` 
---

### Rajouter du style avec Bootstrap CDN

- Ajout des liens css et javascript dans le fichier base.html.twig
- Modification du fichier config/packages/twig.yaml : 

```php
twig:
  / --
  form_themes: ['bootstrap_5_layout.html.twig']
```
---

### Création d'une page d'accueil

```bach
symfony console make:controller HomeController
``` 
- Modification du fichier *src/Security/AppAuthenticator.php*

```php
/ --
// For example: 
return new RedirectResponse($this->urlGenerator->generate('app_home'));

/ --
```
---

### Création d'une entitée pour la demande de jouet

```bash
symfony console make:entity ToyRequest
> user
> relation
> User
ManyToOne   Each ToyRequest relates to (has) one User.
           
            Each User can relate to (can have) many ToyRequest objects
> ManyToOne 
> [valeur defaut]
> [valeur defaut]
> name
> [valeur defaut]
> [valeur defaut]
> [valeur defaut]
> status [le status de la demande qui évoluera dans le workflow ]
> array [plusieurs status en parallèle car plusieurs demande de validation à gérer]
```

- Migration de l'entitée 

```bash
php bin/console make:migration
```

- Appliquer la migration

```bash
php bin/console d:m:m ( ou symfony console d:m:m)
```
---

### Installation du composant workflow

```bash
composer require symfony/workflow
```

- configuration du workflow *config/packages/workflow.yaml*
- créer une représentation graphique du workflow

```bash
php bin/console workflow:dump toy_request | dot -Tpng -o graph.png
```
---

### Création d'un formulaire pour les requêtes faites par l'enfant

```bash
symfony console make:form ToyRequestType 
> ToyRequest
```
---

### Création d'un contôleur pour gérer les demandes

```bash
symfony console make:controller ToyRequestController
```
---

### Mettre en place les notifications par email via MailCatcher 

*src/EventSubscriber/workflowSubscriber.php*

- Lorsqu'il y a une demande de validation (mail au parents)) 
- lorsque la demande à été approuvé par les parents (mail à l'enfant)
- **MailCatcher** est une image docker (voir docker-compose.yaml)
- Utilisation des evenements (Using Event) de Wokflow* 