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
use App\Models\ComplaintFile;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    public function index(): View
    {

        if (Auth::user()->level == 'root') {
            $count_type2 = Complaints::where('status', 1)->whereIn('type', [2])->count();
            $count_type3 = Complaints::where('status', 1)->whereIn('type', [3])->count();
            $count_type4 = Complaints::where('status', 1)->where('type', 5)->count();
            $count_type5 = Complaints::where('status', 1)->whereIn('type', [4, 5, 6])->count();
            $count_type0 = Complaints::where('status', 1)->whereIn('type', [0, 7])->count();

        } else {

            $count_type2 = 0;
            $count_type3 = Complaints::where('status', 1)->where('type', 3)
                ->where('unit_id', Auth::user()->unit_id)
                ->count();
            $count_type4 = Complaints::where('status', 1)->whereIn('type', [4, 6])
                ->where('unit_id', Auth::user()->unit_id)
                ->count();
            $count_type0 = 0;
            $count_type5 = Complaints::where('status', 1)->whereIn('type', [3, 4, 5, 6, 7, 8])
                ->where('unit_id', Auth::user()->unit_id)
                ->count();
        }

        return view('admin.home', compact('count_type2', 'count_type3', 'count_type4', 'count_type0', 'count_type5'));


    }

    public function accept(): View
    {
        if (Auth::user()->level != 'root') {
            abrabort(404);
        }
        $unit = Unit::select('id', 'name')->where('type', 1)->orderBy('name', 'ASC')->get();
        return view('admin.complaint.accept', compact('unit'));
    }

    public function follow(Request $request): View
    {
        if (Auth::user()->level != 'root') {
            abrabort(404);
        }
        $unit = Unit::select('id', 'name')->where('type', 1)->orderBy('name', 'ASC')->get();
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


    public function getComplaint(Request $request)
    {
        if (request()->ajax()) {

            $type = [];
            if ($request->type == 3) {
                $type = [3];
            } else if ($request->type == 5) {
                $type = [4, 5, 6];
            } else if ($request->type == 44) {
                $type = [4, 6];
            } else if ($request->type == 77) {
                $type = [7, 0];
            } else if ($request->type == 99) {
                $type = [4, 5, 6, 7, 8, 0];
            } else if ($request->type == 88) {
                $type = [3, 4, 5, 6, 7, 8];
            } else {
                $type = [$request->type];
            }



            $where_unit = [];
            if (Auth::user()->level != 'root') {
                $where_unit = ['unit_id' => Auth::user()->unit_id];
            }

            $date_from = '';
            $date_end = '';
            if ($request->s_date) {
                $dt = Carbon::parse($request->s_date)->setTimezone('Asia/Bangkok');
                $date = $dt->format('Y-m-d');
                $date_from = $date . ' 00:00:00';
                $date_end = $date . ' 23:59:59';
            }

            $item_total = Complaints::where('status', 1)
                ->whereIn('type', $type)
                ->where($where_unit)
                ->where(function ($query) use ($request) {
                    if (!empty($request->s_code)) {
                        $query->where('code', 'like', '%' . $request->s_code . '%');
                    }
                })
                ->when(!empty($request->s_date), function ($query) use ($date_from, $date_end) {
                    $query->whereBetween('created_at', [$date_from, $date_end]);
                })
                ->where('status', 1)
                ->count();
            $pagination['total'] = $item_total;

            $request->page = (empty($request->page)) ? 1 : $request->page;
            $request->limit = (empty($request->limit)) ? 10 : $request->limit;

            $offset = ($request->page - 1) * $request->limit;
            $pagination['from'] = $offset + 1;
            $pagination['to'] = ($offset + 1) * $request->limit;
            if ($pagination['to'] > $item_total) {
                $pagination['to'] = $item_total;
            }

            $item = Complaints::select('id', 'code', 'unit_id', 'type_id', 'sub_id', 'name', 'method_id', 'created_at', 'type', 'status', 'user_id', 'is_add')
                ->whereIn('type', $type)
                ->where('status', 1)
                ->where($where_unit)
                ->where(function ($query) use ($request) {
                    if (!empty($request->s_code)) {
                        $query->where('code', 'like', '%' . $request->s_code . '%');
                    }
                })
                ->when(!empty($request->s_date), function ($query) use ($date_from, $date_end) {
                    $query->whereBetween('created_at', [$date_from, $date_end]);
                })
                ->offset($offset)
                ->limit($request->limit)
                ->orderBy('code', 'DESC')
                ->get();
            foreach ($item as $k => $v) {
                $item[$k]->name = Helper::decryptData($v->name);
                $item[$k]->unit = '';
                $item[$k]->user_add = 'ผู้ร้องเรียน';

                if ($v->unit_id) {
                    $unit = Unit::select('name')->where('id', $v->unit_id)->first();
                    if (!empty($unit)) {
                        $item[$k]->unit = $unit->name;
                    }
                }
                $item[$k]->methods = '';
                if ($v->method_id) {
                    $methods = ComplaintMethod::select('name')->where('id', $v->method_id)->first();
                    if (!empty($methods)) {
                        $item[$k]->methods = $methods->name;
                    }
                }
                $item[$k]->type_name = '';
                if ($v->type_id) {
                    $type = ComplaintType::select('name')->where('id', $v->type_id)->first();
                    if (!empty($type)) {
                        $item[$k]->type_name = $type->name;
                    }
                }
                $item[$k]->sub_name = '';
                if ($v->sub_id) {
                    $sub = ComplaintSub::select('name')->where('id', $v->sub_id)->first();
                    if (!empty($sub)) {
                        $item[$k]->sub_name = $sub->name;
                    }
                }


                $process = ComplaintLog::where('complaint_id', $v->id)->count();
                $item[$k]->process = $process;


                if ($v->user_id) {
                    $user = User::select('name', 'level', 'unit_id')->where('id', $v->user_id)->first();
                    if ($user) {
                        $item[$k]->user_add = $user->name;
                        $item[$k]->user_add .= ($user->level == 'root') ? ' (เจ้าหน้าที่ศูนย์รับเรื่องร้องเรียน)' : ' (หน่วยงาน)';

                    }
                }

            }

            return response()->json([
                'status' => 200,
                'item' => $item,
                'pagination' => $pagination
            ], 200);

        } else {
            abort(404);
        }
    }

    public function create(): View
    {
        return view('admin.complaint.create');
    }

    public function getMasterData()
    {
        if (request()->ajax()) {
            $unit = Unit::select('id', 'name')->where('type', 1)->orderBy('name', 'ASC')->get();
            $type = ComplaintType::select('id', 'name', 'num')->where('type', '>=', 1)->orderBy('num', 'ASC')->get();
            $sub = ComplaintSub::select('id', 'name', 'complaint_type_id')->where('type', 1)->orderBy('num', 'ASC')->get();
            $person = ComplaintPerson::select('id', 'name')->where('type', 1)->orderBy('num', 'ASC')->get();
            $province = Province::select('id', 'name')->orderBy('name', 'ASC')->get();
            $methods = ComplaintMethod::select('id', 'name')->where('type', 1)->orderBy('num', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'unit' => $unit,
                'type' => $type,
                'sub' => $sub,
                'person' => $person,
                'province' => $province,
                'methods' => $methods,
            ], 200);
        } else {
            abort(404);
        }
    }
    public function complaintStore(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | ตรวจสอบข้อมูลทั่วไปและไฟล์ใหม่
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [
            'id' => [
                'nullable',
                'integer',
                'exists:complaints,id',
            ],

            'method_id' => [
                'required',
                'integer',
            ],

            'type_id' => [
                'required',
                'integer',
            ],

            'name' => [
                'required',
                'string',
            ],

            'description' => [
                'required',
                'string',
            ],

            'improvement' => [
                'required',
                'string',
            ],

            'attachments' => [
                'nullable',
                'array',
                'max:12',
            ],

            'attachments.*' => [
                'bail',
                'file',
                'max:10240', // 10 MB
                'mimes:jpg,jpeg,png,webp,pdf,mp4,mov',
            ],

            'deleted_file_ids' => [
                'nullable',
                'array',
            ],

            'deleted_file_ids.*' => [
                'integer',
                'exists:complaint_files,id',
            ],
        ], [
            'method_id.required' => 'กรุณาเลือกช่องทางการร้องเรียน',
            'type_id.required' => 'กรุณาเลือกประเด็นร้องเรียน',
            'name.required' => 'กรุณากรอกเรื่องที่ร้องเรียน',
            'description.required' => 'กรุณากรอกรายละเอียดเรื่องร้องเรียน',
            'improvement.required' => 'กรุณากรอกสิ่งที่ต้องการให้แก้ไข',

            'attachments.array' => 'รูปแบบไฟล์แนบไม่ถูกต้อง',
            'attachments.max' => 'แนบไฟล์รวมได้ไม่เกิน 12 ไฟล์',
            'attachments.*.uploaded' => 'อัปโหลดไฟล์ไม่สำเร็จ กรุณาตรวจสอบขนาดไฟล์',
            'attachments.*.file' => 'ไฟล์แนบบางรายการไม่ถูกต้อง',
            'attachments.*.max' => 'แต่ละไฟล์ต้องมีขนาดไม่เกิน 10 MB',
            'attachments.*.mimes' => 'รองรับเฉพาะ JPG, JPEG, PNG, WEBP, PDF, MP4 และ MOV',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ตรวจจำนวนไฟล์เดิมที่เหลือ + ไฟล์ใหม่
        |--------------------------------------------------------------------------
        */

        $validator->after(function ($validator) use ($request) {
            $fileTypes = [];

            if ($request->filled('id')) {
                $existingFiles = ComplaintFile::query()
                    ->where('complaint_id', $request->id)
                    ->whereNotIn(
                        'id',
                        $request->input('deleted_file_ids', [])
                    )
                    ->get();

                $fileTypes = $existingFiles
                    ->pluck('file_type')
                    ->all();
            }

            foreach ($request->file('attachments', []) as $index => $file) {
                /*
                 * ป้องกัน error:
                 * The "" file does not exist or is not readable.
                 */
                if (!$file || !$file->isValid()) {
                    $validator->errors()->add(
                        "attachments.$index",
                        $file
                        ? $file->getErrorMessage()
                        : 'ไฟล์ไม่สามารถอ่านได้'
                    );

                    continue;
                }

                $fileType = $this->getComplaintFileType(
                    $file->getMimeType()
                );

                if (!$fileType) {
                    $validator->errors()->add(
                        "attachments.$index",
                        "ไม่รองรับไฟล์ {$file->getClientOriginalName()}"
                    );

                    continue;
                }

                $fileTypes[] = $fileType;
            }

            $imageCount = count(array_filter(
                $fileTypes,
                fn($type) => $type === 'image'
            ));

            $pdfCount = count(array_filter(
                $fileTypes,
                fn($type) => $type === 'pdf'
            ));

            $videoCount = count(array_filter(
                $fileTypes,
                fn($type) => $type === 'video'
            ));

            if ($imageCount > 10) {
                $validator->errors()->add(
                    'attachments',
                    'รูปภาพต้องไม่เกิน 10 รูป'
                );
            }

            if ($pdfCount > 1) {
                $validator->errors()->add(
                    'attachments',
                    'ไฟล์ PDF ต้องไม่เกิน 1 ไฟล์'
                );
            }

            if ($videoCount > 1) {
                $validator->errors()->add(
                    'attachments',
                    'ไฟล์วิดีโอต้องไม่เกิน 1 ไฟล์'
                );
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | ตรวจสอบเลขบัตรประชาชน
        |--------------------------------------------------------------------------
        */

        $idcardClean = null;

        if ($request->filled('idcard')) {
            $idcardClean = preg_replace(
                '/\D/',
                '',
                $request->idcard
            );

            $invalidIdcards = Helper::checkIdcare();

            if (in_array($idcardClean, $invalidIdcards)) {
                return response()->json([
                    'status' => 422,
                    'message' => 'เลขบัตรประชาชนไม่ถูกต้อง',
                ], 422);
            }
        }

        // ใช้ลบไฟล์ใหม่ หาก Transaction ไม่สำเร็จ
        $newFilePaths = [];

        // ลบไฟล์เก่าหลัง Commit สำเร็จเท่านั้น
        $oldFilePaths = [];

        try {
            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | ค้นหารายการเดิม หรือสร้าง Model ใหม่
            |--------------------------------------------------------------------------
            */

            if ($request->filled('id')) {
                $complaint = Complaints::query()
                    ->lockForUpdate()
                    ->findOrFail($request->id);
            } else {
                $complaint = new Complaints();

                $company = Company::findOrFail(1);

                /*
                |--------------------------------------------------------------------------
                | สร้างรหัสร้องเรียน เช่น DOH6900001
                |--------------------------------------------------------------------------
                */

                $buddhistYear =
                    (int) now()->format('Y') + 543;

                $yearTwoDigits = substr(
                    (string) $buddhistYear,
                    -2
                );

                $keyTitle =
                    $request->key_title ?: $company->key_title;

                $prefix = $keyTitle . $yearTwoDigits;

                $lastNumber = DB::table('complaints')
                    ->where('code', 'like', $prefix . '%')
                    ->lockForUpdate()
                    ->selectRaw(
                        'MAX(CAST(RIGHT(code, 5) AS UNSIGNED)) AS last_number'
                    )
                    ->value('last_number');

                $nextNumber = ((int) $lastNumber) + 1;

                $complaint->code =
                    $prefix . sprintf('%05d', $nextNumber);

                /*
                 * ระบุผู้เพิ่มข้อมูล
                 */
                if (Auth::user()->level === 'root') {
                    $complaint->is_add = 3;
                } elseif (Auth::user()->level === 'unit') {
                    $complaint->is_add = 2;
                } else {
                    $complaint->is_add = 1;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | ฟังก์ชันแปลง null, undefined และค่าว่างให้เป็น null
            |--------------------------------------------------------------------------
            */

            $nullableValue = function ($value) {
                if (
                    $value === null ||
                    $value === '' ||
                    $value === 'null' ||
                    $value === 'undefined'
                ) {
                    return null;
                }

                return $value;
            };

            /*
            |--------------------------------------------------------------------------
            | บันทึก Complaint
            |--------------------------------------------------------------------------
            */

            $complaint->concealed =
                $request->boolean('concealed') ? 1 : 0;

            $complaint->idcard = $idcardClean
                ? Helper::encryptData($idcardClean)
                : null;

            $cleanPhone = $request->filled('phone')
                ? preg_replace('/\D/', '', $request->phone)
                : null;

            $complaint->idcard_sub = $cleanPhone
                ? substr($cleanPhone, -4)
                : null;

            $complaint->fname = $request->filled('fname')
                ? Helper::encryptData($request->fname)
                : null;

            $complaint->lname = $request->filled('lname')
                ? Helper::encryptData($request->lname)
                : null;

            $complaint->address = $request->filled('address')
                ? Helper::encryptData($request->address)
                : null;

            $complaint->work = $request->filled('work')
                ? Helper::encryptData($request->work)
                : null;

            $complaint->tel = $request->filled('tel')
                ? Helper::encryptData($request->tel)
                : null;

            $complaint->email = $request->filled('email')
                ? Helper::encryptData($request->email)
                : null;

            $complaint->phone = $request->filled('phone')
                ? Helper::encryptData($request->phone)
                : null;

            $complaint->province_id =
                $nullableValue($request->province_id);

            $complaint->district_id =
                $nullableValue($request->district_id);

            $complaint->subdistrict_id =
                $nullableValue($request->subdistrict_id);

            $complaint->zipcode =
                $nullableValue($request->zipcode);

            $complaint->unit_id =
                $nullableValue($request->unit_id);

            $complaint->type_id =
                $nullableValue($request->type_id);

            $complaint->sub_id =
                $nullableValue($request->sub_id);

            $complaint->person_id =
                $nullableValue($request->person_id);

            $complaint->method_id =
                $nullableValue($request->method_id);

            $complaint->gender =
                $nullableValue($request->gender);

            $complaint->name =
                Helper::encryptData($request->name);

            $complaint->description =
                Helper::encryptData($request->description);

            $complaint->improvement =
                Helper::encryptData($request->improvement);

            $complaint->type = in_array(
                Auth::user()->level,
                ['root', 'admin'],
                true
            ) ? 2 : 3;

            $complaint->ip = $request->ip();
            $complaint->user_id = Auth::id();

            if (Auth::user()->level === 'unit') {
                $complaint->send_unit =
                    Auth::user()->unit_id;
            }

            $complaint->save();

            /*
            |--------------------------------------------------------------------------
            | ลบรายการไฟล์เดิมที่ Admin เลือกลบ
            |--------------------------------------------------------------------------
            */

            $deletedFileIds = $request->input(
                'deleted_file_ids',
                []
            );

            if (!empty($deletedFileIds)) {
                /*
                 * where complaint_id ป้องกันการลบไฟล์ของ Complaint อื่น
                 */
                $deletedFiles = ComplaintFile::query()
                    ->where('complaint_id', $complaint->id)
                    ->whereIn('id', $deletedFileIds)
                    ->lockForUpdate()
                    ->get();

                foreach ($deletedFiles as $deletedFile) {
                    $oldFilePaths[] =
                        $deletedFile->file_path;

                    $deletedFile->delete();
                }
            }

            /*
            |--------------------------------------------------------------------------
            | หา sort_order ล่าสุด
            |--------------------------------------------------------------------------
            */

            $sortOrder = (int) ComplaintFile::query()
                ->where('complaint_id', $complaint->id)
                ->max('sort_order');

            /*
            |--------------------------------------------------------------------------
            | บันทึกไฟล์ใหม่
            |--------------------------------------------------------------------------
            */

            foreach ($request->file('attachments', []) as $file) {
                if (!$file || !$file->isValid()) {
                    throw new RuntimeException(
                        'ไฟล์บางรายการอัปโหลดไม่สำเร็จ'
                    );
                }

                $mimeType = $file->getMimeType();

                $fileType =
                    $this->getComplaintFileType($mimeType);

                if (!$fileType) {
                    throw new RuntimeException(
                        "ไม่รองรับไฟล์ {$file->getClientOriginalName()}"
                    );
                }

                $extension = strtolower(
                    $file->getClientOriginalExtension()
                );

                $fileName =
                    now()->format('Ymd_His')
                    . '_'
                    . Str::uuid()
                    . '.'
                    . $extension;

                /*
                 * storage/app/public/complaints/{id}
                 */
                $filePath = $file->storeAs(
                    'complaints/' . $complaint->id,
                    $fileName,
                    'public'
                );

                if (!$filePath) {
                    throw new RuntimeException(
                        "ไม่สามารถบันทึกไฟล์ {$file->getClientOriginalName()}"
                    );
                }

                $newFilePaths[] = $filePath;

                ComplaintFile::create([
                    'complaint_id' => $complaint->id,
                    'file_type' => $fileType,
                    'original_name' =>
                        $file->getClientOriginalName(),
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'mime_type' => $mimeType,
                    'extension' => $extension,
                    'file_size' => $file->getSize(),
                    'sort_order' => ++$sortOrder,
                ]);
            }

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | ลบไฟล์จริงหลังฐานข้อมูล Commit สำเร็จ
            |--------------------------------------------------------------------------
            */

            foreach ($oldFilePaths as $oldPath) {
                if (
                    $oldPath &&
                    Storage::disk('public')->exists($oldPath)
                ) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $complaint->load('files');

            return response()->json([
                'status' => 200,
                'message' => 'บันทึกข้อมูลเรียบร้อยแล้ว',
                'code' => $complaint->code,
                'complaint_id' => $complaint->id,
                'files' => $complaint->files,
            ], 200);
        } catch (\Throwable $exception) {
            DB::rollBack();

            /*
             * ลบเฉพาะไฟล์ใหม่ที่บันทึกไปแล้ว
             */
            foreach ($newFilePaths as $newPath) {
                if (
                    $newPath &&
                    Storage::disk('public')->exists($newPath)
                ) {
                    Storage::disk('public')->delete($newPath);
                }
            }

            report($exception);

            return response()->json([
                'status' => 500,
                'message' => 'ไม่สามารถบันทึกข้อมูลได้',
            ], 500);
        }
    }

    public function remove(Request $request)
    {
        if (request()->ajax()) {

            $item = Complaints::find($request->id);
            if ($item) {
                $item->update(['status' => 0]);
            }
            return response()->json([
                'status' => 200,
                'message' => 'succeed',
            ], 200);
        } else {
            abort(404);
        }
    }

    public function getComplaintById(Request $request, $id)
    {
        if (request()->ajax()) {

            $item = Complaints::with([
                'files' => function ($query) {
                    $query->select([
                        'id',
                        'complaint_id',
                        'file_type',
                        'original_name',
                        'file_name',
                        'file_path',
                        'mime_type',
                        'extension',
                        'file_size',
                        'sort_order',
                    ])->orderBy('sort_order');
                },
            ])->find($id);
            if ($item) {

                if (Auth::user()->level == 'unit' && $item->concealed == 1 && $item->is_add != 2) {
                    $item->fname = '';
                    $item->lname = '';
                    $item->work = '';
                    $item->address = '';
                    $item->email = '';
                    $item->phone = '';
                    $item->tel = '';
                    $item->idcard = '';
                    $item->zipcode = '';

                } else {
                    //
                    $item->fname = ($item->fname) ? Helper::decryptData($item->fname) : '';
                    $item->lname = ($item->lname) ? Helper::decryptData($item->lname) : '';
                    $item->work = ($item->work) ? Helper::decryptData($item->work) : '';
                    $item->address = ($item->address) ? Helper::decryptData($item->address) : '';
                    $item->email = ($item->email) ? Helper::decryptData($item->email) : '';
                    $item->phone = ($item->phone) ? Helper::decryptData($item->phone) : '';
                    $item->tel = ($item->tel) ? Helper::decryptData($item->tel) : '';
                    $item->idcard = ($item->idcard) ? Helper::decryptData($item->idcard) : '';
                }



                $item->name = ($item->name) ? Helper::decryptData($item->name) : '';

                $item->auth_fname = ($item->auth_fname) ? Helper::decryptData($item->auth_fname) : '';
                $item->auth_lname = ($item->auth_lname) ? Helper::decryptData($item->auth_lname) : '';
                $item->auth_phone = ($item->auth_phone) ? Helper::decryptData($item->auth_phone) : '';
                $item->auth_email = ($item->auth_email) ? Helper::decryptData($item->auth_email) : '';

                if ($request->type == 'show') {
                    $item->improvement = ($item->improvement) ? Helper::convoretHtml(Helper::decryptData($item->improvement)) : '';
                    $item->description = ($item->description) ? Helper::convoretHtml(Helper::decryptData($item->description)) : '';
                    $item->trace_show = ($item->trace_show) ? Helper::convoretHtml(Helper::decryptData($item->trace_show)) : '';


                    if (Auth::user()->level == 'unit' && ($item->type == 4 || $item->type == 5 || $item->type == 6)) {
                        $item->answer_detail = ($item->answer_detail) ? Helper::decryptData($item->answer_detail) : '';
                    } else {
                        $item->answer_detail = ($item->answer_detail) ? Helper::convoretHtml(Helper::decryptData($item->answer_detail)) : '';
                    }


                    if (Auth::user()->level == 'unit' && $item->concealed == 1 && $item->is_add != 2) {
                        $item->address = '';
                    } else {
                        $address = $item->address;
                        $item->address = Helper::getAddress($item->province_id, $item->district_id, $item->subdistrict_id, $address, $item->zipcode);
                    }

                    if ($item->unit_id) {
                        $unit = Unit::select('name')->where('id', $item->unit_id)->first();
                        if ($unit) {
                            $item->unit_name = $unit->name;
                        }
                    }

                    if ($item->method_id) {
                        $methods = ComplaintMethod::select('name')->where('id', $item->method_id)->first();
                        if ($methods) {
                            $item->method_name = $methods->name;
                        }
                    }

                    if ($item->type_id) {
                        $type = ComplaintType::select('name')->where('id', $item->type_id)->first();
                        if ($type) {
                            $item->type_name = $type->name;
                        }
                    }
                    if ($item->sub_id) {
                        $sub = ComplaintSub::select('name')->where('id', $item->sub_id)->first();
                        if ($sub) {
                            $item->sub_name = $sub->name;
                        }
                    }

                    if ($item->person_id) {
                        $person = ComplaintPerson::select('name')->where('id', $item->person_id)->first();
                        if ($person) {
                            $item->person_name = $person->name;
                        }
                    }

                    if ($item->severity_admin) {
                        $severity = ComplaintSeverity::select('name', 'time')->where('id', $item->severity_admin)->first();
                        if ($severity) {
                            $item->severity_name = $severity->name;
                            $item->severity_time = $severity->time;
                        }
                    }

                    $item->user_add = 'ผู้ร้องเรียน';
                    if ($item->user_id) {
                        $user = User::select('name', 'level', 'unit_id')->where('id', $item->user_id)->first();
                        if ($user) {
                            $item->user_add = $user->name . ' (' . $user->level . ')';
                            $item->user_add .= ($user->level == 'root') ? ' (เจ้าหน้าที่ศูนย์รับเรื่องร้องเรียน)' : ' (หน่วยงาน)';
                        }
                    }

                    if ($item->file) {
                        $item->file_url = asset('storage/files/' . $item->file);
                    }

                    if ($item->send_files) {
                        $item->send_files_url = asset('storage/files/' . $item->send_files);
                    }

                    if ($item->answer_file) {
                        $item->answer_file_url = asset('storage/files/' . $item->answer_file);
                    }

                    $item->log = ComplaintLog::where('complaint_id', $item->id)
                        ->orderBy('date_time', 'DESC')
                        ->get();
                    foreach ($item->log as $k => $v) {
                        if ($v->type == 6 && $v->comment_id) {
                            $comment = ComplaintComment::find($v->comment_id);
                            if ($comment) {
                                $item->log[$k]->comment_ask = $comment->ask_unit;
                                $item->log[$k]->comment_com = $comment->comment_unit;
                                $item->log[$k]->comment_date = $comment->date_com;
                                $item->log[$k]->user_com = $comment->user_com;
                                $item->log[$k]->user_ask = $comment->user_ask;
                                $item->log[$k]->comment_type = $comment->type;
                            }
                        }
                    }

                } else {
                    $item->improvement = ($item->improvement) ? Helper::decryptData($item->improvement) : '';
                    $item->description = ($item->description) ? Helper::decryptData($item->description) : '';
                    $item->trace_show = ($item->trace_show) ? Helper::decryptData($item->trace_show) : '';

                    $item->answer_detail = ($item->answer_detail) ? Helper::decryptData($item->answer_detail) : '';
                }

               $item->files->transform(function ($file) {
    $file->file_url = route(
        'complaint.file.show',
        ['complaintFile' => $file->id],
        false
    );

    if ($file->file_size >= 1024 * 1024) {
        $file->file_size_text =
            number_format(
                $file->file_size / (1024 * 1024),
                2
            ) . ' MB';
    } else {
        $file->file_size_text =
            number_format(
                $file->file_size / 1024,
                2
            ) . ' KB';
    }

    return $file;
});

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                    'item' => $item,
                ], 200);
            } else {
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        } else {
            abort(404);
        }
    }


    private function getComplaintFileType(
        string $mimeType
    ): ?string {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }

        if ($mimeType === 'application/pdf') {
            return 'pdf';
        }

        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        return null;
    }

    public function cancel(Request $request, $id)
    {
        if (request()->ajax()) {

            $item = Complaints::find($id);
            if ($item) {
                $item->type = 0;
                $item->del_user = Auth::user()->id;
                $item->del_date = now();
                $item->del_comm = $request->comm_cancel;
                $item->save();

                ComplaintLog::create([
                    'complaint_id' => $item->id,
                    'type' => 1,
                    'date_time' => $item->del_date,
                    'user_id' => Auth::user()->name,
                ]);

                $chk_user = Telegram::where("type", 1)
                    ->get();

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            } else {
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        } else {
            abort(404);
        }
    }

    public function send(Request $request, $id)
    {
        if (request()->ajax()) {

            $item = Complaints::find($id);
            if ($item) {

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
                if ($filename) {
                    if ($item->send_files) {
                        $item->send_files = $item->send_files . ',' . $filename;
                    } else {
                        $item->send_files = $filename;
                    }
                }
                $item->save();

                ComplaintLog::create([
                    'complaint_id' => $item->id,
                    'type' => 2,
                    'date_time' => $item->send_date,
                    'user_id' => Auth::user()->name,
                ]);


                $chk_user = Telegram::where("type", 2)
                    ->where("unit_id", $request->send_unit)
                    ->get();
                foreach ($chk_user as $row) {
                    $token = $row->token;
                    $chatId = $row->chat_id;
                    $message = "ศูนย์ได้ส่งเรื่องร้องเรียน ให้หน่วยงานของท่าน \n" .
                        "รหัสเรื่องร้องเรียน: " . $item->code . "\n";

                    Helper::sendTelegramMessage($token, $chatId, $message);
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            } else {
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        } else {
            abort(404);
        }
    }

    public function receiveUpdate(Request $request, $id)
    {
        if (request()->ajax()) {

            $item = Complaints::find($id);
            if ($item) {
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
                    'date_time' => $item->receive_date,
                    'user_id' => Auth::user()->name,
                ]);

                $chk_user = Telegram::where("type", 1)
                    ->get();
                foreach ($chk_user as $row) {
                    $token = $row->token;
                    $chatId = $row->chat_id;
                    $message = "หน่วยงานรับเรื่องร้องเรียน \n" .
                        "รหัสเรื่องร้องเรียน: " . $item->code . "\n";

                    Helper::sendTelegramMessage($token, $chatId, $message);
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            } else {
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        } else {
            abort(404);
        }
    }

    public function addComment(Request $request, $id)
    {
        if (request()->ajax()) {

            $item = Complaints::find($id);
            if ($item) {

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
                    'date_time' => now(),
                    'user_id' => Auth::user()->name,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            } else {
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        } else {
            abort(404);
        }
    }

    public function addReply(Request $request, $id)
    {
        if (request()->ajax()) {

            $comm = ComplaintComment::find($request->id);
            if ($comm) {

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
            } else {
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        } else {
            abort(404);
        }
    }

    public function deleteComment(Request $request)
    {
        if (request()->ajax()) {
            $log = ComplaintLog::find($request->id);
            if ($log) {

                $comm = ComplaintComment::where("id", $log->comment_id)->first();
                if ($comm && $comm->type == 1) {
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


        } else {
            abort(404);
        }
    }

    public function saveTrace(Request $request, $id)
    {
        if (request()->ajax()) {

            $item = Complaints::find($id);
            if ($item) {

                $item->trace_approve = $request->data['trace_approve'];
                $item->trace_show = ($request->data['trace_show']) ? Helper::encryptData($request->data['trace_show']) : '';
                $item->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            } else {
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        } else {
            abort(404);
        }
    }

    public function saveAnswer(Request $request, $id)
    {
        if (request()->ajax()) {

            $item = Complaints::find($id);
            if ($item) {

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
                    'date_time' => $item->answer_date,
                    'user_id' => Auth::user()->name,
                ]);

                $chk_user = Telegram::where("type", 1)
                    ->get();
                foreach ($chk_user as $row) {
                    $token = $row->token;
                    $chatId = $row->chat_id;
                    $message = "หน่วยกำกับดูแล ตอบการแก้ไขปัญหาข้อร้องเรียน \n" .
                        "รหัสเรื่องร้องเรียน: " . $item->code . "\n";

                    Helper::sendTelegramMessage($token, $chatId, $message);
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'succeed',
                ], 200);
            } else {
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        } else {
            abort(404);
        }
    }

    public function saveReport(Request $request, $id)
    {
        if (request()->ajax()) {

            $item = Complaints::find($id);
            if ($item) {

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
                    'date_time' => $item->report_date,
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
            } else {
                return response()->json([
                    'status' => 201,
                    'message' => 'error',
                ], 200);
            }

        } else {
            abort(404);
        }
    }

    public function sendError($id)
    {
        if (request()->ajax()) {

            $item = Complaints::find($id);
            if ($item) {
                if ($item->type == 5) {
                    $item->update([
                        'type' => 6,
                    ]);

                    ComplaintLog::create([
                        'complaint_id' => $item->id,
                        'type' => 5,
                        'date_time' => now(),
                        'user_id' => Auth::user()->name,
                    ]);

                    $chk_user = Telegram::where("type", 2)
                        ->where("unit_id", $item->send_unit)
                        ->get();
                    foreach ($chk_user as $row) {
                        $token = $row->token;
                        $chatId = $row->chat_id;
                        $message = "ศูนย์ได้ส่งเรื่องร้องเรียน กลับให้หน่วย แก้ไขข้อร้องเรียนใหม่อีกครั้ง \n" .
                            "รหัสเรื่องร้องเรียน: " . $item->code . "\n";

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
        } else {
            abort(404);
        }


    }

    public function sendNextStep($id)
    {
        if (request()->ajax()) {

            $item = Complaints::find($id);
            if ($item) {
                if ($item->type == 5) {
                    $item->update([
                        'type' => 7,
                    ]);

                    ComplaintLog::create([
                        'complaint_id' => $item->id,
                        'type' => 7,
                        'date_time' => now(),
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
        } else {
            abort(404);
        }


    }

    public function resetUpdate($id)
    {
        if (request()->ajax()) {
            $item = Complaints::find($id);
            if ($item) {
                if ($item->type == 0) {
                    $item->update([
                        'type' => 2,
                    ]);

                    ComplaintLog::create([
                        'complaint_id' => $item->id,
                        'type' => 8,
                        'date_time' => now(),
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
        } else {
            abort(404);
        }
    }



}
