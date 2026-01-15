<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\Complaints;
use App\Models\ComplaintType;
use App\Models\ComplaintSub;
use App\Models\ComplaintMethod;
use App\Models\ComplaintPerson;
use App\Models\Unit;
use App\Models\Province;
use App\Models\Company;
use App\Helpers\Helper; 
use App\Models\Telegram;
use App\Models\User;
use App\Models\ComplaintLog;
use App\Models\ComplaintComment;
use App\Models\ComplaintSeverity;
use Auth;
use DB;
use Carbon\Carbon;

class AdminController extends Controller
{

    public function index(): View
    {

        if(Auth::user()->level == 'root'){        
            $count_type2 = Complaints::where('status', 1)->whereIn('type', [2])->count();
            $count_type3 = Complaints::where('status', 1)->whereIn('type', [3])->count();
            $count_type4 = Complaints::where('status', 1)->where('type', 5)->count();
            $count_type5 = Complaints::where('status', 1)->whereIn('type', [4,5,6])->count();
            $count_type0 = Complaints::where('status', 1)->whereIn('type', [0,7])->count();

        }else{

            $count_type2 = 0;
            $count_type3 = Complaints::where('status', 1)->where('type', 3)
                ->where('unit_id', Auth::user()->unit_id)
                ->count();
            $count_type4 = Complaints::where('status', 1)->whereIn('type', [4,6])
                ->where('unit_id', Auth::user()->unit_id)
                ->count();
            $count_type0 = 0;
            $count_type5 = Complaints::where('status', 1)->whereIn('type', [3,4,5,6,7,8])
            ->where('unit_id', Auth::user()->unit_id)
            ->count();
        }

        return view('admin.home', compact('count_type2','count_type3','count_type4','count_type0','count_type5'));


    }

    public function accept(): View
    {
         if(Auth::user()->level != 'root'){
            abrabort(404);
        }
        $unit = Unit::select('id','name')->where('type',1)->orderBy('name', 'ASC')->get();
        return view('admin.complaint.accept', compact('unit'));
    }

    public function follow(Request $request): View
    {
        if(Auth::user()->level != 'root'){
            abrabort(404);
        }
        $unit = Unit::select('id','name')->where('type',1)->orderBy('name', 'ASC')->get();
        $type = $request->type ?? 99;
        return view('admin.complaint.follow', compact('unit', 'type'));
    }

    public function receive(): View
    {
        $unit = [];
        return view('admin.complaint.receive', compact('unit'));
    }

     public function alter(): View
    {
        $unit = [];
        return view('admin.complaint.alter', compact('unit'));
    }

     public function userfollow(): View
    {
        $unit = [];
        return view('admin.complaint.userfollow', compact('unit'));
    }


