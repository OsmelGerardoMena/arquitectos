<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

	use Authenticatable, CanResetPassword;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbCTOUsuario';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbCTOUsuarioID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CTOUsuarioNombre', 'CTOUsuarioContrasena',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'CTOUsuarioContrasena', 'remember_token',
    ];

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
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->RegistroUsuario = Auth::id();
            $model->updated_by = Auth::id();
        });

        self::deleting(function($model){
            $model->RegistroUsuario = Auth::id();
            $model->deleted_by = Auth::id();
            $model->save();
        });
    }

    public function username () {
    	return $this->CTOUsuarioNombre;
	}

    /**
     * @return mixed
     */
    public function getAuthIdentifier()
    {
    	return $this->tbCTOUsuarioID;
    }

    /**
     * @return string
     */
    public function getAuthPassword() 
    {
		return $this->CTOUsuarioContrasena;
	}

    /**
     * @return string
     */
    public function getRememberToken()
    {
        return null;
    }

    /**
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        // Store a new token user for the "remember me" functionality
    }

    /**
     * @return string
     */
    public function getRememberTokenName()
    {
        return null;
    }

    public function person()
    {
        return $this->hasOne('App\Models\Person', 'tbDirPersonaID', 'tbDirPersonaID_CTOUsuario');
    }

    public function role()
    {
        return $this->hasOne('App\Models\Role', 'tbCTOUsuarioGrupoID', 'tbCTOUsuarioGrupoID_CTOUsuario');
    }

}