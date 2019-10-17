<?php

/*
 * Current Slavko Project
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */

namespace app\components;

use Exception;

/**
 * Абстрактный класс Meteopost с общими данными 
 * для составления/исправления бюллетеня
 * на основе данных "Указаний по работе метеопоста адн"
 * 
 * @author E.Slavko <e20860@mail.ru>
 */
abstract class Meteopost {

    /**
     *
     * @var MeteoBulletin Метеобюллетень 
     */
    protected $bulletin;
    
    /**
     * Набор наземных метеоэлементов
     * @var array 
     */
    protected $measurements = [
        'time'  => 0,
        'hAMS'  => 0,
        'temp'  => 15,
        'press' => 750,
        'aW'    => 0,
        'sW'    => 0, // Скорость ветра или дальность сноса пуль
    ];

    /**
     * Массив стандартых высот бюллетеня
     * @var array
     */
    protected $hh = ['02','04','08','12','16','20','24','30','40'];

    /**
     *  Две переменные, определяющие максимальные и минимальные значения
     *  для определения скорости ветра из таблиц
     *  @var integer
     */
    protected $maxWindRange = 0;
    protected $minWindRange = 0;


    /**
     * Таблица распределения температуры воздуха по высоте
     * от -40 до -10: с дискретностью 10 градусов (нужна интерполяция)
     * от -10 до -3 : с дискретностью 1 градус (брать без изменений)
     * свыше -3: температура с изменением высоты - не меняется. 
     * То с чем вошли - то и в бюллетене
     * 
     * @var array
     */
    protected $tempDeviation = [
        50 => [-49, -48, -46, -44, -42, -40, -38, -37, -31],
        40 => [-39, -38, -37, -35, -34, -32, -31, -30, -27],
        30 => [-29, -29, -28, -26, -25, -24, -23, -22, -20],
        20 => [-20, -19, -18, -17, -17, -16, -15, -15, -14],
        10 => [-9, -9, -8, -8, -7, -7, -7, -6, -6],
        9  => [-8, -8, -7, -7, -7, -6, -6, -5, -5],
        8  => [-8, -8, -7, -6, -6, -6, -5, -5, -4],
        7  => [-7, -6, -6, -5, -5, -5, -5, -4, -4],
        6  => [-6, -6, -6, -5, -4, -4, -4, -4, -4],
        5  => [-5, -5, -5, -4, -4, -4, -4, -4, -4],
        4  => [-4, -4, -4, -4, -3, -3, -3, -3, -3],
        3  => [-3, -3, -3, -3, -3, -3, -2, -2, -2],
        ];   


    /**
     * Возвращает виртуальную температуру
     * @param numeric $temp
     * @return numeric
     */
    protected function getVirtualTemp($temp) {
        $retTemp = $temp;
        if ($temp > 0 && $temp <= 5) {
            $retTemp = $temp + 0.5;
        } else if ($temp > 5 && $temp < 10) {
            $retTemp = $temp + 0.5 + ($temp - 5) * 0.1;
        } else if ($temp >= 10 && $temp <= 15) {
            $retTemp = $temp + 1;
        } else if ($temp > 15 && $temp <= 20) {
            $retTemp = $temp + 1 + ($temp - 15) * 0.1;
        } else if ($temp > 20 && $temp <= 25) {
            $retTemp = $temp + 1.5 + ($temp - 20) * 0.1;
        } else if ($temp > 25 && $temp <= 30) {
            $retTemp = $temp + 2 + ($temp - 25) * 0.3;
        } else if ($temp > 30 && $temp <= 40) {
            $retTemp = $temp + 3.5 + ($temp - 30) * 0.1;
        }
        return $retTemp;
    }
    
    /**
     * Возвращает массив распределения ветра по высоте
     * @param numeric $speed
     * @return type
     */
    protected function getWindGroup($speed) {
        if ($speed < $this->minWindRange || $speed > $this->maxWindRange) {
            return ['00', '00', '00', '00', '00', '00', '00', '00', '00'];
        }
        $ret = array_map(function($v) {
            return sprintf("%02d", $v);
        }, $this->windDistr[$speed]);
        return $ret;
    }

