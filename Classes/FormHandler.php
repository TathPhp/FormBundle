<?php
declare(strict_types=1);

namespace Tath\FormBundle\Classes;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tath\FormBundle\Services\FormPopulatorFactory;

final class FormHandler
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var FormPopulatorInterface
     */
    private $formPopulator;

    private function __construct(Request $request, FormPopulatorInterface $formPopulator)
    {
        $this->request = $request;
        $this->formPopulator = $formPopulator;

        $this->form = $this->formPopulator->getBuilder()->getForm();
    }

    public static function make($request, FormPopulatorInterface $formPopulator)
    {
        $handler = new static($request, $formPopulator);
        return $handler;
    }

    public function getViewData($formPropertyName = 'form'): array
    {
        return [
            'form' => $this->form->getViewData(),
        ];
    }
}