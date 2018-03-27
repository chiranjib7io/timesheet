<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
 
 // App Controller Start
class AppController extends Controller
{
    // Extension Controller
    var $uses = array(
        'User',
        'LogRecord',
        'Country',
        'Project',
        'UsersProject',
        'Timesheet',
        'TimesheetDetail',
        'Expense',
        'Expensetype',
        'Expentry',
        'Verifylog'
        );
    // added the debug toolkit
    // sessions support
    // authorization for login and logut redirect
    public $components = array(
        //'DebugKit.Toolbar',
        'Session',
        'Email',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'dashboards', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
            'authError' => 'You must be logged in to view this page.',
            'loginError' => 'Invalid Username or Password entered, please try again.'),
        'Micro'
        );	
	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Number','Slt');
    // only allow the login controllers only
    public function beforeFilter()
    {
        $this->Auth->allow('login');
        //echo Configure::version();;
    }
    public function beforeRender()
    {
        $this->set('userData', $this->Auth->user());  
    }
    public function isAuthorized($user) {
        // Here is where we should verify the role and give access based on role
        return true;
    }
    // This function is for Detect the device of the user
    function detectDevice() {
        $userAgent = $_SERVER["HTTP_USER_AGENT"];
        $devicesTypes = array(
            "computer" => array(
                "msie 10",
                "msie 9",
                "msie 8",
                "windows.*firefox",
                "windows.*chrome",
                "x11.*chrome",
                "x11.*firefox",
                "macintosh.*chrome",
                "macintosh.*firefox",
                "opera"),
            "tablet" => array(
                "tablet",
                "android",
                "ipad",
                "tablet.*firefox"),
            "mobile" => array(
                "mobile ",
                "android.*mobile",
                "iphone",
                "ipod",
                "opera mobi",
                "opera mini"),
            "bot" => array(
                "googlebot",
                "mediapartners-google",
                "adsbot-google",
                "duckduckbot",
                "msnbot",
                "bingbot",
                "ask",
                "facebook",
                "yahoo",
                "addthis"));
        foreach ($devicesTypes as $deviceType => $devices) {
            foreach ($devices as $device) {
                if (preg_match("/" . $device . "/i", $userAgent)) {
                    $deviceName = $deviceType;
                }
            }
        }
        return ucfirst($deviceName);
    }
    function getLastQuery()
    {
        $dbo = ConnectionManager::getDataSource('default');
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);
        return $lastLog['query'];
    }
	function create_account_number() {
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);
        $dec_hex = dechex($a_dec * 1000000);
        $sec_hex = dechex($a_sec);
        $this->ensure_length($dec_hex, 5);
        $this->ensure_length($sec_hex, 6);
        $guid = "";
        $guid .= $dec_hex;
        $guid .= $this->create_guid_section(3);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= $this->create_guid_section(6);
        return $guid;
    }
    function ensure_length(&$string, $length) {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, "0");
        } else if ($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }
    function create_guid_section($characters) {
        $return = "";
        for ($i = 0; $i < $characters; $i++) {
            $return .= dechex(mt_rand(0, 15));
        }
        return $return;
    }
    // Genaral function for making a JSON file
    function prepare_json($response, $remove_null = 1)  {
        $json = json_encode($response, true);
        if ($remove_null == 1) {
            $json = preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $json);
        }
        /* disconnect with db */
        //App::import('Model', 'ConnectionManager');
        //$ds = ConnectionManager::getDataSource('default');
        //$ds->disconnect();
        return $json;
    }  
	public function get_all_rate($user_id){
        $rate_data = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
        if(!empty($rate_data))
            return $rate_data['User'];
        else
            return $rate_data;
    }
    function calculate_cost($times_result){
        $res_arr = array();
        $total_cost = $total_earned = $total_normal_charge = $total_ot_charge = $total_exp = $total_hours = $total_extra_charge = 0;
        foreach($times_result as $time_data){
            //pr($time_data);echo '<hr>';
            $normal_hours = $ot_hours = $client_rate = $normal_rate = $ot_rate = $duration = $total_break = $exp = $extra_hours = 0;
            $normal_charge = $ot_charge = $client_charge = $extra_charge = 0;
        	$client_rate = number_format(!empty($time_data['Timesheet']['client_rate'])?$time_data['Timesheet']['client_rate']:0,2);
            $normal_rate = number_format(!empty($time_data['Timesheet']['normal_rate'])?$time_data['Timesheet']['normal_rate']:0,2);
            $ot_rate = number_format(($time_data['Timesheet']['ot_eligible']==1)?$time_data['Timesheet']['ot_rate']:0,2);
            $normal_hours = !empty($time_data['Timesheet']['normal_hours'])?$time_data['Timesheet']['normal_hours']:0;
            if(!empty($time_data['TimesheetDetail'])){
                foreach($time_data['TimesheetDetail'] as $rowsheet){ 
                        $duration += $rowsheet['decimal_duration'];   
                        $total_break += $rowsheet['break_hours'];    
                }
            }
            $duration = $duration-$total_break;
            if(($time_data['Timesheet']['ot_eligible']==1)&&($duration>=$normal_hours)){
                if($duration>12){
                    $extra_hours = $duration-12;
                    $extra_charge = $extra_hours*($normal_rate*2);
                    $ot_hours = $duration - $normal_hours-$extra_hours;
                    $normal_charge = $normal_hours*$normal_rate;
                    $ot_charge = $ot_hours*$ot_rate;
                }else{
                    $extra_hours = 0;
                    $extra_charge = 0;
                    $ot_hours = $duration - $normal_hours;
                    $normal_charge = $normal_hours*$normal_rate;
                    $ot_charge = $ot_hours*$ot_rate;
                }
            }else{
                $extra_hours = 0;
                $extra_charge = 0;
                $ot_hours = 0;
                $normal_charge = $duration*$normal_rate;
                $ot_charge = 0;
            }      
            $client_charge = $duration*$client_rate;
            $cost = ($normal_charge+$ot_charge+$extra_charge);
            $total_cost += $cost;
            $total_earned += $client_charge;
            $total_normal_charge += $normal_charge;
            $total_ot_charge += $ot_charge;
            $total_hours += $duration;  
            $total_extra_charge += $extra_charge;
        }
        $res_arr['total_cost'] = $total_cost;
        $res_arr['total_hours'] = $total_hours;
        $res_arr['total_ot_hours'] = $ot_hours;
        $res_arr['total_ot_charge'] = $total_ot_charge;
        $res_arr['total_earned'] = $total_earned;
        $res_arr['total_normal_charge'] = $total_normal_charge;
        $res_arr['total_extra_charge'] = $total_extra_charge;
        return $res_arr; 
    }
}
// App controller END
?>