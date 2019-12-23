<?php


namespace app\controllers;

use yii\base\Controller;
use app\components\Polar;
use app\components\Grc;
use app\components\Topo;
use app\components\Knp;
use app\components\Sdu;
use app\components\Directional;
use yii\data\ArrayDataProvider;
use app\models\Notch;

/**
 * Description of ReconnaissanceController
 *
 * @author Admin
 */
class ReconnaissanceController extends Controller
{
    private function makeFakeJournal($nn)
    {
        $sess = \Yii::$app->session;
        if(!isset($sess['knp'])){
            $knp = new Knp('КНП-5',new Grc(29350, 61220, 120));
            $knp->unit = '5';
            $sess['knp'] = $knp;
        }
        if(!isset($sess['osn'])){
            $sess['osn'] = new Directional(round(rand(0, 59)), 0);
        }
        if(!isset($sess['operDate'])){$sess['operDate']=time();}
        for($i=0;$i<$nn;$i++){
            $journal[] = $this->getFakeRecord();
        }
        $sess['journal'] = $journal;
    }
    
    private function getFakeRecord()
    {
        $sess = \Yii::$app->session;
        $tn = new \app\models\TargetNotch();
        $tn->num = (string) round(rand(101,300));
        $tn->time = time();
        $on = (integer) (substr((string) $sess['osn'],0,2));
        $min = $on - 7;
        $max = $on + 7;
        $bdu = round(rand($min, $max));
        $mdu = round(rand(1,99));        
        $tn->polars = new Polar(new Directional($bdu, $mdu),
                round(rand(1500, 3500)),
                new Sdu('-',0,round(rand(0,50))));
        $tn->rumb = new Directional(0,0);
        $tn->range = 0;
        $targets = ['пехота','пехота укрытая', 'опорный пункт','набл. пункт','мин. батр'];
        $tn->targetName = $targets[rand(0, 4)];
        $tn->coords = Topo::pgz($sess['knp'], $tn->polars);
        $tn->accuracy = 'точно';
        $tn->notes = 'сгенерировано автоматически';
        return $tn;
    }
    
    private function prepareNotch()
    {
        $notch = new Notch();
        $num = 0;
        foreach (\Yii::$app->session['journal'] as $str) {
            if ($str['num'] > $num) {
                $num = $str['num'];
            }
        }
        $notch->num = $num + 1;
        $notch->hours = date('H');
        $notch->mins = date('i');
        $notch->height = \Yii::$app->session['knp']->h;
        $notch->e = '+0-00';
        $notch->accuracy = 'точно';
        return $notch;
    }


    public function actionJournal()
    {
        if(!isset( \Yii::$app->session['journal'])){
            $this->makeFakeJournal(3);
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => \Yii::$app->session['journal'],
            'pagination' => [
                'pageSize' => 10,],
            'sort' => [ 'attributes' => ['num',], ], ]);
        $notch = $this->prepareNotch();
        return $this->render('journal',[ 'provider' => $dataProvider, 'notch' => $notch,]);
    }
    
    
    
    public function actionNotch()
    {
        $data = \Yii::$app->request->post()['Notch'];
        $jrn = \Yii::$app->session['journal'];
        $operDate = \Yii::$app->session['operDate'];
        $nr = new \app\models\TargetNotch;
        $nr->num = $data['num'];
        $nr->time = mktime($data['hours'], $data['mins'], 0, date('m',$operDate), date('d',$operDate), date('Y',$operDate));
        $alpha = explode('-', $data['alpha']);
        $e = new Sdu(substr($data['e'],0,1),substr($data['e'],1,1),substr($data['e'],3,2));
        $nr->polars = new Polar(new Directional($alpha[0], $alpha[1]), $data['range'], $e);
        $nr->rumb = new Directional(0, 0);
        $nr->range = 0;
        $nr->targetName = $data['tgtName'];
        $nr->coords = Topo::pgz(\Yii::$app->session['knp'], $nr->polars);
        $nr->accuracy = $data['accuracy'];
        $nr->notes = $data['notes'];
        $jrn[] = $nr;
        \Yii::$app->session['journal'] = $jrn;
        \Yii::$app->session->setFlash('success','Данные засечки цели записаны в журнал');
        return \Yii::$app->response->redirect('/reconnaissance/journal');
        }
   
    /**
     * Крупномасштабный планшет
     */
    public function actionPlanshet()
    {
        return $this->render('planshet');
        
    }
}
