Tath Form Tool
==============
The Tath Form Tool makes it easy to generate Symfony Forms by adding annotations to your entities.
Much in the same way that you can build a database schema using Doctrine's annotations you can
also build your forms for creating and updating entities.

For an explanation of the word Tath, refer to the Tath Core README.md file.

**Tath and by extension the Tath Form Tool are under active development and the interface
should not be considered stable.**

Installation
------------
Since this is pre-release software I am not yet tagging version IDs. I will start
using semver tags when the Grid tool is released and both are solid enough to use
at least experimentally. For now, install with:

    composer require tath/form_bundle:master@dev

Usage
-----
Add an @Form annotation to entities that you want to generate forms for. Add a
@FormField annotation to fields that you want included in a form.

    use Tath\FormBundle\Configuration\Form;
    use Tath\FormBundle\Configuration\FormField;
    
    /**
     * @ORM\Entity
     * @ORM\Table
     * @Form(template="AppBundle:Foo:form.html.twig", success_redirect="foos")
     */
    class Foo
    {
        /**
         * @ORM\Column(type="string")
         * @FormField(label="Foo Name")
         */
        private $name;
    }

Add the @Form annotation to the class. Include an optional template, and a success redirect which
is the route to return to when the form is submitted. You can optionally include a template to
contain the form. If you don't use a template attribute on an entity, Tath will look in your
config.yml for a default template:

    tath_form:
      template: "AppBundle::form.html.twig"
      
If neither are provided Tath will supply its own form template which includes both the form and
instructions for supplying your own.

Form fields are marked with the @FormField annotation. It can include a number of attributes, which
are all optional:

### label
The caption to show with the input element.

### type
An input type. These match those built into Symfony Forms, and Tath attempts to match
Doctrine types to these as best as it can. You can set the type manually to one of:

- birthday
- checkbox
- choice
- country
- currency
- date
- datetime
- email
- entity
- file
- integer
- language
- locale
- money
- number
- radio
- range
- text
- textarea
- time
- timezone
- url

### choices
A list of choices for the choice type. For example:

    /**
     * @ORM\Column(type="string")
     * @FormField(choices={"foo":"Foo", "bar":"Bar", "baz":"Baz"})
     */
    private $choice;

### restrict
Restricts the visibility or editing of fields based on Symfony roles. For example:

    /**
     * @ORM\Column(type="string")
     * @FormField(restrict={
     *     @RoleRestriction(visible={"ROLE_FOO"}, edit={"ROLE_BAR", "ROLE_BAZ"})
     * })
     */
    private $text;

### min / max
Limits integers or ranges to minimum and/or maximum values.

### currency
Used by the money type, indicates the expected currency for an input. See
http://symfony.com/doc/current/reference/forms/types/money.html#currency.

Twig Functions
--------------
These functions both include an optional 'extra' parameter which can be used to pass 
extra key/value pairs to the form as described in the next section.

Routes for Tath Forms can be generated with the these twig functions:

### tath_new(class_name, extra)
To link to a form for creating new entities, call tath_new with the name of the entity
you want to add. For example:

    <a href="{{ tath_new('AppBundle:Foo') }}">New</a>

### tath_edit(entity, extra)
To link to a form for editing an existing entity, call tath_edit with the entity you
wish to edit. For example:

    <a href="{{ tath_edit(foo) }}">Edit</a>


Extra Data / Relationships
--------------------------
Sometimes forms need to pass along extra data. For example, say you had a database that 
contained Artists and Albums. Albums can be managed from an Artist page. You would want
to include an ID for the Artist when adding an Album, and you would want the Album's
setter for the Artist to be called when creating new Albums.

Your Album entity would have a @ManyToOne relationship like so:

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Artist")
     */
    private $artist;

Links to the new album form would be generated with an extra parameter like so:

    <a href="{{ tath_new('AppBundle:Album', {'artist':artist.id}) }}">New Album</a>

The key in the extra hash (artist) matches the name of the ManyToOne column, so Tath
is able to create this relationship when the add form is submitted. Edit forms won't
change the relationship so the extra parameter is not needed there as the column will
be untouched.

Extra parameters are also passed back to success redirects. So, your controller that
shows an artist can take an 'artist' parameter, and it will be filled from the extra
hash when it is used.
