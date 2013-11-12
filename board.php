<?php
include('head.php');
// Get all the data from the "example" table
$result2 = mysql_query("SELECT * FROM post WHERE `board`='".$_GET['board']."' ORDER BY time DESC") 
or die(mysql_error());  
while($row2 = mysql_fetch_array( $result2 )) {
if($row2['email']=="sage")
continue;
$thread = $row2['parent'];
if($thread==-1)
$thread=$row2['id'];
$result = mysql_query("SELECT * FROM post WHERE `board`='".$_GET['board']."' AND `id`=".$thread) 
or die(mysql_error()); 
$count=0;
while($row = mysql_fetch_array( $result )) {
if($set[$row['id']]==10)
continue;
$set[$row['id']]=10;
echo '
        <div class="thread">
';
read_post($row);
$posts = mysql_query("SELECT * FROM post WHERE `board`='".$_GET['board']."' AND `parent`=".$row['id']." ORDER BY id desc") 
or die(mysql_error());  
$i = 0;
while($post = mysql_fetch_array( $posts )) {
$post_b[$i]=$post;
$i++;
}
if($i>3)
echo '<div class="morereplies">Click reply to view the other '.($i-3).' replies</div><div class="break">&nbsp;</div> 
';
$a=$i-1;
if($i>2)
$a=2;
for($a=$a;$a>=0;$a--) {
read_post($post_b[$a]);
}

echo '        </div>
';

}
}
include('foot.php');
?>