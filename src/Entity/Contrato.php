<?php

namespace App\Entity;

use App\Repository\ContratoRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=ContratoRepository::class)
 */
class Contrato
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * Representa el identificador unico del contrato-PK. Se genera automaticamente
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * Debe tener al menos 5 caracteres y comenzar con MACRO
     * Representa el numero del contrato
     */
    private $numeroContrato;

    /**
     * @ORM\Column(type="float")
     * Debe ser un numero positivo
     * Representa el valor del contrato
     */
    private $valor;

    /**
     * @ORM\Column(type="date")
     * Representa la fecha en que se firmó el contrato
     * Formato esperado: DD-MM-YYYY
     */
    private $fecha;

       

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroContrato(): ?string
    {
        return $this->numeroContrato;
    }

    public function setNumeroContrato(string $numeroContrato): self
    {
        if(strlen($numeroContrato)<6){
            throw new \InvalidArgumentException('El numero del contrato debe tener minimo 5 caracteres');
        }
        if(strtoupper(substr($numeroContrato, 0, 5)) != 'MACRO'){
            throw new \InvalidArgumentException('El numero del contrato debe empezar con MACRO'); 
        }

        $this->numeroContrato = $numeroContrato;

        return $this;
    }

    public function getValor(): ?float
    {        
        return $this->valor;
    }

    public function setValor(float $valor): self
    {
        if($valor<=0){
            throw new \InvalidArgumentException('El valor debe ser un número mayor que cero');
        }
        
        $this->valor = $valor;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
            $this->fecha = $fecha;
            return $this;
       
    }
}

