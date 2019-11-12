<?php

/*
 * Artillery project 
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */

namespace app\components;

/**
 * Геодезические прямоугольные координаты
 *
 * @author E.Slavko <e20860@mail.ru>
 */
class Grc 
{
    /**
     *
     * @var integer координата X (абсцисса) 
     */
    protected $x = 0;
    /**
     *
     * @var integer координата Y (ордината) 
     */
    protected $y = 0;
    /**
     *
     * @var integer  H (высота в метрах над уровнем балтийского моря) 
     */
    protected $h = 0;
    /**
     * Конструктор
     * @param integer $x
     * @param integer $y
     * @param integer $h
     */
    public function __construct($x,$y,$h) 
    {
        $this->x = $x;
        $this->y = $y;
        $this->h = $h;
    }
    /**
     *  Магический метод
     * @param type $name
     * @return type
     */
    public function __get($name) {
        if(property_exists($this,$name)){
            return $this->$name;
        }
    }

    /**
     *  Магический метод
     * @param type $name
     * @param type $value
     * @return type
     */    
    public function __set($name, $value) {
        if(property_exists($this,$name)){
            return $this->$name  = $value;
        }
    }

        public function __toString() {
        return 'x= ' .$this->x.', y= ' .$this->y .', h= ' .$this->h;
    }
    
    public function toTable()
    {
        return '' .round($this->x).' ' .round($this->y) .' ' .round($this->h);
    }
}
