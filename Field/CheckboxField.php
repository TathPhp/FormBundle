<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Tath\Core\Classes\AnnotationTool;

class CheckboxField extends Field
{
    public static function match(AnnotationTool $annotationTool, $propertyName): bool
    {
        return self::isOrmType($annotationTool, $propertyName, 'boolean');
    }

    public static function getType(): string
    {
        return CheckboxType::class;
    }
}
