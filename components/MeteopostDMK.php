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
abstract class MeteopostDMK extends Meteopost{
    /**
     * Изменение дирекционного угла ветра по высоте
     * @var array
     */
    protected $deltaAlpha = [1,2,3,3,4,4,4,5,5];

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
    
    abstract function createBulletin($temp, $hAMS, $press, $aW, $sW, $time = null, $oldMB=null);

}
