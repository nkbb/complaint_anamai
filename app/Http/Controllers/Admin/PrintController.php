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
use App\Models\ComplaintLog;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use Carbon\Carbon;
use App\Helpers\Helper; 
use PDF;

class PrintController extends Controller
{

  public function index($code){


    $item = Complaints::where('code', $code)->first();
    if(!$item){
      abort(404);
    }

    if(Auth::user()->level == 'unit' && Auth::user()->unit_id != $item->send_unit){
        abort(403);
    }


    $item->fname = ($item->fname) ? Helper::decryptData($item->fname): '';
    $item->lname = ($item->lname) ? Helper::decryptData($item->lname): '';
    $item->work = ($item->work) ? Helper::decryptData($item->work): '';
    $item->address  = ($item->address) ? Helper::decryptData($item->address): '';
    $item->email = ($item->email) ? Helper::decryptData($item->email): '';
    $item->phone = ($item->phone) ? Helper::decryptData($item->phone): '';
    $item->tel = ($item->tel) ? Helper::decryptData($item->tel): '';
    $item->improvement = ($item->improvement) ? Helper::decryptData($item->improvement): '';
    $item->name = ($item->name) ? Helper::decryptData($item->name): '';
    $item->description = ($item->description) ? Helper::decryptData($item->description): '';
    $item->idcard = ($item->idcard) ? Helper::decryptData($item->idcard): '';

    $item->auth_fname = ($item->auth_fname) ? Helper::decryptData($item->auth_fname): '';
    $item->auth_lname = ($item->auth_lname) ? Helper::decryptData($item->auth_lname): '';
    $item->auth_phone = ($item->auth_phone) ? Helper::decryptData($item->auth_phone): '';
    $item->auth_email = ($item->auth_email) ? Helper::decryptData($item->auth_email): '';

    $item->trace_show = ($item->trace_show) ? Helper::decryptData($item->trace_show): '';

    $item->answer_detail = ($item->answer_detail) ? Helper::decryptData($item->answer_detail): '';

    $item->show_date = Helper::getDateThaiSubYear($item->created_at);
    $address = $item->address;
    $item->address = Helper::getAddress($item->province_id, $item->district_id, $item->subdistrict_id, $address, $item->zipcode); 
    $item->unit_name = '';
    if($item->unit_id){
        $unit = Unit::select('name')->where('id',$item->unit_id)->first();
        if($unit){
            $item->unit_name = $unit->name;
        }
    }

    $item->method_name = '';
    if($item->method_id){
        $methods = ComplaintMethod::select('name')->where('id',$item->method_id)->first();
        if($methods){
            $item->method_name = $methods->name;
        }
    }

    $item->type_name  = '';
    if($item->type_id){
        $type = ComplaintType::select('name')->where('id',$item->type_id)->first();
        if($type){
            $item->type_name = $type->name;
        }
    }
    $item->sub_name = '';
    if($item->sub_id){
        $sub = ComplaintSub::select('name')->where('id',$item->sub_id)->first();
        if($sub){
            $item->sub_name = $sub->name;
        }
    }

    $item->person_name = '';
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

                

    $pdf = PDF::loadView('pdf.report', ['data' => $item]);
    // $pdf = PDF::loadView('pdf.report', $data);
    $pdf->setPaper('A4', 'portrait');
    return $pdf->stream('report.pdf');
  }
}