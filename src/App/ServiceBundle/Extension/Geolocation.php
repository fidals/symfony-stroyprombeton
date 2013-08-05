<?php

namespace App\ServiceBundle\Extension;

class Geolocation
{
    static $instance = null;

    static function instance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new Geolocation();
        }
        return self::$instance;
    }

    public function __construct()
    {
        GeolocationService::fillModel($this);
    }

    private $country = '';
    private $city = '';
    private $region = '';
    private $district = '';
    private $latitude = 0.0;
    private $longitude = 0.0;

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setDistrict($district)
    {
        $this->district = $district;
        return $this;
    }

    public function getDistrict()
    {
        return $this->district;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    public function getRegion()
    {
        return $this->region;
    }
}