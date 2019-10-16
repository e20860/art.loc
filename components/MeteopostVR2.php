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
    protected $maxWindRange = 15;
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

    
        public function compileBulletin($temp, $hAMS, $press, $aW, $sW, $time = null) {
            $qq = 1;
        }
        
        public function correctBulletin($temp, $hAMS, $press, $aW, $sW, $time = null, $oldMB = null) {
            $qq = 1;
        }
            
    
}
