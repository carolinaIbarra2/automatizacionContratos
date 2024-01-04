<?php

namespace App\Service;

interface ContratoServiceInterface
{
    public function proyectar(int $meses,string $servicio,array $contratos) : array;
}