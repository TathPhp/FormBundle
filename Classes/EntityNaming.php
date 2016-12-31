<?php

namespace Tath\FormBundle\Classes;

use Doctrine\ORM\EntityManagerInterface;
use Tath\Core\Classes\Collection;

class EntityNaming
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * EntityNaming constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function entityToRouteName($className)
    {
        $className = $this->shortName($className);
        return $this->isShortName($className) ?
            $this->shortNameToRouteName($className) :
            $this->longNameToRouteName($className);
    }

    public function routeToEntityName($routeName)
    {
        if (strpos($routeName, '__') === false) {
            $routeName = '_' . $routeName;
        }
        return str_replace(['__', '_'], [':', '\\'], $routeName);
    }

    public function entityToPathName($className)
    {
        $path = str_replace([':', '\\'], ['+', '/'], $this->shortName($className));
        if ($path[0] === '/') {
            $path = substr($path, 1);
        }
        return $path;
    }

    private function isShortName($className)
    {
        return strpos($className, ':') !== false;
    }

    private function shortName($className)
    {
        if ($this->isShortName($className)) {
            return $className;
        }
        $namespaces = $this->entityManager->getConfiguration()->getEntityNamespaces();
        $alias = Collection::make($namespaces)
            ->search(function ($namespace) use ($className) {
                return substr($className, 0, strlen($namespace)) === $namespace;
            });
        if ($alias === false) {
            return $className; //Can't be shortened
        }
        $shortClassName = substr($className, strlen($namespaces[$alias]) + 1);
        return $alias . ':' . $shortClassName;
    }

    private function shortNameToRouteName($className)
    {
        list($alias, $name) = explode(':', $className, 2);
        return $alias . '__' . $this->longNameToRouteName($name);
    }

    private function longNameToRouteName($className)
    {
        if ($className[0] === '\\') {
            $className = substr($className, 1);
        }
        return str_replace('\\', '_', $className);
    }
}
