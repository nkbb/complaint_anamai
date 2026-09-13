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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\PublicDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SettingController extends Controller
{

    private const ADMIN_CACHE_KEY = 'public_documents.admin';
    private const PUBLIC_CACHE_KEY = 'public_documents.public';

    public function index(): View
    {
        return view('admin.setting.index');
    }

    public function unit(): View
    {
        return view('admin.setting.unit');
    }

    public function unitLoad()
    {
        if (request()->ajax()) {

            $item = Unit::where('type', 1)->orderBy('name', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $item,
            ], 200);
        } else {
            abort(404);
        }
    }

    public function unitStore(Request $request)
    {
        if (request()->ajax()) {

            if ($request->data['id']) {
                Unit::where('id', $request->data['id'])
                    ->update([
                        'name' => $request->data['name'],
                        'short_name' => $request->data['shortName'],
                        'area' => $request->data['area'],
                        'type' => $request->data['type'],
                    ]);
            } else {
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
        } else {
            abort(404);
        }
    }

    public function unitRemove(Request $request)
    {
        if (request()->ajax()) {

            if ($request->id) {
                if ($request->type == 'unit') {
                    Unit::where('id', $request->id)
                        ->update([
                            'type' => 0,
                        ]);
                }
                if ($request->type == 'methods') {
                    ComplaintMethod::where('id', $request->id)
                        ->update([
                            'type' => 0,
                        ]);
                }
                if ($request->type == 'person') {
                    ComplaintPerson::where('id', $request->id)
                        ->update([
                            'type' => 0,
                        ]);
                }
                if ($request->type = 'question') {
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
        } else {
            abort(404);
        }
    }

    public function type(): View
    {
        return view('admin.setting.type');
    }

    public function typeLoad()
    {
        if (request()->ajax()) {

            $item = ComplaintType::where('type', '>', 0)->orderBy('num', 'ASC')->get();
            foreach ($item as $k => $v) {
                $sub = ComplaintSub::where('complaint_type_id', $v->id)->orderBy('num', 'ASC')->get();
                $item[$k]->sub = $sub;
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

    public function typeStore(Request $request)
    {
        if (request()->ajax()) {

            if ($request->data['id']) {
                if ($request->data['type'] == 'main') {
                    ComplaintType::where('id', $request->data['id'])
                        ->update([
                            'time_span' => $request->data['timeSpan'],
                        ]);
                }
                if ($request->data['type'] == 'sub') {
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
        } else {
            abort(404);
        }
    }

    public function timefinish(): View
    {
        $item = Company::select('id', 'dead_date_answer', 'dead_date_finish', 'dead_date_receive', 'dead_date_send')->where('id', 1)->first();
        return view('admin.setting.timefinish', compact('item'));
    }

    public function timefinishStore(Request $request)
    {
        if (request()->ajax()) {
            $item = Company::find(1);
            if (!empty($item)) {
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
        } else {
            abort(404);
        }
    }

    public function complaint(): View
    {
        $item = Company::select('id', 'key_title', 'conditions')->where('id', 1)->first();
        return view('admin.setting.complaint', compact('item'));
    }

    public function complaintStore(Request $request)
    {

        if (request()->ajax()) {
            $item = Company::find(1);
            if (!empty($item)) {
                $item->update([
                    'key_title' => $request->data['keyTitle'],
                    'conditions' => $request->conditions,
                ]);

                Cache::forget('company' . $item);
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        } else {
            abort(404);
        }
    }

    public function methods(): View
    {
        return view('admin.setting.methods');
    }

    public function methodsLoad()
    {
        if (request()->ajax()) {

            $item = ComplaintMethod::where('type', 1)->orderBy('num', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $item,
            ], 200);
        } else {
            abort(404);
        }
    }

    public function methodsStore(Request $request)
    {
        if (request()->ajax()) {

            if (isset($request->data['id']) && $request->data['id']) {

                ComplaintMethod::where('id', $request->data['id'])
                    ->update([
                        'name' => $request->data['name'],
                    ]);
            } else {
                if (!$request->id) {
                    $order = ComplaintMethod::select('num')->orderBy('num', 'DESC')->first();
                    $order_num = 1;
                    if (!empty($order)) {
                        $order_num = $order->num + 1;
                    }

                    ComplaintMethod::create([
                        'name' => $request->data['name'],
                        'num' => $order_num,
                    ]);
                } else {
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
        } else {
            abort(404);
        }
    }

    public function person(): View
    {
        return view('admin.setting.person');
    }

    public function personLoad()
    {
        if (request()->ajax()) {
            $item = ComplaintPerson::where('type', 1)->orderBy('num', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $item,
            ], 200);
        } else {
            abort(404);
        }
    }

    public function personStore(Request $request)
    {
        if (request()->ajax()) {

            if (isset($request->data['id']) && $request->data['id']) {

                ComplaintPerson::where('id', $request->data['id'])
                    ->update([
                        'name' => $request->data['name'],
                    ]);
            } else {
                if (!$request->id) {
                    $order = ComplaintPerson::select('num')->orderBy('num', 'DESC')->first();
                    $order_num = 1;
                    if (!empty($order)) {
                        $order_num = $order->num + 1;
                    }

                    ComplaintPerson::create([
                        'name' => $request->data['name'],
                        'num' => $order_num,
                    ]);
                } else {
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
        } else {
            abort(404);
        }
    }

    public function severity(): View
    {
        return view('admin.setting.severity');
    }

    public function severityLoad()
    {
        if (request()->ajax()) {
            $item = ComplaintSeverity::where('type', 1)->orderBy('level', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'message' => 'succeed',
                'item' => $item,
            ], 200);
        } else {
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

    public function questionLoad()
    {
        if (request()->ajax()) {
            $item = Question::where('type', 1)->orderBy('num', 'ASC')->get();

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

            if (isset($request->data['id']) && $request->data['id']) {

                Question::where('id', $request->data['id'])
                    ->update([
                        'name' => $request->data['name'],
                    ]);
            } else {
                if (!$request->id) {
                    $order = Question::select('num')->orderBy('num', 'DESC')->first();
                    $order_num = 1;
                    if (!empty($order)) {
                        $order_num = $order->num + 1;
                    }

                    Question::create([
                        'name' => $request->data['name'],
                        'num' => $order_num,
                    ]);
                } else {
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
        } else {
            abort(404);
        }
    }

    public function comment(): View
    {
        return view('admin.setting.comment');
    }

    public function commentLoad()
    {
        if (request()->ajax()) {

            $item = CommentType::where('type', '>', 0)->orderBy('num', 'ASC')->get();
            foreach ($item as $k => $v) {
                $sub = CommentSub::where('Comment_type_id', $v->id)->orderBy('num', 'ASC')->get();
                $item[$k]->sub = $sub;
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

    public function commentStore(Request $request)
    {
        if (request()->ajax()) {

            if ($request->data['id']) {
                if ($request->data['type'] == 'main') {
                    CommentType::where('id', $request->data['id'])
                        ->update([
                            'name' => $request->data['name'],
                        ]);
                }
                if ($request->data['type'] == 'sub') {
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
        } else {
            abort(404);
        }
    }


    public function telegram(): View
    {
        $unit = Unit::where('type', 1)->orderBy('name', 'ASC')->get();
        return view('admin.setting.telegram', compact('unit'));
    }

    public function telegramLoad(Request $request)
    {
        if (request()->ajax()) {

            $query = \App\Models\Telegram::query();

            if ($request->has('unit_id') && $request->unit_id) {
                $query->where('unit_id', $request->unit_id);
            }

            $item = $query->orderBy('id', 'ASC')->get();
            foreach ($item as $k => $v) {
                if ($v->unit_id == null) {
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
        } else {
            abort(404);
        }
    }

    public function telegramStore(Request $request)
    {
        if (request()->ajax()) {

            if ($request->data['id']) {
                Telegram::where('id', $request->data['id'])
                    ->update([
                        'token' => $request->data['token'],
                        'chat_id' => $request->data['chat_id'],
                        'type' => $request->data['type'],
                        'unit_id' => ($request->data['type'] == '2') ? $request->data['unit_id'] : null,
                    ]);
            } else {
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
        } else {
            abort(404);
        }
    }

    public function telegramRemove(Request $request)
    {
        if (request()->ajax()) {

            if ($request->id) {
                Telegram::where('id', $request->id)->delete();
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        } else {
            abort(404);
        }
    }

    public function users(): View
    {

        $unit = Unit::where('type', 1)->orderBy('name', 'ASC')->get();
        return view('admin.setting.users', compact('unit'));
    }


    public function usersLoad(Request $request)
    {
        if (request()->ajax()) {

            $query = \App\Models\User::query();
            if (Auth::user()->level == 'unit') {
                $query->where('unit_id', Auth::user()->unit_id);
            }

            if ($request->has('unit_id') && $request->unit_id) {
                $query->where('unit_id', $request->unit_id);
            }

            $item = $query->orderBy('name', 'ASC')->get();
            foreach ($item as $k => $v) {
                if ($v->unit_id == null) {
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
        } else {
            abort(404);
        }
    }

    public function usersStore(Request $request)
    {
        if (request()->ajax()) {

            if ($request->data['id']) {
                User::where('id', $request->data['id'])
                    ->update([
                        'name' => $request->data['name'],
                        'phone' => $request->data['phone'],
                        'email' => $request->data['email'],
                        'level' => $request->data['level'],
                        'unit_id' => ($request->data['level'] == 'unit') ? $request->data['unit_id'] : null,
                    ]);
            } else {
                $chk = User::where('username', $request->data['username'])->first();
                if ($chk) {
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
        } else {
            abort(404);
        }
    }

    public function usersRemove(Request $request)
    {
        if (request()->ajax()) {

            if ($request->id) {
                User::where('id', $request->id)->delete();
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        } else {
            abort(404);
        }
    }

    public function banner(): View
    {
        return view('admin.setting.banner');
    }

    public function bannerLoad(Request $request)
    {
        abort_unless($request->ajax(), 404);

        $type = (int) $request->input('type', 1);
        $cacheKey = 'banners.type.' . $type;

       $items = Cache::remember(
            $cacheKey,
            now()->addMinutes(15),
            function () use ($type) {
                return Banner::where('type', $type)
                    ->latest('id')
                    ->get()
                    ->map(function ($banner) {
                        $banner->image_desktop_url = $banner->image_desktop
                            ? '/storage/banner/' . $banner->image_desktop
                            : null;

                        $banner->image_mobile_url = $banner->image_mobile
                            ? '/storage/banner/' . $banner->image_mobile
                            : $banner->image_desktop_url;

                        return $banner;
                    });
            }
        );

        return response()->json([
            'status' => 200,
            'item' => $items,
        ]);
    }

    public function bannerStore(Request $request)
    {
        abort_unless($request->ajax(), 404);

        $request->validate([
            'id' => ['nullable', 'integer', 'exists:banners,id'],
            'type' => ['required', 'integer'],
            'uri' => ['nullable', 'string', 'max:2048'],

            'image_desktop' => [
                $request->filled('id') ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],

            'image_mobile' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],
        ], [
            'image_desktop.required' => 'กรุณาเลือกรูปสำหรับ Desktop',
            'image_desktop.image' => 'ไฟล์ Desktop ต้องเป็นรูปภาพ',
            'image_desktop.mimes' => 'รองรับรูป Desktop เฉพาะ JPG, PNG และ WebP',
            'image_desktop.max' => 'รูป Desktop ต้องมีขนาดไม่เกิน 10 MB',

            'image_mobile.image' => 'ไฟล์ Mobile ต้องเป็นรูปภาพ',
            'image_mobile.mimes' => 'รองรับรูป Mobile เฉพาะ JPG, PNG และ WebP',
            'image_mobile.max' => 'รูป Mobile ต้องมีขนาดไม่เกิน 10 MB',
        ]);

        $banner = $request->filled('id')
            ? Banner::findOrFail($request->id)
            : new Banner();

        $oldType = $banner->exists ? (int) $banner->type : null;
        $oldDesktop = $banner->image_desktop;
        $oldMobile = $banner->image_mobile;

        /*
         * ตอนแก้ไข หากไม่เลือกรูปใหม่
         * จะใช้ชื่อไฟล์เดิมจากฐานข้อมูล
         */
        $imageDesktop = $oldDesktop;
        $imageMobile = $oldMobile;

        if ($request->hasFile('image_desktop')) {
            $desktopFile = $request->file('image_desktop');

            $imageDesktop = now()->format('Ymd_His')
                . '_desktop_'
                . uniqid()
                . '.'
                . $desktopFile->extension();

            $desktopFile->storeAs(
                'banner',
                $imageDesktop,
                'public'
            );
        }

        if ($request->hasFile('image_mobile')) {
            $mobileFile = $request->file('image_mobile');

            $imageMobile = now()->format('Ymd_His')
                . '_mobile_'
                . uniqid()
                . '.'
                . $mobileFile->extension();

            $mobileFile->storeAs(
                'banner',
                $imageMobile,
                'public'
            );
        }

        /*
         * กรณีเพิ่มใหม่และไม่ได้ส่งรูป Mobile
         * ให้ใช้รูป Desktop แทน
         */
        if (!$imageMobile) {
            $imageMobile = $imageDesktop;
        }

        $banner->image_desktop = $imageDesktop;
        $banner->image_mobile = $imageMobile;
        $banner->uri = $request->input('uri');
        $banner->type = (int) $request->input('type');
        $banner->save();

        // ลบไฟล์ Desktop เก่าหลังบันทึกสำเร็จ
        if (
            $request->hasFile('image_desktop') &&
            $oldDesktop &&
            $oldDesktop !== $imageDesktop
        ) {
            $this->deleteBannerFileIfUnused(
                $oldDesktop,
                $banner->id
            );
        }

        // ลบไฟล์ Mobile เก่าหลังบันทึกสำเร็จ
        if (
            $request->hasFile('image_mobile') &&
            $oldMobile &&
            $oldMobile !== $imageMobile
        ) {
            $this->deleteBannerFileIfUnused(
                $oldMobile,
                $banner->id
            );
        }

        // ล้างแคชประเภทปัจจุบัน
        Cache::forget('banners.type.' . $banner->type);

        // หากแก้ type ให้ล้างแคช type เดิมด้วย
        if ($oldType !== null && $oldType !== (int) $banner->type) {
            Cache::forget('banners.type.' . $oldType);
        }

        return response()->json([
            'status' => 200,
            'message' => $request->filled('id')
                ? 'แก้ไขข้อมูลสำเร็จ'
                : 'เพิ่มข้อมูลสำเร็จ',
            'item' => $banner,
        ]);
    }

    public function bannerRemove(Request $request)
    {
        abort_unless($request->ajax(), 404);

        $request->validate([
            'id' => ['required', 'integer', 'exists:banners,id'],
        ]);

        $banner = Banner::findOrFail($request->id);

        $type = (int) $banner->type;
        $desktopImage = $banner->image_desktop;
        $mobileImage = $banner->image_mobile;

        $banner->delete();

        // ใช้ array_unique ป้องกันลบซ้ำ กรณี Mobile ใช้ไฟล์เดียวกับ Desktop
        $files = array_unique(array_filter([
            $desktopImage,
            $mobileImage,
        ]));

        foreach ($files as $fileName) {
            $this->deleteBannerFileIfUnused($fileName);
        }

        Cache::forget('banners.type.' . $type);

        return response()->json([
            'status' => 200,
            'message' => 'ลบข้อมูลสำเร็จ',
        ]);
    }

    private function deleteBannerFileIfUnused(
        string $fileName,
        ?int $exceptBannerId = null
    ): void {
        $isUsed = Banner::query()
            ->when($exceptBannerId, function ($query) use ($exceptBannerId) {
                $query->where('id', '!=', $exceptBannerId);
            })
            ->where(function ($query) use ($fileName) {
                $query
                    ->where('image_desktop', $fileName)
                    ->orWhere('image_mobile', $fileName);
            })
            ->exists();

        if (!$isUsed) {
            Storage::disk('public')->delete(
                'banner/' . $fileName
            );
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

    public function changePasswordStore(Request $request)
    {
        if (request()->ajax()) {

            $user = Auth::user();
            if (!Hash::check($request->data['old_password'], $user->password)) {

                return response()->json([
                    'status' => 201,
                    'message' => 'รหัสผ่านเดิมไม่ถูกต้อง',
                ], 200);
            }

            if ($user) {
                $user->update([
                    'password' => Hash::make($request->data['confirm_password']),
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

    public function document(): View
    {
        return view('admin.setting.download');
    }

    public function documentLoad(Request $request): JsonResponse
    {
        abort_unless($request->ajax(), 404);

        $items = Cache::remember(
            self::ADMIN_CACHE_KEY,
            now()->addMinutes(15),
            fn () => PublicDocument::query()
                ->orderBy('sort_order')
                ->latest('id')
                ->get()
                ->map(fn (PublicDocument $document) => $this->transform($document))
                ->values()
        );

        return response()->json([
            'status' => 200,
            'item' => $items,
        ]);
    }

    public function documentStore(Request $request): JsonResponse
    {
        abort_unless($request->ajax(), 404);

        $document = $request->filled('id')
            ? PublicDocument::findOrFail($request->integer('id'))
            : new PublicDocument();

        $validated = $request->validate([
            'id' => ['nullable', 'integer', 'exists:public_documents,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category' => [
                'required',
                Rule::in(['manual', 'form', 'document', 'other']),
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_new' => ['required', 'boolean'],
            'status' => ['required', 'boolean'],
            'file' => [
                $document->exists ? 'nullable' : 'required',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,zip',
                'max:20480',
            ],
        ], [
            'name.required' => 'กรุณาระบุชื่อเอกสาร',
            'category.required' => 'กรุณาเลือกประเภทเอกสาร',
            'category.in' => 'ประเภทเอกสารไม่ถูกต้อง',
            'file.required' => 'กรุณาเลือกไฟล์เอกสาร',
            'file.mimes' => 'รองรับเฉพาะ PDF, Word, Excel และ ZIP',
            'file.max' => 'ไฟล์ต้องมีขนาดไม่เกิน 20 MB',
        ]);

        $oldFilePath = $document->file_path;
        $newFilePath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $storedName = now()->format('Ymd_His')
                . '_'
                . uniqid()
                . '.'
                . $extension;

            $newFilePath = $file->storeAs(
                'documents',
                $storedName,
                'public'
            );

            $document->file_name = $file->getClientOriginalName();
            $document->file_path = $newFilePath;
            $document->file_extension = $extension;
            $document->file_size = $file->getSize();
        }

        try {
            $document->name = $validated['name'];
            $document->description = $validated['description'] ?? null;
            $document->category = $validated['category'];
            $document->sort_order = (int) ($validated['sort_order'] ?? 0);
            $document->is_new = $request->boolean('is_new');
            $document->status = $request->boolean('status');
            $document->save();
        } catch (\Throwable $exception) {
            if ($newFilePath) {
                Storage::disk('public')->delete($newFilePath);
            }

            throw $exception;
        }

        if ($newFilePath && $oldFilePath && $oldFilePath !== $newFilePath) {
            Storage::disk('public')->delete($oldFilePath);
        }

        $this->forgetCaches();

        return response()->json([
            'status' => 200,
            'message' => $request->filled('id')
                ? 'แก้ไขเอกสารสำเร็จ'
                : 'เพิ่มเอกสารสำเร็จ',
            'item' => $this->transform($document->fresh()),
        ]);
    }

    /** ลบข้อมูลและไฟล์ */
    public function documentDestroy(Request $request): JsonResponse
    {
        abort_unless($request->ajax(), 404);

        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:public_documents,id'],
        ]);

        $document = PublicDocument::findOrFail($validated['id']);
        $filePath = $document->file_path;

        $document->delete();

        if ($filePath) {
            Storage::disk('public')->delete($filePath);
        }

        $this->forgetCaches();

        return response()->json([
            'status' => 200,
            'message' => 'ลบเอกสารสำเร็จ',
        ]);
    }

    private function transform(PublicDocument $document): array
    {
        return [
            'id' => $document->id,
            'name' => $document->name,
            'description' => $document->description,
            'category' => $document->category,
            'file_name' => $document->file_name,
            'file_extension' => $document->file_extension,
            'file_size' => $document->file_size,
            'file_url' => route('documents.download', $document),
            'download_count' => $document->download_count,
            'sort_order' => $document->sort_order,
            'is_new' => (int) $document->is_new,
            'status' => (int) $document->status,
            'created_at' => $document->created_at?->toDateTimeString(),
            'updated_at' => $document->updated_at?->toDateTimeString(),
        ];
    }

    public function download(PublicDocument $document): BinaryFileResponse
    {
        abort_unless($document->status, 404);
        abort_unless(
            $document->file_path
            && Storage::disk('public')->exists($document->file_path),
            404
        );

        $document->increment('download_count');
        $this->forgetCaches();

        return response()->download(
            Storage::disk('public')->path($document->file_path),
            $document->file_name
        );
    }

    private function forgetCaches(): void
    {
        Cache::forget(self::ADMIN_CACHE_KEY);
        Cache::forget(self::PUBLIC_CACHE_KEY);
    }

}
