<?php

namespace App\Services;

use Greenter\See;
use Greenter\Model\Company\Company;
use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\Ws\Services\SunatEndpoints;

class SunatGreService
{
    public function getSee(): See
    {
        $see = new See();

        // Cargar certificado
        $pfx = file_get_contents(storage_path('app/certs/certificado.pfx'));
        $cert = new X509Certificate($pfx, env('CERT_PASSWORD'));
        $see->setCertificate($cert->export());

        // SUNAT beta o producción
        $see->setService(SunatEndpoints::GUIA_BETA); // o GUIA_PRODUCCION

        // Usuario SOL
        $see->setClaveSOL('20000000001MODDATOS', 'moddatos'); // Cambia esto si usas producción

        return $see;
    }
}
