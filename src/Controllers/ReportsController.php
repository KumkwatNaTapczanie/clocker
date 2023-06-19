<?php

namespace App\Controllers;

use App\Exception\QueryBuilderException;
use App\Framework\Response;
use App\Model\QueryBuilder;
use App\Model\TaskRepository;
use App\Model\User;
use Templates\ReportsView;

class ReportsController
{
    /**
     * @return Response
     */
    public static function index()
    {
        $response = new Response();
        $response->setContent(ReportsView::render([
            'script' => 'javascript/Reports.js async'
        ]));
        return $response;
    }

    /**
     *oF cOUrsE it needs like 64273462 lines to do 1 (one) simple thing
     * @return void
     * @throws QueryBuilderException
     */
    public static function filterData()
    {
        $projects = [];
        $clients = [];
        $startFrom = null;
        $startTo = null;
        $stopFrom = null;
        $stopTo = null;
        $aggregate = false;
        foreach ($_POST as $key => $value) {
            if ($key == 'startFrom' && $value != '') {
                $startFrom = $value;
            } elseif ($key == 'startTo' && $value != '') {
                $startTo = $value;
            } elseif ($key == 'stopFrom' && $value != '') {
                $stopFrom = $value;
            } elseif ($key == 'stopTo' && $value != '') {
                $stopTo = $value;
            } elseif (preg_match('/^p/', $key)) {
                $projects[] = intval(ltrim($key, 'p'));
            } elseif (preg_match('/^c/', $key)) {
                $clients[] = intval(ltrim($key, 'c'));
            } elseif ($key == 'aggregation') {
                $aggregate = true;
            }
        }
        $qb = new QueryBuilder();
        if ($aggregate) {
            if ($projects) {
                $qb->select('p.projectName, c.clientName, p.wage, SUM(TIME_TO_SEC(TIMEDIFF(t.stopTime, t.startTime))) AS totalTime, p.wage AS totalPayout');
                $qb->groupBy('p.projectName, c.clientName, p.wage');
            } elseif ($clients) {
                $qb->select('c.clientName, SUM(TIME_TO_SEC(TIMEDIFF(t.stopTime, t.startTime))) AS totalTime, p.wage AS totalPayout');
                $qb->groupBy('c.clientName');
            } else {
                $qb->select('t.title, p.projectName, c.clientName, p.wage, t.startTime, t.stopTime, SUM(TIME_TO_SEC(TIMEDIFF(t.stopTime, t.startTime))) AS totalTime, p.wage AS totalPayout');
                $qb->groupBy('t.title', 'p.projectName', 'c.clientName', 'p.wage', 't.startTime', 't.stopTime');
            }
        } else {
            $qb->select('t.title, p.projectName, c.clientName, p.wage, t.startTime, t.stopTime, SUM(TIME_TO_SEC(TIMEDIFF(t.stopTime, t.startTime))) AS totalTime, p.wage AS totalPayout');
            $qb->groupBy('t.title', 'p.projectName', 'c.clientName', 'p.wage', 't.startTime', 't.stopTime');
        }
        $qb->from('Task t')
            ->join('Project p', 't.projectId = p.id')
            ->join('Client c', 'p.clientId = c.id')
            ->where('t.userId', '=', [$_SESSION['uid']])
            ->where('t.stopTime', 'IS NOT', [null], 'AND');
        if ($projects && $clients) {
            $qb->where('p.id', '=', $projects, 'AND', true);
            $qb->where('c.id', '=', $clients, 'OR', false, true);
        } elseif ($projects) {
            $qb->where('p.id', '=', $projects, 'AND');
        } elseif ($clients) {
            $qb->where('c.id', '=', $clients, 'AND');
        }
        if ($startFrom) {
            $qb->where('t.startTime', '>', [$startFrom], 'AND');
        }
        if ($startTo) {
            $qb->where('t.startTime', '<', [$startTo], 'AND');
        }
        if ($stopFrom) {
            $qb->where('t.stopTime', '>', [$stopFrom], 'AND');
        }
        if ($stopTo) {
            $qb->where('t.stopTime', '<', [$stopTo], 'AND');
        }
        $qb->prepareStatement();
        $taskRep = new TaskRepository();
        try {
            $rows = $taskRep->executeQueryFromBuilder($qb);
            foreach ($rows as &$row) {
                $row['totalPayout'] = number_format(round($row['totalPayout'] * $row['totalTime'] / 3600, 2), 2);
                $row['totalTime'] = sprintf('%02d:%02d:%02d', intval($row['totalTime'] / 3600), intval($row['totalTime'] / 60) % 60, $row['totalTime'] % 60);
            }
            $response = json_encode($rows);
        } catch (\Exception $e) {
            $response = json_encode($e);
        }
        header('Content-type: application/json');
        echo $response;
        exit;
    }
}

//ReportsController::filterData();