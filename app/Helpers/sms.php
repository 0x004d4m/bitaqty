<?php

function sendOTPSMS($phonenumber, $otp_code)
{
    $curl = curl_init();

    $from = "Bitaqty";
    $to = $phonenumber;
    $message = "Admin login OTP code for " . $phonenumber . " is : " . $otp_code;

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://platform.releans.com/api/v2/message",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "sender=$from&mobile=$to&content=$message",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer 20e1a42c138a23d138519ec67e1381b1"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
}
