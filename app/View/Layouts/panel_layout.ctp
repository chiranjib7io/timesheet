<!DOCTYPE html>
<html>
  <head>
    <title><?php echo (!empty($title))?$title:'Project management || 7io' ; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap 3.3.2 -->
    <link href="<?php echo $this->webroot; ?>asset/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="<?php echo $this->webroot; ?>asset/dist/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="<?php echo $this->webroot; ?>asset/dist/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- daterange picker -->
    <link href="<?php echo $this->webroot; ?>asset/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Color Picker -->
    <link href="<?php echo $this->webroot; ?>asset/plugins/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet"/>
    <!-- Bootstrap time Picker -->
    <link href="<?php echo $this->webroot; ?>asset/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet"/>
    <!-- Theme style -->
    <link href="<?php echo $this->webroot; ?>asset/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo $this->webroot; ?>asset/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo $this->webroot; ?>asset/plugins/iCheck/all.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    
    <!-- jQuery 2.1.3 -->
    <script src="<?php echo $this->webroot; ?>asset/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo $this->webroot; ?>asset/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo $this->webroot; ?>asset/dist/js/easyResponsiveTabs.js"></script>
	
    <!-- InputMask -->
    <script src="<?php echo $this->webroot; ?>asset/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
    <script src="<?php echo $this->webroot; ?>asset/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
    <script src="<?php echo $this->webroot; ?>asset/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
    <!-- date-range-picker -->
    <script src="<?php echo $this->webroot; ?>asset/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <!-- bootstrap color picker -->
    <script src="<?php echo $this->webroot; ?>asset/plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
    <!-- bootstrap time picker -->
    <script src="<?php echo $this->webroot; ?>asset/plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="<?php echo $this->webroot; ?>asset/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- iCheck 1.0.1 -->
    <script src="<?php echo $this->webroot; ?>asset/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='<?php echo $this->webroot; ?>asset/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="<?php echo $this->webroot; ?>asset/dist/js/app.min.js" type="text/javascript"></script>
    
    <!-- DATA TABES SCRIPT -->
    <script src="<?php echo $this->webroot; ?>asset/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <link href="<?php echo $this->webroot; ?>asset/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    
    
    <!-- Page script -->
    <script type="text/javascript">
     function goBack(){
        //if it was the first page
        if(history.length === 1){
            window.location = "<?= $this->Html->url('/') ?>";
        } else {
            history.back();
        }
     }
    </script>
    
  </head>
  <body class="skin-purple">
    <div class="wrapper">
      
      <!--========= Header =========-->
    <?php echo $this->element("front_header"); ?>
    <!--========= Header Ends =========-->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" style="min-height: 498px;">
        
        <?php echo $this->fetch('content'); ?>
        
      </div><!-- /.content-wrapper -->
      
      
    <!--========= Footer =========-->
    <?php echo $this->element("front_footer"); ?>
    <!--========= Footer Ends =========-->

  </body>
</html>