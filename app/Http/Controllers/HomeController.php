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
use App\Models\Company;
use App\Models\Banner;
use Illuminate\Support\Facades\Cache;
use App\Models\PublicDocument;


class HomeController extends Controller
{

  public function index(): View
  {

    $banners = Cache::remember(
      'home.banners.type.1',
      now()->addMinutes(15),
      function () {
        return Banner::where('type', 1)
          ->latest('id')
          ->get()
          ->map(function ($banner) {
            return [
              'id' => $banner->id,
              'uri' => $banner->uri,

              // ใช้ relative URL ป้องกัน localhost/port ไม่ตรง
              'image_desktop_url' => $banner->image_desktop
                ? '/storage/banner/' . $banner->image_desktop
                : null,

              'image_mobile_url' => $banner->image_mobile
                ? '/storage/banner/' . $banner->image_mobile
                : (
                  $banner->image_desktop
                  ? '/storage/banner/' . $banner->image_desktop
                  : null
                ),
            ];
          })
          ->values();
      }
    );

    $company = Cache::remember('company', now()->addMinutes(10), function () {
      return Company::find(1);
    });

    return view('home', compact('company', 'banners'));
  }

  public function documents(): View
  {
    $documents = Cache::remember(
        'public_documents.public',
        now()->addMinutes(15),
        function () {
            return PublicDocument::where('status', true)
                ->orderBy('sort_order')
                ->latest('id')
                ->get();
        }
    );

    return view('documents', compact('documents'));
  }
  public function evaluation(): View
  {

    $type = 1;
    $cacheKey = 'questions.type.' . $type;

    $questions = Cache::remember(
        $cacheKey,
        now()->addMinutes(15),
        function () use ($type) {
            return Question::select('id', 'name')
                ->where('type', $type)
                ->orderBy('num', 'ASC')
                ->get()
                ->map(function ($question) {
                    $question->sel = 3;
                    $question->checked_3 = true;

                    return $question;
                });
        }
    );

    return view('evaluation', compact('questions'));
  }


  public function complaint(): View
  {
    return view('complaint');
  }

  public function loadQuestion()
  {
    if (request()->ajax()) {
      $item = Question::select('id', 'name')->where('type', 1)->orderBy('num', 'ASC')->get();
      foreach ($item as $k => $v) {
        $item[$k]->sel = 3;
        $item[$k]->checked_3 = true;
      }

      return response()->json([
        'status' => 200,
        'message' => 'succeed',
        'item' => $item,
      ], 200);
    } else {
      abort(404);
    }
  }

  public function questionStore(Request $request)
  {
    if (request()->ajax()) {

      $vote = QuestionVote::create([
        'gender' => $request->gender,
        'work' => $request->work,
        'work_dis' => '',
        'qualification' => $request->qualification,
        'age' => $request->age,
        'ip' => $request->getClientIp(),
      ]);

      foreach ($request->questions as $v) {
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
    } else {
      abort(404);
    }
  }

  public function loadCommentType()
  {
    if (request()->ajax()) {

      $data = [];
      $item = CommentSub::select('id', 'name', 'comment_type_id')->where('type', 1)->orderBy('num', 'ASC')->get();
      foreach ($item as $k => $v) {
        $upd['id'] = 'sub_' . $v->comment_type_id . '_' . $v->id;
        $upd['name'] = $v->name;
        array_push($data, $upd);
      }

      $item = CommentType::select('id', 'name')->where('type', 1)->orderBy('num', 'ASC')->get();
      foreach ($item as $k => $v) {
        $upd['id'] = 'type_' . $v->id;
        $upd['name'] = $v->name;
        array_push($data, $upd);
      }

      return response()->json([
        'status' => 200,
        'message' => 'succeed',
        'item' => $data,
      ], 200);
    } else {
      abort(404);
    }
  }

  public function commentStore(Request $request)
  {
    if (request()->ajax()) {
      $type_id = null;
      $sub_id = null;
      $check = explode("_", $request->data['commentType']);

      $type_id = $check[1];
      if ($check[0] == 'sub') {
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

    } else {
      abort(404);
    }
  }

  public function manual($file): View
  {
    if ($file == 'appeal' || $file == 'trace' || $file == 'complaint') {
      return view('manual', compact('file'));
    }
    abort(404);
  }

  public function bannerGet(Request $request)
  {
    $items = Banner::where('type', 1)->orderBy('created_at', 'DESC')->get();
    foreach ($items as $item) {
      $item->image_url = asset('storage/banner/' . $item->image);
    }

    return response()->json([
      'status' => 200,
      'message' => 'succeed',
      'banners' => $items,
    ], 200);

  }

  

  public function cookiesPolicy(): view
  {
    return view('pdpa.cookies-policy');
  }

  public function privacyPolicy(): view
  {
    return view('pdpa.privacy-policy');
  }

  public function securityPolicy(): view
  {
    return view('pdpa.security-policy');
  }

  public function webPolicy(): view
  {
    return view('pdpa.web-policy');
  }


}
