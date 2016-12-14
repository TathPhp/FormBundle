<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\LocaleType;

class LocaleField extends Field
{
    public static function getType(): string
    {
        return LocaleType::class;
    }
}