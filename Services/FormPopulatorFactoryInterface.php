<?php
namespace Tath\FormBundle\Services;

use Symfony\Component\Form\FormBuilderInterface;

interface FormPopulatorFactoryInterface
{
    public function makePopulator(FormBuilderInterface $formBuilder);
}