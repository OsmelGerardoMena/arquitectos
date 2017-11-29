<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Generator extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbGenerador';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbGeneradorID';

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

    public function reciver()
    {
        return $this->hasOne('App\Models\Person', 'tbDirPersonaID', 'tbDirPersonaEmpresaObraID_GeneradorRecibe');
    }

    public function reviewer()
    {
        return $this->hasOne('App\Models\Person', 'tbDirPersonaID', 'tbDirPersonaEmpresaObraID_GeneradorRevisa');
    }

    public function authorizer() {
        return $this->hasOne('App\Models\Person', 'tbDirPersonaID', 'tbDirPersonaEmpresaObraID_GeneradorAutoriza');
    }

    public function unity() {
        return $this->hasOne('App\Models\Unity', 'tbUnidadID', 'tbUnidadID_Generador');
    }

    public function catalog() {
        return $this->hasOne('App\Models\Catalog', 'tbCatalogoID', 'tbCatalogoID_Generador');
    }
}