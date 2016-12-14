<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class BirthdayField extends Field
{
    public static function getType(): string
    {
        return BirthdayType::class;
    }
}
