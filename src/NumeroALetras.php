<?php
namespace Lindan\Utils\Format;
/**
 * Clase que implementa un coversor de números
 * a letras.
 *
 * Soporte para PHP >= 5.4
 * Para soportar PHP 5.3, declare los arreglos
 * con la función array.
 *
 * @author AxiaCore S.A.S
 * tomado de:https://github.com/arielcr/numero-a-letras
 * 
 
 */
class NumeroALetras
{
    private static $UNIDADES = [
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];
    private static $DECENAS = [
        'VENTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];
    private static $CENTENAS = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];

    public static $thousandSeparator=',';
    public static $decimapSeparator='.';
    /**
     * Este atributo es util para el caso de valores currency con su simbolo , ejemplo $124.00 , € 123.00, etc.
     * Por defecto es $, setea este valor al deseado.
     * Aunque poco comun, tambien es posible encontrar valore como "Eu 1234,56","USD 1,234.56" ,"GBP 1,234.56" (ejemplos de valores tomandos de https://www.php.net/manual/es/function.money-format.php),
     * por lo que si tambien se desean omitir estos valores (se debe conocer el valor), colocar este como currencySymbol (aunque sea mas de un caracter)
     *
     * @var string
     */
    public static $currencySymbol='$'; //it can be €, USD,MXN
    /**
     * 
     *
     * @param scalar $number
     * @param string $moneda example 'PESOS','EUROS' ,etc
     * @param string $centimos CENTAVOS  (util cuando centavos es en LETRA. ignorado cuando la parte fracional es en ##/##)
     * @param boolean $forzarCentimos "con {#centimos} " para el caso de formato letra y ##/00 para el caso de decimal con numero
     * @param boolean $centimosEnLetra Si es true, se usara el valor de centimos, en caso contrario se usara el formato centecimos/100 
     * @param string $claveMoneda usado cuando el formato es centecimos/100, va al final y puede expresar el tipo de moneda (M.N o alguna)
     * @return void
     */
    public static function convertir($number,string $moneda = '',string $centimos = '', $forzarCentimos = false,bool $centimosEnLetra=false,string $claveMoneda='M.N')
    {
        $converted = '';
        $decimales = '';
        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }
        $number=trim($number);
        #me aseguro de que el symbolo sea el mismo por eso convierto todo a upper case (por ejemplo usd y USD no machearian sino hago esto)
        $lcurrencySymbol=strlen(self::$currencySymbol);
        $part=substr($number,0,$lcurrencySymbol);
        #echo $part."\n";
        if(strcasecmp($part,self::$currencySymbol)==0){ #this means it has the symbol o money type at the beggining
            $number= trim(substr($number,$lcurrencySymbol,strlen($number)-$lcurrencySymbol));
          #  echo $number."\n";
        }else{
           # echo $number."\n";
        }

        /*
        if($number[0]==self::$currencySymbol){
            #I trimmed the string because is common that a whitespace is between the currency symbol and the numeric part, for example: $ 1,212.0
           $number= trim(substr($number,1,strlen($number)-1));
        }
        */
        $div_decimales = explode(self::$decimapSeparator,$number);
        $decNumberStr='00';
        if(count($div_decimales) > 1){
            $number = $div_decimales[0];
            $decNumberStr = (string) $div_decimales[1];
            if(strlen($decNumberStr) == 2){
                $decNumberStrFill = str_pad($decNumberStr, 9, '0', STR_PAD_LEFT);
                $decCientos = substr($decNumberStrFill, 6);
                $decimales = self::convertGroup($decCientos);
            }
        }#
        else if (count($div_decimales) == 1 && $forzarCentimos){
            $decimales = 'CERO ';

        }
        /*
        if(0==$number){//si es cero y no tirnen decimales
            $converted= "CERO ";


        }
        */
        $numberStr = (string) $number;
        //con str_pad rellenamos los espacios necesarios hasta cumplir los 9 digitos a la izquierda que es hasta centenas de milloes, ejemplo: si number es 1 (uno). rellenamos con 0 hasta los 9 digitos: '000000001'.
        //con esto forzamos a 9 y asi poder extraer siempre los millones, miles y cientos

        #en caso de que tenga separadores de miles los remuevo:
        $numberStr=str_replace(self::$thousandSeparator,'',$numberStr);
        
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);
        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
            }
        }
        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
            }
        }
        if (intval($cientos) > 0) {
            
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos));
            }
        }else{ //este es el ultimo cero, y si representa un valor en texto
            if($cientos=='000'){
                $converted .= 'CERO ';
            }
        }
        if(empty($decimales)){
            $valor_convertido = $converted . strtoupper($moneda);
        } else {
            if($centimosEnLetra){
            $valor_convertido = $converted . strtoupper($moneda) . ' CON ' . $decimales . ' ' . strtoupper($centimos);
            }else{
                $valor_convertido = $converted . strtoupper($moneda) . '  ' . $decNumberStr . '/100 '.$claveMoneda; #m.n = moneda nacional 
            }
        }
        return $valor_convertido;
    }
    private static function convertGroup(string $n)
    {
        $output = '';
        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = self::$CENTENAS[$n[0] - 1];
        }
        $k = intval(substr($n,1));
        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            }
        }
        return $output;
    }
}