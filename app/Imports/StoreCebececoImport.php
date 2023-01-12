<?php

namespace App\Imports;

use App\Models\StoreCebececo;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

//class StoreCebececoImport implements ToModel

class StoreCebececoImport implements toCollection, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    
    /*public function model(array $row)
    {
        return new StoreCebececo([
            //
            'regional'  => $row[0],
            'segmento' => $row[1],
            'formato' => $row[2],
            'nombre_segmento' => $row[3],
            'direccion' => $row[4],
            'cebe_ceco' => $row[5],
            'nombre_cebe_ceco' => $row[6],
            'cod_region' => $row[7],
            'cod_formato' => $row[8],
            'cod_direccion' => $row[9],
    
        ]);
    }*/

    public function collection(Collection $rows)
    {
        //$this->rows = $collection;

        foreach ($rows as $row ) {
           
            $data=[
                'regional'  => $row['regional'],
                'segmento' => $row['segmento'],
                'formato' => $row['formato'],
                'nombre_segmento' => $row['nombre_segmento'],
                'direccion' => $row['direccion'],
                'cebe_ceco' => $row['cebe_ceco'],
                'nombre_cebe_ceco' => $row['nombre_cebe_ceco'],
                'cod_region' => $row['cod_region'],
                'cod_formato' => $row['cod_formato'],
                'cod_direccion' => $row['cod_direccion'],
                'seg_administrado' => $row['seg_administrado'],
            ];

            										

            StoreCebececo::create($data);
        }
    }

    public function rules(): array{

        return [

            //'1' => Rule::in('cebe_ceco'),

            'regional'  => 'required',
            'segmento' => 'required',
            'formato' => 'required',
            'nombre_segmento' => 'required',
            'direccion' => 'required',
            'cebe_ceco' => 'required|unique:bo_kpi_store_cebececo,cebe_ceco',
            'nombre_cebe_ceco' => 'required',
            'cod_region' => 'required',
            'cod_formato' => 'required',
            //'cod_direccion' => 'required',

        ];
    }

  

}
