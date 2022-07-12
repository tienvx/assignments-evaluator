<?php

namespace Tienvx\AssignmentsEvaluator\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Tienvx\AssignmentsEvaluator\AssignmentsEvaluator;
use Tienvx\AssignmentsEvaluator\SyntaxError;

/**
 * @covers \Tienvx\AssignmentsEvaluator\AssignmentsEvaluator
 *
 * @uses \Tienvx\AssignmentsEvaluator\SyntaxError
 */
class AssignmentsEvaluatorTest extends TestCase
{
    protected AssignmentsEvaluator $assignmentsEvaluator;

    protected function setUp(): void
    {
        $this->assignmentsEvaluator = new AssignmentsEvaluator(new ExpressionLanguage());
    }

    /**
     * @dataProvider validExpressionsProvider
     * @testdox Evaluating valid expression $expression
     */
    public function testEvaluateValidExpression(string $expression, array $values, array $expected): void
    {
        $this->assertSame($expected, $this->assignmentsEvaluator->evaluate($expression, $values));
    }

    /**
     * @dataProvider invalidExpressionsProvider
     * @testdox Evaluating invalid expression $expression
     */
    public function testEvaluateInvalidExpression(string $expression, string $message): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage($message);
        $this->assignmentsEvaluator->evaluate($expression);
    }

    /**
     * @dataProvider invalidExpressionsProvider
     * @testdox Linting invalid expression $expression
     */
    public function testLintInvalidExpression(string $expression, string $message): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage($message);
        $this->assignmentsEvaluator->lint($expression);
    }

    /**
     * @dataProvider validExpressionsProvider
     * @testdox Linting valid expression $expression with allowed variables
     */
    public function testLintValidExpressionWithAllowedVariables(string $expression, array $values): void
    {
        $this->expectNotToPerformAssertions();
        $this->assignmentsEvaluator->lint($expression, array_keys($values));
    }

    /**
     * @dataProvider validExpressionsProvider
     * @testdox Linting valid expression $expression without allowed variables
     */
    public function testLintValidExpressionWithoutAllowedVariables(string $expression): void
    {
        $this->expectNotToPerformAssertions();
        $this->assignmentsEvaluator->lint($expression);
    }

    public function validExpressionsProvider(): array
    {
        $date = new \DateTime('2013-04-12');

        return [
            [
                'year = date.modify("+3 years").format("Y"); ' .
                'month = date.format("M"); ' .
                'day = date.format("j"); dateString = day~" "~month~" "~year',
                [
                    'date' => $date,
                ],
                [
                    'date' => $date,
                    'year' => '2016',
                    'month' => 'Apr',
                    'day' => '12',
                    'dateString' => '12 Apr 2016',
                ],
            ],
            [
                'version = "1.0.0-rc.1"; isStable = not(version matches "/rc|beta|alpha/")',
                [],
                [
                    'version' => '1.0.0-rc.1',
                    'isStable' => false,
                ],
            ],
            [
                'isEmailValid = email === "norval.strosin@hotmail.com" ; ' .
                'isPasswordValid = pass == "12345" ; ' .
                'isCredentialsValid = isEmailValid and isPasswordValid',
                [
                    'email' => 'boehm.elbert@gmail.com',
                    'pass' => 12345,
                ],
                [
                    'email' => 'boehm.elbert@gmail.com',
                    'pass' => 12345,
                    'isEmailValid' => false,
                    'isPasswordValid' => true,
                    'isCredentialsValid' => false,
                ],
            ],
        ];
    }

    public function invalidExpressionsProvider(): array
    {
        return [
            ['', 'Assignment at index "0" is empty.'],
            [';', 'Assignment at index "0" is empty.'],
            ['=;', 'Variable in assignment "=" is empty.'],
            ['=;=', 'Variable in assignment "=" is empty.'],
            ['var', 'Assignment "var" is invalid, expected "variable = expression".'],
            ['= right', 'Variable in assignment "= right" is empty.'],
            ['left =', 'Expression in assignment "left =" is empty.'],
            [
                'age == 37',
                'Expression "= 37" is invalid: Unexpected character "=" around position 0 for expression `= 37`..',
            ],
            [
                'browser === "firefox"',
                'Expression "== "firefox"" is invalid: ' .
                'Unexpected token "operator" of value "==" around position 1 for expression `== "firefox"`..',
            ],
            ['email = "labadie.christina@hotmail.com" ;', 'Assignment at index "1" is empty.'],
            [';address = "http://example.com"', 'Assignment at index "0" is empty.'],
            ['result += 2', 'Variable "result +" is invalid.'],
            ['result = 1 ;; result = result + 1', 'Assignment at index "1" is empty.'],
        ];
    }
}
