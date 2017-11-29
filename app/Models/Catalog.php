<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Catalog extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbCatalogo';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbCatalogoID';

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

    public function contractor() {
        //return $this->hasOne('App\Models\Business', 'tbDirEmpresaID', 'tbDirEmpresaObraID_Contratista');
    }

    public function contract() {
        return $this->hasOne('App\Models\Contract', 'tbContratoID', 'tbContratoID_Catalogo');
    }

    public function unity() {
        return $this->hasOne('App\Models\Unity', 'tbUnidadID', 'tbUnidadesID_Catalogo');
    }

    public function departure() {
        return $this->hasOne('App\Models\Departure', 'tbPartidaID', 'tbPartidaID_Catalogo');
    }

    public function subdeparture() {
        return $this->hasOne('App\Models\Subdeparture', 'tbSubPartidaID', 'tbSubpartidaID_Catalogo');
    }

    public function level() {
        return $this->hasOne('App\Models\Level', 'tbUbicaNivelID', 'tbUbicaNivelID_Catalogo');
    }

    public function generator() {
        return $this->belongsTo('App\Models\Generator', 'tbCatalogoID_Generador', 'tbCatalogoID');
    }

    public function generators()
    {
        return $this->hasMany('App\Models\Generator', 'tbCatalogoID_Generador', 'tbCatalogoID');
    }

    public function estimates()
    {
        return $this->hasMany('App\Models\Estimate', 'tbContratoID_Estimacion', 'tbContratoID_Catalogo');
    }

    public function comments() {
        return $this->hasMany('\App\Models\Comment','ComentarioTablaID')->where('ComentarioTabla', '=', $this->table)->orderBy('ComentarioFecha', 'DESC');
    }
}