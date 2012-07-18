<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>FuelPHP Framework</title>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
	<?php echo Asset::css('bootstrap.min.css'); ?>
	<?php echo Asset::css('bootstrap-responsive.min.css'); ?>
	<?php echo Asset::css('style.css'); ?>
	<?php echo Asset::js('bootstrap.js'); ?>
	<?php echo Asset::js('datagenerator.js'); ?>
</head>
<body>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
	<div class="container">
	  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </a>
	  <a class="brand" href="#">Generate you a datas</a>
	  <div class="nav-collapse">
		<ul class="nav">
		</ul>
	  </div><!--/.nav-collapse -->
	</div>
  </div>
</div>

<div class="container">

  <?= $content ?>

</div> <!-- /container -->
</body>
</html>
