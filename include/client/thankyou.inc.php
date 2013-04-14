<?php
if(!defined('OSTCLIENTINC') || !is_object($ticket)) die('Kwaheri rafiki!'); //Say bye to our friend..

//Please customize the message below to fit your organization speak!
?>
<div>
    <?if($errors['err']) {?>
        <p align="center" id="errormessage"><?=$errors['err']?></p>
    <?}elseif($msg) {?>
        <p align="center" id="infomessage"><?=$msg?></p>
    <?}elseif($warn) {?>
        <p id="warnmessage"><?=$warn?></p>
    <?}?>
</div>

	<div class="container greyBlock">
		<div class="row">
			<div class="twelvecol last">
				<p>Thank you for contacting us <?=Format::htmlchars($ticket->getName())?>. A support ticket request has been created and a representative will be getting back to you shortly, if necessary.</p>
				<?if($cfg->autoRespONNewTicket()){ ?>
			    <p>An email with the ticket number has been sent to <strong><?=$ticket->getEmail()?></strong>. You'll need the ticket number along with your email to view status and progress online.</p>
			    <p>If you wish to send additional comments or information regarding same issue, please follow the instructions on the email.</p>
			    <?}?>
			    <p class="sig">Support Team</p>
			</div>
		</div>
	</div>
<?
unset($_POST); //clear to avoid re-posting on back button??
?>
