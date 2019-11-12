<?php

namespace app\components;

use app\components\Point;

/**
 * Командно-наблюдательный пункт
 *
 * @author Admin
 */
class Knp  extends Point
{
    protected $unit = 'батр';
    
    
    
    public function __toString() {
        return 'КНП ' . $this->unit . ', X= ' . $this->x . ', Y= ' . $this->y .
                ', H= ' . $this->h;
    }
}
