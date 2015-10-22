<?php

namespace AP\Bundle\BugTrackerBundle\Form\Type;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType
{
    const NAME = 'bug_tracker_issue';
    
    const DATA_CLASS = 'AP\Bundle\BugTrackerBundle\Entity\Issue';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary', 'text')
            ->add('description', 'text', [
                'required' => false
            ])
            ->add('priority', 'entity', [
                'class' => 'AP\Bundle\BugTrackerBundle\Entity\Priority',
                'required' => true,
            ])
            ->add('tags', 'oro_tag_select', [
                'label' => 'oro.tag.entity_plural_label'
            ])
            ->add('type', 'choice', [
                'label' => 'type',
                'required' => true,
                'choices' => Issue::getTypeNames()
            ]);
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => self::DATA_CLASS,
        ]);
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
}
