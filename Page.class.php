<?php
/**
 * Date: 2016/5/28 0028
 * Time: 下午 11:43
 */

namespace Tools;
class Page
{
    private $total; //数据表中总记录数
    private $pageSize; //每页显示行数
    private $limit; //sql分页参数
    private $uri;
    private $pageNum; //页数
    private $config = array('header' => "个记录", "prev" => "上一页", "next" => "下一页", "first" => "首 页", "last" => "尾 页");
    private $listNum = 8;//   【 12  3  4567  】  【 1 2345 】  【 1234 5 6789】

    /*
     * $total
     * $listRows
     */
    public function __construct($total, $pagesize = 10, $pa = "")
    {
        $this->total = $total;
        $this->pageSize = $pagesize;
        $this->uri = $this->getUri($pa);
        $this->page = !empty($_GET["page"]) ? $_GET["page"] : 1;
        $this->pageNum = ceil($this->total / $this->pageSize);
        $this->limit = $this->setLimit();
    }

    private function setLimit()
    {
        return "Limit " . ($this->page - 1) * $this->pageSize . ", {$this->pageSize}";
    }

    private function getUri($pa)
    {
        $url = $_SERVER["REQUEST_URI"] . (strpos($_SERVER["REQUEST_URI"], '?') ? '' : "?") . $pa;

        //$url = 'http://username:password@hao123.com:8080/news/hot?page=1&size=10#anchor';
        //parse_url($url)返回格式如下：
        //Array ( [scheme] => http [host] => hao123.com [port] => 8080 [user] => username [pass] => password [path] => /news/hot [query] => page=1&size=10 [fragment] => anchor )

        $parse = parse_url($url);


        if (isset($parse["query"])) {
            parse_str($parse['query'], $params);
            unset($params["page"]);
            $url = $parse['path'] . '?' . http_build_query($params);

        }

        return $url;
    }

    function __get($args)
    {
        if ($args == "limit")
            return $this->limit;
        else
            return null;
    }

    //当前页第一条记录属于第几条记录
    private function start()
    {
        if ($this->total == 0)
            return 0;
        else
            return ($this->page - 1) * $this->pageSize + 1;
    }

    //当前页最后一条记录属于第几条记录
    private function end()
    {
        return min($this->page * $this->pageSize, $this->total);
    }

    //首页
    private function first()
    {
        $html = "";
        if ($this->page == 1)//刚好在首页，不显示首页链接
            $html .= '';
        else //非首页时生成一个【首页】链接
            $html .= "&nbsp;&nbsp;<a href='{$this->uri}&page=1'>{$this->config["first"]}</a>&nbsp;&nbsp;";

        return $html;
    }

    //上一页
    private function prev()
    {
        $html = "";
        if ($this->page == 1)
            $html .= '';
        else
            $html .= "&nbsp;&nbsp;<a href='{$this->uri}&page=" . ($this->page - 1) . "'>{$this->config["prev"]}</a>&nbsp;&nbsp;";

        return $html;
    }

    //页面跳转索引条
    private function pageList()
    {
        $linkPage = "";

        $inum = floor($this->listNum / 2);

        for ($i = $inum; $i >= 1; $i--) {
            $page = $this->page - $i;

            if ($page >= 1) {
                $linkPage .= "&nbsp;<a href='{$this->uri}&page={$page}'>{$page}</a>&nbsp;";

            } else
                continue;

        }

        $linkPage .= "&nbsp;{$this->page}&nbsp;";


        for ($i = 1; $i <= $inum; $i++) {
            $page = $this->page + $i;
            if ($page <= $this->pageNum)
                $linkPage .= "&nbsp;<a href='{$this->uri}&page={$page}'>{$page}</a>&nbsp;";
            else
                break;
        }

        return $linkPage;
    }

    //下一页
    private function next()
    {
        $html = "";
        if ($this->page == $this->pageNum)//处于最后一页，不显示下一页链接
            $html .= '';
        else
            $html .= "&nbsp;&nbsp;<a href='{$this->uri}&page=" . ($this->page + 1) . "'>{$this->config["next"]}</a>&nbsp;&nbsp;";

        return $html;
    }

    //末页
    private function last()
    {
        $html = "";
        if ($this->page == $this->pageNum)
            $html .= '';
        else
            $html .= "&nbsp;&nbsp;<a href='{$this->uri}&page=" . ($this->pageNum) . "'>{$this->config["last"]}</a>&nbsp;&nbsp;";

        return $html;
    }

    //跳转到指定页面
    private function goPage()
    {
        return '&nbsp;&nbsp;<input type="text" onkeydown="javascript:if(event.keyCode==13){var page=(this.value>' . $this->pageNum . ')?' . $this->pageNum . ':this.value;location=\'' . $this->uri . '&page=\'+page+\'\'}" value="' . $this->page . '" style="width:25px"><input type="button" value="GO" onclick="javascript:var page=(this.previousSibling.value>' . $this->pageNum . ')?' . $this->pageNum . ':this.previousSibling.value;location=\'' . $this->uri . '&page=\'+page+\'\'">&nbsp;&nbsp;';
    }

    function fpage($display = array(0, 1, 2, 3, 4, 5, 6, 7, 8))
    {
        $html[0] = "&nbsp;&nbsp;共有<b>{$this->total}</b>{$this->config["header"]}&nbsp;&nbsp;";
        $html[1] = "&nbsp;&nbsp;每页显示<b>" . ($this->end() - $this->start() + 1) . "</b>条，本页<b>{$this->start()}-{$this->end()}</b>条&nbsp;&nbsp;";
        $html[2] = "&nbsp;&nbsp;<b>{$this->page}/{$this->pageNum}</b>页&nbsp;&nbsp;";

        $html[3] = $this->first();
        $html[4] = $this->prev();
        $html[5] = $this->pageList();
        $html[6] = $this->next();
        $html[7] = $this->last();
        $html[8] = $this->goPage();
        $fpage = '';
        foreach ($display as $index) {
            $fpage .= $html[$index];
        }

        return $fpage;

    }


}


/*  使用方法：
$model = D('Goods');//创建商品模型对象关联到 sw_goods表
$pagesize =  10 ;
$total = $model->count();
$page = new Page($total,$pagesize);

$infos = $model->query('select * from sw_goods ORDER BY goods_id desc '.$page->limit);

$pageinfo = $page->fpage();

$this->assign('infos', $infos);
$this->assign('pageinfo', $pageinfo);
*/