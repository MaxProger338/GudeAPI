# GudeAPI

GudeAPI - это простая библиотека на PHP, для создания WEB-API

- Можно быстро создать простой WEB-API

- Написана на чистом PHP (v8.3.10)

- Большая скорость работы

```php
use GudeAPI\GudeAPI;
use GudeAPI\RequestType;
use GudeAPI\HTTPMethod;

// Инициализируем наш API, в режиме PATH и идентификатором posts/langs
$api = new GudeAPI(
    RequestType::PATH("posts/langs")
);

// Проверяем совпадают пути
$api->PATH(HTTPMethod::GET, "{:lang:}/functions/{|*|}", function(array $properties) {
    // Если всё ок
    echo "Category: " . "functions" . PHP_EOL;
    echo "Language: " . $properties["DynamicElements"]["lang"];
});

$api->PATH(HTTPMethod::GET, "{:lang:}/classes/{|*|}", function(array $properties) {
    echo "Category: " . "classes" . PHP_EOL;
    echo "Language: " . $properties["DynamicElements"]["lang"];
});
```

Файл .htaccess

```.htaccess
RewriteEngine On
RewriteCond %{REQUEST_FILLNAME} !-f
RewriteRule ^(.+)$ api.php?q=$1 [L,QSA]
```

## Установка GudeAPI

Установка через __[Composer](https://getcomposer.org/). (рекумендуется)__

```bash
composer require maxproger338/gudeapi
```

## Документация

Смотри в файле __[Document File](docs/DOCUMENTATION)__

## Планы на будущие

* Будут добавлены такие режимы работы как:
    + *__PARAMS__* - можно будет работать не с путём запрома, а с его параметрами!
    + *__PATH_AND_PARAMS__* - комбинация режимов PATH и PARAMS

* Будут добавлены функции для минимальной работы с базой данных MySQL

* Появться функции для изменение заголовков запрома, таких как:
    + *__Content-type__*

## Авторы 

Мой Github - __[https://github.com/MaxProger338](https://github.com/MaxProger338)__

E-mail - __maxproger0309@mail.ru__

## Благодарноси

Можете просто подписаться на мой __GitHub__!

Буду рад!


## License

GudeAPI доступен по лицензии MIT (MIT). Пожалуйста, смотрите файл лицензии __[License File](LICENSE)__ для получения дополнительной информации.

