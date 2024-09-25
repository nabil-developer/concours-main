<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CCity extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     * 
     * @var string
     */
    // protected $schema = 'adempiere';
    protected $table = 'adempiere.c_city';

    /**
     * @var array
     */
    protected $fillable = ['ad_client_id', 'ad_org_id', 'isactive', 'created', 'createdby', 'updated', 'updatedby', 'name', 'locode', 'coordinates', 'postal', 'areacode', 'c_country_id', 'c_region_id', 'c_city_uu', 'c_city_efichier_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adClient()
    {
        return $this->belongsTo('App\AdClient', null, 'ad_client_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adUsers()
    {
        return $this->hasMany('App\AdUser', null, 'c_city_id');
    }

     public $timestamps = false;
}
