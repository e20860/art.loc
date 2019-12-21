<?php

namespace app\components;

/**
 * Артиллерийское орудие
 *
 * @author Admin
 */
class Gun {
    
    /**
     * Номер орудия в батарее
     * @var character
     */
    private $number;
    
    /**
     * Расход и наличие боеприпасов
     * @var array of amunition
     */
    private $ammo;
    
    public function __construct($number) 
    {
        $this->number = $number;
        $this->ammo = [
            'expence' => '', // расход
            'balance' => '', // остаток
        ];
    }
    
    public function __get($name) {
        if(property_exists($this, $name)){
            return $this->$name;
        }
    }
    
    public function __set($name, $value) {
        if(property_exists($this, $name)){
            $this->$name = $value;
        }
    }
    /**
     *  Выстрел орудия
     * @param string $atype тип боеприпаса
     * @return boolean ИСТИНА - если остаток боеприпасов данного типа больше 0
     */
    public function shot ($atype)
    {
        $this->ammo['expence'][$atype] ++;
        $this->ammo['balance'][$atype] --;
        return $this->ammo['balance'][$atype] > 0;
    }
    
    /**
     * Возвращает расход боеприпасов данного типа
     * @param string $atype
     * @return numeric
     */
    public function getExpence($atype)
    {
        return $this->ammo['expence'][$atype];
    }
    
    /**
     * Возвращает остаток боеприпасов данного типа
     * @param string $atype
     * @return numeric
     */
    public function getBalance($atype)
    {
        return $this->ammo['balance'][$atype];
    }
    
    
    /**
     *  Приём боеприпасов
     * @param string $atype
     * @param integer $quantity количество боеприпасов
     */
    public function takeAmmo($atype, $quantity)
    {
        if(isset($this->ammo['balance'][$atype])){
            $this->ammo['balance'][$atype] += $quantity;
        } else {
            $this->ammo['balance'][$atype] = $quantity;
        }
    }
    /**
     * Возврат боеприпасов
     * @param string $atype тип боеприпасов
     * @param integer $quantity количество боеприпасов
     * @return integer Количество реально выданных боеприпасов
     */
    public function giveAmmo($atype, $quantity)
    {
        if(isset($this->ammo['balance'][$atype])){
            $this->ammo['balance'][$atype] -= $quantity;
            $q = $this->ammo['balance'][$atype];
            if($q < 0){
                $this->ammo['balance'][$atype] = 0;
                $q += $quantity;
            }
            $this->ammo['expence'][$atype] += $q;
        } else {
            $this->ammo['balance'][$atype] = 0;
            $q = 0; // Ничего не выдано
        }
        return $q;
    }
    
}
