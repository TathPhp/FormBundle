<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\TimezoneType;

class TimezoneField extends Field
{
    public static function getType(): string
    {
        return TimezoneType::class;
    }
}