*\- To my dear brother Carlos, whose memories will live in those who loved him*

DoctrineDataAccess
==================

Hello, this is the doctrine extension for Hirudo, is intended to provide database
access from Hirudo, allowing you to store your neat objects into the database.

## Installation

* Download the extension from this repository.
* Unzip
* Put the DoctrineDataAccess folder into tour Hirudo under the ```ext/libs/```
directory.
* That's all.

## Configuration

* Go to your config file at ```ext/config/Config.yml``` and add your connection 
configuration like this:

```yaml
dbal:
    dbname: mydatabase
    user: my_db_user
    password: secret_pass
    host: localhost
    driver: pdo_mysql
```

You can find more information about dbal configuration [here](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html)

* And you are good to go now.

## Usage

As this is a first version, there are some limitation for now:

* You cannot use a metadata format other than Annotations.
* There is no way to tell the extension what autoloading mechanism to use, for now,
it just registers the Doctrine's namespace using the manifest.
* You can not tell the extension to look for entities un places other than your
applications' models.

Well, if the limitations above are not problem for you or you know how to dodge them,
then lets continue.

Now that you have installed and configured the doctrine's integration is time for code. 

### Entities

In order to persist your entities, you just need to configure them with the appopiate
annotation, here is an example:

```php
<?php
namespace MyApp\Models\Entities;

/** @Entity */
class MyPersistentClass
{
    /** @Column(type="integer") */
    private $id;
    /** @Column(length=50) */
    private $name; // type defaults to string
    //...
}
?>
```

#### Whatch out! there are considerations

In order to get your entities detected by doctrine, those must be located in the
entities folder of your applications, that is, if you have an application named MyApp
your entities must be in the MyApp/Models/Entities folder and must be under the 
```MyApp\Models\Entities``` namespace.

To know more about entities configuration look at [here](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/basic-mapping.html) 
and [here](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/annotations-reference.html).


### Components

DoctrineDataAccess provides you with a base class to create components (the
```DoctrineDataAccess\Models\Components\DoctrineComponent``` class), it provides
to its child classes an entity manager from which you can work with your persistence.

Here is an example of a component class:

```php
<?php
namespace MyApp\Models\Components;
use DoctrineDataAccess\Models\Components\DoctrineComponent;

class MyPersistentClassComponent extends DoctrineComponent {

    public function getAll() {
        // Note the use of the fully qualified class name.
        return $this->em->getRepository("MyApp\Models\Entities\MyPersistentClass")->findAll();
    }

    public function getById($id) {
        // The shorter way, this extension automatically adds an alias for each application
        // That allows you to refer to your entities with the AppName:EntityName notation.
        return $this->em->getRepository("MyApp:MyPersistentClass")->find($id);
    }

}
?>
```

Is that simple to create a Hirudo component which uses doctrine, note that the component
should be under the MyApp/Models/Components folder in order to be accesible from
your modules. 

### Modules

Well, this is the part where we actually do something, to access to your doctrine
component just get it the same way you would do with any other component types:

```php
namespace MyApp\Modules\HelloDoctrine;
use Hirudo\Core\Module;

class HelloDoctrine extends Module {

    public function index() {
        $myentities = $this->component("MyPersistentClass")->getAll();
        //Do something with $myentities
    }

    public function byId($id) {
        $myentity = $this->component("MyPersistentClass")->getById($id);
        //Do something with $myentity
    }
}
```

Or, if you like the autocomplete too much, you can simply declare a dependency 
over the component.

```php
namespace MyApp\Modules\HelloDoctrine;
use Hirudo\Core\Module;
use Hirudo\Core\Annotations\Import; //<-- Always use the 'use' statement with annotations!

class HelloDoctrine extends Module {

    /**
    *
    * @var MyApp\Models\Components\MyPersistentClassComponent
    *
    * @Import(className="MyApp\Models\Components\MyPersistentClassComponent")
    */
    private $myComponent;

    public function index() {
        //Yay with auto complete!
        $myentities = $this->myComponent->getAll();
        //Do something with $myentities
    }

    public function byId($id) {
        $myentity = $this->myComponent->getById($id);
        //Do something with $myentity
    }
}
```

