<?php


namespace AP\Bundle\BugTrackerBundle\Tests\Functional\Controller;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Form;

/**
 * Class IssueControllerTest
 * @package AP\Bundle\BugTrackerBundle\Tests\Functional\Controller
 *
 * @dbIsolation
 */
class IssueControllerTest extends WebTestCase
{

    protected function setUp()
    {
        $this->initClient([], array_merge($this->generateBasicAuthHeader(), ['HTTP_X-CSRF-Header' => 1]));
        $this->loadFixtures(['AP\Bundle\BugTrackerBundle\Tests\Functional\DataFixtures\LoadIssueData']);
    }

    public function testCreateIssue()
    {
        $crawler = $this->client->request('GET', '/bug-tracker/issue/create');
        $doctrine = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $priority = $doctrine->getRepository('APBugTrackerBundle:Priority')->findOneBy([]);
        $assignee = $doctrine->getRepository('OroUserBundle:User')->findOneBy([]);

        /** @var Form $form */
        $form = $crawler->selectButton('Save and Close')->form();

        $form['bug_tracker_issue[summary]'] = 'Test create new issue summary.';
        $form['bug_tracker_issue[description]'] = 'Test create new issue.';
        $form['bug_tracker_issue[assignee]'] = $assignee->getId();
        $form['bug_tracker_issue[priority]'] = $priority->getId();
        $form['bug_tracker_issue[type]'] = 'story';
        $form['bug_tracker_issue[tags][all]'] =
            '[{"id":"Test","name":"Test","owner":true,"notSaved":true,"moreOwners":false,"url":""}]';
        $form['bug_tracker_issue[tags][owner]'] =
            '[{"id":"Test","name":"Test","owner":true,"notSaved":true,"moreOwners":false,"url":""}]';
        $this->client->followRedirects(true);
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testCreateSubtask()
    {
        $manager = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $issue = new Issue();
        $issue->setType(Issue::TYPE_STORY)
            ->setCode('STORY-1')
            ->setPriority($manager->getRepository('APBugTrackerBundle:Priority')->findOneBy([]))
            ->setDescription('Subtask available issue description')
            ->setReporter($manager->getRepository('OroUserBundle:User')->find(1))
            ->setSummary('Subtask available issue')
        ;
        $manager->persist($issue);
        $manager->flush();

        $crawler = $this->client->request('GET', '/bug-tracker/issue/' . $issue->getId() . '/subtask');


        $form = $crawler->selectButton('Save and Close')->form();

        $form['bug_tracker_issue[summary]'] = 'Issue Summary';
        $form['bug_tracker_issue[type]'] = 'subtask';
        $this->client->followRedirects(true);
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testViewIssue()
    {
        $doctrine = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $issue = $doctrine->getRepository('APBugTrackerBundle:Issue')->findOneBy([]);

        $this->client->request('GET', '/bug-tracker/issue/' . $issue->getId());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testUpdateIssue()
    {
        $doctrine = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $issue = $doctrine->getRepository('APBugTrackerBundle:Issue')->findOneBy([]);

        $crawler = $this->client->request('GET', '/bug-tracker/issue/update/' . $issue->getId());
        $form = $crawler->selectButton('Save and Close')->form();

        $form['bug_tracker_issue[summary]'] = 'Issue update test Summary';


        $this->client->followRedirects(true);
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
