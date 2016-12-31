<?php


namespace Tath\FormBundle\Twig;

use Doctrine\ORM\Mapping\Id;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\RouterInterface;
use Tath\Core\Classes\AnnotationTool;
use Tath\FormBundle\Classes\EntityNaming;
use Twig_Extension;
use Twig_SimpleFunction;

class FormExtension extends Twig_Extension
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityNaming
     */
    private $naming;

    public function __construct(RouterInterface $router, EntityNaming $naming)
    {
        $this->router = $router;
        $this->naming = $naming;
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('tath_edit', [$this, 'tathEdit']),
            new Twig_SimpleFunction('tath_new', [$this, 'tathNew']),
        );
    }

    public function tathNew($className, $extra = [])
    {
        $routeName = empty($extra) ? 'tath_form_new' : 'tath_form_new_extra';
        $parameters = [
            'name' => $this->naming->entityToRouteName($className),
            'id' => null,
        ];
        if (!empty($extra)) {
            $parameters['extra'] = $this->constructExtraParameter($extra);
        }
        return $this->router->generate($routeName, $parameters);
    }

    public function tathEdit($entity, $extra = [])
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $tool = AnnotationTool::make(get_class($entity));
        $ids = $tool->getPropertiesWith(Id::class);
        reset($ids);
        $id = $accessor->getValue($entity, key($ids));
        $routeName = empty($extra) ? 'tath_form_edit' : 'tath_form_extra';
        $parameters = [
            'name' => $this->naming->entityToRouteName(get_class($entity)),
            'id' => $id,
        ];
        if (!empty($extra)) {
            $parameters['extra'] = $this->constructExtraParameter($extra);
        }
        return $this->router->generate($routeName, $parameters);
    }

    private function constructExtraParameter($extra)
    {
        $parts = [];
        foreach ($extra as $key => $value) {
            $parts[] = $key;
            $parts[] = $value;
        }
        return implode('/', $parts);
    }
}