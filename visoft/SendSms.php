<?php
namespace Modules\CsConvivamos\Libs\ServiceResponse;

/**
 * ServiceResponse class
 */
class SendSms
{
     /**
         * Build the message.
         *
         * @return $this
         */

    public function build(string $messageSMS = "", $configSms, $numberTo)
    {
        $post['to'] = array($numberTo);
        $post['text'] = $messageSMS;
        $post['from'] = $configSms['to'];
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

        return $result;
    }
}
