<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'descripcion' => 'required',
            'unidad' => 'required',
            'monedaCompra' => 'required',
            // 'precioCompra' => 'required',
            'monedaVenta' => 'required',
            // 'precioVenta' => 'required',
            'stock' => 'required|integer',
            'stockMinimo' => 'nullable',
        ];
    }

    public function attributes()
    {
        return[
            'descripcion' => 'descripci¨®n del producto',
            'monedaCompra' => 'moneda de compra',
            // 'precioCompra' => 'precio de compra',
            'monedaVenta' => 'moneda de venta',
            // 'precioVenta' => 'precio de venta',
            'stock' => 'stock del producto',
        ];
    }

}
