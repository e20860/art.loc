<?php


namespace app\components;

use app\components\Point;
/**
 * Description of Target
 *
 * @author Admin
 */
class Target extends Point{
    protected $number = '001';
    protected $type = '';
    protected $front = 0;
    protected $depth = 0;
    
    public function __toString() {
        return $this->name . ', цель ' . $this->number . '-я, X= '.$this->x . 
                ', Y= ' . $this->y.', высота= ' .$this->h . 
                ($this->front > 0?', фронт ' . $this->front:'') .
                ($this->depth>0?', глубина ' . $this->depth:'');
    }
}
