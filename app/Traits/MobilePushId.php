<?php

namespace App\Traits;

trait MobilePushId
{
    public function mobilePushId() {
        return $this->hasOne('App\Models\MobileApp');
    }

}
