<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Agents | Templates
 * and open the template in the editor.
 */

class AgentStatus extends DataStore {
    
    const StoreDescription = 'Benchmarking Agent Status';

    function getData($params, $checkId=true) {
        $data = parent::getData($params);
        if ($this->error) 
            {return '';};
        $data['Agents']= iterator_to_array(findInDataStore('Agent', ['status_id' => $data['_id']], ['projection'=>['_id']]));
        return $data;
    }
 
}
