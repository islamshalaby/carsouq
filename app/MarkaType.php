<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarkaType extends Model
{
    protected $fillable = ['image', 'title_en', 'title_ar', 'deleted' , 'marka_id'];

    public function Marka() {
        return $this->belongsTo('App\Marka', 'marka_id');
    }
}
