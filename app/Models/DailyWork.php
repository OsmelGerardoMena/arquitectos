<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyWork extends Model {

    use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbDiario';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbDiarioID';

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

    public function author() {
        return $this->hasOne('\App\Models\Person', 'tbDirPersonaID', 'tbDirPersonaID_DiarioAutor');
    }

    public function catcher() {
        return $this->hasOne('\App\Models\User', 'tbCTOUsuarioID', 'tbCTOUsuarioID_DiarioCapturo');
    }

    public function comments() {
        return $this->hasMany('\App\Models\Comment','ComentarioTablaID', 'tbDiarioID')->where('ComentarioTabla', '=', $this->table)->orderBy('ComentarioFecha', 'DESC');
    }

    /*public static function categories() {
        $categories = DB::table('tbDirPersonaEmpresaCategoria')->get();
        return $categories;
    }

    public function work() {
        return $this->hasMany('\App\Models\BusinessWork', 'tbDirEmpresaID_DirEmpresaObra', 'tbDirEmpresaID');
    }

    public function addresses() {
        return $this->hasMany('\App\Models\BusinessAddress', 'tbDirEmpresaID_EmpresaDomicilio', 'tbDirEmpresaID');
    }*/
}