<?php

namespace App\Services;

class MyAngpao
{
  public static function redeem($mobile, $voucherLink, $userAgent = '')
  {
    $voucherId = self::extractVoucherId($voucherLink);
    $url = "https://gift.truemoney.com/campaign/vouchers/{$voucherId}/redeem";

    $curl = self::createCurl($url, $userAgent);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
      'mobile' => $mobile,
      'voucher_hash' => $voucherId
    ]));
    $resp = curl_exec($curl);
    curl_close($curl);

    return json_decode($resp);
  }

  public static function verify($mobile, $voucherLink, $userAgent = '')
  {
    $voucherId = self::extractVoucherId($voucherLink);
    $url = "https://gift.truemoney.com/campaign/vouchers/{$voucherId}/verify?mobile={$mobile}";

    $curl = self::createCurl($url, $userAgent);
    $resp = curl_exec($curl);
    curl_close($curl);

    return json_decode($resp);
  }

  private static function createCurl($url, $userAgent = '')
  {
    $userAgent = $userAgent ?: self::getDefaultUserAgent();

    $curl = curl_init($url);
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "User-Agent: {$userAgent}"
      ],
      CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_3,
      CURLOPT_SSL_VERIFYPEER => false, // debug only
      CURLOPT_SSL_VERIFYHOST => false  // debug only
    ]);

    return $curl;
  }

  private static function extractVoucherId($voucher)
  {
    return explode("?v=", $voucher)[1] ?? '';
  }

  private static function getDefaultUserAgent()
  {
    return 'Super Idol的笑容 都没你的甜 八月正午的阳光 都没你耀眼 热爱 105 °C的你 滴滴清纯的蒸馏水 你不知道你有多可爱';
  }

  public static function getmoney($mobile, $voucherLink, $mok = '', $userAgent = '')
  {   
    // ใช้เพื่อทดลอง
    if ($mok >= 1 && $mok <= 10000000) {
      return $mok;
    }

    $voucherId = self::extractVoucherId($voucherLink);
    $url = "https://gift.truemoney.com/campaign/vouchers/{$voucherId}/redeem";

    $curl = self::createCurl($url, $userAgent);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
      'mobile' => $mobile,
      'voucher_hash' => $voucherId
    ]));
    $resp = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($resp);
    $money = 0;
    if (isset($result->data->my_ticket->amount_baht)) {
      $money = $result->data->my_ticket->amount_baht;
    }
    return $money;
  }
}

// $result = Angpao::redeem('0812345678', 'https://gift.truemoney.com/campaign/?v=1234567890abcdef');
// var_dump($result); 
//      if (isset($result->data->my_ticket->amount_baht)) {
//          $additional_balance = $result->data->my_ticket->amount_baht;
//      }  

// $check = Angpao::verify('0812345678', 'https://gift.truemoney.com/campaign/?v=1234567890abcdef');
// var_dump($check);

// $getmoney = Angpao::getmoney('0812345678', 'https://gift.truemoney.com/campaign/?v=1234567890abcdef'); 
// $getmoney ได้ยอด money หรือ 0
