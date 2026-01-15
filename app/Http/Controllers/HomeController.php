<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\QuestionVote;
use App\Models\QuestionDetail;
use App\Models\CommentType;
use App\Models\CommentSub;
use App\Models\Comments;
use App\Models\Banner;

class HomeController extends Controller
{

    public function index(): View
    {

        $banner = Banner::where('type',2)->first();
        if($banner){
          $banner->image_url = asset('storage/banner/' . $banner->image);
        }

        return view('home',compact('banner'));
    }

    public function complaint(): View{
      return view('complaint');
    }

    public function loadQuestion(){
      if(request()->ajax()) {
          $item = Question::select('id','name')->where('type', 1)->orderBy('num', 'ASC')->get();
          foreach($item as $k => $v){
            $item[$k]->sel = 3;
            $item[$k]->checked_3 = true;
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

    public function questionStore(Request $request){
      if(request()->ajax()) {
        
        $vote = QuestionVote::create([
          'gender' => $request->data['gender'],
          'work' => $request->data['work'],
          'work_dis' => ($request->data['work'] ==6)? $request->data['workDis'] : '' ,
          'qualification' => $request->data['qualification'],
          'age' => $request->data['age'],
          'ip' => $request->getClientIp(),
        ]);

        foreach($request->question as $v){
          QuestionDetail::create([
            'vote_id' => $vote->id,
            'question_id' => $v['id'],
            'score' => $v['sel'],
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

    public function loadCommentType(){
      if(request()->ajax()) {

        $data = [];
        $item = CommentSub::select('id','name','comment_type_id')->where('type', 1)->orderBy('num', 'ASC')->get();
        foreach($item as $k => $v){
          $upd['id'] = 'sub_'.$v->comment_type_id.'_'.$v->id;
          $upd['name'] = $v->name;
          array_push($data, $upd);
        }

        $item = CommentType::select('id','name')->where('type', 1)->orderBy('num', 'ASC')->get();
        foreach($item as $k => $v){
          $upd['id'] = 'type_'.$v->id;
          $upd['name'] = $v->name;
          array_push($data, $upd);
        }

        return response()->json([
            'status' => 200,
            'message' => 'succeed',
            'item' => $data,
        ], 200);
      }else{
          abort(404);
      }
    }

    public function commentStore(Request $request){
      if(request()->ajax()) {
        $type_id = null;
        $sub_id = null;
        $check = explode("_", $request->data['commentType']);

        $type_id = $check[1];
        if($check[0] == 'sub'){
          $sub_id = $check[2];
        }
        
        Comments::create([
          'type_id' => $type_id,
          'sub_id' => $sub_id,
          'name' => $request->data['name'],
          'comment' => $request->data['comment'],
          'ip' => $request->getClientIp(),
        ]);

        return response()->json([
          'status' => 200,
          'message' => 'succeed',
        ], 200);

      }else{
        abort(404);
      }
    }

    public function manual($file): View{
      if($file == 'appeal' || $file == 'trace' || $file == 'complaint'){
        return view('manual',compact('file'));
      }
      abort(404);
    }

    public function bannerGet(Request $request){
        $items = Banner::where('type',1)->orderBy('created_at', 'DESC')->get();
            foreach ($items as $item) {
                $item->image_url = asset('storage/banner/' . $item->image);
            }

        return response()->json([
            'status' => 200,
            'message' => 'succeed',
            'banners' => $items,
        ], 200);
      
    }

    public function cookiesPolicy():view{
      return view('pdpa.cookies-policy');
    }

    public function privacyPolicy():view{
      return view('pdpa.privacy-policy');
    }

    public function securityPolicy():view{
      return view('pdpa.security-policy');
    }

    public function webPolicy():view{
      return view('pdpa.web-policy');
    }

    
}
