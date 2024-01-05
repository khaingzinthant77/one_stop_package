<?php

namespace App\Imports;

use App\Item;
use App\ProductSerial;

use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class ItemsImport implements ToCollection,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     return new Employee([
    //         'emp_id'     => $row[1],
    //         'emp_name'    => $row[2], 
    //         'branch' => $row[3],
    //         'dept' => $row[4]
    //     ]);
    // }
    public function collection(Collection $rows)
    {
       
        DB::beginTransaction();
        try {
                
                foreach ($rows as $row) 
                {
                     // dd($row);
                     $items = Item::where('cat_id',$row['category'])->where('brand_id',$row['brand'])->where('model',$row['model'])->get();
                     $count =$items->count();
           
                     if($count > 0) {
                       
                        $arrs=[
                        'item_id'=>$row['items_id'],
                        'serial_no'=>$row['serialno'],
                        ];
            
                        ProductSerial::create($arrs);
                     } else {
                         $arr=[
                        'cat_id'=>$row['category'],
                        'brand_id' => $row['brand'],
                        'model'=>$row['model'],
                        'product_name'=>$row['product'],
                        // 'serial_no'=>$arr,
                        'unit'=>$row['unit'],
                        'price'=>$row['price'],
                        'remark'=>$row['remark'],
                        ];
                        $arrs=[
                        'item_id'=>$row['items_id'],
                        'serial_no'=>$row['serialno'],
                        ];
                        Item::create($arr);
                        ProductSerial::create($arrs);
                     }


                }
            DB::commit();
        } 
        catch (Exception $e) {
              DB::rollback();
                return redirect()->route('item.index')
                            ->with('error','Something wrong!');
         }
    }
}