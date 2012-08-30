<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

function editLabel($key, $label, $language, $messagesArray, $is_success, $backto, $msgInstanceName, $selfedit, $saved) {
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
if($saved) {
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
<form action="saveLabel" method="post">
	<?php if ($is_success) { ?>
		<p style="color:green">The label has been successfully updated.</p>
	<?php } ?>

	<p>Label for key '<?php echo $key ?>' in language <?php echo $language ?>:</p>
	<?php 
	if($label == "" && !is_null($label)) {
		echo "<img src='".ROOT_URL."plugins/utils/icons/crystalproject/1.0/actions/agt_update_critical.png' alt='warning' title='Empty text'/> Caution label is set but empty<br />";
	}
	?>
	<input type="hidden" name="key" value="<?php echo plainstring_to_htmlprotected($key) ?>" />
	<input type="hidden" name="msginstancename" value="<?php echo plainstring_to_htmlprotected($msgInstanceName) ?>" />
	<input type="hidden" name="selfedit" value="<?php echo plainstring_to_htmlprotected($selfedit) ?>" />
	<input type="hidden" name="language" value="<?php echo plainstring_to_htmlprotected($language) ?>" />
	<input type="hidden" name="backto" value="<?php echo plainstring_to_htmlprotected($backto) ?>" />
	<!--<input type="text" name="label" value="<?php echo plainstring_to_htmlprotected($label) ?>" size="80" /><br/>-->
	<textarea name="label" rows="5" cols="80"><?php echo plainstring_to_htmlprotected($label) ?></textarea><br/>
	<input type="checkbox" name="delete" value="delete" /> Delete the translation<br />
	<input type="submit" name="save" value="Save" />
	<?php if ($backto != null) { ?>
	<input type="submit" name="back" value="Back to application" />
	<?php } ?>

	<p>This message in other languages:</p>
	<table>
		<tr>
			<th style="width:100px">Language</th>
			<th>Messages</th>
			<th>Edit</th>
		</tr>
		<?php foreach ($messagesArray as $language=>$value) { ?>
		<tr>
			<td><?php echo plainstring_to_htmlprotected($language) ?></td>
			<td style="white-space: pre-wrap"><?php echo plainstring_to_htmlprotected($value) ?></td>
			<td><a href="editLabel?key=<?php echo plainstring_to_htmlprotected($key) ?>&amp;language=<?php echo plainstring_to_htmlprotected($language) ?>&amp;backto=<?php echo plainstring_to_htmlprotected($backto) ?>&amp;msginstancename=<?php echo plainstring_to_htmlprotected($msgInstanceName); ?>&amp;selfedit=<?php echo plainstring_to_htmlprotected($selfedit) ?>">Edit</a></td>
		</tr>
		<?php } ?>
	</table>
</form>
<?php
}