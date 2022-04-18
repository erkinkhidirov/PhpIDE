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
        <div class="morfine_form_sections morfine_security_section">
            <div class="container">
                <div class="morfine_form_section">
                    <div class="morf_login_logo">
                        <img src="assets/img/logo_big.png"/>
                    </div>
                    <div class="morfine_form_block">
                        <div class="morfine_form_title">
                            <h4>Authorization</h4>
                        </div>
                        <?php
                        if(isset($_SESSION['pass_try'])){
                            ?>
                            <div class="tries_to_login">
                                <div class="try_num">
                                    <span><?php echo $_SESSION['pass_try']; ?></span>
                                </div>
                                <div class="try_text">
                                    You have <?php echo $_SESSION['pass_try']; ?> attempts left before being banned
                                </div>
                            </div>
                        <?php

                        }
                        ?>
                        <div class="morfine_form_body">
                            <form action="" method="POST">
                                <div class="morfine_form_input">
                                    <input type="password" name="password" value="<?php echo isset( $_POST['password'] ) ? $_POST['password'] : '' ?>" placeholder="Password"/>
                                    <div class="morfine_form_input_icon">
                                        <i class="fa-solid fa-lock"></i>
                                    </div>
                                </div>
                                <input type="hidden" name="form" value="morfin_security" />
                                <div class="morf_login_buttons">
                                    <button class="morfine_form_btn">Login <i class="fa-solid fa-arrow-right-to-bracket"></i></button>
                                    <a class="generete_password" href="?q=generate-password">Generate password</a>
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