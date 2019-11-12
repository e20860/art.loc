<?php


namespace app\components;

use app\components\Point;
/**
 * Огневая позиция артиллерийского подразделения
 *
 * @author Admin
 */
class Op extends Point
{
    protected $unit = 'батр';

    protected $guns = [];
    
    protected $front;
    
    protected $corrections = [];
    
    public function __toString() {
        return 'ОП ' . $this->unit . ', X= ' . $this->x . ', Y= ' . $this->y .
                ', H= ' . $this->h;
    }

}
