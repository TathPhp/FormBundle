<?php
namespace Tath\FormBundle\Classes;

use Symfony\Component\Form\FormBuilderInterface;

interface FormPopulatorInterface
{
    /**
     * @param mixed $roles
     * @return $this
     */
    public function setRoles($roles);

    /**
     * @return FormBuilderInterface
     */
    public function getBuilder() : FormBuilderInterface;

    /**
     * Populate it using the FormField annotations in
     * the class of the builder's data.
     *
     * @return FormPopulator
     */
    public function populate();
}