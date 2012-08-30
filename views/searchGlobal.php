<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

?>
<style type="text/css">
	table#fin_searchlabel th {
		background-color: #CCCCCC;
	}
	table#fin_searchlabel th.instance {
		background-color: #AAAAAA;
	}
</style>

<h2>FINE 2.1 Results</h2>
<?php if($this->error) {?>
	no research
<?php } else {?>
	<table id="fin_searchlabel" style="clear: both; width: 100%">
	<?php
	foreach ($this->results as $instance => $elements) {
		echo "<tr><th colspan='3' class='instance'>Instance: ".$instance."</th></tr>";
		if($elements) {
			foreach ($elements as $key => $languages) {
				echo "<tr><th colspan='3'>".$key."</th></tr>";
				foreach ($languages as $lang => $value) {
					echo '<tr>
							<td style="width: 20px">
								<a href="'.ROOT_URL.'mouf/editLabels/editLabel?key='.plainstring_to_htmlprotected($key).'&language='.plainstring_to_htmlprotected($lang).'&backto='.urlencode(plainstring_to_htmlprotected(ROOT_URL."mouf/editLabels/missinglabels?name=".$instance."&selfedit=".$this->selfedit)).'&msginstancename='.plainstring_to_htmlprotected($instance).'&selfedit='.plainstring_to_htmlprotected($this->selfedit).'">
									<img src="'.ROOT_URL.'plugins/utils/icons/crystalproject/1.0/actions/configure.png" alt="edit" />
								</a>
							</td>
							<td style="width: 48px"><b>'.$lang.'</b></td>
							<td>'.plainstring_to_htmlprotected($value).'</td>
						</tr>';
				}
				echo '<tr><td>&nbsp;</td></tr>';
			}
		}
		else {
			echo '<tr><td colspan="3">No result</td></tr>';
		}
	}
	?>
	</table>
<?php } ?>
