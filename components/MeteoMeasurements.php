<?php

/*
 * Current Slavko Project
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */

namespace app\components;

/**
 * Метеорологические измерения метеопоста
 * (пока заглушка, в последующем - таблица БД)
 *
 * @author E.Slavko <e20860@mail.ru>
 */
class MeteoMeasurements 
{
    protected $time;
    protected $hAMS;
    protected $airTemperature;
    protected $airPressure;
    protected $windDirection;
    protected $windSpeed;
    protected $bulletDeflection;
    //-------------------------------------------
    /**
     * Конструктор класса
     * @param timestamp $time  Время производства метеоизмерений
     * @param integer $hAMS Высота метеопоста над уровнем моря
     * @param numeric $temp  Наземная температура воздуха (градусы Цельсия)
     * @param integer $press Наземное давление воздуха (мм рт. ст.)
     * @param integer $dir   Наземный дирекционный угол ветра (большие деления угломера)
     * @param integer $spd   Скорость наземного ветра 
     * @param integer $dfl   Дальность сноса ветровых пуль
     */
    
    public function __construct($time = null,$hght = 0, $temp = 15,$press = 750, 
            $dir = 0, $spd = 3, $dfl = 0) 
    {
        if(!isset($time)){$time = time();}
        $this->time = $time;
        $this->hAMS = $hght;
        $this->airTemperature = $temp;
        $this->airPressure = $press;
        $this->windDirection = $dir;
        $this->windSpeed = $spd;
        $this->bulletDeflection = $dfl;
    }
    
    /**
     * Магический геттер
     * @param mixed $name
     * @return mixed
     */
    public function __get($name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }
    /**
     * Магический сеттер
     * @param mixed $name Наименование свойства
     * @param mixed $value Значение свойства
     */
    public function __set($name,$value) {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

}
