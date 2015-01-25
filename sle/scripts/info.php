<?php
//if the page has been accesed from having just added a rule then tell the user this
if(isset($_GET['just'])){
echo "<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>";
echo '<script>window.setTimeout(function(){$(".tobe").fadeOut()},3000);</script>';
echo "<div class='tobe' style='position:absolute;top:10px;left:30%;width:30%;height:50px;background-color:orange;'><center><p class='information'>";
echo $_GET['just'];
echo "</p></center></div>";
}
?>
