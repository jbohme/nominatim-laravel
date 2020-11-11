<?php
namespace NominatimLaravel\Content;

/**
 * QueryInterface for building request to Nominatim.
 */
interface QueryInterface
{
    /**
     * Get path of the request.
     *
     *  Example request :
     *  - Search = search
     *  - Reverse Geocoding = reverse
     */
    public function getPath(): string;

    /**
     * Get the query to send.
     */
    public function getQuery(): array;

    /**
     * Get the format of the request.
     *
     * Example : json or xml
     */
    public function getFormat(): string;
}
