<?php
namespace Templates;

class LoginView
{
    public static function render($params = [])
    {
        ob_start();
        ?>
        <?= Layout::header() ?>
        <div class="thing register">
            <div class="nag_task">
                <h2 class="text-center">Log in</h2>
            </div>

            <div class="validation-errors">
                <?php
                if (!empty($params['message'])) {
                    echo '<p class="color-red text-center">' . $params['message'] . '</p>';
                }
                ?>
            </div>

            <form method="POST" action="index.php?action=login-set" class="add-form">
                <div class="data_log">
                    <div class="mail_log">
                        <label for="email">Email: </label>
                        <input type="text" id="email" name="email"
                               value="<?= !empty($params['values']['email']) ? $params['values']['email'] : ''; ?>">
                    </div>

                    <div class="password_log">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password">
                    </div>
                    <p></p>
                    <div class="btm_log">
                        <input class="btn-rep" type="submit" value="Log in">
                    </div>
                </div>
            </form>
        </div>
        </div>
        </div>
        <?= Layout::footer() ?>
        <?php
        return ob_get_clean();
    }
}

?>
