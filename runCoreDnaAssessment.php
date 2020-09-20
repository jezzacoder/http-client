<?php

/**
 * Run the Core Dna Assessment
 *
 * @package HttpClient
 * @author  Jeremy Mason <nospam@acme.com>
 * @link https://github.com/jezzacoder/http-client
 */

use App\HttpClient;
use App\Request;

// Using Composer just for autoloader
require dirname(__FILE__) . '/vendor/autoload.php';

try {
    // Create HttpClient for assessment endpoint
    $client = new HttpClient('https://www.coredna.com/assessment-endpoint.php');

    // Send OPTIONS request to get authentication token
    $authToken = $client->sendRequest(new Request('OPTIONS'))->getContent();


    // Now send my details to endpoint with auth token
    $submitResponse = $client->sendRequest(
        new Request(
            'POST',
            [
                'name' => 'Jeremy Mason',
                'email' => base64_decode('amV6emFtYW4xOUBnbWFpbC5jb20='), // Attempt to hide email from SPAM
                'url' => 'https://github.com/jezzacoder/http-client',
                ],
            [
                'Authorization' => 'Bearer ' . $authToken
                ]
        )
    );

    // Echo submit response
    echo $submitResponse->getContent();
} catch (Throwable $e) {
    echo 'An error was encountered:' . PHP_EOL;
    echo get_class($e) . ': ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
