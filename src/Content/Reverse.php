<?php
namespace NominatimLaravel\Content;

use NominatimLaravel\Exceptions\InvalidParameterException;

/**
 * Reverse Geocoding a OSM nominatim service for places.
 *
 * @see http://wiki.openstreetmap.org/wiki/Nominatim
 */
class Reverse extends Query
{
    /**
     * OSM Type accepted (Node/Way/Relation).
     * @var array
     */
    public $osmType = ['N', 'W', 'R'];

    /**
     * Constructor.
     * @param array $query Default value for this query
     */
    public function __construct(array &$query = [])
    {
        parent::__construct($query);

        $this->setPath('reverse');
    }

    /**
     * [osmType description].
     * @param string $type
     * @return $this
     * @throws InvalidParameterException
     */
    public function osmType(string $type): self
    {
        if (\in_array($type, $this->osmType, true)) {
            $this->query['osm_type'] = $type;

            return $this;
        }

        throw new InvalidParameterException('OSM Type is not supported');
    }

    /**
     * A specific osm node / way / relation to return an address for.
     * @param int $id
     * @return $this
     */
    public function osmId(int $id): self
    {
        $this->query['osm_id'] = $id;

        return $this;
    }

    /**
     * The location to generate an address for.
     * @param float $lat
     * @param float $lon
     * @return $this
     */
    public function latlon(float $lat, float $lon): self
    {
        $this->query['lat'] = $lat;
        $this->query['lon'] = $lon;

        return $this;
    }

    /**
     * Level of detail required where 0 is country and 18 is house/building.
     * @param int $zoom
     * @return $this
     */
    public function zoom(int $zoom): self
    {
        $this->query['zoom'] = (string) $zoom;

        return $this;
    }
}