    public function getComplaint(Request $request){
        if(request()->ajax()) {

            $type = [];
            if($request->type == 3){
                $type = [3];
            }else if($request->type == 5){
                $type = [4,5,6];
            }else if($request->type == 44){
                $type = [4,6];
            }else if($request->type == 77){
                $type = [7,0];
            }else if($request->type == 99){
                $type = [4,5,6,7,8,0];
            }else if($request->type == 88){
                $type = [3,4,5,6,7,8];
            }else{
                $type = [$request->type];
            }

        

            $where_unit = [];
            if(Auth::user()->level != 'root'){
                $where_unit = ['unit_id' => Auth::user()->unit_id];
            }

            $date_from = '';
            $date_end = '';
            if($request->s_date){
                $dt = Carbon::parse($request->s_date)->setTimezone('Asia/Bangkok');
                $date = $dt->format('Y-m-d');
                $date_from = $date.' 00:00:00';
                $date_end = $date.' 23:59:59';
            }

            $item_total = Complaints::where('status', 1)
                ->whereIn('type', $type)
                ->where($where_unit)
                ->where(function($query) use ($request) {
                    if(!empty($request->s_code)){
                        $query->where('code', 'like', '%'.$request->s_code.'%');
                    }
                })
                ->when(!empty($request->s_date), function($query) use ($date_from, $date_end) {
                    $query->whereBetween('created_at', [$date_from, $date_end]);
                })
                ->where('status', 1)
                ->count();
            $pagination['total'] = $item_total;
    
            $request->page = (empty($request->page)) ? 1 : $request->page;
            $request->limit = (empty($request->limit)) ? 10 : $request->limit;

            $offset = ($request->page -1) * $request->limit;
            $pagination['from'] = $offset + 1;
            $pagination['to'] = ($offset + 1) * $request->limit;
            if($pagination['to'] > $item_total){
                $pagination['to'] = $item_total;
            }

            $item = Complaints::select('id','code','unit_id','type_id','sub_id','name','method_id','created_at','type','status','user_id','is_add')
                ->whereIn('type', $type)
                ->where('status', 1)
                ->where($where_unit)
                ->where(function($query) use ($request) {
                    if(!empty($request->s_code)){
                        $query->where('code', 'like', '%'.$request->s_code.'%');
                    }
                })
                ->when(!empty($request->s_date), function($query) use ($date_from, $date_end) {
                    $query->whereBetween('created_at', [$date_from, $date_end]);
                })
                ->offset($offset)
                ->limit($request->limit)
                ->orderBy('code','DESC')
                ->get();
            foreach($item as $k => $v){
                $item[$k]->name = Helper::decryptData($v->name);
                $item[$k]->unit = '';
                $item[$k]->user_add = 'ผู้ร้องเรียน';

                if($v->unit_id){
                    $unit = Unit::select('name')->where('id', $v->unit_id)->first();
                    if(!empty($unit)){
                        $item[$k]->unit = $unit->name;
                    }
                }
                $item[$k]->methods = '';
                if($v->method_id){
                    $methods = ComplaintMethod::select('name')->where('id', $v->method_id)->first();
                    if(!empty($methods)){
                        $item[$k]->methods = $methods->name;
                    }
                }
                $item[$k]->type_name = '';
                if($v->type_id){
                    $type = ComplaintType::select('name')->where('id', $v->type_id)->first();
                    if(!empty($type)){
                        $item[$k]->type_name = $type->name;
                    }
                }
                $item[$k]->sub_name = '';
                if($v->sub_id){
                    $sub = ComplaintSub::select('name')->where('id', $v->sub_id)->first();
                    if(!empty($sub)){
                        $item[$k]->sub_name = $sub->name;
                    }
                }


                $process = ComplaintLog::where('complaint_id', $v->id)->count();
                $item[$k]->process = $process;

                
                if($v->user_id){
                    $user = User::select('name','level','unit_id')->where('id', $v->user_id)->first();
                    if($user){
                        $item[$k]->user_add = $user->name;
                        $item[$k]->user_add .= ($user->level == 'root') ? ' (เจ้าหน้าที่ศูนย์รับเรื่องร้องเรียน)'  : ' (หน่วยงาน)';
                        
                    }
                }
                    
            }

            return response()->json([
                'status' => 200,
                'item' => $item,
                'pagination' => $pagination
            ], 200);

        }else{
            abort(404);
        }
    }

    public function create(): View
    {
        return view('admin.complaint.create');
    }

