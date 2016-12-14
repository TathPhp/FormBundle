<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Tath\Core\Classes\AnnotationTool;
use Tath\FormBundle\Configuration\FormField;

class ChoiceField extends Field
{
    public static function match(AnnotationTool $annotationTool, $propertyName): bool
    {
        $annotations = $annotationTool->getTypedProperties(FormField::class, $propertyName);
        $annotation = reset($annotations);
        return (!is_null($annotation) && ($annotation instanceof FormField) && (count($annotation->choices) > 0));
    }

    public static function getType(): string
    {
        return ChoiceType::class;
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();
        if (isset($this->formField->choices)) {
            $options['choices'] = $this->formField->choices;
        }
        return $options;
    }
}
