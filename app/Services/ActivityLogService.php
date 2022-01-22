<?php

namespace App\Services;

use App\Models\ActivityLogsQueries; 

class ActivityLogService
{
    public function fetchActivityQueries($logid) 
    {
        return ActivityLogsQueries::with(['user'])->where(['activity_log_id' => $logid])->get();
    }

    public function createQuery(array $data) : ActivityLogsQueries
    {
        $query = new ActivityLogsQueries();

        return $this->updateQuery($query, $data);
    }   

    public function updateQuery(ActivityLogsQueries $query, array $data) : ActivityLogsQueries
    { 
        $query->activity_log_id = array_key_exists("activity_log_id",$data)?$data['activity_log_id']:$query->activity_log_id;
        $query->transaction_reference = array_key_exists("transaction_reference",$data)?$data['transaction_reference']:$query->transaction_reference;  
        $query->admin_id = array_key_exists("admin_id",$data)?$data['admin_id']:$query->admin_id;
        $query->comment = array_key_exists("comment",$data)?$data['comment']:$query->comment;   
        $query->admin_type = array_key_exists("admin_type",$data)?$data['admin_type']:$query->admin_type;  
        $query->status = array_key_exists("status",$data)?$data['status']:$query->status;  

        $query->save();

        return $query;
    }

    public function updateQueries($activity, $status)
    { 
        return ActivityLogsQueries::where(['activity_log_id' => $activity->id])->update(['status' => $status]);
    }    
}

