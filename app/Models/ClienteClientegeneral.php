<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ClienteClientegeneral
 * 
 * @property int $idClienteClienteGeneral
 * @property int|null $idCliente
 * @property int|null $idClienteGeneral
 *
 * @package App\Models
 */
class 	ClienteClientegeneral extends Model
{
	protected $table = 'cliente_clientegeneral';
	protected $primaryKey = 'idClienteClienteGeneral';
	public $timestamps = false;

	protected $casts = [
		'idCliente' => 'int',
		'idClienteGeneral' => 'int'
	];

	protected $fillable = [
		'idCliente',
		'idClienteGeneral'
	];


	   // Relación con Cliente
	   public function cliente()
	   {
		   return $this->belongsTo(Cliente::class, 'idCliente');
	   }
   
	   // Relación con ClienteGeneral
	   public function clienteGeneral()
	   {
		   return $this->belongsTo(ClienteGeneral::class, 'idClienteGeneral');
	   }
}

