<?php

namespace Templates;

use App\Model\UserRepository;
use App\Model\ClientRepository;
use App\Model\ProjectRepository;
use App\Controllers\AddTaskController;

class AddTaskView
{
    public static function render($params = [])
    {
        ob_start();
        ?>
        <?= Layout::header() ?>
        <div class="thing register">
            <div class="nag_task">
                <h2 class="text-center">Add Task</h2>
            </div>

            <form method="POST" action="index.php?action=Add-Task" class="add-form">
                <div class="validation-errors">
                    <?php
                    if (!empty($params['message'])) {
                        echo '<p class="color-red text-center">' . $params['message'] . '</p>';
                    }
                    ?>
                </div>
                <label for="Task-title">Task Title</label>
                <input type="text" id="Task-title" name="Task-title"
                       value="<?= !empty($params['values']['Task-title']) ? $params['values']['Task-title'] : ''; ?>"/>

                <label for="Project_Name">Project Name</label>
                <input class="Project_Name" type="text" list="Projects" id="Project_Name" name="Project_Name"
                       value="<?= !empty($params['values']['Project_Name']) ? $params['values']['Project_Name'] : ''; ?>"/>
                <datalist id="Projects">
                    <?php
                    $ProjectsRep = new ProjectRepository();
                    $Projects = $ProjectsRep->findByUserId($_SESSION['uid']);
                    foreach ($Projects as $Project): ?>
                        <option><?= $Project->getProjectName() ?></option>
                    <?php endforeach ?>
                </datalist>

                <label for="Time_start">Time Start</label>
                <input type="datetime-local" id="Time_start" name="Time_start"
                       value="<?= !empty($params['values']['Time_start']) ? $params['values']['Time_start'] : ''; ?>"/>

                <label for="Time_stop">Time Stop</label>
                <input type="datetime-local" id="Time_stop" name="Time_stop"
                       value="<?= !empty($params['values']['Time_stop']) ? $params['values']['Time_stop'] : ''; ?>"/>

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
