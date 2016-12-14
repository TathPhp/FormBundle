<?php
declare(strict_types=1);

namespace Tath\FormBundle\Tests;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Tath\FormBundle\Classes\FormPopulatorListener;
use Tath\FormBundle\Events\PopulateEvent;
use Tath\FormBundle\Tests\Fixtures\FormEntity;
use Tath\FormBundle\Classes\FormPopulator;
use Symfony\Component\Form\Test\TypeTestCase;

class FormPopulatorTest extends TypeTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->dispatcher = new EventDispatcher();
        $formPopulatorListener = new FormPopulatorListener();
        $this->dispatcher->addListener(PopulateEvent::NAME, [$formPopulatorListener, 'onTathFormPopulate']);
    }

    public function testPopulate()
    {
        $builder = $this->makeBuilder();
        $this->assertCount(25, $builder);
        $this->assertTrue($builder->has('Save'));
    }

    public function testVisible()
    {
        $fooBuilder = $this->makeBuilder(['ROLE_FOO']);
        $barBuilder = $this->makeBuilder(['ROLE_FOO', 'ROLE_BAR']);
        $bazBuilder = $this->makeBuilder(['ROLE_BAZ', 'ROLE_BAR']);

        $this->assertCount(26, $fooBuilder);

        $this->assertTrue($fooBuilder->has('text'));
        $fooOptions = $fooBuilder->get('text')->getOptions();
        $this->assertEquals(true, $fooOptions['disabled']);

        $this->assertTrue($barBuilder->has('text'));
        $barOptions = $barBuilder->get('text')->getOptions();
        $this->assertEquals(false, $barOptions['disabled']);

        $this->assertFalse($bazBuilder->has('text'));
    }

    public function testRequired()
    {
        $builder = $this->makeBuilder();

        //The money property is not required because the column is nullable with Doctrine.
        //Tath sets this.
        $this->assertTrue($builder->has('money'));
        $options = $builder->get('money')->getOptions();
        $this->assertArrayHasKey('required', $options);
        $this->assertFalse($options['required']);
    }

    public function testLength()
    {
        $builder = $this->makeBuilder();

        //The money property is not required because the column is nullable with Doctrine.
        //Tath sets this.
        $this->assertTrue($builder->has('email'));
        $options = $builder->get('email')->getOptions();
        $this->assertArrayHasKey('attr', $options);
        $attributes = $options['attr'];
        $this->assertArrayHasKey('maxlength', $attributes);
        $this->assertEquals('60', $attributes['maxlength']);
    }

    protected function makeFormBuilder(): FormBuilderInterface
    {
        $builder = $this->factory->createBuilder();
        return $builder;
    }

    private function makeBuilder($forRoles = [])
    {
        $builder = $this->factory->createBuilder();
        $builder->setData(new FormEntity());
        return FormPopulator::make($builder, $this->dispatcher)
            ->setRoles($forRoles)
            ->populate()
            ->getBuilder()
            ;
    }

    public function populateEventListener(PopulateEvent $populateEvent)
    {
        $builder = $populateEvent->getPopulator()->getBuilder();
        $email = $builder->get('email');
        $options = $email->getOptions();
        $options['label'] = 'Foo';
        $builder->add('email', EmailType::class, $options);
    }

    public function testPopulateEvent()
    {
        $this->dispatcher->addListener(PopulateEvent::NAME, [$this, 'populateEventListener']);
        $builder = $this->makeBuilder();
        $this->assertCount(25, $builder);
        $this->assertTrue($builder->has('email'));
        $options = $builder->get('email')->getOptions();
        $this->assertEquals('Foo', $options['label']);
    }
}
