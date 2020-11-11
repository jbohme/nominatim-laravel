<?php
namespace NominatimLaravel\Exceptions;

use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * InvalidParameterException exception is thrown when a request failed because of a bad client configuration.
 *
 * InvalidParameterException appears when the request failed because of a bad parameter from
 * the client request.
 *
 * @category Exceptions
 */
class NominatimException extends Exception
{
    /**
     * Contain the request.
     * @var RequestInterface|null
     */
    private $request;

    /**
     * Contain the response.
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * NominatimException constructor.
     * @param $message
     * @param RequestInterface|null $request
     * @param ResponseInterface|null $response
     * @param Exception|null $previous
     */
    public function __construct(
        $message,
        ?RequestInterface $request = null,
        ?ResponseInterface $response = null,
        ?Exception $previous = null
    ) {
        // Set the code of the exception if the response is set and not future.
        $code = $response && !($response instanceof PromiseInterface) ? $response->getStatusCode() : 0;

        parent::__construct($message, $code, $previous);

        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Return the Request.
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * Return the Response.
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }
}
