<?php

/*
 * Artillery project
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */

namespace app\components;

use app\models\Directional;
use app\models\Sdu;

/**
 * Полярные координаты
 *
 * @author E.Slavko <e20860@mail.ru>
 */
class Polar {
    /**
     * Дирекционный угол
     * @var Directional
     */
    protected $a;
    
    /**
     * Дальность
     * @var real
     */
    protected $d = 0;
    
    /**
     * Угол места
     * @var Sdu
     */
    protected $e;
    /**
     * Конструктор
     * @param Directional $a
     * @param real $d
     * @param Sdu $e
     */
    public function __construct(Directional $a, $d,Sdu $e) {
        $this->a = $a;
        $this->d = $d;
        $this->e = $e;
    }
    /**
     * Получение частных значений
     * @return real
     */
    public function __get($prop) {
        $ret = 0;
        switch ($prop){
            case 'a':
                $ret = $this->a->rad;
                break;
            case 'd':
                $ret = $this->d;
                break;
            case 'e':
                $ret = $this->e->rad;
                break;
        }
        return $ret;
    }
    
    /**
     *  Устанавливает частные значения
     * @param string $prop наименование свойства
     * @param real $value Значение свойства
     */
    public function __set($prop, $value) {
	if($prop=='a' || $prop=='e'){
            $this->$prop->rad = $value;
	} else {if (property_exists($this, $prop)) {
            $this->$prop = $value;
            }
	}
    }
    /**
     * 
     * @return string
     */
    public function __toString() {
        return 'A = '. $this->a.', Д = ' . $this->d . ', Е = '.$this->e;
    }

}
