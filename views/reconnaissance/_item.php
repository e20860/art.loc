<!--Строка  журнала разведки и обслуживания стрельбы -->
<div class="row">
    <div class="col-sm-1"><?=$model->num?></div>
    <div class="col-sm-1"><?=date('h:i',$model->time)?></div>
    <div class="col-sm-2"><?=$model->polars->toTable()?></div>
    <div class="col-sm-1"><?=$model->rumb?></div>
    <div class="col-sm-1"><?=$model->range?></div>
    <div class="col-sm-1"><?=$model->targetName?></div>
    <div class="col-sm-2"><?=$model->coords->toTable()?></div>
    <div class="col-sm-1"><?=$model->accuracy?></div>
    <div class="col-sm-2"><?=$model->notes?></div>
</div>
<hr>
