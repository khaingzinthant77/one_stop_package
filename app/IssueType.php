<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IssueType extends Model
{
    protected $table = 'issue_types';
    protected $fillable = ['issue_type','status'];
}
