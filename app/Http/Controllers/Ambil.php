<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Kirim as MdlKirim;

use Carbon\Carbon;

class Ambil extends Controller
{
    //
    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function getCurrent() {
        $getDataRumah = MdlKirim::suhuTerakhir();
        $getDataTangerang = $this->suhuTangerang();
        $dataRumah = [
            'icon' => 1,
            'suhu_c' => 0,
            'suhu_f' => 0,
            'kelembaban' => 0
        ];
        $dataTangerang = [
            'icon' => 1,
            'suhu_c' => 0,
            'suhu_f' => 0,
            'kelembaban' => 0
        ];
        if(!empty($getDataRumah)) {
            $isHujan = (isset($getDataRumah->nilai_hujan_digital) && !$getDataRumah->nilai_hujan_digital)?1:0;
            $lastUpdate = (isset($getDataRumah->created_at))?strtotime($getDataRumah->created_at):0;
            $dataRumah = [
                'icon' => (!$isHujan)?3:2,
                'suhu_c' => round((isset($getDataRumah->nilai_suhu_c))?$getDataRumah->nilai_suhu_c:0),
                'suhu_f' => round((isset($getDataRumah->nilai_suhu_f))?$getDataRumah->nilai_suhu_f:0),
                'kelembaban' => (isset($getDataRumah->nilai_lembab))?$getDataRumah->nilai_lembab:0,
                'last' => Carbon::createFromTimeStamp($lastUpdate)->setTimezone('Asia/Jakarta')->format("F d, Y - H:i:s")
            ];
        }
        if(!empty($getDataTangerang)) {
            /* 
            <img style="float:left;height:64px;width:64px" ng-if="curentData.icon == 1" alt="Cloud" src="./resources/img/Cloud.svg"><!-- Clouds 803 804-->
            <img style="float:left;height:64px;width:64px" ng-if="curentData.icon == 2" alt="Cloud Drizzle" src="./resources/img/Cloud-Drizzle.svg"><!-- Rain -->
            <img style="float:left;height:64px;width:64px" ng-if="curentData.icon == 3" alt="Sun" src="./resources/img/Sun.svg"><!-- Clear 800 -->
            <img style="float:left;height:64px;width:64px" ng-if="curentData.icon == 4" alt="Cloud Sun" src="./resources/img/Cloud-Sun.svg"><!-- Clouds 801 802 -->
            <img style="float:left;height:64px;width:64px" ng-if="curentData.icon == 5" alt="Cloud Lightning" src="./resources/img/Cloud-Lightning.svg"><!-- Thunderstorm -->
            <img style="float:left;height:64px;width:64px" ng-if="curentData.icon == 6" alt="Cloud Fog" src="./resources/img/Cloud-Fog.svg"><!-- Haze Mist Smoke Dust Fog Sand Ash Squall Tornado -->
            <img style="float:left;height:64px;width:64px" ng-if="curentData.icon == 7" alt="Cloud Rain Sun" src="./resources/img/Cloud-Rain-Sun.svg"><!-- Drizzle -->
            */
            $icon = 3;
            if(isset($getDataTangerang->weather[0]->id) && isset($getDataTangerang->weather[0]->id)) {
                if ($getDataTangerang->weather[0]->main == "Clouds") {
                    if ($getDataTangerang->weather[0]->id == 803 || $getDataTangerang->weather[0]->id == 804) {
                        $icon = 1;
                    } else if ($getDataTangerang->weather[0]->id == 801 || $getDataTangerang->weather[0]->id == 802) {
                        $icon = 4;
                    }
                } else if ($getDataTangerang->weather[0]->main == "Rain") {
                    $icon = 1;
                } else if ($getDataTangerang->weather[0]->main == "Clear") {
                    $icon = 3;
                } else if ($getDataTangerang->weather[0]->main == "Thunderstorm") {
                    $icon = 5;
                } else if (
                    $getDataTangerang->weather[0]->main == "Haze" || 
                    $getDataTangerang->weather[0]->main == "Mist" || 
                    $getDataTangerang->weather[0]->main == "Smoke" || 
                    $getDataTangerang->weather[0]->main == "Dust" || 
                    $getDataTangerang->weather[0]->main == "Fog" || 
                    $getDataTangerang->weather[0]->main == "Sand" || 
                    $getDataTangerang->weather[0]->main == "Ash" || 
                    $getDataTangerang->weather[0]->main == "Squall" || 
                    $getDataTangerang->weather[0]->main == "Tornado"
                ) {
                    $icon = 6;
                } else if ($getDataTangerang->weather[0]->main == "Drizzle") {
                    $icon = 7;
                } 
            }
            $dataTangerang = [
                'icon' => $icon,
                'suhu_c' => round((isset($getDataTangerang->main->temp))?$getDataTangerang->main->temp:0),
                'kelembaban' => (isset($getDataTangerang->main->humidity))?$getDataTangerang->main->humidity:0,
                'last' => Carbon::now('Asia/Jakarta')->format("F d, Y - H:i:s")
            ];
            $dataTangerang['suhu_f'] = round(((9/5) * $dataTangerang['suhu_c']) + (32));
        }
        return response()->json([
            'status' => 1,
            'message' => 'Success',
            'data' => [
                // 'q' => $getDataTangerang,
                // 'w' => $getDataRumah,
                'tangerang' => $dataTangerang,
                'rumah' => $dataRumah
            ]
        ], 200);
    }

    public function getStatistik() {
        // d M
        $data = [
            'label' => [

            ],
            'datasets' => [

            ],
            'labelTemp' => [

            ]
        ];
        for ($i = 29; $i >= 0; $i--) { 
            $data['label'][] = Carbon::now('Asia/Jakarta')->sub($i . " day")->format("d M");
            $data['labelTemp'][] = Carbon::now('Asia/Jakarta')->sub($i . " day")->format("Y-m-d");
            $data['datasets'][] = 0;
        }
        $getData = MdlKirim::getStatistik();
        if(!empty($getData)) {
            foreach ($data['labelTemp'] as $key => $value) {
                foreach ($getData as $key2 => $value2) {
                    if ($value == $value2->kapan) {
                        $data['datasets'][$key] = ($value2->count * 5);
                    }
                }
            }
        }
        return response()->json([
            'status' => 1,
            'message' => 'Success',
            'data' => [
                // 'q' => $getData,
                // 'w' => $data['labelTemp'],
                'label' => $data['label'],
                'datasets' => $data['datasets']
            ]
        ], 200);
    }

    public function suhuTangerang() {
        $endpoint = "https://api.openweathermap.org/data/2.5/weather";
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $endpoint, ['query' => [
            'appid' => 'b0488e10d17ff3067ebcbbd9dc607a2a', 
            'id' => '1625084',
            'units' => 'metric',
            'lang' => 'en'
        ]]);

        $statusCode = $response->getStatusCode();
        $payload = json_decode($response->getBody()->getContents());
        return $payload;
    }

}
