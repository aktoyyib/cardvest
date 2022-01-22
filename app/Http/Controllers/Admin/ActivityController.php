<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ActivitylogService;
use Spatie\Activitylog\Models\Activity;
use App\Models\Transaction;

class ActivityController extends Controller
{
    protected $activitylogService;

    public function __construct(ActivitylogService $activitylogService)
    { 
        $this->activitylogService = $activitylogService; 
    }

    public function index()
    {
        $activities = Activity::where('log_name', 'admin.transactions')->orderBy('created_at', 'desc')->simplePaginate(40);
        return view('admin.activity.index', compact('activities'));
    }

    public function queryTransactionLogs(Request $request, Activity $activity)
    {  
        try{
            $user = auth()->user(); 
            //$activity = Activity::where(['log_name' => 'admin.transactions', 'id' => $logid])->firstOrFail();
            $transaction = Transaction::where(['id' => $activity->subject_id])->firstOrFail();

            if(!($user->hasRole('super admin')) && ($activity->causer_id != $user->id))
            {
                return back()->with('error', 'Unauthorized access!');
            }

            if ($request->post()) 
            {
                $request->validate([ 
                    'comment' => 'required',
                ]);

                $data = $request->except(['_token']);
                $data['admin_type'] = (count($user->getRoleNames()) > 1) ? 'superadmin' : 'admin';
                $data['activity_log_id'] = $activity->id;
                $data['transaction_reference'] = $transaction->reference;
                $data['admin_id'] = $user->id;
                $data['status'] = 'open';


                $saveQuery =  $this->activitylogService->createQuery($data); 

                return redirect()->back()->with('info', 'Query saved successfully');
                
            }
            else
            {
                $queries = $this->activitylogService->fetchActivityQueries($activity->id);
                $returns = [
                    'activity'  => $activity, 
                    'transaction'  =>  $transaction,
                    'queries' =>    $queries
                ];

                return view('admin.activity.query', $returns);
            }

        }catch(\Exception $e){
            return redirect()->back()->with('warning', $e->getMessage());
        }
    }

    public function closeQuery(Request $request, Activity $activity)
    {
        try{  
            if($user->hasRole('admin'))
            {
                return back()->with('error', 'Unauthorized access!');
            }

            $updateQueries = $this->activitylogService->updateQueries($activity, 'closed');

            if ($updateQueries) 
            {
                return redirect()->back()->with('info', 'Query status changed successfully');
            }
            else
            {
                return redirect()->back()->with('warning', 'Something went wrong');
            }

        }catch(\Exception $e){
            return redirect()->back()->with('warning', $e->getMessage());
        }
    }
}
