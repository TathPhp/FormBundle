<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Tath\Core\Classes\AnnotationTool;

class FileField extends Field
{
    public static function match(AnnotationTool $annotationTool, $propertyName): bool
    {
        return self::isOrmType($annotationTool, $propertyName, 'blob');
    }

    public static function getType(): string
    {
        return FileType::class;
    }
}
