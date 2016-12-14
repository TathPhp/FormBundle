<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\CountryType;

class CountryField extends Field
{
    public static function getType(): string
    {
        return CountryType::class;
    }
}