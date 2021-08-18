<?php

namespace App\Traits;


use App\Models\User;
use Carbon\Carbon;

trait Banning
{
    public function ban(int $forDays = null)
    {
      if (is_null($forDays)) 
        $this->update(['banned_at' => 0]);
      else 
        $this->update(['banned_at' => Carbon::now()->addDays($forDays)]);
    }

    public function liftBan(int $forDays = null)
    {
      $this->update(['banned_at' => null]);
    }

    public function isBanned() : bool
    {
      if (is_null($this->banned_at)) return false;

      // Check if its permanently
      if ($this->banned_at == 0) return true;

      // If its not 0, then its a datetime
      if (Carbon::now()->gt($this->banned_at)) {
        $this->liftBan();
        return false;
      }

      return true;
    }
}