# Ecommerce Symfony

Pour récupèrer le projet :

```bash
git clone ...
cd projet
composer install
```

S'il y a un blocage au niveau du `composer install` à cause de la version de PHP :

```bash
composer update
```

Pour configurer la base de données, on n'oublie pas de créer un fichier `.env.local` avec les lignes suivantes :

```bash
DATABASE_URL=...
```

Attention de bien créer la BDD :

```bash
php bin/console doctrine:database:create
```

Et aussi, il faut synchroniser la BDD :

```bash
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

## La partie produits

On va créer une entité Product :

- Id (déjà fait par Symfony)
- Nom (Le produit) => name
- Slug (le-produit) => slug
- Description (Text) => description
- Prix (decimal (10, 2)) => price
- Date de création => createdAt
- Coup de coeur (Boolean) => liked
- Image du produit (peut être null) => image
- Promotion (entier en pourcentage, peut être null) => promotion

Ne pas oublier de créer les migrations et d'appliquer les migrations.

Quand vous avez fini l'entité, on va créer une nouvelle page `/product/create` :

- On se concentre juste sur le formulaire (Il faudra créer un Type)
- Au niveau de la validation :
    - le nom devra faire au moins 3 caractères
    - la description au moins 10 caractères
    - Le prix doit être situé entre 99,00 et 999,99
    - La date de création doit être supérieure ou égale à la date du jour
    - Le coup de coeur doit être un booléen
    - La promotion peut être nulle ou située entre 1 et 100
- Bien sûr, quand le formulaire est terminé, on persiste le produit dans la BDD.

En BONUS, on pourra générer le slug avant de persister l'objet dans la BDD grâce au composant String de Symfony.

## Le ManyToMany

On va créer une entité Color (name). L'entité sera liée à Product par une relation ManyToMany.
On n'oublie pas les migrations.
On modifiera nos fixtures pour créer 5 couleurs. On devra associer plusieurs couleurs à chaque produit :

```php
$blue = new Color();
$blue->setName('Bleu');
$red = new Color();
$red->setName('Rouge');

$product->addColor($blue)->addColor($red);
```

Si on peut, on le fait en "dynamique".
On affichera les couleurs liés au produit en BDD sur la fiche produit.

```twig
{% for color in product.colors %}
    {{ color.name }}
{% endfor %}
```
