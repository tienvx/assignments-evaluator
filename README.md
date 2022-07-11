# Expression Language Assignments [![Build Status][actions_badge]][actions_link] [![Coverage Status][coveralls_badge]][coveralls_link] [![Version][version-image]][version-url] [![PHP Version][php-version-image]][php-version-url]

[Symfony Expression Language][expression-language] only return single value per expression.
It does not support assignment. This library allow assigning result of that expression's evaluation to a variable. It also support multiple assignments.

## Installation

```shell
composer require tienvx/assignments-evaluator
```

## Documentation

```php
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Tienvx\AssignmentsEvaluator\AssignmentsEvaluator;

class Robot
{
    public function sayHi(string $name): string
    {
        return sprintf('Hi %s!', $name);
    }
}

$assignmentsEvaluator = new AssignmentsEvaluator(new ExpressionLanguage());

var_dump($assignmentsEvaluator->evaluate(
    'fullName = firstName~" "~lastName; hello = robot.sayHi(fullName)',
    [
        'firstName' => 'Madonna',
        'lastName' => 'Jenkins',
        'robot' => new Robot(),
    ]
));
/* displays
array(5) {
  ["firstName"]=>
  string(7) "Madonna"
  ["lastName"]=>
  string(7) "Jenkins"
  ["robot"]=>
  object(Robot)#8 (0) {
  }
  ["fullName"]=>
  string(15) "Madonna Jenkins"
  ["hello"]=>
  string(19) "Hi Madonna Jenkins!"
}
*/
```

## License

[MIT](https://github.com/tienvx/assignments-evaluator/blob/main/LICENSE)

[actions_badge]: https://github.com/tienvx/assignments-evaluator/workflows/main/badge.svg
[actions_link]: https://github.com/tienvx/assignments-evaluator/actions

[coveralls_badge]: https://coveralls.io/repos/tienvx/assignments-evaluator/badge.svg?branch=main&service=github
[coveralls_link]: https://coveralls.io/github/tienvx/assignments-evaluator?branch=main

[version-url]: https://packagist.org/packages/tienvx/assignments-evaluator
[version-image]: http://img.shields.io/packagist/v/tienvx/assignments-evaluator.svg?style=flat

[php-version-url]: https://packagist.org/packages/tienvx/assignments-evaluator
[php-version-image]: http://img.shields.io/badge/php-7.4.0+-ff69b4.svg

[expression-language]: https://symfony.com/doc/current/components/expression_language.html
