<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>พิมพ์รายละเอียด ร้องเรียน-ร้องทุกข์</title>

	<style>

    @font-face {
      font-family: THSarabunNew;
      src: url("{{public_path('fonts/THSarabunNew/THSarabunNew.ttf')}}");
  }

  @font-face {
      font-family: THSarabunNew;
      src: url("{{public_path('fonts/THSarabunNew/THSarabunNew Bold.ttf')}}");
      font-weight: bold;
  } 
	@page { margin: 32pt 20pt 80pt 20pt; }
		body{
			font-family: 'THsarabunNew';
			font-size: 16pt;
		}
		.line-h6{line-height: 6pt; }
		.line-h8{line-height: 8pt; }
		.line-h10{line-height: 10pt; }
		.line-h12{line-height: 12pt; }
		.line-h14{line-height: 14pt; }
		.line-h16{line-height: 16pt; }
		.line-h18{line-height: 18pt; }
		.line-h20{line-height: 20pt; }
		.line-h22{line-height: 22pt; }
		.line-h24{line-height: 24pt; }
		.line-h26{line-height: 26pt; }
		h2{ font-size: 20pt; font-weight: bold;}
		h3{ font-size: 16pt; font-weight: bold; margin-top:0px;  margin-bottom:8pt;}
		p{ font-size: 16pt; font-weight: bold;}
		.text-center{ text-align:center}
		.text-right{ text-align:right}
		.col-head{background-color:#aaacae; padding-left:10pt;padding-top:-6px;}
		.detail{ margin-top: 0px; margin-left:10pt;}
		.detail .title{ font-weight: bold; }

		.mt-2{ margin-top:2pt}
		.mt-10{ margin-top:10pt}
		.mt-12{ margin-top:12pt}
		.mt-14{ margin-top:14pt}
		.pl-4{ margin-left:14pt; font-size:14pt;}
		.font-bold{ font-weight: bold; }
		.page_break { page-break-before: always; }
		.text-status{ text-align:center; margin-bottom: 20pt; font-size: 22pt;}
	</style>
</head>
<body>

	<table width="100%">
		<tr>
			<td width="15%" style="text-align:center;">
      <img src="{{ public_path('images/logo/logo_dmh.jpg') }}" width="75px">

			</td>
			<td width="40%" style="font-size:26px; font-weight: bold;vertical-align: top;">
				<div style="maring-top:-20px;">ระบบบริหารจัดการข้อคิดเห็น</div>
				<div style="line-height: 12pt;">ข้อร้องเรียน กรมสุขภาพจิต</div>
			</td>
			<td width="5%"></div>
			<td width="40%">
				<div style="border: 1px solid;width: 90%;padding:16px 10px; ">
					<div class="line-h10">เลขที่ร้องเรียน <b>{{ $data->code }}</b></div>
					<div class="line-h14">วันที่รับเรื่องร้องเรียน {{ $data->show_date }}</div>
					<div class="line-h10">เวลา {{ substr($data->created_at, 11,5)}} น.</div>
				</div>
			</td>
		</tr>
	</table>

    <div style="width:100%; margin-top: -14px;">
        <h2 class="text-center">การร้องเรียน - ร้องทุกข์</h2>
        <div class="col-head" >
            <h3>1. ข้อมูลผู้ร้องเรียน</h3>
        </div>

				@if($data->concealed == 1)
				<div class="detail text-status">ปกปิดข้อมูลผู้ร้องเรียน</div>
				@else
		<div class="detail">
				<div class="">
					<span class="title">ชื่อ - นามสกุล ผู้ร้องเรียน : </span>
					<span style="margin-left:20pt;">{{ $data->fname}} {{ $data->lname }}</span>
				</div>

				<div class="">
					<span class="title">เพศ : </span>
					<span style="margin-left:20pt;">
						@if($data->gender == 1) ชาย @endif
						@if($data->gender == 2) หญิง @endif
						@if($data->gender == 3) LGBTQ+ @endif
					</span>
					
					<span class="title" style="margin-left:60pt;">อาชีพ : </span>
					<span style="margin-left:20pt;">{{ $data->work }}</span>
					
				</div>

				<div class="line-h16">
					<span class="title">ที่อยู่ : </span>
					<span style="margin-left:20pt;">{{ $data->address }}</span>
				</div>

				<div class="">
				<span class="title">โทรศัพท์ : </span>
				<span style="margin-left:20pt;">{{ $data->tel }}</span>

				<span class="title" style="margin-left:20pt;">มือถือ : </span>
				<span style="margin-left:20pt;">{{ $data->phone }}</span>
				<span class="title" style="margin-left:20pt;">Email : </span>
				<span style="margin-left:20pt;">{{ $data->email }}</span>
			
			</div>
			</div>

				@endif



		<div class="col-head">
            <h3>2. ข้อมูลเกี่ยวกับเรื่องร้องเรียน</h3>
        </div>
		
		<div class="detail">
			
			<div class="">
				<span class="title">ช่องทางการร้องเรียน : </span>
				<span style="margin-left:20pt;">{{ $data->method_name }}</span>
		
				<span class="title" style="margin-left:20pt;">ร้องเรียนถึง : </span>
				<span style="margin-left:20pt;">{{ $data->unit_name }}</span>
			</div>
			<div class="">
				<span class="title">ประเภทการร้องเรียน : </span>
				<span style="margin-left:20pt;">{{ $data->type_name }}</span>
        @if($data->sub_name)
          <span class="title" style="margin-left:15pt;">ประเภทย่อย : </span>
					<span style="margin-left:20pt;">{{ $data->sub_name }}</span>
        @endif
			</div>
			<div class="">
				<span class="title">ร้องเรียนบุคคล : </span>
				<span style="margin-left:20pt;">{{ $data->person_name }}</span>
			</div>
			<div class="">
				<span class="title">เรื่องที่ร้องเรียน : </span>
				<span style="margin-left:20pt;">{{ $data->name }}</span>
			</div>
			<div class="line-h16">
				<span class="title">รายละเอียดเรื่องที่ร้องเรียน : </span>
				<span style="margin-left:20pt;">
          {!! nl2br(e($data->description)) !!}
        </span>
			</div>
			<div class="line-h16">
				<span class="title">สิ่งที่ต้องการให้แก้ไข : </span>
				<span style="margin-left:20pt;">{!! nl2br(e($data->improvement)) !!}</span>
			</div>
			<div class="" style="margin-bottom:20pt;">
				<span class="title">เอกสารประกอบ : </span>
				<span style="margin-left:20pt;">
			
				</span>
				<span class="title" style="margin-left:20pt;">หน่วยกำกับดูแล : </span>
				<span style="margin-left:20pt;"></span>
			</div>
		</div>


</body>
</html>