<!-- ALERT MESSAGE--
<div class="container">
	<div class="column">
	<div class="alert alert-info alert-dismissible" role="alert">
  <button type="button" onclick="this.parentNode.parentNode.removeChild(this.parentNode);" class="bg-dark text-info" data-dismiss="alert"><span aria-hidden="true">Close</span><span class="sr-only"></span></button>
  <strong><i class="fa fa-info-circle"></i></strong>
  <marquee><p style="font-family: Impact; font-size: 18pt">In order to use the software, you would have to be logged in. However, if you don't have an account yet, then please proceed to Sign Up.</p></marquee>
  </div>
  </div>
  </div>
-- END OF ALERT MESSAGE -->
<!-- ALERT MESSAGE -->
<?php


// افتراض أن اسم المستخدم مسجل في الجلسة
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>

<!-- ALERT MESSAGE -->
<div class="container">
  <div class="column">
    <div class="alert alert-info alert-dismissible" role="alert">
      <button type="button" onclick="this.parentNode.parentNode.removeChild(this.parentNode);" class="bg-dark text-info" data-dismiss="alert">
        <span aria-hidden="true">Close</span>
        <span class="sr-only"></span>
      </button>
      <strong><i class="fa fa-info-circle"></i></strong>
      <marquee>
        <p style="font-family: Impact; font-size: 18pt">Welcome, <span id="username">Guest</span>! We're glad to have you here. Enjoy using the software!</p>
      </marquee>
    </div>
  </div>
</div>

<script>
// جلب اسم المستخدم من المتغير المرسل بواسطة PHP
let username = "<?php echo $username; ?>";

// تحديث اسم المستخدم في الرسالة
document.getElementById("username").innerText = username;
</script>

<!-- END OF ALERT MESSAGE -->
 