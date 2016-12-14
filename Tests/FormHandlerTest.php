<?php
declare(strict_types=1);

namespace Tath\FormBundle\Tests;

use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\Request;
use Tath\Form\Tests\Fixtures\FooEntity;
use Tath\FormBundle\Classes\FormPopulator;
use Tath\FormBundle\Events\PopulateEvent;
use Tath\FormBundle\Services\FormHandlerFactory;
use Tath\FormBundle\Services\FormPopulatorFactory;
use Tath\FormBundle\Services\FormPopulatorFactoryInterface;

class FormHandlerTest extends TypeTestCase
{
    /**
     * @var FormPopulatorFactoryInterface
     */
    private $populatorFactory;

    /**
     * @var FormHandlerFactory
     */
    private $handlerFactory;

    public function setUp()
    {
        parent::setUp();
        $this->dispatcher->addListener(PopulateEvent::NAME, [FormPopulator::class, 'populateListener']);
        $this->populatorFactory = new FormPopulatorFactory($this->dispatcher);
        $this->handlerFactory = new FormHandlerFactory($this->factory, $this->populatorFactory);
    }

    public function testPopulatorFactory()
    {
        $factory = new FormPopulatorFactory($this->dispatcher);
        $populator = $factory->makePopulator($this->factory->createBuilder());
        $this->assertInstanceOf(FormPopulator::class, $populator);
    }

    public function testControllerGet()
    {
        $request = new Request(
            [],
            [
                'form[bar]' => 'baz',
            ]
        );
        $handler = $this->handlerFactory->makeFormHandler($request, new FooEntity());
        $viewData = $handler->getViewData();
        $this->assertArrayHasKey('form', $viewData);
    }
}
