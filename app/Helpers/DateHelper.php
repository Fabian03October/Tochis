<?php

if (!function_exists('formatDate')) {
    /**
     * Formatear fecha en español
     */
    function formatDate($date, $format = 'd/m/Y H:i')
    {
        if (!$date) return '';
        
        $carbon = \Carbon\Carbon::parse($date)->setTimezone('America/Mexico_City');
        return $carbon->format($format);
    }
}

if (!function_exists('formatDateSpanish')) {
    /**
     * Formatear fecha en español con nombres de meses
     */
    function formatDateSpanish($date, $includeTime = false)
    {
        if (!$date) return '';
        
        $carbon = \Carbon\Carbon::parse($date)->setTimezone('America/Mexico_City');
        $months = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
        ];
        
        $formatted = $carbon->day . ' de ' . $months[$carbon->month] . ' de ' . $carbon->year;
        
        if ($includeTime) {
            $formatted .= ' a las ' . $carbon->format('H:i');
        }
        
        return $formatted;
    }
}

if (!function_exists('timeAgo')) {
    /**
     * Mostrar tiempo transcurrido en español
     */
    function timeAgo($date)
    {
        if (!$date) return '';
        
        $carbon = \Carbon\Carbon::parse($date)->setTimezone('America/Mexico_City');
        $now = \Carbon\Carbon::now('America/Mexico_City');
        
        $diff = $carbon->diff($now);
        
        if ($diff->y > 0) {
            return $diff->y . ' año' . ($diff->y > 1 ? 's' : '');
        } elseif ($diff->m > 0) {
            return $diff->m . ' mes' . ($diff->m > 1 ? 'es' : '');
        } elseif ($diff->d > 0) {
            return $diff->d . ' día' . ($diff->d > 1 ? 's' : '');
        } elseif ($diff->h > 0) {
            return $diff->h . ' hora' . ($diff->h > 1 ? 's' : '');
        } elseif ($diff->i > 0) {
            return $diff->i . ' minuto' . ($diff->i > 1 ? 's' : '');
        } else {
            return 'hace unos segundos';
        }
    }
}
