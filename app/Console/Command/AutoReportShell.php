<?php
/**
 * AppShell file
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
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
date_default_timezone_set('Asia/Kolkata');
App::uses('Shell', 'Console');
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('CakeTime', 'Utility');
App::uses('CakeLog', 'Utility');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class AutoReportShell extends AppShell {
    
    
    public $uses = array('RawEntry','Kendra','WalletTransaction','IncomeExpenditure');
    
        /**
     * Controller class
     * @var Controller
     */
    	public $Controller;
    /**
     * EmailComponent
     * @var EmailComponent
     */
    	public $Email;
        
 

    
    public function daily_report(){

        $this->layout="ajax";
        $this->autoRender = false;
        
        
        /** Data Preapre  ******************************************************************/
        $msg = "";
        $day = date("Y-m-d");
        $wk_day = date('l', strtotime($day));
        $data['date'] = $day;
        $data['day_name'] = $wk_day;
        
        if(trim(date('w', strtotime($day))==0) || trim(date('w', strtotime($day))==6)){
            break;
            die;
        }
        /** For Kendra list **/
        $this->RawEntry->bindModel(
            array('belongsTo' => array(
                    'Kendra' => array(
                        'className' => 'Kendra',
                        'foreign_key' => 'kendra_id'
                    )
                )
            )
        );
        $entry_list = $this->RawEntry->find('all',array('conditions'=>array('RawEntry.date'=>$day)));		        	
        if(empty($entry_list)){
            
            $msg = "<p></p><p>Data has not been updated on $day</p><p></p>";
            //$entry_list = $this->Kendra->find('all',array('conditions'=>array('Kendra.payment_day'=>$wk_day,'Kendra.branch_id'=>'1')));		        
        }else{
            $rwe_list = $this->RawEntry->find('all',array('fields' => array('sum(RawEntry.no_of_member) as members'),'conditions'=>array('RawEntry.date'=>$day)));
            $data['kendra_list'] = $entry_list;
            $data['members'] = !empty($rwe_list[0][0]['members'])?$rwe_list[0][0]['members']:0;
            $data['kendra_number'] = count($entry_list);
            
            /** For Opening balance **/
            if(trim(date('w', strtotime($day))==1)){
                $wallet_date = date("Y-m-d",strtotime("$day -3 days"));
            }else{
                $wallet_date = date("Y-m-d",strtotime("$day -1 days"));
            }
            
            $op_wdata=$this->WalletTransaction->find("first",array("conditions"=>array("WalletTransaction.wallet_id"=>1,"WalletTransaction.update_date"=>$wallet_date)));
            $data['opening'] = !empty($op_wdata['WalletTransaction']['closing_amount'])?$op_wdata['WalletTransaction']['closing_amount']:0;
            /** For Closing balance ************/
            $op_wdata=$this->WalletTransaction->find("first",array("conditions"=>array("WalletTransaction.wallet_id"=>1,"WalletTransaction.update_date"=>$day)));
            $data['closing'] = !empty($op_wdata['WalletTransaction']['closing_amount'])?$op_wdata['WalletTransaction']['closing_amount']:0;
            
            /** For Interest collection ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>7,'IncomeExpenditure.transaction_date'=>$day)));
            $data['interest_collection'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For Principal collection ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>2,'IncomeExpenditure.transaction_date'=>$day)));
            $data['principal_collection'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For bank deposit ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(debit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>12,'IncomeExpenditure.transaction_date'=>$day)));
            $data['bank_deposit'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For Only Office expenses ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(debit_amount) as exp'), 'conditions' => array('IncomeExpenditure.transaction_date'=>$day,'NOT'=>array('IncomeExpenditure.account_ledger_id'=>[4,5,6,12]))));
            $data['office_exp'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For email body Preapre Start **************************************************************************/
            $disburse = 0;
            
            $msg .="<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">";
            $msg .= '<tr><td><h4 style="font-size:1em">Mathurapur Daily Summary</h4></td></tr>';
            $msg .= '<tr><td><p style="font-size:0.9em;">'.$wk_day.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.date("d M, Y",strtotime($day)).'</p></td></tr>';
            $msg .= '<tr><td><p  style="font-size:0.9em; line-height:22px;">Total Loan collection of '.number_format(($data['principal_collection']+$data['interest_collection'])).' from '.$data['kendra_number'].' Kendras ('.$data['members'].' members). '.number_format($data['interest_collection']).' in interest was earned. Amount '.number_format($data['bank_deposit']).' deposited into the bank. Starting cash in hand was '.number_format($data['opening']).' and ending cash in hand was '.number_format($data['closing']).'</p></td></tr>';
            $msg .= "</table>";
            //Loan Collection start
            $msg .="<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4  style=\"font-size:1em\">Loan Collection </h4>";
            $msg .= '<tr>
                        <th align="center" valign="middle" style="border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em; background-color:#f3f3f3">Kendra</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em;  background-color:#f3f3f3">Realizable</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Realized</th>
                        
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Overdue</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Advance</th>
                      </tr>';
            $realisable = $realise = $overdue = $prep = $odc = 0;
            foreach($entry_list as $row){
                $msg .= '<tr>
                            <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em; text-align:left">'.$row['Kendra']['kendra_name'].'</td>
                            <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em;">'.number_format($row['RawEntry']['realizable']).'</td>
                            <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em;">'.number_format($row['RawEntry']['realize']).'</td>
                            
                            <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em;">'.number_format($row['RawEntry']['overdue']).'</td>
                            <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em;">'.number_format($row['RawEntry']['prepayment']).'</td>
                          </tr>';
                $realisable += $row['RawEntry']['realizable'];
                $realise += $row['RawEntry']['realize'];
                $odc += $row['RawEntry']['overdue_collection'];
                $overdue += $row['RawEntry']['overdue'];
                $prep += $row['RawEntry']['prepayment'];
            }
            $msg .= '<tr>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; border-bottom:1px solid #ccc; font-size:0.9em; text-align:left">Total</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; background-color:#cfe2f3; font-size:0.9em"><strong>'.number_format($realisable).'</strong></td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc;  background-color:#cfe2f3; font-size:0.9em"><strong>'.number_format($realise).'</strong></td>
                        
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc;  background-color:#cfe2f3; font-size:0.9em"><strong>'.number_format($overdue).'</strong></td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc;  border-right:1px solid #ccc; border-bottom:1px solid #ccc;  background-color:#cfe2f3; font-size:0.9em"><strong>'.number_format($prep).'</strong></td>
                      </tr>';           
            $msg .= "</table>";
            //Loan Collection end
            
            //Saving Collection start
            $msg .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4 style=\"font-size:1em\">Savings Collection </h4>";
            $msg .= '<tr>
                        <th align="center" valign="middle" style="border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em; background-color:#f3f3f3">Kendra</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Todays Collection</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Total Collected</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Interest Owed</th>
                      </tr>';
            $td = $total = $intr = $savings_return = $security_return = 0;
            foreach($entry_list as $row){
                $msg .= '<tr>
                            <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em; text-align:left">'.$row['Kendra']['kendra_name'].'</td>
                            <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">'.number_format($row['RawEntry']['saving_collection']).'</td>
                            <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">'.number_format($row['Kendra']['total_savings']).'</td>
                            <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em">'.number_format($row['Kendra']['total_savings']*.03).'</td>
                          </tr>';
                $td += $row['RawEntry']['saving_collection'];
                $total += $row['Kendra']['total_savings'];
                $intr += ($row['Kendra']['total_savings']*.03);
                
                $savings_return += $row['RawEntry']['saving_withdraw'];
                $security_return += $row['RawEntry']['security_withdraw'];
                if($row['RawEntry']['disburse_member_no']>0){
                    $disburse = 1;
                }
            }
            $msg .= '<tr>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; border-bottom:1px solid #ccc; font-size:0.9em; text-align:left">Total</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; background-color:#cfe2f3; font-size:0.9em"><strong>'.number_format($td).'</strong></td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc;  background-color:#cfe2f3; font-size:0.9em"><strong>'.number_format($total).'</strong></td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc;  border-right:1px solid #ccc; border-bottom:1px solid #ccc;  background-color:#cfe2f3; font-size:0.9em"><strong>'.number_format($intr).'</strong></td>
                      </tr>';
            $msg .= "</table>";
            //Saving Collection end
            
            //Loan Disbursement start
            if($disburse){
            $msg .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4 style=\"font-size:1em\">New Disbursement</h4>";
            $msg .= '<tr>
                        <th align="center" valign="middle" style="border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em; background-color:#f3f3f3">Kendra</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3"># Loans Disbursed</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Loan Amount</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Security Deposit</th>
                      </tr>';
            $dis_no = $total_amt = $sec = 0;
            foreach($entry_list as $row){
                if($row['RawEntry']['disburse_member_no']>0){
                $msg .= '<tr>
                            <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em">'.$row['Kendra']['kendra_name'].'</td>
                            <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">'.$row['RawEntry']['disburse_member_no'].'</td>
                            <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">'.number_format($row['RawEntry']['disburse_amount']).'</td>
                            <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em">'.number_format($row['RawEntry']['security_deposit']).'</td>
                          </tr>';
                $dis_no += $row['RawEntry']['disburse_member_no'];
                $total_amt += $row['RawEntry']['disburse_amount'];
                $sec += $row['RawEntry']['security_deposit'];
                }
            }
            $msg .= '<tr>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; border-bottom:1px solid #ccc; font-size:0.9em">Total</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; background-color:#cfe2f3; font-size:0.9em">'.$dis_no.'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc;  background-color:#cfe2f3; font-size:0.9em">'.number_format($total_amt).'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc;  border-right:1px solid #ccc; border-bottom:1px solid #ccc;  background-color:#cfe2f3; font-size:0.9em">'.$sec.'</td>
                      </tr>';
            $msg .= "</table>";
            }
            //Loan Disbursement end
            
            //Expenses Summary start
            $msg .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4 style=\"font-size:1em\">Expenses and Other Summary</h4>";
            $msg .= '<tr>
                        <th align="center" valign="middle" style="border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em">Security Return</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Savings Return</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em">Savings Interest Paid</th>
                      </tr>';
            $msg .= '<tr>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; padding:10px; font-size:0.9em">'.number_format($security_return).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($savings_return).'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.number_format($savings_return*.03).'</td>
                      </tr>';
            $msg .= '<tr>
                        <td colspan="3">&nbsp;</td>
                        
                      </tr>';
            
            $msg .= '<tr>
                        <th align="center" valign="middle" style="border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em">Bank Deposit</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Overdue Collection</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em">Just Office Expenses</th>
                      </tr>';
            $msg .= '<tr>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; padding:10px; font-size:0.9em">'.number_format($data['bank_deposit']).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($odc).'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.number_format($data['office_exp']).'</td>
                      </tr>';
            
            $msg .= "</table>";
            //Expenses Summary end
            
            
            /** For email body Preapre End **************************************************************************/
            //echo $msg;
        }
        /** Data Preapre  End******************************************************************/
        
        //die;
        /** Email Preapre and Send *******/
        $this->Controller =& new Controller();
    	$this->Email =& new CakeEmail();
        $this->Email->Controller = $this->Controller;

        $subject = "Mathurapur Daily Summary as on ".date("d M, Y",strtotime($day));
        $this->Email->from('no-reply@7io.co');
		$this->Email->to(array('chiranjib.dey@7io.co','sood@7io.co'));
        $this->Controller->set('content', $msg);
		$this->Email->subject($subject);
		//$this->Email->layout = 'default';
        $this->Email->viewVars(array('content' => $msg));
		$this->Email->template('default');
		$this->Email->config(array('additionalParameters' => '-fno-reply@7io.co'));
		$this->Email->emailFormat('html');
		try {
			if ($this->Email->send()) {
				$this->out(print('Email Sent!'));
                
			} else {
				$this->out(print('Email Not Sent!'));
                
			}
		}
		catch (phpmailerException $e) {
            $this->out($e);
			return false;
		}
		catch (exception $e) {
             $this->out($e);
			return false;
		}
        $this->out(print("Run at ".date("Y-m-d H:i:s")));
        
    }
    
    public function weekly_report(){
        $this->layout="ajax";
        $this->autoRender = false;
        /** Data Preapre  ******************************************************************/
        $msg = "";
        $day = date("Y-m-d");
        
        if(trim(date('w', strtotime($day))==5)){
            $start_day = date("Y-m-d",strtotime("$day -4 days"));
            $end_day = $day;
        }else{
            echo 'Not Done';
            die;
        }
        
        $flag = 1;
        for($i=4;$i>=0;$i--){
            $tmp_day = date("Y-m-d",strtotime("$day -$i days"));
            $entries = $this->RawEntry->find('count',array('conditions'=>array('RawEntry.date'=>$tmp_day)));
            if($entries==0){
                $msg .= "$tmp_day,";
                $flag = 0;
            }
        }
        $data['start_date'] = $start_day;
        $data['end_day'] = $end_day;
        if($flag){
            
            
            /** For data generate **/
            $rwe_list = $this->RawEntry->find('all',array(
                'fields' => array(
                            'sum(RawEntry.no_of_member) as members',
                            'count(distinct(RawEntry.kendra_id)) as kendra_no',
                            'sum(RawEntry.realizable) as realizable',
                            'sum(RawEntry.realize) as realize',
                            'sum(RawEntry.overdue_collection) as overdue_collection',
                            'sum(RawEntry.prepayment) as prepayment',
                            'sum(RawEntry.saving_collection) as saving_collection',
                            'sum(RawEntry.saving_withdraw) as saving_withdraw',
                            'sum(RawEntry.security_withdraw) as security_withdraw',
                            'sum(RawEntry.disburse_amount) as disburse_amount',
                            'sum(RawEntry.disburse_member_no) as disburse_member_no',
                            'sum(RawEntry.overdue_no) as overdue_no',
                            ),
                'conditions'=>array('RawEntry.date >='=>$start_day,'RawEntry.date <='=>$end_day)));
            
            $data['members'] = !empty($rwe_list[0][0]['members'])?$rwe_list[0][0]['members']:0;
            $data['kendra_number'] = !empty($rwe_list[0][0]['kendra_no'])?$rwe_list[0][0]['kendra_no']:0;
            $data['realizable'] = !empty($rwe_list[0][0]['realizable'])?$rwe_list[0][0]['realizable']:0;
            $data['realize'] = !empty($rwe_list[0][0]['realize'])?$rwe_list[0][0]['realize']:0;
            $data['overdue_collection'] = !empty($rwe_list[0][0]['overdue_collection'])?$rwe_list[0][0]['overdue_collection']:0;
            $data['prepayment'] = !empty($rwe_list[0][0]['prepayment'])?$rwe_list[0][0]['prepayment']:0;
            $data['saving_collection'] = !empty($rwe_list[0][0]['saving_collection'])?$rwe_list[0][0]['saving_collection']:0;
            $data['saving_withdraw'] = !empty($rwe_list[0][0]['saving_withdraw'])?$rwe_list[0][0]['saving_withdraw']:0;
            $data['security_withdraw'] = !empty($rwe_list[0][0]['security_withdraw'])?$rwe_list[0][0]['security_withdraw']:0;
            $data['disburse_amount'] = !empty($rwe_list[0][0]['disburse_amount'])?$rwe_list[0][0]['disburse_amount']:0;
            $data['disburse_member_no'] = !empty($rwe_list[0][0]['disburse_member_no'])?$rwe_list[0][0]['disburse_member_no']:0;
            $data['overdue'] = $data['realizable'] - $data['realize'];
            $data['overdue_no'] = !empty($rwe_list[0][0]['overdue_no'])?$rwe_list[0][0]['overdue_no']:0;
            
            $kendra_data = $this->Kendra->find('all',array('fields' => array('sum(Kendra.total_savings) as savings','sum(Kendra.total_security) as security','sum(Kendra.loan_remaining) as loan'),'conditions'=>array('Kendra.status'=>1)));
            $data['savings'] = !empty($kendra_data[0][0]['savings'])?$kendra_data[0][0]['savings']:0;
            $data['security'] = !empty($kendra_data[0][0]['security'])?$kendra_data[0][0]['security']:0;
            $data['loan'] = !empty($kendra_data[0][0]['loan'])?$kendra_data[0][0]['loan']:0;
            
            /** For Opening balance **/
            $wallet_date = date("Y-m-d",strtotime("$start_day -3 days"));
            
            $op_wdata=$this->WalletTransaction->find("first",array("conditions"=>array("WalletTransaction.wallet_id"=>1,"WalletTransaction.update_date"=>$wallet_date)));
            $data['opening'] = !empty($op_wdata['WalletTransaction']['closing_amount'])?$op_wdata['WalletTransaction']['closing_amount']:0;
            /** For Closing balance ************/
            $op_wdata=$this->WalletTransaction->find("first",array("conditions"=>array("WalletTransaction.wallet_id"=>1,"WalletTransaction.update_date"=>$end_day)));
            $data['closing'] = !empty($op_wdata['WalletTransaction']['closing_amount'])?$op_wdata['WalletTransaction']['closing_amount']:0;
            
            /** For Interest collection ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>7,'IncomeExpenditure.transaction_date >='=>$start_day,'IncomeExpenditure.transaction_date <='=>$end_day)));
            $data['interest_collection'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For Principal collection ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>2,'IncomeExpenditure.transaction_date >='=>$start_day,'IncomeExpenditure.transaction_date <='=>$end_day)));
            $data['principal_collection'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For bank deposit ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(debit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>12,'IncomeExpenditure.transaction_date >='=>$start_day,'IncomeExpenditure.transaction_date <='=>$end_day)));
            $data['bank_deposit'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For Only Office expenses ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(debit_amount) as exp'), 'conditions' => array('IncomeExpenditure.transaction_date >='=>$start_day,'IncomeExpenditure.transaction_date <='=>$end_day,'NOT'=>array('IncomeExpenditure.account_ledger_id'=>[4,5,6,12]))));
            $data['office_exp'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For Fees collection ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('OR'=>array('IncomeExpenditure.account_ledger_id'=>[8,9,10,16,33]),'IncomeExpenditure.transaction_date >='=>$start_day,'IncomeExpenditure.transaction_date <='=>$end_day)));
            $data['fees_collection'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For email body Preapre Start **************************************************************************/
            $msg .="<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">";
            $msg .= '<tr><td><h4 style="font-size:1em">Mathurapur Weekly Summary</h4></td></tr>';
            $msg .= '<tr><td><p style="font-size:0.9em;">'.date("d M, Y",strtotime($start_day)).'&nbsp;&nbsp;TO&nbsp;&nbsp; '.date("d M, Y",strtotime($end_day)).'</p></td></tr>';
            $msg .= '<tr><td><p  style="font-size:0.9em; line-height:22px;">Total Loan collection of '.number_format(($data['principal_collection']+$data['interest_collection'])).' from '.$data['kendra_number'].' Kendras ('.$data['members'].' members). '.number_format($data['interest_collection']).' in interest was earned. Amount '.number_format($data['bank_deposit']).' deposited into the bank. Starting cash in hand was '.number_format($data['opening']).' and ending cash in hand was '.number_format($data['closing']).'</p></td></tr>';
            $msg .= "</table>";
            //Loan Collection start
            $msg .="<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4  style=\"font-size:1em\">Loan Collection </h4>";
            $msg .= '<tr> 
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; padding:10px; background-color:#f3f3f3">Realizable</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Realized</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Advance</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Overdue Collection</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Total Overdue</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Overdue no</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Loan Remaining</th>
                      </tr>';
            $msg .= '<tr>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; padding:10px;font-size:0.9em;">'.$data['realizable'].'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; font-size:0.9em;">'.$data['realize'].'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em;">'.$data['prepayment'].'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; font-size:0.9em;">'.$data['overdue_collection'].'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; font-size:0.9em;">'.$data['overdue'].'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; font-size:0.9em;">'.$data['overdue_no'].'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.$data['loan'].'</td>
                      </tr>';          
            $msg .= "</table>";
            //Loan Collection end
            
            //Saving Collection start
            $msg .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4>Savings Collection </h4>";
            $msg .= '<tr>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em;padding:10px; background-color:#f3f3f3">Total Security Deposit</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Week Collection</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Total Collected</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Interest Owed</th>
                      </tr>';
            $msg .= '<tr>
                            <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:10px; font-size:0.9em">'.number_format($data['security']).'</td>
                            <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($data['saving_collection']).'</td>
                            <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($data['savings']).'</td>
                            <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.number_format($data['savings']*.03).'</td>
                            
                          </tr>';
            $msg .= "</table>";
            //Saving Collection end
            
            //Loan Disbursement start
            $msg .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4>New Disbursement</h4>";
            $msg .= '<tr>
                        
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em;padding:10px; background-color:#f3f3f3"># Loans Disbursed</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Loan Amount</th>
                        
                      </tr>';
            $msg .= '<tr>
                        
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; padding:10px;font-size:0.9em">'.$data['disburse_member_no'].'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.number_format($data['disburse_amount']).'</td>
                        
                      </tr>';
            $msg .= "</table>";
            //Loan Disbursement end
            
            //Expenses Summary start
            $msg .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4>Expenses and Other Summary</h4>";
            $msg .= '<tr>
                        <th align="center" valign="middle" style="border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em">Security Return</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Savings Return</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em">Savings Interest Paid</th>
                      </tr>';
            $msg .= '<tr>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; padding:10px; font-size:0.9em">'.number_format($data['security_withdraw']).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($data['saving_withdraw']).'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.number_format($data['saving_withdraw']*.03).'</td>
                      </tr>';
            $msg .= '<tr>
                        <td colspan="3">&nbsp;</td>
                        
                      </tr>';
            
            $msg .= '<tr>
                        <th align="center" valign="middle" style="border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em">Bank Deposit</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Fees Collection</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em">Just Office Expenses</th>
                      </tr>';
            $msg .= '<tr>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; padding:10px; font-size:0.9em">'.number_format($data['bank_deposit']).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($data['fees_collection']).'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.number_format($data['office_exp']).'</td>
                      </tr>';
            
            $msg .= "</table>";
            //Expenses Summary end
            
            //portfolio health start
            $msg .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4>Portfolio Health</h4>";
            $msg .= '<tr>
                        <th align="center" valign="middle" style="border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em">Loan in Market</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Principal</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Interest</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Savings</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Interest Payable</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Security</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em">Actual</th>
                      </tr>';
                      $prnc = round($data['loan']*87.2/100);
                      $intrst = $data['loan']- $prnc;
                      $sav_int = round($data['savings']*0.03);
                      $actual = $data['loan']-($data['savings']+$data['security']+$sav_int+$intrst);
            $msg .= '<tr>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; padding:10px; font-size:0.9em">'.number_format($data['loan']).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($prnc).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($intrst).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($data['savings']).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($sav_int).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($data['security']).'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.number_format($actual).'</td>
                      </tr>';
            
            $msg .= "</table>";
            //portfolio health end
            
            
            /** For email body Preapre End **************************************************************************/
            
        /** Data Preapre  End******************************************************************/
        }else{// Flag If end
            $msg = "Data has not been updated on ".$msg;
        }
        //echo $msg;
        //die;
        
        
        /** Email Preapre and Send *******/
        $this->Controller =& new Controller();
    	$this->Email =& new CakeEmail();
        $this->Email->Controller = $this->Controller;

        $subject =  "Mathurapur Weekly Summary - ".date("d M, Y",strtotime($start_day))." to ".date("d M, Y",strtotime($end_day));
        $this->Email->from('no-reply@7io.co');
		$this->Email->to(array('chiranjib.dey@7io.co','sood@7io.co'));
        $this->Controller->set('content', $msg);
		$this->Email->subject($subject);
		//$this->Email->layout = 'default';
        $this->Email->viewVars(array('content' => $msg));
		$this->Email->template('default');
		$this->Email->config(array('additionalParameters' => '-fno-reply@7io.co'));
		$this->Email->emailFormat('html');
		try {
			if ($this->Email->send()) {
				$this->out(print('Email Sent!'));
                
			} else {
				$this->out(print('Email Not Sent!'));
                
			}
		}
		catch (phpmailerException $e) {
            $this->out($e);
			return false;
		}
		catch (exception $e) {
             $this->out($e);
			return false;
		}
        $this->out(print("Run at ".date("Y-m-d H:i:s")));
        
   }
   
   public function monthly_report(){
        $this->layout="ajax";
        $this->autoRender = false;
        /** Data Preapre  ******************************************************************/
        $msg = "";
        $day = date("Y-m-t",strtotime("-1 days"));//date("Y-m-d");
        
        $start_day = date("Y-m-01",strtotime($day));
        $end_day = $day;
        
        $flag = 1;
        for($i=(date("t")-1);$i>=0;$i--){
            $tmp_day = date("Y-m-d",strtotime("$day -$i days"));
 
            if((date('w', strtotime($tmp_day)) > 0) && (date('w', strtotime($tmp_day)) < 6)){
                
                $entries = $this->RawEntry->find('count',array('conditions'=>array('RawEntry.date'=>$tmp_day)));
                if($entries==0){
                    $msg .= "$tmp_day,";
                    $flag = 0;
                }
            }
            
        }
        $data['start_date'] = $start_day;
        $data['end_day'] = $end_day;
        if($flag){
            
            
            /** For data generate **/
            $rwe_list = $this->RawEntry->find('all',array(
                'fields' => array(
                            'sum(RawEntry.no_of_member) as members',
                            'count(distinct(RawEntry.kendra_id)) as kendra_no',
                            'sum(RawEntry.realizable) as realizable',
                            'sum(RawEntry.realize) as realize',
                            'sum(RawEntry.overdue_collection) as overdue_collection',
                            'sum(RawEntry.prepayment) as prepayment',
                            'sum(RawEntry.saving_collection) as saving_collection',
                            'sum(RawEntry.saving_withdraw) as saving_withdraw',
                            'sum(RawEntry.security_withdraw) as security_withdraw',
                            'sum(RawEntry.disburse_amount) as disburse_amount',
                            'sum(RawEntry.disburse_member_no) as disburse_member_no',
                            'sum(RawEntry.overdue_no) as overdue_no',
                            ),
                'conditions'=>array('RawEntry.date >='=>$start_day,'RawEntry.date <='=>$end_day)));
            
            $data['members'] = !empty($rwe_list[0][0]['members'])?$rwe_list[0][0]['members']:0;
            $data['kendra_number'] = !empty($rwe_list[0][0]['kendra_no'])?$rwe_list[0][0]['kendra_no']:0;
            $data['realizable'] = !empty($rwe_list[0][0]['realizable'])?$rwe_list[0][0]['realizable']:0;
            $data['realize'] = !empty($rwe_list[0][0]['realize'])?$rwe_list[0][0]['realize']:0;
            $data['overdue_collection'] = !empty($rwe_list[0][0]['overdue_collection'])?$rwe_list[0][0]['overdue_collection']:0;
            $data['prepayment'] = !empty($rwe_list[0][0]['prepayment'])?$rwe_list[0][0]['prepayment']:0;
            $data['saving_collection'] = !empty($rwe_list[0][0]['saving_collection'])?$rwe_list[0][0]['saving_collection']:0;
            $data['saving_withdraw'] = !empty($rwe_list[0][0]['saving_withdraw'])?$rwe_list[0][0]['saving_withdraw']:0;
            $data['security_withdraw'] = !empty($rwe_list[0][0]['security_withdraw'])?$rwe_list[0][0]['security_withdraw']:0;
            $data['disburse_amount'] = !empty($rwe_list[0][0]['disburse_amount'])?$rwe_list[0][0]['disburse_amount']:0;
            $data['disburse_member_no'] = !empty($rwe_list[0][0]['disburse_member_no'])?$rwe_list[0][0]['disburse_member_no']:0;
            $data['overdue'] = $data['realizable'] - $data['realize'];
            $data['overdue_no'] = !empty($rwe_list[0][0]['overdue_no'])?$rwe_list[0][0]['overdue_no']:0;
            
            $kendra_data = $this->Kendra->find('all',array('fields' => array('sum(Kendra.total_savings) as savings','sum(Kendra.total_security) as security','sum(Kendra.loan_remaining) as loan'),'conditions'=>array('Kendra.status'=>1)));
            $data['savings'] = !empty($kendra_data[0][0]['savings'])?$kendra_data[0][0]['savings']:0;
            $data['security'] = !empty($kendra_data[0][0]['security'])?$kendra_data[0][0]['security']:0;
            $data['loan'] = !empty($kendra_data[0][0]['loan'])?$kendra_data[0][0]['loan']:0;
            
            /** For Opening balance **/
            $wallet_date = date("Y-m-d",strtotime("$start_day -3 days"));
            
            $op_wdata=$this->WalletTransaction->find("first",array("conditions"=>array("WalletTransaction.wallet_id"=>1,"WalletTransaction.update_date"=>$wallet_date)));
            $data['opening'] = !empty($op_wdata['WalletTransaction']['closing_amount'])?$op_wdata['WalletTransaction']['closing_amount']:0;
            /** For Closing balance ************/
            $op_wdata=$this->WalletTransaction->find("first",array("conditions"=>array("WalletTransaction.wallet_id"=>1,"WalletTransaction.update_date"=>$end_day)));
            $data['closing'] = !empty($op_wdata['WalletTransaction']['closing_amount'])?$op_wdata['WalletTransaction']['closing_amount']:0;
            
            /** For Interest collection ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>7,'IncomeExpenditure.transaction_date >='=>$start_day,'IncomeExpenditure.transaction_date <='=>$end_day)));
            $data['interest_collection'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For Principal collection ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>2,'IncomeExpenditure.transaction_date >='=>$start_day,'IncomeExpenditure.transaction_date <='=>$end_day)));
            $data['principal_collection'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For bank deposit ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(debit_amount) as exp'), 'conditions' => array('IncomeExpenditure.account_ledger_id'=>12,'IncomeExpenditure.transaction_date >='=>$start_day,'IncomeExpenditure.transaction_date <='=>$end_day)));
            $data['bank_deposit'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For Only Office expenses ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(debit_amount) as exp'), 'conditions' => array('IncomeExpenditure.transaction_date >='=>$start_day,'IncomeExpenditure.transaction_date <='=>$end_day,'NOT'=>array('IncomeExpenditure.account_ledger_id'=>[4,5,6,12]))));
            $data['office_exp'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For Fees collection ********/
            $exp_arr = $this->IncomeExpenditure->find('all', array('fields' => array('sum(credit_amount) as exp'), 'conditions' => array('OR'=>array('IncomeExpenditure.account_ledger_id'=>[8,9,10,16,33]),'IncomeExpenditure.transaction_date >='=>$start_day,'IncomeExpenditure.transaction_date <='=>$end_day)));
            $data['fees_collection'] = !empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0';
            
            /** For email body Preapre Start **************************************************************************/
            $msg .="<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">";
            $msg .= '<tr><td><h4 style="font-size:1em">Mathurapur Monthly Summary</h4></td></tr>';
            $msg .= '<tr><td><p style="font-size:0.9em;">'.date("d M, Y",strtotime($start_day)).'&nbsp;&nbsp;TO&nbsp;&nbsp; '.date("d M, Y",strtotime($end_day)).'</p></td></tr>';
            $msg .= '<tr><td><p  style="font-size:0.9em; line-height:22px;">Total Loan collection of '.number_format(($data['principal_collection']+$data['interest_collection'])).' from '.$data['kendra_number'].' Kendras ('.$data['members'].' members). '.number_format($data['interest_collection']).' in interest was earned. Amount '.number_format($data['bank_deposit']).' deposited into the bank. Starting cash in hand was '.number_format($data['opening']).' and ending cash in hand was '.number_format($data['closing']).'</p></td></tr>';
            $msg .= "</table>";
            //Loan Collection start
            $msg .="<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4  style=\"font-size:1em\">Loan Collection </h4>";
            $msg .= '<tr> 
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; padding:10px; background-color:#f3f3f3">Realizable</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Realized</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Advance</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Overdue Collection</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Total Overdue</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Overdue no</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Loan Remaining</th>
                      </tr>';
            $msg .= '<tr>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; padding:10px;font-size:0.9em;">'.$data['realizable'].'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; font-size:0.9em;">'.$data['realize'].'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em;">'.$data['prepayment'].'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; font-size:0.9em;">'.$data['overdue_collection'].'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; font-size:0.9em;">'.$data['overdue'].'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; font-size:0.9em;">'.$data['overdue_no'].'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.$data['loan'].'</td>
                      </tr>';          
            $msg .= "</table>";
            //Loan Collection end
            
            //Saving Collection start
            $msg .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4>Savings Collection </h4>";
            $msg .= '<tr>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em;padding:10px; background-color:#f3f3f3">Month Collection</th>
                        
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Total Collected</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Interest Owed</th>
                      </tr>';
            $msg .= '<tr>
                            <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:10px; font-size:0.9em">'.number_format($data['saving_collection']).'</td>
                            
                            <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($data['savings']).'</td>
                            <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.number_format($data['savings']*.03).'</td>
                            
                          </tr>';
            $msg .= "</table>";
            //Saving Collection end
            
            //Loan Disbursement start
            $msg .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4>New Disbursement</h4>";
            $msg .= '<tr>
                        
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em;padding:10px; background-color:#f3f3f3"># Loans Disbursed</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em; background-color:#f3f3f3">Loan Amount</th>
                        
                      </tr>';
            $msg .= '<tr>
                        
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc;border-bottom:1px solid #ccc; padding:10px;font-size:0.9em">'.$data['disburse_member_no'].'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.number_format($data['disburse_amount']).'</td>
                        
                      </tr>';
            $msg .= "</table>";
            //Loan Disbursement end
            
            //Expenses Summary start
            $msg .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4>Expenses and Other Summary</h4>";
            $msg .= '<tr>
                        <th align="center" valign="middle" style="border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em">Security Return</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Savings Return</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em">Savings Interest Paid</th>
                      </tr>';
            $msg .= '<tr>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; padding:10px; font-size:0.9em">'.number_format($data['security_withdraw']).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($data['saving_withdraw']).'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.number_format($data['saving_withdraw']*.03).'</td>
                      </tr>';
            $msg .= '<tr>
                        <td colspan="3">&nbsp;</td>
                        
                      </tr>';
            
            $msg .= '<tr>
                        <th align="center" valign="middle" style="border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em">Bank Deposit</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Fees Collection</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em">Just Office Expenses</th>
                      </tr>';
            $msg .= '<tr>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; padding:10px; font-size:0.9em">'.number_format($data['bank_deposit']).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($data['fees_collection']).'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.number_format($data['office_exp']).'</td>
                      </tr>';
            
            $msg .= "</table>";
            //Expenses Summary end
            //portfolio health start
            $msg .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border-spacing='0' style=\"font-family:Verdana, Geneva, sans-serif\">
                        <h4>Portfolio Health</h4>";
            $msg .= '<tr>
                        <th align="center" valign="middle" style="border-top:1px solid #ccc; border-left:1px solid #ccc; padding:10px; font-size:0.9em">Loan in Market</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Principal</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Interest</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Savings</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Interest Payable</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:0.9em">Security</th>
                        <th style="border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; font-size:0.9em">Actual</th>
                      </tr>';
                      $prnc = round($data['loan']*87.2/100);
                      $intrst = $data['loan']- $prnc;
                      $sav_int = round($data['savings']*0.03);
                      $actual = $data['loan']-($data['savings']+$data['security']+$sav_int+$intrst);
            $msg .= '<tr>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; padding:10px; font-size:0.9em">'.number_format($data['loan']).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($prnc).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($intrst).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($data['savings']).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($sav_int).'</td>
                        <td style="text-align:center; border-top:1px solid #ccc; border-left:1px solid #ccc; border-bottom:1px solid #ccc; font-size:0.9em">'.number_format($data['security']).'</td>
                        <td style="text-align:center;border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom:1px solid #ccc;font-size:0.9em">'.number_format($actual).'</td>
                      </tr>';
            
            $msg .= "</table>";
            //portfolio health end
            
            /** For email body Preapre End **************************************************************************/
            
        /** Data Preapre  End******************************************************************/
        }else{// Flag If end
            $msg = "Data has not been updated on ".$msg;
        }
        
        /** Email Preapre and Send *******/
        $this->Controller =& new Controller();
    	$this->Email =& new CakeEmail();
        $this->Email->Controller = $this->Controller;

        $subject =  "Mathurapur Monthly Summary - ".date("d M, Y",strtotime($start_day))." to ".date("d M, Y",strtotime($end_day));
        $this->Email->from('no-reply@7io.co');
		$this->Email->to(array('chiranjib.dey@7io.co','sood@7io.co'));
        $this->Controller->set('content', $msg);
		$this->Email->subject($subject);
		//$this->Email->layout = 'default';
        $this->Email->viewVars(array('content' => $msg));
		$this->Email->template('default');
		$this->Email->config(array('additionalParameters' => '-fno-reply@7io.co'));
		$this->Email->emailFormat('html');
		try {
			if ($this->Email->send()) {
				$this->out(print('Email Sent!'));
                
			} else {
				$this->out(print('Email Not Sent!'));
                
			}
		}
		catch (phpmailerException $e) {
            $this->out($e);
			return false;
		}
		catch (exception $e) {
             $this->out($e);
			return false;
		}
        $this->out(print("Run at ".date("Y-m-d H:i:s")));
        
   }
   
}
