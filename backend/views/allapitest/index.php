<style>
    .method_block{
        margin-bottom: 20px;
    }
    
    .dropDown-menu .current{
        background-color: #cccccc;
    }

    .showNav div{
        margin-bottom: 10px;
        font-size: 18px;
    }

    .api-title{
        margin-top: 10px;
    }
</style>
<div class="page-container" id="showAllCentent">
    <a style="position: fixed;right: 20px;bottom: 30px;z-index:999;" href="javascript:void(0);" onclick="window.scrollTo(0,0);">点击回到顶部</a>
    <div class="col-xs-6">
        <h2>测试通过接口</h2>

                <?php if(!empty($testList)){
                    foreach ($testList as $itkey=>$item) {
                        if (!empty($item)) {
                            ?>
                            <h4 class="api-title"><?php echo $itkey; ?></h4>
                            <ul class="dropDown-menu menu radius box-shadow">

                            <?php
                            foreach ($item as $key => $it) {
                                if ($it['status'] == true) {
                                    ?>
                                    <li><span class="label label-success radius">成功</span> <?php echo $key.':'.$it['res']['msg']; ?></li>
                                    <?php
                                }
                            }
                        }
                        ?>
                        </ul>
                        <?php
                    }
                } ?>

    </div>
    <div class="col-xs-6">
        <h2>测试未通过接口</h2>
        <?php if(!empty($testList)){
            foreach ($testList as $itkey=>$item){
                if(!empty($item)) {
                    ?>
                    <h4 class="api-title"><?php echo $itkey; ?></h4>
                    <ul class="dropDown-menu menu radius box-shadow">
                    <?php
                    foreach ($item as $key => $it) {
                        if ($it['status'] == false) {
                            ?>
                            <li><span class="label label-danger radius">失败</span> <?php echo $key.':'.$it['res']; ?></li>
                            <?php
                        }
                    }
                }
                    ?>
                </ul>
                <?php
            }
        } ?>


    </div>
</div>
