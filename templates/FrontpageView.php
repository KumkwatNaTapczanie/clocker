<?php

namespace Templates;

use App\Model\TaskRepository;
use App\Model\UserRepository;

class FrontpageView
{
    public static function render($params = [])
    {
        ob_start();
        ?>
        <?= Layout::header() ?>
        <nav class="navig">
            <ul class="menulist">
                <?php
                $names = ['Log in', 'Register'];
                $actions = ['login', 'register'];
                foreach (array_combine($actions, $names) as $action => $name): ?>
                    <li>
                        <a class="choice" <?= "href=?action=" . $action ?>><?= $name ?></a>
                    </li>
                <?php endforeach ?>
            </ul>
        </nav>
        <div class="thing">
            <div class="nag_task">
                <h2>Our statistics</h2>
            </div>
            <div class="d-flex f-wrap">
            <div class="stats d-flex" draggable="true">
                <p class="big-text"><?php
                    $usersRep = new UserRepository();
                    echo $usersRep->getNumberOfUsers();
                    ?></p><p class="box">registered users</p>
            </div>

            <div class="stats d-flex">
                <p class="big-text"> <?php
                    $tasksRep = new TaskRepository();
                    echo $tasksRep->getNumberOfTasks();
                    ?></p><p class="box">submitted tasks</p>
            </div>

            <div class="stats d-flex">
                <p class="big-text"><?php
                    echo Layout::secondsToHours($tasksRep->getTotalTasksTimeThisPeriod('week'));
                    ?> </p><p class="box">hours reported this week</p>
            </div>

            <div class="stats d-flex">
                <p class="big-text"><?php
                    echo Layout::secondsToHours($tasksRep->getTotalTasksTimeThisPeriod('month'));
                    ?> </p><p class="box">hours reported this month</p>
            </div>

            <div class="stats d-flex">
                <p class="big-text"><?php
                    echo Layout::secondsToHours($tasksRep->getTotalTasksTimeThisPeriod('year'));
                    ?> </p><p class="box">hours reported this year</p>
            </div>

            <div class="stats d-flex">
                <p class="big-text"><?php
                    echo Layout::secondsToHours($tasksRep->getTotalTasksTimeThisPeriod());
                    ?></p><p class="box">hours reported in total</p>
            </div>
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