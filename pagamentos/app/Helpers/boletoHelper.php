<?php

if (!function_exists('formatarBoleto')) {
    function formatarBoleto(string $codigoBarras): string
    {
        //Formata código de barras do boleto
        $codigoBarras = preg_replace('/\D/', '', $codigoBarras); // Remove caracteres não numéricos

        $codigoFormatado = substr($codigoBarras, 0, 5) . '.' .
            substr($codigoBarras, 5, 5) . ' ' .
            substr($codigoBarras, 10, 5) . '.' .
            substr($codigoBarras, 15, 6) . ' ' .
            substr($codigoBarras, 21, 5) . '.' .
            substr($codigoBarras, 26, 6) . ' ' .
            substr($codigoBarras, 32, 1) . ' ' .
            substr($codigoBarras, 33);
        return $codigoFormatado;
    }
}
