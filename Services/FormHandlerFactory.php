<?php
declare(strict_types=1);

namespace Tath\FormBundle\Services;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Tath\FormBundle\Classes\FormHandler;

class FormHandlerFactory
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FormPopulatorFactoryInterface
     */
    private $formPopulatorFactory;

    public function __construct(
        FormFactoryInterface $formFactory,
        FormPopulatorFactoryInterface $formPopulatorFactory
    ) {
        $this->formFactory = $formFactory;
        $this->formPopulatorFactory = $formPopulatorFactory;
    }

    public function makeFormHandler(Request $request, $entity, $options = [])
    {
        $builder = $this->formFactory->createBuilder(FormType::class, $entity, $options);
        $populator = $this->formPopulatorFactory->makePopulator($builder);
        return FormHandler::make($request, $populator);
    }
}