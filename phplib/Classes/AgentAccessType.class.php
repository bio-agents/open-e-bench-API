<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Agents | Templates
 * and open the template in the editor.
 */

class AgentAccessType extends DataStore {
    
    const StoreDescription = 'Benchmarking Agents Access types';

    function getData($params, $checkId=true) {
        $data = parent::getData($params);
        if ($this->error) 
            {return '';};
        $data['Agents']= iterator_to_array(findInDataStore('Agent', ['agent_access.agent_access_type_id' => $data['_id']], ['projection'=>['_id']]));
        return $data;
    }
    
}
