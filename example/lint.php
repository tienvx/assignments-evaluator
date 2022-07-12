<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Tienvx\AssignmentsEvaluator\AssignmentsEvaluator;

$assignmentsEvaluator = new AssignmentsEvaluator(new ExpressionLanguage());

$assignmentsEvaluator->lint('title = "Dr."; firstName = "zane"; lastName = "stroman"; name = title~ucfirst(firstName)~" "~ucfirst(lastName)');
