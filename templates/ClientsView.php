<?php

namespace Templates;

use App\Model\ClientRepository;

class ClientsView
{
    public static function render($params = [])
    {
        ob_start();
        ?>
        <?= Layout::header($params) ?>
        <?= Layout::navbar() ?>
        <div class="thing">
            <div class="nag_task">
                <h2>List of clients</h2>
            </div>
            <div class="d-flex f-wrap">
                 <form method="POST" action="index.php?action=filter-clients" class="d-flex">
                     <input type="text" name="clientName" id="clientName" class="input-compact-text" maxlength="50"
                            placeholder="Type here to search..." value="<?= isset($params['phrase']) ? $params['phrase'] : null ?>">
                     <input type="submit" class="btn-rep" name="submit" value="Search">
                 </form>
                <div class="filler"></div>
                <form method="POST" action="index.php?action=Show-Add-Client">
                    <input type="submit" id="submit" class="btn-rep client" name="submit" value="Add">
                </form>
            </div>
            <p></p>
            <div class="task-table">
                <table id="task">
                    <tr>
                        <th class="trash"></th>
                        <th>Client name</th>
                    </tr>
                    <?php
                    if (isset($params['clients'])) {
                        $clients = $params['clients'];
                    } else {
                        $clientsRep = new ClientRepository();
                        $clients = $clientsRep->findByUserId($_SESSION['uid']);
                    }
                    foreach ($clients as $client):
                        $id = $client->getId();
                        ?>
                        <tr contenteditable="true" onfocusout=editOnFocusOut(<?= $id ?>) id=<?= $id ?>>
                            <td contenteditable="false" class="del">
                                <a href="#" class="del_link" onclick=deleteOnClick(<?=$id?>)><img class="icon icon-table" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDQ4IDQ4IiBoZWlnaHQ9IjQ4cHgiIHZlcnNpb249IjEuMSIgdmlld0JveD0iMCAwIDQ4IDQ4IiB3aWR0aD0iNDhweCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+PGcgaWQ9IkV4cGFuZGVkIj48Zz48Zz48cGF0aCBkPSJNNDEsNDhIN1Y3aDM0VjQ4eiBNOSw0NmgzMFY5SDlWNDZ6Ii8+PC9nPjxnPjxwYXRoIGQ9Ik0zNSw5SDEzVjFoMjJWOXogTTE1LDdoMThWM0gxNVY3eiIvPjwvZz48Zz48cGF0aCBkPSJNMTYsNDFjLTAuNTUzLDAtMS0wLjQ0Ny0xLTFWMTVjMC0wLjU1MywwLjQ0Ny0xLDEtMXMxLDAuNDQ3LDEsMXYyNUMxNyw0MC41NTMsMTYuNTUzLDQxLDE2LDQxeiIvPjwvZz48Zz48cGF0aCBkPSJNMjQsNDFjLTAuNTUzLDAtMS0wLjQ0Ny0xLTFWMTVjMC0wLjU1MywwLjQ0Ny0xLDEtMXMxLDAuNDQ3LDEsMXYyNUMyNSw0MC41NTMsMjQuNTUzLDQxLDI0LDQxeiIvPjwvZz48Zz48cGF0aCBkPSJNMzIsNDFjLTAuNTUzLDAtMS0wLjQ0Ny0xLTFWMTVjMC0wLjU1MywwLjQ0Ny0xLDEtMXMxLDAuNDQ3LDEsMXYyNUMzMyw0MC41NTMsMzIuNTUzLDQxLDMyLDQxeiIvPjwvZz48Zz48cmVjdCBoZWlnaHQ9IjIiIHdpZHRoPSI0OCIgeT0iNyIvPjwvZz48L2c+PC9nPjwvc3ZnPg=="</a>
                            </td>
                            <td onfocusout=editOnFocusOut(<?= $id ?>) class="client_cln"><?= $client->getClientName() ?></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </div>
        </div>
        </div>
        <?= Layout::footer() ?>
        <?php
        $html = ob_get_clean();
        return $html;
    }
}