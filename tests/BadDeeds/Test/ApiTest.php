<?php

namespace BadDeeds\Test;

use BadDeeds\Controller\Api;

/**
 * Contains a set of unit test to check on the API calls.
 */
class ApiTest extends LocalWebTestCase
{
    protected $db;

    /**
     * Get database connection.
     */
    protected function getConnection() : \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
    {
        if (null === $this->db) {
            $this->db = $this->app->getContainer()['db'];
        }

        return $this->createDefaultDBConnection($this->db, ':memory:');
    }

    /**
     * Get initial dataset.
     */
    protected function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__ . '/_files/seed.xml');
    }

    /**
     * Test a list of deeds.
     *
     * @return void
     */
    private function evaluateList()
    {
        $body = $this->client->getResponse()->getBody();
        $data = json_decode($body);
        $this->assertEquals(Api::DEFAULT_SIZE, count($data));
        foreach ($data as $row) {
            $this->assertNotNull($row);
            $this->assertObjectHasAttribute('id', $row);
            $this->assertObjectHasAttribute('subject', $row);
            $this->assertObjectHasAttribute('description', $row);
        }
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->db->exec('DELETE FROM deed');
    }

    /**
     * Test how it responds to a request to a page that doesn't exists.
     *
     * @return void
     */
    public function testPageNotFound()
    {
        $this->client->get('/wrongpage');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test the index page, that should output info about the api.
     *
     * @return void
     */
    public function testVersion()
    {
        $this->client->get('/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $body = $this->client->getResponse()->getBody();
        $data = json_decode($body);
        $this->assertNotNull($data);
        $this->assertObjectHasAttribute('app', $data);
        $this->assertObjectHasAttribute('version', $data);
        $this->assertObjectHasAttribute('type', $data);
    }

    /**
     * Test a request to get a list of deeds with default parameters.
     *
     * @return void
     */
    public function testDefaultList()
    {
        $this->client->get('/list');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->evaluateList();
    }

    /**
     * Test a request to get a list of deeds from the second page.
     *
     * @return void
     */
    public function testDefaultListSecondPage()
    {
        $this->client->get('/list/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->evaluateList();
    }
}
