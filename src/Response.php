<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\HttpResponseException;
use App\Exceptions\JsonParseException;

/**
 * Class Response
 * @package App
 */
class Response
{
    /** @var string $httpVersion HTTP version string */
    private string $httpVersion;

    /** @var int $statusCode HTTP Status Code */
    private int $statusCode;

    /** @var string $reason Status code reason */
    private string $reason;

    /** @var array $headers Response headers */
    private array $headers;

    /** @var string $content Returned content */
    private string $content;


    /**
     * Set response headers
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Get response headers
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get response status code
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Set response status code
     * @param int $statusCode
     * @throws HttpResponseException When status code is 4xxx or 5xx
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;

        if ($statusCode >= 400 && $statusCode <= 599) {
            throw new HttpResponseException('Abnormal response status code: ' . $statusCode);
        }
    }

    /**
     * Set response content
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Get response content
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get array from JSON content
     * @return array
     */
    public function getJsonContent(): array
    {
        $jsonArray = json_decode($this->content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonParseException(json_last_error_msg());
        }

        return $jsonArray;
    }

    /**
     * Get HTTP Version from response
     * @return string
     */
    public function getHttpVersion(): string
    {
        return $this->httpVersion;
    }

    /**
     * Set response HTTP version
     * @param string $httpVersion
     */
    public function setHttpVersion(string $httpVersion): void
    {
        $this->httpVersion = $httpVersion;
    }

    /**
     * Get response reason
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * Set response reason
     * @param string $reason
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }
}
