<?php
namespace Tath\FormBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Tath\Core\Classes\AnnotationTool;
use Tath\Core\Classes\Collection;
use Tath\Core\Exceptions\MissingAnnotationException;
use Tath\FormBundle\Classes\FormPopulator;
use Tath\FormBundle\Configuration\Form;
use Tath\FormBundle\Configuration\FormAction;

class FormController extends Controller
{
    private $extra;

    /**
     * @var AnnotationTool
     */
    private $annotationTool;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function formAction(Request $request, $name, $id = null, $extra = null)
    {
        $this->extra = $extra;
        $entityManager = $this->get('doctrine.orm.default_entity_manager');
        $entityNaming = $this->get('tath_entity_naming');
        $className = $entityNaming->routeToEntityName($name);
        $meta = $entityManager->getClassMetadata($className);
        $longName = $meta->getName();
        $entity = is_null($id) ?
            new $longName() :
            $entityManager->find($className, $id);

        $this->annotationTool = AnnotationTool::make($longName);
        $formAnnotations = $this->annotationTool->getClassAnnotationsOfType(Form::class);
        if (count($formAnnotations) === 0) {
            throw MissingAnnotationException::makeWithAnnotationNameAndFrom('Form', $entity);
        }
        /**
         * @var Form $formAnnotation
         */
        $formAnnotation = reset($formAnnotations);

        $form = FormPopulator::make($this->createFormBuilder($entity), $this->get('event_dispatcher'))
            ->populate()
            ->getBuilder()
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->objectManager = $this->getDoctrine()->getManager();
            $this->populateEntityFromExtra($entity);
            $this->objectManager->persist($entity);
            $this->objectManager->flush();
            return $this->successRedirect($form);
        }

        $template = $formAnnotation->template ??
            ($this->container->hasParameter('tath_form.template') ?
                $this->getParameter('tath_form.template') :
                'TathFormBundle:Default:index.html.twig');
        return $this->render($template, [
            'form' => $form->createView()
        ]);
    }

    private function successRedirect(FormInterface $form)
    {
        $forms = $this->annotationTool->getClassAnnotationsOfType(Form::class);
        $formAnnotation = reset($forms);
        $action = Collection::make($this->annotationTool->getClassAnnotationsOfType(FormAction::class))
                ->first(function (FormAction $action) use ($form, &$action) {
                    $button = $form->get($action->label);
                    return ($button instanceof ClickableInterface) && $button->isClicked();
                }, $formAnnotation);
        return $this->redirectToRoute($action->success_redirect, $this->getExtraMap());
    }

    private function getExtraMap()
    {
        $extra = [];
        if (empty($this->extra)) {
            return $extra;
        }
        $parts = explode('/', $this->extra);
        while (count($parts) > 0) {
            $key = array_shift($parts);
            $value = (count($parts) > 0) ? array_shift($parts) : null;
            $extra[$key] = $value;
        }
        return $extra;
    }

    private function populateEntityFromExtra($entity)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $tool = AnnotationTool::make(get_class($entity));
        $relationships = $tool->getPropertiesWith(ManyToOne::class);
        $entityManager = $this->get('doctrine.orm.default_entity_manager');
        Collection::make($this->getExtraMap())
            ->filter(function ($value, $property) use ($entity, $accessor, $relationships) {
                return $accessor->isWritable($entity, $property) && isset($relationships[$property]);
            })
            ->each(function ($value, $property) use ($entity, $accessor, $relationships, $entityManager) {
                /**
                 * @var ManyToOne $relationship
                 */
                $relationship = reset($relationships[$property]);
                $reference = $entityManager->getReference($relationship->targetEntity, $value);
                $accessor->setValue($entity, $property, $reference);
            });
    }
}
