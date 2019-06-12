<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Kirim extends Model
{
    protected $table = 'tblhujan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function scopeKirim($query, $analog, $digital){
        $data_insert = [
            'nilai_analog' => $analog,
            'nilai_digital' => $digital,
            'created_at' => Carbon::now()
        ];

        return DB::table($this->table)->insert($data_insert);
    }
}
