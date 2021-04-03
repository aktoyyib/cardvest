<?php


namespace App\Traits;


trait Filter
{   
    protected $trans_state = [
        'pending' => 'pending',
        'cancelled' => 'closed',
        'completed' => 'succeed'
    ];

    protected $duration = [
        'this_month' => [],
        'this_year' => 'closed',
        'prev_month' => 'succeed',
        'prev_year' => 'succeed'
    ];
    
    public function scopeFilterByStatus($query, $val)
    {
        if (!isset($this->trans_state[$val])) {
            return $query;
        }
        return $query->where('status', $this->trans_state[$val]);
    }

    public function scopeFilterByDuration($query, $val)
    {
        // return $query->when(isset($duration[$val]), function ($query, $val) {
        //     return $query->whereBetween('created_at', $duration[$val]);
        // });
        return $query;
    }
}