<?php

/*
 * HMDoll project framework of internet-shop
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */

namespace app\components;

use app\components\MeteoBulletin;
/**
 * Составляет метеобюллетень на основании данных ДМК
 *
 * @author E.Slavko <e20860@mail.ru>
 */
class CompilerBulletinDMK extends MeteopostDMK
{
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
        $dH = $press-750;
        $vt = round($this->getVirtualTemp($temp)-15.9);
        $tGroup = $this->getTempGroup($vt);
        $wGroup = $this->getWindGroup($sW);
        $dGroup = $this->getAlphaGroup($aW);
        //if(isNull($time)){$time = time();}
        $ind = 0;
        $groups = [];
        foreach ($this->hh as $k){
            $groups[$k] = $tGroup[$ind] . $dGroup[$ind] . $wGroup[$ind];
            $ind++;
        }
        return new MeteoBulletin($time,2,'',$hAMS,$vt,$dH, $groups);
        
    }
    /**
     * Возвращает массив сооставляющей температуры
     * @param numeric $temp
     * @return array
     */
    protected function getTempGroup($temp)
    {
        $ret = [];
        $t0 = round($temp);
        if($t0 > -3){
            $tg = abs($t0<0?$t0-50:$t0);
            foreach($this->hh as $h){
                $ret[] = sprintf("%02d",$tg);
            }
        } elseif ($t0 > -11) {
            $ret = array_map(function($v){return sprintf("%02d",abs($v-50));},
                    $this->tempDeviation[abs($t0)]);
        } else {
            $dec = $this->tempDeviation[floor(abs($t0)/10)*10];
            $ed = $this->tempDeviation[abs($t0) - floor(abs($t0)/10)*10];
            for($i = 0; $i < 9; $i++){
                $ret[$i] = sprintf("%02d",abs($dec[$i] + $ed[$i]-50));
            }
        } 
        return $ret;
    }
    

}
    
    

