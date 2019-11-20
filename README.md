# Numero A Letras

Convierte un número o cadena numerica a su valor correspondiente en palabras.
Es un fork de

## Instalación

Agrega `arielcr/numero-a-letras` a tu archivo composer.json.

    {
        "require": {
            "arielcr/numero-a-letras": "dev-master"
        }
    }
## firma del metodo:
		convertir(string $number,string $moneda = '',string $centimos = '',string $suffix='', int $flags = 0):string

## Parametros
#### number. 
Numero o cadena numerica a convertir en palabras
#### moneda
moneda en texto que se agregara al final de la parte entera del valor numerico. Por ejemplo: PESOS
#### centimos
Si es indicado, es la parte centimos de la moneda, ejemplos son CENTAVOS, si no es especificado este valor o es cadena vacia se usa el formato ##/100        
#### suffix
Sufijo , por defecto se agrega al final de la cadena solo cuando el formato es ##/100, se puede cambiar este comportamiento usando las banderas del ultimo parametro.
#### flags
banderas para cambiar el comportamiento del metodo        

## Uso

        $letras = NumeroALetras::convertir(12345);
Tambien es posible recibir cadenas numericas

         echo  NumeroALetras::convertir("121,311,321.21",'PESOS','CENTAVOS');
         >CIENTO VENTIUN MILLONES TRESCIENTOS ONCE MIL TRESCIENTOS VENTIUN PESOS 21/100
         echo NumeroALetras::convertir("$ 121,311,321.21",'PESOS','CENTAVOS');
         >CIENTO VENTIUN MILLONES TRESCIENTOS ONCE MIL TRESCIENTOS VENTIUN PESOS 21/100
         echo NumeroALetras::convertir("$-12131321.21",'PESOS','CENTAVOS');
         >MENOS CIENTO VENTIUN MILLONES TRESCIENTOS ONCE MIL TRESCIENTOS VENTIUN PESOS 21/100
        
Ademas de elegir los separadores y simbolo de moneda:

	NumeroALetras::$currencySymbol='€';
	NumeroALetras::$thousandSeparator='.';
	NumeroALetras::$decimalSeparator=',';
    $output=NumeroALetras::convertir("12.345,67 €",'EUROS','','EUR');
 	echo NumeroALetras::convertir("12.345,67 €",'','CENTAVOS',false,false,'EUR');
 		> DOCE MIL TRESCIENTOS CUARENTA Y CINCO EUROS 67/100 EUR

Incluso haciendo uso de currencySymbol, se puede aceptar el siguiente formato:
		NumeroALetras::$currencySymbol='MXN';
 		echo NumeroALetras::convertir("-12131321.21 MXN",'PESOS','CENTAVOS');
 		>MENOS DOCE MILLONES CIENTO TREINTA Y UN MIL TRESCIENTOS VENTIUN PESOS CON VENTIUN CENTAVOS

       

##Excepciones
La excepcion InvalidArgumentException es arrojada si el numero/cadena numerica excede los centenares de millon 

## Créditos

Basado en la clase para PHP [AxiaCore/numero-a-letras](https://github.com/AxiaCore/numero-a-letras/blob/master/php/NumberToLetterConverter.class.php)

