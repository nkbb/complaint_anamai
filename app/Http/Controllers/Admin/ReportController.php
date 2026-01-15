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
use App\Models\Complaints;
use App\Models\QuestionVote;
use App\Models\VisitorLog;
use App\Models\QuestionDetail;
use Illuminate\Support\Facades\Hash;
use Auth;
use Carbon\Carbon;
use App\Helpers\Helper; 

class ReportController extends Controller
{


    public function index(): View
    {
        
        return view('admin.report.index');
    }

    public function summary():view{

      $unit = Unit::select('id','name')
        ->where('type',1)
        ->orderBy('area','ASC')
        ->orderBy('name','ASC')
        ->get();
      $method = ComplaintMethod::select('id','name')
        ->where('type', 1)
        ->orderBy('num','ASC')
        ->get();

      $person = ComplaintPerson::select('id','name')
        ->where('type', 1)
        ->orderBy('num','ASC')
        ->get();
      return view('admin.report.summary',compact('unit','method','person'));
    }

    public function type():View{
      return view('admin.report.type');
    }

    public function typeLoad(Request $request){
      if(request()->ajax()) {

        if($request->type == 1){
          if(is_array($request->day) &&
            count($request->day) === 2 &&
            !empty($request->day[0]) &&
            !empty($request->day[1])){
            $dt = Carbon::parse($request->day[0])->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $dt_end = Carbon::parse($request->day[1])->setTimezone('Asia/Bangkok');
            $date_end = $dt_end->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date_end.' 23:59:59';
          }else{
            $dt = Carbon::parse($request->day)->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date.' 23:59:59';
          }
        }
        if($request->type == 2){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-'.$request->month.'-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfMonth()->toDateString() . ' 23:59:59';
        }

        if($request->type == 3){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-01-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfYear()->toDateString() . ' 23:59:59';
        }
        
        if(Auth::user()->level == 'root'){
          $where_unit = [];
        }else{
          $where_unit = ['send_unit' => Auth::user()->unit_id];
        }

        $item = ComplaintType::select('id','name','type','num')
          ->orderBy('num','ASC')
          ->get();
        foreach($item as $k => $v){

          $item[$k]->sub = [];
          if($v->type == 2){
            $item[$k]->count = 0;
            $sub = ComplaintSub::select('id','name','num')->where('complaint_type_id', $v->id)->orderBy('num','ASC')->get();
            foreach($sub as $kk => $vv){
              $chk = Complaints::where('status', 1)->where('type_id', $v->id)->where('sub_id', $vv->id)
                ->whereBetween('created_at', [$date_from, $date_end])
                ->where($where_unit)
                ->where('status', 1)
                ->count();
              $sub[$kk]->count = $chk;
            }
            $item[$k]->sub = $sub;
          }else{

            $chk = Complaints::where('status', 1)->where('type_id', $v->id)
              ->whereBetween('created_at', [$date_from, $date_end])
            ->where('status', 1)
            ->where($where_unit)
            ->count();
            $item[$k]->count = $chk;
          }

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

    public function methods():View{
      return view('admin.report.methods');
    }

    public function methodsLoad(Request $request){
      if(request()->ajax()) {

        if($request->type == 1){
          if(is_array($request->day) &&
            count($request->day) === 2 &&
            !empty($request->day[0]) &&
            !empty($request->day[1])){
            $dt = Carbon::parse($request->day[0])->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $dt_end = Carbon::parse($request->day[1])->setTimezone('Asia/Bangkok');
            $date_end = $dt_end->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date_end.' 23:59:59';

          }else{
            $dt = Carbon::parse($request->day)->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date.' 23:59:59';
          }
        }
        if($request->type == 2){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-'.$request->month.'-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfMonth()->toDateString() . ' 23:59:59';
        }

        if($request->type == 3){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-01-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfYear()->toDateString() . ' 23:59:59';
        }
        
        if(Auth::user()->level == 'root'){
          $where_unit = [];
        }else{
          $where_unit = ['send_unit' => Auth::user()->unit_id];
        }
        

        $item = ComplaintMethod::select('id','name','type','num')
          ->where('type', 1)
          ->orderBy('num','ASC')
          ->get();
        foreach($item as $k => $v){

          $item[$k]->sub = [];
          $chk = Complaints::where('status', 1)->where('method_id', $v->id)
            ->whereBetween('created_at', [$date_from, $date_end])
            ->where('status', 1)
            ->where($where_unit)
            ->count();
          $item[$k]->count = $chk;

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

    public function office():View{
       if(Auth::user()->level != 'root') {
            abort(403);
        }
      return view('admin.report.office');
    }

    public function officeLoad(Request $request){
      if(request()->ajax()) {

        if($request->type == 1){
          if(is_array($request->day) &&
            count($request->day) === 2 &&
            !empty($request->day[0]) &&
            !empty($request->day[1])){
            $dt = Carbon::parse($request->day[0])->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $dt_end = Carbon::parse($request->day[1])->setTimezone('Asia/Bangkok');
            $date_end = $dt_end->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date_end.' 23:59:59';

          }else{
            $dt = Carbon::parse($request->day)->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date.' 23:59:59';
          }
        }
        if($request->type == 2){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-'.$request->month.'-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfMonth()->toDateString() . ' 23:59:59';
        }

        if($request->type == 3){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-01-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfYear()->toDateString() . ' 23:59:59';
        }
        
        

        $item = Unit::select('id','name')
          ->where('type',1)
          ->orderBy('area','ASC')
          ->orderBy('name','ASC')
          ->get();
        foreach($item as $k => $v){


            $chk = Complaints::where('status', 1)->where('send_unit', $v->id)
              ->whereBetween('created_at', [$date_from, $date_end])
              ->where('status', 1)
              ->count();
            $item[$k]->count = $chk;
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

    public function person():View{
      return view('admin.report.person');
    }

    public function personLoad(Request $request){
      if(request()->ajax()) {

        if($request->type == 1){
          if(is_array($request->day) &&
            count($request->day) === 2 &&
            !empty($request->day[0]) &&
            !empty($request->day[1])){
            $dt = Carbon::parse($request->day[0])->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $dt_end = Carbon::parse($request->day[1])->setTimezone('Asia/Bangkok');
            $date_end = $dt_end->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date_end.' 23:59:59';

          }else{
            $dt = Carbon::parse($request->day)->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date.' 23:59:59';
          }
        }
        if($request->type == 2){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-'.$request->month.'-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfMonth()->toDateString() . ' 23:59:59';
        }

        if($request->type == 3){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-01-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfYear()->toDateString() . ' 23:59:59';
        }
        
        if(Auth::user()->level == 'root'){
          $where_unit = [];
        }else{
          $where_unit = ['send_unit' => Auth::user()->unit_id];
        }

        $item = ComplaintPerson::select('id','name')
          ->where('type',1)
          ->orderBy('num','ASC')
          ->get();
        foreach($item as $k => $v){


            $chk = Complaints::where('status', 1)->where('person_id', $v->id)
              ->whereBetween('created_at', [$date_from, $date_end])
              ->where('status', 1)
              ->where($where_unit)
              ->count();
            $item[$k]->count = $chk;
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

    public function history():View{
       if(Auth::user()->level != 'root') {
            abort(403);
        }
      return view('admin.report.history');
    }

    public function historyYear(){
      if(request()->ajax()) {
        
        $currentYear = date('Y'); // ปีปัจจุบัน
        $years = [];
        $categories = [];
        $series = [];
        for ($i = 0; $i < 10; $i++) {
            $upd['id'] = $currentYear - $i;
            $upd['name'] = $currentYear - $i + 543;

            $date = Carbon::createFromFormat('Y-m-d', $upd['id'].'-01-01')->setTimezone('Asia/Bangkok');
            $date_from = $date->copy()->toDateString() . ' 00:00:00';
            $date_end = $date->copy()->endOfYear()->toDateString() . ' 23:59:59';

            $chk = VisitorLog::whereBetween('created_at', [$date_from, $date_end])->count();
            $upd['count'] = (float)$chk;
            array_push($years, $upd);

            array_unshift($categories, 'พ.ศ. '.$upd['name']);
            array_unshift($series, $upd['count']);
        }



         return response()->json([
            'status' => 200,
            'message' => 'succeed',
            'item' => $years,
            'categories' => $categories,
            'series' => $series,
        ], 200);
      }else{
        abort(404);
      }
    }

    public function historyMonth(Request $request){
      if(request()->ajax()) {
        
        $years = [];
        $categories = [];
        $series = [];
        $month_name = ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];

        for ($i = 0; $i < 12; $i++) {
            $upd['name'] = $month_name[$i].' '.$request->year;
            $ii = $i+1;
            if($ii < 10){
              $month = '0'.$ii;
            }else{
              $month = $ii;
            }

            $year = $request->year - 543;
            $date = Carbon::createFromFormat('Y-m-d', $year.'-'.$month.'-01')->setTimezone('Asia/Bangkok');
            $date_from = $date->copy()->toDateString() . ' 00:00:00';
            $date_end = $date->copy()->endOfMonth()->toDateString() . ' 23:59:59';

            $chk = VisitorLog::whereBetween('created_at', [$date_from, $date_end])->count();
            $upd['count'] = (float)$chk;
            array_push($years, $upd);

            array_push($categories, $upd['name']);
            array_push($series, $upd['count']);
        }

         return response()->json([
            'status' => 200,
            'message' => 'succeed',
            'item' => $years,
            'categories' => $categories,
            'series' => $series,
        ], 200);
      }else{
        abort(404);
      }
    }

    public function historyDay(Request $request){
      if(request()->ajax()) {
        
        $years = [];
        $categories = [];
        $series = [];

        for ($i = 0; $i < 30; $i++) {
          // วันปัจจุบัน - $i วัน
          $date = Carbon::now()->subDays($i)->setTimezone('Asia/Bangkok');

          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->toDateString() . ' 23:59:59';
          $chk = VisitorLog::whereBetween('created_at', [$date_from, $date_end])->count();
          $upd['count'] = (float)$chk;
          $upd['name'] = Helper::getDateThaiSubYear($date->toDateString());
          array_push($years, $upd);

          array_unshift($categories, $upd['name']);
          array_unshift($series, $upd['count']);
        }

         return response()->json([
            'status' => 200,
            'message' => 'succeed',
            'item' => $years,
            'categories' => $categories,
            'series' => $series,
        ], 200);
      }else{
        abort(404);
      }
    }

    public function questionnaire():View{
       if(Auth::user()->level != 'root') {
            abort(403);
        }
      return view('admin.report.questionnaire');
    }

    public function questionnaireLoad(Request $request){
      if(request()->ajax()) {
        
        if($request->type == 1){
          if(is_array($request->day) &&
            count($request->day) === 2 &&
            !empty($request->day[0]) &&
            !empty($request->day[1])){
            $dt = Carbon::parse($request->day[0])->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $dt_end = Carbon::parse($request->day[1])->setTimezone('Asia/Bangkok');
            $date_end = $dt_end->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date_end.' 23:59:59';

          }else{
            $dt = Carbon::parse($request->day)->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date.' 23:59:59';
          }
        }
        if($request->type == 2){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-'.$request->month.'-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfMonth()->toDateString() . ' 23:59:59';
        }

        if($request->type == 3){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-01-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfYear()->toDateString() . ' 23:59:59';
        }
        
        $gender = [0, 0, 0]; // 0 = ชาย, 1 = หญิง, 2 = อื่น ๆ
        $work = [0,0,0,0,0,0];
        $education = [0,0,0,0];
        $age = [0,0,0,0,0];

        $items = QuestionVote::whereBetween('created_at', [$date_from, $date_end])->get();

        foreach ($items as $v) {
            if ($v->gender == 1) {
                $gender[0]++;
            } elseif ($v->gender == 2) {
                $gender[1]++; 
            } elseif ($v->gender == 3) {
                $gender[2]++;
            }

            if ($v->qualification == 1) {
                $education[0]++; 
            } elseif ($v->qualification == 2) {
                $education[1]++; 
            } elseif ($v->qualification == 3) {
                $education[2]++;
            } elseif ($v->qualification == 4) {
                $education[3]++;
            }

            if ($v->work == 1) {
                $work[0]++; 
            } elseif ($v->work == 2) {
                $work[1]++; 
            } elseif ($v->work == 3) {
                $work[2]++;
            } elseif ($v->work == 4) {
                $work[3]++;
            } elseif ($v->work == 5) {
                $work[4]++;
            } elseif ($v->work == 6) {
                $work[5]++;
            }

            if ($v->age < 20) {
                $age[0]++;
            } elseif ($v->age >= 20 && $v->age <= 30) {
                $age[1]++;
            } elseif ($v->age >= 31 && $v->age <= 40) {
                $age[2]++;
            } elseif ($v->age >= 41 && $v->age <= 50) {
                $age[3]++;
            } else { // 51 ปีขึ้นไป
                $age[4]++;
            }
        }


        $question = Question::select('id','name')->where('type', 1)->orderBy('num','ASC')->get();
        foreach($question as $k => $v){

          $chk_1 = QuestionDetail::where('question_id', $v->id)
            ->where('score', 1)
            ->whereBetween('created_at', [$date_from, $date_end])
            ->count();
          $chk_2 = QuestionDetail::where('question_id', $v->id)
            ->where('score', 2)
            ->whereBetween('created_at', [$date_from, $date_end])
            ->count();
          $chk_3 = QuestionDetail::where('question_id', $v->id)
            ->where('score', 3)
            ->whereBetween('created_at', [$date_from, $date_end])
            ->count();
          // นับรวมทั้งหมด
          $total = $chk_1 + $chk_2 + $chk_3;

          // ป้องกันหารศูนย์
          if ($total > 0) {
              $percent_1 = ($chk_1 / $total) * 100;
              $percent_2 = ($chk_2 / $total) * 100;
              $percent_3 = ($chk_3 / $total) * 100;
          } else {
              $percent_1 = $percent_2 = $percent_3 = 0;
          }

          // ส่งเป็น array %
          $question[$k]->vote = [
              round($percent_1, 2),
              round($percent_2, 2),
              round($percent_3, 2),
          ];
        }

        return response()->json([
            'status' => 200,
            'message' => 'succeed',
            'gender' => $gender,
            'item' => $items,
            'work' => $work,
            'education' => $education,
            'age' => $age,
            'question' => $question,
        ], 200);
      }else{
        abort(404);
      }
    }

    public function summaryLoad(Request $request){
      if(request()->ajax()) {

        if($request->type == 1){
          if(is_array($request->day) &&
            count($request->day) === 2 &&
            !empty($request->day[0]) &&
            !empty($request->day[1])){
            $dt = Carbon::parse($request->day[0])->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $dt_end = Carbon::parse($request->day[1])->setTimezone('Asia/Bangkok');
            $date_end = $dt_end->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date_end.' 23:59:59';

          }else{
            $dt = Carbon::parse($request->day)->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date.' 23:59:59';
          }
        }
        if($request->type == 2){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-'.$request->month.'-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfMonth()->toDateString() . ' 23:59:59';
        }

        if($request->type == 3){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-01-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfYear()->toDateString() . ' 23:59:59';
        }
        
        if(Auth::user()->level == 'root'){
          $where_unit = [];
        }else{
          $where_unit = ['send_unit' => Auth::user()->unit_id];
        }
        
        $item = Complaints::select('id','code','created_at','type','method_id','person_id','type_id','sub_id','unit_id','send_unit')
          ->when($date_from && $date_end, function ($query) use ($date_from, $date_end) {
              return $query->whereBetween('created_at', [$date_from, $date_end]);
          })
          ->where($where_unit)
          ->where('status', 1)
          ->get();

        foreach($item as $k => $v){
          if($v->method_id){
            $method = ComplaintMethod::select('name')->where('id', $v->method_id)->first();
            $item[$k]->method_name = $method ? $method->name : null;
          }
          if($v->person_id){
            $person = ComplaintPerson::select('name')->where('id', $v->person_id)->first();
            $item[$k]->person_name = $person ? $person->name : null;
          }
          $item[$k]->type_name = '';
          if($v->type_id){
            $type = ComplaintType::select('name')->where('id', $v->type_id)->first();
            $item[$k]->type_name = $type ? $type->name : null;
          }
          $item[$k]->sub_name = '';
          if($v->sub_id){
            $sub = ComplaintSub::select('name')->where('id', $v->sub_id)->first();
            $item[$k]->sub_name = $sub ? $sub->name : null;
          }
          if($v->unit_id){
            $unit = Unit::select('name')->where('id', $v->unit_id)->first();
            $item[$k]->unit_name = $unit ? $unit->name : null;
          }
          $item[$k]->unit_send = '';
          if($v->send_unit){
            $send_unit = Unit::select('name')->where('id', $v->send_unit)->first();
            $item[$k]->unit_send = $send_unit ? $send_unit->name : null;
          }
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

    public function severity():View{
      return view('admin.report.severity');
    }

    public function severityLoad(Request $request){
      if(request()->ajax()) {

        if($request->type == 1){
          if(is_array($request->day) &&
            count($request->day) === 2 &&
            !empty($request->day[0]) &&
            !empty($request->day[1])){
            $dt = Carbon::parse($request->day[0])->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $dt_end = Carbon::parse($request->day[1])->setTimezone('Asia/Bangkok');
            $date_end = $dt_end->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date_end.' 23:59:59';

          }else{
            $dt = Carbon::parse($request->day)->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $date_from = $date.' 00:00:00';
            $date_end = $date.' 23:59:59';
          }
        }
        if($request->type == 2){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-'.$request->month.'-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfMonth()->toDateString() . ' 23:59:59';
        }

        if($request->type == 3){
          $year = $request->year - 543;
          $date = Carbon::createFromFormat('Y-m-d', $year.'-01-01')->setTimezone('Asia/Bangkok');
          $date_from = $date->copy()->toDateString() . ' 00:00:00';
          $date_end = $date->copy()->endOfYear()->toDateString() . ' 23:59:59';
        }
        
        if(Auth::user()->level == 'root'){
          $where_unit = [];
        }else{
          $where_unit = ['send_unit' => Auth::user()->unit_id];
        }
        

        $item = ComplaintSeverity::select('id','name','type','level')
          ->where('type', 1)
          ->orderBy('level','ASC')
          ->get();
        foreach($item as $k => $v){

          $item[$k]->sub = [];
          $chk = Complaints::where('status', 1)->where('severity_admin', $v->id)
            ->whereBetween('created_at', [$date_from, $date_end])
            ->where('status', 1)
            ->where($where_unit)
            ->count();
          $item[$k]->count = $chk;

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
}