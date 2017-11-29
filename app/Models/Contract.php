<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbContrato';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbContratoID';

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

    public function deliverables() {
        return $this->hasMany('\App\Models\ContractDeliverable','tbContratoID_ContratoEntregable', 'tbContratoID');
    }

    public function comments() {
        return $this->hasMany('\App\Models\Comment','ComentarioTablaID')->where('ComentarioTabla', '=', $this->table)->orderBy('ComentarioFecha', 'DESC');
    }

    public static function types() {
        $types = DB::table('tbContratoTipo')->get();
        return $types;
    }

    public static function typesAllocation() {
        $types = DB::table('tbContratoTipoAsignacion')->get();
        return $types;
    }

    public function estimates() {
        return $this->hasMany('App\Models\Estimate','tbContratoID_Estimacion', 'tbContratoID');
    }

    public function contractor()
    {
        return $this->hasOne('App\Models\BusinessWork','tbDirEmpresaObraID','tbDirEmpresaObraID_Contratista');
    }

    public function directCustomer()
    {
        return $this->hasOne('App\Models\BusinessWork','tbDirEmpresaObraID','tbDirEmpresaObraID_ClienteDirecto');
    }

    public function contractCustomer()
    {
        return $this->hasOne('App\Models\BusinessWork','tbDirEmpresaObraID','tbDirEmpresaObraID_ClienteContratante');
    }

    public function supervisingCompany()
    {
        return $this->hasOne('App\Models\BusinessWork','tbDirEmpresaObraID','tbDirEmpresaObraID_ContratoSupervisora');
    }

    public function customerSignature()
    {
        return $this->hasOne('App\Models\PersonWork','tbDirPersonaEmpresaObraID','tbDirPersonaEmpresaObraID_FirmaCliente');
    }

    public function customerRepresentative()
    {
        return $this->hasOne('App\Models\PersonWork','tbDirPersonaEmpresaObraID','tbDirPersonaEmpresaObraID_ClienteRepresentante');
    }

    public function contractorSignature()
    {
        return $this->hasOne('App\Models\PersonWork','tbDirPersonaEmpresaObraID','tbDirPersonaEmpresaObraID_FirmaContratista');
    }

    public function workManager()
    {
        return $this->hasOne('App\Models\PersonWork','tbDirPersonaEmpresaObraID','tbDirPersonaEmpresaObraID_ContratistaResponsableObra');
    }

    public function currency()
    {
        return $this->hasOne('App\Models\Currency', 'tbMonedaID', 'tbMonedasID_Contrato');
    }

    public function clientAddress()
    {
        return $this->hasOne('\App\Models\BusinessAddress', 'tbDirEmpresaDomicilioID', 'tbDirEmpresaDomicilioID_ContratoCliente');
    }

    public function contractorAddress()
    {
        return $this->hasOne('\App\Models\BusinessAddress', 'tbDirEmpresaDomicilioID', 'tbDirEmpresaDomicilioID_ContratoContratista');
    }
}