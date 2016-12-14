<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\RangeType;

class RangeField extends Field
{
    public static function getType(): string
    {
        return RangeType::class;
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();
        if (isset($this->formField->min)) {
            $options['attr']['min'] = $this->formField->min;
        }
        if (isset($this->formField->max)) {
            $options['attr']['max'] = $this->formField->max;
        }
        return $options;
    }
}
