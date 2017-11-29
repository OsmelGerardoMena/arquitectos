<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessAddress extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbDirEmpresaDomicilio';

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'tbDirEmpresaDomicilioID';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleting(function ($model) {
            $model->deleted_by = Auth::id();
            $model->save();
        });
    }

    public function address() {
        return $this->hasOne('\App\Models\Address', 'tbDirDomicilioID', 'tbDirDomicilioID_EmpresaDomicilio');
    }
}