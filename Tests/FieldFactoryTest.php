<?php
declare(strict_types=1);

namespace Tath\FormBundle\Tests;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormFactoryBuilder;
use Tath\Core\Classes\AnnotationTool;
use Tath\FormBundle\Classes\FieldFactory;
use Tath\FormBundle\Tests\Fixtures\FormEntity;

class FieldFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $tool;
    private $builder;

    /**
     * @var FieldFactory
     */
    private $factory;

    public function setUp()
    {
        $this->tool = AnnotationTool::make(FormEntity::class);
        $factory = new FormFactoryBuilder();
        $this->builder = $factory->getFormFactory()->createBuilder();
        $this->factory = FieldFactory::make($this->tool);
    }

    public function testText()
    {
        $field = $this->factory->makeField('text');
        $this->assertEquals(TextType::class, $field->getType());
        $options = $field->getOptions();
        $this->assertArrayHasKey('label', $options);
        $this->assertEquals("Custom Label", $options['label']);
    }

    public function testTextArea()
    {
        $field = $this->factory->makeField('textArea');
        $this->assertEquals(TextareaType::class, $field->getType());
        $this->assertArrayNotHasKey('label', $field->getOptions());
    }

    public function testEmail()
    {
        $field = $this->factory->makeField('email');
        $this->assertEquals(EmailType::class, $field->getType());
    }

    public function testInteger()
    {
        $field = $this->factory->makeField('integer');
        $this->assertEquals(IntegerType::class, $field->getType());
    }

    public function testConfinedInteger()
    {
        $field = $this->factory->makeField('confinedInteger');
        $this->assertEquals(IntegerType::class, $field->getType());
        $options = $field->getOptions();
        $this->assertArrayHasKey('attr', $options);
        $attributes = $options['attr'];
        $this->assertArrayHasKey('min', $attributes);
        $this->assertEquals(1, $attributes['min']);
        $this->assertArrayHasKey('max', $attributes);
        $this->assertEquals(10, $attributes['max']);
    }

    public function testMoney()
    {
        $field = $this->factory->makeField('money');
        $this->assertEquals(MoneyType::class, $field->getType());
        $options = $field->getOptions();
        $this->assertArrayHasKey('currency', $options);
        $this->assertEquals('CAD', $options['currency']);
    }

    public function testNumber()
    {
        $field = $this->factory->makeField('number');
        $this->assertEquals(NumberType::class, $field->getType());
        $options = $field->getOptions();
        $this->assertArrayHasKey('scale', $options);
        $this->assertEquals(1, $options['scale']);
    }

    public function testUrl()
    {
        $field = $this->factory->makeField('url');
        $this->assertEquals(UrlType::class, $field->getType());
    }

    public function testRange()
    {
        $field = $this->factory->makeField('range');
        $this->assertEquals(RangeType::class, $field->getType());
        $options = $field->getOptions();
        $this->assertArrayHasKey('attr', $options);
        $attributes = $options['attr'];
        $this->assertArrayHasKey('min', $attributes);
        $this->assertEquals(0, $attributes['min']);
        $this->assertArrayHasKey('max', $attributes);
        $this->assertEquals(100, $attributes['max']);
    }

    public function testChoice()
    {
        $field = $this->factory->makeField('choice');
        $this->assertEquals(ChoiceType::class, $field->getType());
        $options = $field->getOptions();
        $this->assertArrayHasKey('choices', $options);
        $choices = $options['choices'];
        $this->assertCount(3, $choices);
        $this->assertArrayHasKey('foo', $choices);
        $this->assertEquals('Bar', $choices['bar']);
    }

    public function testChoiceRadio()
    {
        $field = $this->factory->makeField('choiceRadio');
        $this->assertEquals(ChoiceType::class, $field->getType());
        $options = $field->getOptions();
        $this->assertArrayHasKey('choices', $options);
        $choices = $options['choices'];
        $this->assertCount(3, $choices);
        $this->assertArrayHasKey('foo', $choices);
        $this->assertEquals('Bar', $choices['bar']);
    }

    public function testEntity()
    {
        $field = $this->factory->makeField('entity');
        $this->assertEquals(EntityType::class, $field->getType());
        $options = $field->getOptions();
        $this->assertArrayHasKey('class', $options);
        $this->assertEquals('Tath\FormBundle\Tests\Fixtures\FooEntity', $options['class']);
    }

    public function testCountry()
    {
        $field = $this->factory->makeField('country');
        $this->assertEquals(CountryType::class, $field->getType());
    }

    public function testLanguage()
    {
        $field = $this->factory->makeField('language');
        $this->assertEquals(LanguageType::class, $field->getType());
    }

    public function testLocale()
    {
        $field = $this->factory->makeField('locale');
        $this->assertEquals(LocaleType::class, $field->getType());
    }

    public function testTimezone()
    {
        $field = $this->factory->makeField('timezone');
        $this->assertEquals(TimezoneType::class, $field->getType());
    }

    public function testCurrency()
    {
        $field = $this->factory->makeField('currency');
        $this->assertEquals(CurrencyType::class, $field->getType());
    }

    public function testDate()
    {
        $field = $this->factory->makeField('date');
        $this->assertEquals(DateType::class, $field->getType());
    }

    public function testDateTime()
    {
        $field = $this->factory->makeField('dateTime');
        $this->assertEquals(DateTimeType::class, $field->getType());
    }

    public function testTime()
    {
        $field = $this->factory->makeField('time');
        $this->assertEquals(TimeType::class, $field->getType());
    }

    public function testBirthday()
    {
        $field = $this->factory->makeField('birthday');
        $this->assertEquals(BirthdayType::class, $field->getType());
    }

    public function testCheckbox()
    {
        $field = $this->factory->makeField('checkbox');
        $this->assertEquals(CheckboxType::class, $field->getType());
    }

    public function testBooleanDropDown()
    {
        $field = $this->factory->makeField('booleanDropDown');
        $this->assertEquals(ChoiceType::class, $field->getType());
        $options = $field->getOptions();
        $this->assertArrayHasKey('choices', $options);
        $this->assertEquals([
            '' => "Choose",
            1 => "True",
            0 => "False",
        ], $options['choices']);
    }

    public function testBooleanRadio()
    {
        $field = $this->factory->makeField('booleanRadio');
        $this->assertEquals(ChoiceType::class, $field->getType());
        $options = $field->getOptions();
        $this->assertArrayHasKey('choices', $options);
        $this->assertEquals([
            '' => "Choose",
            1 => "True",
            0 => "False",
        ], $options['choices']);
    }

    public function testFile()
    {
        $field = $this->factory->makeField('file');
        $this->assertEquals(FileType::class, $field->getType());
    }
}
