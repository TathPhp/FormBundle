<?php
namespace Tath\FormBundle\Configuration;

use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class FormField implements Annotation
{
    public $label;

    public $type;

    public $choices = [];

    /**
     * @var array
     * var \Tath\Form\Configuration\RoleRestriction
     */
    public $restrict = [];

    /**
     * @var integer
     */
    public $min;

    /**
     * @var integer
     */
    public $max;

    public $currency;
}
