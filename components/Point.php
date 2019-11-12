<?php

namespace app\components;
use app\components\Grc;

/**
 * Пункт. Родоначальник всехобъектов, имеющих кординаты.
 *
 * @author Admin
 */
class Point {
    /**
     *
     * @var string Наиенование точки
     */
    private $name;
    
    /**
     *
     * @var Grc Прямоугольные координаты точки
     */
    private $coords;
    
    public function __construct(string $name, Grc $coords) 
    {
        $this->name = $name;
        $this->coords = $coords;
    }
    
    public function __get($name) 
    {
        switch ($name){
            case 'x': {
                $ret = $this->coords->x;
                break;}
            case 'y': {
                $ret = $this->coords->y;
                break;}
            case 'h': {
                $ret = $this->coords->h;
                break;}
            default : {
                if(property_exists($this, $name)) {
                    $ret = $this->$name;
                        }  
                      }
        }
        return $ret;
    }
    
    public function __set($name, $value) 
    {
        switch ($name){
            case 'x':{
                $this->coords->x = $value;
                break;
        }
            case 'y':{
                $this->coords->y = $value;
                break;
        }
            case 'h':{
                $this->coords->h = $value;
                break;
        }
            default : {
                if(property_exists($this, $name)) {
                    $this->$name = $value;
                        }  
                      }
        }
    }
}
