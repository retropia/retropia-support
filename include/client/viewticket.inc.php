<?php
if(!defined('OSTCLIENTINC') || !is_object($thisclient) || !is_object($ticket)) die('Kwaheri'); //bye..see ya
//Double check access one last time...
if(strcasecmp($thisclient->getEmail(),$ticket->getEmail())) die('Access Denied');

$info=($_POST && $errors)?Format::input($_POST):array(); //Re-use the post info on error...savekeyboards.org

$dept = $ticket->getDept();
//Making sure we don't leak out internal dept names
$dept=($dept && $dept->isPublic())?$dept:$cfg->getDefaultDept();
//We roll like that...
?>

	<div class="container">
		<div class="row">
			<div class="twelvecol last">
				<ul id="ticketInfo">
					<li class="ticketNo">Ticket #: <?=$ticket->getExtId()?></li>
					<li class="ticketSubject">Subject: <?=Format::htmlchars($ticket->getSubject())?></li>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="container greyBlock ticketOverview">
		<div class="row">
			<div class="sixcol">
		        <div>
					<span class="heading">Ticket Status:</span>
					<p>
					<?php if (strpos($ticket->getStatus(),'closed') !== false) {
					    $class = 'green';
					} ?>
						<span class="ticketStatus <?php echo $class; ?>"><?=$ticket->getStatus()?></span>
					</p>
				</div>
	            <div>
	                <span class="heading">Department:</span>
	                <p><?=Format::htmlchars($dept->getName())?></p>
	            </div>
				<div>
	                <span class="heading">Create Date:</span>
	                <p><?=Format::db_datetime($ticket->getCreateDate())?></p>
	            </div>
						
			</div>
			<div class="sixcol last">
	            <div>
	                <span class="heading">Name:</span>
	                <p><?=Format::htmlchars($ticket->getName())?></p>
	            </div>
	            <div>
	                <span class="heading">Email:</span>
	                <p><?=$ticket->getEmail()?></p>
	            </div>
	            <div>
	                <span class="heading">Phone:</span>
	                <p><?=Format::phone($ticket->getPhoneNumber())?></p>
	            </div>
			</div>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="twelvecol last">
				<p class="headline ticketThread">Ticket Thread</p>
			</div>
		</div>
	</div>
				
	<div class="container greyBlock">
		<div class="row">
			<div class="twelvecol last">		
			    <div id="ticketthread">
			        <?
				    //get messages
			        $sql='SELECT msg.*, count(attach_id) as attachments  FROM '.TICKET_MESSAGE_TABLE.' msg '.
			            ' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  msg.ticket_id=attach.ticket_id AND msg.msg_id=attach.ref_id AND ref_type=\'M\' '.
			            ' WHERE  msg.ticket_id='.db_input($ticket->getId()).
			            ' GROUP BY msg.msg_id ORDER BY created';
				    $msgres =db_query($sql);
				    while ($msg_row = db_fetch_array($msgres)):
					    ?>
					    <div class="ticketMsg">
			                <?if($msg_row['attachments']>0){ ?>
			                <?=$ticket->getAttachmentStr($msg_row['msg_id'],'M')?>
			                <?}?>
			                <p><?=Format::display($msg_row['message'])?></p>
			                <span class="msgDate"><?=Format::db_daydatetime($msg_row['created'])?></span>
					    </div>
			            <?
			            //get answers for messages
			            $sql='SELECT resp.*,count(attach_id) as attachments FROM '.TICKET_RESPONSE_TABLE.' resp '.
			                ' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  resp.ticket_id=attach.ticket_id AND resp.response_id=attach.ref_id AND ref_type=\'R\' '.
			                ' WHERE msg_id='.db_input($msg_row['msg_id']).' AND resp.ticket_id='.db_input($ticket->getId()).
			                ' GROUP BY resp.response_id ORDER BY created';
			            //echo $sql;
					    $resp =db_query($sql);
					    while ($resp_row = db_fetch_array($resp)) {
			                $respID=$resp_row['response_id'];
			                $name=$cfg->hideStaffName()?'staff':Format::htmlchars($resp_row['staff_name']);
			                ?>
			    		    <div class="ticketMsg adminTicketMsg">
			    		    	<span class="adminMsg">Admin</span>
			    		    	<span class="msgDate"><?=Format::db_daydatetime($msg_row['created'])?></span>
			                    <?if($resp_row['attachments']>0){ ?>
			                    <?=$ticket->getAttachmentStr($respID,'R')?>             
			                    <?}?>
						        <p><?=Format::display($resp_row['response'])?></p>
			    		    </div>       
					    <?
					    } //endwhile...response loop.
			            $msgid =$msg_row['msg_id'];
			        endwhile; //message loop.
			     ?>
			    </div>

				<div>
				    <div id="reply">
				        <?if($ticket->isClosed()) {?>
				        <div class="msg">Ticket will be reopened on message post</div>
				        <?}?>
				        <form action="view.php?id=<?=$id?>#reply" name="reply" method="post" enctype="multipart/form-data">
				            <input type="hidden" name="id" value="<?=$ticket->getExtId()?>">
				            <input type="hidden" name="respid" value="<?=$respID?>">
				            <input type="hidden" name="a" value="postmessage">
				            <div>
				            	<?if($_POST && $errors['err']) {?>
						            <p id="errormessage"><?=$errors['message']?></p>
						        <?}elseif($msg) {?>
						            <p class="green"><b><?=$msg?></b></p>
						        <?}?>
				            	<label>Enter Message:</label>
				                <textarea name="message" id="message" cols="60" rows="7" wrap="soft" style="width:100%"><?=$info['message']?></textarea>
				            </div>
				            <? if($cfg->allowOnlineAttachments()) {?>
				            
				                Attach File<br><input type="file" name="attachment" id="attachment" size=30px value="<?=$info['attachment']?>" /> 
				                    <font class="error">&nbsp;<?=$errors['attachment']?></font>
				            
				            <?}?>
				            <input class="button" type='submit' value='Post Reply' id="postReply" />
				        </form>
				    </div>
				</div>
			</div>
		</div>
	</div>
