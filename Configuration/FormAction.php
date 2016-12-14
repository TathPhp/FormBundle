<?php
namespace Tath\FormBundle\Configuration;

use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class FormAction implements Annotation
{
    public $label = 'Save';
}