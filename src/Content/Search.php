<?php
namespace NominatimLaravel\Content;

use NominatimLaravel\Exceptions\InvalidParameterException;

/**
 * Searches a OSM nominatim service for places.
 *
 * @see http://wiki.openstreetmap.org/wiki/Nominatim
 */
class Search extends Query
{
    /**
     * Search constructor.
     * @param array $query
     */
    public function __construct(array &$query = [])
    {
        parent::__construct($query);

        $this->setPath('search');

        $this->acceptedFormat[] = 'html';
        $this->acceptedFormat[] = 'jsonv2';
    }

    /**
     * Query string to search for.
     * @param string $query
     * @return $this
     */
    public function query(string $query): self
    {
        $this->query['q'] = $query;

        return $this;
    }

    /**
     * Street to search for.
     * Do not combine with query().
     * @param string $street
     * @return $this
     */
    public function street(string $street): self
    {
        $this->query['street'] = $street;

        return $this;
    }

    /**
     * City to search for (experimental).
     * Do not combine with query().
     * @param string $city
     * @return $this
     */
    public function city(string $city): self
    {
        $this->query['city'] = $city;

        return $this;
    }

    /**
     * County to search for.
     * Do not combine with query().
     * @param string $county
     * @return $this
     */
    public function county(string $county): self
    {
        $this->query['county'] = $county;

        return $this;
    }

    /**
     * State to search for.
     * Do not combine with query().
     * @param string $state
     * @return $this
     */
    public function state(string $state): self
    {
        $this->query['state'] = $state;

        return $this;
    }

    /**
     * Country to search for.
     * Do not combine with query().
     * @param string $country
     * @return $this
     */
    public function country(string $country): self
    {
        $this->query['country'] = $country;

        return $this;
    }

    /**
     * Postal code to search for (experimental).
     * Do not combine with query().
     * @param string $postalCode
     * @return $this
     */
    public function postalCode(string $postalCode): self
    {
        $this->query['postalcode'] = $postalCode;

        return $this;
    }

    /**
     * Limit search results to a specific country (or a list of countries).
     * <countrycode> should be the ISO 3166-1alpha2 code, e.g. gb for the United
     * Kingdom, de for Germany, etc.
     * @param string $countrycode
     * @return $this
     * @throws InvalidParameterException
     */
    public function countryCode(string $countrycode): self
    {
        if (!preg_match('/^[a-z]{2}$/i', $countrycode)) {
            throw new InvalidParameterException("Invalid country code: \"{$countrycode}\"");
        }

        if (empty($this->query['countrycodes'])) {
            $this->query['countrycodes'] = $countrycode;
        } else {
            $this->query['countrycodes'] .= ','.$countrycode;
        }

        return $this;
    }

    /**
     * The preferred area to find search results.
     * @param string $left
     * @param string $top
     * @param string $right
     * @param string $bottom
     * @return $this
     */
    public function viewBox(string $left, string $top, string $right, string $bottom): self
    {
        $this->query['viewbox'] = $left.','.$top.','.$right.','.$bottom;

        return $this;
    }

    /**
     * If you do not want certain OpenStreetMap objects to appear in the search results.
     * @return $this
     * @throws InvalidParameterException
     */
    public function exludePlaceIds(): self
    {
        $args = \func_get_args();

        if (\count($args) > 0) {
            $this->query['exclude_place_ids'] = implode(', ', $args);

            return $this;
        }

        throw new InvalidParameterException('No place id in parameter');
    }

    /**
     * Limit the number of returned results.
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->query['limit'] = (string) $limit;

        return $this;
    }
}
