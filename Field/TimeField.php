<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Tath\Core\Classes\AnnotationTool;

class TimeField extends Field
{
    public static function match(AnnotationTool $annotationTool, $propertyName): bool
    {
        return self::isOrmType($annotationTool, $propertyName, 'time');
    }

    public static function getType(): string
    {
        return TimeType::class;
    }
}
