<?php

namespace Templates;
use App\Model\UserRepository;
use App\Model\ClientRepository;
use App\Model\ProjectRepository;
class AddClientView
{
    public static function render($params = [])
    {
        ob_start();
        ?>
        <?= Layout::header() ?>
        <div class="thing register">
            <div class="nag_task">
                <h2 class="text-center">Add Client</h2>
            </div>

            <form method="POST" action="index.php?action=Add-Client">
                <div class="validation-errors">
                    <?php
                    if (!empty($params['message'])) {
                        echo '<p class="color-red text-center">' . $params['message'] . '</p>';
                    }
                    ?>
                </div>
            <table id="task" class="add-form">
                    <tr>
                        <th>Client name</th>
                    </tr>
                    <tr>
                         <td >
                            <input type="text" id="Client-Name" name="Client-Name" 
                            value="<?= !empty($params['values']['Client-Name']) ? $params['values']['Client-Name'] : ''; ?>"/>
                        </td>
                    </tr>
                    
                </table>
                <p></p>
                <input type="submit" id="submit" class="btn-rep" value="ADD">
            </form>
        </div>
        </div>
        </div>
        <?= Layout::footer() ?>
        <?php
        $html = ob_get_clean();
        return $html;
    }
}
