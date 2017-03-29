<?php
// show potential errors / feedback (from registration object)
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo $error;
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo $message;
        }
    }
}
?>
<?php include("header.html"); ?>
<!-- register form -->
  <div class='container container_cnl'>
    <form method="post" action="register.php" name="registerform" class="col col-md-4 offset-md-4">
      <h2> 註冊新帳號 </h2>
      <!-- the user name input field uses a HTML5 pattern check -->
      <div class="form-group">
        <input id="login_input_username" class="form-control" placeholder="使用者名稱" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />
      </div>
      <div class="form-group">
        <input id="login_input_email" class="form-control" placeholder="Email" type="email" name="user_email" required />
      </div>
      <div class="form-group">
        <input id="login_input_password_new" class="form-control" placeholder="密碼（六個字以上）" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
      </div>
      <div class="form-group">
        <input id="login_input_password_repeat" class="form-control" placeholder="重複密碼" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-lg btn-primary btn-block" name="register" value="註冊" />
      </div>
      <div class="float-md-right">
        <a href="../index.php">返回登入畫面</a>
      </div>
    </form>
  </div>

<!-- backlink -->
