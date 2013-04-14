<?php
if(!defined('OSTCLIENTINC')) die('Kwaheri');

$e=Format::input($_POST['lemail']?$_POST['lemail']:$_GET['e']);
$t=Format::input($_POST['lticket']?$_POST['lticket']:$_GET['t']);
?>
	<div>
	    <?if($errors['err']) {?>
	        <p align="center" id="errormessage"><?=$errors['err']?></p>
	    <?}elseif($warn) {?>
	        <p class="warnmessage"><?=$warn?></p>
	    <?}?>
	</div>

	<div class="container">
		<div class="row">
			<div class="twelvecol last">
			    <p class="headline">To view the status of a ticket, provide us with your login details below.</p>
			    <p>If this is your first time contacting us or you've lost the ticket ID, please <a href="open.php">click here</a> to open a new ticket.</p>
	    	</div>
		</div>
	</div>
	
	<div class="container greyBlock">
		<div class="row">
			<div class="twelvecol last">
			    <form class="status_form ticket_status_form" action="login.php" method="post">
					<div>
						<label>Email Address</label>
						<input type="text" name="lemail" size="50">
					</div>
					<div>
						<label>Ticket #</label>
						<input type="text" name="lticket" size="50">
					</div>
					<input type="submit" class="button" value="Check Status">
				</form>
			    <span class="error"><?=Format::htmlchars($loginmsg)?></span>
	    	</div>
		</div>
	</div>
	
	
