# LyssalMonnaieBundle

LyssalMonnaieBundle permet la manipulation de monnaies.


## Entités

Toutes les entités possèdent leur manager et leur gestion administrative (optionnelle) si vous utilisez Sonata.

Les entités sont :
* Monnaie

## Utilisation

Vous devez créer un bundle héritant `LyssalMonnaieBundle` :

```php
namespace Acme\MonnaieBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeMonnaieBundle extends Bundle
{
    public function getParent()
    {
        return 'LyssalMonnaieBundle';
    }
}
```

Ensuite, vous devez créer dans votre bundle les entités héritant celles de `LyssalMonnaieBundle` et redéfinir certaines propriétés :

```php
namespace Acme\MonnaieBundle\Entity;

use Lyssal\MonnaieBundle\Entity\Monnaie as BaseMonnaie;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Monnaie.
 * 
 * @ORM\Entity()
 * @ORM\Table
 * (
 *     name="acme_pays",
 *     uniqueConstraints=
 *     {
 *         @UniqueConstraint(name="CODE", columns={ "monnaie_code" }),
 *         @UniqueConstraint(name="SYMBOLE", columns={ "monnaie_symbole" })
 *     }
 * )
 */
class Monnaie extends BaseMonnaie
{
    
}
```

Vous devez ensuite redéfinir les paramètres suivants :

* `lyssal.monnaie.entity.monnaie.class` : Acme\MonnaieBundle\Entity\Monnaie

Exemple avec sur `Acme/MonnaieBundle/Resources/config/services.xml` :

```xml
<?xml version="1.0" ?>
<container xmlns="http://symfony.com/schema/dic/services" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    <parameters>
        <parameter key="lyssal.monnaie.entity.monnaie.class">Acme\MonnaieBundle\Entity\Monnaie</parameter>
    </parameters>
</container>
```

## Managers

Les services sont :
* lyssal.monnaie.manager.monnaie

### Exemple d'utilisation

Dans votre contrôleur :

```php
$monnaies = $this->container->get('lyssal.monnaie.manager.monnaie')->findAll();
```

### Utiliser vos managers hérités de LyssalGeographieBundle

Si vous utilisez vos propres managers héritant des managers de `LyssalMonnaieBundle`, vous pouvez redéfinir les paramètres suivants :
* `lyssal.monnaie.manager.monnaie.class`

Exemple en XML :
```xml
<parameters>
    <parameter key="lyssal.monnaie.manager.monnaie.class">Acme\MonnaieBundle\Manager\MonnaieManager</parameter>
</parameters>
```

## SonataAdmin

Les entités seront automatiquement intégrées à `SonataAdmin` si vous l'avez installé.

Si vous souhaitez redéfinir les classes `Admin`, il suffit de surcharger les paramètres suivants :
* `lyssal.monnaie.admin.monnaie.class`


## Installation


1. Mettez à jour votre `composer.json` :
```json
"require": {
    "lyssal/monnaie-bundle": "*"
}
```
2. Installez le bundle :
```sh
php composer.phar update
```
3. Mettez à jour `AppKernel.php` :
```php
new Lyssal\StructureBundle\LyssalStructureBundle(),
new Lyssal\MonnaieBundle\LyssalMonnaieBundle(),
new Acme\MonnaieBundle\AcmeMonnaieBundle(),
```
4. Créez les tables en base de données :
```sh
php app/console doctrine:schema:update --force
```

## Commandes

### Importer des données

Vide et importe des données :
```sh
lyssal:monnaie:database:import
```

Attention : Les tables seront automatiquement vidées lors de l'appel de cette commande.

Le remplissage de la base concerne :

* Ajout d'un jeu de données avec des monnaies

