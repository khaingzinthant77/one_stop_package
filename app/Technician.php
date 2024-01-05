<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    protected $table = 'technicians';
    protected $fillable =['name','phone_no','photo','path','group_id','status'];

    public function group()
    {
        return $this->hasOne('App\Group', 'id', 'group_id');
    }
}
