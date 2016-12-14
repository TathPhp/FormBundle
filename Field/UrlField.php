<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Symfony\Component\Form\Extension\Core\Type\UrlType;

class UrlField extends Field
{
    public static function getType(): string
    {
        return UrlType::class;
    }
}