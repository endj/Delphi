<?php $this->load->view('admin/header'); ?>
<div class="settingscontainer">




        <!-- //Survey link input box -->
        <div class="survey">
          <div class="survey_box">
                <h1 class="sl">Survey link for <?php echo $company_name; ?></h1>
                <h2 class="sl"> i.e Survey Monkey, Google Form, etc.</h2>
              <div class="survey_register">
                  <form  action="" method="post">
                    <input type="text" name="url" class="linkinfo" value="<?php echo "https://docs.google.com/forms/d/1nz3Xbjc5GSN7KfwG9WM3sshilBq3zz4B_b1CNjn4hFc/viewform"
                    //use $survey when you insert your own link
                    ?>">
                </div>
                <button class="button assign" type="submit" name="link"> Register Link<i class="fa fa-angle-right" aria-hidden="true"></i> </button>
                  </form>
        </div>  <!-- end survey box -->
        </div>  <!-- end survey -->

        <div class="row themePicker">

          <div class="squareTheme_container" onclick="document.location = '<?php echo site_url("index.php/admin/settings?theme=green") ?>'">
            <div class="squareTheme <?php if($theme_name=='green') echo 'active'; ?>"></div>
            <span>Summer green</span>
          </div>

          <div class="squareTheme_container" onclick="document.location='<?php echo site_url("index.php/admin/settings?theme=blue") ?>'">
            <div class="squareTheme blue <?php if($theme_name=='blue') echo 'active'; ?>"></div>
            <span>Deep ocean blue</span>
          </div>

          <div class="squareTheme_container" onclick="document.location='<?php echo site_url("index.php/admin/settings?theme=red") ?>'">
            <div class="squareTheme red <?php if($theme_name=='red') echo 'active'; ?>"></div>
            <span>Passionate <br/>red</span>
          </div>

          <div class="squareTheme_container" onclick="document.location='<?php echo site_url("index.php/admin/settings?theme=dark") ?>'">
            <div class="squareTheme dark <?php if($theme_name=='dark') echo 'active'; ?>"></div>
            <span>Liquorice<br/> Black</span>
          </div>

          <div class="squareTheme_container" onclick="document.location='<?php echo site_url("index.php/admin/settings?theme=roseg") ?>'">
            <div class="squareTheme roseg <?php if($theme_name=='roseg') echo 'active'; ?>"></div>
            <span>Rose gold</span>
          </div>

          <div class="squareTheme_container" onclick="document.location='<?php echo site_url("index.php/admin/settings?theme=sunset") ?>'">
            <div class="squareTheme sunset <?php if($theme_name=='sunset') echo 'active'; ?>"></div>
            <span>Sunset <br/>orange</span>
          </div>

          <div class="squareTheme_container" onclick="document.location='<?php echo site_url("index.php/admin/settings?theme=heartbreak") ?>'">
            <div class="squareTheme heartbreak <?php if($theme_name=='heartbreak') echo 'active'; ?>"></div>
            <span>Heartbreak <br/>red</span>
          </div>


        </div>

  </div>
</body>
</html>
