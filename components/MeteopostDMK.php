<?php

/*
 * Slavko current project
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */

namespace app\components;

/**
 * Абстрактный класс MeteopostDMK с общими данными 
 * для составления/исправления бюллетеня
 * на основе данных "Указаний по работе метеопоста адн"
 * с использованием Десантного метеокомплекта
 * 
 * @author E.Slavko <e20860@mail.ru>
 */
class MeteopostDMK extends Meteopost{
    /**
     * Изменение дирекционного угла ветра по высоте
     * @var array
     */
    protected $deltaAlpha = [1,2,3,3,4,4,4,5,5];
    
    /**
     * Поправки в температуру бюллетеня "Метеосредний"
     *            (для исправления)
     * @var array
     */
    protected $deltaTau = [
        1 => [1, 0, 0, 0, 0, 0, 0, 0, 0],
        2 => [1, 1, 0, 0, 0, 0, 0, 0, 0],
        3 => [2, 1, 1, 0, 0, 0, 0, 0, 0],
        4 => [3, 2, 1, 1, 0, 0, 0, 0, 0],
        5 => [3, 3, 2, 1, 1, 0, 0, 0, 0],
        6 => [4, 3, 3, 2, 1, 1, 0, 0, 0],
        7 => [5, 4, 3, 3, 2, 1, 1, 0, 0],
        8 => [6, 5, 4, 3, 3, 2, 1, 1, 0],
        9 => [7, 6, 5, 4, 3, 3, 2, 1, 1],
        10 => [8, 7, 6, 5, 4, 3, 3, 2, 1],
    ];

    /**
     *  Две переменные, определяющие максимальные и минимальные значения
     *  для определения скорости ветра из таблиц
     *  @var integer
     */
    protected $maxWindRange = 15;
    protected $minWindRange = 3;

    /**
     * Таблица распределение скорости ветра по высотам
     * взависимости от скорости наземного ветра
     * @var array
     */
    protected $windDistr = [
        3  => [4,5,5,5,6,6,6,6,6],
        4  => [6,7,8,8,8,9,9,9,10],
        5  => [8,10,10,11,11,11,12,12,12],
        6  => [9,11,11,12,13,13,14,14,14,],
        7  => [10,12,13,13,14,14,15,15,16],
        8  => [12,14,15,16,17,17,18,18,19],
        9  => [14,17,18,19,20,20,21,21,22],
        10 => [15,18,19,20,21,21,22,23,24],
        11 => [16,20,21,22,23,24,25,25,26],
        12 => [18,22,23,24,25,26,27,28,29],
        13 => [20,23,25,26,27,28,29,30,32],
        14 => [21,25,27,28,29,30,32,32,34],
        15 => [22,27,28,30,32,32,34,36,36],
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
                    $ret = '12';
                    break;
                }
                case 7:
                case 8:
                case 9: {
                    $ret = '16';
                    break;
                }
                case 10:
                case 11:
                case 12: {
                    $ret = '16';
                }
            }
            return $ret;        }
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
                $h = $h; // Чтобы не ругалась проверка синтаксиса
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
        foreach ($this->hh as $hh) {
            $grp = $groups[$hh];
            $dt = $tempCorrections[$ind] * $sign;
            $tg = (integer) substr($grp, 0, 2);
            $tt = sprintf("%02d", $tg + $dt);
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
        return $groups;
    }


    /************************ ИМПЛЕМЕНТАЦИЯ АБСТРАКТНЫХ МЕТОДОВ *******************/
    /**
     *  Составляет бюллетень
     *  @param array $m
     *  Должен включать следующие переменные:
     *  numeric temp температура на уровне АМС
     *  integer $hAMS высота АМС над уровнем моря
     *  numeric $press атмосферное давление на уровне АМС
     *  integer $aW дирекционный угол направления ветра
     *  numeric $sW скорость ветра на уровне АМС
     *  integer $time время создания бюллетеня
     */
            
    public function compileBulletin(array $m) {
        //$temp, $hAMS, $press, $aW, $sW, $time = null
        $dH = $m['press']-750;
        $vt = round($this->getVirtualTemp($m['temp'])-15.9);
        $tGroup = $this->getTempGroup($vt);
        $wGroup = $this->getWindGroup($m['sW']);
        $dGroup = $this->getAlphaGroup($m['aW']);
        if(!isset($m['time'])){$m['time'] = time();}
        $ind = 0;
        $groups = [];
        foreach ($this->hh as $k){
            $groups[$k] = $tGroup[$ind] . $dGroup[$ind] . $wGroup[$ind];
            $ind++;
        }
        return new MeteoBulletin($m['time'],2,'',$m['hAMS'],$vt,$dH, $groups);
    }

    /**
     * Исправляет устаревший бюллетень
     * @param numeric $temp температура на уровне АМС
     * @param integer $hAMS высота АМС над уровнем моря
     * @param numeric $press атмосферное давление на уровне АМС
     * @param integer $aW дирекционный угол направления ветра
     * @param numeric $sW скорость ветра на уровне АМС
     * @param integer $time время создания бюллетеня
     */
    public function correctBulletin(array $m, MeteoBulletin $oldMB) 
    {
        //$temp, $hAMS, $press, $aW, $sW, $time
        $hrsPassed  = round(($m['time'] - $oldMB->ddhhm)/3600,1);
        $oldMB->ddhhm = $m['time'];
        $oldMB->atmPressAMS = $m['press'];
        $oldMB->hAMS = $m['hAMS'];
        $vt = round($this->getVirtualTemp($m['temp']) - 15.9);
        $wGroup = $this->getWindGroup($m['sW']);
        $dGroup = $this->getAlphaGroup($m['aW']);
        $deltaTemp = round($vt - $oldMB->airTempAMS);
        $sign = $deltaTemp < 0 ? -1 : 1;
        $tempCorrections = $this->deltaTau[abs($deltaTemp)];
        $groups = $oldMB->groups;
        // максимальный индекс исправления по высоте для показателей ветра.
        $maxIndex = array_search($this->getMaxHeight($hrsPassed), $this->hh);
        $oldMB->groups = correctGroups($groups, $sign, $tempCorrections, 
                                        $maxIndex, $dGroup, $wGroup);
        return $oldMB;
    }
           
}
