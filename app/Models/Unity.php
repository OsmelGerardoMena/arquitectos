<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unity extends Model {

    use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbUnidad';
    //protected $dates = ['created_at','updated_at','deleted_at'];
    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbUnidadID';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    // protected static function boot()
    // {
    //     parent::boot();

    //     self::creating(function ($model) {
    //         $model->created_by = Auth::id();
    //     });

    //     self::updating(function ($model) {
    //         $model->updated_by = Auth::id();
    //     });

    //     self::deleting(function($model){
    //         $model->deleted_by = Auth::id();
    //         $model->save();
    //     });
    // }
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->RegistroUsuario = Auth::id();
        });

        self::updating(function ($model) {
            $model->RegistroUsuario = Auth::id();
        });

        self::deleting(function($model){
            $model->RegistroUsuario = Auth::id();
            $model->save();
        });
    }

    public function comments() {
        return $this->hasMany('\App\Models\Comment','ComentarioTablaID')->where('ComentarioTabla', '=', $this->table)->orderBy('ComentarioFecha', 'DESC');
    }
    
}