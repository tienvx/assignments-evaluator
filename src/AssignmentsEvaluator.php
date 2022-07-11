<?php

namespace Tienvx\AssignmentsEvaluator;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError as ExpressionLanguageSyntaxError;

class AssignmentsEvaluator
{
    protected ExpressionLanguage $expresionLanguage;

    public function __construct(ExpressionLanguage $expresionLanguage)
    {
        $this->expresionLanguage = $expresionLanguage;
    }

    public function evaluate(string $assignmentExpression, array $values = []): array
    {
        foreach (explode(';', $assignmentExpression) as $index => $assignment) {
            list($variable, $expression) = $this->splitAssignment($assignment, $index);
            $key = $this->expresionLanguage->evaluate($variable, [$variable => $variable]);
            $values[$key] = $this->expresionLanguage->evaluate($expression, $values);
        }

        return $values;
    }

    public function lint($assignmentExpression, array $names = []): void
    {
        foreach (explode(';', $assignmentExpression) as $index => $assignment) {
            list($variable, $expression) = $this->splitAssignment($assignment, $index);
            try {
                $this->expresionLanguage->lint($variable, [$variable]);
            } catch (ExpressionLanguageSyntaxError $th) {
                throw new SyntaxError(sprintf('Variable "%s" is invalid.', $variable));
            }
            try {
                $this->expresionLanguage->lint($expression, $names);
            } catch (ExpressionLanguageSyntaxError $th) {
                throw new SyntaxError(sprintf('Expression "%s" is invalid: %s.', $expression, $th->getMessage()));
            }
            if (!in_array($variable, $names, true)) {
                $names[] = $variable;
            }
        }
    }

    protected function splitAssignment(string $assignment, int $index): array
    {
        if (empty($assignment)) {
            throw new SyntaxError(sprintf('Assignment at index "%d" is empty.', $index));
        }
        $strings = explode('=', $assignment);
        if (count($strings) !== 2) {
            throw new SyntaxError(sprintf('Assignment "%s" is invalid, "var = expression" is expected.', $assignment));
        }

        return $strings;
    }
}
