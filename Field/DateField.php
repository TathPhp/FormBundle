<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Tath\Core\Classes\AnnotationTool;

class DateField extends Field
{
    public static function match(AnnotationTool $annotationTool, $propertyName): bool
    {
        return self::isOrmType($annotationTool, $propertyName, 'date');
    }

    public static function getType(): string
    {
        return DateType::class;
    }
}
