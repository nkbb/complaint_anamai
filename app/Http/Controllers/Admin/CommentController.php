<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\Complaints;
use App\Helpers\Helper; 
use Auth;
use DB;

class CommentController extends Controller
{
   public function index(): View
    {

        dd(Auth::user()->level);
        if(Auth::user()->level == 'root'){        
            $count_type2 = Complaints::where('status', 1)->whereIn('type', [2,3])->count();
            $count_type3 = Complaints::where('status', 1)->where('type', 4)->count();
            $count_type4 = Complaints::where('status', 1)->where('type', 5)->count();
            $count_type0 = Complaints::where('status', 1)->whereIn('type', [0,6])->count();

            return view('admin.comments.index', compact('count_type2','count_type3','count_type4','count_type0'));
        }
    }
}
?>