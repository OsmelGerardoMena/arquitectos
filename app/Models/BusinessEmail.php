<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessEmail extends Model {

    use SoftDeletes;

    /**
     * Nombre de la tabla en bd
     *
     * @var string
     */
    protected $table = 'tbDirEmailEmpresa';

    /**
     * la llave primaria de la tabla
     *
     * @var string
     */
    protected $primaryKey = 'tbDirEmailEmpresaID';

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

    public function email() {
        return $this->hasOne('\App\Models\Email', 'tbDirEmailId','tbDirEmailID_DirEmailEmpresa');
    }

}