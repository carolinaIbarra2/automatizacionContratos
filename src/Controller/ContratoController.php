<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\CreacionContratoService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Controlador para manejar operaciones relacionadas con contratos.
 */
class ContratoController extends AbstractController
{

    private $creacionContratoService;

    public function __construct(CreacionContratoService $creacionContratoService) {

        $this->creacionContratoService = $creacionContratoService;
    }


    /**
     * Renderiza la página principal de contratos cuando se visita la URL /contrato.
     * @return Response La respuesta HTTP que muestra la página de contratos.
     * @Route("/contrato", name="app_contrato")
     */
    public function index(): Response
    {
        return $this->render('contrato/index.html.twig', [
            'controller_name' => 'ContratoController',
        ]);
    }


    /**
     * Almacena un contrato en la base de datos.     
     * @param Request $request Contiene los datos enviados por el cliente: numero_contrato, valor_contrato, fecha_contrato.     *
     * @return JsonResponse Mensaje que indica el estado de la función.
     * @Route("/contrato/crear", name="app_contrato_crear", methods={"POST"})
     */
    public function crearContrato(Request $request)
    {
        try{            
            $data = json_decode($request->getContent(), true);
            $this->creacionContratoService->crearContrato($data);
            return $this->json('Contrato guardado');         

        } catch (\InvalidArgumentException $ex) {
            return $this->json($ex->getMessage(), 400);        
        
        }catch(\Throwable $ex){
            return $this->json('Ocurrio un fallo en la ejecucion. Intente nuevamente mas tarde');
        }
    }

    /**
     * Elimina un contrato.
     *
     * @param int $id El ID del contrato a eliminar.
     * @return JsonResponse Devuelve un JSON con el resultado de la operación de eliminación. 
     * @Route("/contrato/{id}/eliminar", name="eliminar_contrato", methods={"DELETE"})
     */
    public function eliminarContrato(int $id): JsonResponse
    {
        try{
            $mensaje = $this->creacionContratoService->remove($id);     
            
            if($mensaje === 'El contrato no existe'){
                throw new NotFoundHttpException('El contrato solicitado no existe');
            }
            
            return new JsonResponse(['mensaje' => $mensaje]);
        }catch(NotFoundHttpException $exception) {
            return new JsonResponse(['mensaje' => 'El contrato solicitado no existe'], JsonResponse::HTTP_NOT_FOUND);
    }
    }


    /**
     * Actualiza un contrato existente.
     *
     * Este método actualiza los datos de un contrato existente en la base de datos utilizando los datos proporcionados
     * en la solicitud.
     * @param Request $request La solicitud HTTP que contiene los datos del contrato a actualizar.
     * @return JsonResponse Una respuesta JSON que indica el resultado de la actualización.
     * @Route("/contrato/actualizar", methods={"PUT"}, name="app_contrato_actualizar")
     */
    public function updateContrato(Request $request): JsonResponse    
    {                     
        try {
            $data = json_decode($request->getContent(), true);
            $this->creacionContratoService->updateContrato($data);
            return $this->json('Contrato actualizado');   
        } catch (\Throwable $ex) {
            return $this->json('Ocurrió un error al actualizar el contrato');
        }   
    }

    
    /**
     * Lista todos los contratos disponibles
     * @return JsonResponse Devuelve un JSON con la lista de contratos 
     * @Route("/contratos/listar", name="listar_contratos", methods={"GET"})
     */
    public function listarContratos(): JsonResponse
    {
        $contratos = $this->creacionContratoService->listarContratos();
        
        //Convertir los resultados a un formato adecuado para API
        $contratosArray =[];

        foreach($contratos as $contrato){
            $contratosArray[] = [
                'numero_contrato' => $contrato ->getNumeroContrato(),
                'valor_contrato' => $contrato ->getValor(),
                'fecha_contrato' => $contrato ->getFecha(),
                'id_contrato' => $contrato ->getId()
            ];
        }
        return new JsonResponse($contratosArray);
    }


    /**
     * Busca un contrato por su númeroId y devuelve el contrato.
     * @param int $id El ID del contrato a buscar.
     * @return JsonResponse Devuelve un JSON con los detalles del contrato si se encuentra.
     * @Route("/contrato/{id}/listarContrato", name="listar_por_contrato", methods={"GET"})
     */
    public function ListarContrato(int $id): JsonResponse
    {
        try{
            $contrato = $this->creacionContratoService->EncontrarContratoPorNumero($id); 
        
            if($contrato == null){           
                throw new NotFoundHttpException();
            } else {
                return new JsonResponse($contrato);
            }
        }catch(NotFoundHttpException $exception) {
            return new JsonResponse(['mensaje' => 'El contrato solicitado no existe'], JsonResponse::HTTP_NOT_FOUND);
        }
    } 
}   


