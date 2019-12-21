<?php

/*
 * Current Slavko Project
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */

namespace app\components;

use app\components\MeteoBulletin;
/**
 * Создаёт бюллетень на основании его строкового представления
 *
 * @author E.Slavko <e20860@mail.ru>
 */
class RecieverBulletin 
{
    public function recieve($str)
    {
        $arr = explode('-', $str);
        if (stripos($arr[0],'Метео11')=== false) {
            Throw new Exception('Некорректный формат бюллетеня');
        }
        $grpTitle = array_shift($arr);
        if(stripos($grpTitle,'приближ') > 0) {
            $type = 2;
            $numAMS = '';
        } else {
            $type = 1;
            $numAMS = mb_substr($grpTitle, 7,2);
        }
        // Формат времени ДДЧЧМ
        $grpDate = array_shift($arr);
        $day = (integer) substr($grpDate,0,2);
        $hour = (integer) substr($grpDate,2,2);
        $min = substr($grpDate,4,1)*10;
        $month = (integer) date("m");
        $ddhhm = mktime($hour, $min,0, $month,$day);
        // Высота АМС
        $hAMS = (integer) array_shift($arr);
        // Отклонение давления и температуры
        $bbbtt = array_shift($arr);
        $dH = (integer) substr($bbbtt,0,3);
        $atmPressAMS = $dH > 0? $dH:($dH - 500) * -1;
        $dT = (integer) substr($bbbtt,3,2);
        $airTempAMS = $dT > 0? $dT:($dT - 50) * -1;
        // Группы по высотам
        $groups = [];
        while (!empty($arr)){
            $k = array_shift($arr);
            $groups[$k] = array_shift($arr);
        }
        return new MeteoBulletin($ddhhm, $type, $numAMS, $hAMS, $airTempAMS, $atmPressAMS, $groups);
    }
}
