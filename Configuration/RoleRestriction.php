<?php
namespace Tath\FormBundle\Configuration;

use Tath\Core\Classes\Collection;

/**
 * @Annotation
 */
class RoleRestriction
{
    public $visible = [];

    public $edit = [];

    public function appliesTo(array $roles, string $type)
    {
        $target = $this->$type;
        return Collection::make($roles)
            ->intersect($target)
            ->isEmpty();
    }
}
