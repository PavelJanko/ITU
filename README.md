ITU Projekt 2017/18 - Webové rozhraní pro správu publikací
------
### Zadání:
Vytvořte webové rozhraní pro správu publikací (dokumentový sklad), umožňující oprávněným osobám správu uživatelů a skupin, vkládání a správu dokumentů a správu komentářů k publikacím. Uživatel by měl mít možnost vyhledávat publikace dle klíčových slov, procházet publikace, zobrazovat abstrakt.
Zaměřte se a navrhněte inovativní způsob interakce. Toto řešení implementujte a otestujte (intuitivnost, efektivitu) srovnáním se standarním přístupem. Realizujte s využitím technologií HTML/CSS/MySQL/PHP/Javascript/AJAX/jQuery.

### Použité technologie:
* [Laravel](https://laravel.com/)
* [Bootstrap](https://getbootstrap.com/)
* [jQuery](https://jquery.com/)
* [Vue.js](https://vuejs.org/)

### Požadavky:
* PHP >= 7.0
* OpenSSL PHP rozšíření
* PDO PHP rozšíření
* Mbstring PHP rozšíření
* Tokenizer PHP rozšíření
* XML PHP rozšíření
* **Pro instalaci:**
  * [Composer](https://getcomposer.org/) - back-end závislosti
  * [Node.js](https://nodejs.org/en/) nebo [Yarn](https://yarnpkg.com/lang/en/) - front-end závislosti
* **Poznámka:**
  * Je zapotřebí nakonfigurovat databázi pro persistenci uživatelů a dalších klíčových dat - při vývoji jsme používali [MariaDB](https://mariadb.org/), ale Laravel podporuje mnoho dalších, jako [PostgreSQL](https://www.postgresql.org/), [SQLite](https://www.sqlite.org/), a další.

### Postup instalace:
Ve **složce s projektem** proveďte:
```bash
# Zkopírovat soubor prostředí a následně jej upravit
$ cp .env.example .env
# Stažení back-end závislostí
$ composer install
# Vygenerování klíče použitého pro šifrování například uživatelských hesel
$ php artisan key:generate
# Migrace a populace databáze testovacími daty
$ php artisan migrate --seed
# Vytvoření symbolického odkazu pro uložiště
$ php artisan storage:link
# Stažení front-end závislostí
$ yarn
# Kompilace front-end závislostí
$ yarn run development
```