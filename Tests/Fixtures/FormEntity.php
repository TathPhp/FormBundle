<?php
namespace Tath\FormBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;
use Tath\FormBundle\Configuration\Form;
use Tath\FormBundle\Configuration\FormAction;
use Tath\FormBundle\Configuration\FormField;
use Tath\FormBundle\Configuration\RoleRestriction;

/**
 * @ORM\Entity
 * @ORM\Table(name="form_entity")
 * @Form(success_redirect="bar")
 */
class FormEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @FormField(label="Custom Label", restrict={
     *     @RoleRestriction(visible={"ROLE_FOO"}, edit={"ROLE_BAR", "ROLE_BAZ"})
     * })
     */
    private $text;

    /**
     * @ORM\Column(type="text")
     * @FormField()
     */
    private $textArea;

    /**
     * @ORM\Column(type="string", length=60)
     * @FormField(type="email")
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     * @FormField()
     */
    private $integer;

    /**
     * @ORM\Column(type="integer")
     * @FormField(min=1, max=10)
     */
    private $confinedInteger;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @FormField(type="money", currency="CAD")
     */
    private $money;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=1)
     * @FormField()
     */
    private $number;

    /**
     * @ORM\Column(type="string")
     * @FormField(type="url")
     */
    private $url;

    /**
     * @ORM\Column(type="integer")
     * @FormField(type="range", min=0, max=100)
     */
    private $range;

    /**
     * @ORM\Column(type="string")
     * @FormField(choices={"foo":"Foo", "bar":"Bar", "baz":"Baz"})
     */
    private $choice;

    /**
     * @ORM\Column(type="string")
     * @FormField(type="radio", choices={"foo":"Foo", "bar":"Bar", "baz":"Baz"})
     */
    private $choiceRadio;

    /**
     * @ORM\ManyToOne(targetEntity="Tath\FormBundle\Tests\Fixtures\FooEntity")
     * @ORM\JoinColumn()
     * @FormField()
     */
    private $entity;

    /**
     * @ORM\Column(type="string")
     * @FormField(type="country")
     */
    private $country;

    /**
     * @ORM\Column(type="string")
     * @FormField(type="language")
     */
    private $language;

    /**
     * @ORM\Column(type="string")
     * @FormField(type="locale")
     */
    private $locale;

    /**
     * @ORM\Column(type="string")
     * @FormField(type="timezone")
     */
    private $timezone;

    /**
     * @ORM\Column(type="string")
     * @FormField(type="currency")
     */
    private $currency;

    /**
     * @ORM\Column(type="date")
     * @FormField()
     */
    private $date;

    /**
     * @ORM\Column(type="datetime")
     * @FormField()
     */
    private $dateTime;

    /**
     * @ORM\Column(type="time")
     * @FormField()
     */
    private $time;

    /**
     * @ORM\Column(type="date")
     * @FormField(type="birthday")
     */
    private $birthday;

    /**
     * @ORM\Column(type="boolean")
     * @FormField()
     */
    private $checkbox;

    /**
     * @ORM\Column(type="boolean")
     * @FormField(choices={"":"Choose", 1:"True", 0:"False"})
     */
    private $booleanDropDown;

    /**
     * @ORM\Column(type="boolean")
     * @FormField(type="radio", choices={"":"Choose", 1:"True", 0:"False"})
     */
    private $booleanRadio;

    /**
     * @ORM\Column(type="blob")
     * @FormField()
     */
    private $file;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return FormEntity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return FormEntity
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTextArea()
    {
        return $this->textArea;
    }

    /**
     * @param mixed $textArea
     * @return FormEntity
     */
    public function setTextArea($textArea)
    {
        $this->textArea = $textArea;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return FormEntity
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInteger()
    {
        return $this->integer;
    }

    /**
     * @param mixed $integer
     * @return FormEntity
     */
    public function setInteger($integer)
    {
        $this->integer = $integer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfinedInteger()
    {
        return $this->confinedInteger;
    }

    /**
     * @param mixed $confinedInteger
     * @return FormEntity
     */
    public function setConfinedInteger($confinedInteger)
    {
        $this->confinedInteger = $confinedInteger;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * @param mixed $money
     * @return FormEntity
     */
    public function setMoney($money)
    {
        $this->money = $money;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     * @return FormEntity
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return FormEntity
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * @param mixed $range
     * @return FormEntity
     */
    public function setRange($range)
    {
        $this->range = $range;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChoice()
    {
        return $this->choice;
    }

    /**
     * @param mixed $choice
     * @return FormEntity
     */
    public function setChoice($choice)
    {
        $this->choice = $choice;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChoiceRadio()
    {
        return $this->choiceRadio;
    }

    /**
     * @param mixed $choiceRadio
     * @return FormEntity
     */
    public function setChoiceRadio($choiceRadio)
    {
        $this->choiceRadio = $choiceRadio;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     * @return FormEntity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return FormEntity
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     * @return FormEntity
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     * @return FormEntity
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param mixed $timezone
     * @return FormEntity
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     * @return FormEntity
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return FormEntity
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param mixed $dateTime
     * @return FormEntity
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     * @return FormEntity
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     * @return FormEntity
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCheckbox()
    {
        return $this->checkbox;
    }

    /**
     * @param mixed $checkbox
     * @return FormEntity
     */
    public function setCheckbox($checkbox)
    {
        $this->checkbox = $checkbox;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBooleanDropDown()
    {
        return $this->booleanDropDown;
    }

    /**
     * @param mixed $booleanDropDown
     * @return FormEntity
     */
    public function setBooleanDropDown($booleanDropDown)
    {
        $this->booleanDropDown = $booleanDropDown;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBooleanRadio()
    {
        return $this->booleanRadio;
    }

    /**
     * @param mixed $booleanRadio
     * @return FormEntity
     */
    public function setBooleanRadio($booleanRadio)
    {
        $this->booleanRadio = $booleanRadio;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return FormEntity
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }
}
