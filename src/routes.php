<?php

use BadDeeds\Controller\Api;
// Routes


// Insert new deed.
$app->post('/insert', function ($request, $response, $args) {
    $badDeed = new Api($this->db);
    var_dump($request);
});

// List latest deeds.
$app->get('/list[/{page}[/{size}]]', function ($request, $response, $args) {
    $this->logger->info("route /list");
    $badDeed = new Api($this->db);
    $page = isset($args['page']) ? (int) $args['page'] : 0;
    $size = isset($args['size']) ? (int) $args['size'] : 10;


    $response->withJson($badDeed->list($page, $size));
});

// Identify the application.
$app->get('/', function ($request, $response, $args) {
    $this->logger->info("route /");

    // Render index view
    $response->withJson([
        'app' => 'BadDeed',
        'version' => '0.1.0',
        'type' => 'restful',
    ]);
});
