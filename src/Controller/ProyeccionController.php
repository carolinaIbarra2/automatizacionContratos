<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\ContratoProyeccionService;
use App\Entity\Contrato;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContratoRepository;

/**
 * Controlador encargado de gestionar las proyecciones de cuotas de contratos.
 */
class ProyeccionController extends AbstractController
{

    private $contratoProyeccionService;
    private $entityManager;
    private $contratoRepository;


    /**
     * Constructor de la clase ProyeccionController.
     * @param ContratoProyeccionService $contratoProyeccionService Servicio para proyección de contratos
     * @param EntityManagerInterface $entityManager Interfaz de manejo de entidades
     * @param ContratoRepository $contratoRepository Repositorio de contratos
     */
    public function __construct(ContratoProyeccionService $contratoProyeccionService, EntityManagerInterface $entityManager,
                                ContratoRepository $contratoRepository) {
        $this->contratoProyeccionService = $contratoProyeccionService;
        $this->entityManager = $entityManager;
        $this->contratoRepository = $contratoRepository;
    }


    /**
     * Obtiene la proyección de cuotas para un servicio y período de tiempo dados.
     * @param string $servicio Nombre del servicio (PAYPAL o PAYONLINE)
     * @param int $meses Cantidad de meses para la proyección
     * @return JsonResponse Resultado de la proyección en formato JSON     * 
     * @Route("/proyeccion/{servicio}/{meses}", name="listar_proyeccion", methods={"GET"})
     */
    public function listarProyeccion(string $servicio, int $meses): JsonResponse
    {
        try{
        //Obtener datos desde la URL
            $proyeccion['servicio'] = $servicio;
            $proyeccion['meses'] = $meses;

            // Obtener contratos de la base de datos
            $contratos = $this->entityManager->getRepository(Contrato::class)->findAll();

            // Llamar al servicio para obtener la proyección
            $proyeccion = $this->contratoProyeccionService->proyectar($meses, $servicio, $contratos);
        

            return new JsonResponse($proyeccion);
        
        }catch (\InvalidArgumentException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Obtiene la proyección de cuotas para un contrato específico.
     * 
     * @param string $servicio Nombre del servicio (PAYPAL o PAYONLINE)
     * @param int $meses Cantidad de meses para la proyección
     * @param string $numeroContrato Número de contrato para buscar
     * @return JsonResponse Retorna un JSON con la proyección de cuotas del contrato o un mensaje de error si el contrato 
     * no es encontrado
     * @Route("/proyeccion/{servicio}/{meses}/{numeroContrato}", name="listarContrato_proyeccion", methods={"GET"})
     */
    public function listarPorContrato(string $servicio, int $meses, string $numeroContrato): JsonResponse
    {
        try{
            //Obtener el contrato de la base de datos
            $contrato = $this->contratoRepository->findOneBy(['numeroContrato'=>$numeroContrato]);

            if(!$contrato){                
                throw new \InvalidArgumentException('El contrato no fue encontrado');
            }

            // Llamar al servicio para obtener la proyección del contrato
            $proyeccion = $this->contratoProyeccionService->proyectarPorContrato($meses, $servicio, $contrato);
            
            return new JsonResponse($proyeccion);
        }catch (\InvalidArgumentException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}