<?php
/**
 * @author Erkin Khidirov
 * @author Erkin Khidirov <erkinofabrichi@gmail.com>
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <?php get_head(); ?>
    </head>
    <body class="">
        <div class="morfine_form_sections morfine_security_section morf_reg_password">
            <div class="container">
                <div class="morfine_form_section morfine_file_manager_block">
                    <div class="morf_login_logo">
                        <img src="assets/img/logo_big.png"/>
                    </div>
                    <div class="morfine_form_block">
                        <div class="morfine_form_title">
                            <h4>Generate Password</h4>
                        </div>
                        <?php if(defined('YOUR_PASSWORD') && empty(YOUR_PASSWORD)) { ?>
                        <div class="tries_to_login">
                            <div class="try_text">
                                The password has not yet been set. Generate a new password and paste in index.php. <a href="<?php echo HOW_TO_AUTHORIZE; ?>" target="_blank">Learn this article</a>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="morfine_form_body">
                            <form action="" method="POST">
                                <div class="morfine_form_input">
                                    <input type="password" name="password" value="<?php echo isset( $_POST['password'] ) ? $_POST['password'] : '' ?>" placeholder="Type any password"/>
                                    <div class="morfine_form_input_icon">
                                        <i class="fa-solid fa-lock"></i>
                                    </div>
                                </div>
                                <input type="hidden" name="form" value="morfin_security" />
                                <div class="morf_login_buttons">

                                    <?php if(defined('YOUR_PASSWORD') && !empty(YOUR_PASSWORD)) { ?>
                                    <a class="go_back" href="<?php echo MORF_URL_PROJECT; ?>"><i class="fa-solid fa-arrow-left-long"></i> Go back</a>
                                    <?php } ?>
                                    <a class="morfine_form_btn gen_pass" href="#">Generate password</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="footer">
                        <a href="https://phpide.io/" target="_blank">PhpIDE</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
