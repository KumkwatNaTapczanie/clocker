<?php

namespace Templates;

use App\Controllers\ReportsController;
use App\Model\ClientRepository;
use App\Model\ProjectRepository;
use App\Model\TaskRepository;

class ReportsView
{
    public static function render($params = [])
    {
        ob_start();
        ?>
        <?= Layout::header($params) ?>
        <?= Layout::navbar() ?>
        <div class="thing reports-main">
            <div class="d-flex export">
                <div class="f-header">Generate report:</div>
                <div class="switch-field">
                    <input type="radio" id="csv-btn" name="format-choice" value="csv" checked/>
                    <label for="csv-btn">.csv</label>
                    <input type="radio" id="xls-btn" name="format-choice" value="xls" />
                    <label for="xls-btn">.xls</label>
                </div>
                <input class="btn-rep" type="submit" value="Generate">
            </div>

            <div class="task-table">
                <table id="task">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Project name</th>
                        <th>Client name</th>
                        <th>Wage</th>
                        <th>Started</th>
                        <th>Ended</th>
                        <th>Duration</th>
                        <th>Payout</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tasksRep = new TaskRepository();
                    $tasks = $tasksRep->findByUserId($_SESSION['uid']);
                    foreach ($tasks as $task): ?>

                        <tr>
                            <?php
                            $project = $tasksRep->getProjectByTaskId($task->getId());
                            $client = $tasksRep->getClientByTaskId($task->getId());
                            $wage = $project ? $project->getWage() : null;
                            $durationInSec = $task->getStopTime() ? strtotime($task->getStopTime()) - strtotime($task->getStartTime()) : null;
                            $payout = ($durationInSec && $wage) ? number_format(round($wage * $durationInSec / 3600, 2), 2) : null;
                            $timeFormatted = $durationInSec ? sprintf('%02d:%02d:%02d', ($durationInSec / 3600), intval($durationInSec / 60) % 60, $durationInSec % 60) : null;
                            ?>
                            <td><?= $task->getTitle() ?></td>
                            <td><?= $project ? $project->getProjectName() : '' ?></td>
                            <td><?= $client ? $client->getClientName() : '' ?></td>
                            <td><?= $wage ?: '' ?></td>
                            <td><?= $task->getStartTime() ?></td>
                            <td><?= $task->getStopTime() ?></td>
                            <td><?= $timeFormatted ?: '' ?></td>
                            <td><?= $payout ?: '' ?></td>
                        </tr>

                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="nag_task reports-bar">
            <div class="f-form">
                <form method="GET" id="form" action="index.php?action=reports-filter">

                    <div class="project-name d-flex f-wrap">
                        <div class="item-rep">
                            <div class="f-header">Collective report</div>
                        </div>
                        <div class="item-rep">
                            <label for="aggregation" class="switch">
                                <input type="checkbox" id="aggregation" name="aggregation" value="Aggregation">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="f-header">Project</div>
                    <div class="project-name d-flex f-wrap">
                        <?php
                        $projectsRep = new ProjectRepository();
                        $projects = $projectsRep->findByUserId($_SESSION['uid']);
                        foreach ($projects as $project):
                            ?>
                            <div class="item-rep">
                                <label for="<?= 'p' . $project->getId() ?>" class="switch-bubble">
                                    <input type="checkbox" id="<?= 'p' . $project->getId() ?>"
                                           name="<?= 'p' . $project->getId() ?>">
                                    <span class="bubble"><?= $project->getProjectName() ?></span>
                                </label>
                            </div>
                        <?php endforeach; ?>

                    </div>
                    <div class="f-header">Client</div>
                    <div class="client-name d-flex f-wrap">

                        <?php
                        $clientsRep = new ClientRepository();
                        $clients = $clientsRep->findByUserId($_SESSION['uid']);
                        foreach ($clients as $client):
                            ?>
                            <div class="item-rep">
                                <label for="<?= 'c' . $client->getId() ?>" class="switch-bubble">
                                    <input type="checkbox" id="<?= 'c' . $client->getId() ?>"
                                           name="<?= 'c' . $client->getId() ?>">
                                    <span class="bubble"><?= $client->getClientName() ?></span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="f-header">Started between:</div>
                    <div class="started">
                        <div class="item-rep">
                            <input type="datetime-local" class="input-compact reports-date" name="startFrom"
                                   id="startFrom">
                        </div>
                        <div class="item-rep">
                            <input type="datetime-local" class="input-compact reports-date" name="startTo" id="startTo">
                        </div>
                    </div>
                    <div class="f-header">Ended between:</div>
                    <div class="ended">
                        <div class="item-rep"><label>
                                <input type="datetime-local" class="input-compact reports-date" name="stopFrom">
                            </label></div>
                        <div class="item-rep"><label>
                                <input type="datetime-local" class="input-compact reports-date" name="stopTo">
                            </label></div>
                    </div>

                </form>
            </div>

            <div class="btn-re">
                <input class="btn-rep" type="submit" value="Filter" onclick="getResults()">
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