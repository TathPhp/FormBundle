<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Tath\Core\Classes\AnnotationTool;

class NumberField extends Field
{
    public static function match(AnnotationTool $annotationTool, $propertyName): bool
    {
        return self::isOrmType($annotationTool, $propertyName, 'decimal');
    }

    public static function getType(): string
    {
        return NumberType::class;
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();
        $ormColumn = $this->getOrmColumn();
        $options['scale'] = $ormColumn->scale;
        return $options;
    }
}
