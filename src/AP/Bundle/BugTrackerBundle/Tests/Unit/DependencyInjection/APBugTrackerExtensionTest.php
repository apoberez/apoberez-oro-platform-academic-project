<?php


namespace AP\Bundle\BugTrackerBundle\Tests\Unit\DependencyInjection;

use AP\Bundle\BugTrackerBundle\DependencyInjection\APBugTrackerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class APBugTrackerExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var APBugTrackerExtension
     */
    private $extension;

    /**
     * @var ContainerBuilder
     */
    private $container;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new APBugTrackerExtension();
    }

    public function testLoad()
    {
        $this->extension->load([], $this->container);
        $this->assertSame(
            'AP\Bundle\BugTrackerBundle\Entity\Issue',
            $this->container->getParameter('ap.bug_tracker.issue.entity.class')
        );
    }
}
