Squelette projet PHP architecture MVC.
* Initialiser le composer
* Mettre en place l'autoloading PSR 4:
 -     "require": {
        "twig/twig": "^3.0"
    },
    "require-dev": {
        "filp/whoops": "^2.8",
        "friendsofphp/php-cs-fixer": "^2.16",
        "phpstan/phpstan": "^0.12.53"
    },
    "autoload": {
        "psr-4": {
            "App\\" : "src/"
        }
    }
* Dépendances : Twig
* Dépendances --dev :
  - PHP Stan (correction code php)
  - php-cs-fixer (correction code php)
  - filp/whoops (visualisation des erreurs php)