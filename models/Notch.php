<?php

namespace app\models;

/**
 * Description of Notch
 *
 * @author Admin
 */
class Notch extends \yii\base\Model
{
    public $num;
    
    public $tgtName;
    
    public $tgtType;

    public $hours;
    
    public $mins;
    
    public $alpha;
    
    public $range;
    
    public $height;
    
    public $e;
    
    public $accuracy;
    
    public $notes;
    
public function attributeLabels() {
        return [
            'num'=>'Номер',
            'tgtName'=>'Характер цели',
            'hours'=>'Часов',
            'mins'=>'Минут',
            'alpha'=>'Дирекционный',
            'range'=>'Дальность',
            'height'=>'Высота',
            'e'=>'Угол места',
            'accuracy'=>'Точность',
            'notes'=>'Примечания',
        ];
}

    public function rules() {
        return [
            [['num', 'tgtName', 'hours', 'mins',
               'alpha','range','accuracy'],'required'],
            [['height','e','notes'],'safe'],
            [['num','tgtName','accuracy','notes','alpha','e'],'string'],
            [['range','height','hours','mins'],'integer'],
        ];
    }
}
