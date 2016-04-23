<?php

namespace BadDeeds\Test;

/**
 * Base class for Slim PHPUnit.
 */
abstract class LocalWebTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    protected $app;
    protected $client;

    /**
     * Setup the environment.
     *
     * @return void.
     */
    public function setup()
    {
        $this->app = $this->getSlimInstance();
        $this->client = new WebTestClient($this->app);
        parent::setup();
    }
    
    /**
     * Mock the application bootstrap and returns the Slim App.
     *
     * @return \Slim\App Slim application object.
     */
    public function getSlimInstance() : \Slim\App
    {
        // Instantiate the app

        // Get settings from src/setting but override with phpunit settings.
        $settings = array_replace_recursive(
            require __DIR__ . '/../../../src/settings.php',
            eval($GLOBALS['settings'])
        );

        $app = new \Slim\App($settings);

        // Set up dependencies
        require __DIR__ . '/../../../src/dependencies.php';

        // Register middleware
        require __DIR__ . '/../../../src/middleware.php';

        // Register routes
        require __DIR__ . '/../../../src/routes.php';

        return $app;
    }
}
