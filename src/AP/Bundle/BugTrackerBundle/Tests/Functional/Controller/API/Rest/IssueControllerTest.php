<?php


namespace AP\Bundle\BugTrackerBundle\Tests\Functional\Controller\API\Rest;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * Class IssueControllerTest
 * @package AP\Bundle\BugTrackerBundle\Tests\Functional\API\Rest
 *
 * @dbIsolation
 */
class IssueControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], $this->generateWsseAuthHeader());
        $this->loadFixtures(['AP\Bundle\BugTrackerBundle\Tests\Functional\DataFixtures\LoadIssueData']);
    }

    public function testGetListAction()
    {
        $this->client->request('GET', '/api/rest/latest/bug-tracker/issue/list');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $responseData = $this->getDecodedResponse();

        $this->assertSame(10, count($responseData));
    }

    public function testGetIssueItemAction()
    {
        $expectedIssue = $this->getIssueRepository()->findOneBy([]);
        $url = '/api/rest/latest/bug-tracker/issues/' . $expectedIssue->getId() . '/item';
        $this->client->request('GET', $url);

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $responseData = $this->getDecodedResponse();

        $this->assertEquals($expectedIssue->getId(), $responseData->id);
        $this->assertEquals($expectedIssue->getSummary(), $responseData->summary);
        $this->assertEquals($expectedIssue->getCreatedAt(), new \DateTime($responseData->createdAt));
        $this->assertEquals($expectedIssue->getUpdatedAt(), new \DateTime($responseData->updatedAt));
        $this->assertEquals($expectedIssue->getPriority()->getName(), $responseData->priority);
        $this->assertEquals($expectedIssue->getResolution()->getName(), $responseData->resolution);
    }

    public function testCreateAction()
    {
        $priority = $this->client->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('APBugTrackerBundle:Priority')
            ->findOneBy([]);

        $requestData = [
            'summary' => 'Test API create issue method summary',
            'description' => 'Test API create issue method description',
            'priority' => $priority->getId(),
            'type' => 'story'
        ];
        $this->client->request('POST', '/api/rest/latest/bug-tracker/issues', $requestData);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $response = $this->getDecodedResponse();

        /** @var Issue $savedIssue */
        $savedIssue = $this->getIssueRepository()->find($response->id);

        $this->assertEquals($savedIssue->getSummary(), $requestData['summary']);
        $this->assertEquals($savedIssue->getDescription(), $requestData['description']);
        $this->assertEquals($savedIssue->getPriority()->getId(), $requestData['priority']);
    }


    public function testUpdateAction()
    {
        $priority = $this->client->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('APBugTrackerBundle:Priority')
            ->findOneBy([]);

        /** @var Issue $issue */
        $issue = $this->getIssueRepository()->findOneBy([]);

        $requestData = [
            'summary' => 'Test API update issue method summary',
            'description' => 'Test API update issue method description',
            'priority' => $priority->getId()
        ];
        $this->client->request('PUT', '/api/rest/latest/bug-tracker/issues/' . $issue->getId(), $requestData);

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        /** @var Issue $updatedIssue */
        $updatedIssue = $this->getIssueRepository()->findOneBy(['summary' => $requestData['summary']]);

        $this->assertEquals($updatedIssue->getSummary(), $requestData['summary']);
        $this->assertEquals($updatedIssue->getDescription(), $requestData['description']);
        $this->assertEquals($updatedIssue->getPriority()->getId(), $requestData['priority']);
    }

    public function testDeleteIssue()
    {
        $issue = $this->getIssueRepository()->findOneBy([]);

        $this->client->request('DELETE', '/api/rest/latest/bug-tracker/issues/' . $issue->getId());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $issueAfterDelete = $issue = $this->getIssueRepository()->findOneBy(['id' => $issue->getId()]);

        $this->assertNull($issueAfterDelete);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getIssueRepository()
    {
        return $this->client->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('APBugTrackerBundle:Issue');
    }

    /**
     * @return mixed
     */
    protected function getDecodedResponse()
    {
        return json_decode($this->client->getResponse()->getContent());
    }
}
