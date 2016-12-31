<?php
namespace Tath\FormBundle\Tests;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Tath\Core\Tests\ContainerAwareTestCase;
use Tath\FormBundle\Tests\Fixtures\FooEntity;
use Tath\FormBundle\Twig\FormExtension;

class FormExtensionTest extends ContainerAwareTestCase
{
    public function testGetFunctions()
    {
        $extension = $this->buildExtension();
        $this->assertCount(2, $extension->getFunctions());
    }

    /**
     * @dataProvider tathNewProvider
     */
    public function testTathNew($className, $extra, $expectedPath)
    {
        $extension = $this->buildExtension();
        $path = $extension->tathNew($className, $extra);
        $this->assertEquals($expectedPath, $path);
    }

    /**
     * @dataProvider tathEditProvider
     */
    public function testTathEdit($id, $extra, $expectedPath)
    {
        $this->buildEntityManager(FooEntity::class);
        $extension = $this->buildExtension();
        $instance = new FooEntity();
        $instance->setId($id);
        $path = $extension->tathEdit($instance, $extra);
        $this->assertEquals($expectedPath, $path);
    }

    public function tathNewProvider()
    {
        return [
            'Simple Short Form' =>
                ['AppBundle:Foo', [], '/form/AppBundle__Foo'],
            'Simple Long Form' =>
                ['AppBundle\Entity\Foo', [], '/form/AppBundle__Foo'],
            'Extra Short Form' =>
                ['AppBundle:Foo', ['foo' => 'bar'], '/form/AppBundle__Foo//foo/bar'],
        ];
    }

    public function tathEditProvider()
    {
        return [
            'Simple' =>
                [1, [], '/form/TathFormBundle__FooEntity/1'],
            'Extra' =>
                [
                    'abcde',
                    ['foo' => 'bar'],
                    '/form/TathFormBundle__FooEntity/abcde/foo/bar'
                ],
        ];
    }

    /**
     * @return FormExtension
     */
    private function buildExtension(): FormExtension
    {
        $extension = new FormExtension(
            $this->getContainer()->get('router'),
            $this->getContainer()->get('tath_entity_naming')
        );
        return $extension;
    }

    private function buildEntityManager($className, $runs = 1)
    {
        $entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $configuration = new Configuration();
        $configuration->setEntityNamespaces([
            'TathFormBundle' => 'Tath\FormBundle\Tests\Fixtures',
        ]);

        $entityManager->expects($this->exactly($runs))
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $this->getContainer()->set('doctrine.orm.default_entity_manager', $entityManager);
    }
}