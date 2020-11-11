<?php
namespace NominatimLaravel\Content;

use NominatimLaravel\Exceptions\InvalidParameterException;

/**
 * Lookup the address of one or multiple OSM objects like node, way or relation.
 *
 * @see http://wiki.openstreetmap.org/wiki/Nominatim
 */
class Lookup extends Query
{
    /**
     * Lookup constructor.
     * @param array $query
     */
    public function __construct(array &$query = [])
    {
        parent::__construct($query);

        $this->setPath('lookup');
    }

    /**
     * A list of up to 50 specific osm node, way or relations ids to return the addresses for.
     * @param string $id
     * @return $this
     */
    public function osmIds(string $id): self
    {
        $this->query['osm_ids'] = $id;

        return $this;
    }

    /**
     * Output format for the geometry of results.
     * @param string $polygon
     * @throws InvalidParameterException
     */
    public function polygon(string $polygon): void
    {
        throw new InvalidParameterException('The polygon is not supported with lookup');
    }
}
