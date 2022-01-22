<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogsQueries extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'admin_id');
    }

    public function activity_log()
    {
        return $this->belongsTo('Spatie\Activitylog\Models\Activity', 'activity_log_id');
    }

    protected $table = 'activity_log_queries';
}
