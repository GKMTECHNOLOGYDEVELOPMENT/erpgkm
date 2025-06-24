<?php

namespace App\Http\Controllers;

use App\Services\GuiaRemisionService;
use App\Services\SunatGreService;
use Illuminate\Http\Request;

class GuiaController extends Controller
{
public function enviar()
{
    $sunat = new SunatGreService();
    $see = $sunat->getSee();

    $builder = new GuiaRemisionService();
    $guia = $builder->buildGuia();

    $res = $see->send($guia);

    if (!$res->isSuccess()) {
        return response()->json(['error' => $res->getError()->getMessage()], 500);
    }

    $cdr = $res->getCdrResponse();

    if ((int) $cdr->getCode() === 0) {
        return response()->json([
            'estado' => 'ACEPTADO',
            'descripcion' => $cdr->getDescription()
        ]);
    } else {
        return response()->json([
            'estado' => 'OBSERVADO',
            'descripcion' => $cdr->getDescription()
        ]);
    }
}}
