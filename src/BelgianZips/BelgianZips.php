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
    private function filterZipcodes(array $conditions = [])
    {
        return array_filter($this->zipcodes, function (array $instance) use ($conditions) {

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