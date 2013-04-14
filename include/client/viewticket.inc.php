<?php if(!defined('OSTCLIENTINC') || !is_object($thisclient) || !is_object($ticket)) die('Kwaheri'); //bye..see ya
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
					<li class="ticketNo">Ticket #: <?php echo $ticket->getExtId()?></li>
					<li class="ticketSubject">Subject: <?php echo Format::htmlchars($ticket->getSubject())?></li>
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
						<span class="ticketStatus <?php echo $class; ?>"><?php echo $ticket->getStatus()?></span>
					</p>
				</div>
	            <div>
	                <span class="heading">Department:</span>
	                <p><?php echo Format::htmlchars($dept->getName())?></p>
	            </div>
				<div>
	                <span class="heading">Create Date:</span>
	                <p><?php echo Format::db_datetime($ticket->getCreateDate())?></p>
	            </div>
						
			</div>
			<div class="sixcol last">
	            <div>
	                <span class="heading">Name:</span>
	                <p><?php echo Format::htmlchars($ticket->getName())?></p>
	            </div>
	            <div>
	                <span class="heading">Email:</span>
	                <p><?php echo $ticket->getEmail()?></p>
	            </div>
	            <div>
	                <span class="heading">Phone:</span>
	                <p><?php echo Format::phone($ticket->getPhoneNumber())?></p>
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
			        <?php 
				    //get messages
			        $sql='SELECT msg.*, count(attach_id) as attachments  FROM '.TICKET_MESSAGE_TABLE.' msg '.
			            ' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  msg.ticket_id=attach.ticket_id AND msg.msg_id=attach.ref_id AND ref_type=\'M\' '.
			            ' WHERE  msg.ticket_id='.db_input($ticket->getId()).
			            ' GROUP BY msg.msg_id ORDER BY created';
				    $msgres =db_query($sql);
				    while ($msg_row = db_fetch_array($msgres)):
					    ?>
					    <div class="ticketMsg">
			                <?php if($msg_row['attachments']>0){ ?>
			                <?php echo $ticket->getAttachmentStr($msg_row['msg_id'],'M')?>
			                <?php }?>
			                <p><?php echo Format::display($msg_row['message'])?></p>
			                <span class="msgDate"><?php echo Format::db_daydatetime($msg_row['created'])?></span>
					    </div>
			            <?php 
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
			    		    	<span class="msgDate"><?php echo Format::db_daydatetime($msg_row['created'])?></span>
			                    <?php if($resp_row['attachments']>0){ ?>
			                    <?php echo $ticket->getAttachmentStr($respID,'R')?>             
			                    <?php }?>
						        <p><?php echo Format::display($resp_row['response'])?></p>
			    		    </div>       
					    <?php 
					    } //endwhile...response loop.
			            $msgid =$msg_row['msg_id'];
			        endwhile; //message loop.
			     ?>
			    </div>

				<div>
				    <div id="reply">
				        <?php if($ticket->isClosed()) {?>
				        <div class="msg">Ticket will be reopened on message post</div>
				        <?php }?>
				        <form action="view.php?id=<?php echo $id?>#reply" name="reply" method="post" enctype="multipart/form-data">
				            <input type="hidden" name="id" value="<?php echo $ticket->getExtId()?>">
				            <input type="hidden" name="respid" value="<?php echo $respID?>">
				            <input type="hidden" name="a" value="postmessage">
				            <div>
				            	<?php if($_POST && $errors['err']) {?>
						            <p id="errormessage"><?php echo $errors['message']?></p>
						        <?php }elseif($msg) {?>
						            <p class="green"><b><?php echo $msg?></b></p>
						        <?php }?>
				            	<label>Enter Message:</label>
				                <textarea name="message" id="message" cols="60" rows="7" wrap="soft" style="width:100%"><?php echo $info['message']?></textarea>
				            </div>
				            <?php  if($cfg->allowOnlineAttachments()) {?>
				            
				                Attach File<br><input type="file" name="attachment" id="attachment" size=30px value="<?php echo $info['attachment']?>" /> 
				                    <font class="error">&nbsp;<?php echo $errors['attachment']?></font>
				            
				            <?php }?>
				            <input class="button" type='submit' value='Post Reply' id="postReply" />
				        </form>
				    </div>
				</div>
			</div>
		</div>
	</div>
