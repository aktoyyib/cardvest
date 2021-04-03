<?php


namespace App\Services;

use App\Models\Trader;
use App\Models\Transaction;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TraderService
{
//    ALl amounts must be converted to kobo before processing

    public function createTrader(Request $request) {

        // Create object for response
        $data = array(
            'status' => 'success',
            'message' => 'Trader created successfully.'
        );

        DB::beginTransaction();

        try {
            // Create the Trader
            $trader = Trader::create($request->all());
            
        } catch (\Throwable $e) {
            DB::rollBack();

            throw Exception('Could not create trader');
        }

        return $trader;
    }

    public function uploadImage(Request $request, Trader $trader) {
        // Save the image
        $file = $request->file('thumbnail');
        $filename = Str::slug($request->type, '-') . time().'.'.$file->extension();

        $path = $file->storeAs(
            'trader_icon', $filename
        );

        if (!is_null($trader->thumbnail)) {
            // Delete the previous image from the server
            $initialpath = $trader->thumbnail;
            Storage::delete($initialpath);
        }

        $trader->thumbnail = $path;
        $trader->save();
    }

    public function deleteTrader(Trader $trader) {
        $investors = $trader->investors;

        if ($investors->isEmpty()) {
            $trader->delete();

            return back()->with('success', 'Trader deleted successfully!');
        }

        return back()->with('warning', 'You can\'t delete the trader. Trader still assigned to wallets.');
    }

    // public function allTimeIncrease($traders, $date = null) {
    //     $increase = array('rate' => 0, 'amount' => 0);

    //     foreach ($traders as $trader) {
    //         $beginning = $trader->pivot->created_at;

    //         if (is_null($date) or $date->lt(Carbon::parse($beginning))) {
    //             $date = $beginning;
    //         }

    //         $result = $this->calculateIncrease($trader, $date);
    //         $increase['rate'] += $result['rate'];
    //         $increase['amount'] += $result['amount'];
    //     }

    //     return $increase;
    // }

    // public function calculateIncrease(Trader $trader, $date) {
    //     $unit = $trader->pivot->unit;

    //     $event = Event::for($trader)->fromDate($date)->orderAscend()->first();

    //     $rate = 0;
    //     $start_price = 0;

    //     if (!is_null($event)) {
    //         $start_price = $event->open;

    //         if ($start_price == 0) {
    //             $start_price = $event->close;
    //         }

    //         do {
    //             $rate += $this->getProfit($event->open, $event->close);
    //             $event = $event->next_event;
    //         } while (!is_null($event));
    //     }

    //     $amount = ($start_price * $rate);
    //     return ['rate' => $rate, 'amount' => $amount * $unit];
    // }

//     public function getProfit($open, $close) {
// //        Log::alert('Open: '.$open.', CLose: '.$close);
//         if ($open == 0) {
//             return 1;
//         }
//         $gain = ($close - $open)/$open;
//         $profit = $gain;

//         return floatval($profit);
//     }
}