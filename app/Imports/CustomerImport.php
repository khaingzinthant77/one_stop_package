<?php

namespace App\Imports;

use App\Survey;
use App\Customer;
use App\Township;
use App\Group;
use App\Assign;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class CustomerImport implements ToCollection,WithHeadingRow
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
                    $townships = Township::all();
                    foreach ($townships as $key => $value) {
                            if ($row['township'] === $value->town_name) {
                               $tsh_id = $value->id;
                            }
                        }
                    $teams = Group::all();
                    foreach ($teams as $key => $value) {
                            if ($row['survey_team'] == $value->group_name) {
                               $team_id = $value->id;
                            }
                    }

                    $customer = Customer::create([
                        'name'=>$row['customer_name'],
                        'phone_no'=>$row['phone_no'],
                        'tsh_id'=>$tsh_id,
                        'address'=>$row['address'],
                        ]);
                    $cust_tsh = Customer::find($customer->id)->tsh_id;
                    $all_surveys = Survey::all();
                    $count = $all_surveys->count();
                    $cust_count = str_pad(++$count,4,"0",STR_PAD_LEFT);
                    $tsh_short_code = Township::find($cust_tsh)->townshort_name;
                    $voucher_no = $tsh_short_code.$cust_count;
                    
                    $surveys = Survey::create([
                        'cust_id'=>$customer->id,
                        'survey_by'=>$team_id,
                        'survey_name'=>$row['survey_team'],
                        'lat'=>$row['lat'],
                        'lng'=>$row['lng'],
                        'is_solve'=>1,
                        'c_code'=>$voucher_no,
                        'assign_status'=>1,
                        'survey_type'=>1

                    ]);
                    $hour = date('H:i:s');
                    $assign = Assign::create([
                        'survey_id'=>$surveys->id,
                        'team_id'  => $team_id,
                        'assign_date'=>$team_id != null ? date('Y-m-d',strtotime($row['assign_date'])).' '.$hour : null,
                        'appoint_date'=>$team_id != null ? date('Y-m-d',strtotime($row['assign_date'])) : null,
                        'solved_date'=>date('Y-m-d',strtotime($row['solved_date']))
                      ]);
                }
            DB::commit();
            return redirect()->route('customer.index')->with('success','Success');
        }

        catch (Exception $e) {
              DB::rollback();
                return redirect()->route('category.index')
                            ->with('error','Something wrong!');
         }
    }
}