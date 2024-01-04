<?php

namespace App\Service;

use App\Entity\Contrato;
use App\Service\ContratoServiceInterface;

/**
 * Clase que maneja la proyección de cuotas de contratos para diferentes servicios y períodos de tiempo.
 */
class ContratoProyeccionService implements ContratoServiceInterface{


    /**
     * Proyecta las cuotas de varios contratos para un servicio y período de tiempo dados.
     *
     * @param int $meses Cantidad de meses para la proyección
     * @param string $servicio Nombre del servicio (PAYPAL o PAYONLINE)
     * @param array $contratos Arreglo de contratos a proyectar
     * @return array Arreglo con las cuotas proyectadas para todos los contratos
     * @throws \InvalidArgumentException Cuando el servicio proporcionado no es válido
     */  
    public function proyectar(int $meses,string $servicio,array $contratos): array
    {
        $cuotas = [];

        foreach($contratos as $contrato)
        {
            $cuotas = array_merge($cuotas, $this->calcularCuotas($meses, $servicio, $contrato));
        }
            return $cuotas;
    }


     /**
     * Proyecta las cuotas para un contrato específico y servicio dado.
     *
     * @param int $meses Cantidad de meses para la proyección
     * @param string $servicio Nombre del servicio (PAYPAL o PAYONLINE)
     * @param Contrato $contrato El contrato para el cual se proyectan las cuotas
     * @return array Arreglo con las cuotas proyectadas para el contrato especificado
     * @throws \InvalidArgumentException Cuando el servicio proporcionado no es válido
     */
    public function proyectarPorContrato(int $meses,string $servicio,Contrato $contrato): array
    {    
        return $this->calcularCuotas($meses, $servicio, $contrato);      
    
    }


    /**
     * Calcula las cuotas para un contrato y servicio específicos.
     *
     * @param int $meses Cantidad de meses para la proyección
     * @param string $servicio Nombre del servicio (PAYPAL o PAYONLINE)
     * @param Contrato $contrato El contrato para el cual se proyectan las cuotas
     * @return array Arreglo con las cuotas proyectadas para el contrato especificado
     * @throws \InvalidArgumentException Cuando el servicio proporcionado no es válido
     */
    private function calcularCuotas(int $meses, string $servicio, Contrato $contrato): array
    {
        $cuotas = [];

        switch(strtoupper($servicio)){
            case 'PAYPAL':
                            $interesSimple = 0.01;
                            $comision = 0.02;
                            break;   

            case 'PAYONLINE':
                            $interesSimple = 0.02;
                            $comision = 0.01;   
                            break;

            DEFAULT:
                    throw new \InvalidArgumentException('Servicio no existe');                    
        }


        $valorCuota = $contrato->getValor()/$meses;
        
        $fecha = $contrato->getFecha();

        for($i=0; $i<$meses; $i++){

            $valor=0;
            $valor = ($valorCuota*(1+$interesSimple))+($valorCuota*(1+$comision));
                                                
            // Obtenemos el timestamp y sumamos un mes
            $nueva_fecha_timestamp = strtotime('+1 month', $fecha->getTimestamp());

            // Creamos un nuevo objeto DateTime usando el nuevo timestamp
            $nueva_fecha = new \DateTime();
            $nueva_fecha->setTimestamp($nueva_fecha_timestamp);

            // Formateamos la fecha a 'd-m-Y'
            $nueva_fecha_formateada = $nueva_fecha->format('d-m-Y');

            $cuota= [
                "numero_contrato"=>$contrato->getNumeroContrato(),
                "fecha_contrato"=>$nueva_fecha_formateada,
                "valor_cuota"=>$valor
            ];
            
            $cuotas[] = $cuota;
            $fecha = $nueva_fecha;
        }
    
        return $cuotas;           
    }
}

  
  