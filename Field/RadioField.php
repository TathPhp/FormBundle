<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Tath\Core\Classes\AnnotationTool;

class RadioField extends ChoiceField
{
    public static function match(AnnotationTool $annotationTool, $propertyName): bool
    {
        return false;
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();
        $options['expanded'] = true;
        $options['multiple'] = false;
        return $options;
    }
}
