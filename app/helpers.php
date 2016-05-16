<?php

	/*
	* @Push Notification Android
	*/
	function androidPushNotification($data) {
       
        $headers = array("Content-Type:" . "application/json", "Authorization:" . "key=" . Config::get('constants.API_ACCESS_KEY'));
          
		//$data=json_encode($data);
		$data=json_encode($data,true);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);       
        $response = curl_exec($ch);
        curl_close($ch);
        $pos = strpos($response, '"success":1');
        if ($pos == true)
            return $response;
        else
            error_log($response);
        return $response;
    }


	/*
	* @Push Notification I-phone
	*/
 	function iphonePushNotification($data) {

        $deviceToken = $data['token'];
        // Put your private key's passphrase here:
        $passphrase = Config::get('constants.passphrase');
        $pem_url=Config::get('constants.ios_pem_file_url');    
        // print_r($deviceToken."-".url($pem_url));die();
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $pem_url);
        stream_context_set_option($ctx, 'ssl', 'passphrase',  $passphrase);

        // Open a connection to the APNS server
//        $fp = stream_socket_client(
//                'ssl://gateway.sandbox.push.apple.com:2195', $err,
//                $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        echo $fp;

        if (!$fp) {
            // exit("Failed to connect: $err $errstr" . PHP_EOL);
            return false;
        }
        //echo 'Connected to APNS' . PHP_EOL;
        // Create the payload body
        $body['aps'] = array(
            'alert' => $data['message'],
            'sound' => 'default',
           // 'msgId' =>$data['msgId'],
            //'chatType' => $chatType,
            //'sname' => $senderName
        );
    // $body['aps']['sname']=$senderName;

        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        // Close the connection to the server
        fclose($fp);
        if (!$result) {
            return false;
            //echo 'Message not delivered' . PHP_EOL;
        } else {
            return true;
            //echo 'Message successfully delivered' . PHP_EOL;
        }

    }

?>