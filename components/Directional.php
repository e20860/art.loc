<?php

/*
 * Artillery project 
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */

namespace app\coomponents;

/**
 * Дирекционный угол
 *
 * @author <e20860@mail.ru>
 */
class Directional extends Du
{
    /**
     * 
     * @param type $bdu
     * @param type $mdu
     */
    public function __construct($bdu, $mdu,$sign=1) {
        parent::__construct($bdu, $mdu, $sign);
        $this->bdu = $bdu;
        $this->mdu = $mdu;
        $this->sign = $sign;
        $this->rad = $this->duToRad();
    }
    /**
     *  Возвращает строковое представление класса
     * @return string
     */
    public function __toString() {
        return sprintf("%02d-%02d",$this->bdu,$this->mdu);
    }

}
