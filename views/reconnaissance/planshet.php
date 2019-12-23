<?php
/**
 *  Крупномаснтабный планшет
 * 
 * @var model
 */
    $bo = Yii::$app->session['battleOrder'];
    $quadrant = floor($bo['osn']/5);
    $deltaX = [-2,-1,0,0,0,-1,-2,-3-4,-4,-4,-3];
    $deltaY = [0,0,-1,-2,-3,-4,-4,-4,-3,-2,-1,0];
    $x0 = floor($bo['yknp']/1000) + $deltaX[$quadrant];
    $y0 = floor($bo['xknp']/1000) + $deltaY[$quadrant];
    $journal = Yii::$app->session['journal'];
    
    $this->registerCssFile('/web/css/svgstyles.css');
?>
<div class="planshet">
    <div class="container">
        <a href="/reconnaissance/journal" class="btn btn-primary">Возврат к журналу</a>
        <div class="text-center text-primary">
            <h1>Крупномасштабный планшет</h1>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" id="ga" class="svg-triangle"> 
            <defs>
                <g id = "knp">
                        <polyline points = "0,-25 0,-22, -5,-22 5,-22 0,-22 0,-19 
                                        -5,-19 5,-19 0,-19 0,-10 -10,5 10,5 0,-10"/>
                        <polyline points="-1,0 1,0"/>
                </g>
                <g id="target" >
                        <path  d="M2.7037 2.586c5.4902,5.2261 10.362,5.2261 14.6153,0"/>
                        <line  x1="2.3575" y1="6.0049" x2="3.2596" y2= "5.0081" />
                        <line  x1="6.0306" y1="8.468" x2="6.4757" y2= "7.3722" />
                        <line  x1="9.9708" y1="9.5868" x2="10.0438" y2= "8.4438" />
                        <line  x1="14.4535" y1="8.7393" x2="13.7618" y2= "7.626" />
                        <line  x1="18.0484" y1="6.0499" x2="16.9269" y2= "5.1185" />
                        <rect x="0.1272" y="0.0636" width="20" height="10"/>
                </g>
            </defs>
            
        <?php
            // координатная сетка
            for($i = 0;$i <= 1000 ; $i+=200):
        ?>
            <line x1="0" y1="<?=$i?>" x2="999" y2="<?=$i?>"/>
            <text x="10" y="<?= $i-10?>"><?=($y0+5)- $i/200?></text>
            <line x1="<?=$i?>" y1="0" x2="<?=$i?>" y2="999"/>
            <text x="<?= $i+15?>" y="20"><?=$x0 + $i/200?></text>
        <?php endfor;?>  
        <!-- КНП и цели на схему -->    
            <use x="<?=round(($bo['yknp']-$x0*1000)/5);?>" 
                 y="<?=1000 - round(($bo['xknp']-$y0*1000)/5);?>" 
                 xlink:href="#knp" />
            <?php
                foreach ($journal as $n => $tgt):
                    $tgtX = $tgt->coords->x;
                    $tgtY = $tgt->coords->y;
                    $bx = round(($tgtY-$x0*1000)/5);
                    $by = round(1000 -($tgtX - $y0*1000)/5);
            ?>
            <use x="<?=$bx -10?>" y="<?=$by -5?>" xlink:href="#target" 
                 style="transform-origin: <?=round(($bx)/10).'%' ?> <?=round(($by)/10) .'%'?>; 
                 transform: rotate(<?=$bo['osn']*6?>deg)" />
            <text x="<?=$bx+20?>" y="<?=$by+15?>"><?=$tgt->num?></text>
            <?php 
                    endforeach;
            ?>
        </svg>

    </div>
<?php 
    $this->registerJsFile('/web/js/svgmng.js');
?>
</div>
