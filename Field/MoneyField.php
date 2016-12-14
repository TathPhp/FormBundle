<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class MoneyField extends Field
{
    public static function getType(): string
    {
        return MoneyType::class;
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();
        if (isset($this->formField->currency)) {
            $options['currency'] = $this->formField->currency;
        }
        return $options;
    }
}
