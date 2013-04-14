<?php if(!defined('OSTCLIENTINC')) die('Kwaheri rafiki!'); //Say bye to our friend..

$info=($_POST && $errors)?Format::input($_POST):array(); //on error...use the post data
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
				        <label>Name: <span class="required">(required)</span>&nbsp;<font class="error">&nbsp;<?php echo $errors['name']?></font></label>
				            <?php if ($thisclient && ($name=$thisclient->getName())) {
				                ?>
				                <input type="hidden" name="name" value="<?php echo $name?>"><?php echo $name?>
				            <?php }else {?>
				                <input type="text" name="name" size="25" value="<?php echo $info['name']?>">
					        <?php }?>
				            
				    </div>
				    
				    <div>
				        <label>Email Address: <span class="required">(required)</span>&nbsp;<font class="error">&nbsp;<?php echo $errors['email']?></font></label>
				            <?php if ($thisclient && ($email=$thisclient->getEmail())) {
				                ?>
				                <input type="hidden" name="email" size="25" value="<?php echo $email?>"><?php echo $email?>
				            <?php }else {?>             
				                <input type="text" name="email" size="25" value="<?php echo $info['email']?>">
				            <?php }?>
				            
				    </div>
				    
				    <div>
				        <label>Help Topic: <span class="required">(required)</span>&nbsp;<font class="error">&nbsp;<?php echo $errors['topicId']?></font></label>
				            <select name="topicId">
				                <?php 
				                 $services= db_query('SELECT topic_id,topic FROM '.TOPIC_TABLE.' WHERE isactive=1 ORDER BY topic');
				                 if($services && db_num_rows($services)) {
				                     while (list($topicId,$topic) = db_fetch_row($services)){
				                        $selected = ($info['topicId']==$topicId)?'selected':''; ?>
				                        <option value="<?php echo $topicId?>"<?php echo $selected?>><?php echo $topic?></option>
				                        <?php 
				                     }
				                 }else{?>
				                    <option value="0" >General Enquiry</option>
				                <?php }?>
				            </select>
				            
				    </div>
				    
				    <div>
				        <label>Subject: <span class="required">(required)</span>&nbsp;<font class="error">&nbsp;<?php echo $errors['subject']?></font></label>
				        
				        <input type="text" name="subject" size="35" value="<?php echo $info['subject']?>">
				        
				    </div>
				    
				    <div>
				        <label>Message:</label>
				        
				            <?php  if($errors['message']) {?> <font class="error"><b>&nbsp;<?php echo $errors['message']?></b></font><br/><?php }?>
				            <textarea name="message" cols="35" rows="8" wrap="soft"><?php echo $info['message']?></textarea>
				    </div>
				    
				    <?php 
				    if($cfg->allowPriorityChange() ) {
				      $sql='SELECT priority_id,priority_desc FROM '.TICKET_PRIORITY_TABLE.' WHERE ispublic=1 ORDER BY priority_urgency DESC';
				      if(($priorities=db_query($sql)) && db_num_rows($priorities)){ ?>
				      
				      <div>
				        <label>Priority:</label>
				        
				            <select name="pri">
				              <?php 
				                $info['pri']=$info['pri']?$info['pri']:$cfg->getDefaultPriorityId(); //use system's default priority.
				                while($row=db_fetch_array($priorities)){ ?>
				                    <option value="<?php echo $row['priority_id']?>" <?php echo $info['pri']==$row['priority_id']?'selected':''?> ><?php echo $row['priority_desc']?></option>
				              <?php }?>
				            </select>
				       </div>
				       
				    <?php  }
				    }?>
				
				    <?php if(($cfg->allowOnlineAttachments() && !$cfg->allowAttachmentsOnlogin())  
				                || ($cfg->allowAttachmentsOnlogin() && ($thisclient && $thisclient->isValid()))){
				        
				        ?>
				    <div>
				        <label>Attachment:</label>
				            <input type="file" name="attachment"><font class="error">&nbsp;<?php echo $errors['attachment']?></font>
				    </div>
				    
				    <?php }?>
				    <?php if($cfg && $cfg->enableCaptcha() && (!$thisclient || !$thisclient->isValid())) {
				        if($_POST && $errors && !$errors['captcha'])
				            $errors['captcha']='Please re-enter the text again';
				        ?>
				    <div>
				        <label>Captcha Text:</label>
				        <img src="captcha.php" border="0" align="left">
				        <span>&nbsp;&nbsp;<input type="text" name="captcha" size="7" value="">&nbsp;<i>Enter the text shown on the image.</i></span><br/>
				                <font class="error">&nbsp;<?php echo $errors['captcha']?></font>
				    </div>
				    
				    <?php }?>
				    
				    <div>
				            <input class="button" type="submit" name="submit_x" value="Submit Ticket">
				    </div>
				
				</form>
			</div>
		</div>
	</div>
