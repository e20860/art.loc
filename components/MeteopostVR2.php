<?php

/*
 * Current Slavko Project
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */
namespace app\components;
/**
 * Метеопост на основе ветрового ружья
 *
 * @author E.Slavko <e20860@mail.ru>
 */
class MeteopostVR2 extends Meteopost{
    /**
     * Изменение дирекционного угла ветра по высоте
     * @var array
     */
    protected $deltaAlpha = [0,1,2,2,3,3,3,4,4];

    /**
     *  Две переменные, определяющие максимальные и минимальные значения
     *  для определения скорости ветра из таблиц
     *  @var integer
     */
    protected $maxWindRange = 150;
    protected $minWindRange = 40;

    /**
     * Таблица распределение скорости ветра по высотам
     * взависимости от дальности сноса зондировочных пуль
     * @var array
     */
    protected $windDistr = [
        40  => [3,4,4,4,4,4,4,5,5],
        50  => [4,5,5,5,6,6,6,6,6],
        60  => [5,6,6,7,7,7,8,8,8],
        70  => [6,7,7,8,8,8,9,9,9,],
        80  => [7,8,8,8,9,9,9,10,10],
        90  => [7,9,9,9,10,10,10,11,11],
        100 => [8,10,10,11,11,11,12,12,12],
        110 => [9,11,11,12,13,12,14,14,14],
        120 => [10,12,13,13,14,14,15,15,16],
        130 => [11,13,14,14,15,16,16,17,18],
        140 => [12,14,15,15,17,17,18,18,19],
        150 => [12,15,16,16,17,18,19,19,20],
        ];


    /**
     * Определяет максимальную группу для  исправления бюллетеня
     * @param integer $period
     * @return string
     */
        protected function getMaxHeight($period)
        {
            switch ($period){
                case 3: 
                case 4:
                case 5:
                case 6:    {
                    $ret = '16';
                    break;
                }
                case 7:
                case 8:
                case 9: {
                    $ret = '20';
                    break;
                }
                case 10:
                case 11:
                case 12: {
                    $ret = '24';
                }
            }
            return $ret;        }

    /**
     * Исправляет группы старого бюллетеня
     * @param array $groups
     * @param type $sign
     * @param type $tempCorrections
     * @param type $maxIndex
     * @param type $dGroup
     * @param type $wGroup
     * @return type
     */
    protected function correctGroups(array $groups, $sign, $tempCorrections, $maxIndex, $dGroup, $wGroup)
    {
        $ind = 0;
        $ret = [];
        foreach ($this->hh as $hh) {
            $grp = $groups[$hh];
            $tg = (integer) substr($grp, 0, 2);
            $correctSign = $tg>50?-1:1;
            $dt = $tempCorrections[$ind] * $sign * $correctSign;
            $tt = sprintf("%02d", $tg + $dt);
            if ($ind <= $maxIndex) {
                $nn = $dGroup[$ind];
                $ss = $wGroup[$ind];
                $newgrp = $tt . $nn . $ss;
            } else {
                $newgrp = substr_replace($grp, $tt, 0, 2);
            }
            $ret[$hh] = $newgrp;
            $ind++;
        }
        return $ret;
    }
            
    /**
     *  Составляет метеобюллетень
     * @param array $m Результаты наземных измерений
     * @return \app\components\MeteoBulletin
     */
    
    public function compileBulletin(array $m) 
    {
        //$temp, $hAMS, $press, $aW, $sW, $time = null
        $dH = $m['press'] - 750;
        $vt = round($this->getVirtualTemp($m['temp']) - 15.9);
        $tGroup = $this->getTempGroup($vt);
        $wGroup = $this->getWindGroup($m['sW']);
        $dGroup = $this->getAlphaGroup($m['aW']);
        $ind = 0;
        $groups = [];
        foreach ($this->hh as $k) {
            $groups[$k] = $tGroup[$ind] . $dGroup[$ind] . $wGroup[$ind];
            $ind++;
        }
        return new MeteoBulletin($m['time'], 2, '', $m['hAMS'], $vt, $dH, $groups);
    }
        
        public function correctBulletin(array $m, MeteoBulletin $oldMB) 
    {
        //$temp, $hAMS, $press, $aW, $sW, $time
        $hrsPassed  = round(($m['time'] - $oldMB->ddhhm)/3600);
        $oldMB->bulType = 2;
        $oldMB->ddhhm = $m['time'];
        $oldMB->atmPressAMS = $m['press'] - 750;
        $oldMB->hAMS = $m['hAMS'];
        $vt = round($this->getVirtualTemp($m['temp']) - 15.9);
        $wGroup = $this->getWindGroup($m['sW']);
        $dGroup = $this->getAlphaGroup($m['aW']);
        $deltaTemp = round($vt - $oldMB->airTempAMS);
        $oldMB->airTempAMS = $vt;
        $sign = $deltaTemp < 0 ? -1 : 1;
        $tempCorrections = $this->deltaTau[abs($deltaTemp)];
        $groups = $oldMB->groups;
        // максимальный индекс исправления по высоте для показателей ветра.
        $maxIndex = array_search($this->getMaxHeight($hrsPassed), $this->hh);
        $oldMB->groups = $this->correctGroups($groups, $sign, $tempCorrections, 
                                        $maxIndex, $dGroup, $wGroup);
        return $oldMB;
    }
    
}
