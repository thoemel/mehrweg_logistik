
</div>
<!-- /container -->

<footer>
	<div class="container hidden-print">
<?php echo anchor('', '_\|/_', array('title'=>'Start')); ?>
	</div>
</footer>



<?php 
$min = ('development' == ENVIRONMENT )  ? '' : '.min';
echo '
<script src="' . base_url() . 'js/vendor/jquery-1.10.2.js"></script>
<script src="' . base_url() . 'js/vendor/jquery-ui-1.10.4.min.js"></script>
<script src="' . base_url() . 'js/vendor/bootstrap.js"></script>
		
<script src="' . base_url() . 'js/main.js"></script>
<script src="' . base_url() . 'js/unterschrift.js"></script>';
?>

</body>
</html>
