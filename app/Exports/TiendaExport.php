<?php

namespace App\Exports;

use App\Models\Tienda;
use Maatwebsite\Excel\Concerns\FromCollection;

class TiendaExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Tienda::all();
    }
}
