<?php
include('include.php');
//define a maxim size for the uploaded images in Kb
 define ("MAX_SIZE","2560");


 if(isset($_POST['Submit'])) 
 {
 	//get the original name of the file from the clients machine
        $filename = preg_replace("#[^a-z0-9_.-]#i", "", strtolower (stripslashes(mysql_real_escape_string(htmlentities($_FILES['image']['name'])))));
        $name = mysql_real_escape_string(htmlentities($_POST['name']));
        $email = mysql_real_escape_string(htmlentities($_POST['email']));
        $subject = mysql_real_escape_string(htmlentities($_POST['subject']));
        $comment = mysql_real_escape_string(htmlentities($_POST['comment']));
        $parent = mysql_real_escape_string(htmlentities($_POST['parent']));
        $board = mysql_real_escape_string(htmlentities($_POST['board']));
        if(strlen($name)==0)
            $name="Anonymous";

 	//reads the name of the file the user submitted for uploading
 	$image=$filename;
 	//if it is not empty
	if($_POST['parent']==-1 && !$image) {
	    die('You must upload an image to your thread!');
	}


 	if ($image) 
 	{

 if ($_FILES['image']['size'] > MAX_SIZE*1024 )
{
die ("ERROR: Large File Size");

}

//check if its image file

if (!getimagesize($_FILES['image']['tmp_name']))
{ 
die("Invalid Image File...");
}		
 	//if it is not a known extension, we will suppose it is an error and will not  upload the file,  
	//otherwise we will do more tests

//get the size of the image in bytes
 //$_FILES['image']['tmp_name'] is the temporary filename of the file
 //in which the uploaded file was stored on the server
 $size=filesize($_FILES['image']['tmp_name']);

//compare the size with the maxim size we defined and print error if bigger
if ($size > MAX_SIZE*1024)
{
	die('<h1>You have exceeded the size limit!</h1>');
	$errors=1;

}

//we will give an unique name, for example the time in unix time format
//the new name will be containing the full path where will be stored (images folder)
}      else {
  	 	if (strlen($comment)<1)
            die("ERROR: Comment field empty");
}
mysql_query("INSERT INTO `".$db."`.`post` (`id`, `parent`, `board`, `subject`, `name`, `email`, `text`, `time`) 
VALUES (NULL, '".$parent."', '".$board."', '".$subject."', '".$name."', '".$email."', '".$comment."', CURRENT_TIMESTAMP);") 
or die(mysql_error()); 
$post = mysql_insert_id();


 	if ($image)  {
$info = pathinfo($filename);
$newname="images/full/".$post.".".$info['extension'];
$info['filename'] = str_replace(".","",$info['filename']);
if(strlen($info['filename'])<1)
$info['filename']="untitled";
//we verify if the image has been uploaded, and print error instead
$copied = $errors==0 && copy($_FILES['image']['tmp_name'], $newname);
if (!$copied) 
{
	die('<h1>Copy unsuccessfull!</h1>');

}
	if(isset($_POST['Submit']) && $errors==0) 
 {
 if($_POST['parent']==-1) {
        createthumb($newname,"images/thumb/".$post.".".$info['extension'],250,250);
	} else {
        createthumb($newname,"images/thumb/".$post.".".$info['extension'],100,100);

	}
 }
mysql_query("INSERT INTO `".$db."`.`upload` (`id`, `name`, `board`, `post`, `type`, `size`) VALUES (NULL, '".$info['filename']."','".$board."',".$post.",'".$info['extension']."',".$_FILES['image']['size'].");") 
or die(mysql_error()); 
if($_POST['parent']==-1)
$_POST['parent']=$post;

	} 
}
header('Location: '.$url.''.$_POST['board'].'/'.$_POST['parent']);



 ?>