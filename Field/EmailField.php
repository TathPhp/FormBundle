<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Tath\Core\Classes\AnnotationTool;

class EmailField extends Field
{
    public static function match(AnnotationTool $annotationTool, $propertyName): bool
    {
        return self::isFieldType($annotationTool, $propertyName, 'email');
    }

    public static function getType(): string
    {
        return EmailType::class;
    }
}
