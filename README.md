# Projet EcoIT 

# Description

Dépôt du projet EcoIT

Les documents annexes sont disponibles dans le dossier ANNEXES : 

- Charte graphique 
- Manuel d'utilisation 
- Documentation technique 

# Recupération 

Utiliser GIT Clone pour récupérer le dépôt

```bash
https://github.com/gdefa/formation_eco.git
```


## Installation


```bash
  # Déplacement dans le dossier
cd mediatheque

# Installation des dépendances
composer install

# Création de la base de données
php bin/console doctrine:database:create

# Création des tables (migrations)
php bin/console doctrine:migrations:migrate

```
## Utilisation

Deux options pour lancer le serveur de développement PHP :

- Si vous avez installé Symfony

``` bash
  symfony server:start
```
- Si vous utilisez Composer, il faut installer le Web Server Bundle :

``` bash
composer require symfony/web-server-bundle --dev
php bin/console server:start
```

## Utilisateur 

Administrateur 
```bash
Email = defa7@live.fr
mot de passe = guillaume
```

Instructeur 
```bash
Email = instructeur@email.com
Mot de passe = instructeur
```

Instructeur non validé
```bash
Email = instructeurnotvalid@email.com
Mot de passe = instrcuteur
```

Apprenant
```bash
Email = apprenant@email.com 
Mot de passe = apprenant
```

## Annexes

La charte graphique, documentation technique et le manuel d'utilisation sont a retrouvez [ici](https://drive.google.com/drive/folders/1LZYU7eOY9Coy8IrXiWNaeVEoQIEWU6Zg?usp=sharing) (Google Drive)
