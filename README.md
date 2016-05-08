# PandawanTechnology Neo4j Fixtures Bundle
Symfony bundle to allow fixtures loading into your Neo4j graph database

## Installation
Add the bundle as a requirement:
```bash
composer require pandawan-technology/neo4j-fixtures-bundle
```

Enable it in your `app/AppKernel.php` file:
```php
<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ... my bundles
            new PandawanTechnology\Neo4jFixturesBundle\PandawanTechnologyNeo4jFixturesBundle(),
        ];
        
        return $bundles;
    }
}
```

## Usage
### Basic
By default, the bundle will search for the `/DataFixtures/Neo4j` directories within your registered bundles:
```php
<?php
// src/AppBundle/DataFixtures/Neo4j/LoadUserData.php
namespace AppBundle\DataFixtures\Neo4j;

use GraphAware\Common\Connection\ConnectionInterface;
use PandawanTechnology\Neo4jDataFixtures\FixtureInterface;

class LoadUserData implements FixtureInterface
{
    /**
     * @inheritDoc
     */
    public function load(ConnectionInterface $connection)
    {
        // ... statements here
    }
}
```
See [Pandawan Technology Neo4j Data Fixtures' library documentation](https://github.com/PandawanTechnology/neo4j-data-fixtures/blob/master/README.md) for more informations.

### Advanced
If you need to use third party services from your container, you can implements the `ContainerAwareInterface`:
```php
<?php
// src/AppBundle/DataFixtures/Neo4j/LoadUserData.php
namespace AppBundle\DataFixtures\Neo4j;

use GraphAware\Common\Connection\ConnectionInterface;
use PandawanTechnology\Neo4jDataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    private $container;
    
    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * @inheritDoc
     */
    public function load(ConnectionInterface $connection)
    {
        // ... statements here
    }
}
```

