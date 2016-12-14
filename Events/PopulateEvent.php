<?php
declare(strict_types=1);

namespace Tath\FormBundle\Events;

use Symfony\Component\EventDispatcher\Event;
use Tath\FormBundle\Classes\FormPopulator;

class PopulateEvent extends Event
{
    const NAME = 'tath.form.populate';

    private $populator;

    /**
     * PopulateEvent constructor.
     * @param $populator
     */
    public function __construct(FormPopulator $populator)
    {
        $this->populator = $populator;
    }

    public function getPopulator(): FormPopulator
    {
        return $this->populator;
    }
}
