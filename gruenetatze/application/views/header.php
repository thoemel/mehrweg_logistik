<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html>
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Grüne Tatze</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="icon" href="<?php echo base_url();?>" type="image/x-icon"> 
	<link rel="apple-touch-icon" href="<?php echo base_url();?>apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url();?>apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url();?>apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url();?>apple-touch-icon-144x144.png" />
	<link rel="apple-touch-icon-precomposed" href="<?php echo base_url();?>apple-touch-icon-precomposed.png"/>
	
<?php 
$min = ('dev' == substr($_SERVER['SERVER_NAME'], 0, 3)) ? '' : '.min';
echo '
	<link rel="stylesheet" href="' . base_url() . 'css/bootstrap' . $min . '.css">';

$tsCss = filectime(FCPATH . '/css/main.css');
echo '
	<link rel="stylesheet" href="' . base_url() . 'css/main.css?' . $tsCss . '">

	<link rel="stylesheet" href="' . base_url() . 'css/ui-lightness/jquery-ui-1.10.4.min.css?' . $tsCss . '">';

if (!empty($querformat)) {
	// The cheap way. Will have to add a pdf link too.
	echo '
	<style type="text/css" media="print">@page {size: landscape;}</style>';
}
?>

	<script src="<?php echo base_url();?>js/vendor/modernizr-2.6.1-respond-1.1.0.min.js"></script>
</head>
<body>
	<!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->

<?php echo $navi ?>

	<div class="container">
	
	
<?php 
if (1 == $this->session->userdata('logged_in')) {
	echo '
		<div id="confirmation">
			<h2>Formular abgeschickt - warte auf Antwort</h2>
		</div>';
}

if (false != $this->session->flashdata('success')) {
	echo '<div class="alert alert-success">' . $this->session->flashdata('success') . '</div>';
}
if (!empty($success)) {
	echo '<div class="alert alert-success">' . $success . '</div>';
}
if (false != $this->session->flashdata('error')) {
	echo '<div class="alert alert-error">' . $this->session->flashdata('error') . '</div>';
}
if (!empty($error)) {
	echo '<div class="alert alert-error">' . $error . '</div>';
}
