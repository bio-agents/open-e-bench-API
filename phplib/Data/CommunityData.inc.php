<?php

function getCommunityData($id, $fmt='', $extended = false) {
    $data = $GLOBALS['cols']['Community']->findOne(['_id' => $id]);
    if ($data['_id'] != $id) {
        return '';
    }
    foreach (array_keys($GLOBALS['cols']) as $colname) {
        if ($colname == 'Community') {
            continue;
        }
        $rex = new MongoDB\BSON\Regex($id, "i");
        if ($extended) {
            $proj = [];
        } else
            $proj = ['_id'];
        $data1 = iterator_to_array($GLOBALS['cols'][$colname]->find(['_id' => $rex], ['projection' => $proj]));
        if (count($data1)) {
            $data[$colname] = $data1;
        }
    }
    if (preg_match('/htm/', $fmt)) {
        foreach ($data['BenchmarkingEvent'] as $be) {
            $data['BenchmarkingEventsList'][] = $be['_id'];
        }
        $data['linksList'] = [];
        foreach ($data['links'] as $lk) {
            $data['linksList'][] = $lk['label'] . ": " . setLinks($lk['uri']);
        }
        unset($data['links']);
        $data['DatasetList'] = [];
        $data['DatasetInputList']=[];
        $data['DatasetOutputList']=[];
        $data['DatasetOtherList']=[];
        foreach ($data['Dataset'] as $dts) {
            $dtsData=getOneDocument('Dataset',$dts['_id']);
            switch ($dtsData['type']) {
                case 'Input': $data['DatasetInputList'][] = $dts['_id'];
                    break;
                case 'Output':$data['DatasetOutputList'][] = $dts['_id'];
                    break;
                default:
                    $data['DatasetOtherList'][] = $dts['_id'];
            }
        }
        $data['DatasetList'] = [$data['DatasetInputList'], $data['DatasetOutputList'], $data['DatasetOtherList']];
        $data['agentsList'] = [];
        foreach ($data['Agent'] as $dts) {
            $data['agentsList'][] = $dts['_id'];
        }
        $data['metricsList'] = [];
        foreach ($data['Metrics'] as $dts) {
            $data['metricsList'][] = $dts['_id'];
        }
    }
    if ($extended) {
        $data['community_contacts'] = findArrayInDataStore('Contact', $data['community_contacts']);
        $data['status'] = getOneDocument('CommunityStatus', $data['status']);
        if ($data['status']) {
            unset($data['status_id']);
        }
    }
    return $data;
}
