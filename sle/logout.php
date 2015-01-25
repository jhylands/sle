<?php
//cause the cookie to expire
setcookie("SLEUser","", time()-3600);
if(isset($_COOKIE['SLESession'])){
	setcookie("SLESession","", time()-3600);
}
if(isset($_COOKIE['SLEteacher'])){
	setcookie("SLEteacher","", time()-3600);
}

?>
<script>window.location.replace("index.php");</script>
