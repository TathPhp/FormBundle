<?php
declare(strict_types=1);

namespace Tath\FormBundle\Classes;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Tath\Core\Classes\AnnotationTool;
use Tath\Core\Classes\Collection;
use Tath\FormBundle\Configuration\Form;
use Tath\FormBundle\Configuration\FormAction;
use Tath\FormBundle\Configuration\FormField;
use Tath\FormBundle\Events\PopulateEvent;
use Tath\FormBundle\Field\Field;

final class FormPopulator implements FormPopulatorInterface
{
    /**
     * @var FormBuilderInterface
     */
    private $builder;

    private $roles = [];
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * FormTool constructor.
     * @param FormBuilderInterface $builder
     */
    private function __construct(FormBuilderInterface $builder, EventDispatcherInterface $dispatcher)
    {
        $this->builder = $builder;
        $this->dispatcher = $dispatcher;
    }

    public static function make(FormBuilderInterface $builder, EventDispatcherInterface $dispatcher)
    {
        $tool = new static($builder, $dispatcher);
        return $tool;
    }

    /**
     * @param mixed $roles
     * @return $this
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return FormBuilderInterface
     */
    public function getBuilder(): FormBuilderInterface
    {
        return $this->builder;
    }

    /**
     * Populate it using the FormField annotations in
     * the class of the builder's data.
     *
     * @return FormPopulator
     */
    public function populate()
    {
        $event = new PopulateEvent($this);
        $this->dispatcher->dispatch(PopulateEvent::NAME, $event);
        return $this;
    }

    public function populateListener(PopulateEvent $event)
    {
        $populator = $event->getPopulator();
        $builder = $populator->builder;
        $entity = $builder->getData();
        $tool = AnnotationTool::make(get_class($entity));
        $factory = FieldFactory::make($tool);
        Collection::make($tool->getPropertiesWith(FormField::class))
            ->keys()
            ->map(function ($propertyName) use ($factory, $populator) {
                return $factory->makeField($propertyName)->setRoles($populator->roles);
            })
            ->filter(function (Field $field) use ($factory, $populator) {
                return $populator->isFieldVisible($field, $factory->getFormFieldAnnotation($field->getPropertyName()));
            })
            ->each(function (Field $field) use ($builder) {
                $builder->add($field->getPropertyName(), $field->getType(), $field->getOptions());
            });
        if (Collection::make($tool->getClassAnnotationsOfType(FormAction::class))
            ->each(function (FormAction $action) use ($builder) {
                $builder->add($action->label, SubmitType::class, ['label' => $action->label]);
            })
            ->count() === 0) {
            $builder->add('Save', SubmitType::class, ['label' => 'Save']);
        }
    }

    private function isFieldVisible(Field $field, FormField $annotation): bool
    {
        return Collection::make($annotation->restrict)
            ->filter(function ($restriction) use ($field) {
                return $field->doesRestrictionApplyTo($restriction, 'visible');
            })
            ->isEmpty();
    }
}
