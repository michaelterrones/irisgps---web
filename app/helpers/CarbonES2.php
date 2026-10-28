<?php

class CarbonES2 extends Carbon {

    public function diffForHumans(Carbon $other = null, $absolute = false)
    {
        $isNow = $other === null;

        if ($isNow) {
            $other = static::now($this->tz);
        }

        $diffInterval = $this->diff($other);

        switch (true) {
            case ($diffInterval->y > 0):
                $unit = 'year';
                $delta = $diffInterval->y;
                break;

            case ($diffInterval->m > 0):
                $unit = 'month';
                $delta = $diffInterval->m;
                break;

            case ($diffInterval->d > 0):
                $unit = 'day';
                $delta = $diffInterval->d;
                if ($delta >= self::DAYS_PER_WEEK) {
                    $unit = 'week';
                    $delta = floor($delta / self::DAYS_PER_WEEK);
                }
                break;

            case ($diffInterval->h > 0):
                $unit = 'hour';
                $delta = $diffInterval->h;
                break;

            case ($diffInterval->i > 0):
                $unit = 'minute';
                $delta = $diffInterval->i;
                break;

            default:
                $delta = $diffInterval->s;
                $unit = 'second';
                break;
        }

        if ($delta == 0) {
            $delta = 1;
        }

        $txt = $delta . ' ' . $unit;
        $txt .= $delta == 1 ? '' : 's';

        if ($absolute) {
            return $txt;
        }

        $isFuture = $diffInterval->invert === 1;

                // Código adaptado para utilizar el gestor de idiomas de Laravel
        $txt = 'carbonlocale';

        if (!$isFuture) {
            return Lang::choice("$txt.past.$unit", $delta, compact('delta'));
        }

        return Lang::choice("$txt.future.$unit", $delta, compact('delta'));


        if ($isNow) {
            if ($isFuture) {
                return $txt . ' from now';
            }

            return $txt . ' ago';
        }

        if ($isFuture) {
            return $txt . ' after';
        }

        return $txt . ' before';
        
    }

}