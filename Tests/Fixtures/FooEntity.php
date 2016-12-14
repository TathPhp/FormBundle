<?php
namespace Tath\Form\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="foo_entity")
 */
class FooEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $bar;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return FooEntity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param mixed $bar
     * @return FooEntity
     */
    public function setBar($bar)
    {
        $this->bar = $bar;
        return $this;
    }
}