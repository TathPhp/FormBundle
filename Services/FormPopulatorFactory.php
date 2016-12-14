<?php
declare(strict_types=1);

namespace Tath\FormBundle\Services;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Tath\FormBundle\Classes\FormPopulator;

class FormPopulatorFactory implements FormPopulatorFactoryInterface
{
    private $dispatcher;

    /**
     * FormPopulatorFactory constructor.
     * @param $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function makePopulator(FormBuilderInterface $formBuilder)
    {
        return FormPopulator::make($formBuilder, $this->dispatcher);
    }
}
