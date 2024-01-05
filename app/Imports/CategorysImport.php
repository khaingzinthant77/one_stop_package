<?php

namespace App\Imports;

use App\Category;

use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class CategorysImport implements ToCollection,WithHeadingRow
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
                    'name'=>$row['category'],
                    'install_charge' =>$row['install_charge'],
                    ];
                   
                    Category::create($arr);
                }
            DB::commit();
        }

        catch (Exception $e) {
              DB::rollback();
                return redirect()->route('category.index')
                            ->with('error','Something wrong!');
         }
    }
}