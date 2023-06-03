<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listpo extends Model
{
    use HasFactory;

    protected $table = 'list_po';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'id_statuses');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
