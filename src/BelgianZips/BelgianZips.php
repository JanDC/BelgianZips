<?php


namespace BelgianZips;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;

class BelgianZips
{
    const CACHE_IDENTIFIER = 'belgianzips.zipcodes';


    /** @var array */
    private $zipCodes;

    /** @var ArrayCache */
    private $cache;

    public function __construct(Cache $cache = null)
    {
        $this->cache = $cache;

        if (is_null($cache)) {
            $this->cache = new ArrayCache();
        }

        if (!$this->cache->contains(self::CACHE_IDENTIFIER)) {
            $zipcodes = json_decode(file_get_contents(__DIR__ . '/../../var/zipcodes.json'), true);
            $this->cache->save(self::CACHE_IDENTIFIER, $zipcodes);
        }

        $this->zipCodes = $this->cache->fetch(self::CACHE_IDENTIFIER);
    }

    /**
     * @param array $conditions
     *
     * @return array
     */
    private function filterZipcodes(array $conditions = [])
    {
        return array_filter($this->zipCodes, function (array $instance) use ($conditions) {

            foreach ($conditions as $key => $condition) {
                if (strtolower($instance[$key]) != strtolower($condition)) { // Type/case juggling
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * @param string $zipcode
     * @return array
     */
    public function getZipCodeInformation(string $zipcode)
    {
        return $this->filterZipcodes(['zip' => $zipcode]);
    }

    /**
     * @param string $city
     * @return array
     */
    public function getInformationByCity(string $city)
    {
        return $this->filterZipcodes(['city' => $city]);
    }

}