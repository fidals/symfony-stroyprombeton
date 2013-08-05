<?php

namespace App\ServiceBundle\Extension;

/**
 * Класс геолокации
 *
 * Class GeolocationService
 * @package App\ServiceBundle\Extension
 */
class GeolocationService
{

    /**
     * ссылка на api геолокации
     */
    const GEOBASE_LINK = 'ipgeobase.ru:7020/geo?ip=';

    /**
     * заполняет модель геолокации
     * @param Geolocation $model
     * @return array
     */
    static function fillModel(Geolocation $model)
    {
        // получаем данные по ip
        $link = self::GEOBASE_LINK . '109.195.84.188';//self::getIp();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        $string = curl_exec($ch);

        $data = self::_parseXmlResponse($string);
        if(!empty($data)) {
            $model->setRegion($data['region'])
                ->setLatitude($data['lat'])
                ->setLongitude($data['lng'])
                ->setCity($data['city'])
                ->setCountry($data['country'])
                ->setDistrict($data['district']);
        }
        return $data;
    }

    /**
     * Парсит полученный ответ сервера геолокации
     * @param $string
     * @return array
     */
    static function _parseXmlResponse($string)
    {
        $pa['inetnum'] = '#<inetnum>(.*)</inetnum>#is';
        $pa['country'] = '#<country>(.*)</country>#is';
        $pa['city'] = '#<city>(.*)</city>#is';
        $pa['region'] = '#<region>(.*)</region>#is';
        $pa['district'] = '#<district>(.*)</district>#is';
        $pa['lat'] = '#<lat>(.*)</lat>#is';
        $pa['lng'] = '#<lng>(.*)</lng>#is';
        $data = array();
        foreach($pa as $key => $pattern) {
            if(preg_match($pattern, $string, $out)) {
                $data[$key] = trim($out[1]);
            }
        }
        return $data;
    }

    /**
     * Возвращает ip клиента
     * @return bool
     */
    static function getIp()
    {
        $ip = false;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipa[] = trim(strtok($_SERVER['HTTP_X_FORWARDED_FOR'], ','));
        }

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipa[] = $_SERVER['HTTP_CLIENT_IP'];
        }

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipa[] = $_SERVER['REMOTE_ADDR'];
        }

        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ipa[] = $_SERVER['HTTP_X_REAL_IP'];
        }

        // проверяем ip-адреса на валидность начиная с приоритетного.
        foreach($ipa as $ips) {
            //  если ip валидный обрываем цикл, назначаем ip адрес и возвращаем его
            if(self::isValidIp($ips)) {
                $ip = $ips;
                break;
            }
        }
        return $ip;
    }

    /**
     * Валидирует ip клиента
     * @param null $ip
     * @return bool
     */
    static function isValidIp($ip = null)
    {
        if(preg_match("#^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$#", $ip)) {
            return true; // если ip-адрес попадает под регулярное выражение, возвращаем true
        }
        return false; // иначе возвращаем false
    }
}