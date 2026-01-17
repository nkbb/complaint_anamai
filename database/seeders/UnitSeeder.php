<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [1,'สถาบันเวชศาสตร์วิถีชีวิต กรมอนามัย','',1,1],
            [2,'กองอนามัยฉุกเฉิน กรมอนามัย','',1,1],
            [3,'สำนักคณะกรรมการผู้ทรงคุณวุฒิ','',1,1],
            [4,'กองส่งเสริมความรอบรู้และสื่อสารสุขภาพ ','',1,1],
            [5,'สำนักงานเลขานุการกรม','',1,1],
            [6,'สำนักส่งเสริมสุขภาพ','',1,1],
            [7,'สำนักทันตสาธารณสุข','',1,1],
            [8,'สำนักโภชนาการ','',1,1],
            // [9,'','',1,1],
            // [10,'','',1,1],
            // [11,'','',1,1],
            // [12,'','',1,1],
            // [13,'','',1,1],
            // [14,'','',1,1],
            // [15,'','',1,1],
            // [16,'','',1,1],
            // [17,'','',1,1],
            // [18,'','',1,1],
            // [19,'','',1,1],
            // [20,'','',1,1],
            // [21,'','',1,1],
        ];

        foreach($items as $item){
            $chk = Unit::where('id',$item[0])->first();
            if(empty($chk)){
                Unit::create([
                    'id' => $item[0],
                    'name' => $item[1],
                    'short_name' => $item[2],
                    'area' => $item[3],
                    'type' => $item[4],
                ]);
                echo "Unit ".$item[1]." create successfull."."\n";
            }else{
                $chk->update([
                    'name' => $item[1],
                    'short_name' => $item[2],
                    'area' => $item[3],
                    'type' => $item[4],
                ]);
                echo "Unit ".$item[1]." updated successfull."."\n";
            }
        }
    }
}
