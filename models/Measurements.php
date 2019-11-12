<?php

namespace app\models;

use yii\base\Model;

/**
 * Description of Measures
 *
 * @author Admin
 */
class Measurements extends Model{
    
    public $tool;
    public $temp;
    public $hAMS;
    public $press;
    public $aW;
    public $sW;
    public $ddhhm;
    
    public function attributeLabels() {
        return [
            'tool' => 'Средство метеоизмерений',
            'temp' => 'Наземная температура',
            'hAMS' => 'Высота метеостанции',
            'press' => 'Наземное давление',
            'aW' => 'Направление ветра',
            'sW' => 'Скорость ветра',
            'ddhhm' => 'Время измерений',
        ];
    }
    
    public function rules() {
        return [
            [['tool','temp','hAMS','press','aW','sW','time'],'required'],
            [['temp','hAMS','press','aW','sW'],'integer'],
            [['ddhhm'],'time'],
            [['tool'],'string'],
        ];
    }
    
}
