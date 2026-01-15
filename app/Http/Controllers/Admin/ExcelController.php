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

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Font;


class ExcelController extends Controller
{
  public function office(Request $request){
    if(request()->ajax()) {
      $spreadsheet = new Spreadsheet();
      $spreadsheet->getDefaultStyle()->getFont()->setName('TH SarabunPSK');
      $spreadsheet->getDefaultStyle()->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->freezePane('A5');


      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('รายงาน');

      $sheet->getCell('A1')->setValue('รายงานแบ่งตามหน่วยรับผิดชอบ');
      $sheet->mergeCells('A1:E1');
      if($request->type == 1){
        if(is_array($request->day) &&
            count($request->day) === 2 &&
            !empty($request->day[0]) &&
            !empty($request->day[1])){

            $dt = Carbon::parse($request->day[0])->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $show_date = Helper::getDateThaiFull($date);

            if($request->day[0] == $request->day[1]){
                $sheet->getCell('A2')->setValue('ประจำวันที่ '.$show_date);
            }else{
              $dt_end = Carbon::parse($request->day[1])->setTimezone('Asia/Bangkok');
              $date_end = $dt_end->format('Y-m-d');
              $show_date_end = Helper::getDateThaiFull($date_end);
              $sheet->getCell('A2')->setValue('ระหว่างวันที่ '.$show_date.' ถึง '.$show_date_end);
            }

        }else{
            $dt = Carbon::parse($request->day)->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $show_date = Helper::getDateThaiFull($date);
            $sheet->getCell('A2')->setValue('ประจำวันที่ '.$show_date);
        }
      }
      if($request->type == 2){
        $show_month = Helper::getMonThfull($request->month);
        $sheet->getCell('A2')->setValue('ประจำเดือน '.$show_month.' '.$request->year);
      }
      if($request->type == 3){
        $sheet->getCell('A2')->setValue('ประจำปี '.$request->year);
      }
      $sheet->mergeCells('A2:E2');

      $sheet->getCell('B4')->setValue('ลำดับ');
      $sheet->getCell('C4')->setValue('หน่วย');
      $sheet->getCell('D4')->setValue('จำนวนเรื่อง');
      $sheet->getCell('E4')->setValue('ร้อยละ');


      $sheet->getStyle('A1:E4')->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);
      $sheet->getStyle('A1:E4')->getFont()->setBold(true);
      // $sheet->getStyle('A1:N1')->getFont()->setSize(18);
      $total = count($request->item);
      $row_sum = $total+5;

      $numrow = 5;
      foreach($request->item as $k => $v){
        $num = $k+1;
        $sheet->getCell('B'.$numrow)->setValue($num);
        $sheet->getCell('C'.$numrow)->setValue($v['name']);
        $sheet->getCell('D'.$numrow)->setValue($v['count']);
        if($v['count'] > 0){
          $sheet->setCellValue('E' . $numrow, '=ROUND(D'.$numrow.'/D'.$row_sum.'* 100, 2) & "%"');
        }
        $numrow++;

      }

      $sheet->getCell('B'.$numrow)->setValue('รวม');
      $sheet->mergeCells('B'.$numrow.':C'.$numrow);
      $sheet->getStyle('B'.$numrow.':C'.$numrow)->getFont()->setBold(true);
      $sub_end = $numrow - 1;
      $sheet->setCellValue('D' . $numrow, '=SUM(D5:D'.$sub_end.')');


      $sheet->getStyle('B5:B'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);

      $sheet->getStyle('D5:E'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);

      $styleArray = [
          'borders' => [
              'allBorders' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                  'color' => ['argb' => '000'],
              ],
          ],
        ];
      $sheet->getStyle('B4:E'.$numrow)->applyFromArray($styleArray);


      $sheet->getColumnDimension('A')->setWidth('3');
      $sheet->getColumnDimension('B')->setWidth('8');
      $sheet->getColumnDimension('C')->setWidth('55');
      $sheet->getColumnDimension('D')->setWidth('20');
      $sheet->getColumnDimension('E')->setWidth('20');


      $writer = new Xlsx($spreadsheet);

      $path = public_path();
      $file_name = 'report_office.xlsx';
      $writer->save($path.'/export/'.$file_name);

