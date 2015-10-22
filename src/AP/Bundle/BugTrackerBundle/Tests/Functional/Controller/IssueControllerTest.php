<?php


namespace AP\Bundle\BugTrackerBundle\Tests\Functional\Controller;

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

    public function testMainMenu()
    {
        //todo
    }

    public function testAllTrackerPagesAvailable()
    {
        $pages = [
            '/bug-tracker/issue/',
            '/bug-tracker/issue/create'
        ];

        foreach ($pages as $page) {
            $this->client->request('GET', $page);
            $this->assertTrue($this->client->getResponse()->isSuccessful());
        }

    }

    public function testCreateIssue()
    {
        $crawler = $this->client->request('GET', '/bug-tracker/issue/create');
        $doctrine = $this->getContainer()->get('doctrine.orm.entity_manager');
        $priority = $doctrine->getRepository('APBugTrackerBundle:Priority')->findOneBy([]);

        /** @var Form $form */
        $form = $crawler->selectButton('Save and Close')->form();

        $form['bug_tracker_issue[summary]'] = 'Issue Summary';
        $form['bug_tracker_issue[description]'] = 'Test create new issue.';
        $form['bug_tracker_issue[priority]'] = $priority->getId();
        $form['bug_tracker_issue[tags][all]'] =
            '[{"id":"Test","name":"Test","owner":true,"notSaved":true,"moreOwners":false,"url":""}]';
        $form['bug_tracker_issue[tags][owner]'] =
            '[{"id":"Test","name":"Test","owner":true,"notSaved":true,"moreOwners":false,"url":""}]';
        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains('Test create new issue.', $crawler->html());

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        //todo assert entity in db
        //todo assert tag association
    }

    public function testViewIssue()
    {

    }
}
