<?php

namespace App\Repository;

use App\Entity\Contrato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contrato>
 *
 * @method Contrato|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contrato|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contrato[]    findAll()
 * @method Contrato[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */


/**
 * Repositorio para la entidad Contrato.
 * @extends ServiceEntityRepository<Contrato>
 */ 
class ContratoRepository extends ServiceEntityRepository
{
    
    /**
     * Constructor del repositorio.
     * @param ManagerRegistry $registry El registro de entidades.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contrato::class);
    }

    /**
     * Persiste una entidad Contrato en la base de datos.
     * @param Contrato $entity La entidad Contrato a persistir.
     * @param bool $flush Determina si se debe realizar un flush después de persistir la entidad.
     */
    public function add(Contrato $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * Elimina una entidad Contrato de la base de datos.
     * @param Contrato $entity La entidad Contrato a eliminar.
     * @param bool $flush Determina si se debe realizar un flush después de eliminar la entidad.
     */
    public function remove(Contrato $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * Verifica si un contrato existe según su número de contrato.
     * @param string $numeroContrato El número de contrato a verificar.
     * @return Contrato|null El contrato si existe, de lo contrario, null.
     */
    public function contratoExiste($numeroContrato): ?Contrato
        {
            return $this->createQueryBuilder('c')
                ->andWhere('c.numeroContrato = :numeroContrato')
                ->setParameter('numeroContrato', $numeroContrato)
                ->getQuery()               
                ->getOneOrNullResult()
            ;
        }

    
    /**
     * Obtiene todos los contratos almacenados en la base de datos.
     * @return Contrato[] Un arreglo con todos los contratos almacenados.
     */    
    public function listarTodosContratos(): array
    {
        return $this->createQueryBuilder('c')
            ->getQuery()
            ->getResult()
        ;
    }
 

    /**
     * Busca un contrato por su ID.
     * @param int $id El ID del contrato a buscar.
     * @return Contrato|null El contrato si se encuentra, de lo contrario, null.
     */
    public function listarPorContrato($id) 
    {
        return $this->createQueryBuilder('c')
        ->andWhere('c.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult();
    }


//    /**
//     * @return Contrato[] Returns an array of Contrato objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    
}