      return response()->json([
          'status' => 200,
          'message' => 'export successfully.',
          'data' => [
              'filename' => $file_name,
          ]
      ], 200);
    }else{
      abort(404);
    }
  }

  public function type(Request $request){
    if(request()->ajax()) {
      $spreadsheet = new Spreadsheet();
      $spreadsheet->getDefaultStyle()->getFont()->setName('TH SarabunPSK');
      $spreadsheet->getDefaultStyle()->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->freezePane('A5');


      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('รายงาน');

      $sheet->getCell('A1')->setValue('รายงานแบ่งตามประเภทข้อร้องเรียน');
      $sheet->mergeCells('A1:F1');
      if($request->type == 1){
        if(is_array($request->day) &&
            count($request->day) === 2 &&
            !empty($request->day[0]) &&
            !empty($request->day[1])){

            $dt = Carbon::parse($request->day[0])->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $show_date = Helper::getDateThaiFull($date);

            if($request->day[0] == $request->day[1]){
                $sheet->getCell('A2')->setValue('ประจำวันที่ '.$show_date);
            }else{
              $dt_end = Carbon::parse($request->day[1])->setTimezone('Asia/Bangkok');
              $date_end = $dt_end->format('Y-m-d');
              $show_date_end = Helper::getDateThaiFull($date_end);
              $sheet->getCell('A2')->setValue('ระหว่างวันที่ '.$show_date.' ถึง '.$show_date_end);
            }

        }else{
            $dt = Carbon::parse($request->day)->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $show_date = Helper::getDateThaiFull($date);
            $sheet->getCell('A2')->setValue('ประจำวันที่ '.$show_date);
        }
      }
      if($request->type == 2){
        $show_month = Helper::getMonThfull($request->month);
        $sheet->getCell('A2')->setValue('ประจำเดือน '.$show_month.' '.$request->year);
      }
      if($request->type == 3){
        $sheet->getCell('A2')->setValue('ประจำปี '.$request->year);
      }
      $sheet->mergeCells('A2:F2');

      $sheet->getCell('B4')->setValue('ลำดับ');
      $sheet->mergeCells('B4:C4');
      $sheet->getCell('D4')->setValue('ประเด็นข้อร้องเรียน');
      $sheet->getCell('E4')->setValue('จำนวนเรื่อง');
      $sheet->getCell('F4')->setValue('ร้อยละ');


      $sheet->getStyle('A1:F4')->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);
      $sheet->getStyle('A1:F4')->getFont()->setBold(true);
      // $sheet->getStyle('A1:N1')->getFont()->setSize(18);
      $total = count($request->item);
      $row_sum = $total+5+$request->total_sub;
      $numrow = 5;
      foreach($request->item as $k => $v){
        $num = $k+1;
        $sheet->getCell('B'.$numrow)->setValue($num);
        $sheet->getCell('C'.$numrow)->setValue($v['name']);
        $sheet->mergeCells('C'.$numrow.':D'.$numrow);
        if($v['type'] == 1){
          $sheet->getCell('E'.$numrow)->setValue($v['count']);
          if($v['count'] > 0){
            $sheet->setCellValue('F' . $numrow, '=ROUND(E'.$numrow.'/E'.$row_sum.'* 100, 2) & "%"');
          }
        }
        $numrow++;


        foreach($v['sub'] as $k_sub => $v_sub){
          $sub_num = $k_sub+1;
          $sheet->getCell('C'.$numrow)->setValue($num.'.'.$sub_num);
          $sheet->getCell('D'.$numrow)->setValue($v_sub['name']);
          $sheet->getStyle('C'.$numrow.':C'.$numrow)->getAlignment()->applyFromArray([
              'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
              'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
              'wrapText' => true,
          ]);
          $sheet->getCell('E'.$numrow)->setValue($v_sub['count']);
          if($v_sub['count'] > 0){
            $sheet->setCellValue('F' . $numrow, '=ROUND(E'.$numrow.'/E'.$row_sum.'* 100, 2) & "%"');
          }
          $numrow++;
        }

      }

      $sheet->getCell('B'.$numrow)->setValue('รวม');
      $sheet->mergeCells('B'.$numrow.':D'.$numrow);
      $sheet->getStyle('B'.$numrow.':F'.$numrow)->getFont()->setBold(true);
      $sub_end = $numrow - 1;
      $sheet->setCellValue('E' . $numrow, '=SUM(E5:E'.$sub_end.')');

      $sheet->getStyle('B5:B'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);

      $sheet->getStyle('E5:F'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);

      $styleArray = [
          'borders' => [
              'allBorders' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                  'color' => ['argb' => '000'],
              ],
          ],
        ];
      $sheet->getStyle('B4:F'.$numrow)->applyFromArray($styleArray);


      $sheet->getColumnDimension('A')->setWidth('3');
      $sheet->getColumnDimension('B')->setWidth('6');
      $sheet->getColumnDimension('C')->setWidth('6');
      $sheet->getColumnDimension('D')->setWidth('55');
      $sheet->getColumnDimension('E')->setWidth('20');
      $sheet->getColumnDimension('F')->setWidth('20');


      $writer = new Xlsx($spreadsheet);

      $path = public_path();
      $file_name = 'report_type.xlsx';
      $writer->save($path.'/export/'.$file_name);

      return response()->json([
          'status' => 200,
          'message' => 'export successfully.',
          'data' => [
              'filename' => $file_name,
          ]
      ], 200);
    }else{
      abort(404);
    }
  }

  public function methods(Request $request){
    if(request()->ajax()) {
      $spreadsheet = new Spreadsheet();
      $spreadsheet->getDefaultStyle()->getFont()->setName('TH SarabunPSK');
      $spreadsheet->getDefaultStyle()->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->freezePane('A5');


      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('รายงาน');

      $sheet->getCell('A1')->setValue('รายงานแบ่งตามช่องทางการร้องเรียน');
      $sheet->mergeCells('A1:E1');
      if($request->type == 1){
        if(is_array($request->day) &&
            count($request->day) === 2 &&
            !empty($request->day[0]) &&
            !empty($request->day[1])){

            $dt = Carbon::parse($request->day[0])->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $show_date = Helper::getDateThaiFull($date);

            if($request->day[0] == $request->day[1]){
                $sheet->getCell('A2')->setValue('ประจำวันที่ '.$show_date);
            }else{
              $dt_end = Carbon::parse($request->day[1])->setTimezone('Asia/Bangkok');
              $date_end = $dt_end->format('Y-m-d');
              $show_date_end = Helper::getDateThaiFull($date_end);
              $sheet->getCell('A2')->setValue('ระหว่างวันที่ '.$show_date.' ถึง '.$show_date_end);
            }

        }else{
            $dt = Carbon::parse($request->day)->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $show_date = Helper::getDateThaiFull($date);
            $sheet->getCell('A2')->setValue('ประจำวันที่ '.$show_date);
        }
      }
      if($request->type == 2){
        $show_month = Helper::getMonThfull($request->month);
        $sheet->getCell('A2')->setValue('ประจำเดือน '.$show_month.' '.$request->year);
      }
      if($request->type == 3){
        $sheet->getCell('A2')->setValue('ประจำปี '.$request->year);
      }
      $sheet->mergeCells('A2:E2');

      $sheet->getCell('B4')->setValue('ลำดับ');
      $sheet->getCell('C4')->setValue('ช่องทางการร้องเรียน');
      $sheet->getCell('D4')->setValue('จำนวนเรื่อง');
      $sheet->getCell('E4')->setValue('ร้อยละ');


      $sheet->getStyle('A1:E4')->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);
      $sheet->getStyle('A1:E4')->getFont()->setBold(true);
      // $sheet->getStyle('A1:N1')->getFont()->setSize(18);
      $total = count($request->item);
      $row_sum = $total+5;

      $numrow = 5;
      foreach($request->item as $k => $v){
        $num = $k+1;
        $sheet->getCell('B'.$numrow)->setValue($num);
        $sheet->getCell('C'.$numrow)->setValue($v['name']);
        $sheet->getCell('D'.$numrow)->setValue($v['count']);
        if($v['count'] > 0){
          $sheet->setCellValue('E' . $numrow, '=ROUND(D'.$numrow.'/D'.$row_sum.'* 100, 2) & "%"');
        }
        $numrow++;

      }

      $sheet->getCell('B'.$numrow)->setValue('รวม');
      $sheet->mergeCells('B'.$numrow.':C'.$numrow);
      $sheet->getStyle('B'.$numrow.':C'.$numrow)->getFont()->setBold(true);
      $sub_end = $numrow - 1;
      $sheet->setCellValue('D' . $numrow, '=SUM(D5:D'.$sub_end.')');


      $sheet->getStyle('B5:B'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);

      $sheet->getStyle('D5:E'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);

      $styleArray = [
          'borders' => [
              'allBorders' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                  'color' => ['argb' => '000'],
              ],
          ],
        ];
      $sheet->getStyle('B4:E'.$numrow)->applyFromArray($styleArray);


      $sheet->getColumnDimension('A')->setWidth('3');
      $sheet->getColumnDimension('B')->setWidth('8');
      $sheet->getColumnDimension('C')->setWidth('55');
      $sheet->getColumnDimension('D')->setWidth('20');
      $sheet->getColumnDimension('E')->setWidth('20');


      $writer = new Xlsx($spreadsheet);

      $path = public_path();
      $file_name = 'report_methods.xlsx';
      $writer->save($path.'/export/'.$file_name);

      return response()->json([
          'status' => 200,
          'message' => 'export successfully.',
          'data' => [
              'filename' => $file_name,
          ]
      ], 200);
    }else{
      abort(404);
    }
  }

  public function person(Request $request){
    if(request()->ajax()) {
      $spreadsheet = new Spreadsheet();
      $spreadsheet->getDefaultStyle()->getFont()->setName('TH SarabunPSK');
      $spreadsheet->getDefaultStyle()->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->freezePane('A5');


      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('รายงาน');

      $sheet->getCell('A1')->setValue('รายงานแบ่งตามบุคคลที่ถูกร้องเรียน');
      $sheet->mergeCells('A1:E1');
      if($request->type == 1){
        if(is_array($request->day) &&
            count($request->day) === 2 &&
            !empty($request->day[0]) &&
            !empty($request->day[1])){

            $dt = Carbon::parse($request->day[0])->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $show_date = Helper::getDateThaiFull($date);

            if($request->day[0] == $request->day[1]){
                $sheet->getCell('A2')->setValue('ประจำวันที่ '.$show_date);
            }else{
              $dt_end = Carbon::parse($request->day[1])->setTimezone('Asia/Bangkok');
              $date_end = $dt_end->format('Y-m-d');
              $show_date_end = Helper::getDateThaiFull($date_end);
              $sheet->getCell('A2')->setValue('ระหว่างวันที่ '.$show_date.' ถึง '.$show_date_end);
            }

        }else{
            $dt = Carbon::parse($request->day)->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $show_date = Helper::getDateThaiFull($date);
            $sheet->getCell('A2')->setValue('ประจำวันที่ '.$show_date);
        }
      }
      if($request->type == 2){
        $show_month = Helper::getMonThfull($request->month);
        $sheet->getCell('A2')->setValue('ประจำเดือน '.$show_month.' '.$request->year);
      }
      if($request->type == 3){
        $sheet->getCell('A2')->setValue('ประจำปี '.$request->year);
      }
      $sheet->mergeCells('A2:E2');

      $sheet->getCell('B4')->setValue('ลำดับ');
      $sheet->getCell('C4')->setValue('บุคคล');
      $sheet->getCell('D4')->setValue('จำนวนเรื่อง');
      $sheet->getCell('E4')->setValue('ร้อยละ');


      $sheet->getStyle('A1:E4')->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);
      $sheet->getStyle('A1:E4')->getFont()->setBold(true);
      // $sheet->getStyle('A1:N1')->getFont()->setSize(18);
      $total = count($request->item);
      $row_sum = $total+5;

      $numrow = 5;
      foreach($request->item as $k => $v){
        $num = $k+1;
        $sheet->getCell('B'.$numrow)->setValue($num);
        $sheet->getCell('C'.$numrow)->setValue($v['name']);
        $sheet->getCell('D'.$numrow)->setValue($v['count']);
        if($v['count'] > 0){
          $sheet->setCellValue('E' . $numrow, '=ROUND(D'.$numrow.'/D'.$row_sum.'* 100, 2) & "%"');
        }
        $numrow++;

      }

      $sheet->getCell('B'.$numrow)->setValue('รวม');
      $sheet->mergeCells('B'.$numrow.':C'.$numrow);
      $sheet->getStyle('B'.$numrow.':C'.$numrow)->getFont()->setBold(true);
      $sub_end = $numrow - 1;
      $sheet->setCellValue('D' . $numrow, '=SUM(D5:D'.$sub_end.')');


      $sheet->getStyle('B5:B'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);

      $sheet->getStyle('D5:E'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);

      $styleArray = [
          'borders' => [
              'allBorders' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                  'color' => ['argb' => '000'],
              ],
          ],
        ];
      $sheet->getStyle('B4:E'.$numrow)->applyFromArray($styleArray);


      $sheet->getColumnDimension('A')->setWidth('3');
      $sheet->getColumnDimension('B')->setWidth('8');
      $sheet->getColumnDimension('C')->setWidth('55');
      $sheet->getColumnDimension('D')->setWidth('20');
      $sheet->getColumnDimension('E')->setWidth('20');


      $writer = new Xlsx($spreadsheet);

      $path = public_path();
      $file_name = 'report_person.xlsx';
      $writer->save($path.'/export/'.$file_name);

      return response()->json([
          'status' => 200,
          'message' => 'export successfully.',
          'data' => [
              'filename' => $file_name,
          ]
      ], 200);
    }else{
      abort(404);
    }
  }

  public function history(Request $request){
    if(request()->ajax()) {
      $spreadsheet = new Spreadsheet();
      $spreadsheet->getDefaultStyle()->getFont()->setName('TH SarabunPSK');
      $spreadsheet->getDefaultStyle()->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->freezePane('A4');


      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('สถิติผู้เข้าชม');

      if($request->type == 3){
        $sheet->getCell('A1')->setValue('สถิติผู้เข้าชม 30 วันล่าสุด');
        // $sheet->getCell('C3')->setValue('วันที่');
      }
      if($request->type == 2){
        $sheet->getCell('A1')->setValue('สถิติผู้เข้าชม รายเดือน ประจำปี '.$request->year);
        $sheet->getCell('C3')->setValue('เดือน ปี (พ.ศ.)');
      }
      if($request->type == 1){
        $sheet->getCell('A1')->setValue('สถิติผู้เข้าชม รายปี');
        $sheet->getCell('C3')->setValue('ปี (พ.ศ.)');
      }
      $sheet->mergeCells('A1:D1');
      
      $sheet->getCell('B3')->setValue('ลำดับ');
      $sheet->getCell('D3')->setValue('จำนวนเรื่อง');


      $sheet->getStyle('A1:D3')->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);
      $sheet->getStyle('A1:D3')->getFont()->setBold(true);
      

      $numrow = 4;
      foreach($request->item as $k => $v){
        $num = $k+1;
        $sheet->getCell('B'.$numrow)->setValue($num);
        $sheet->getCell('C'.$numrow)->setValue($v['name']);
        $sheet->getCell('D'.$numrow)->setValue($v['count']);
        $numrow++;
      }

      
      $sheet->getCell('B'.$numrow)->setValue('รวม');
      $sheet->mergeCells('B'.$numrow.':C'.$numrow);
      $sheet->getStyle('B'.$numrow.':D'.$numrow)->getFont()->setBold(true);
      $sub_end = $numrow - 1;
      $sheet->setCellValue('D' . $numrow, '=SUM(D4:D'.$sub_end.')');


      $sheet->getStyle('B4:D'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);

      $styleArray = [
          'borders' => [
              'allBorders' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                  'color' => ['argb' => '000'],
              ],
          ],
        ];
      $sheet->getStyle('B3:D'.$numrow)->applyFromArray($styleArray);

      
      $sheet->getColumnDimension('A')->setWidth('3');
      $sheet->getColumnDimension('B')->setWidth('12');
      $sheet->getColumnDimension('C')->setWidth('22');
      $sheet->getColumnDimension('D')->setWidth('22');


      $writer = new Xlsx($spreadsheet);

      $path = public_path();
      $file_name = 'report_history.xlsx';
      $writer->save($path.'/export/'.$file_name);

      return response()->json([
          'status' => 200,
          'message' => 'export successfully.',
          'data' => [
              'filename' => $file_name,
          ]
      ], 200);
    }else{
      abort(404);
    }
  }

  public function summary(Request $request){
    if(request()->ajax()) {

      $spreadsheet = new Spreadsheet();
      $spreadsheet->getDefaultStyle()->getFont()->setName('TH SarabunPSK');
      $spreadsheet->getDefaultStyle()->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->freezePane('A5');


      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('ภาพรวม');

      $sheet->getCell('A1')->setValue('ทะเบียนรวมข้อร้องเรียน');
      $sheet->mergeCells('A1:T1');
      if($request->type == 1){
        if(is_array($request->day) &&
            count($request->day) === 2 &&
            !empty($request->day[0]) &&
            !empty($request->day[1])){

            $dt = Carbon::parse($request->day[0])->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $show_date = Helper::getDateThaiFull($date);

            if($request->day[0] == $request->day[1]){
                $sheet->getCell('A2')->setValue('ประจำวันที่ '.$show_date);
            }else{
              $dt_end = Carbon::parse($request->day[1])->setTimezone('Asia/Bangkok');
              $date_end = $dt_end->format('Y-m-d');
              $show_date_end = Helper::getDateThaiFull($date_end);
              $sheet->getCell('A2')->setValue('ระหว่างวันที่ '.$show_date.' ถึง '.$show_date_end);
            }

        }else{
            $dt = Carbon::parse($request->day)->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $show_date = Helper::getDateThaiFull($date);
            $sheet->getCell('A2')->setValue('ประจำวันที่ '.$show_date);
        }
      }
      if($request->type == 2){
        $show_month = Helper::getMonThfull($request->month);
        $sheet->getCell('A2')->setValue('ประจำเดือน '.$show_month.' '.$request->year);
      }
      if($request->type == 3){
        $sheet->getCell('A2')->setValue('ประจำปี '.$request->year);
      }
      $sheet->mergeCells('A2:T2');

      $sheet->getCell('B4')->setValue('ลำดับ');
      $sheet->getCell('C4')->setValue('รหัสเรื่อง');
      $sheet->getCell('D4')->setValue('วันที่บันทึก');
      $sheet->getCell('E4')->setValue('ประเภทช่องทาง');
      $sheet->getCell('F4')->setValue('ผู้ถูกร้องเรียน');
      $sheet->getCell('G4')->setValue('ประเด็นข้อร้องเรียน');
      $sheet->getCell('H4')->setValue('หน่วยที่เกี่ยวข้อง');
      $sheet->getCell('I4')->setValue('เรื่องที่ร้องเรียน');
      $sheet->getCell('J4')->setValue('รายละเอียดเรื่องที่ร้องเรียน');
      $sheet->getCell('K4')->setValue('สิ่งที่ต้องการให้แก้ไข');
      $sheet->getCell('L4')->setValue('เอกสารประกอบ');
      $sheet->getCell('M4')->setValue('ระดับความรุนแรง');
      $sheet->getCell('N4')->setValue('หน่วยงานที่รับผิดชอบ/สถานที่ส่งเรื่อง');
      $sheet->getCell('O4')->setValue('วันที่ส่งเรื่องให้หน่วย');
      $sheet->getCell('P4')->setValue('วันที่หน่วยรับเรื่อง');
      $sheet->getCell('Q4')->setValue('วิธีการแก้ไขผลการดำเนินการ');
      $sheet->getCell('R4')->setValue('เอกสารแนบ');
      $sheet->getCell('S4')->setValue('วันที่ดำเนินการแก้ไข');
      $sheet->getCell('T4')->setValue('สถานะเรื่อง');

      $sheet->getStyle('A1:T4')->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);
      $sheet->getStyle('A1:T4')->getFont()->setBold(true);
      // $sheet->getStyle('A1:N1')->getFont()->setSize(18);
      $numrow = 5;
      foreach($request->item as $k => $v){

        $comp = Complaints::find($v['id']);

        $num = $k+1;
        $sheet->getCell('B'.$numrow)->setValue($num);
        $sheet->getCell('C'.$numrow)->setValue($v['code']);
        $show_date = Helper::getDateThaiSubYear($v['created_at']);
        $show_time = substr($v['created_at'],11,5);
        $sheet->getCell('D'.$numrow)->setValue($show_date.' '.$show_time);
        $sheet->getCell('E'.$numrow)->setValue($v['method_name']);
        $sheet->getCell('F'.$numrow)->setValue($v['person_name']);
        $type_name = '';
        if($v['type_name'] != ''){
          $type_name .= $v['type_name'];
        }
        if($v['sub_name'] != ''){
          $type_name .= ' ('.$v['sub_name'].')';
        }
        $sheet->getCell('G'.$numrow)->setValue($type_name);
        $sheet->getCell('H'.$numrow)->setValue($v['unit_name']);
        $sheet->getCell('N'.$numrow)->setValue($v['unit_send']);

        if($comp){
          $name = ($comp->name) ? Helper::decryptData($comp->name) : '';
          $sheet->getCell('I'.$numrow)->setValue($name);

          $description = ($comp->description) ? Helper::decryptData($comp->description) : '';
          $sheet->getCell('J'.$numrow)->setValue($description);

          $improvement = ($comp->improvement) ? Helper::decryptData($comp->improvement) : '';
          $sheet->getCell('K'.$numrow)->setValue($improvement);

          if($comp->file){
             $sheet->getCell('L'.$numrow)->setValue($comp->file);
          }
          if($comp->severity_admin){
            $sheet->getCell('M'.$numrow)->setValue('ระดับ '.$comp->severity_admin);
          }
          if($comp->send_date){
            $show_date = Helper::getDateThaiSubYear($comp->send_date);
            $show_time = substr($comp->send_date,11,5);
            $sheet->getCell('O'.$numrow)->setValue($show_date.' '.$show_time);
          }
          if($comp->receive_date){
            $show_date = Helper::getDateThaiSubYear($comp->receive_date);
            $show_time = substr($comp->receive_date,11,5);
            $sheet->getCell('P'.$numrow)->setValue($show_date.' '.$show_time);
          }
          if($comp->answer_detail){
            $answer_detail = ($comp->answer_detail) ? Helper::decryptData($comp->answer_detail) : '';
            $sheet->getCell('Q'.$numrow)->setValue($answer_detail);
          }
          if($comp->answer_file){
             $sheet->getCell('R'.$numrow)->setValue($comp->answer_file);
          }
          if($comp->answer_date){
            $show_date = Helper::getDateThaiSubYear($comp->answer_date);
            $show_time = substr($comp->answer_date,11,5);
            $sheet->getCell('S'.$numrow)->setValue($show_date.' '.$show_time);
          }
          // $sheet->getCell('M'.$numrow)->setValue($v['severity']);

          // $sheet->getCell('O'.$numrow)->setValue($v['date_sent']);
          // $sheet->getCell('P'.$numrow)->setValue($v['date_received']);
          // $sheet->getCell('Q'.$numrow)->setValue($v['solution_method']);
          // $sheet->getCell('R'.$numrow)->setValue($v['solution_document']);
          // $sheet->getCell('S'.$numrow)->setValue($v['date_solved']);
        }

        $status = '';
        $user_level = Auth::user()->level;
        if ($v['type'] == 0) {
            $status = 'ยุติเรื่อง-ไม่ใช่';
        }

        else if ($v['type'] == 2) {
            $status = 'รอศูนย์รับเรื่อง';
        }

        else if ($v['type'] == 3 && $user_level == 'root') {
            $status = 'ศูนย์รับเรื่อง - รอหน่วยรับเรื่อง';
        }

        else if ($v['type'] == 3 && $user_level == 'unit') {
            $status = 'รอรับเรื่อง';
        }

        else if ($v['type'] == 4 && $user_level == 'root') {
            $status = 'หน่วย รับเรื่อง - กำลังดำเนินการ';
        }

        else if ($v['type'] == 4 && $user_level == 'unit') {
            $status = 'รับเรื่องแล้ว - กำลังดำเนินการ';
        }

        else if ($v['type'] == 5) {
            $status = 'หน่วย ยุติเรื่อง';
        }

        else if ($v['type'] == 6 && $user_level == 'root') {
            $status = 'ส่งกลับให้หน่วย ดำเนินการแก้ไข';
        }

        else if ($v['type'] == 6 && $user_level == 'unit') {
            $status = 'ศูนย์ส่งกลับ - ให้แก้ไข';
        }

        else if ($v['type'] == 7) {
            $status = 'ยุติเรื่อง';
        }

        else if ($v['type'] == 8) {
            $status = 'เสร็จสิ้น';
        }

        $sheet->getCell('T'.$numrow)->setValue($status);
        $numrow++;
      }

      $numrow = $numrow - 1;


      $sheet->getStyle('B5:E'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      ]);

      $sheet->getStyle('M5:M'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      ]);

      $sheet->getStyle('O5:P'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      ]);

      $sheet->getStyle('S5:T'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      ]);

      $sheet->getStyle('B5:T'.$numrow)->getAlignment()->applyFromArray([
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
          'wrapText' => true,
      ]);

      $styleArray = [
          'borders' => [
              'allBorders' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                  'color' => ['argb' => '000'],
              ],
          ],
      ];
      $sheet->getStyle('B4:T'.$numrow)->applyFromArray($styleArray);

      $sheet->getColumnDimension('A')->setWidth('3');
      $sheet->getColumnDimension('B')->setWidth('8');
      $sheet->getColumnDimension('C')->setWidth('15');
      $sheet->getColumnDimension('D')->setWidth('15');
      $sheet->getColumnDimension('E')->setWidth('18');
      $sheet->getColumnDimension('F')->setWidth('12');
      $sheet->getColumnDimension('G')->setWidth('22');
      $sheet->getColumnDimension('H')->setWidth('20');
      $sheet->getColumnDimension('I')->setWidth('28');
      $sheet->getColumnDimension('J')->setWidth('40');
      $sheet->getColumnDimension('K')->setWidth('40');
      $sheet->getColumnDimension('L')->setWidth('28');
      $sheet->getColumnDimension('M')->setWidth('10');
      $sheet->getColumnDimension('N')->setWidth('20');
      $sheet->getColumnDimension('O')->setWidth('15');    
      $sheet->getColumnDimension('P')->setWidth('15');    
      $sheet->getColumnDimension('Q')->setWidth('40');    
      $sheet->getColumnDimension('R')->setWidth('28');    
      $sheet->getColumnDimension('S')->setWidth('15');    
      $sheet->getColumnDimension('T')->setWidth('18');    
      $writer = new Xlsx($spreadsheet);

      $path = public_path();
      $file_name = 'report_summary.xlsx';
      $writer->save($path.'/export/'.$file_name);

      return response()->json([
          'status' => 200,
          'message' => 'export successfully.',
          'data' => [
              'filename' => $file_name,
          ]
      ], 200);
    }else{
      abort(404);
    }
  }

  public function severity(Request $request){
    if(request()->ajax()) {
      $spreadsheet = new Spreadsheet();
      $spreadsheet->getDefaultStyle()->getFont()->setName('TH SarabunPSK');
      $spreadsheet->getDefaultStyle()->getFont()->setSize(16);
      $spreadsheet->getActiveSheet()->freezePane('A5');


      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('รายงาน');

      $sheet->getCell('A1')->setValue('รายงานแบ่งตามระดับความรุนแรง');
      $sheet->mergeCells('A1:E1');
      if($request->type == 1){
        if(is_array($request->day) &&
            count($request->day) === 2 &&
            !empty($request->day[0]) &&
            !empty($request->day[1])){

            $dt = Carbon::parse($request->day[0])->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $show_date = Helper::getDateThaiFull($date);

            if($request->day[0] == $request->day[1]){
                $sheet->getCell('A2')->setValue('ประจำวันที่ '.$show_date);
            }else{
              $dt_end = Carbon::parse($request->day[1])->setTimezone('Asia/Bangkok');
              $date_end = $dt_end->format('Y-m-d');
              $show_date_end = Helper::getDateThaiFull($date_end);
              $sheet->getCell('A2')->setValue('ระหว่างวันที่ '.$show_date.' ถึง '.$show_date_end);
            }

        }else{
            $dt = Carbon::parse($request->day)->setTimezone('Asia/Bangkok');
            $date = $dt->format('Y-m-d');
            $show_date = Helper::getDateThaiFull($date);
            $sheet->getCell('A2')->setValue('ประจำวันที่ '.$show_date);
        }
      }
      if($request->type == 2){
        $show_month = Helper::getMonThfull($request->month);
        $sheet->getCell('A2')->setValue('ประจำเดือน '.$show_month.' '.$request->year);
      }
      if($request->type == 3){
        $sheet->getCell('A2')->setValue('ประจำปี '.$request->year);
      }
      $sheet->mergeCells('A2:E2');

      $sheet->getCell('B4')->setValue('ลำดับ');
      $sheet->getCell('C4')->setValue('ระดับความรุนแรง');
      $sheet->getCell('D4')->setValue('จำนวนเรื่อง');
      $sheet->getCell('E4')->setValue('ร้อยละ');


      $sheet->getStyle('A1:E4')->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);
      $sheet->getStyle('A1:E4')->getFont()->setBold(true);
      // $sheet->getStyle('A1:N1')->getFont()->setSize(18);
      $total = count($request->item);
      $row_sum = $total+5;

      $numrow = 5;
      foreach($request->item as $k => $v){
        $num = $k+1;
        $sheet->getCell('B'.$numrow)->setValue($num);
        $show_name = 'ระดับ '.$num.' '.$v['name'];
        $sheet->getCell('C'.$numrow)->setValue($show_name);
        $sheet->getCell('D'.$numrow)->setValue($v['count']);
        if($v['count'] > 0){
          $sheet->setCellValue('E' . $numrow, '=ROUND(D'.$numrow.'/D'.$row_sum.'* 100, 2) & "%"');
        }
        $numrow++;

      }

      $sheet->getCell('B'.$numrow)->setValue('รวม');
      $sheet->mergeCells('B'.$numrow.':C'.$numrow);
      $sheet->getStyle('B'.$numrow.':C'.$numrow)->getFont()->setBold(true);
      $sub_end = $numrow - 1;
      $sheet->setCellValue('D' . $numrow, '=SUM(D5:D'.$sub_end.')');


      $sheet->getStyle('B5:B'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);

      $sheet->getStyle('D5:E'.$numrow)->getAlignment()->applyFromArray([
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true,
      ]);

      $styleArray = [
          'borders' => [
              'allBorders' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                  'color' => ['argb' => '000'],
              ],
          ],
        ];
      $sheet->getStyle('B4:E'.$numrow)->applyFromArray($styleArray);


      $sheet->getColumnDimension('A')->setWidth('3');
      $sheet->getColumnDimension('B')->setWidth('8');
      $sheet->getColumnDimension('C')->setWidth('55');
      $sheet->getColumnDimension('D')->setWidth('20');
      $sheet->getColumnDimension('E')->setWidth('20');


      $writer = new Xlsx($spreadsheet);

      $path = public_path();
      $file_name = 'report_severity.xlsx';
      $writer->save($path.'/export/'.$file_name);

      return response()->json([
          'status' => 200,
          'message' => 'export successfully.',
          'data' => [
              'filename' => $file_name,
          ]
      ], 200);
    }else{
      abort(404);
    }
  }
}