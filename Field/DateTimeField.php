<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Tath\Core\Classes\AnnotationTool;

class DateTimeField extends Field
{
    public static function match(AnnotationTool $annotationTool, $propertyName): bool
    {
        return self::isOrmType($annotationTool, $propertyName, 'datetime');
    }

    public static function getType(): string
    {
        return DateTimeType::class;
    }
}
