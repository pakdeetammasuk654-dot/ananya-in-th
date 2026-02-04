<?php

namespace App\Managers;

class ThaiCalendarHelper
{
    private static $lunarCache = [];
    private static $lastDate = null;
    private static $lastLunar = null;

    /**
     * Determine if a given date is a Thai Buddhist Holy Day (Wan Pra).
     */
    public static function isWanPra($date)
    {
        $lunar = self::getThaiLunarDate($date);
        $day = $lunar['day'];

        if ($day == 8 || $day == 15) {
            return true;
        }

        // Month 7 extra day handling or Odd Moon 29 days
        if ($lunar['phase'] == 'waning' && $day == 14) {
            if (!self::isFullMonth($lunar['month'], $lunar['year_be'], $lunar['is_second_8'])) {
                return true;
            }
        }

        return false;
    }

    public static function getThaiLunarDate($dateStr)
    {
        if (isset(self::$lunarCache[$dateStr])) {
            return self::$lunarCache[$dateStr];
        }

        $date = new \DateTime($dateStr);

        // Optimization: start from last calculated date if possible to avoid O(N^2) loops in sequences
        if (self::$lastDate !== null && $date >= self::$lastDate) {
            $refDate = clone self::$lastDate;
            $currDay = self::$lastLunar['day'];
            $currPhase = self::$lastLunar['phase'];
            $currMonth = self::$lastLunar['month'];
            $currYearBE = self::$lastLunar['year_be'];
            $isSecondMonth8 = self::$lastLunar['is_second_8'];
        } else {
            // Using a reliable epoch: 1 Jan 2023 was Waxing 10, Month 2, BE 2566
            $refDate = new \DateTime('2023-01-01');
            $currDay = 10;
            $currPhase = 'waxing';
            $currMonth = 2;
            $currYearBE = 2566;
            $isSecondMonth8 = false;
        }

        $daysDiff = (int) $date->diff($refDate)->format('%a');
        if ($date < $refDate)
            $daysDiff = -$daysDiff;

        if ($daysDiff >= 0) {
            for ($i = 0; $i < $daysDiff; $i++) {
                $maxDays = ($currPhase == 'waxing') ? 15 : (self::isFullMonth($currMonth, $currYearBE, $isSecondMonth8) ? 15 : 14);
                $currDay++;
                if ($currDay > $maxDays) {
                    $currDay = 1;
                    if ($currPhase == 'waxing') {
                        $currPhase = 'waning';
                    } else {
                        $currPhase = 'waxing';
                        $res = self::nextMonth($currMonth, $currYearBE, $isSecondMonth8);
                        $currMonth = $res['month'];
                        $currYearBE = $res['year_be'];
                        $isSecondMonth8 = $res['is_second_8'];
                    }
                }
            }
        } else {
            for ($i = 0; $i > $daysDiff; $i--) {
                $currDay--;
                if ($currDay < 1) {
                    if ($currPhase == 'waning') {
                        $currPhase = 'waxing';
                        $currDay = 15;
                    } else {
                        $currPhase = 'waning';
                        $res = self::prevMonth($currMonth, $currYearBE, $isSecondMonth8);
                        $currMonth = $res['month'];
                        $currYearBE = $res['year_be'];
                        $isSecondMonth8 = $res['is_second_8'];
                        $currDay = self::isFullMonth($currMonth, $currYearBE, $isSecondMonth8) ? 15 : 14;
                    }
                }
            }
        }

        $result = [
            'day' => $currDay,
            'phase' => $currPhase,
            'month' => $currMonth,
            'year_be' => $currYearBE,
            'is_second_8' => $isSecondMonth8
        ];

        // Cache result and update last state for next call
        self::$lunarCache[$dateStr] = $result;
        self::$lastDate = clone $date;
        self::$lastLunar = $result;

        return $result;
    }

    private static function isFullMonth($month, $yearBE, $isSecondMonth8 = false)
    {
        if ($month == 7 && self::isAthikawan($yearBE))
            return true;
        if ($isSecondMonth8)
            return true; // Month 8-2 is always 30 days
        return ($month % 2 == 0);
    }

    private static function nextMonth($month, $yearBE, $isSecondMonth8)
    {
        if ($month == 8 && self::isAthikamat($yearBE) && !$isSecondMonth8) {
            return ['month' => 8, 'year_be' => $yearBE, 'is_second_8' => true];
        }
        $isSecondMonth8 = false;
        $month++;
        if ($month > 12) {
            $month = 1;
            $yearBE++;
        }
        return ['month' => $month, 'year_be' => $yearBE, 'is_second_8' => $isSecondMonth8];
    }

