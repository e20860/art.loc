<?php

/*
 * Artillery project 
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */

namespace app\components;

use app\components\Du;

/**
 * Деление угломера со знаком (доворот, угол места)
 *
 * @author E.Slavko <e20860@mail.ru>
 */
class Sdu extends Du
{
    /**
     * Конструктор
     * @param integer $bdu
     * @param integer $mdu
     */
    public function __construct($sign, $bdu, $mdu) {
        $this->sign = $sign=='-'?-1:1;
        $this->bdu = $bdu;
        $this->mdu = $mdu;
        $this->rad = $this->duToRad() * $this->sign;
    }
    public function __toString() {
		$sign =  $this->sign>0?'+':'-';
        return sprintf("%01s%01d-%02d",$sign,$this->bdu,$this->mdu);
    }    
}
