<?php
include('include.php');
if(!isset($title))
$title = "Index";
$title = ' - '.$title;
if(isset($_GET['board']))
$boardText = ' - /'.$_GET['board'].'/ ';

?>
<!DOCTYPE html>
<html>
    <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title><?php echo $chanName.$boardText.$title;?></title>
        <link rel="StyleSheet" href="<?php echo $url;?>style/style.css" type="text/css" media="screen" />
        <script type="text/javascript" src="<?php echo $url;?>script.js">
        </script>
    </head>
    <body>
        <div class="head">
            <a href="<?echo $url.$_GET['board'];?>">Back</a> 
        </div>
		<?
		$thread=$_GET['thread'];
		if(!$_GET['thread'])
		$thread=-1;
		?>
<center>
<form name="newad" method="post" enctype="multipart/form-data" action="<?echo $url;?>upload.php"><input type="hidden" name="parent" value="<?echo $thread;?>" /> <input type="hidden" name="board" value="<?echo $_GET['board'];?>" />
<table class="reply">
<tr>
<td>Name</td>
<td><input name="name" type="text" value="" /></td>
</tr>
<tr>
<td>E-mail</td>
<td><input name="email" type="text" value="" /></td>
</tr>
<tr>
<td>Subject</td>
<td><input name="subject" class="subject" type="text" value="" /><input name="Submit" class="submit" type="submit" value="Submit" /></td>
</tr>
<tr>
<td>Comment:</td>
<td>
<textarea name="comment" type="text" rows="5">
</textarea></td>
</tr>
<tr>
<td>File</td>
<td><input type="file" name="image" /></td>
</tr>
</table>
</form>
</center>