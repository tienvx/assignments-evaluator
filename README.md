# Expression Language Assignments [![Build Status][actions_badge]][actions_link] [![Coverage Status][coveralls_badge]][coveralls_link] [![Version][version-image]][version-url] [![PHP Version][php-version-image]][php-version-url]

[Symfony Expression Language][expression-language] only return single value per expression.
It does not support assignment. This library add assignments support by allowing assign a value/expression to a variable for each assignment.

## Installation

```shell
composer require tienvx/expression-language-assignments
```

## Documentation

```php
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

$expressionLanguage = new ExpressionLanguage();

var_dump($expressionLanguage->evaluate(
    'fullName = firstName~" "~lastName; fileType = extension in ["apng", "avif", "jpg", "jpeg", "png", "gif", "webp", "bmp", "svg", "ico", "tif", "tiff"] ? "image", "other"; isStable = not(version matches "rc|beta|alpha")',
    [
        'firstName' => 'Madonna',
        'lastName' => 'Jenkins',
        'extension' => 'webm',
        'version' => '1.0.0-rc.1',
    ]
)); // displays "Honeycrisp"
```

## License

[MIT](https://github.com/tienvx/expression-language-assignments/blob/main/LICENSE)

[actions_badge]: https://github.com/tienvx/expression-language-assignments/workflows/main/badge.svg
[actions_link]: https://github.com/tienvx/expression-language-assignments/actions

[coveralls_badge]: https://coveralls.io/repos/tienvx/expression-language-assignments/badge.svg?branch=main&service=github
[coveralls_link]: https://coveralls.io/github/tienvx/expression-language-assignments?branch=main

[version-url]: https://packagist.org/packages/tienvx/expression-language-assignments
[version-image]: http://img.shields.io/packagist/v/tienvx/expression-language-assignments.svg?style=flat

[php-version-url]: https://packagist.org/packages/tienvx/expression-language-assignments
[php-version-image]: http://img.shields.io/badge/php-7.4.0+-ff69b4.svg

[expression-language]: https://symfony.com/doc/current/components/expression_language.html
