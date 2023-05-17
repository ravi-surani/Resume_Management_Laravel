<?php

namespace App;

use App\Http\Controllers\LogsController;
use App\Models\TimesheetLog;
use App\PayRoll\Employee\EmployeeModel;
use Artisan;

class SendSMS
{
    public static function instance()
    {
        return new SendSMS();
    }

    function PostRequest($url, $_data)
    {
        // convert variables array to string:
        $data = array();
        foreach ($_data as $n => $v) {
            $data[] = "$n=$v";
        }
        $data = implode('&', $data);
        // format --> test1=a&test2=b etc.
        // parse the given URL
        $url = parse_url($url);
        if ($url['scheme'] != 'https') {
            die('Only HTTP request are supported !');
        }
        // extract host and path:
        $host = $url['host'];
        $path = $url['path'];
        //echo $host;exit;
        // open a socket connection on port 80
        $fp = fsockopen($host, 80);
        // send the request headers:
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: " . strlen($data) . "\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data);
        $result = '';
        while (!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 128);
        }
        //echo $result;
        // close the socket connection:
        fclose($fp);
        // split the result header from the content
        $result = explode("\r\n\r\n", $result, 2);
        $header = isset($result[0]) ? $result[0] : '';
        $content = isset($result[1]) ? $result[1] : '';
        // return as array:
        return array($header, $content);
    }

    function sendmsg($mobileno, $message,$template_name='',$params=array(),$button='',$countrycode='no')
    {
        if (!empty($mobileno) && $template_name!='') {
            return $this->sendwhatsapp($mobileno, $template_name,$params,$message,$button,$countrycode);
        }
    }

    public function sendwhatsapp($mobileno,$template_name, $params =array(),$message,$button='',$countrycode)
    {
        // return $mobileno;
        //return 'success';
        $whatsapp_token = config('app.whatsapp_token');
        $res = array();
        $mobile_no = $mobileno;
        if($countrycode=='no') {
            if (strlen($mobileno) == 10) {
                $mobile_no = "91" . $mobileno;
            }
        }
//        foreach ($mobileno as $value) {
        $buttonfield='';
        if($button!='') {
            $buttonfield = ',
                    {
                        "type": "button",
                        "sub_type" : "url",
                        "index": "0",
                        "parameters": [
                          {
                            "type": "text",
                            "text": "'.$button.'"
                          }
                        ]
                      }';
        }
            $fields = '{
                "messaging_product":"whatsapp",
                "to" : "' . str_replace('+', '', $mobile_no).'",
                "type" : "template",
                "template" : {"name" : "'.$template_name.'", "language" : {"code" : "en_US"},
                "components" : [
                    {
                        "type" : "body",
                        "parameters" : '.json_encode($params).'
                    }'.$buttonfield.'
                ]
            } }';

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://graph.facebook.com/v12.0/111259231595100/messages',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$fields,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.$whatsapp_token
                ),
            ));

           $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $res['error'] = "cURL Error #:" . $err;
            } else {
                $res['success'] = $response;
            }

            // $employee = EmployeeModel::where('employeewhatsappnumber',$mobileno)->get()->first();

            // $timesheet_log = new TimesheetLog();
            // $timesheet_log->module = 'Whatsapp';
            // $timesheet_log->module_id = 0;
            // $timesheet_log->user_id = (!empty($employee)?$employee->employeeid:0);
            // $timesheet_log->action = 'add';
            // $timesheet_log->before_action = $fields;
            // $timesheet_log->after_action = json_encode($res);
            // $timesheet_log->other = $message;
            // $timesheet_log->save();
            // dd($response);
        return $res;
    }

    public function getWhatsappToken(){
        $handelId = '285824038230163';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://socialcrm.weybee.in/api/get-token?handleId='.$handelId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ));


        $response = curl_exec($curl);
        if(!empty($response)){
            $path = base_path('.env');

            if (file_exists($path)) {
                file_put_contents($path, str_replace('WHATSAPP_TOKEN='.config('app.whatsapp_token'), 'WHATSAPP_TOKEN='.$response, file_get_contents($path)
                ));
            }
        }

        $err = curl_error($curl);

        curl_close($curl);

        Artisan::call('config:cache');
    }
}
