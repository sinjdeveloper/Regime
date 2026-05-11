# Vary'Ena – Application de Régime Alimentaire

Application web développée avec **PHP / CodeIgniter 4** permettant à des utilisateurs de sélectionner un régime alimentaire adapté à leurs objectifs de santé.

---

## Fichier de spécification

Le fichier de spécification du projet se trouve à la racine du dépôt : **`Regime.pdf`**.  
Il décrit le cahier des charges complet (fonctionnalités front-office, back-office, technologies utilisées).

---

## Logique métier principale

### 1. Accès aux détails d'un régime (contrôle d'accès par achat)

Conformément au PDF, les régimes ont un **prix** et doivent être **achetés** avant que l'utilisateur puisse accéder à leur contenu complet.

| État | Contenu affiché |
|------|-----------------|
| Non acheté | Nom, image, prix, message "🔒 Contenu réservé aux acheteurs", bouton **Acheter** |
| Acheté | Nom, image, description complète, variation de poids, répartition alimentaire (viande / poisson / volaille) |

Ce contrôle est appliqué **côté serveur** dans `ProgramController::regime()` : la méthode vérifie l'existence d'un enregistrement dans la table `regimeclient` pour le couple `(client_id, regime_id)` avant de transmettre le drapeau `$isPurchased` à la vue.

### 2. Option Gold

Les utilisateurs peuvent souscrire une option **Gold** (paiement unique).  
Avec l'option Gold, ils bénéficient d'une **réduction de 15 %** sur tous les régimes lors de l'achat.

### 3. Structure d'un programme / suggestion

Selon le PDF : *"L'application suggère les régimes et l'activité sportive nécessaire pendant une durée."*  
Un programme comprend donc **toujours** :
- **Un régime alimentaire** (répartition viande / poisson / volaille, variation de poids, durée)
- **Une activité sportive** complémentaire

Le `SuggestionController` implémente cette logique : chaque suggestion retournée associe un régime et un sport à un objectif de poids et une durée calculée.

---

## Prérequis serveur

PHP 8.2 ou supérieur avec les extensions :

- `intl`
- `mbstring`
- `json` (activé par défaut)
- `mysqlnd` (pour MySQL)
- `libcurl` (pour `HTTP\CURLRequest`)

---

## Installation

```bash
cp env .env
# Configurer la base de données dans .env
composer install
php spark migrate
php spark db:seed DatabaseSeeder
```

Le serveur web doit pointer vers le dossier **`public/`**.

