<?php


namespace AP\Bundle\BugTrackerBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
