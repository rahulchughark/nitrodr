<?php

$url = "https://media.smsgupshup.com/GatewayAPI/rest";

    $params = [
        "userid"          => "2000249837",
        "password"        => "*uh7P4*2",
        "send_to"         => "7065846828", // Recipient's WhatsApp number
        "v"               => "1.1",
        "format"          => "json",
        "msg_type"        => "IMAGE",
        "method"          => "SENDMEDIAMESSAGE",
        "caption"         => "Hi Rahul Chugh,\n\nPlease find below order status Test",
        "media_url"       => "https://ict360.com/ict-new/public/Front/img/ict-logo.png",
        "isTemplate"      => "true",
        "buttonUrlParam"  => "https://ict360.com/"
    ];



    $queryString = http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . "?" . $queryString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

            if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch);
            } else {
            $inviteTemplate = $url.'?'.$queryString;
            
            echo "Response: " . $response;
        }

    curl_close($ch);