<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CDiplomes extends Model
{
    use HasFactory;

      /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'adempiere.sys_diplomecon';

    /**
     * @var array
     */
    protected $fillable = ['ad_client_id', 'ad_org_id', 'isactive', 'created', 'createdby', 'updated', 'updatedby', 'diplom', 'sys_csps_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adUsers()
    {
        return $this->hasMany('App\AdUser', null, 'sys_diplomecon_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sysSpeciliteinscrs()
    {
        return $this->hasMany('App\SysSpeciliteinscr', null, 'sys_diplomecon_id');
    }

     public $timestamps = false;
}
