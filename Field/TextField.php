<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Tath\Core\Classes\AnnotationTool;

class TextField extends Field
{
    public static function match(AnnotationTool $annotationTool, $propertyName): bool
    {
        return true;
    }

    public static function getType(): string
    {
        return TextType::class;
    }
}
