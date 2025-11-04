<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = ['nom','categorie_id'];

    public function timeSheets()
    {
        return $this->hasMany(TimeSheet::class, 'type');
    }
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
}