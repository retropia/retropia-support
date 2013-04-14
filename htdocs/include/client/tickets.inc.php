<?php if(!defined('OSTCLIENTINC') || !is_object($thisclient) || !$thisclient->isValid()) die('Kwaheri');

//Get ready for some deep shit.
$qstr='&'; //Query string collector
$status=null;
if($_REQUEST['status']) { //Query string status has nothing to do with the real status used below.
    $qstr.='status='.urlencode($_REQUEST['status']);
    //Status we are actually going to use on the query...making sure it is clean!
    switch(strtolower($_REQUEST['status'])) {
     case 'open':
     case 'closed':
        $status=$_REQUEST['status'];
        break;
     default:
        $status=''; //ignore
    }
}

//Restrict based on email of the user...STRICT!
$qwhere =' WHERE email='.db_input($thisclient->getEmail());

//STATUS
if($status){
    $qwhere.=' AND status='.db_input($status);    
}
//Admit this crap sucks...but who cares??
$sortOptions=array('date'=>'ticket.created','ID'=>'ticketID','pri'=>'priority_id','dept'=>'dept_name');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');

//Sorting options...
if($_REQUEST['sort']) {
        $order_by =$sortOptions[$_REQUEST['sort']];
}
if($_REQUEST['order']) {
    $order=$orderWays[$_REQUEST['order']];
}
if($_GET['limit']){
    $qstr.='&limit='.urlencode($_GET['limit']);
}

$order_by =$order_by?$order_by:'ticket.created';
$order=$order?$order:'DESC';
$pagelimit=$_GET['limit']?$_GET['limit']:PAGE_LIMIT;
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;

$qselect = 'SELECT ticket.ticket_id,ticket.ticketID,ticket.dept_id,isanswered,ispublic,subject,name,email '.
           ',dept_name,status,source,priority_id ,ticket.created ';
$qfrom=' FROM '.TICKET_TABLE.' ticket LEFT JOIN '.DEPT_TABLE.' dept ON ticket.dept_id=dept.dept_id ';
//Pagenation stuff....wish MYSQL could auto pagenate (something better than limit)
$total=db_count('SELECT count(*) '.$qfrom.' '.$qwhere);
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('view.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));

//Ok..lets roll...create the actual query
$qselect.=' ,count(attach_id) as attachments ';
$qfrom.=' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  ticket.ticket_id=attach.ticket_id ';
$qgroup=' GROUP BY ticket.ticket_id';
$query="$qselect $qfrom $qwhere $qgroup ORDER BY $order_by $order LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
$tickets_res = db_query($query);
$showing=db_num_rows($tickets_res)?$pageNav->showing():"";
$results_type=($status)?ucfirst($status).' Tickets':' All Tickets';
$negorder=$order=='DESC'?'ASC':'DESC'; //Negate the sorting..
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
			<div class="topWrap">
				<p class="headline"><?php echo $results_type?> <span class="showing"><?php echo $showing?></span></p>
				<ul class="ticketButtons">
					<li id="tO"><a href="view.php?status=open">View Open</a></li>
					<li id="tC"><a href="view.php?status=closed">View Closed</a></li>         
			        <li id="tR"><a href="">Refresh</a></li>
			    </ul>
			</div>
		</div>
	</div>

	<div class="container greyBlock">
		<div class="row">
		
			<?php 
        $class = "row1";
        $total=0;
        if($tickets_res && ($num=db_num_rows($tickets_res))):
        	$i = 1;
            $defaultDept=Dept::getDefaultDeptName();
            while ($row = db_fetch_array($tickets_res)) {
            	$even = ($i%2 == 0) ? true : false;
                $dept=$row['ispublic']?$row['dept_name']:$defaultDept; //Don't show hidden/non-public depts.
                $subject=Format::htmlchars(Format::truncate($row['subject'],40));
                $ticketID=$row['ticketID'];
                if($row['isanswered'] && !strcasecmp($row['status'],'open')) {
                    $subject="<b>$subject</b>";
                    $ticketID="<b>$ticketID</b>";
                } $i++;
                ?>
                
            
            
                
            <div class="sixcol <?php if($even) { echo 'last'; } ?> <?php echo $class?>" id="<?php echo $row['ticketID']?>">
            	<div class="ticket">
            		
		            		<?php if (strpos($row['status'],'closed') !== false) {
							    $class = 'green';
							} ?>
							<span class="ticketStatus <?php echo $class; ?>"><?php echo ucfirst($row['status'])?></span>
	            	<ul>
	            		<li class="large">
	            			<span class="heading">Ticket #:</span>
	            			<a class="<?php echo strtolower($row['source'])?>Ticket" title="<?php echo $row['email']?>" href="view.php?id=<?php echo $row['ticketID']?>"><?php echo $ticketID?></a>
	            		</li>
	            		<li>
		            		<span class="heading">Create Date:</span>
		            		<?php echo Format::db_date($row['created'])?>
	            		</li>
	            		<li>
		            		<span class="heading">Subject:</span>
		            		<a href="view.php?id=<?php echo $row['ticketID']?>"><?php echo $subject?></a>
		            		<?php echo $row['attachments']?"<span class='Icon file'>&nbsp;</span>":''?>
	            		</li>
	            		<li>
		            		<span class="heading">Department:</span>
		            		<?php echo Format::truncate($dept,30)?>
	            		</li>
	            		<li>
		            		<span class="heading">Email:</span>
		            		<?php echo Format::truncate($row['email'],40)?>
	            		</li>
	            	</ul>
	            	<a href="view.php?id=<?php echo $row['ticketID']?>" class="button">View ticket</a>
            	</div>
            </div>
		
		
		
		
	
				   
			
			
			

		            <?php 
		            $class = ($class =='row2') ?'row1':'row2';
		            } //end of while.
		        else: //not tickets found!! ?> 
		            <p class="<?php echo $class?>">No tickets found.</p>
		        <?php 
		        endif; ?>
		     
		    <?php 
		    if($num>0 && $pageNav->getNumPages()>1){ //if we actually had any tickets returned?>
		     <p>page:<?php echo $pageNav->getPageLinks()?>&nbsp;</p>
		    <?php }?>
 
		
 	</div>
 </div>

<?php 