    public function getMasterData(){
        if(request()->ajax()) {
            $unit = Unit::select('id','name')->where('type',1)->orderBy('name', 'ASC')->get();
            $type = ComplaintType::select('id','name','num')->where('type','>=',1)->orderBy('num', 'ASC')->get();
            $sub = ComplaintSub::select('id','name','complaint_type_id')->where('type',1)->orderBy('num', 'ASC')->get();
            $person = ComplaintPerson::select('id','name')->where('type',1)->orderBy('num', 'ASC')->get();
            $province = Province::select('id','name')->orderBy('name', 'ASC')->get();
            $methods = ComplaintMethod::select('id','name')->where('type',1)->orderBy('num', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'unit' => $unit,
                'type' => $type,
                'sub' => $sub,
                'person' => $person,
                'province' => $province,
                'methods' => $methods,
            ], 200);
        }else{
            abort(404);
        }
    }

    public function complaintStore(Request $request){
        if(request()->ajax()) {

        $filename = null;
        if ($request->hasFile('file')) {
          $file = $request->file('file');
          $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

          $path = $file->storeAs('files', $filename, 'public');
        }

        $idcard = '';
        if($request->idcard){
            $idcard = $request->idcard;
            $idcard_clean = str_replace('-', '', $idcard);
            $chk_idcard = Helper::checkIdcare();
            if (in_array($idcard_clean, $chk_idcard)) {
            return response()->json([
                'status' => 201,
                'message' => 'error idcard faild',
            ], 200);
            }
        }


        
        $upd['concealed']     = ($request->concealed) ? 1 : 0;
        $upd['file']          = $filename;
        $upd['idcard']        = ($idcard) ? Helper::encryptData($idcard_clean) : null;
        $upd['idcard_sub']    = ($request->phone) ? substr($request->phone, -4) : null;
        $upd['fname']         = ($request->fname) ? Helper::encryptData($request->fname) : null;
        $upd['lname']         = ($request->lname) ? Helper::encryptData($request->lname) : null;
        $upd['address']       = ($request->address) ? Helper::encryptData($request->address) : null;
        $upd['work']          = ($request->work) ? Helper::encryptData($request->work) : null;
        $upd['tel']           = ($request->tel) ? Helper::encryptData($request->tel) : null;
        $upd['email']         = ($request->email) ? Helper::encryptData($request->email) : null;
        $upd['phone']         = ($request->phone) ? Helper::encryptData($request->phone) : null;

        $upd['province_id']   = ($request->province_id && $request->province_id != "null") ? $request->province_id : null;
        $upd['district_id']   = ($request->district_id && $request->district_id != "null") ? $request->district_id : null;
        $upd['subdistrict_id']= ($request->subdistrict_id && $request->subdistrict_id != "null") ? $request->subdistrict_id : null;
        $upd['zipcode']       = ($request->zipcode && $request->zipcode != "null") ? $request->zipcode : null;

        $upd['unit_id']       = $request->unit_id;
        $upd['type_id']       = $request->type_id;
        $upd['sub_id']        = ($request->sub_id != "undefined" && $request->sub_id)? $request->sub_id : null;
        $upd['person_id']     = $request->person_id;
        $upd['gender']        = $request->gender ?: null;

        $upd['name']          = Helper::encryptData($request->name);
        $upd['description']   = Helper::encryptData($request->description);
        $upd['improvement']   = Helper::encryptData($request->improvement);

        $upd['type']          = (Auth::user()->level == 'root' || Auth::user()->level == 'admin') ? 2 : 3;
        $upd['ip']            = $request->getClientIp();
        $upd['user_id']       = Auth::user()->id;
    
        if(Auth::user()->level == 'unit'){
           $upd['send_unit'] = Auth::user()->unit_id; 
        }
        if($request->id){

            $chk = Complaints::find($request->id);
            $chk->update($upd);
            $code = $chk->code;

        }else{
            $company = Company::find(1);

            $code = '';
            $nex_code = DB::table('complaints')
            ->select(DB::raw('MAX(CAST(SUBSTR(code, -6) AS UNSIGNED)) + 1 AS next_code'))
            ->value('next_code');
            if(empty($nex_code)){
            $code = $request->key_title.'000001';
            }else{
            $std_id="".sprintf("%06d",$nex_code);
                $code = $company->key_title.''.$std_id;
            }

            $is_add = 1;
            if(Auth::user()->level == 'unit'){
                $is_add = 2;
            }
            if(Auth::user()->level == 'root'){
                $is_add = 3;
            }

            $upd['code'] = $code;
            $upd['is_add'] = $is_add;
            Complaints::create($upd);
            /*Complaints::create([
                'code' => $code,
                'concealed' => ($request->concealed)? 1 : 0,
                'file' => $filename,
                'idcard' => ($idcard)? Helper::encryptData($idcard_clean) : null,
                'idcard_sub' => ($request->phone)? substr($request->phone, -4) : null,
                'fname' => ($request->fname)? Helper::encryptData($request->fname) : null,
                'lname' => ($request->lname)? Helper::encryptData($request->lname) : null,
                'address' => ($request->address)? Helper::encryptData($request->address) : null,
                'work' => ($request->work)? Helper::encryptData($request->work) : null,
                'tel' => ($request->tel) ? Helper::encryptData($request->tel) : null,
                'email' => ($request->email) ? Helper::encryptData($request->email) : null,
                'phone' => ($request->phone)? Helper::encryptData($request->phone) : null,
                'province_id' => ($request->province_id)? $request->province_id : null,
                'district_id' => ($request->district_id)? $request->district_id : null,
                'subdistrict_id' => ($request->subdistrict_id)? $request->subdistrict_id : null,
                'zipcode' => ($request->zipcode)? $request->zipcode : null,
                'unit_id' => $request->unit_id,
                'type_id' => $request->type_id,
                'sub_id' => ($request->sub_id)? $request->sub_id : null,
                'person_id' => $request->person_id,
                'gender' => ($request->gender)? $request->gender : null,
                'name' => Helper::encryptData($request->name),
                'description' => Helper::encryptData($request->description),
                'improvement' => Helper::encryptData($request->improvement),
                'type' => (Auth::user()->level == 'root' || Auth::user()->level == 'admin' )? 2 : 3,
                'ip' => $request->getClientIp(),
                'user_id' => Auth::user()->id,
                'is_add' => $is_add,
            ]);*/
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

    public function remove(Request $request){
        if(request()->ajax()) {

            $item = Complaints::find($request->id);
            if($item){
                $item->update(['status' => 0]);
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

    public function getComplaintById(Request $request, $id){
        if(request()->ajax()) {

            $item = Complaints::find($id);
            if($item){

                if(Auth::user()->level == 'unit' && $item->concealed == 1 && $item->is_add != 2){
                    $item->fname = '';
                    $item->lname = '';
                    $item->work = '';
                    $item->address  = '';
                    $item->email = '';
                    $item->phone = '';
                    $item->tel = '';
                    $item->idcard = '';
                    $item->zipcode = '';
                    
                }else{
                    //
                    $item->fname = ($item->fname) ? Helper::decryptData($item->fname): '';
                    $item->lname = ($item->lname) ? Helper::decryptData($item->lname): '';
                    $item->work = ($item->work) ? Helper::decryptData($item->work): '';
                    $item->address  = ($item->address) ? Helper::decryptData($item->address): '';
                    $item->email = ($item->email) ? Helper::decryptData($item->email): '';
                    $item->phone = ($item->phone) ? Helper::decryptData($item->phone): '';
                    $item->tel = ($item->tel) ? Helper::decryptData($item->tel): '';
                    $item->idcard = ($item->idcard) ? Helper::decryptData($item->idcard): '';
                }



                $item->name = ($item->name) ? Helper::decryptData($item->name): '';
                
                $item->auth_fname = ($item->auth_fname) ? Helper::decryptData($item->auth_fname): '';
                $item->auth_lname = ($item->auth_lname) ? Helper::decryptData($item->auth_lname): '';
                $item->auth_phone = ($item->auth_phone) ? Helper::decryptData($item->auth_phone): '';
                $item->auth_email = ($item->auth_email) ? Helper::decryptData($item->auth_email): '';

                if($request->type == 'show'){
                    $item->improvement = ($item->improvement) ? Helper::convoretHtml(Helper::decryptData($item->improvement)): '';
                    $item->description = ($item->description) ? Helper::convoretHtml(Helper::decryptData($item->description)): '';
                    $item->trace_show = ($item->trace_show) ? Helper::convoretHtml(Helper::decryptData($item->trace_show)): '';


                    if(Auth::user()->level == 'unit' && ($item->type == 4 || $item->type == 5 || $item->type == 6) ){
                        $item->answer_detail = ($item->answer_detail) ? Helper::decryptData($item->answer_detail): '';
                    }else{
                        $item->answer_detail = ($item->answer_detail) ? Helper::convoretHtml(Helper::decryptData($item->answer_detail)): '';
                    }
                    

                    if(Auth::user()->level == 'unit' && $item->concealed == 1 && $item->is_add != 2){
                        $item->address = '';
                    }else{
                        $address = $item->address;
                        $item->address = Helper::getAddress($item->province_id, $item->district_id, $item->subdistrict_id, $address, $item->zipcode); 
                    }

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

                    if($item->severity_admin){
                        $severity = ComplaintSeverity::select('name','time')->where('id',$item->severity_admin)->first();
                        if($severity){
                            $item->severity_name = $severity->name;
                            $item->severity_time = $severity->time;
                        }
                    }

                    $item->user_add = 'ผู้ร้องเรียน';
                    if($item->user_id){
                        $user = User::select('name','level','unit_id')->where('id', $item->user_id)->first();
                        if($user){
                            $item->user_add = $user->name.' ('.$user->level.')';
                            $item->user_add .= ($user->level == 'root') ? ' (เจ้าหน้าที่ศูนย์รับเรื่องร้องเรียน)'  : ' (หน่วยงาน)';
                        }
                    }

                    if($item->file){
                        $item->file_url = asset('storage/files/' . $item->file);
                    }

                    if($item->send_files){
                        $item->send_files_url = asset('storage/files/' . $item->send_files);
                    }

                    if($item->answer_file){
                        $item->answer_file_url = asset('storage/files/' . $item->answer_file);
                    }

                   $item->log =  ComplaintLog::where('complaint_id', $item->id)
                    ->orderBy('date_time','DESC')
                    ->get();
                    foreach($item->log as $k => $v){
                        if($v->type == 6 && $v->comment_id){
                            $comment = ComplaintComment::find($v->comment_id);
                            if($comment){
                                $item->log[$k]->comment_ask = $comment->ask_unit;
                                $item->log[$k]->comment_com = $comment->comment_unit;
                                $item->log[$k]->comment_date = $comment->date_com;
                                $item->log[$k]->user_com = $comment->user_com;
                                $item->log[$k]->user_ask = $comment->user_ask;
                                $item->log[$k]->comment_type = $comment->type;
                            }
                        }
                    }

                }else{
                    $item->improvement = ($item->improvement) ? Helper::decryptData($item->improvement): '';
                    $item->description = ($item->description) ? Helper::decryptData($item->description): '';
                    $item->trace_show = ($item->trace_show) ? Helper::decryptData($item->trace_show): '';

                    $item->answer_detail = ($item->answer_detail) ? Helper::decryptData($item->answer_detail): '';
                }
                
                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                    'item' => $item,
                ], 200);
            }else{
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        }else{
            abort(404);
        }
    }

    public function cancel(Request $request, $id){
        if(request()->ajax()) {

            $item = Complaints::find($id);
            if($item){
                $item->type = 0;
                $item->del_user = Auth::user()->id;
                $item->del_date = now();
                $item->del_comm = $request->comm_cancel;
                $item->save();

                ComplaintLog::create([
                    'complaint_id' => $item->id,
                    'type' => 1,
                    'date_time' =>  $item->del_date,
                    'user_id' => Auth::user()->name,
                ]);

                 $chk_user = Telegram::where("type",1)
                    ->get();

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            }else{
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        }else{
            abort(404);
        }
    }

    public function send(Request $request, $id){
        if(request()->ajax()) {

            $item = Complaints::find($id);
            if($item){

                $filename = null;
                if ($request->hasFile('file')) {
                  $file = $request->file('file');
                  $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                  $path = $file->storeAs('files', $filename, 'public');
                }

                $item->type = 3;
                $item->send_user = Auth::user()->id;
                $item->send_date = now();
                $item->send_unit = $request->send_unit;
                $item->unit_id = $request->send_unit;
                $item->send_comm = $request->send_comm;
                $item->complain_level = $request->complain_level;
                $item->severity_admin = $request->severity_admin;
                if($filename){
                    if($item->send_files){
                        $item->send_files = $item->send_files.','.$filename;
                    }else{
                        $item->send_files = $filename;
                    }
                }
                $item->save();

                ComplaintLog::create([
                    'complaint_id' => $item->id,
                    'type' => 2,
                    'date_time' =>  $item->send_date,
                    'user_id' => Auth::user()->name,
                ]);


                $chk_user = Telegram::where("type",2)
                    ->where("unit_id", $request->send_unit)
                    ->get();
                foreach($chk_user as $row){
                    $token = $row->token;
                    $chatId = $row->chat_id;
                    $message = "ศูนย์ได้ส่งเรื่องร้องเรียน ให้หน่วยงานของท่าน \n".
                                "รหัสเรื่องร้องเรียน: ".$item->code."\n";

                    Helper::sendTelegramMessage($token, $chatId, $message);
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            }else{
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        }else{
            abort(404);
        }
    }

    public function receiveUpdate(Request $request, $id){
         if(request()->ajax()) {

            $item = Complaints::find($id);
            if($item){
                $item->type = 4;
                $item->auth_fname = Helper::encryptData($request->auth_fname);
                $item->auth_lname = Helper::encryptData($request->auth_lname);
                $item->auth_email = Helper::encryptData($request->auth_email);
                $item->auth_phone = Helper::encryptData($request->auth_phone);
                $item->receive_user = Auth::user()->id;
                $item->receive_date = now();
                $item->save();

                ComplaintLog::create([
                    'complaint_id' => $item->id,
                    'type' => 3,
                    'date_time' =>  $item->receive_date,
                    'user_id' => Auth::user()->name,
                ]);

                $chk_user = Telegram::where("type",1)
                    ->get();
                foreach($chk_user as $row){
                    $token = $row->token;
                    $chatId = $row->chat_id;
                    $message = "หน่วยงานรับเรื่องร้องเรียน \n".
                                "รหัสเรื่องร้องเรียน: ".$item->code."\n";

                    Helper::sendTelegramMessage($token, $chatId, $message);
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            }else{
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        }else{
            abort(404);
        }
    }

    public function addComment(Request $request, $id){
        if(request()->ajax()) {

            $item = Complaints::find($id);
            if($item){

                $comm = ComplaintComment::create([
                    'ask_unit' => $request->comment,
                    'user_ask' => Auth::user()->name,
                    'date_ask' => now(),
                    'type' => 1,
                ]);


                ComplaintLog::create([
                    'complaint_id' => $item->id,
                    'comment_id' => $comm->id,
                    'type' => 6,
                    'date_time' =>  now(),
                    'user_id' => Auth::user()->name,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            }else{
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        }else{
            abort(404);
        }
    }

    public function addReply(Request $request, $id){
        if(request()->ajax()) {

            $comm = ComplaintComment::find($request->id);
            if($comm){

                $comm->update([
                    'comment_unit' => $request->comment,
                    'user_com' => Auth::user()->name,
                    'date_com' => now(),
                    'type' => 2,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            }else{
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        }else{
            abort(404);
        }
    }

    public function deleteComment(Request $request){
        if(request()->ajax()) {
            $log = ComplaintLog::find($request->id);
            if($log){

                $comm = ComplaintComment::where("id", $log->comment_id)->first();
                if($comm && $comm->type == 1){
                    $log->delete();
                    $comm->delete();

                    return response()->json([
                        'status' => 200,
                        'message' => 'succeed',
                    ], 200);
                }
            }

            return response()->json([
                'status' => 201,
                'message' => 'error',
            ], 200);
            

        }else{
            abort(404);
        }
    }

    public function saveTrace(Request $request, $id){
        if(request()->ajax()) {

            $item = Complaints::find($id);
            if($item){

                $item->trace_approve = $request->data['trace_approve'];
                $item->trace_show = ($request->data['trace_show']) ? Helper::encryptData($request->data['trace_show']) : '';
                $item->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            }else{
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        }else{
            abort(404);
        }
    }

    public function saveAnswer(Request $request, $id){
        if(request()->ajax()) {

            $item = Complaints::find($id);
            if($item){

                $item->answer_detail = ($request->answer_detail) ? Helper::encryptData($request->answer_detail) : null;

                $filename = null;
                if ($request->hasFile('answer_file')) {
                  $file = $request->file('answer_file');
                  $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                  $path = $file->storeAs('files', $filename, 'public');
                  $item->answer_file = $filename;
                }

                $item->answer_name = ($request->answer_name) ? $request->answer_name : null;
                $item->answer_date = now();
                $item->answer_name = Auth::user()->name;
                $item->type = 5;
                $item->save();

                ComplaintLog::create([
                    'complaint_id' => $item->id,
                    'type' => 4,
                    'date_time' =>  $item->answer_date,
                    'user_id' => Auth::user()->name,
                ]);

                $chk_user = Telegram::where("type",1)
                    ->get();
                foreach($chk_user as $row){
                    $token = $row->token;
                    $chatId = $row->chat_id;
                    $message = "หน่วยกำกับดูแล ตอบการแก้ไขปัญหาข้อร้องเรียน \n".
                                "รหัสเรื่องร้องเรียน: ".$item->code."\n";

                    Helper::sendTelegramMessage($token, $chatId, $message);
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            }else{
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        }else{
            abort(404);
        }
    }

    public function saveReport(Request $request, $id){
        if(request()->ajax()) {

            $item = Complaints::find($id);
            if($item){

                $item->answer_detail = ($request->answer_detail) ? Helper::encryptData($request->answer_detail) : null;

                $filename = null;
                if ($request->hasFile('report_file')) {
                  $file = $request->file('report_file');
                  $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                  $path = $file->storeAs('files', $filename, 'public');
                  $item->answer_file = $filename;
                }

                $item->report_name = ($request->report_name) ? $request->report_name : null;
                $item->report_date = now();
                $item->report_name = Auth::user()->name;
                $item->type = 8;
                $item->save();

                ComplaintLog::create([
                    'complaint_id' => $item->id,
                    'type' => 9,
                    'date_time' =>  $item->report_date,
                    'user_id' => Auth::user()->name,
                ]);

                /*$chk_user = Telegram::where("type",1)
                    ->get();
                foreach($chk_user as $row){
                    $token = $row->token;
                    $chatId = $row->chat_id;
                    $message = "หน่วยกำกับดูแล ตอบการแก้ไขปัญหาข้อร้องเรียน \n".
                                "รหัสเรื่องร้องเรียน: ".$item->code."\n";

                    Helper::sendTelegramMessage($token, $chatId, $message);
                }*/

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            }else{
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        }else{
            abort(404);
        }
    }

    public function sendError($id){
        if(request()->ajax()) {

            $item = Complaints::find($id);
            if($item){
                if($item->type == 5){
                    $item->update([
                        'type' => 6,
                    ]);

                    ComplaintLog::create([
                        'complaint_id' => $item->id,
                        'type' => 5,
                        'date_time' =>  now(),
                        'user_id' => Auth::user()->name,
                    ]);

                    $chk_user = Telegram::where("type",2)
                            ->where("unit_id", $item->send_unit)
                            ->get();
                        foreach($chk_user as $row){
                            $token = $row->token;
                            $chatId = $row->chat_id;
                            $message = "ศูนย์ได้ส่งเรื่องร้องเรียน กลับให้หน่วย แก้ไขข้อร้องเรียนใหม่อีกครั้ง \n".
                                        "รหัสเรื่องร้องเรียน: ".$item->code."\n";

                            Helper::sendTelegramMessage($token, $chatId, $message);
                        }

                    return response()->json([
                        'status' => 200,
                        'message' => 'succeed',
                    ], 200);
                }
            }

            return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
        }else{
            abort(404);
        }


    }

    public function sendNextStep($id){
        if(request()->ajax()) {

            $item = Complaints::find($id);
            if($item){
                if($item->type == 5){
                    $item->update([
                        'type' => 7,
                    ]);

                    ComplaintLog::create([
                        'complaint_id' => $item->id,
                        'type' => 7,
                        'date_time' =>  now(),
                        'user_id' => Auth::user()->name,
                    ]);


                    return response()->json([
                        'status' => 200,
                        'message' => 'succeed',
                    ], 200);
                }
            }

            return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
        }else{
            abort(404);
        }


    }

    public function resetUpdate($id){
        if(request()->ajax()) {
            $item = Complaints::find($id);
            if($item){
                if($item->type == 0){
                    $item->update([
                        'type' => 2,
                    ]);

                    ComplaintLog::create([
                        'complaint_id' => $item->id,
                        'type' => 8,
                        'date_time' =>  now(),
                        'user_id' => Auth::user()->name,
                    ]);


                    return response()->json([
                        'status' => 200,
                        'message' => 'succeed',
                    ], 200);
                }
            }

            return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
        }else{
            abort(404);
        }
    }



}
