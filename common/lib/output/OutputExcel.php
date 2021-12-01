<?php
namespace common\lib\output;

/**
 * 导出excel
 * @package common\lib\output
 */
class OutputExcel
{
    /**
     * 导出excel
     */
    public function run($name,$head,$exList){
        $fileName = $name;
        // 输出Excel文件头，可把user.csv换成你要的文件名
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '.csv"');
        header('Cache-Control: max-age=0');

        // 打开PHP文件句柄，php://output 表示直接输出到浏览器
        $fp = fopen('php://output', 'a');

        // 输出Excel列名信息
        foreach ($head as $i => $v) {
            // CSV的Excel支持GBK编码，一定要转换，否则乱码
            $head[$i] = iconv('utf-8', 'gbk', $v);
        }

        // 将数据通过fputcsv写到文件句柄
        fputcsv($fp, $head);

        // 计数器
        $cnt = 0;
        // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;
        $data = $exList;
        // 逐行取出数据，不浪费内存
        $count = count($data);
        for ($t = 0; $t < $count; $t++) {
            $cnt++;
            if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
                ob_flush();
                flush();
                $cnt = 0;
            }
            $row = $data[$t];
            foreach ($row as $i => $v) {
                if (!empty($v) && !is_numeric($v)) {
                    $row[$i] =  str_replace(',','，',iconv('utf-8', 'gbk//IGNORE', $v));
                } else {
                    $row[$i] = $v;
                }

            }
            fputcsv($fp, $row);
        }
        exit();
    }

}