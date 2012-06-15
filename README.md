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
configuration like this

```yaml
dbal:
    dbname: mydatabase
    user: my_db_user
    password: secret_pass
    host: localhost
    driver: pdo_mysql
```

* And you are good to go now.

## Usage

As this is a first version there are some limitation for now, those are:

* You cannot use another metadata format other than Annotations.
* There is no way to tell the extension what autoloading mechanism to use, for now,
it just registers the Doctrine namespace using the manifest.

Well, if the limitations above are not problem for you or you know how to dodge them,
then lets continue.

>>>> Work in progress <<<<<