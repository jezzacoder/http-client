<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\JsonParseException;

/**
 * Class Request
 * @package App
 */
class Request
{
    /** @var array $headers Request headers */
    private array $headers;

    /** @var string $content Request content */
    private string $content = '';

    /** @var string $method Request method (POST, GET, etc) */
    private string $method = '';


    /**
     * Request constructor.
     * @param string $method
     * @param string|array $content
     * @param array $headers
     */
    public function __construct(string $method, $content = '', array $headers = [])
    {
        $this->setMethod($method);
        $this->setHeaders($headers);
        $this->setContent($content);
    }

    /**
     * Get request method
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Set request method
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        // @todo Validate methods
        $this->method = $method;
    }

    /**
     * Get request content
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set request content
     * @param string|array $content Array content will be converted to JSON string and Content-Type header added
     */
    public function setContent($content): void
    {
        if (is_array($content)) {
            $this->processJson($content);
        } else {
            $this->content = $content;
        }
    }

    /**
     * Get request headers
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set request headers
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Add a request header
     * @param string $name
     * @param string $value
     */
    public function addHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    /**
     * Get headers as line separated string
     * @return string
     */
    public function getHeadersAsString(): string
    {
        $headerString = '';
        foreach ($this->headers as $name => $value) {
            $headerString .= "$name: $value\r\n";
        }

        return $headerString;
    }

    /**
     * Process JSON content
     * @param array $content
     */
    private function processJson(array $content)
    {
        $this->content = $this->toJson($content);
        $this->addHeader('Content-Type', 'application/json');
    }

    /**
     * Converts an array to JSON string
     * @param array $content
     * @return string
     * @throws JsonParseException When error encountered parsing to JSON string
     */
    private function toJson(array $content): string
    {
        $json = json_encode($content);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonParseException(json_last_error_msg());
        }

        return $json;
    }
}
