<?php


namespace AP\Bundle\BugTrackerBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/** todo add parent for subtasks */
class IssueApiType extends IssueType
{
    const NAME = 'issue';

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
            [
                'csrf_protection' => false
            ]
        );
    }
}
