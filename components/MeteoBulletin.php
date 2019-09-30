<?php

/*
 * HMDoll project framework of internet-shop
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */

namespace app\components;

/**
 * Метеорологический артиллерийский бюллетень
 *
 * @author E.Slavko
 */
class MeteoBulletin {
    /**
     * Время составления метеобюллетеня
     * @var integer (time)
     */
    protected $ddhhm;
    /**
     * Тип бюллетеня (метеосредний=1/метеоприближённый=2)
     * @var integer
     */
    protected $bulType = 2;
    /**
     * Номер метеостанции для метеосреднего
     * @var string
     */
    protected $numAMS;
    
    /**
     * Высота метеостанции над уровнем моря
     * @var integer
     */
    protected $hAMS;
    
    /**
     * Скорость наземного ветра на уровне АМС
     * @var integer
     */
    protected $airTempAMS;
    
    /**
     * Атмосферное давление на уровне АМС
     * @var integer
     */
    protected $atmPressAMS;
    
    /**
     * Массив групп изменения метеоэлементов по стандартным высотам
     * @var array
     */
    protected $groups = [];
    
    /**
     * Кoнструктор
     * @param integer $ddhhm
     * @param integer $bulType
     * @param string $numAMS
     * @param integer $hAMS
     * @param integer $airTempAMS
     * @param integer $atmPressAMS
     * @param array $groups
     */
    public function __construct($ddhhm, $bulType,$numAMS,$hAMS,$airTempAMS,$atmPressAMS,$groups)
    {
        $this->ddhhm = $ddhhm;
        $this->bulType = $bulType;
        $this->numAMS = $numAMS;
        $this->hAMS = $hAMS;
        $this->airTempAMS = $airTempAMS;
        $this->atmPressAMS = $atmPressAMS;
        $this->groups = $groups;
    }
    /**
     * Строковое представление бюллетеня
     * @return string
     */
    public function __toString() 
    {
        $ret = 'Метео11';
        $ret .= $this->bulType==1?sprintf("%02d", $this->numAMS):'приближённый';
        $ret .= ('-' . substr(date("dHi", $this->ddhhm),0,5));
        $ret .= sprintf("-%04d", $this->hAMS);
        $ret .= sprintf("-%03d", $this->atmPressAMS > 0?$this->atmPressAMS:abs($this->atmPressAMS-500));
        $ret .= sprintf("%02d", $this->airTempAMS > 0?$this->airTempAMS:abs($this->airTempAMS-50));
        foreach($this->groups as $k=>$v){
            $ret .= ('-'.$k.'-'.$v);
        }
        return $ret;
    }
    /**
     * Магический геттер
     * @param mixed $name
     * @return mixed
     */
    public function __get($name) {
        if (property_exists($this, $name)){
            return $this->$name;
        }
    }
    
    /**
     * 
     * @param string $grp
     * @return string
     */
    public function getGroup($grp)
    {
        return $this->groups[$grp];
    }
    
}
