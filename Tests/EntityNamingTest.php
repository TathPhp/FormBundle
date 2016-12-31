<?php

namespace Tath\FormBundle\Tests;

use Tath\FormBundle\Classes\EntityNaming;
use Tath\Core\Tests\ContainerAwareTestCase;

class EntityNamingTest extends ContainerAwareTestCase
{
    private $entityManager;

    protected function setUp()
    {
        parent::setUp();
        $this->entityManager = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $this->entityManager->getConfiguration()->addEntityNamespace('AppBundle', '\\AppBundle\\Entity');
    }

    /**
     * @dataProvider routeNameProvider
     * @param $className
     * @param $shortName
     * @param $routeName
     * @param $pathName
     */
    public function testRouteNames($className, $shortName, $routeName, $pathName)
    {
        $naming = new EntityNaming($this->entityManager);
        $this->assertEquals(
            $routeName,
            $naming->entityToRouteName($className),
            "Unexpected entity to route name from $className"
        );
        $this->assertEquals(
            $shortName,
            $naming->routeToEntityName($routeName),
            "Unexpected route to entity name from $routeName"
        );
        $this->assertEquals(
            $pathName,
            $naming->entityToPathName($className),
            "Unexpected entity to path name from $className"
        );
    }

    public function routeNameProvider()
    {
        return [
            [
                'AppBundle:Foo',
                'AppBundle:Foo', //Already a short name
                'AppBundle__Foo',
                'AppBundle+Foo',
            ],
            [
                'AppBundle:Bar\Foo',
                'AppBundle:Bar\Foo', //Already a short name
                'AppBundle__Bar_Foo',
                'AppBundle+Bar/Foo',
            ],
            [
                '\AppBundle\Entity\Foo',
                'AppBundle:Foo', //Can be shortened
                'AppBundle__Foo',
                'AppBundle+Foo',
            ],
            [
                '\Other\NameSpaced\Class',
                '\Other\NameSpaced\Class', //Can not be shortened
                'Other_NameSpaced_Class',
                'Other/NameSpaced/Class',
            ],
        ];
    }
}
