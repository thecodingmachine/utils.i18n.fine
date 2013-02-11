<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

?>
<script type="text/javascript">
/*
jQuery(document).ready(function() {
	jQuery('#fine_missingLabel').fixedtableheader();
});
*/
</script> 
<div id="missingLabelPage">
	<form action="searchLabel" id="searchLabel">
		<input type="hidden" name="backto" value="<?php echo plainstring_to_htmlprotected(ROOT_URL."mouf/editLabels/missinglabels?name=".$this->msgInstanceName."&selfedit=".$this->selfedit); ?>" />
		<input type="hidden" name="msginstancename" value="<?php echo plainstring_to_htmlprotected($this->msgInstanceName); ?>" />
		<input type="hidden" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit); ?>" />
		<p style="text-align: left">Search: <br /><input type="text" name="search" value="" /><br />
			<select name="language_search" class="language input-small">
				<option value="">--</option>
				<?php
				foreach ($this->languages as $language) {
					echo "<option value='".plainstring_to_htmlprotected($language)."'>$language</option>";
				}
				?>
			</select>
			<button type="submit" class="submit btn">Search</button>
		</p>
	</form>

	<h1>Translations</h1>
	
	<form action="editLabel" id="editLabel">
	<input type="hidden" name="backto" value="<?php echo plainstring_to_htmlprotected(ROOT_URL."editLabels/missinglabels?name=".$this->msgInstanceName."&selfedit=".$this->selfedit); ?>" />
	<input type="hidden" name="msginstancename" value="<?php echo plainstring_to_htmlprotected($this->msgInstanceName); ?>" />
	<input type="hidden" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit); ?>" />
	<div>
		<label style="float: left; margin-top: 5px">Add a new label: </label><input type="text" name="key" value="" style="float: left;"/>
		<select name="language" class="input-small" style="float: left;">
		<?php
		foreach ($this->languages as $language) {
			echo "<option value='".plainstring_to_htmlprotected($language)."'>$language</option>";
		}
		?>
		</select>
		<button type="submit" class="btn btn-success" style="float: left;">Add</button>
	</div>
	</form>
	<table id="fine_missingLabel">
		<thead>
		<tr>
			<th>Key</th>
			<?php
			foreach ($this->languages as $language) {
				if($language != "default")
					echo '<th class="language_title">'.$language.'</th>';
				else
					echo "<th>$language</th>";
			}
			?>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($this->msgs as $key => $msgsForKey) {
			echo "<tr><td>$key</td>";
			foreach ($this->languages as $language) {
				echo "<td class='language'>";
				echo "<a href='editLabel?key=".plainstring_to_htmlprotected($key)."&language=".plainstring_to_htmlprotected($language)."&backto=".urlencode(plainstring_to_htmlprotected(ROOT_URL."editLabels/missinglabels?name=".$this->msgInstanceName."&selfedit=".$this->selfedit))."&msginstancename=".plainstring_to_htmlprotected($this->msgInstanceName)."&selfedit=".plainstring_to_htmlprotected($this->selfedit)."'>";
				if (is_null($msgsForKey[$language])) {
					echo "<img src='".ROOT_URL."../utils.i18n.fine/src/views/images/cancel.png' alt='No label provided' title='No label provided' />";
				}
				elseif ($msgsForKey[$language] != "") {
					echo "<img src='".ROOT_URL."../utils.i18n.fine/src/views/images/apply.png' alt='ok' title='".plainstring_to_htmlprotected($msgsForKey[$language])."'/>";
				} else {
					echo "<img src='".ROOT_URL."../utils.i18n.fine/src/views/images/warning.png' alt='warning' title='Empty text'/>";
				}
				echo "</a>";
				
				echo "</td>";
			}
			echo "</tr>";
		}
		?>
		</tbody>
	</table>
</div>
