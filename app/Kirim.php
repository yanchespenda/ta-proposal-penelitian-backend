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
            'nilai_hujan_analog' => $analog,
            'nilai_hujan_digital' => $digital,
            'created_at' => Carbon::now()
        ];

        return DB::table($this->table)->insert($data_insert);
    }

    public function scopeSuhuTerakhir($query){
        return DB::table($this->table)->latest()->first(['nilai_hujan_digital', 'nilai_suhu_c', 'nilai_suhu_f', 'nilai_lembab', 'created_at']);
    }

    public function scopeGetStatistik($query){
        return DB::table($this->table)
                ->select(DB::raw('DATE(created_at) AS kapan, COUNT(*) AS count'))
                ->where('nilai_hujan_digital', '=', 0)
                ->whereBetween('created_at', [Carbon::now()->sub("30 day"), Carbon::now()])
                ->groupBy('kapan')
                ->get();
    }
}
