<?php

namespace App\Exceptions;

/**
 * Class HttpResponseException - Exception for Response
 * Ideally should have child exceptions for 4xx, 5xx, etc
 * @package App\Exceptions
 */
class HttpResponseException extends HttpClientException
{

}
