# BGF:ES, the web platform
This site will make signing up to the tournaments easier.

##Installation
After downloading the project, don't forget to

```bash
composer update
```


If you didn't had the database already :

```bash
php app/console doctrine:database:create
```


Then, just to be sure :

```bash
php app/console doctrine:schema:drop --force
php app/console doctrine:schema:create
```

##Fixtures
Fixtures are default data loaded in the database. If you want it you just have to do :

```bash
php app/console doctrine:fixtures:load -n
```
