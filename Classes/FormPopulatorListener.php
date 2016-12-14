<?php

namespace Tath\FormBundle\Classes;

use Tath\FormBundle\Events\PopulateEvent;

class FormPopulatorListener
{
    public function onTathFormPopulate(PopulateEvent $event)
    {
        $event->getPopulator()->populateListener($event);
    }
}
