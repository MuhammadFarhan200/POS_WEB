<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;
    protected $table = 'penggajian';
    protected $primaryKey = 'id_gaji';
    protected $guarded = [];
    public $timestaps = true;

     public function list_po()
    {
        return $this->hasOne(Listpo::class, 'id', 'id_list_po');
    }
}
