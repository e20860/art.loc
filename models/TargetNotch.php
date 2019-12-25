<?php

namespace app\models;

use yii\base\Model;

/**
 * Засечка цели (строка из журнала разведки и обслуживания стрельбы)
 *
 * @author Admin
 */
class TargetNotch extends Model
{
    public $num;
    public $time;
    public $polars; // дирекционный, дальность, угол места
    public $rumb;
    public $range;
    public $targetType;
    public $targetName;
    public $coords; // прямоугольные координаты
    public $accuracy;
    public $notes;
    
    public function attributeLabels() {
        return [
            'num' => 'Номер цели',
            'time' => 'Время засечки',
            'polars' => 'Полярные координаты',
            'rumb' => 'Правый',
            'range' => 'Дальность правого',
            'targetType' => 'Тип цели',
            'targetName' => 'Наименованиие цели',
            'coords' => 'Прямоугольные координаты цели',
            'accuracy' => 'Характеристика точности',
            'notes' => 'Примечания',
        ];
    }


    public function rules() {
        return [
            [['num','time','polars',
                'targetName','coords','accuracy'],'required'],
            [['rumb','range','notes'],'safe'],
            [['num','targetName','accuracy','notes'],'string'],
            [['time'],'time'],
            [['polars'],'Polar'],
            [['coords'],'Grc'],
            [['range'],'integer'],
        ];
    }
}
