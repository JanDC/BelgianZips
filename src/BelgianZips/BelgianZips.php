<?php


namespace BelgianZips;

class BelgianZips
{
    /** @var array */
    private $zipcodes;

    public function __construct()
    {
        $this->zipcodes = json_decode(file_get_contents(__DIR__ . '/../../var/zipcodes.json'), true);
    }

    /**
     * @param array $conditions
     *
     * @return array
     */
    private function filterZipcode(array $conditions = [])
    {
        return array_filter($this->zipcodes, function (array $instance) use ($conditions) {

            foreach ($conditions as $key => $condition) {
                if ($instance[$key] != $conditions) { // Type jugling
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
        return $this->filterZipcode(['zip' => $zipcode]);
    }

    /**
     * @param string $city
     * @return array
     */
    public function getInformationByCity(string $city)
    {
        return $this->filterZipcode(['city' => $city]);
    }

}