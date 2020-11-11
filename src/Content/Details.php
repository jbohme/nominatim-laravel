<?php
namespace NominatimLaravel\Content;

use NominatimLaravel\Exceptions\InvalidParameterException;

/**
 * Lookup details about a single place by id.
 *
 * @see http://wiki.openstreetmap.org/wiki/Nominatim
 */
class Details extends Query
{
    /**
     * OSM Type accepted (Node/Way/Relation).
     *
     * @var array
     */
    private $osmType = ['N', 'W', 'R'];

    /**
     * Constructor.
     * Details constructor.
     * @param array $query
     */
    public function __construct(array &$query = [])
    {
        parent::__construct($query);

        $this->setPath('details');

        $this->acceptedFormat[] = 'html';
        $this->acceptedFormat[] = 'jsonv2';
    }

    /**
     * Place information by placeId.
     * @param int $placeId
     * @return $this
     */
    public function placeId(int $placeId): self
    {
        $this->query['place_id'] = $placeId;

        return $this;
    }

    /**
     * [osmType description].
     * @param string $type
     * @return $this
     * @throws InvalidParameterException if osm type is not supported
     */
    public function osmType(string $type): self
    {
        if (\in_array($type, $this->osmType, true)) {
            $this->query['osmtype'] = $type;

            return $this;
        }

        throw new InvalidParameterException('OSM Type is not supported');
    }

    /**
     * Place information by osmtype and osmid.
     * @param int $osmId
     * @return $this
     */
    public function osmId(int $osmId): self
    {
        $this->query['osmid'] = $osmId;

        return $this;
    }
}
