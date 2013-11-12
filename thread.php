<?php
include('head.php');
// Get all the data from the "example" table
$result = mysql_query("SELECT * FROM post WHERE `id`='".$_GET['thread']."' AND `parent`=-1 ORDER BY time DESC LIMIT 1") 
or die(mysql_error());  

while($row = mysql_fetch_array( $result )) {

echo '
        <div class="thread">
';
read_post($row);
$posts = mysql_query("SELECT * FROM post WHERE `board`='".$_GET['board']."' AND `parent`=".$row['id']." ORDER BY id asc") 
or die(mysql_error());  
while($post = mysql_fetch_array( $posts )) {
read_post($post);

}

echo '        </div>
';

}
include('foot.php');
?>