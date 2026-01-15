<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

use App\Models\Company;
use App\Models\Province;
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\Zipcode;
use App\Models\Unit;
use App\Models\ComplaintType;
use App\Models\ComplaintSub;
use App\Models\ComplaintPerson;
use App\Models\Complaints;
use App\Helpers\Helper;
use App\Models\ComplaintMethod;
use DB;
use Illuminate\Support\Facades\Http;
use App\Models\Telegram;


class ComplaintController extends Controller
{

    public function index(): View
    {

      $company = Company::find(1);
      $province = Province::select('id','name')->orderBy('name', 'ASC')->get();
      $unit = Unit::select('id','name')->where('type',1)->orderBy('name', 'ASC')->get();
      $type = ComplaintType::select('id','name','num')->where('type','>=',1)->orderBy('num', 'ASC')->get();
      $sub = ComplaintSub::select('id','name','complaint_type_id')->where('type',1)->orderBy('num', 'ASC')->get();
      $person = ComplaintPerson::select('id','name')->where('type',1)->orderBy('num', 'ASC')->get();

      return view('complaint.index', compact('company','province','unit','type','sub','person'));
    }

    public function store(Request $request){
      if(request()->ajax()) {

        $filename = null;
        if ($request->hasFile('file')) {
          $file = $request->file('file');
          $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

          $path = $file->storeAs('files', $filename, 'public');
        }

        $idcard = $request->idcard;
        $idcard_clean = str_replace('-', '', $idcard);

        $chk_idcard = Helper::checkIdcare();

        if (in_array($idcard_clean, $chk_idcard)) {
          return response()->json([
              'status' => 201,
              'message' => 'error idcard faild',
          ], 200);
        }

        $code = '';
        $nex_code = DB::table('complaints')
          ->select(DB::raw('MAX(CAST(SUBSTR(code, -6) AS UNSIGNED)) + 1 AS next_code'))
          ->value('next_code');
        if(empty($nex_code)){
          $code = $request->key_title.'000001';
        }else{
          $std_id="".sprintf("%06d",$nex_code);
          $code = $request->key_title.''.$std_id;
        }
        $complaint = Complaints::create([
          'code' => $code,
          'concealed' => ($request->concealed)? 1 : 0,
          'file' => $filename,
          'idcard' => Helper::encryptData($idcard_clean),
          'idcard_sub' => substr($request->phone, -4),
          'fname' => Helper::encryptData($request->fname),
          'lname' => Helper::encryptData($request->lname),
          'address' => Helper::encryptData($request->address),
          'work' => Helper::encryptData($request->work),
          'tel' => ($request->tel) ? Helper::encryptData($request->tel) : null,
          'email' => ($request->email) ? Helper::encryptData($request->email) : null,
          'phone' => Helper::encryptData($request->phone),
          'province_id' => $request->province_id,
          'district_id' => $request->district_id,
          'subdistrict_id' => $request->subdistrict_id,
          'zipcode' => $request->zipcode,
          'unit_id' => $request->unit_id,
          'type_id' => $request->type_id,
          'sub_id' => ($request->sub_id)? $request->sub_id : null,
          'person_id' => $request->person_id,
          'gender' => $request->gender,
          'name' => Helper::encryptData($request->name),
          'description' => Helper::encryptData($request->description),
          'improvement' => Helper::encryptData($request->improvement),
          'type' => 2,
          'ip' => $request->getClientIp(),
        ]);

        $show_type = $complaint->hasType->name;
        if($complaint->sub_id){
          $show_type .= '('.$complaint->hasSub->name.')';
        }
        

        $chk_user = Telegram::where("type",1)->get();
        foreach($chk_user as $row){
          $token = $row->token;
          $chatId = $row->chat_id;
          $message = "มีผู้แจ้งเรื่องร้องเรียนใหม่ \n".
                      "วันที่: ".Helper::getDateThaiFull(now())."\n".
                      "ชื่อผู้ร้อง: ".$request->fname." ".$request->lname."\n".
                      "รหัสเรื่องร้องเรียน: ".$code."\n".
                      "ร้องเรียนถึง: ".$complaint->hasUnit->name."\n".
                      "ประเด็นการ้องเรียน: ".$show_type."\n".
                      "ร้องเรียนบุคคล: ".$complaint->hasPerson->name."\n".
                      "เรื่องที่ร้องเรียน: ".$request->name;

          Helper::sendTelegramMessage($token, $chatId, $message);
        }

        return response()->json([
            'status' => 200,
            'message' => 'succeed',
            'code' => $code,
        ], 200);
      }else{
          abort(404);
      }
    }

    public function follow(){
      return view('complaint.follow');
    }


    public function getProvince(Request $request){
      if(request()->ajax()) {
          $item = Province::select('id','name')->orderBy('name', 'ASC')->get();
          return response()->json([
              'status' => 200,
              'message' => 'succeed',
              'item' => $item,
          ], 200);
      }else{
          abort(404);
      }
    }

