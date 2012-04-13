<?php
require_once('connect.php'); // import page

if(isset($_REQUEST['submit']))
{
	if($_REQUEST['date']<=date('Y-m-d')) // if selected is in future or not?
	{
	$flag = '0'; // if it is today or before, make it expired.
	}
	else
	{
	$flag = '1'; 
	}
	$title = addslashes(ucwords($_REQUEST['title']));
	$desc = addslashes(ucfirst($_REQUEST['description']));	

$sql->dbQuery("insert into reminders (title,description,date,flag)values('$title','$desc','$_REQUEST[date]','$flag')"); // add reminder
}
$Result = $sql->dbQuery("select * from reminders order by id desc");
$expired_Result = $sql->dbQuery("SELECT * FROM `reminders` WHERE flag = '0' "); // select expired reminders
?>
<!DOCTYPE html >
<html>
<head>
<meta "charset=UTF-8" />
<title>Reminder Application</title>
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.18.custom.css">
<script src="js/jquery-1.7.1.min.js"></script>
<script src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script src="js/jquery.ui.datepicker.js"></script>
<script src="js/jquery.ui.tabs.js"></script>
<script>			
		$(function() {
					$( "#tabs" ).tabs();
				});
				$(function() {
		$( "#date" ).datepicker();
	});
</script>
</head>
<body>
<div id="Container" >
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">All Reminders</a></li>
			<li><a href="#tabs-2">Add Reminder</a></li>
			<li><a href="#tabs-3">Expired Reminders</a></li>
		</ul>
		
		<div id="tabs-1">
				<?php 					
			while($row = $sql->dbFetchAssoc($Result)){?>
			<div id="reminder"  >
			<a href="edit.php?id=<?php echo $row['id'];?>" <?php if($row['flag']== 0){?>style="color:#999999;" <?php }?>>
			<?php echo $row['title'];?>
			</a>
			
			<p <?php if($row['flag']== 0){?>style="color:#999999;" <?php }?>>
			<?php if($row['flag']== 1){echo "set on ".$row['date'];}else{ echo "expired on ".$row['date'];}?>
			</p>
			
			</div>
			<?php }
			$sql->dbFreeResult($Result); ?>		
		</div>	
		
		<div id="tabs-2">		
				<form name="add_reminder" action="" method="post">
					<table width="60%" border="0">
					<tr>
					<td colspan="2" align="center" id="message"></td>
					</tr>
				  <tr>
					<td width="32%">Title</td>
					<td><input type="text" name="title" ></td>
				  </tr>
				  <tr>
					<td>Description</td>
					<td><textarea name="description" cols="30" rows="5" id="description"></textarea></td>
					</tr>
				  <tr>
					<td>Remind me Date</td>
					<td><input type="text" id="date" name="date"></td>
					</tr>
				  <tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="Save Reminder" name="submit" /></td>
				  </tr>
				</table>
				</form>
		</div>
		
		<div id="tabs-3">
			<?php 	
			$numRows = $sql->dbNumRows($expired_Result);
			if($numRows > 0)
			{	
				while($expired = $sql->dbFetchAssoc($expired_Result)){?>
				<div id="reminder"  >
				<a href="edit.php?id=<?php echo $expired['id']?>"><?php echo $expired['title'];?></a>
				<p ><?php echo "expired on ".$expired['date'];?></p>
				
				</div>
				<?php 	}
			}
			else{
			echo "There are no expired Reminders";
			}
			$sql->dbFreeResult($expired_Result);?>
		</div>		
		
	</div>
</div>
</body>
</html>