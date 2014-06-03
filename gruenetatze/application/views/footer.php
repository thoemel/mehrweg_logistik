
</div>
<!-- /container -->

<footer>
	<div class="container hidden-print">
		<span class="col-xs-2">
			<?php echo anchor('', '_\|/_', array('title'=>'Start')); ?>
		</span>
		<span class="col-xs-6 col-xs-offset-2">
			Notfall-Telefon: <a href="tel:+41786045998">078 604 59 98</a>
		</span>
	</div>
</footer>



<?php 
$min = ('development' == ENVIRONMENT )  ? '' : '.min';
echo '
<script src="' . base_url() . 'js/vendor/jquery-1.10.2.js"></script>
<script src="' . base_url() . 'js/vendor/jquery-ui-1.10.4.min.js"></script>
<script src="' . base_url() . 'js/vendor/bootstrap.js"></script>
		
<script src="' . base_url() . 'js/main.js"></script>';

if (!empty($additionalJS)) {
	echo $additionalJS;
}
?>

</body>
</html>
