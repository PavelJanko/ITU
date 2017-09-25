ITU Projekt 2017/18 - Webové rozhraní pro správu publikací
------
### Zadání:
Vytvořte webové rozhraní pro správu publikací (dokumentový sklad), umožňující oprávněným osobám správu uživatelů a skupin, vkládání a správu dokumentů a správu komentářů k publikacím. Uživatel by měl mít možnost vyhledávat publikace dle klíčových slov, procházet publikace, zobrazovat abstrakt.
Zaměřte se a navrhněte inovativní způsob interakce. Toto řešení implementujte a otestujte (intuitivnost, efektivitu) srovnáním se standarním přístupem. Realizujte s využitím technologií HTML/CSS/MySQL/PHP/Javascript/AJAX/jQuery.

### Použité technologie:
* [Laravel](https://laravel.com/)
* [Bootstrap](https://getbootstrap.com/)
* [Vue.js](https://vuejs.org/)

### Požadavky:
* PHP >= 7.0
* OpenSSL PHP rozšíření
* PDO PHP rozšíření
* Mbstring PHP rozšíření
* Tokenizer PHP rozšíření
* XML PHP rozšíření

### Postup instalace:
Ve **složce s projektem** proveďte:
```bash
$ cp .env.example .env
# Poté soubor .env upravit podle prostředí
$ composer install
$ php artisan key:generate
$ php artisan migrate --seed
$ php artisan storage:link
$ yarn
```
