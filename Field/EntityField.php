<?php
declare(strict_types=1);

namespace Tath\FormBundle\Field;

use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Tath\Core\Classes\AnnotationTool;

class EntityField extends Field
{
    public static function match(AnnotationTool $annotationTool, $propertyName): bool
    {
        $annotations = $annotationTool->getTypedProperties(ManyToOne::class, $propertyName);
        $annotation = reset($annotations);
        if (!is_null($annotation) && ($annotation instanceof ManyToOne)) {
            return true;
        }
        return false;
    }

    public static function getType(): string
    {
        return EntityType::class;
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();
        $manyToOne = $this->getOrmManyToOne();
        $options['class'] = $manyToOne->targetEntity;
        if (isset($this->formField->currency)) {
            $options['currency'] = $this->formField->currency;
        }
        return $options;
    }
}
