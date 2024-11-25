<?php
if (!function_exists('numero_a_letras')) {
    function numero_a_letras($numero) {
        $unidades = array('', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez');
        $decenas = array('', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa');
        $centenas = array('', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos');
        $especiales = array(
            11 => 'once',
            12 => 'doce',
            13 => 'trece',
            14 => 'catorce',
            15 => 'quince',
            16 => 'dieciséis',
            17 => 'diecisiete',
            18 => 'dieciocho',
            19 => 'diecinueve',
            21 => 'veintiuno',
            22 => 'veintidós',
            23 => 'veintitrés',
            24 => 'veinticuatro',
            25 => 'veinticinco',
            26 => 'veintiséis',
            27 => 'veintisiete',
            28 => 'veintiocho',
            29 => 'veintinueve'
        );

        // Separar parte entera y decimal
        $partes = explode('.', number_format($numero, 2, '.', ''));
        $entero = (int)$partes[0];
        $decimal = (int)$partes[1];

        if ($entero === 0) {
            return 'cero';
        }

        if ($entero > 2000) {
            return 'número fuera de rango';
        }

        // Caso especial para 1000
        if ($entero === 1000) {
            return 'mil';
        }

        // Caso especial para 2000
        if ($entero === 2000) {
            return 'dos mil';
        }

        // Para números entre 1001 y 1999
        if ($entero > 1000 && $entero < 2000) {
            $resto = $entero - 1000;
            if ($resto === 0) {
                return 'mil';
            }
            return 'mil ' . numero_a_letras($resto);
        }

        // Casos especiales
        if (isset($especiales[$entero])) {
            return $especiales[$entero];
        }

        // Para números entre 100 y 999
        if ($entero >= 100) {
            $centena = floor($entero / 100);
            $resto = $entero % 100;
            
            // Caso especial para el 100
            if ($entero === 100) {
                return 'cien';
            }
            
            return $centenas[$centena] . ($resto > 0 ? ' ' . numero_a_letras($resto) : '');
        }

        // Para números entre 30 y 99
        if ($entero >= 30) {
            $decena = floor($entero / 10);
            $unidad = $entero % 10;
            return $decenas[$decena] . ($unidad > 0 ? ' y ' . $unidades[$unidad] : '');
        }

        // Para números menores a 30
        if ($entero < 30) {
            if (isset($especiales[$entero])) {
                return $especiales[$entero];
            }
            if ($entero <= 10) {
                return $unidades[$entero];
            }
        }

        return 'número fuera de rango';
    }
}