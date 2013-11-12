<?php
function lastIndexOf($string, $item) {
  $index = strpos(strrev($string), strrev($item));
  if ($index) {
    $index = strlen($string) - strlen($item) - $index;
    return $index;
  }
  else
    return - 1;
}
function createthumb($name, $filename, $new_w, $new_h) {
  $system = explode('.', $name);
  if (preg_match('/jpg|jpeg/', $system[1])) {
    $src_img = imagecreatefromjpeg($name);
  }
  if (preg_match('/png/', $system[1])) {
    $src_img = imagecreatefrompng($name);
  }
  if (preg_match('/gif/', $system[1])) {
    $src_img = imagecreatefromgif($name);
  }
  $old_x = imageSX($src_img);
  $old_y = imageSY($src_img);
  if ($old_x > $old_y) {
    $thumb_w = $new_w;
    $thumb_h = $old_y * ($new_h / $old_x);
  }
  if ($old_x < $old_y) {
    $thumb_w = $old_x * ($new_w / $old_y);
    $thumb_h = $new_h;
  }
  if ($old_x == $old_y) {
    $thumb_w = $new_w;
    $thumb_h = $new_h;
  }
  $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
  imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
  imagejpeg($dst_img, $filename, 75);
  imagedestroy($dst_img);
  imagedestroy($src_img);
}
function tripcode($pw) {
  $pw = mb_convert_encoding($pw, 'SJIS', 'UTF-8');
  $pw = str_replace('&', '&amp;', $pw);
  $pw = str_replace('"', '&quot;', $pw);
  $pw = str_replace("'", '&#39;', $pw);
  $pw = str_replace('<', '&lt;', $pw);
  $pw = str_replace('>', '&gt;', $pw);
  $salt = substr($pw . 'H.', 1, 2);
  $salt = preg_replace('/[^.\/0-9:;<=>?@A-Z\[\\\]\^_`a-z]/', '.', $salt);
  $salt = strtr($salt, ':;<=>?@[\]^_`', 'ABCDEFGabcdef');
  $trip = substr(crypt($pw, $salt), - 10);
  return $trip;
}
include ('config.php');
function post_head($row, $image) {
  global $url;
  if (strlen($row['subject']) > 0) {
    $email = "<a href=\"mailto:" . $row['email'] . "\">";
    $email2 = "</a>";
  }
  echo '                <div class="postheader">
                    <input name="' . $row['id'] . '" type="checkbox" id="delete' . $row['id'] . '" />
';
  if (strlen($row['subject']) > 0)
    echo '                    <span class="subject">' . $row['subject'] . ' </span>
';
  echo '                    <span class="name">' . $email . $row['name'] . $email2 . '</span>
                    ' . date("m/d/y(D)H:i:s", strtotime($row['time'])) . ' No.' . $row['id'];
  if ($row['parent'] == - 1 && !isset ($_GET['thread'])) {
    echo '<a class="reply" href="' . $url . $_GET['board'] . '/' . $row['id'] . '">Reply</a>';
  }
  if (isset ($image['id'])) {
    $iname = $image['name'];
    if (strlen($image['name']) > 15)
      $iname = substr($image['name'], 0, 10) . '(...)';
    echo '<div class="imageinfo">File: <a href="' . $url . '' . $row['board'] . '/' . $row['id'] . '/' . $image['name'] . '.' . $image['type'] . '">' . $iname . '.' . $image['type'] . '</a> (' . bytes($image['size']) . ')</div>';
  }
  echo '
                </div>
';
}
function read_post($row) {
  global $url;
  if ($row['parent'] == - 1) {
    $type = "topic";
  }
  else {
    $type = "post";
  }
  $imageresult = mysql_query("SELECT * FROM upload WHERE `board`='" . $row['board'] . "' AND `post`=" . $row['id'] . " LIMIT 1");
  $image = mysql_fetch_array($imageresult);
  echo '           <div class="' . $type . '">
';
  if ($row['parent'] != - 1) {
    post_head($row, $image);
  }
  if ($image != null) {
    echo '
               <div class="image">
                   <a href="' . $url . $row['board'] . '/' . $row['id'] . '/' . $image['name'] . '.' . $image['type'] . '"><img src="' . $url . $row['board'] . '/' . $row['id'] . '/thumb-' . $image['name'] . '.' . $image['type'] . '.jpg"/></a>
               </div>
';
  }
  else {
    echo '<div class="noimage">&nbsp;</div>';
  }
  if ($row['parent'] == - 1) {
    post_head($row, $image);
  }
  if (strlen($row['text']) > 0)
    echo '               <div class="posttext">' . wordwrap($row['text'], 75, " ", true) . '</div>
  ';
  echo '
           </div><div class="break">&nbsp;</div>
';
}
function bytes($a) {
  $unim = array("B", "KB", "MB", "GB", "TB", "PB");
  $c = 0;
  while ($a >= 1024) {
    $c++;
    $a = $a / 1024;
  }
  return number_format($a, ($c ? 1 : 0), ".", ",") . "" . $unim[$c];
}
?>