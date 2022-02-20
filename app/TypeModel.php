<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeModel extends Model
{
    protected $fillable = ['image', 'year' , 'deleted' , 'marka_type_id'];

    public function MarkaType() {
        return $this->belongsTo('App\MarkaType', 'marka_type_id');
    }
}
