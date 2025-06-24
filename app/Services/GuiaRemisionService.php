<?php

namespace App\Services;

use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchLine;
use Greenter\Model\Despatch\Direction;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Transportist;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Document;
use Carbon\Carbon;

class GuiaRemisionService
{
    public function buildGuia(): Despatch
    {
        $empresa = (new Company())
            ->setRuc('20000000001')
            ->setRazonSocial('EMPRESA DE PRUEBA')
            ->setNombreComercial('MI EMPRESA')
            ->setAddress((new Direction())
                ->setUbigueo('150101')
                ->setDepartamento('LIMA')
                ->setProvincia('LIMA')
                ->setDistrito('LIMA')
                ->setUrbanizacion('-')
                ->setDireccion('Av. Ejemplo 123')
                ->setCodLocal('0000'));

        $cliente = (new Client())
            ->setTipoDoc('6')
            ->setNumDoc('20600055566')
            ->setRznSocial('Cliente de Prueba');

        $shipment = (new Shipment())
            ->setIndicadorTrasladoBienes(true)
            ->setCodTraslado('01') // Venta
            ->setDesplazamiento('01') // público
            ->setPesoTotal(10.5)
            ->setUndPesoTotal('KGM')
            ->setFechaInicioTraslado(Carbon::now()->toDateTime())
            ->setModalidadTraslado('01') // Transporte público
            ->setPartida((new Direction())->setUbigueo('150101')->setDireccion('Origen X'))
            ->setLlegada((new Direction())->setUbigueo('150101')->setDireccion('Destino Y'))
            ->setTransportista((new Transportist())
                ->setTipoDoc('6')
                ->setNumDoc('20123456789')
                ->setRznSocial('TRANSPORTISTA SAC'));

        $line = (new DespatchLine())
            ->setId(1)
            ->setCantidad(2)
            ->setUnidad('NIU')
            ->setDescripcion('PRODUCTO A');

        $despatch = (new Despatch())
            ->setVersion('2022')
            ->setTipoDoc('09')
            ->setSerie('T001')
            ->setCorrelativo('00012345')
            ->setFechaEmision(Carbon::now()->toDateTime())
            ->setEmpresa($empresa)
            ->setDestinatario($cliente)
            ->setEnvio($shipment)
            ->setDespatchLines([$line]);

        return $despatch;
    }
}