    /**
     *  Возвращает приращения дирекционных углов по высотам
     * @param type $alpha
     * @return type
     */
    protected function getAlphaGroup($alpha)
    {
        $ret = array_map(function($v) use ($alpha){
            $val = $v + $alpha > 60 ? $v + $alpha-60 : $v + $alpha;
            return sprintf("%02d",$val);
        }, $this->deltaAlpha);
        return $ret;
    }
    
    /**
     * Управляет составлением/исправлением бюллетеня
     * @param numeric $time
     * @throws Exception
     */
    protected function manageBulletin($time)
    {
        if(empty($this->bulletin) && $this->measurements['time']==0) {
            throw new Exception('Не проведён замер метеоэлементов');
        }

        $interval = empty($this->bulletin)? 2 :(integer) ($this->bulletin->ddhhm - $time)/3600;
        
        if($interval > 3 && $interval < 12 && $this->bulletin->type == 1){
            $this->bulletin = $this->correctBulletin($this->measurements, $this->bulletin);
        } else {
            $this->bulletin = $this->compileBulletin($this->measurements);
        }
    }

        /**
     * Принимает бюллетень из строкового представления и сохраняет его
     * @param string $str Строковое представление бюллетеня "Метеосредний/приближённый"
     * @throws Exception
     */
        public function recieve($str) {
        $arr = explode('-', $str);
        if (stripos($arr[0], 'Метео11') === false) {
            Throw new Exception('Некорректный формат бюллетеня');
        }
        $grpTitle = array_shift($arr);
        if (stripos($grpTitle, 'приближ') > 0) {
            $type = 2;
            $numAMS = '';
        } else {
            $type = 1;
            $numAMS = mb_substr($grpTitle, 7, 2);
        }
        // Формат времени ДДЧЧМ
        $grpDate = array_shift($arr);
        $day = (integer) substr($grpDate, 0, 2);
        $hour = (integer) substr($grpDate, 2, 2);
        $min = substr($grpDate, 4, 1) * 10;
        $month = (integer) date("m");
        $ddhhm = mktime($hour, $min, 0, $month, $day);
        // Высота АМС
        $hAMS = (integer) array_shift($arr);
        // Отклонение давления и температуры
        $bbbtt = array_shift($arr);
        $dH = (integer) substr($bbbtt, 0, 3);
        $atmPressAMS = $dH > 0 ? $dH : ($dH - 500) * -1;
        $dT = (integer) substr($bbbtt, 3, 2);
        $airTempAMS = $dT > 0 ? $dT : ($dT - 50) * -1;
        // Группы по высотам
        $groups = [];
        while (!empty($arr)) {
            $k = array_shift($arr);
            $groups[$k] = array_shift($arr);
        }
        $this->bulletin = new MeteoBulletin(
            $ddhhm, $type, $numAMS, $hAMS, $airTempAMS, $atmPressAMS, $groups);
    }
    /**
     * Процедура получения метеоэлементов по результатам замера
     * @param numeric $temp
     * @param numeric $hAMS
     * @param numeric $press
     * @param numeric $aW
     * @param numeric $sW
     * @param timestamp $time
     */
    public function measure($temp, $hAMS, $press, $aW, $sW, $time)
    {
        //if(!isset($time)) {$time = time();}
        $this->measurements['time'] = $time;
        $this->measurements['temp'] = $temp;
        $this->measurements['hAMS'] = $hAMS;
        $this->measurements['press'] = $press;
        $this->measurements['aW'] = $aW;
        $this->measurements['sW'] = $sW;
        $this->manageBulletin($time);
    }

    /**
     * Возвращает метеобюллетень, если он создан
     * @return Meteobulletin
     * @throws Exception
     */
    public function getBulletin()
    {
        if(empty($this->bulletin)) {
            throw new Exception('Бюллетень отсутствует. Нет замеров/не принят от АМС');
        }
        return $this->bulletin;
    }


    abstract function compileBulletin(array $m);
    
    abstract function correctBulletin(array $m, MeteoBulletin $oldMB);

}
