<?php
namespace Tath\FormBundle\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tath\Core\Exceptions\MissingAnnotationException;
use Tath\Core\Tests\ContainerAwareTestCase;
use Tath\FormBundle\Controller\FormController;

class FormControllerTest extends ContainerAwareTestCase
{
    public function testFormAction()
    {
        $this->buildEntityManager('FormEntity', 2);
        $this->buildFormFactory(2);
        $this->buildTwig();
        $this->buildRouter();
        if ($this->getContainer()->hasParameter('tath_form.template')) {
            $this->getContainer()->setParameter('tath_form.template', null);
        }
        $controller = new FormController();
        $controller->setContainer($this->getContainer());
        $request = new Request();
        $className = 'TathFormBundle__FormEntity';
        $response = $controller->formAction($request, $className);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("TathFormBundle:Default:index.html.twig", $response->getContent());
        $response = $controller->formAction($request, $className);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testWithExtra()
    {
        $this->buildEntityManager('FormEntity', 2);
        $this->buildFormFactory(2);
        $this->buildTwig();
        $this->buildRouter();
        if ($this->getContainer()->hasParameter('tath_form.template')) {
            $this->getContainer()->setParameter('tath_form.template', null);
        }
        $controller = new FormController();
        $controller->setContainer($this->getContainer());
        $request = new Request();
        $className = 'TathFormBundle__FormEntity';
        $extra = 'foo/bar';
        $response = $controller->formAction($request, $className, null, $extra);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("TathFormBundle:Default:index.html.twig", $response->getContent());
        $response = $controller->formAction($request, $className, null, $extra);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("bar?foo=bar", $response->getTargetUrl());
    }

    public function testAnnotationTemplate()
    {
        $this->buildEntityManager('FooEntity');
        $this->buildFormFactory();
        $this->buildTwig();
        $controller = new FormController();
        $controller->setContainer($this->getContainer());
        $request = new Request();
        $className = 'TathFormBundle__FooEntity';
        $response = $controller->formAction($request, $className);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('foo.html.twig', $response->getContent());
    }

    public function testConfigTemplate()
    {
        $this->buildEntityManager('FormEntity');
        $this->buildFormFactory();
        $this->buildTwig();
        $template = 'from_config.html.twig';
        $this->getContainer()->setParameter('tath_form.template', $template);
        $controller = new FormController();
        $controller->setContainer($this->getContainer());
        $request = new Request();
        $className = 'TathFormBundle__FormEntity';
        $response = $controller->formAction($request, $className);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($template, $response->getContent());
    }

    public function testNoFormAnnotation()
    {
        $this->buildEntityManager('User');
        $controller = new FormController();
        $controller->setContainer($this->getContainer());
        $request = new Request();
        $className = 'TathFormBundle__User';
        $this->expectException(MissingAnnotationException::class);
        $controller->formAction($request, $className);
    }

    private function buildEntityManager($className, $runs = 1)
    {
        $entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $meta = $this
            ->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();
        $meta->expects($this->exactly($runs))
            ->method('getName')
            ->will($this->returnValue("Tath\\FormBundle\\Tests\\Fixtures\\$className"));

        $entityManager->expects($this->exactly($runs))
            ->method('getClassMetadata')
            ->with("TathFormBundle:$className")
            ->will($this->returnValue($meta));

        $this->getContainer()->set('doctrine.orm.default_entity_manager', $entityManager);
    }

    private function buildFormFactory($runs = 1)
    {
        $factory = $this
            ->getMockBuilder(FormFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $builder = $this
            ->getMockBuilder(FormBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $form = $this
            ->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock();
        $postedForm = $this
            ->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory->expects($this->exactly($runs))
            ->method('createBuilder')
            ->will($this->returnValue($builder));

        if ($runs > 1) {
            $postedForm->expects($this->once())
                ->method('isSubmitted')
                ->will($this->returnValue(true));
            $postedForm->expects($this->once())
                ->method('isValid')
                ->will($this->returnValue(true));
        }
        $builder->expects($this->exactly($runs))
            ->method('getForm')
            ->will($this->onConsecutiveCalls($form, $postedForm));

        $this->getContainer()->set('form.factory', $factory);
    }

    private function buildTwig()
    {
        $twig = $this
            ->getMockBuilder(TwigEngine::class)
            ->disableOriginalConstructor()
            ->getMock();

        $twig->expects($this->once())
            ->method('renderResponse')
            ->will($this->returnCallback(function ($template) {
                return new Response($template, 200);
            }));

        $this->getContainer()->set('templating', $twig);
    }

    private function buildRouter()
    {
        $router = $this
            ->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock();

        $router->expects($this->once())
            ->method('generate')
            ->will($this->returnCallback(function ($route, $parameters) {
                return $route . '?' . http_build_query($parameters);
            }));

        $this->getContainer()->set('router', $router);
    }
}
