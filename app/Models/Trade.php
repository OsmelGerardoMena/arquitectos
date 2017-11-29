<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Illuminate\Database\Eloquent\Builder;

class Trade extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbOficio';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbOficioID';

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

        /*static::addGlobalScope('RegistroCerrado', function(Builder $builder) {
            $builder->where('RegistroCerrado', '=', 0);
        });*/

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

    public function location() {
        return $this->hasOne('\App\Models\Location', 'tbLocalidadID', 'tbLocalidadID_OficioLocalidadExpedicion');
    }

    public function personReceiver() {
        return $this->hasOne('\App\Models\PersonMyBusiness', 'tbDirPersonaEmpresaMiEmpresaID', 'tbDirPersonaEmpresaObraID_OficioDestinatario');
    }

    public function businessReceiver() {
        return $this->hasOne('\App\Models\BusinessMyBusiness', 'tbDirEmpresaMiEmpresaID', 'tbDirPersonaEmpresaObraID_OficioDestinatario');
    }

    public function personSender() {
        return $this->hasOne('\App\Models\PersonMyBusiness', 'tbDirPersonaEmpresaMiEmpresaID', 'tbDirPersonaEmpresaObraID_OficioRemitente');
    }

    public function personReceivedBy() {
        return $this->hasOne('\App\Models\PersonMyBusiness', 'tbDirPersonaEmpresaMiEmpresaID', 'tbDirPersonaEmpresaObraID_OficioRecibidoPor');
    }

    public function catcher() {
        return $this->hasOne('\App\Models\User', 'tbCTOUsuarioID', 'OficioCreadoPor');
    }

    public function modifier() {
        return $this->hasOne('\App\Models\User', 'tbCTOUsuarioID', 'OficioModificadoPor');
    }
}