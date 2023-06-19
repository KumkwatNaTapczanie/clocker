<?php

namespace Templates;
use App\Model\UserRepository;
use App\Model\ClientRepository;
use App\Model\ProjectRepository;
class AddProjectView
{
    public static function render($params = [])
    {
        ob_start();
        ?>
        <?= Layout::header() ?>
        <div class="thing register">
            <div class="nag_task">
                <h2 class="text-center">Add Project</h2>
            </div>

            <form method="POST" action="index.php?action=Add-Project">
                <div class="validation-errors">
                    <?php
                    if (!empty($params['message'])) {
                        echo '<p class="color-red text-center">' . $params['message'] . '</p>';
                    }
                    ?>
                </div>
            <table id="task" class="add-form">
                    <tr>
                        <th>Project name</th>
                        <th>Client name</th>
                        <th>Wage</th>
                    </tr>
                    <tr>
                         <td >
                            <input type="text" id="Project-Name" name="Project-Name" 
                            value="<?= !empty($params['values']['Project-Name']) ? $params['values']['Project-Name'] : ''; ?>"/>
                        </td>
                        <td>
                            <input class="Client_select" type="text" list="Clients" id="Client_Name" name="Client_Name"
                            value="<?= !empty($params['values']['Client_Name']) ? $params['values']['Client_Name'] : ''; ?>" />
                                <datalist id="Clients">
                                    <?php
                                    $clientsRep = new ClientRepository();
                                    $clients = $clientsRep->findByUserId($_SESSION['uid']);
                                    foreach ($clients as $client): ?>
                                        <option><?= $client->getClientName() ?></option>
                                    <?php endforeach ?>
                                </datalist>
                        </td>
                        <td>
                            <input type="text" id="Wage" name="Wage"
                            value="<?= !empty($params['values']['Wage']) ? $params['values']['Wage'] : ''; ?>"/>
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
