<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;

AnnotationRegistry::registerFile(__DIR__ . "/../Configuration/FormField.php");
AnnotationRegistry::registerFile(__DIR__ . "/../Configuration/RoleRestriction.php");
