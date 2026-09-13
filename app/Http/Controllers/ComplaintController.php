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
use App\Models\QuestionVote;
use App\Models\QuestionDetail;
use DB;
use App\Models\Telegram;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\ComplaintFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ComplaintController extends Controller
{

  public function index(Request $request): View
  {

    $company = Cache::remember('company', now()->addMinutes(10), function () {
      return Company::find(1);
    });
    $province = Province::select('id', 'name')->orderBy('name', 'ASC')->get();
    $unit = Unit::select('id', 'name')->where('type', 1)->orderBy('name', 'ASC')->get();
    $type = ComplaintType::select('id', 'name', 'num')->where('type', '>=', 1)->orderBy('num', 'ASC')->get();
    $sub = ComplaintSub::select('id', 'name', 'complaint_type_id')->where('type', 1)->orderBy('num', 'ASC')->get();
    $person = ComplaintPerson::select('id', 'name')->where('type', 1)->orderBy('num', 'ASC')->get();

    $sel_id = $request->input('type_id', '');
    return view('complaint.index', compact('company', 'province', 'unit', 'type', 'sub', 'person', 'sel_id'));
  }

  private function detectComplaintFileType(string $mimeType): string
  {
    if (str_starts_with($mimeType, 'image/')) {
      return 'image';
    }

    if ($mimeType === 'application/pdf') {
      return 'pdf';
    }

    if (str_starts_with($mimeType, 'video/')) {
      return 'video';
    }

    return 'other';
  }

  public function store(Request $request)
  {
    if (!$request->ajax()) {
      abort(404);
    }

    /*
    |--------------------------------------------------------------------------
    | Validate ข้อมูลและไฟล์
    |--------------------------------------------------------------------------
    */

    $validator = Validator::make($request->all(), [
      'fname' => ['required', 'string', 'max:255'],
      'lname' => ['required', 'string', 'max:255'],
      'phone' => ['required', 'string', 'max:20'],
      'type_id' => ['required', 'integer'],
      'name' => ['required', 'string'],
      'attachments' => ['nullable', 'array', 'max:12'],
      'attachments.*' => [
        'file',
        'max:10240', // 10 MB ต่อไฟล์
        'mimes:jpg,jpeg,png,webp,pdf,mp4,mov',
      ],
    ], [
      'attachments.array' => 'รูปแบบไฟล์แนบไม่ถูกต้อง',
      'attachments.max' => 'แนบไฟล์ได้รวมไม่เกิน 12 ไฟล์',
      'attachments.*.file' => 'ไฟล์แนบบางรายการไม่ถูกต้อง',
      'attachments.*.max' => 'ไฟล์แต่ละไฟล์ต้องมีขนาดไม่เกิน 10 MB',
      'attachments.*.mimes' => 'รองรับเฉพาะ JPG, PNG, WEBP, PDF, MP4 และ MOV',
    ]);

    /*
    |--------------------------------------------------------------------------
    | ตรวจสอบจำนวนไฟล์แต่ละประเภท
    |--------------------------------------------------------------------------
    */

    $validator->after(function ($validator) use ($request) {
      $imageCount = 0;
      $pdfCount = 0;
      $videoCount = 0;

      foreach ($request->file('attachments', []) as $file) {
        $mimeType = $file->getMimeType();

        if (str_starts_with($mimeType, 'image/')) {
          $imageCount++;
        } elseif ($mimeType === 'application/pdf') {
          $pdfCount++;
        } elseif (str_starts_with($mimeType, 'video/')) {
          $videoCount++;
        }
      }

      if ($imageCount > 10) {
        $validator->errors()->add(
          'attachments',
          'แนบรูปภาพได้ไม่เกิน 10 รูป'
        );
      }

      if ($pdfCount > 1) {
        $validator->errors()->add(
          'attachments',
          'แนบไฟล์ PDF ได้ไม่เกิน 1 ไฟล์'
        );
      }

      if ($videoCount > 1) {
        $validator->errors()->add(
          'attachments',
          'แนบไฟล์วิดีโอได้ไม่เกิน 1 ไฟล์'
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

    /*$idcard = $request->idcard;
    $idcardClean = str_replace('-', '', (string) $idcard);

    $checkIdcards = Helper::checkIdcare();

    if (in_array($idcardClean, $checkIdcards)) {
      return response()->json([
        'status' => 201,
        'message' => 'error idcard failed',
      ], 200);
    }*/

    $storedPaths = [];

    try {
      DB::beginTransaction();

      /*
      |--------------------------------------------------------------------------
      | สร้างเลขที่ร้องเรียน
      |--------------------------------------------------------------------------
      */

      $buddhistYear = (int) now()->format('Y') + 543;
      $yearTwoDigits = substr((string) $buddhistYear, -2);

      // เช่น DOH69
      $prefix = $request->key_title . $yearTwoDigits;

      /*
       * lockForUpdate ช่วยลดโอกาสได้เลขซ้ำ
       * ควรเพิ่ม UNIQUE INDEX ที่ complaints.code ด้วย
       */
      $lastNumber = DB::table('complaints')
        ->where('code', 'like', $prefix . '%')
        ->lockForUpdate()
        ->selectRaw(
          'MAX(CAST(RIGHT(code, 5) AS UNSIGNED)) AS last_number'
        )
        ->value('last_number');

      $nextNumber = ((int) $lastNumber) + 1;

      // เช่น DOH6900001
      $code = $prefix . sprintf('%05d', $nextNumber);

      /*
      |--------------------------------------------------------------------------
      | บันทึก Complaint
      |--------------------------------------------------------------------------
      */

      $complaint = Complaints::create([
        'code' => $code,
        'concealed' => $request->boolean('concealed') ? 1 : 0,
        'idcard' => null,
        'idcard_sub' => substr($request->phone, -4),
        'fname' => Helper::encryptData($request->fname),
        'lname' => Helper::encryptData($request->lname),
        'address' => Helper::encryptData($request->address),
        'work' => Helper::encryptData($request->work),

        'tel' => $request->tel
          ? Helper::encryptData($request->tel)
          : null,

        'email' => $request->email
          ? Helper::encryptData($request->email)
          : null,

        'phone' => Helper::encryptData($request->phone),
        'province_id' => $request->province_id,
        'district_id' => $request->district_id,
        'subdistrict_id' => $request->subdistrict_id,
        'zipcode' => $request->zipcode,
        'unit_id' => null,
        'type_id' => $request->type_id,
        'sub_id' => null,
        'person_id' => null,
        'gender' => $request->gender,
        'name' => Helper::encryptData($request->name),
        'description' => Helper::encryptData($request->description),
        'improvement' => Helper::encryptData($request->improvement),
        'type' => 2,
        'ip' => $request->ip(),
      ]);

      /*
      |--------------------------------------------------------------------------
      | Upload และบันทึกไฟล์แนบ
      |--------------------------------------------------------------------------
      */

      foreach ($request->file('attachments', []) as $index => $file) {
        $mimeType = $file->getMimeType();
        $fileType = $this->detectComplaintFileType($mimeType);

        $extension = strtolower(
          $file->getClientOriginalExtension()
        );

        $fileName = now()->format('Ymd_His')
          . '_'
          . Str::uuid()
          . '.'
          . $extension;

        /*
         * เก็บไฟล์ที่:
         * storage/app/public/complaints/{complaint_id}
         */
        $filePath = $file->storeAs(
          'complaints/' . $complaint->id,
          $fileName,
          'public'
        );

        if (!$filePath) {
          throw new \RuntimeException(
            'ไม่สามารถบันทึกไฟล์แนบได้'
          );
        }

        $storedPaths[] = $filePath;

        ComplaintFile::create([
          'complaint_id' => $complaint->id,
          'file_type' => $fileType,
          'original_name' => $file->getClientOriginalName(),
          'file_name' => $fileName,
          'file_path' => $filePath,
          'mime_type' => $mimeType,
          'extension' => $extension,
          'file_size' => $file->getSize(),
          'sort_order' => $index + 1,
        ]);
      }

      /*
      |--------------------------------------------------------------------------
      | บันทึกแบบประเมิน
      |--------------------------------------------------------------------------
      */

      $vote = QuestionVote::create([
        'gender' => $request->eva_gender,
        'work' => $request->eva_work,
        'work_dis' => $request->eva_work == 6
          ? $request->eva_workDis
          : '',
        'qualification' => $request->eva_qualification,
        'age' => $request->eva_age,
        'ip' => $request->ip(),
      ]);

      $questions = json_decode(
        $request->input('questions', '[]'),
        true
      );

      // ป้องกัน foreach ได้ค่า string หรือ null
      if (!is_array($questions)) {
        $questions = [];
      }

      foreach ($questions as $question) {
        if (
          !isset($question['id']) ||
          !array_key_exists('sel', $question)
        ) {
          continue;
        }

        QuestionDetail::create([
          'vote_id' => $vote->id,
          'question_id' => $question['id'],
          'score' => $question['sel'],
        ]);
      }

      DB::commit();

      /*
      |--------------------------------------------------------------------------
      | ส่ง Telegram หลังจากบันทึกฐานข้อมูลสำเร็จ
      |--------------------------------------------------------------------------
      */

      $showType = optional($complaint->hasType)->name ?? '-';

      if ($complaint->sub_id && $complaint->hasSub) {
        $showType .= ' (' . $complaint->hasSub->name . ')';
      }

      $telegramUsers = Telegram::where('type', 1)->get();

      foreach ($telegramUsers as $row) {
        $message = "มีผู้แจ้งเรื่องร้องเรียนใหม่\n"
          . "วันที่: " . Helper::getDateThaiFull(now()) . "\n"
          . "ชื่อผู้ร้อง: {$request->fname} {$request->lname}\n"
          . "รหัสเรื่องร้องเรียน: {$code}\n"
          . "ประเด็นการร้องเรียน: {$showType}\n"
          . "เรื่องที่ร้องเรียน: {$request->name}";

        try {
          Helper::sendTelegramMessage(
            $row->token,
            $row->chat_id,
            $message
          );
        } catch (\Throwable $telegramException) {
          // Telegram ส่งไม่สำเร็จ แต่ข้อมูลร้องเรียนยังบันทึกอยู่
          report($telegramException);
        }
      }

      return response()->json([
        'status' => 200,
        'message' => 'succeed',
        'code' => $code,
        'complaint_id' => $complaint->id,
        'files' => $complaint->files()->get(),
      ], 200);
    } catch (\Throwable $exception) {
      DB::rollBack();

      /*
       * ลบไฟล์ที่อัปโหลดไปแล้ว หากฐานข้อมูลบันทึกไม่สำเร็จ
       */
      foreach ($storedPaths as $path) {
        Storage::disk('public')->delete($path);
      }

      dd($exception);
      report($exception);

      return response()->json([
        'status' => 500,
        'message' => 'ไม่สามารถบันทึกเรื่องร้องเรียนได้',
      ], 500);
    }
  }


  /*
  public function store(Request $request)
  {
    if (request()->ajax()) {



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
      // ปี ค.ศ. + 543 แล้วเลือก 2 หลักสุดท้าย
      $buddhistYear = (int) now()->format('Y') + 543;
      $yearTwoDigits = substr((string) $buddhistYear, -2);

      // เช่น ABC69
      $prefix = $request->key_title . $yearTwoDigits;

      // หาเลขลำดับเฉพาะ key_title และปีปัจจุบัน
      $lastNumber = DB::table('complaints')
        ->where('code', 'like', $prefix . '%')
        ->selectRaw('MAX(CAST(RIGHT(code, 5) AS UNSIGNED)) AS last_number')
        ->value('last_number');

      $nextNumber = ((int) $lastNumber) + 1;

      // เช่น ABC6900001
      $code = $prefix . sprintf('%05d', $nextNumber);

      $complaint = Complaints::create([
        'code' => $code,
        'concealed' => ($request->concealed) ? 1 : 0,
        // 'file' => $filename,
        // 'idcard' => Helper::encryptData($idcard_clean),
        'idcard' => null,
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
        'unit_id' => null,
        'type_id' => $request->type_id,
        'sub_id' => null,
        'person_id' => null,
        'gender' => $request->gender,
        'name' => Helper::encryptData($request->name),
        'description' => Helper::encryptData($request->description),
        'improvement' => Helper::encryptData($request->improvement),
        'type' => 2,
        'ip' => $request->getClientIp(),
      ]);

      $show_type = $complaint->hasType->name;
      if ($complaint->sub_id) {
        $show_type .= '(' . $complaint->hasSub->name . ')';
      }


      $vote = QuestionVote::create([
        'gender' => $request->eva_gender,
        'work' => $request->eva_work,
        'work_dis' => ($request->eva_work == 6) ? $request->eva_workDis : '',
        'qualification' => $request->eva_qualification,
        'age' => $request->eva_age,
        'ip' => $request->getClientIp(),
      ]);

      $questions = json_decode(
        $request->input('questions', '[]'),
        true
      );

      foreach ($questions as $v) {
        QuestionDetail::create([
          'vote_id' => $vote->id,
          'question_id' => $v['id'],
          'score' => $v['sel'],
        ]);
      }


      $chk_user = Telegram::where("type", 1)->get();
      foreach ($chk_user as $row) {
        $token = $row->token;
        $chatId = $row->chat_id;
        $message = "มีผู้แจ้งเรื่องร้องเรียนใหม่ \n" .
          "วันที่: " . Helper::getDateThaiFull(now()) . "\n" .
          "ชื่อผู้ร้อง: " . $request->fname . " " . $request->lname . "\n" .
          "รหัสเรื่องร้องเรียน: " . $code . "\n" .
          //"ร้องเรียนถึง: ".$complaint->hasUnit->name."\n".
          "ประเด็นการ้องเรียน: " . $show_type . "\n" .
          //"ร้องเรียนบุคคล: ".$complaint->hasPerson->name."\n".
          "เรื่องที่ร้องเรียน: " . $request->name;

        Helper::sendTelegramMessage($token, $chatId, $message);
      }

      return response()->json([
        'status' => 200,
        'message' => 'succeed',
        'code' => $code,
      ], 200);
    } else {
      abort(404);
    }
  }
  */
  public function follow()
  {
    return view('complaint.follow');
  }


  public function getProvince(Request $request)
  {
    if (request()->ajax()) {
      $item = Province::select('id', 'name')->orderBy('name', 'ASC')->get();
      return response()->json([
        'status' => 200,
        'message' => 'succeed',
        'item' => $item,
      ], 200);
    } else {
      abort(404);
    }
  }

  public function getDistrict(Request $request, $province_id)
  {
    if (request()->ajax()) {
      $item = District::select('id', 'name')
        ->where('province_id', $province_id)
        ->orderBy('name', 'ASC')
        ->get();
      return response()->json([
        'status' => 200,
        'message' => 'succeed',
        'item' => $item,
      ], 200);
    } else {
      abort(404);
    }
  }

  public function getSubDistrict(Request $request, $district_id)
  {
    if (request()->ajax()) {
      $item = Subdistrict::select('id', 'name', 'zip_code')
        ->where('district_id', $district_id)
        ->orderBy('name', 'ASC')
        ->get();
      return response()->json([
        'status' => 200,
        'message' => 'succeed',
        'item' => $item,
      ], 200);
    } else {
      abort(404);
    }
  }

  public function getZipcode(Request $request, $subdistrict_id)
  {
    if (request()->ajax()) {
      $item = Zipcode::select('id', 'zipcode')
        ->where('subdistrict_id', $subdistrict_id)
        ->first();
      return response()->json([
        'status' => 200,
        'message' => 'succeed',
        'zipcode' => (!empty($item)) ? $item->zipcode : '',
      ], 200);
    } else {
      abort(404);
    }
  }

  public function trackingResult(): View
  {
    $complaintId = session('complaint_tracking_id');
    $complaintCode = session('complaint_tracking_code');
    $trackingToken = session('complaint_tracking_token');

    if (!$complaintId || !$complaintCode || !$trackingToken) {
      abort(404);
    }

    $item = Complaints::query()
      ->with([
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
      ])
      ->select([
        'id',
        'code',
        'fname',
        'lname',
        'work',
        'address',
        'email',
        'phone',
        'tel',
        'created_at',
        'updated_at',
        'improvement',
        'name',
        'description',
        'idcard',
        'unit_id',
        'method_id',
        'type_id',
        'sub_id',
        'person_id',
        'gender',
        'concealed',
        'province_id',
        'district_id',
        'subdistrict_id',
        'zipcode',
        'file',
        'trace_approve',
        'trace_show',
        'type',
      ])
      ->whereKey($complaintId)
      ->where('code', $complaintCode)
      ->first();

    if (!$item) {
      abort(404);
    }

    // ใช้ URL แบบ relative และใช้ชื่อ tracking_url เพื่อไม่ชน Model accessor
    $item->files->each(function (ComplaintFile $file) {
      $file->setAttribute(
        'tracking_url',
        route(
          'complaint.file.show',
          ['complaintFile' => $file->id],
          false
        )
      );

      $size = (int) $file->file_size;
      $file->setAttribute(
        'file_size_text',
        $size >= 1024 * 1024
        ? number_format($size / (1024 * 1024), 2) . ' MB'
        : number_format($size / 1024, 2) . ' KB'
      );
    });

    if ((int) $item->concealed !== 1) {
      $item->fname = $item->fname ? Helper::decryptData($item->fname) : '';
      $item->lname = $item->lname ? Helper::decryptData($item->lname) : '';
      $item->work = $item->work ? Helper::decryptData($item->work) : '';
      $address = $item->address ? Helper::decryptData($item->address) : '';
      $item->email = $item->email ? Helper::decryptData($item->email) : '';
      $item->phone = $item->phone ? Helper::decryptData($item->phone) : '';
      $item->tel = $item->tel ? Helper::decryptData($item->tel) : '';
      $item->idcard = $item->idcard ? Helper::decryptData($item->idcard) : '';
      $item->address = Helper::getAddress(
        $item->province_id,
        $item->district_id,
        $item->subdistrict_id,
        $address,
        $item->zipcode
      );
    } else {
      $item->fname = '';
      $item->lname = '';
      $item->work = '';
      $item->email = '';
      $item->phone = '';
      $item->tel = '';
      $item->idcard = '';
      $item->address = '';
      $item->zipcode = '';
      $item->province_id = null;
      $item->district_id = null;
      $item->subdistrict_id = null;
    }

    $item->fname_masked = Helper::maskName(
        $item->fname
    );

    $item->lname_masked = Helper::maskName(
        $item->lname
    );

    $item->phone_masked = Helper::maskPhone(
        $item->phone
    );

    $item->improvement = $item->improvement
      ? Helper::decryptData($item->improvement)
      : '';
    $item->name = $item->name ? Helper::decryptData($item->name) : '';
    $item->description = $item->description
      ? Helper::decryptData($item->description)
      : '';
    $item->trace_show = $item->trace_show
      ? Helper::decryptData($item->trace_show)
      : '';

    $item->unit_name = $item->unit_id
      ? Unit::whereKey($item->unit_id)->value('name')
      : null;
    $item->method_name = $item->method_id
      ? ComplaintMethod::whereKey($item->method_id)->value('name')
      : null;
    $item->type_name = $item->type_id
      ? ComplaintType::whereKey($item->type_id)->value('name')
      : null;
    $item->sub_name = $item->sub_id
      ? ComplaintSub::whereKey($item->sub_id)->value('name')
      : null;
    $item->person_name = $item->person_id
      ? ComplaintPerson::whereKey($item->person_id)->value('name')
      : null;

    // รองรับไฟล์จากคอลัมน์เดิม
    if ($item->file) {
      $item->file_url = '/storage/files/' . ltrim($item->file, '/');
    }

    return view('complaint.follow', compact('item'));
  }

  public function showComplaintFile(ComplaintFile $complaintFile)
  {
    $complaintId = session('complaint_tracking_id');
    $complaintCode = session('complaint_tracking_code');
    $trackingToken = session('complaint_tracking_token');

    if (!$complaintId || !$complaintCode || !$trackingToken) {
      abort(404);
    }

    if ((int) $complaintFile->complaint_id !== (int) $complaintId) {
      abort(404);
    }

    $allowed = Complaints::query()
      ->whereKey($complaintId)
      ->where('code', $complaintCode)
      ->exists();

    if (!$allowed) {
      abort(404);
    }

    $disk = Storage::disk('public');
    $path = $complaintFile->file_path;

    if (!$path || !$disk->exists($path)) {
      abort(404, 'ไม่พบไฟล์');
    }

    $absolutePath = $disk->path($path);

    if (!is_file($absolutePath) || !is_readable($absolutePath)) {
      abort(404, 'ไม่สามารถอ่านไฟล์ได้');
    }

    return response()->file($absolutePath, [
      'Content-Type' => $complaintFile->mime_type
        ?: 'application/octet-stream',
      'Content-Disposition' => 'inline; filename="' . $complaintFile->file_name . '"',
      'X-Content-Type-Options' => 'nosniff',
      'Cache-Control' => 'private, no-store, max-age=0',
    ]);
  }


  public function followCheck(Request $request)
  {

    if (request()->ajax()) {

      $idcard_sub = $request->phone_last4;
      $code = $request->code;


      $complaint = Complaints::select('id', 'code')
        ->where('code', $code)
        ->where('idcard_sub', $idcard_sub)
        ->first();

      if ($complaint) {


        $trackingToken = Str::random(64);

        // Token หมดอายุใน 30 นาที
        Cache::put(
          'complaint_tracking_token:' . $trackingToken,
          $complaint->id,
          now()->addMinutes(30)
        );

        // เก็บสิทธิ์ไว้ใน Laravel Session
        session([
          'complaint_tracking_id' => $complaint->id,
          'complaint_tracking_token' => $trackingToken,
          'complaint_tracking_code' => $complaint->code,
        ]);

        return response()->json([
          'status' => 200,
          'message' => 'succeed',
          'tracking_token' => $complaint->id,
          'complaint_id' => $complaint->code,
        ], 200);
      } else {
        return response()->json([
          'status' => 404,
          'message' => 'ไม่พบข้อมูลเรื่องร้องเรียน กรุณาตรวจสอบข้อมูลอีกครั้ง',
        ], 200);
      }

    } else {
      abort(404);
    }
  }

  public function test()
  {
    return view('test');
    /*$token = "8260913888:AAHoRuNzvQCU-brJQ0w8uTzLxK-rCCiRCWg";
      $chatId = "-5047928395";
      $message = "ทดสอบส่งข้อความจาก Laravel!";

      Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
          'chat_id' => $chatId,
          'text'    => $message,
      ]);

      return "ส่งข้อความไป Telegram แล้ว!";*/
  }

}
