<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CCommune extends Model
{
    use HasFactory;
    
    protected $table = 'adempiere.hh_commune';
    /**
     * @property integer $id
     * @property string $nom
     * @property integer $wilaya_id
     * @property string $codecommune_erp
     * @property integer $dairas_id
     * @property string $created_at
     * @property string $updated_at
     */
    protected $fillable = ['nom', 'wilaya_id', 'codecommune_erp', 'dairas_id', 'created_at', 'updated_at'];
    public $timestamps = false;
}
