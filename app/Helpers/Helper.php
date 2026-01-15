<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Http;
use App\Models\Province;
use App\Models\District;
use App\Models\Subdistrict;

class Helper
{

  public static function checkIdcare(){
    return [
      '1234567890123',
      '1111111111111',
      '2222222222222',
      '3333333333333',
      '0123456789012',
      '9999999999999',
      '8888888888888',
      '1212121212121',
      '9876543210987',
      '5555555555555',
      '6666666666666',
      '7777777777777',
      '0000000000000',
      '1234123412341',
      '1231231231231',
    ];

  }

  public static function encryptData($data) {
      $key = env('KEY_CRYPT', 'ABC1234');
      $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
      $iv = openssl_random_pseudo_bytes($ivlen);
      $ciphertext_raw = openssl_encrypt($data, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
      $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
      $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
      return $ciphertext;
  }

  public static function decryptData($ciphertext) {
    $key = env('KEY_CRYPT', 'ABC1234');
    $c = base64_decode($ciphertext);
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len=32);
    $ciphertext_raw = substr($c, $ivlen+$sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
      if (hash_equals($hmac, $calcmac)) {
          return $original_plaintext;
      } else {
          return false;
      }
  }

  public static function getAddress($province_id,$district_id,$subdistrict_id,$address,$zipcode){ 
    return $address . ($province_id==1 ? '   ' : '   ต.') .
      Subdistrict::where('id', $subdistrict_id)->value('name') . ($province_id==1 ? '' : ' อ.') .
      District::where('id', $district_id)->value('name') . ($province_id==1 ? '' : ' จ.') .
      Province::where('id', $province_id)->value('name') . ' ' .
      $zipcode;
  }

  public static function convoretHtml($text){
    return str_replace(["\r\n", "\n", "\r"], "<br/>", $text);

  }

  public static function sendTelegramMessage($token, $chatId, $message) {


    Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
        'chat_id' => $chatId,
        'text'    => $message,
    ]);


    return true;
  }

  public static function getDateThaiFull($data){
        if($data == '0000-00-00' || !$data){
            return '';
        }
		$year = substr($data,0,4);
		$mon = substr($data,5,2);
		$date = substr($data,8,2);

		$y = $year+543;
		$d = $date+1-1;
		$m = self::getMonThfull($mon);
        if($d < 10){
            $day = '0'.$d;
        }else{
            $day = $d;
        }
		
		return $day." ".$m." ".$y;
	}

  public static function getDateThaiSubYear($data){
        if($data == '0000-00-00' || !$data){
            return '';
        }
		$year = substr($data,0,4);
		$mon = substr($data,5,2);
		$date = substr($data,8,2);

		$y = substr($year+543,2,2);
		$d = $date+1-1;
		$m = self::getMonTh($mon);
        if($d < 10){
            $day = '0'.$d;
        }else{
            $day = $d;
        }
		
		return $day." ".$m." ".$y;
	}

  public static function getMonTh($mon){
		$c_m = $mon-1;

		$c_mon = array("ม.ค.", "ก.พ.", "มี.ค." ,"เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$m = $c_mon[$c_m];

		return $m;
	}

	public static function getMonThfull($mon){
		$c_m = $mon-1;

		$c_mon = array("มกราคม", "กุมภาพันธ์", "มีนาคม" ,"เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		$m = $c_mon[$c_m];

		return $m;
	}
}
?>