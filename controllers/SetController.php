<?php

namespace app\controllers;

use app\models\BattleOrder;
use app\components\Directional;
use app\components\Knp;
use app\components\Op;
use app\components\Grc;

/**
 * Установки системы
 *
 * @author Admin
 */
class SetController extends \yii\base\Controller {

    protected function SetOrder($bo)
    {
        $sess = \Yii::$app->session;
        if(isset($bo)){
            $sess['osn'] = new Directional($bo['osn'], 0);
            $reg = '/(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})/s';
            $matches = [];
            preg_match($reg, $bo['operDate'], $matches);
            $sess['operDate'] = mktime($matches[4], $matches[5], 0, $matches[2],
                    $matches[3], $matches[1]);
            $sess['unitName'] = $bo['unitName'];
            $sess['knp'] = new Knp('кнп батареи', new Grc($bo['xknp'],$bo['yknp'],$bo['hknp']));
            $sess['bnp'] = new Knp('правый', new Grc($bo['xbnp'],$bo['ybnp'],$bo['hbnp']));
            $sess['op'] = new Op('op батареи', new Grc($bo['xop'],$bo['yop'],$bo['hop']));
            $sess['op']->nGuns = $bo['nGuns'];
            $sess['op']->caliber = $bo['caliber'];
            $sess['op']->artSystem = $bo['artSystem'];
        }
    }
    
    protected function fillModel()
    {
        $sess = \Yii::$app->session['battleOrder'];
        $bo = new BattleOrder();
        if(!isset($sess['osn'])){return $bo;}
        $bo->osn = round($sess['osn']->rad/0.10471975511966);
        $bo->operDate = $sess['operDate'];
        $bo['unitName'] = $sess['unitName'];
        $bo['xknp'] = $sess['knp']->x;
        $bo['yknp'] = $sess['knp']->y;
        $bo['hknp'] = $sess['knp']->h;
        $bo['xbnp'] = $sess['bnp']->x;
        $bo['ybnp'] = $sess['bnp']->y;
        $bo['hbnp'] = $sess['bnp']->h;
        $bo['nGuns'] = $sess['op']->nGuns;
        $bo['caliber'] = $sess['op']->caliber;
        $bo['artSystem'] = $sess['op']->artSystem;
        $bo['xop'] = $sess['op']->x;
        $bo['yop'] = $sess['op']->y;
        $bo['hop'] = $sess['op']->h;
        return $bo;
    }

    public function actionBattleorder()
    {
        $bo = \Yii::$app->request->post('BattleOrder');
        if(!is_null($bo)){
            $this->SetOrder($bo);
            return \Yii::$app->response->redirect('/reconnaissance/journal');
        } else {
            $model = $this->fillModel();
            return $this->render('battle-order',[
                'model' => $model,
            ]);
        }
    }

}
