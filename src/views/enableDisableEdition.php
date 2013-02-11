<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

?>
<h1>Message edition</h1>
<p>When navigating in your application, you can have links automatically added by Fine to edit your messages. By enabling 
the message edition feature, you will be able to edit messages directly into you web application.</p>
<p>Message edition is <b><?php echo ($this->isMessageEditionMode==true)?"enabled":"disabled" ?></b></p>
<p>Change message edition status:</p>
<form action="setMode" method="post">
	<label class="radio">
		<input type="radio" name="mode" value="on" <?php echo ($this->isMessageEditionMode==true)?"checked":"" ?>>Enable
	</label>
	<label class="radio">
		<input type="radio" name="mode" value="off" <?php echo ($this->isMessageEditionMode==true)?"":"checked" ?>>Disable
	</label>
	<input type="submit" value="Save" class="btn" />
</form>
