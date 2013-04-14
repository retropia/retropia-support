<?php
if(!defined('OSTCLIENTINC')) die('Kwaheri rafiki!'); //Say bye to our friend..

$info=($_POST && $errors)?Format::input($_POST):array(); //on error...use the post data
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

	<div class="container">
		<div class="row">
			<div class="twelvecol last">
				<p class="headline">Please fill in the form below to open a new ticket.</p>
			</div>
		</div>
	</div>
	
	<div class="container greyBlock">
		<div class="row">
			<div class="twelvecol last">
				<form action="open.php" method="POST" enctype="multipart/form-data" id="newTicket">
				
				    <div>
				        <label>Full Name: <span class="required">(required)</span>&nbsp;<font class="error">&nbsp;<?=$errors['name']?></font></label>
				            <?if ($thisclient && ($name=$thisclient->getName())) {
				                ?>
				                <input type="hidden" name="name" value="<?=$name?>"><?=$name?>
				            <?}else {?>
				                <input type="text" name="name" size="25" value="<?=$info['name']?>">
					        <?}?>
				            
				    </div>
				    
				    <div>
				        <label>Email Address: <span class="required">(required)</span>&nbsp;<font class="error">&nbsp;<?=$errors['email']?></font></label>
				            <?if ($thisclient && ($email=$thisclient->getEmail())) {
				                ?>
				                <input type="hidden" name="email" size="25" value="<?=$email?>"><?=$email?>
				            <?}else {?>             
				                <input type="text" name="email" size="25" value="<?=$info['email']?>">
				            <?}?>
				            
				    </div>
				    
				    <div>
				        <label>Telephone:</label>
				        <input type="text" name="phone" size="25" value="<?=$info['phone']?>">
				            
				    </div>
				    
				    <div>
				        <label>Help Topic: <span class="required">(required)</span>&nbsp;<font class="error">&nbsp;<?=$errors['topicId']?></font></label>
				            <select name="topicId">
				                <?
				                 $services= db_query('SELECT topic_id,topic FROM '.TOPIC_TABLE.' WHERE isactive=1 ORDER BY topic');
				                 if($services && db_num_rows($services)) {
				                     while (list($topicId,$topic) = db_fetch_row($services)){
				                        $selected = ($info['topicId']==$topicId)?'selected':''; ?>
				                        <option value="<?=$topicId?>"<?=$selected?>><?=$topic?></option>
				                        <?
				                     }
				                 }else{?>
				                    <option value="0" >General Enquiry</option>
				                <?}?>
				            </select>
				            
				    </div>
				    
				    <div>
				        <label>Subject: <span class="required">(required)</span>&nbsp;<font class="error">&nbsp;<?=$errors['subject']?></font></label>
				        
				        <input type="text" name="subject" size="35" value="<?=$info['subject']?>">
				        
				    </div>
				    
				    <div>
				        <label>Message:</label>
				        
				            <? if($errors['message']) {?> <font class="error"><b>&nbsp;<?=$errors['message']?></b></font><br/><?}?>
				            <textarea name="message" cols="35" rows="8" wrap="soft"><?=$info['message']?></textarea>
				    </div>
				    
				    <?
				    if($cfg->allowPriorityChange() ) {
				      $sql='SELECT priority_id,priority_desc FROM '.TICKET_PRIORITY_TABLE.' WHERE ispublic=1 ORDER BY priority_urgency DESC';
				      if(($priorities=db_query($sql)) && db_num_rows($priorities)){ ?>
				      
				      <div>
				        <label>Priority:</label>
				        
				            <select name="pri">
				              <?
				                $info['pri']=$info['pri']?$info['pri']:$cfg->getDefaultPriorityId(); //use system's default priority.
				                while($row=db_fetch_array($priorities)){ ?>
				                    <option value="<?=$row['priority_id']?>" <?=$info['pri']==$row['priority_id']?'selected':''?> ><?=$row['priority_desc']?></option>
				              <?}?>
				            </select>
				       </div>
				       
				    <? }
				    }?>
				
				    <?if(($cfg->allowOnlineAttachments() && !$cfg->allowAttachmentsOnlogin())  
				                || ($cfg->allowAttachmentsOnlogin() && ($thisclient && $thisclient->isValid()))){
				        
				        ?>
				    <div>
				        <label>Attachment:</label>
				            <input type="file" name="attachment"><font class="error">&nbsp;<?=$errors['attachment']?></font>
				    </div>
				    
				    <?}?>
				    <?if($cfg && $cfg->enableCaptcha() && (!$thisclient || !$thisclient->isValid())) {
				        if($_POST && $errors && !$errors['captcha'])
				            $errors['captcha']='Please re-enter the text again';
				        ?>
				    <div>
				        <label>Captcha Text:</label>
				        <img src="captcha.php" border="0" align="left">
				        <span>&nbsp;&nbsp;<input type="text" name="captcha" size="7" value="">&nbsp;<i>Enter the text shown on the image.</i></span><br/>
				                <font class="error">&nbsp;<?=$errors['captcha']?></font>
				    </div>
				    
				    <?}?>
				    
				    <div>
				            <input class="button" type="submit" name="submit_x" value="Submit Ticket">
				    </div>
				
				</form>
			</div>
		</div>
	</div>
