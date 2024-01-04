<?php

namespace App\Service;

use App\Entity\Contrato;
use App\Repository\ContratoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * 
 * Servicio para la gestión de contratos, incluyendo creación, eliminación, actualización y visualizacion.
 */
class CreacionContratoService {

    private $contratoRepository;
    private $entityManager;
    private $normalizer;

    /**
     * Constructor del servicio.
     *
     * @param ContratoRepository $contratoRepository Repositorio de contratos.
     * @param EntityManagerInterface $entityManager Manejador de entidades para interactuar con bases de datos.
     * @param NormalizerInterface $normalizer Normalizador para convertir entidades en arrays.
     */
    public function __construct(ContratoRepository $contratoRepository, EntityManagerInterface $entityManager, NormalizerInterface $normalizer) {
        $this->contratoRepository = $contratoRepository;
        $this->entityManager = $entityManager;
        $this->normalizer = $normalizer;
    }

     /**
     * Crea un nuevo contrato a partir de los datos proporcionados.     
     * @param array $data Datos del contrato: número, valor y fecha.     
     * @throws \InvalidArgumentException Si el formato de fecha es inválido.
     */
    public function crearContrato(array $data):void
    {    
        $numeroContrato = $data['numero_contrato'];

        //verifico si el contrato ya existe
        $contratoExistente = $this->contratoRepository->contratoExiste($numeroContrato);

        if($contratoExistente){
            //el contrato ya existe, envío excepcion
            throw new \InvalidArgumentException('El contrato ya existe con ese numero de contrato');
        }

        //contrato no existe
        $contrato = new Contrato();       
        $contrato->setNumeroContrato($data['numero_contrato']);
        $contrato->setValor($data['valor_contrato']);

        $fecha = \DateTime::createFromFormat('d-m-Y', $data['fecha_contrato']);
            
        if (!$fecha || $fecha->format('d-m-Y') !== $data['fecha_contrato']) {
            throw new \InvalidArgumentException('Formato de fecha inválido. Se esperaba el formato d-m-Y.');
        }
            
        $contrato->setFecha($fecha);

        $this->contratoRepository->add($contrato,true);           
    } 
    

   
    /**
     * Elimina un contrato dado su ID.
     *
     * @param int $id El ID del contrato a eliminar.
     * @return string Un mensaje indicando el resultado de la eliminación.
     */
    public function remove(int $id):string
    {       
        //Obtiene el contrato por su numero de contrato desde el repositorio
        $contrato = $this->contratoRepository->find($id);

        if(!$contrato){
            return 'El contrato no existe';
        }else{
            $this->entityManager->remove($contrato);
            $this->entityManager->flush();
            return 'Contrato eliminado correctamente';        
        }
    }


    /**
     * Actualiza un contrato existente con los datos proporcionados.
     *
     * @param array $data Datos del contrato a actualizar: número de contrato, valor y fecha.
     * @throws \InvalidArgumentException Si el contrato no existe o si hay un problema con el formato de los datos.
     */
    public function updateContrato(array $data): void
    {
        $numeroContrato = $data['numero_contrato'];

        //verifico si el contrato ya existe
        $contratoExistente = $this->contratoRepository->contratoExiste($numeroContrato);
        
        if($contratoExistente){
           //Obtener el contrato existente
            $contrato = $this->contratoRepository->findOneBy(['numeroContrato' => $numeroContrato]);
            
            //Actualizar los datos del contrato
            $contrato->setValor($data['valor_contrato']);

            $fecha = \DateTime::createFromFormat('d-m-Y', $data['fecha_contrato']);
            if (!$fecha || $fecha->format('d-m-Y') !== $data['fecha_contrato']) {
                throw new \InvalidArgumentException('Formato de fecha inválido. Se esperaba el formato d-m-Y.');
            }

            $contrato->setFecha($fecha);

            // Persistir los cambios en la base de datos
            $this->entityManager->flush();
        } else{
            throw new \InvalidArgumentException('No existe contrato');
        }       
    }


    /**
     * Lista todos los contratos disponibles.
     * @return array Lista de contratos en forma de arrays.
     */
    public function listarContratos(): array
    {
           return $this->contratoRepository->listarTodosContratos(); 
    }

    
    /**
     * Busca un contrato por su número y devuelve sus detalles si se encuentra.
     *
     * @param int $id El ID del contrato a buscar.
     * @return array|null Detalles del contrato si se encuentra, de lo contrario, null.
     */
    public function EncontrarContratoPorNumero(int $id): ?array
    {
        $contrato = $this->contratoRepository->listarPorContrato($id);

        if ($contrato) {
            return $this->normalizer->normalize($contrato);
        }
        return null;
       
    }

}

