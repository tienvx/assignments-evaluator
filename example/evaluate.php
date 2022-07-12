<?php

require_once __DIR__ . '/../vendor/autoload.php';

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
