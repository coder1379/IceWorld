<?php
namespace common\lib;


class Page
{


    /**
     * @param $url
     * @return string
     */
    public $outMaxNum;
    public $url;
    public $total;
    public $listRows;


    public function createPageString($url,$totleCount=0,$pageSize=10,$currentPage=1,$otherParams=[])
    {
        $page     = $currentPage;
        $this->outMaxNum =10;
        $allPage  = ceil($totleCount/$pageSize);
        $this->total=$totleCount;
        $this->listRows=$pageSize;
        $this->url=$url.'?page_size='.$pageSize.'&'.implode('&',$otherParams).'&current_page=';
        if($allPage<=1){
            return '';
        }
        $codes = [];
        $pageNumberList=[];

        //分页分类处理-总页数小于可显示最大页数无需处理直接生成
        if($allPage<=$this->outMaxNum){
            for ($t=1;$t<=$allPage;$t++){
                $pageNumberList[]=$t;
            }
        }else{
            //分页分类处理-总页数大于可显示最大页数需处理
            $threshold2=floor($this->outMaxNum/2);
            $threshold3=floor($this->outMaxNum/3);
            $maxPageNumber=$this->outMaxNum-$threshold3;

            //当前页数靠前处理流程
            if($page<=$threshold2){
                for($t=1;$t<=$maxPageNumber;$t++){
                    $pageNumberList[]=$t;
                }
                $pageNumberList[]=-1;
                $lastMaxPageThreshold=$allPage-$threshold3+1;
                for($t=$lastMaxPageThreshold;$t<=$allPage;$t++){
                    $pageNumberList[]=$t;
                }

                //当前页数靠后处理流程
            }else if(($allPage-$page+1)<=$threshold2){

                for($t=1;$t<=$threshold3;$t++){
                    $pageNumberList[]=$t;
                }
                $pageNumberList[]=-1;
                $lastMaxPageThreshold=$allPage-$maxPageNumber+1;
                for($t=$lastMaxPageThreshold;$t<=$allPage;$t++){
                    $pageNumberList[]=$t;
                }

                //当前页数在中部处理流程
            }else{
                $firstAndLastNum=floor($this->outMaxNum/4);
                $pageFragment=$this->outMaxNum%4==0?($firstAndLastNum-1):$firstAndLastNum;

                for($t=1;$t<=$firstAndLastNum;$t++){
                    $pageNumberList[]=$t;
                }
                $pageNumberList[]=-1;

                for($t=$page-$pageFragment;$t<$page;$t++){
                    $pageNumberList[]=$t;
                }
                $pageNumberList[]=$page;
                for($t=1;$t<=$pageFragment;$t++){
                    $pageNumberList[]=$t+$page;
                }
                $pageNumberList[]=-1;
                for($t=$allPage-$firstAndLastNum;$t<$allPage;$t++){
                    $pageNumberList[]=$t;
                }

            }
        }

        foreach ($pageNumberList as $p)
        {
            $active = $p == $page ? 'active' : '';
            if($p>0){
                if($p==$page){
                    $codes[] = '<span class="layui-laypage-curr"><em class="layui-laypage-em" style="background-color:#1E9FFF;"></em><em>'.$p.'</em></span>';
                }else{
                    $codes[] = '<a href="'.$this->url.$p.'">'.$p.'</a>';
                }

            }else{
                $codes[] = '<span class="layui-laypage-spr">…</span>';
            }

        }

        $pageCode  = '<div class="layui-box layui-laypage layui-laypage-molv">';
        $pageCode .= $this->fmtTexts();
        $pageCode .= '<a href="'.$this->url.'1" class="layui-laypage-first">首页</a>';
        $pageCode .= '<a href="'.$this->url.($page>1?$page-1:1).'" class="layui-laypage-prev" ><em>上一页</em></a>';
        $pageCode .= implode('', $codes);
        $pageCode .= '<a href="'.$this->url.($page<$allPage?$page+1:$allPage).'" class="layui-laypage-next" ><em>下一页</em></a>';
        $pageCode .= '<a href="'.$this->url.$allPage.'" class="layui-laypage-last" title="尾页">尾页</a>';
        $pageCode .= '</div>';

        return $pageCode;
    }

    public function fmtTexts()
    {
        return '<span class="layui-laypage-count">共 '.$this->total.' 条 每页显示 '.$this->listRows.'条</span>';
    }

}