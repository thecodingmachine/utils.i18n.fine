<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

?>
<h1>Import your Excel file</h1>
<p>To have the right file, please download the Excel export <a href="ExcelExport">Excel Export</a></p>

<?php 
if($this->submit) {
	echo '<div class="good">';
	echo "Label successfully saved.";
	echo '</div>';
	?>
	<script type="text/javascript">
	setTimeout(function() {
		jQuery('.good').fadeOut(1000);
	}, 4000);
	</script>
	<?php 	
}
?>
<form action="excelimport" method="post" enctype="multipart/form-data">
	<input type="hidden" name="msginstancename" value="<?php echo plainstring_to_htmlprotected($this->msgInstanceName) ?>" />
	<input type="hidden" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit) ?>" />
	<input type="file" name="file" value="" /><br />
	<input type="submit" name="import" value="Import" />
</form>
