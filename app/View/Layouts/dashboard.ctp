<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo (!empty($title))?$title:'Project management' ; ?></title>

<link href="<?php echo $this->webroot; ?>front/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo $this->webroot; ?>front/css/style.css" rel="stylesheet">
<link href="<?php echo $this->webroot; ?>front/css/font-awesome.min.css" rel="stylesheet">


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]> 
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="<?php echo $this->webroot; ?>front/js/bootstrap.min.js"></script>

</head>

<body>
<?php echo $this->element("front_header"); ?>

<?php echo $this->fetch('content'); ?>


<?php echo $this->element("front_footer"); ?>
  
</body>
</html>
