<?php

/**
 * ServiceResponse class
 */

     /**
         * Build the message.
         *
         * @return $this
         */

     function build(string $messageSMS = "", $configSms, $numberTo)
    {
        try {
            $post['to'] = $numberTo;
            $post['text'] = $messageSMS;
            $post['from'] = $configSms['from'];
            $user = $configSms['user'];
            $password = $configSms['pass'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://sms.ubicarme.com/Api/rest/message");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
            curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
            "Accept: application/json",
            "Authorization: Basic ".base64_encode($user.":".$password)));
            $result = curl_exec ($ch);
            echo $result;

            return $result;
        }catch (Exception $e){
                    echo $e;
                }
    }
        $configSms['user'] = 'Visof';
        $configSms['pass'] = 'Maga300$';
        $configSms['from'] = '573244220848';
        $to = array('573244220848');

        build('hola envio mensaje2', $configSms, $to);


