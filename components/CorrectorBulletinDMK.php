<?php

/*
 * Current Slavko Project
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */

namespace app\components;

use app\components\MeteoBulletin;

/**
 * Description of CorrectorBulletinDMK
 *
 * @author admin
 */
class CorrectorBulletinDMK extends MeteopostDMK
{
    /**
     * Поправки в температуру бюллетеня "Метеосредний"
     * 
     * @var array
     */
    protected $deltaTau = [
        1  => [1,0,0,0,0,0,0,0,0],
        2  => [1,1,0,0,0,0,0,0,0],
        3  => [2,1,1,0,0,0,0,0,0],
        4  => [3,2,1,1,0,0,0,0,0],
        5  => [3,3,2,1,1,0,0,0,0],
        6  => [4,3,3,2,1,1,0,0,0],
        7  => [5,4,3,3,2,1,1,0,0],
        8  => [6,5,4,3,3,2,1,1,0],
        9  => [7,6,5,4,3,3,2,1,1],
        10 => [8,7,6,5,4,3,3,2,1],
    ];
    /**
     * Составляет бюллетень
     * @param numeric $temp температура на уровне АМС
     * @param integer $hAMS высота АМС над уровнем моря
     * @param numeric $press атмосферное давление на уровне АМС
     * @param integer $aW дирекционный угол направления ветра
     * @param numeric $sW скорость ветра на уровне АМС
     * @param integer $time время создания бюллетеня
     */
    public function createBulletin($temp,$hAMS,$press,$aW,$sW, $time = null, $oldMB=null)
    {
        if (isNull($oldMB)) {throw new Exception ("Не передан старый бюллетень");}
        $hrsPassed  = round((time() - $oldMB->ddhhm));
        if ($hrsPassed < 3) {
            return $oldMB; // Ничего не надо
        }
        if ($hrsPassed > 12) { // больше 12 часов - составляем новый
            $cb = new ComposerBulletinDMK();
            return $cb->createBulletin($temp, $hAMS, $press, $aW, $sW, $time, $oldMB);
        }
        //----------------------------------------------------------------------
        $dH = $press-750;
        $vt = round($this->getVirtualTemp($temp)-15.9);
        $wGroup = $this->getWindGroup($sW);
        $dGroup = $this->getAlphaGroup($aW);
        $deltaTemp = round($vt - $oldMB->airTempAMS);
        $sign = $deltaTemp < 0 ? -1 : 1;
        $tempCorrections = $this->deltaTau[abs($deltaTemp)];
        $groups = $oldMB->groups;
        // максимальный индекс исправления по высоте для показателей ветра.
        $maxIndex = array_search($this->getMaxHeight($hrsPassed), $this->hh);
        $ind = 0;
        foreach($this->hh as $hh){
            $grp = $groups[$hh];
            $dt = $tempCorrections[$ind] * $sign;
            $tg = (integer) substr($grp,0,2);
            $tt = sprintf("%02d",$tg + $dt);
            if ($ind < $maxIndex) {
                $nn = $dGroup[$ind];
                $ss = $wGroup[$ind];
                $newgrp = $tt . $nn . $ss;
            } else {
                $newgrp = substr_replace($grp, $tt, 0, 2);
            }
            $groups[$hh] = $newgrp;
            $ind++;
        }
        
    }
}
