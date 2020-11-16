<?php
namespace NominatimLaravel\Content;

use NominatimLaravel\Exceptions\InvalidParameterException;

/**
 * Class implementing functionality common to requests nominatim.
 */
abstract class Query implements QueryInterface
{
    /**
     * Contain the path of the request.
     * @var string $path
     */
    protected $path;

    /**
     * Contain the query for request.
     * @var array $query
     */
    protected $query = [];

    /**
     * Contain the format for decode data returning by the request.
     * @var string $format
     */
    protected $format;

    /**
     * Output format accepted.
     * @var array $acceptedFormat
     */
    protected $acceptedFormat = ['xml', 'json', 'jsonv2', 'geojson', 'geocodejson'];

    /**
     * Output polygon format accepted.
     * @var array $polygon
     */
    protected $polygon = ['geojson', 'kml', 'svg', 'text'];

    /**
     * Query constructor.
     * @param array $query
     */
    public function __construct(array &$query = [])
    {
        if (empty($query['format'])) {
            //Default format
            $query['format'] = 'json';
        }

        $this->setQuery($query);
        $this->setFormat($query['format']);
    }

    /**
     * Format returning by the request.
     * @param string $format
     * @return $this
     * @throws InvalidParameterException
     */
    final public function format(string $format): self
    {
        $format = mb_strtolower($format);

        if (\in_array($format, $this->acceptedFormat, true)) {
            $this->setFormat($format);

            return $this;
        }

        throw new InvalidParameterException('Format is not supported');
    }

    /**
     *  Preferred language order for showing search results, overrides the value
     * specified in the "Accept-Language" HTTP header. Either uses standard
     * rfc2616 accept-language string or a simple comma separated list of
     * language codes.
     *
     * @param string $language Preferred language order for showing search results, overrides the value
     *                         specified in the "Accept-Language" HTTP header. Either uses standard rfc2616
     *                         accept-language string or a simple comma separated list of language codes.
     * @return $this
     */
    final public function language(string $language): self
    {
        $this->query['accept-language'] = $language;

        return $this;
    }

    /**
     * Include a breakdown of the address into elements.
     * @param bool $details
     * @return $this
     */
    public function addressDetails(bool $details = true): self
    {
        $this->query['addressdetails'] = $details ? '1' : '0';

        return $this;
    }

    /**
     * If you are making large numbers of request please include a valid email address or alternatively include your
     * email address as part of the User-Agent string. This information will be kept confidential and only used to
     * contact you in the event of a problem, see Usage Policy for more details.
     *
     * @param string $email
     * @return $this
     */
    public function email(string $email): self
    {
        $this->query['email'] = $email;

        return $this;
    }

    /**
     * Output format for the geometry of results.
     * @param string $polygon
     * @return $this
     * @throws InvalidParameterException
     */
    public function polygon(string $polygon)
    {
        if (\in_array($polygon, $this->polygon, true)) {
            $this->query['polygon_'.$polygon] = '1';

            return $this;
        }

        throw new InvalidParameterException('This polygon format is not supported');
    }

    /**
    * Include additional information in the result if available.
     * @param bool $tags
     * @return $this
     */
    public function extraTags(bool $tags = true): self
    {
        $this->query['extratags'] = $tags ? '1' : '0';

        return $this;
    }

    /**
     * Include a list of alternative names in the results.
     * These may include language variants, references, operator and brand.
     * @param bool $details
     * @return $this
     */
    public function nameDetails(bool $details = true): self
    {
        $this->query['namedetails'] = $details ? '1' : '0';

        return $this;
    }

    /**
     * Returns the URL-encoded query.
     * @return string
     */
    public function getQueryString(): string
    {
        return http_build_query($this->query);
    }

    /**
     * Get path.
     * @return string
     */
    final public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get query.
     * @return array
     */
    final public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Get format.
     * @return string
     */
    final public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * Set path.
     * @param string $path
     */
    protected function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * Set query.
     * @param array $query
     */
    protected function setQuery(array &$query = []): void
    {
        $this->query = $query;
    }

    /**
     * Set format.
     * @param string $format
     */
    protected function setFormat(string $format): void
    {
        $this->format = $this->query['format'] = $format;
    }

     /**
     * Include additional query parameter in URL.
     * @param $index
     * @param $value
     * @return self
     */
    public function addQuery($index, $value): self
    {
        $this->query[$index] = $value;

        return $this;
    }
}
