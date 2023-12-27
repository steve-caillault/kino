<?php

/**
 * Classe utilitaire pour la gestion des dates
 */

namespace App;

use Illuminate\Support\Facades\App as LaravelApplication;

final class Date {

    /**
     * Formate la date en fonction de la locale
     * @param \DateTimeImmutable $datetime
     * @param array $options
     * @return string|false
     */
    public static function getI18nFormat(\DateTimeImmutable $datetime, array $options = []) : string|false
    {
        $dateTimeZoneName = $datetime->getTimezone()->getName();

        $locale = LaravelApplication::getLocale();

        $timeType = ($options['timeType'] ?? \IntlDateFormatter::MEDIUM);
        $dateType = ($options['dateType'] ?? \IntlDateFormatter::FULL);

        $formatter = new \IntlDateFormatter(
            $locale,
            $dateType,
            $timeType,
            $dateTimeZoneName,
            \IntlDateFormatter::GREGORIAN
        );

        return $formatter->format($datetime);
    }
}
