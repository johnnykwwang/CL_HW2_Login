
<?php
// show potential errors / feedback (from login object)
if (isset($login)) {
    if ($login->errors) {
        foreach ($login->errors as $error) {
            echo $error;
        }
    }
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo $message;
        }
    }
}
?>
<?php include("header.html"); ?>
<!-- login form box -->
<div class="container container_cnl">
  <div class="col-md-4 offset-md-4">
    <h2>歡迎使用<br>CNL Group 9 AP</h2>
    <form method="post" action="index.php" name="loginform">
        <div class="form-group">
          <input id="login_input_username"  type="text" name="user_name" placeholder="帳號" class="form-control" required />
        </div>
        <div class="form-group">
          <input id="login_input_password"  type="password" name="user_password" autocomplete="off" placeholder="密碼" class="form-control" required />
        </div>
        <input type="hidden" name="uamip" value="<?php echo $form['uamip'];?>" />
        <input type="hidden" name="uamport" value="<?php echo $form['uamport'];?>" />
        <input type="hidden" name="userurl" value="<?php echo $form['userurl'];?>" />
        <input type="submit" class="btn btn-primary btn-block" name="login" value="登入" />
    </form>

    <a href="register.php">註冊新帳號</a>
  </div>
</div>
