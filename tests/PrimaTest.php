<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Xarenisoft\NumberToWords\Esp\NumeroALetras;
 

final class PrimaTest extends TestCase {

    public function setup(){
        
    }
    public function testCantidadConSeparadorComaDecimalPuntoMonedaPesosYCentimosVacioDemo(){
        $output= NumeroALetras::convertir("121,311,321.21",'PESOS','','',NumeroALetras::FORZAR_CENTIMOS|NumeroALetras::SUFFIX_SIEMPRE);
        $this->assertEquals('CIENTO VENTIUN MILLONES TRESCIENTOS ONCE MIL TRESCIENTOS VENTIUN PESOS 21/100',
        $output);
     }
    public function testCantidadConSeparadorComaDecimalPuntoMonedaPesosYCentimosVacio(){
       $output= NumeroALetras::convertir("121,311,321.21",'PESOS','');
       $this->assertEquals('CIENTO VENTIUN MILLONES TRESCIENTOS ONCE MIL TRESCIENTOS VENTIUN PESOS 21/100',
       $output);
    }
    public function testCantidadConSeparadorComaDecimalPuntoMonedaPesosYCentimosCentavos(){
       $output= NumeroALetras::convertir("121,311,321.21",'PESOS','CENTAVOS');
       $this->assertEquals('CIENTO VENTIUN MILLONES TRESCIENTOS ONCE MIL TRESCIENTOS VENTIUN PESOS CON VENTIUN CENTAVOS',
       $output);
    } 
    public function testCantidadConSeparadorComaDecimalPuntoMonedaPesosCentimosVacioYSuffix(){
        $output= NumeroALetras::convertir("121,311,321.45",'PESOS','','M.N.');
        $this->assertEquals('CIENTO VENTIUN MILLONES TRESCIENTOS ONCE MIL TRESCIENTOS VENTIUN PESOS 45/100 M.N.',
        $output);
     }
     public function testCantidadConSeparadorComaDecimalValorCero1PosicionForzandoPuntoMonedaPesosCentimosVacioYSuffix(){
        $output= NumeroALetras::convertir("121,311,321.0",'PESOS','','M.N.',NumeroALetras::FORZAR_CENTIMOS);
        $this->assertEquals('CIENTO VENTIUN MILLONES TRESCIENTOS ONCE MIL TRESCIENTOS VENTIUN PESOS 00/100 M.N.',
        $output);
     }
     public function testCantidadConSeparadorComaDecimalValorCero2PosicionesForzandoPuntoMonedaPesosCentimosVacioYSuffix(){
        $output= NumeroALetras::convertir("121,311,321.00",'PESOS','','M.N.',NumeroALetras::FORZAR_CENTIMOS);
        $this->assertEquals('CIENTO VENTIUN MILLONES TRESCIENTOS ONCE MIL TRESCIENTOS VENTIUN PESOS 00/100 M.N.',
        $output);
     }
    
     public function testCantidadConSeparadorComaDecimalValorCeroPuntoMonedaPesosCentimosVacio(){
        $output= NumeroALetras::convertir("121,311,321.00",'PESOS','','M.N.');
        $this->assertEquals('CIENTO VENTIUN MILLONES TRESCIENTOS ONCE MIL TRESCIENTOS VENTIUN PESOS',
        $output);
     }
     public function testCantidadConSeparadorComaDecimalValorCeroPuntoMonedaPesosCentimosVacioSuffixAlways(){
        $output= NumeroALetras::convertir("121,311,321.00",'PESOS','CENTAVOS','M.N.',NumeroALetras::SUFFIX_SIEMPRE);
        $this->assertEquals('CIENTO VENTIUN MILLONES TRESCIENTOS ONCE MIL TRESCIENTOS VENTIUN PESOS M.N.',
        $output);
     }
}