<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\HttpConnectException;

/**
 * Class RequestSender - Sends http request
 * @package App
 */
class RequestSender
{
    /** @var string User Agent */
    private const USER_AGENT = 'Jeremy\'s Http Client';

    /** @var array $responseMetaData Response meta data */
    private array $responseMetaData;

    /** @var string $responseContent Response content */
    private string $responseContent;


    /**
     * RequestSender constructor.
     * @param string $url
     * @param Request $request
     */
    public function __construct(string $url, Request $request)
    {
        $this->sendRequest($url, $request);
    }

    /**
     * Get raw response content
     * @return string
     */
    public function getResponseContent(): string
    {
        return $this->responseContent;
    }

    /**
     * Get response meta data array
     * @return array
     */
    public function getResponseMetaData(): array
    {
        return $this->responseMetaData;
    }

    /**
     * Raw method which sends http request
     * @param string $url
     * @param Request $request
     */
    private function sendRequest(string $url, Request $request): void
    {
        if (!$fp = fopen($url, 'r', false, $this->getContextForRequest($request))) {
            throw new HttpConnectException('Unable to connect to ' . $url);
        }

        $this->responseMetaData = stream_get_meta_data($fp);
        $this->responseContent = stream_get_contents($fp);
        fclose($fp);
    }

    /**
     * Get Stream Context
     * @param Request $request
     * @return resource
     */
    private function getContextForRequest(Request $request)
    {
        return stream_context_create(
            [
                'http' => [
                    'method' => $request->getMethod(),
                    'user_agent' => self::USER_AGENT,
                    'header' => $request->getHeadersAsString(),
                    'content' => $request->getContent(),
                    'ignore_errors' => true,
                ]
            ]
        );
    }
}
