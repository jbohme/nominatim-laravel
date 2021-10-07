<?php
namespace NominatimLaravel\Content;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use NominatimLaravel\Exceptions\NominatimException;
use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;

/**
 *  Wrapper to manage exchanges with OSM Nominatim API.
 *
 * @see http://wiki.openstreetmap.org/wiki/Nominatim
 */
class Nominatim
{
    /**
     * Contain url of the current application.
     * @var string $application_url
     */
    private $application_url;

    /**
     * Contain default request headers.
     * @var array $defaultHeaders
     */
    private $defaultHeaders;

    /**
     * Contain http client connection.
     * @var Client
     */
    private $http_client;

    /**
     * The search object which serves as a template for new ones created
     * by 'newSearch()' method.
     * @var Search
     */
    private $baseSearch;

    /**
     * Template for new ones created by 'newReverser()' method.
     * @var Reverse
     */
    private $baseReverse;

    /**
     * Template for new ones created by 'newLookup()' method.
     * @var Lookup
     */
    private $baseLookup;

    /**
     * Template for new ones created by 'newDetails()' method.
     * @var Details
     */
    private $baseDetails;

    /**
     * Nominatim constructor.
     * @param string $application_url Contain url of the current application
     * @param array $defaultHeaders Client object from Guzzle
     * @param Client|null $http_client Define default header for all request
     * @throws NominatimException
     */
    public function __construct(
        string $application_url,
        array $defaultHeaders = [],
        ?Client $http_client = null
    ) {
        if (empty($application_url)) {
            throw new NominatimException('Application url parameter is empty');
        }

        if (null === $http_client) {
            $http_client = new Client([
                'base_uri' => $application_url,
                'timeout' => 30,
                'connection_timeout' => 5,
            ]);
        } elseif ($http_client instanceof Client) {
            $application_url_client = (string)$http_client->getConfig('base_uri');

            if (empty($application_url_client)) {
                throw new NominatimException('http_client must have a configured base_uri.');
            }

            if ($application_url_client !== $application_url) {
                throw new NominatimException('http_client parameter hasn\'t the same url application.');
            }
        } else {
            throw new NominatimException('http_client parameter must be a \\GuzzleHttp\\Client object or empty');
        }

        $this->application_url = $application_url;
        $this->defaultHeaders = $defaultHeaders;
        $this->http_client = $http_client;

        //Create base
        $this->baseSearch = new Search();
        $this->baseReverse = new Reverse();
        $this->baseLookup = new Lookup();
        $this->baseDetails = new Details();
    }

    /**
     * Returns a new search object based on the base search.
     * @return Search
     */
    public function newSearch(): Search
    {
        return clone $this->baseSearch;
    }

    /**
     * Returns a new reverse object based on the base reverse.
     * @return Reverse
     */
    public function newReverse(): Reverse
    {
        return clone $this->baseReverse;
    }

    /**
     * Returns a new lookup object based on the base lookup.
     * @return Lookup
     */
    public function newLookup(): Lookup
    {
        return clone $this->baseLookup;
    }

    /**
     * Returns a new datails object based on the base details.
     * @return Details
     */
    public function newDetails(): Details
    {
        return clone $this->baseDetails;
    }

    /**
     * Runs the query and returns the result set from Nominatim.
     * @param QueryInterface $nRequest
     * @param array $headers
     * @return array|SimpleXMLElement
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function find(QueryInterface $nRequest, array $headers = [])
    {
        $url = $this->application_url.'/'.$nRequest->getPath().'?';
        $request = new Request('GET', $url, array_merge($this->defaultHeaders, $headers));

        //Convert the query array to string with space replace to +
        $query = \GuzzleHttp\Psr7\Query::build($nRequest->getQuery(), PHP_QUERY_RFC1738);

        $url = $request->getUri()->withQuery($query);
        $request = $request->withUri($url);

        return $this->decodeResponse(
            $nRequest->getFormat(),
            $request,
            $this->http_client->send($request)
        );
    }

    /**
     * Return the client using by instance.
     */
    public function getClient(): Client
    {
        return $this->http_client;
    }

    /**
     * Decode the data returned from the request.
     * @param string $format
     * @param Request $request
     * @param ResponseInterface $response
     * @return mixed|SimpleXMLElement
     * @throws NominatimException
     */
    private function decodeResponse(string $format, Request $request, ResponseInterface $response)
    {
        if ('json' === $format || 'jsonv2' === $format || 'geojson' === $format || 'geocodejson' === $format) {
            return json_decode($response->getBody()->getContents(), true);
        }

        if ('xml' === $format) {
            return new SimpleXMLElement($response->getBody()->getContents());
        }

        throw new NominatimException('Format is undefined or not supported for decode response', $request, $response);
    }
}
