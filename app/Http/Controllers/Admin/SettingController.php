<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Unit;
use App\Models\Company;
use App\Models\ComplaintType;
use App\Models\ComplaintSub;
use App\Models\ComplaintMethod;
use App\Models\ComplaintPerson;
use App\Models\ComplaintSeverity;
use App\Models\Question;
use App\Models\CommentType;
use App\Models\CommentSub;
use App\Models\Banner;
use App\Models\User;
use App\Models\Telegram;
use Illuminate\Support\Facades\Hash;
use Auth;

class SettingController extends Controller
{


    public function index(): View
    {
        return view('admin.setting.index');
    }

    public function unit(): View
    {
        return view('admin.setting.unit');
    }

    public function unitLoad(){
        if(request()->ajax()) {

            $item = Unit::where('type', 1)->orderBy('name', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $item,
            ], 200);
        }else{
            abort(404);
        }
    }

    public function unitStore(Request $request){
        if(request()->ajax()) {

            if($request->data['id']){
                Unit::where('id', $request->data['id'])
                ->update([
                    'name' => $request->data['name'],
                    'short_name' => $request->data['shortName'],
                    'area' => $request->data['area'],
                    'type' => $request->data['type'],
                ]);
            }else{
                Unit::create([
                    'name' => $request->data['name'],
                    'short_name' => $request->data['shortName'],
                    'area' => $request->data['area'],
                    'type' => $request->data['type'],
                ]);
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

    public function unitRemove(Request $request){
        if(request()->ajax()) {

            if($request->id){
                if($request->type == 'unit'){
                    Unit::where('id', $request->id)
                    ->update([
                        'type' => 0,
                    ]);
                }
                if($request->type == 'methods'){
                    ComplaintMethod::where('id', $request->id)
                    ->update([
                        'type' => 0,
                    ]);
                }
                if($request->type == 'person'){
                    ComplaintPerson::where('id', $request->id)
                    ->update([
                        'type' => 0,
                    ]);
                }
                if($request->type = 'question'){
                    Question::where('id', $request->id)
                    ->update([
                        'type' => 0,
                    ]);
                }
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }


    public function type(): View
    {
        return view('admin.setting.type');
    }

    public function typeLoad(){
        if(request()->ajax()) {

            $item = ComplaintType::where('type','>', 0)->orderBy('num', 'ASC')->get();
            foreach($item as $k => $v){
                $sub = ComplaintSub::where('complaint_type_id', $v->id)->orderBy('num', 'ASC')->get();
                $item[$k]->sub = $sub;
            }

            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $item,
            ], 200);
        }else{
            abort(404);
        }
    }

    public function typeStore(Request $request){
        if(request()->ajax()) {

            if($request->data['id']){
                if($request->data['type'] == 'main'){
                    ComplaintType::where('id', $request->data['id'])
                    ->update([
                        'time_span' => $request->data['timeSpan'],
                    ]);
                }
                if($request->data['type'] == 'sub'){
                    ComplaintSub::where('id', $request->data['id'])
                    ->update([
                        'time_span' => $request->data['timeSpan'],
                    ]);
                }
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }
    
    public function timefinish(): View
    {
        $item = Company::select('id','dead_date_answer','dead_date_finish','dead_date_receive','dead_date_send')->where('id', 1)->first();
        return view('admin.setting.timefinish', compact('item'));
    }

    public function timefinishStore(Request $request){
        if(request()->ajax()) {
            $item = Company::find(1);
            if(!empty($item)){
                $item->update([
                    'dead_date_finish' => $request->data['deadDateFinish'],
                    'dead_date_receive' => $request->data['deadDateReceive'],
                    'dead_date_send' => $request->data['deadDateSend'],
                ]);
            } 
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

    public function complaint(): View
    {
        $item = Company::select('id','key_title','conditions')->where('id', 1)->first();
        return view('admin.setting.complaint', compact('item'));
    }

    public function complaintStore(Request $request){

        if(request()->ajax()) {
            $item = Company::find(1);
            if(!empty($item)){
                $item->update([
                    'key_title' => $request->data['keyTitle'],
                    'conditions' => $request->conditions,
                ]);
            } 
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

    public function methods(): View
    {
        return view('admin.setting.methods');
    }

    public function methodsLoad(){
        if(request()->ajax()) {

            $item = ComplaintMethod::where('type', 1)->orderBy('num', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $item,
            ], 200);
        }else{
            abort(404);
        }
    }

    public function methodsStore(Request $request){
        if(request()->ajax()) {

            if(isset($request->data['id']) && $request->data['id']){
                
                ComplaintMethod::where('id', $request->data['id'])
                ->update([
                    'name' => $request->data['name'],
                ]);
            }else{
                if(!$request->id){
                    $order = ComplaintMethod::select('num')->orderBy('num','DESC')->first();
                    $order_num = 1;
                    if(!empty($order)){
                        $order_num = $order->num + 1;
                    }
    
                    ComplaintMethod::create([
                        'name' => $request->data['name'],
                        'num' => $order_num,
                    ]);
                }else{
                    ComplaintMethod::where('id', $request->id)
                    ->update([
                        'num' => $request->num,
                    ]);
                    ComplaintMethod::where('id', $request->cid)
                    ->update([
                        'num' => $request->cnum,
                    ]);
                }
                
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

    public function person(): View
    {
        return view('admin.setting.person');
    }

    public function personLoad(){
        if(request()->ajax()) {
            $item = ComplaintPerson::where('type', 1)->orderBy('num', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $item,
            ], 200);
        }else{
            abort(404);
        }
    }

    public function personStore(Request $request){
        if(request()->ajax()) {

            if(isset($request->data['id']) && $request->data['id']){
                
                ComplaintPerson::where('id', $request->data['id'])
                ->update([
                    'name' => $request->data['name'],
                ]);
            }else{
                if(!$request->id){
                    $order = ComplaintPerson::select('num')->orderBy('num','DESC')->first();
                    $order_num = 1;
                    if(!empty($order)){
                        $order_num = $order->num + 1;
                    }
    
                    ComplaintPerson::create([
                        'name' => $request->data['name'],
                        'num' => $order_num,
                    ]);
                }else{
                    ComplaintPerson::where('id', $request->id)
                    ->update([
                        'num' => $request->num,
                    ]);
                    ComplaintPerson::where('id', $request->cid)
                    ->update([
                        'num' => $request->cnum,
                    ]);
                }
                
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

    public function severity(): View
    {
        return view('admin.setting.severity');
    }

    public function severityLoad(){
        if(request()->ajax()) {
            $item = ComplaintSeverity::where('type', 1)->orderBy('level', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $item,
            ], 200);
        }else{
            abort(404);
        }
    }

    public function manual(): View
    {
        return view('admin.setting.manual');
    }

    public function question(): View
    {
        return view('admin.setting.question');
    }

    public function questionLoad(){
        if(request()->ajax()) {
            $item = Question::where('type', 1)->orderBy('num', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $item,
            ], 200);
        }else{
            abort(404);
        }
    }

    public function questionStore(Request $request){
        if(request()->ajax()) {

            if(isset($request->data['id']) && $request->data['id']){
                
                Question::where('id', $request->data['id'])
                ->update([
                    'name' => $request->data['name'],
                ]);
            }else{
                if(!$request->id){
                    $order = Question::select('num')->orderBy('num','DESC')->first();
                    $order_num = 1;
                    if(!empty($order)){
                        $order_num = $order->num + 1;
                    }
    
                    Question::create([
                        'name' => $request->data['name'],
                        'num' => $order_num,
                    ]);
                }else{
                    Question::where('id', $request->id)
                    ->update([
                        'num' => $request->num,
                    ]);
                    Question::where('id', $request->cid)
                    ->update([
                        'num' => $request->cnum,
                    ]);
                }
                
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

    public function comment(): View
    {
        return view('admin.setting.comment');
    }

    public function commentLoad(){
        if(request()->ajax()) {

            $item = CommentType::where('type','>', 0)->orderBy('num', 'ASC')->get();
            foreach($item as $k => $v){
                $sub = CommentSub::where('Comment_type_id', $v->id)->orderBy('num', 'ASC')->get();
                $item[$k]->sub = $sub;
            }

            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $item,
            ], 200);
        }else{
            abort(404);
        }
    }

    public function commentStore(Request $request){
        if(request()->ajax()) {

            if($request->data['id']){
                if($request->data['type'] == 'main'){
                    CommentType::where('id', $request->data['id'])
                    ->update([
                        'name' => $request->data['name'],
                    ]);
                }
                if($request->data['type'] == 'sub'){
                    CommentSub::where('id', $request->data['id'])
                    ->update([
                        'name' => $request->data['name'],
                    ]);
                }
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }


    public function telegram(): View
    {
        $unit = Unit::where('type', 1)->orderBy('name', 'ASC')->get();
        return view('admin.setting.telegram', compact('unit'));
    }

    public function telegramLoad(Request $request){
        if(request()->ajax()) {

            $query = \App\Models\Telegram::query();

            if($request->has('unit_id') && $request->unit_id){
                $query->where('unit_id', $request->unit_id);
            }

            $item = $query->orderBy('id', 'ASC')->get();
            foreach($item as $k => $v){
                if($v->unit_id == null){
                    $item[$k]->unit_name = '-';
                    continue;
                }
                $unit = Unit::where('id', $v->unit_id)->first();
                $item[$k]->unit_name = $unit->name;
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $item,
            ], 200);
        }else{
            abort(404);
        }
    }

    public function telegramStore(Request $request){
        if(request()->ajax()) {

            if($request->data['id']){
                Telegram::where('id', $request->data['id'])
                ->update([
                    'token' => $request->data['token'],
                    'chat_id' => $request->data['chat_id'],
                    'type' => $request->data['type'],
                    'unit_id' => ($request->data['type'] == '2') ? $request->data['unit_id'] : null,
                ]);
            }else{
                Telegram::create([
                    'token' => $request->data['token'],
                    'chat_id' => $request->data['chat_id'],
                    'type' => $request->data['type'],
                    'unit_id' => ($request->data['type'] == '2') ? $request->data['unit_id'] : null,
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

    public function telegramRemove(Request $request){
        if(request()->ajax()) {

            if($request->id){
                Telegram::where('id', $request->id)->delete();
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

    public function users(): View
    {

        $unit = Unit::where('type', 1)->orderBy('name', 'ASC')->get();
        return view('admin.setting.users', compact('unit'));
    }


    public function usersLoad(Request $request){
        if(request()->ajax()) {

            $query = \App\Models\User::query();
            if(Auth::user()->level == 'unit'){
                $query->where('unit_id',  Auth::user()->unit_id);
            }

            if($request->has('unit_id') && $request->unit_id){
                $query->where('unit_id', $request->unit_id);
            }

            $item = $query->orderBy('name', 'ASC')->get();
            foreach($item as $k => $v){
                if($v->unit_id == null){
                    $item[$k]->unit_name = '-';
                    continue;
                }
                $unit = Unit::where('id', $v->unit_id)->first();
                $item[$k]->unit_name = $unit->name;
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $item,
            ], 200);
        }else{
            abort(404);
        }
    }

    public function usersStore(Request $request){
        if(request()->ajax()) {

            if($request->data['id']){
                User::where('id', $request->data['id'])
                ->update([
                    'name' => $request->data['name'],
                    'phone' => $request->data['phone'],
                    'email' => $request->data['email'],
                    'level' => $request->data['level'],
                    'unit_id' => ($request->data['level'] == 'unit') ? $request->data['unit_id'] : null,
                ]);
            }else{
                $chk = User::where('username', $request->data['username'])->first();
                if($chk){
                    return response()->json([
                        'status' => 201,
                        'message' => 'error',
                    ], 200);
                }


                User::create([
                    'name' => $request->data['name'],
                    'username' => $request->data['username'],
                    'phone' => $request->data['phone'],
                    'email' => $request->data['email'],
                    'level' => $request->data['level'],
                    'unit_id' => ($request->data['level'] == 'unit') ? $request->data['unit_id'] : null,
                    'password' => Hash::make($request->data['password']),
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

    public function usersRemove(Request $request){
        if(request()->ajax()) {

            if($request->id){
                User::where('id', $request->id)->delete();
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

    public function banner(): View
    {
        return view('admin.setting.banner');
    }

    public function bannerLoad(Request $request){
        if(request()->ajax()) {

            $items = Banner::where('type', $request->type)->orderBy('created_at', 'DESC')->get();
            foreach ($items as $item) {
                $item->image_url = asset('storage/banner/' . $item->image);
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $items,
            ], 200);
        }else{
            abort(404);
        }
    }

    public function bannerStore(Request $request){
        if(request()->ajax()) {
            $filename = null;
            if ($request->hasFile('file') && $request->file != 'undefined') {
                $file = $request->file('file');
                $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $path = $file->storeAs('banner', $filename, 'public');
            }
            if(!$filename){
                return response()->json([
                    'status' => 400,
                    'message' => 'error',
                ], 200);

            }

            if($request->id){
                Banner::where('id', $request->id)
                ->update([
                    'image' => $filename,
                    'uri' => $request->uri,
                    'type' => $request->type,
                ]);
            }else{
                Banner::create([
                    'image' => $filename,
                    'uri' => $request->uri,
                    'type' => $request->type,
                ]);
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

    public function bannerRemove(Request $request){
        if(request()->ajax()) {

            if($request->id){
                $banner = Banner::where('id', $request->id)->first();
                if($banner){
                    // Delete image file
                    if($banner->image && \Storage::disk('public')->exists('banner/' . $banner->image)){
                        \Storage::disk('public')->delete('banner/' . $banner->image);
                    }
                    // Delete record
                    Banner::where('id', $request->id)->delete();
                }
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

    public function popup(): View
    {
        return view('admin.setting.popup');
    }

    public function changePassword(): View
    {
        return view('admin.setting.change-password');
    }

    public function changePasswordStore(Request $request){
        if(request()->ajax()) {

            $user = Auth::user();
            if (!Hash::check($request->data['old_password'], $user->password)) {

                return response()->json([
                    'status' => 201,
                    'message' => 'รหัสผ่านเดิมไม่ถูกต้อง',
                ], 200);
            }

            if($user){
                $user->update([
                    'password' => Hash::make($request->data['confirm_password']),
                ]);
            } 
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        }else{
            abort(404);
        }
    }

}
