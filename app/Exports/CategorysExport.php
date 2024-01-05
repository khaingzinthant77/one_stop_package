<?php

namespace App\Exports;

use App\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CategorysExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    { 
        $category = new Category();           
        $categorys =$category->select(
                           'categories.name',
                           'categories.install_charge'
                           )->get();
        // dd($items->get());
        return $categorys;
    }

    public function headings(): array
    {
        return [
            "Category",
            "Install Charges"
        ];
    }
}