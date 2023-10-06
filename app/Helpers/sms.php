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

function sendFCM($fcm, $body)
{
    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array(
        'registration_ids' => array(
            $fcm
        ),
        'notification' => array(
            "title" => "Bitaqty - بطاقتي",
            "body" => $body
        )
    );
    $fields = json_encode($fields);

    $headers = array(
        'Authorization: key=' . "AAAAFUoiVLs:APA91bGEOeZv7UNsznHmXsC4OUlwDQpjEUjsKmiALt5W3sgjqJLR2idjwVZUSNYMYNQ0NxazfyxkJgEzZw5dKrwhWX78DiFroqMjIcoIoUVC6AXBQi_nh2645Z6BURWQGMI2XeVZaq6b",
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    $result = curl_exec($ch);
    curl_close($ch);
}
