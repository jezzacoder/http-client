<?php

declare(strict_types=1);

namespace App;

/**
 * Class ResponseBuilder - Builds Response object from RequestSender
 * @package App
 */
class ResponseBuilder
{
    /** @var Response $response Response object */
    private Response $response;


    /**
     * ResponseBuilder constructor.
     * @param RequestSender $requestSender
     */
    public function __construct(RequestSender $requestSender)
    {
        $this->response = new Response();
        $this->response->setContent($requestSender->getResponseContent());
        $this->parseMetaDataForResponse($requestSender->getResponseMetaData());
    }

    /**
     * Get the Response object
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * Parse response meta data (status, headers)
     * @param array $metaData
     */
    private function parseMetaDataForResponse(array $metaData)
    {
        // First element has status content
        $this->parseStatusResponse($metaData['wrapper_data'][0]);

        // Shift out status content, parse remainder headers
        array_shift($metaData['wrapper_data']);
        $this->parseResponseHeaders($metaData['wrapper_data']);
    }

    /**
     * Parses first line of response header
     * Expects "<http_version> <status_code> <reason>"
     * @param string $status
     */
    private function parseStatusResponse(string $status)
    {
        // Hoping server follows RFC 2616
        // @todo Validate and improve compatibility for non compliant servers
        [$httpVersion, $statusCode, $reason] = explode(' ', $status, 3);

        $this->response->setHttpVersion($httpVersion);
        $this->response->setStatusCode((int)$statusCode);
        $this->response->setReason($reason);
    }

    /**
     * Parses header lines and arranges in name => value array
     * @param array $headers
     */
    private function parseResponseHeaders(array $headers)
    {
        $headersArray = [];
        foreach ($headers as $headerLine) {
            // Expecting "Name: Value"
            $delimiterPosition = strpos($headerLine, ':');

            if ($delimiterPosition !== false) {
                $name = substr($headerLine, 0, $delimiterPosition);
                $value = ltrim(substr($headerLine, $delimiterPosition + 1));
            } else {
                // No ":" found so set name, ignore value
                $name = $headerLine;
                $value = '';
            }

            $headersArray[$name] = $value;
        }

        $this->response->setHeaders($headersArray);
    }
}
