<?php
/**
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
 * @package       app.View.Layouts.Email.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title><?php echo $title_for_layout; ?></title>
</head>
<body>
	
    <table align="center" style="width:90%;">
    	<tr>
        	<td style="background-color: #605ca8; padding:25px 15px;"><img src="http://www.microfinanceapp.com/finance/asset/dist/img/logo.png" /></td>
        </tr>
        <tr>
        	<td>
            	<?php echo $this->fetch('content'); ?>   	
            </td>
        </tr>
        <tr>
        	<td style="padding:20px 10px; background-color:#222d32; color:#FFF;">Developed By 7io.co.</td>
        </tr>
    
    </table>
	
</body>
</html>