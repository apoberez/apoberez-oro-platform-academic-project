<?php


namespace AP\Bundle\TaskTrackerBundle\Tests\Functional\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

class IssueControllerTest extends WebTestCase
{

    protected function setUp()
    {
        $this->initClient([], array_merge($this->generateBasicAuthHeader(), ['HTTP_X-CSRF-Header' => 1]));
    }

    public function testIndexPage()
    {
        $crawler = $this->client->request('GET', '/task-tracker/');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
