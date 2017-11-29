<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Local extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbUbicaLocal';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbUbicaLocalID';

    /**
     * Inhabilitar timestamp para insert y update
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
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

    public function level() {
        return $this->hasOne('\App\Models\Level', 'tbUbicaNivelID', 'tbUbicaNivelID_Local');
    }
}