<?php

namespace App\Exports;

use App\Brand;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BrandsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    { 
        $brand = new Brand();           
        $brands =$brand->select(
                           'brands.name',
                           )->get();
        // dd($items->get());
        return $brands;
    }

    public function headings(): array
    {
        return [
            "Brand Name",
        ];
    }
}