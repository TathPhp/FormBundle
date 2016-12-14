<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Tath\Core\Classes\AnnotationTool;
use Tath\FormBundle\Configuration\FormField;
use Tath\FormBundle\Configuration\RoleRestriction;

abstract class Field
{
    /**
     * @var FormField
     */
    protected $formField;

    /**
     * @var AnnotationTool
     */
    protected $annotationTool;

    /**
     * @var string
     */
    protected $propertyName;

    protected $roles = [];

    public function __construct(AnnotationTool $annotationTool, $propertyName)
    {
        $this->annotationTool = $annotationTool;
        $this->propertyName = $propertyName;
        $this->formField = $annotationTool->getTypedProperties(FormField::class, $propertyName)[0];
    }

    abstract public static function getType(): string;

    public static function match(
        /** @noinspection PhpUnusedParameterInspection */AnnotationTool $annotationTool,
        $propertyName
    ): bool {
        return false;
    }

    protected static function isOrmType(AnnotationTool $annotationTool, $propertyName, $type): bool
    {
        $annotation = self::ormColumnAnnotation($annotationTool, $propertyName);
        if (!is_null($annotation) && ($annotation instanceof Column)) {
            return $annotation->type === $type;
        }
        return false;
    }

    protected static function isFieldType(AnnotationTool $annotationTool, $propertyName, $type): bool
    {
        $annotations = $annotationTool->getTypedProperties(FormField::class, $propertyName);
        $annotation = reset($annotations);
        return (!is_null($annotation) && ($annotation instanceof FormField) && ($annotation->type === $type));
    }

    /**
     * @param AnnotationTool $annotationTool
     * @param $propertyName
     * @return Column
     */
    protected static function ormColumnAnnotation(AnnotationTool $annotationTool, $propertyName)
    {
        $annotations = $annotationTool->getTypedProperties(Column::class, $propertyName);
        $annotation = reset($annotations);
        return $annotation === false ? null : $annotation;
    }

    /**
     * @param AnnotationTool $annotationTool
     * @param $propertyName
     * @return ManyToOne
     */
    private static function ormManyToOneAnnotation(AnnotationTool $annotationTool, $propertyName)
    {
        $annotations = $annotationTool->getTypedProperties(ManyToOne::class, $propertyName);
        $annotation = reset($annotations);
        return $annotation;
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    public function getOptions(): array
    {
        $options = [
            'attr' => [],
        ];
        if (isset($this->formField->label)) {
            $options['label'] = $this->formField->label;
        }
        if (isset($this->formField->min)) {
            $options['attr']['min'] = $this->formField->min;
        }
        if (isset($this->formField->max)) {
            $options['attr']['max'] = $this->formField->max;
        }
        if ($this->restrictEdit()) {
            $options['disabled'] = true;
        }
        $columnAnnotation = $this->getOrmColumn();
        if (!is_null($columnAnnotation)) {
            $this->setOrmColumnOptions($columnAnnotation, $options);
        }
        return $options;
    }

    /**
     * @return Column
     */
    protected function getOrmColumn()
    {
        return self::ormColumnAnnotation($this->annotationTool, $this->propertyName);
    }

    /**
     * @return ManyToOne
     */
    protected function getOrmManyToOne()
    {
        return self::ormManyToOneAnnotation($this->annotationTool, $this->propertyName);
    }

    /**
     * @param array $roles
     * @return Field
     */
    public function setRoles(array $roles): Field
    {
        $this->roles = $roles;
        return $this;
    }

    private function restrictEdit()
    {
        foreach ($this->formField->restrict as $restriction) {
            return $this->doesRestrictionApplyTo($restriction, 'edit');
        }
        return false;
    }

    public function doesRestrictionApplyTo($restriction, $restrictionType): bool
    {
        return ($restriction instanceof RoleRestriction) &&
            $restriction->appliesTo($this->roles, $restrictionType);
    }

    private function setOrmColumnOptions(Column $columnAnnotation, array &$options)
    {
        if ($columnAnnotation->nullable) {
            $options['required'] = false;
        }
        if (isset($columnAnnotation->length)) {
            $options['attr']['maxlength'] = $columnAnnotation->length;
        }
    }
}
