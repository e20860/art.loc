<?php

/*
 * Artillery project 
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */

namespace app\components;

/**
 * Деления угломера (артиллерийская система углов)
 * Абстрактный класс
 * @author E.Slavko <e20860@mail.ru>
 */
abstract class Du {
    /**
     * Большие деления угломера
     * @var integer
     */
    protected $bdu = 0;
    /**
     * Малые деления угломера
     * @var integer
     */
    protected $mdu = 0;
    /**
     * Значение угла в радианах
     * @var real
     */
    protected $rad = 0;
    
    /**
     * Знак отклонения для доворотов и углов места
     * @var real
     */
    protected $sign = 1;
    
    /**
     * Конструктор
     */
    abstract public function __construct($bdu,$mdu,$sign);
    
    /**
     * Возвращает значение в радианах
     * @return real
     */
    public function __get($prop)
    {
                if($prop=='rad') {return $this->rad;}
		return $this->__toString();
    }
    
    /**
     * Устанавливает значение всех угловых свойств
     * @param real $rad
     */
    public function __set($prop,$val)
    {
        if($prop=='rad'){
            $this->rad = $val;
            $res = $this->radToDu();
            $this->bdu = floor($res);
            $this->mdu = round(($res- $this->bdu)*100,0);
            $this->sign = $this->rad>0?1:-1;
        }
    }
    
    /**
     * Добавляет значение в радианах к существующим данным класса
     * @param real $rad
     */
    public function add($rad)
    {
        $this->__set('rad',$rad + $this->rad);
    }

    /**
     *  Возвращает строковое представление класса
     * @return string
     */
    abstract public function __toString() ;

    /**
     * Перевод делений угломера в радианы
     * @return real
     */
    protected function duToRad()
    {
        return ($this->bdu + $this->mdu/100)/9.5492965855137;
    }
    /**
     * Перевод радиан в деления угломера
     * @return real
     */
    protected function radToDu()
    {
        return round($this->rad/0.10471975511966, 2);
    }
}
