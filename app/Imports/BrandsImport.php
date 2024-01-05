<?php

namespace App\Imports;

use App\Brand;

use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class BrandsImport implements ToCollection,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    public function collection(Collection $rows)
    {
       
        DB::beginTransaction();
        try {
                
                foreach ($rows as $row) 
                {
                    $arr=[
                    'name'=>$row['brand'],
                    ];
                   
                    Brand::create($arr);
                }
            DB::commit();
        }

        catch (Exception $e) {
              DB::rollback();
                return redirect()->route('brand.index')
                            ->with('error','Something wrong!');
         }
    }
}