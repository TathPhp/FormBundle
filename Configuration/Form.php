<?php

namespace Tath\FormBundle\Configuration;

use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Form implements Annotation
{
    public $template;
    public $success_redirect;
}