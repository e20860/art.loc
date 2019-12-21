<?php

namespace app\models;

use yii\base\Model;
/**
 * Боевой порядок батареи
 *
 * @author Admin
 */
class BattleOrder extends Model
{
    public $osn;
    public $operDate;
    public $unitName;
    public $xknp;
    public $yknp;
    public $hknp;
    public $xbnp;
    public $ybnp;
    public $hbnp;
    public $nGuns;
    public $caliber;
    public $artSystem;
    public $xop;
    public $yop;
    public $hop;
    
    public function rules() 
    {
        return[
            [['osn', 'operDate', 'unitName', 'xknp', 'yknp', 'hknp',
                'xop', 'yop', 'hop', 'nGuns', 'caliber', 'artSystem'], 'required'],
            [['xbnp', 'ybnp', 'hbnp'],'safe'],
            [['operDate'],'date'],
            [['unitName','artSystem'],'string'],
            [['osn', 'xknp', 'yknp', 'hknp', 'xop', 'yop', 'hop', 
              'nGuns', 'caliber'], 'integer'],
        ];
    }
    
        public function attributeLabels() {
        return [
            'osn' => 'Основное направление',
            'operDate' => 'Оперативная дата',
            'unitName' => 'Наименование подразделения',
            'xknp' => 'Х кнп',
            'yknp' => 'У кнп',
            'hknp' => 'Н кнп',
            'xbnp' => 'Х бнп',
            'ybnp' => 'У бнп',
            'hbnp' => 'Н бнп',
            'nGuns' => 'Количество орудий',
            'caliber' => 'Калибр',
            'artSystem' => 'Арт система',
            'xop' => 'Х оп',
            'yop' => 'У оп',
            'hop' => 'Н оп',
        ];
    }
}
