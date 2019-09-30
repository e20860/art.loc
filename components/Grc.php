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
     * Возвращает координату Х
     * @return integer
     */
    public function getX()
    {
        return $this->x;
    }
    /**
     * Возвращает координату Y
     * @return integer
     */
    public function getY()
    {
        return $this->y;
    }
    /**
     * Возвращает координату H
     * @return integer
     */
    public function getH()
    {
        return $this->h;
    }
    /**
     * Устанавливает координату X
     * @param integer $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }
    /**
     * Устанавливает координату Y
     * @param integer $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }
    /**
     * Устанавливает высоту точки H
     * @param integer $h
     */
    public function setH($h)
    {
        $this->h = $h;
    }
    public function __toString() {
        return 'x= ' .$this->x.', y= ' .$this->y .', h= ' .$this->h;
    }
}
