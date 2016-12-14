<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Tath\Core\Classes\AnnotationTool;

class IntegerField extends Field
{
    public static function match(AnnotationTool $annotationTool, $propertyName): bool
    {
        return self::isOrmType($annotationTool, $propertyName, 'integer');
    }

    public static function getType(): string
    {
        return IntegerType::class;
    }
}
