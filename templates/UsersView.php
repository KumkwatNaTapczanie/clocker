<?php

namespace Templates;

use App\Controllers\UsersController;
use App\Dictionary\UserRoles;
use App\Model\UserRepository;

class UsersView
{
    public static function render($params = [])
    {
        ob_start();
        ?>
        <?= Layout::header($params) ?>
        <?= Layout::navbar() ?>
        <div class="thing">
            <div class="nag_task">
                <h2>List of users</h2>
            </div>
            <div class="d-flex f-wrap">
                <form method="POST" action="index.php?action=filter-users" class="d-flex">
                    <input type="text" name="username" id="username" class="input-compact-text" maxlength="50"
                           placeholder="Type here to search..." value="<?= isset($params['phrase']) ? $params['phrase'] : null ?>">
                    <input type="submit" class="btn-rep" name="submit" value="Search">
                </form>
            </div>
            <p></p>
            <div class="task-table">
                <table id="task">
                    <tr>
                        <th></th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Role</th>
                    </tr>
                    <?php
                    if (isset($params['users'])) {
                        $users = $params['users'];
                    } else {
                        $usersRep = new UserRepository();
                        $users = $usersRep->getAllUsersExceptId($_SESSION['uid']);
                    }
                    foreach ($users as $user):
                        $id = $user->getId();
                        ?>
                        <tr  id=<?= $id ?>>
                            <td class="del">
                                <a href="#" class="del_link" onclick=deleteOnClick(<?=$id?>)><img class="icon icon-table" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDQ4IDQ4IiBoZWlnaHQ9IjQ4cHgiIHZlcnNpb249IjEuMSIgdmlld0JveD0iMCAwIDQ4IDQ4IiB3aWR0aD0iNDhweCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+PGcgaWQ9IkV4cGFuZGVkIj48Zz48Zz48cGF0aCBkPSJNNDEsNDhIN1Y3aDM0VjQ4eiBNOSw0NmgzMFY5SDlWNDZ6Ii8+PC9nPjxnPjxwYXRoIGQ9Ik0zNSw5SDEzVjFoMjJWOXogTTE1LDdoMThWM0gxNVY3eiIvPjwvZz48Zz48cGF0aCBkPSJNMTYsNDFjLTAuNTUzLDAtMS0wLjQ0Ny0xLTFWMTVjMC0wLjU1MywwLjQ0Ny0xLDEtMXMxLDAuNDQ3LDEsMXYyNUMxNyw0MC41NTMsMTYuNTUzLDQxLDE2LDQxeiIvPjwvZz48Zz48cGF0aCBkPSJNMjQsNDFjLTAuNTUzLDAtMS0wLjQ0Ny0xLTFWMTVjMC0wLjU1MywwLjQ0Ny0xLDEtMXMxLDAuNDQ3LDEsMXYyNUMyNSw0MC41NTMsMjQuNTUzLDQxLDI0LDQxeiIvPjwvZz48Zz48cGF0aCBkPSJNMzIsNDFjLTAuNTUzLDAtMS0wLjQ0Ny0xLTFWMTVjMC0wLjU1MywwLjQ0Ny0xLDEtMXMxLDAuNDQ3LDEsMXYyNUMzMyw0MC41NTMsMzIuNTUzLDQxLDMyLDQxeiIvPjwvZz48Zz48cmVjdCBoZWlnaHQ9IjIiIHdpZHRoPSI0OCIgeT0iNyIvPjwvZz48L2c+PC9nPjwvc3ZnPg=="</a>
                            </td>
                            <td class="user_usr"><?= $user->getUsername() ?></td>
                            <td class="email_usr"><?= $user->getEmail() ?></td>
                            <td class="passw_usr">
                                <form method="POST" action="index.php?action=password-change-form">
                                    <input type="hidden" id="id" name="id" value=<?= $id ?>>
                                    <input type="submit" id="submit" class="btn-peach input-compact" name="submit" value="Change password">
                                </form>
                            </td>
                            <td class="role_usr">
                                <select class="role_select" name="role" onfocusout=editOnFocusOut(<?=$id?>)>
                                    <option value="<?php echo $user->getRole() ?>"><?= $user->getRole() ?></option>
                                    <?php if ($user->getRole() == UserRoles::USER) { ?>
                                        <option value="admin"><?php echo UserRoles::ADMIN ?></option>
                                    <?php } else { ?>
                                        <option value="user"><?php echo UserRoles::USER ?></option>
                                    <?php } ?>
                                </select>
                            </td>
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
