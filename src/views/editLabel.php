<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

//function editLabel($key, $label, $language, $messagesArray, $is_success, $backto, $msgInstanceName, $selfedit, $saved) {
?>
<script type="text/javascript">
jQuery(document).keydown(function (e) {
	if(e.ctrlKey == true && e.keyCode == 83){
		e.preventDefault();
		jQuery(document.activeElement).parents("form").submit();
	} 
});
</script>
<h1>Edit your label</h1>

<?php 
if($this->saved) {
	echo '<span class="label label-success">';
	echo "Label successfully saved.";
	echo '</span>';
	?>
	<script type="text/javascript">
	setTimeout(function() {
		jQuery('.good').fadeOut(1000);
	}, 4000);
	</script>
	<?php 	
}
?>
<form action="saveLabel" method="post">
	<p>Label for key '<?php echo $this->key ?>' in language <?php echo $this->language ?>:</p>
	<?php 
	if($this->msg == "" && !is_null($this->msg)) {
		echo '<img src="'.ROOT_URL.'../utils.i18n.fine/src/views/images/warning.png" alt="warning" title="Empty text"/> <span class="label label-warning">Caution label is set but empty</span><br />';
	}
	?>
	<input type="hidden" name="key" value="<?php echo plainstring_to_htmlprotected($this->key) ?>" />
	<input type="hidden" name="msginstancename" value="<?php echo plainstring_to_htmlprotected($this->msgInstanceName) ?>" />
	<input type="hidden" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit) ?>" />
	<input type="hidden" name="language" value="<?php echo plainstring_to_htmlprotected($this->language) ?>" />
	<input type="hidden" name="backto" value="<?php echo plainstring_to_htmlprotected($this->backto) ?>" />
	<textarea name="label" style="width: 500px; height: 200px"><?php echo plainstring_to_htmlprotected($this->msg) ?></textarea><br/>
	<label class="checkbox"><input type="checkbox" name="delete" value="delete" id="delete" /> Delete the translation</label><br />
	<input type="submit" name="save" value="Save" class="btn btn-success"/>
	<?php if ($this->backto != null) { ?>
		<input type="submit" name="back" value="Back to application" class="btn btn-important" />
	<?php } ?>

	<p>This message in other languages:</p>
	<table>
		<tr>
			<th style="width:40px">Lang.</th>
			<th>Messages</th>
			<th>Edit</th>
		</tr>
		<?php foreach ($this->messagesArray as $language => $value) { ?>
		<tr>
			<td><?php echo plainstring_to_htmlprotected($language) ?></td>
			<td style="white-space: pre-wrap"><?php echo plainstring_to_htmlprotected($value) ?></td>
			<td><a href="editLabel?key=<?php echo plainstring_to_htmlprotected($this->key) ?>&amp;language=<?php echo plainstring_to_htmlprotected($language) ?>&amp;backto=<?php echo plainstring_to_htmlprotected($this->backto) ?>&amp;msginstancename=<?php echo plainstring_to_htmlprotected($this->msgInstanceName); ?>&amp;selfedit=<?php echo plainstring_to_htmlprotected($this->selfedit) ?>">Edit</a></td>
		</tr>
		<?php } ?>
	</table>
</form>
