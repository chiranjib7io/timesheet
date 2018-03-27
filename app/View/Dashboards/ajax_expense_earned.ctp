<h4>Expenses and Earning :</h4>
<table class="table table-hover">
      <thead>
        <tr>
        	<th align="center" valign="middle">&nbsp; </th>
            <th align="center" valign="middle"># of Days </th>
          <th align="center" valign="middle">Normal Cost </th>
          <th align="center" valign="middle">Overtime Cost</th>
          <th align="center" valign="middle">Expenses</th>
          
          <th align="center" valign="middle">Total Cost</th>
          <th align="center" valign="middle">Total Earned </th>
          <th align="center" valign="middle">Profit/Loss</th>
          
        </tr>
      </thead>
      <tbody>
<?php foreach($expense_and_earned_data as $lrow){ ?>
        <tr>
          <td scope="row" class="time"><?php echo $lrow['month']; ?></td>
          <td><?php echo $lrow['working']; ?></td>
          <td><?php echo !empty($lrow['normal_cost'])?number_format($lrow['normal_cost']):0; ?></td>
          <td><?php echo !empty($lrow['ot_cost'])?number_format($lrow['ot_cost']):0; ?></td>
          <td><?php echo !empty($lrow['expenses'])?number_format($lrow['expenses']):0; ?></td>
          
          <td><?php echo !empty($lrow['total_cost'])?number_format($lrow['total_cost']):0; ?></td>
          <td><?php echo !empty($lrow['earned'])?number_format($lrow['earned']):0; ?></td>
          <td><?php echo !empty($lrow['earned'])?number_format($lrow['earned']-$lrow['total_cost']):0; ?></td>
           
        </tr>
<?php } ?>
        
      </tbody>
    </table>