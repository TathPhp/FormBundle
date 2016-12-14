<?php
declare(strict_types=1);

namespace Tath\FormBundle\Classes;

use Tath\Core\Classes\AnnotationTool;
use Tath\Core\Classes\Collection;
use Tath\FormBundle\Configuration\FormField;
use Tath\FormBundle\Field\BirthdayField;
use Tath\FormBundle\Field\CheckboxField;
use Tath\FormBundle\Field\ChoiceField;
use Tath\FormBundle\Field\CountryField;
use Tath\FormBundle\Field\CurrencyField;
use Tath\FormBundle\Field\DateField;
use Tath\FormBundle\Field\DateTimeField;
use Tath\FormBundle\Field\EmailField;
use Tath\FormBundle\Field\EntityField;
use Tath\FormBundle\Field\Field;
use Tath\FormBundle\Field\FileField;
use Tath\FormBundle\Field\IntegerField;
use Tath\FormBundle\Field\LanguageField;
use Tath\FormBundle\Field\LocaleField;
use Tath\FormBundle\Field\MoneyField;
use Tath\FormBundle\Field\NumberField;
use Tath\FormBundle\Field\RadioField;
use Tath\FormBundle\Field\RangeField;
use Tath\FormBundle\Field\TextAreaField;
use Tath\FormBundle\Field\TextField;
use Tath\FormBundle\Field\TimeField;
use Tath\FormBundle\Field\TimezoneField;
use Tath\FormBundle\Field\UrlField;

final class FieldFactory
{
    /**
     * @var AnnotationTool
     */
    private $annotationTool;

    /**
     * @var Collection
     */
    private static $fields;

    private function __construct(AnnotationTool $annotationTool)
    {
        $this->annotationTool = $annotationTool;
    }

    public static function make(AnnotationTool $annotationTool)
    {
        $factory = new static($annotationTool);
        return $factory;
    }

    public static function initialize()
    {
        if (isset(self::$fields)) {
            return;
        }
        self::$fields = Collection::make();
        self::registerType('file', FileField::class);
        self::registerType('choice', ChoiceField::class);
        self::registerType('radio', RadioField::class);
        self::registerType('checkbox', CheckboxField::class);
        self::registerType('birthday', BirthdayField::class);
        self::registerType('time', TimeField::class);
        self::registerType('datetime', DateTimeField::class);
        self::registerType('date', DateField::class);
        self::registerType('currency', CurrencyField::class);
        self::registerType('timezone', TimezoneField::class);
        self::registerType('locale', LocaleField::class);
        self::registerType('language', LanguageField::class);
        self::registerType('country', CountryField::class);
        self::registerType('entity', EntityField::class);
        self::registerType('range', RangeField::class);
        self::registerType('url', UrlField::class);
        self::registerType('number', NumberField::class);
        self::registerType('money', MoneyField::class);
        self::registerType('integer', IntegerField::class);
        self::registerType('email', EmailField::class);
        self::registerType('textarea', TextAreaField::class);
        self::registerType('text', TextField::class);
    }

    private static function registerType($typeName, $className)
    {
        self::$fields[$typeName] = [$className, 'match'];
    }

    /**
     * @param $propertyName
     * @return Field
     */
    public function makeField($propertyName)
    {
        self::initialize();
        $type = $this->getType($propertyName);
        return is_null($type) ? null : new $type[0]($this->annotationTool, $propertyName);
    }

    /**
     * @param $propertyName
     * @return mixed
     */
    private function getType($propertyName)
    {
        $annotation = $this->getFormFieldAnnotation($propertyName);
        if (!is_null($annotation) && ($annotation instanceof FormField) && isset($annotation->type)) {
            return self::$fields[$annotation->type] ?? null;
        }

        $type = self::$fields->first(function ($field) use ($propertyName) {
            return call_user_func($field, $this->annotationTool, $propertyName);
        });
        return $type;
    }

    public function getFormFieldAnnotation($propertyName): FormField
    {
        $annotations = $this->annotationTool->getTypedProperties(FormField::class, $propertyName);
        $annotation = reset($annotations);
        return $annotation;
    }
}
