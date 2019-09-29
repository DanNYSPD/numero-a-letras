# Numero A Letras

Convierte un número o cadena numerica a su valor correspondiente en letras.
Es un fork de

## Instalación

Agrega `arielcr/numero-a-letras` a tu archivo composer.json.

    {
        "require": {
            "arielcr/numero-a-letras": "dev-master"
        }
    }
## firma del metodo:
		convertir($number,string $moneda = '',string $centimos = '', $forzarCentimos = false,bool $centimosEnLetra=false,string $claveMoneda='M.N')

## Uso

        $letras = NumeroALetras::convertir(12345);
Tambien es posible recibir cadenas numericas

         echo  NumeroALetras::convertir("121,311,321.21",'PESOS','CENTAVOS');
         >CIENTO VENTIUN MILLONES TRESCIENTOS ONCE MIL TRESCIENTOS VENTIUN  PESOS 21/100 M.N
         echo NumeroALetras::convertir("$ 121,311,321.21",'PESOS','CENTAVOS');
         >CIENTO VENTIUN MILLONES TRESCIENTOS ONCE MIL TRESCIENTOS VENTIUN  PESOS 21/100 M.N
         echo NumeroALetras::convertir("$-12131321.21",'PESOS','CENTAVOS');
         >MENOS DOCE MILLONES CIENTO TREINTA Y UN MIL TRESCIENTOS VENTIUN  PESOS 21/100 M.N
        
Ademas de elegir los separadores y simbolo de moneda:

		NumeroALetras::$currencySymbol='€';
		NumeroALetras::$thousandSeparator='.';
		NumeroALetras::$decimapSeparator=',';
 		echo NumeroALetras::convertir("12.345,67 €",'EUROS','CENTAVOS',false,false,'EUR');
 		> DOCE MIL TRESCIENTOS CUARENTA Y CINCO  EUROS 67/100 EUR
 		echo NumeroALetras::convertir("12.345,67 €",'','CENTAVOS',false,false,'EUR');
 		> DOCE MIL TRESCIENTOS CUARENTA Y CINCO   67/100 EUR

Si deseas que todo sea en letra estabe el parametro @centimosEnLetra a true (ver firma de metodo)

 		echo NumeroALetras::convertir("12.345,67 €",'EUROS','CENTIMOS',false,true,'EUR');
 		>DOCE MIL TRESCIENTOS CUARENTA Y CINCO  EUROS CON SESENTA Y SIETE  CENTIMOS
Incluso haciendo uso de currencySymbol, se puede aceptar el siguiente formato:
		NumeroALetras::$currencySymbol='MXN';
 		echo NumeroALetras::convertir("-12131321.21 MXN",'PESOS','CENTAVOS');
 		>MENOS DOCE MILLONES CIENTO TREINTA Y UN MIL TRESCIENTOS VENTIUN  PESOS 21/100 M.N

Si deseas convertir un número con decimales y mostrar la moneda:

        $letras = NumeroALetras::convertir(12345.67, 'colones', 'centimos');
        

##Excepciones
La excepcion InvalidArgumentException es arrojada si el numero/cadena numerica excede los centenares de millon 

## Créditos

Basado en la clase para PHP [AxiaCore/numero-a-letras](https://github.com/AxiaCore/numero-a-letras/blob/master/php/NumberToLetterConverter.class.php)

