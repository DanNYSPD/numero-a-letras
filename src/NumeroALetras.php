<?php
declare(strict_types=1);
namespace Xarenisoft\NumberToWords\Esp;
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
     * La intencion de este atributo es detectar si hay un currencySymbol (usualmente un caracter o un currencyCode).
     * Este atributo reduciria el performance.Sin embargo permitira formatear valores como 1,000.00 , $1,00.00 o USD 1,000.00 sin indicar el symbolo
     *
     * @var boolean
     */
    public static $smartSymbolDetection=false;

    public static $minusSymbol='-';
    public static $supportForNegativeValues=true;

    public const FORZAR_CENTIMOS=1; // BIT #1 
    public const SUFFIX_SIEMPRE=2; // BIT #2 
    static function isFlagSet($flag,$globalFlags)
    {
        return (($globalFlags & $flag) == $flag);
    }
    static function isSetFlagForceCentimos($globalFlags){
        return self::isFlagSet(self::FORZAR_CENTIMOS,$globalFlags);
    }
    static function isSetFlagSuffixAlways($globalFlags){
        return self::isFlagSet(self::SUFFIX_SIEMPRE,$globalFlags);
    }
    /**
     * 
     *
     * @param scalar $number
     * @param string $moneda example 'PESOS','EUROS' ,etc
     * @param string $centimos CENTAVOS, Cuando es un valor vacio(cadena vacia)  se asigna el formato ##/100 en caso contrario centimos sera en palabra, e.j:centavos    
     * @param string $suffix usado cuando el formato es centecimos/100, va al final y puede expresar el tipo de moneda (M.N o alguna), si se desea usar siempre este suffix establece la bandera SUFFIX_SIEMPRE
     * @return void
     */
    public static  function convertir($number,string $moneda = '',string $centimos = '',string $suffix='', int $flags = 0)
    {
        $forzarCentimos=self::isSetFlagForceCentimos($flags);
        $suffixAlwaysAtTheEnd=self::isSetFlagSuffixAlways($flags);
       # echo $number."\n";
        $converted = '';
        $decimales = '';
        /*
        if (($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }
        */
        $number=trim($number);

        if(self::$smartSymbolDetection===true){

        }
        #me aseguro de que el symbolo sea el mismo por eso convierto todo a upper case (por ejemplo usd y USD no machearian sino hago esto)
        $lcurrencySymbol=strlen(self::$currencySymbol);
        $beginning=substr($number,0,$lcurrencySymbol);
       
        #echo $part."\n";
        if(strcasecmp($beginning,self::$currencySymbol)==0){ #this means it has the symbol o money type at the beggining
            $number= trim(substr($number,$lcurrencySymbol,strlen($number)-$lcurrencySymbol));
          #  echo $number."\n";
        }else{
            
           $end=substr($number,strlen($number)-$lcurrencySymbol,$lcurrencySymbol);
           #en algunos locale, el currentSymbol va al final,como "12.345,67 €", por ello considero este escenario
            
           if(strcasecmp($end,self::$currencySymbol)==0){
               
                $number= trim(substr($number,0,strlen($number)-$lcurrencySymbol));
               
           }

        }
        $div_decimales = explode(self::$decimapSeparator,$number);
        $decNumberStr='00';
        if(count($div_decimales) > 1){
            $number = $div_decimales[0];
            $decNumberStr = (string) $div_decimales[1];
            if(strlen($decNumberStr) <= 2){
                # I did this cast because convertGroup doesn't resolve when it's 0
                if((int)$decNumberStr===0 && $forzarCentimos){
                    $decimales='CERO';
                    $decNumberStr='00';
                }else{               
                    $decNumberStrFill = str_pad($decNumberStr, 9, '0', STR_PAD_LEFT);
                    $decCientos = substr($decNumberStrFill, 6);
                    $decimales = self::convertGroup($decCientos);
                }
            }else{
                throw new \InvalidArgumentException(
                    "Parte fracional invalida :{$decNumberStr}, verifica el numero de decimales o si el symbolo de moneda va al final y es correcto"
                );
            }
        }#
        else if (count($div_decimales) == 1 && $forzarCentimos){
            $decimales = 'CERO ';

        }
      #  echo $number."-----------------\n";
        /*
        if(0==$number){//si es cero y no tirnen decimales
            $converted= "CERO ";


        }
        */
        $numberStr = (string) $number;
        
        $menos=''; #contendra la palabra menos en caso de valores negativos

        if(strpos($numberStr,self::$minusSymbol)!==false){
            $menos ="MENOS ";
            $numberStr=str_replace(self::$minusSymbol,'',$numberStr);

        }
        #en caso de que tenga separadores de miles los remuevo:
        $numberStr=str_replace(self::$thousandSeparator,'',$numberStr);
       # echo $numberStr."-----------------\n";
        # con esto me aseguro que tras procesar la cantidad no se exceda y no ocurra Undefined offset: -1 dentro del metodo convertGroup
        if(intval($numberStr)>999999999){
            throw new \InvalidArgumentException(
            "No se puede formatear mas alla de la suma 999999999, valor dado(limpio sin symbolos): {$numberStr}. 
            Si el valor no exede la suma indica, favor de verificar la configuracion de separador decimal y de miles", 1);
            
        }
        //con str_pad rellenamos los espacios necesarios hasta cumplir los 9 digitos a la izquierda que es hasta centenas de milloes, ejemplo: si number es 1 (uno). rellenamos con 0 hasta los 9 digitos: '000000001'.
        //con esto forzamos a 9 y asi poder extraer siempre los millones, miles y cientos
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
                $converted .= sprintf('%s', self::convertGroup($cientos));
            }
        }else{ //este es el ultimo cero, y si representa un valor en texto
            if($cientos=='000'){
                $converted .= 'CERO ';
            }
        }
        if(""===$decimales){
            $valor_convertido = $converted . strtoupper($moneda);
            if($suffixAlwaysAtTheEnd && !empty($suffix)){
                $valor_convertido.=" ".$suffix;
            }
        } else {
            if(!empty($centimos)){
                $valor_convertido = $converted . strtoupper($moneda) . ' CON ' . trim($decimales) . ' ' . strtoupper($centimos);
                if($suffixAlwaysAtTheEnd && !empty($suffix)){
                    $valor_convertido.=" ".$suffix;
                }
            }else{
                $valor_convertido = $converted . strtoupper($moneda) . ' ' . $decNumberStr . '/100 '.$suffix; #m.n = moneda nacional 
            }
        }
        return trim($menos.$valor_convertido);
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