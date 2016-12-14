<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\LanguageType;

class LanguageField extends Field
{
    public static function getType(): string
    {
        return LanguageType::class;
    }
}