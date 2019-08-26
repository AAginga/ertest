<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'name', 'quantity','description'
    ];
    public function orders(){
        return $this->belongsToMany('App\Order');
    }

    public function suppliers(){
        return $this->belongsToMany('App\Supplier');
    }
}
