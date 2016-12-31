<?php

namespace Tath\FormBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tath\FormBundle\Classes\FormPopulator;
use Tath\FormBundle\Events\PopulateEvent;

class TathFormBundle extends Bundle
{
    const ROUTE_NAME_EDIT = 'tath_form_edit';
    const ROUTE_NAME_NEW = 'tath_form_new';
}
