<?php

namespace App\Exports;

use App\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ItemsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    { 
        $item = new Item();
        $totalqty = $item->serials()->count();
        $leftqty = 0;
        $useqty = 0;
        $takenqty = 0;
        foreach ($item->serials as $key => $serial) {
            if ($serial->status == 2) {
                $useqty = ++$useqty;
            }
            if($serial->status == 1){
                $takenqty = ++$takenqty;
            }
                       
            }

        $leftqty = $totalqty - ($useqty + $takenqty);
       
    	$brand_id = (!empty($_POST['brand_id']))?$_POST['brand_id']:'';
        $cat_id = (!empty($_POST['cat_id']))?$_POST['cat_id']:'';

        $item = $item->leftjoin('brand','brand.id','=','items.brand_id')->leftjoin('category','category.id','=','items.cat_id');

        if($brand_id!=''){
            $item = $item->where('items.brand_id',$brand_id);
        }

        if($cat_id!=''){
            $item = $item->where('items.cat_id',$cat_id);
        }


                    

        $items =$item->select(
                           'category.name',
                           'brand.name AS brand_name',
                           'items.model',
                           'items.unit',
                           'items.price',
                           )->get();
        // dd($items->get());
        return $items;
    }

    public function headings(): array
    {
        return [
            "Category Name",
            'Brand Name',
            'Model',
            'Unit',
            'Price',
           
        ];
    }
}