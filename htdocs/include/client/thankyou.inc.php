<?php if(!defined('OSTCLIENTINC') || !is_object($ticket)) die('Kwaheri rafiki!'); //Say bye to our friend..

//Please customize the message below to fit your organization speak!
?>
<div>
    <?php if($errors['err']) {?>
        <p align="center" id="errormessage"><?php echo $errors['err']?></p>
    <?php }elseif($msg) {?>
        <p align="center" id="infomessage"><?php echo $msg?></p>
    <?php }elseif($warn) {?>
        <p id="warnmessage"><?php echo $warn?></p>
    <?php }?>
</div>

	<div class="container greyBlock">
		<div class="row">
			<div class="twelvecol last">
				<p>Thank you for contacting us <?php echo Format::htmlchars($ticket->getName())?>. A support ticket request has been created and a representative will be getting back to you shortly, if necessary.</p>
				<?php if($cfg->autoRespONNewTicket()){ ?>
			    <p>An email with the ticket number has been sent to <strong><?php echo $ticket->getEmail()?></strong>. You'll need the ticket number along with your email to view status and progress online.</p>
			    <p>If you wish to send additional comments or information regarding same issue, please follow the instructions on the email.</p>
			    <?php }?>
			    <p class="sig">Support Team</p>
			</div>
		</div>
	</div>
<?php 
unset($_POST); //clear to avoid re-posting on back button??
?>
