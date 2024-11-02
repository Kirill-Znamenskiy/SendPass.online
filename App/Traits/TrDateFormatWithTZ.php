<?php

namespace App\Traits;


trait TrDateFormatWithTZ
{
    //protected $dateFormat; //
    public function getDateFormat() {
        //return $this->dateFormat ?: $this->getConnection()->getQueryGrammar()->getDateFormat();
        return $this->dateFormat ?: 'Y-m-d H:i:sO';
    }

}
