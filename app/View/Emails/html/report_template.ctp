 
    <table style="width:100%; padding-left:15px; padding-right:15px;">
                	
                    <tr>
                        <td>
                            <table style="width:100%;">
                            	<tr>
                                	<td colspan="2">
                                    	<h2><?=$branch_data['Branch']['branch_name']?> [<?=date("d-M-Y")?>]</h2><hr>
                                    </td>
                                </tr>
                                <tr>
                                	<td style="width:50%;">
                                    	<table>
                                        	<tr>
                                            	<td style="vertical-align: top;"><strong>Branch Manager Name:</strong></td>
                                                <td style="vertical-align: top;"><?=$branch_data['Branch']['manager_name']?></td>
                                            </tr>
                                        </table>
                                    </td>
                                	<td style="width:50%;">
                                    	<table>
                                        	<tr>
                                            	<td style="vertical-align: top;"><strong>Email ID :</strong></td>
                                                <td style="vertical-align: top;"><?=$branch_data['Branch']['contact_email']?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                	<td style="width:50%;">
                                    	<table>
                                        	<tr>
                                            	<td style="vertical-align: top;"><strong>Organisation Name :</strong></td>
                                                <td style="vertical-align: top;"><?=$branch_data['Branch']['organization_name']?></td>
                                            </tr>
                                        </table>
                                    </td>
                                	<td style="width:50%;">
                                    	<table>
                                        	<tr>
                                            	<td style="vertical-align: top;"><strong>No. of Kendra :</strong></td>
                                                <td style="vertical-align: top;"><?=$branch_data['Branch']['total_kendra']?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                	<td style="width:50%;">
                                    	<table>
                                        	<tr>
                                            	<td style="vertical-align: top;"><strong>No. of Customer :</strong></td>
                                                <td style="vertical-align: top;"><?=$branch_data['Branch']['total_customer']?></td>
                                            </tr>
                                        </table>
                                    </td>
                                	<td style="width:50%;">
                                    	<table>
                                        	<tr>
                                            	<td style="vertical-align: top;"><strong>Total Loan Amount :</strong></td>
                                                <td style="vertical-align: top;"><?=$branch_data['Branch']['total_loan']?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                	<td style="width:50%;">
                                    	<table>
                                        	<tr>
                                            	<td style="vertical-align: top;"><strong>Loan in Market:</strong></td>
                                                <td style="vertical-align: top;"><?=$branch_data['Branch']['total_loan_in_market']?></td>
                                            </tr>
                                        </table>
                                    </td>
                                	<td style="width:50%;">
                                    	<table>
                                        	<tr>
                                            	<td style="vertical-align: top;"><strong>Total Overdue :</strong></td>
                                                <td style="vertical-align: top;"><?=$branch_data['Branch']['total_overdue']?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                	<td style="width:50%;">
                                    	<table>
                                        	<tr>
                                            	<td style="vertical-align: top;"><strong>Percent Paid :</strong></td>
                                                <td style="vertical-align: top;"><?=$branch_data['Branch']['percent_paid']?>%</td>
                                            </tr>
                                        </table>
                                    </td>
                                	<td style="width:50%;">
                                    	<table>
                                        	<tr>
                                            	<td style="vertical-align: top;"><strong>Address :</strong></td>
                                                <td style="vertical-align: top;"><?=$branch_data['Branch']['address']?>, <?=$branch_data['Branch']['city']?>, <br /><?=$branch_data['Branch']['state']?>, <?=$branch_data['Branch']['zip']?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <table style="width:100%; padding-bottom:50px;" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td colspan="8"><h3>::Kendra Summary::</h3></td>
                                </tr>
                                <tr style="background-color:#036; color:#fff;">	
                                    <td style="padding:10px 5px;vertical-align: top;">#</td>
                                    <td style="padding:10px 5px;vertical-align: top;">Kendra Name</td>
                                    <td style="padding:10px 5px;vertical-align: top;">Total Loan</td>
                                    <td style="padding:10px 5px;vertical-align: top;">Loan in Market</td>
                                    <td style="padding:10px 5px;vertical-align: top;">Realizable</td>
                                    <td style="padding:10px 5px;vertical-align: top;">Realized</td>
                                    <td style="padding:10px 5px;vertical-align: top;">Percent Paid</td>
                                    <td style="padding:10px 5px;vertical-align: top;">Total Customer</td>
                                </tr>
                <?php           
                     $i=1;
                    foreach($branch_data['Kendra'] as $pay_row)
                    {       
                            
                  ?>
                                
                                <tr>
                                	<td align="center"><?=$i?></td>
                                    <td style="padding:10px 5px;vertical-align: top;"><?=$pay_row['Kendra']['kendra_name']?></td>
                                    <td style="padding:10px 5px;vertical-align: top;"><?=$pay_row['Kendra']['total_loan']?></td>
                                    <td style="padding:10px 5px;"><?=$pay_row['Kendra']['total_loan_in_market']?></td>
                                    <td style="padding:10px 5px;vertical-align: top;"><?=$pay_row['Kendra']['total_realisable']?></td>
                                    <td style="padding:10px 5px;vertical-align: top;"><?=$pay_row['Kendra']['total_realise']?></td>
                                    <td style="padding:10px 5px;vertical-align: top;"><?=$pay_row['Kendra']['percent_paid']?> %</td>
                                    <td style="padding:10px 5px;vertical-align: top;"><?=$pay_row['Kendra']['total_customer']?></td>
                                </tr>
                  <?php
                    $i++;
                    } 
                   ?>   
                                
                            </table>
                        </td>
                    </tr>
                </table> 