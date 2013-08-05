<?php

namespace App\CatalogBundle\Extension;


/**
 * Класс транслитерации
 * Class Transliteration
 * @package App\CatalogBundle\Extension
 */
class Transliteration
{
    /**
     * Константы разделителей
     */
    const
        SEPARATOR_DASH = '-';

    /**
     * Константы алфавитов alphabet_from -> alphabet_to
     */
    const
        ALPHABET_CYR_LAT = 1;

    /**
     * Массив алфавитов
     *
     * @var array
     */
    public static $alphabet = array(
        self::ALPHABET_CYR_LAT => array(
            array(
                'а' => 'a',  'б' => 'b',  'в' => 'v',   'г' => 'g',  'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'zh',
                'з' => 'z',  'и' => 'i',  'й' => 'y',   'к' => 'k',  'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
                'п' => 'p',  'р' => 'r',  'с' => 's',   'т' => 't',  'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
                'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '',  'э' => 'e', 'ю' => 'yu',
                'я' => 'ya'
            ),
            array(
                'а' => 'a',  'б' => 'b',  'в' => 'v',   'г' => 'g',  'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'zh',
                'з' => 'z',  'и' => 'i',  'й' => 'y',   'к' => 'k',  'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
                'п' => 'p',  'р' => 'r',  'с' => 's',   'т' => 't',  'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
                'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '',  'э' => 'e', 'ю' => 'yu',
                'я' => 'ya',
                'А' => 'A',  'Б' => 'B',  'В' => 'V',   'Г' => 'G',  'Д' => 'D', 'Е' => 'E', 'Ё' => 'E', 'Ж' => 'ZH',
                'З' => 'Z',  'И' => 'I',  'Й' => 'Y',   'К' => 'K',  'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
                'П' => 'P',  'Р' => 'R',  'С' => 'S',   'Т' => 'T',  'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
                'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',  'Э' => 'E', 'Ю' => 'YU',
                'Я' => 'YA'
            )
        )
    );

    /**
     * Возвращает транслитерированный текст
     * @param $text текст
     * @param int $alphabet константа алфавита
     * @param string $separator разделитель
     * @param bool $caseSensitive флаг "чувствительно к регистру"
     * @return mixed
     */
    public static function get($text, $alphabet = self::ALPHABET_CYR_LAT, $separator = self::SEPARATOR_DASH, $caseSensitive = false)
    {
        if(!$caseSensitive) {
            $text = mb_strtolower($text, 'UTF-8');
        }

        $text = strtr($text, self::$alphabet[$alphabet][$caseSensitive]);

        $text = preg_replace('/[^A-я0-9-]+/', $separator, $text);
        $text = preg_replace('/\-$/', '', $text);
        $text = preg_replace('/^\-/', '', $text);

        return $text;
    }
}