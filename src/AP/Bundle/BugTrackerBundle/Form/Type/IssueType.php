<?php

namespace AP\Bundle\BugTrackerBundle\Form\Type;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use AP\Bundle\BugTrackerBundle\Form\DataProvider\IssueFormDataProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType
{
    const NAME = 'bug_tracker_issue';
    
    const DATA_CLASS = 'AP\Bundle\BugTrackerBundle\Entity\Issue';

    /**
     * @var IssueFormDataProviderInterface
     */
    protected $formDataProvider;

    /**
     * IssueType constructor.
     * @param IssueFormDataProviderInterface $formDataProvider
     */
    public function __construct(IssueFormDataProviderInterface $formDataProvider)
    {
        $this->formDataProvider = $formDataProvider;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary', 'text', [
                'label' => 'ap.bugtracker.issue.summary.label'
            ])
            ->add('description', 'textarea', [
                'required' => false,
                'label' => 'ap.bugtracker.issue.description.label'
            ])
            ->add('priority', 'entity', [
                'class' => 'AP\Bundle\BugTrackerBundle\Entity\Priority',
                'required' => true,
                'label' => 'ap.bugtracker.issue.priority.label'
            ])
            ->add('resolution', 'entity', [
                'class' => 'AP\Bundle\BugTrackerBundle\Entity\Resolution',
                'required' => false,
                'label' => 'ap.bugtracker.issue.resolution.label'
            ])
            ->add('tags', 'oro_tag_select', [
                'label' => 'oro.tag.entity_plural_label'
            ])
            ->add('assignee', 'oro_user_organization_acl_select', [
                'required' => false,
                'label' => 'orocrm.contact.assigned_to.label'
            ]);

            $types = $this->formDataProvider->getTypeChoices();
            $this->addTypeField($builder, $types);

            $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => self::DATA_CLASS,
            'intention' => 'issue_item'
        ]);
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSetData(FormEvent $event)
    {
        /** @var Issue $issue */
        $issue = $event->getData();
        if ($issue && $issue->getParentIssue()) {
            $data = $this->formDataProvider->getSubtaskTypeChoices();
            $this->addTypeField($event->getForm(), $data);
        }
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @param FormBuilderInterface|FormInterface $builder
     * @param array $data
     */
    protected function addTypeField($builder, array $data)
    {
        $builder->add('type', 'choice', [
            'label' => 'ap.bugtracker.issue.type.label',
            'choices' => $data
        ]);
    }
}
