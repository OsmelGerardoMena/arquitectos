<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionWork extends Model {

    use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbObra';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbObraID';

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

    public function business()
    {
        return $this->belongsToMany('\App\Models\Business','tbDirEmpresaObra')
            ->withPivot('tbDirEmpresaID_DirEmpresaObra');
    }

    public function contracts() {
        return $this->hasMany('\App\Models\Contract','tbObraID_Contrato', 'tbObraID');
    }

    public function owner() {
        return $this->hasOne('\App\Models\Customer','TbClientesID', 'tbClienteID_ObraPropietario');
    }
}