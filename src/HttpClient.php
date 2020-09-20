<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\HttpClientException;

/**
 * Class HttpClient
 * @package App
 */
class HttpClient
{
    /**
     * @var string $url URL for http-client
     */
    private string $url;


    /**
     * HttpClient constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Sends request
     * @param Request $request
     * @return Response
     */
    public function sendRequest(Request $request): Response
    {
        $this->validateURL();

        $responseBuilder = new ResponseBuilder(new RequestSender($this->url, $request));

        return $responseBuilder->getResponse();
    }

    /**
     * @return void
     * @throws HttpClientException
     */
    private function validateURL(): void
    {
        if (filter_var($this->url, FILTER_VALIDATE_URL) === false) {
            throw new HttpClientException('URL is not valid');
        }
    }
}
