<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VersionUpdate extends Model
{
    protected $table = 'version_updates';
    protected $fillable = ['vCode','vName','direct_url'];
}