    private static function prevMonth($month, $yearBE, $isSecondMonth8)
    {
        if ($month == 8 && self::isAthikamat($yearBE) && $isSecondMonth8) {
            return ['month' => 8, 'year_be' => $yearBE, 'is_second_8' => false];
        }
        $month--;
        if ($month < 1) {
            $month = 12;
            $yearBE--;
        }
        $isSecondMonth8 = ($month == 8 && self::isAthikamat($yearBE));
        return ['month' => $month, 'year_be' => $yearBE, 'is_second_8' => $isSecondMonth8];
    }

    public static function isAthikamat($yearBE)
    {
        $athikamatYears = [2566, 2569, 2571, 2574, 2577, 2579, 2582, 2585, 2587];
        return in_array($yearBE, $athikamatYears);
    }

    public static function isAthikawan($yearBE)
    {
        $athikawanYears = [2567, 2570, 2575]; // Rough manual list for accuracy
        return in_array($yearBE, $athikawanYears);
    }
    public static function getUpcomingWanPras($months = 4)
    {
        $wanpras = [];
        $start = new \DateTime();
        $end = (new \DateTime())->modify("+$months months");

        $current = clone $start;
        // Go back a few days to catch today if it's a wanpra
        $current->modify("-1 day");

        while ($current <= $end) {
            $dateStr = $current->format('Y-m-d');
            if (self::isWanPra($dateStr)) {
                $wanpras[] = [
                    'wanpra_id' => (string) (count($wanpras) + 1),
                    'wanpra_date' => $dateStr
                ];
            }
            $current->modify("+1 day");
        }
        return $wanpras;
    }

    public static function getAuspiciousStatus($dateStr)
    {
        $date = new \DateTime($dateStr);
        $dayOfWeek = (int) $date->format('w'); // 0 (Sun) - 6 (Sat)
        $lunar = self::getThaiLunarDate($dateStr);
        $month = $lunar['month'];

        $status = [
            'is_tongchai' => false,
            'is_atipbadee' => false
        ];

        // Mapping based on Thai Lunar Month
        // This is a common simplified version of the "Kallayok" rules
        switch ($month) {
            case 5:
            case 10:
                if ($dayOfWeek == 4)
                    $status['is_tongchai'] = true; // Thursday
                if ($dayOfWeek == 6)
                    $status['is_atipbadee'] = true; // Saturday
                break;
            case 6:
            case 11:
                if ($dayOfWeek == 5)
                    $status['is_tongchai'] = true; // Friday
                if ($dayOfWeek == 1)
                    $status['is_atipbadee'] = true; // Monday
                break;
            case 7:
            case 12:
                if ($dayOfWeek == 1)
                    $status['is_tongchai'] = true; // Monday
                if ($dayOfWeek == 0)
                    $status['is_atipbadee'] = true; // Sunday
                break;
            case 8:
            case 1:
                if ($dayOfWeek == 3)
                    $status['is_tongchai'] = true; // Wednesday
                if ($dayOfWeek == 4)
                    $status['is_atipbadee'] = true; // Thursday
                break;
            case 9:
            case 2:
                if ($dayOfWeek == 6)
                    $status['is_tongchai'] = true; // Saturday
                if ($dayOfWeek == 3)
                    $status['is_atipbadee'] = true; // Wednesday
                break;
            case 3:
            case 4:
                if ($dayOfWeek == 2) {
                    $status['is_tongchai'] = true;
                    $status['is_atipbadee'] = true;
                } // Tuesday
                break;
        }

        return $status;
    }

    public static function getUpcomingAuspiciousDays($months = 4)
    {
        $days = [];
        $start = new \DateTime();
        $end = (new \DateTime())->modify("+$months months");

        $current = clone $start;
        while ($current <= $end) {
            $dateStr = $current->format('Y-m-d');
            $status = self::getAuspiciousStatus($dateStr);
            if ($status['is_tongchai'] || $status['is_atipbadee']) {
                $days[] = [
                    'date' => $dateStr,
                    'is_tongchai' => $status['is_tongchai'],
                    'is_atipbadee' => $status['is_atipbadee']
                ];
            }
            $current->modify("+1 day");
        }
        return $days;
    }

    public static function getUpcomingAuspiciousEvents($months = 4)
    {
        $events = [];
        $start = new \DateTime();
        $end = (new \DateTime())->modify("+$months months");

        $current = clone $start;
        $current->modify("-1 day"); // catch today

        while ($current <= $end) {
            $dateStr = $current->format('Y-m-d');
            $isWanPra = self::isWanPra($dateStr);
            $auspicious = self::getAuspiciousStatus($dateStr);

            if ($isWanPra || $auspicious['is_tongchai'] || $auspicious['is_atipbadee']) {
                $events[] = [
                    'wanpra_id' => (string) (count($events) + 1),
                    'wanpra_date' => $dateStr,
                    'is_wanpra' => $isWanPra,
                    'is_tongchai' => $auspicious['is_tongchai'],
                    'is_atipbadee' => $auspicious['is_atipbadee']
                ];
            }
            $current->modify("+1 day");
        }
        return $events;
    }
}
