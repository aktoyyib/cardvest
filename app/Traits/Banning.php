<?php

namespace App\Traits;


use App\Models\User;
use Carbon\Carbon;

trait Banning
{
    public function ban(int $forDays = null)
    {
      if (is_null($forDays)) 
        $this->banned_at = 0;
      else 
        $this->banned_at = Carbon::now()->addDays($forDays);
      
      $this->save();
    }

    public function liftBan()
    {
      $this->banned_at = null;
      $this->save();
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

    public function banTerms() : string
    {
      if (is_numeric($this->banned_at) && $this->banned_at == 0)
        return 'permanently';
        
      return 'temporarily';
    }
}