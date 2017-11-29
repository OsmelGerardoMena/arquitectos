<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model {

    use SoftDeletes;

    /**
     * Nombre de la tabla en bd
     *
     * @var string
     */
    protected $table = 'TbDirEmpresa';

    /**
     * la llave primaria de la tabla
     *
     * @var string
     */
    protected $primaryKey = 'tbDirEmpresaID';

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

    public static function categories() {
        $categories = DB::table('tbDirPersonaEmpresaCategoria')->get();
        return $categories;
    }

    public function comments() {
       return $this->hasMany('\App\Models\Comment','ComentarioTablaID')->where('ComentarioTabla', '=', $this->table)->orderBy('ComentarioFecha', 'DESC');
    }

    public function emails() {
        return $this->hasMany('App\Models\BusinessEmail', 'tbDirEmpresaID_DirEmailEmpresa', 'tbDirEmpresaID');
    }

    public function work() {
        return $this->hasMany('\App\Models\BusinessWork', 'tbDirEmpresaID_DirEmpresaObra');
    }

    public function addresses() {
        return $this->hasMany('\App\Models\BusinessAddress', 'tbDirEmpresaID_EmpresaDomicilio');
    }

    public function persons() {
        return $this->hasMany('\App\Models\PersonBusiness', 'tbDirEmpresaID_DirPersonaEmpresa', 'tbDirEmpresaID');
    }

    public function myBusiness() {
        return $this->belongsToMany('\App\Models\MyBusiness', 'tbDirEmpresaMiEmpresa', 'tbDirEmpresaID_DirEmpresaMiEmpresa', 'tbMiEmpresaID_DirEmpresaMiEmpresa')->withPivot('tbDirEmpresaMiEmpresaID');
    }

    public function personsWork() {
        return $this->hasMany('App\Models\PersonBusinessWork', 'tbDirEmpresaID_DirPersonaEmpresaObra', 'tbDirEmpresaID');
    }
}