    public function getDistrict(Request $request, $province_id){
      if(request()->ajax()) {
          $item = District::select('id','name')
            ->where('province_id', $province_id)
            ->orderBy('name', 'ASC')
            ->get();
          return response()->json([
              'status' => 200,
              'message' => 'succeed',
              'item' => $item,
          ], 200);
      }else{
          abort(404);
      }
    }

    public function getSubDistrict(Request $request, $district_id){
      if(request()->ajax()) {
          $item = Subdistrict::select('id','name','zip_code')
            ->where('district_id', $district_id)
            ->orderBy('name', 'ASC')
            ->get();
          return response()->json([
              'status' => 200,
              'message' => 'succeed',
              'item' => $item,
          ], 200);
      }else{
          abort(404);
      }
    }

    public function getZipcode(Request $request, $subdistrict_id){
      if(request()->ajax()) {
          $item = Zipcode::select('id','zipcode')
            ->where('subdistrict_id', $subdistrict_id)
            ->first();
          return response()->json([
              'status' => 200,
              'message' => 'succeed',
              'zipcode' => (!empty($item))?$item->zipcode:'',
          ], 200);
      }else{
          abort(404);
      }
    }
    

    public function followCheck(Request $request){

      if(request()->ajax()) {

        $idcard_sub = $request->data['phone'];
        $code = $request->data['code'];

        $item = Complaints::select('code','fname','lname','work','address','email','phone','tel', 'created_at',
          'improvement','name','description','idcard','unit_id','method_id','type_id','sub_id','person_id',
          'gender','concealed','province_id','district_id','subdistrict_id','zipcode','file','trace_approve','trace_show','type')
          ->where('code', $code)
          ->where('idcard_sub', $idcard_sub)
          ->first();
        if($item){
          if($item->concealed != 1){
            $item->fname = ($item->fname) ? Helper::decryptData($item->fname): '';
            $item->lname = ($item->lname) ? Helper::decryptData($item->lname): '';
            $item->work = ($item->work) ? Helper::decryptData($item->work): '';
            $address = ($item->address) ? Helper::decryptData($item->address): '';
            $item->email = ($item->email) ? Helper::decryptData($item->email): '';
            $item->phone = ($item->phone) ? Helper::decryptData($item->phone): '';
            $item->tel = ($item->tel) ? Helper::decryptData($item->tel): '';
            $item->address = Helper::getAddress($item->province_id, $item->district_id, $item->subdistrict_id, $address, $item->zipcode); 
          }else{
            $item->fname = '';
            $item->lname = '';
            $item->work = '';
            $item->email = '';
            $item->phone = '';
            $item->tel = '';
            $item->address = '';
            $item->zipcode = '';
            $item->province_id = '';
            $item->district_id = '';
            $item->subdistrict_id = '';
          }
          $item->improvement = ($item->improvement) ? Helper::decryptData($item->improvement): '';
          $item->name = ($item->name) ? Helper::decryptData($item->name): '';
          $item->description = ($item->description) ? Helper::decryptData($item->description): '';
          $item->idcard = ($item->idcard) ? Helper::decryptData($item->idcard): '';
          $item->trace_show = ($item->trace_show) ? Helper::decryptData($item->trace_show): '';

         

          if($item->unit_id){
              $unit = Unit::select('name')->where('id',$item->unit_id)->first();
              if($unit){
                  $item->unit_name = $unit->name;
              }
          }

          if($item->method_id){
              $methods = ComplaintMethod::select('name')->where('id',$item->method_id)->first();
              if($methods){
                  $item->method_name = $methods->name;
              }
          }

          if($item->type_id){
              $type = ComplaintType::select('name')->where('id',$item->type_id)->first();
              if($type){
                  $item->type_name = $type->name;
              }
          }
          if($item->sub_id){
              $sub = ComplaintSub::select('name')->where('id',$item->sub_id)->first();
              if($sub){
                  $item->sub_name = $sub->name;
              }
          }

          if($item->person_id){
              $person = ComplaintPerson::select('name')->where('id',$item->person_id)->first();
              if($person){
                  $item->person_name = $person->name;
              }
          }

          if($item->file){
              $item->file_url = asset('storage/files/' . $item->file);
          }

          return response()->json([
              'status' => 200,
              'message' => 'succeed',
              'item' => $item,
          ], 200);
        }else{
          return response()->json([
              'status' => 201,
              'message' => 'error follow check',
          ], 200);
        }

      }else{
          abort(404);
      }
    }

    public function test(){
      // return view('complaint.test');
      $token = "8260913888:AAHoRuNzvQCU-brJQ0w8uTzLxK-rCCiRCWg";
        $chatId = "-5047928395";
        $message = "ทดสอบส่งข้อความจาก Laravel!";

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text'    => $message,
        ]);

        return "ส่งข้อความไป Telegram แล้ว!";
    }
}
