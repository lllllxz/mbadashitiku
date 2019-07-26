<?php
/**
 * Created by PhpStorm.
 * User: Liuxuezhi
 * Date: 2019/7/25
 * Time: 16:18
 */

namespace App\Admin\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class DownloadController extends Controller
{
    public function logic(Request $request)
    {
        $all = $request->all();
        $datas = Question::logic()->where('knowName',$all['know_name'])->get();
        $datas = $datas->map(function ($item){
            $item['optionList'] = json_decode($item['optionList']);

            return $item;
        });

//        $this->export($datas);

        $datas = [
            'data' => $datas,
            'title' => $all['know_name']
        ];


//dd($datas);
        return view('choose',compact('datas'));
    }


    public function export($data)
    {
        $word = new PhpWord();
        $section = $word->addSection();
        $word->addFontStyle('rStyle', array('bold'=>true,'color'=>'87CEEB','size'=>35));
        $word->addParagraphStyle('pStyle', array('align'=>'center','spacing'=>120));
//dd($data->toArray());
        foreach($data as $k=>$v){
            $fontStyle = array('color'=>'000000', 'size'=>15,'align'=>'center');
            $word->addFontStyle('myOwnStyle', $fontStyle);
            $section->addText('【单项选择】'.++$k, 'myOwnStyle');
            $section->addText($v['content'], 'myOwnStyle');
            foreach($v['optionList'] as $item){
                $section->addText($item->sign.'.'.$item->content, 'myOwnStyle');
            }
            $section->addText('解析: '.$v['analysis'], 'myOwnStyle');
            $section->addTextBreak();
            $section->addTextBreak();
        }
        $save = IOFactory::createWriter($word,'Word2007');
        $save->save('test.docx');

        header("Content-type:text/html;charset=utf-8");
         $filename='test.docx';
         $file_path = iconv("utf-8","gbk",$filename);
         $fil_name=$filename;
        if (!file_exists($file_path)){
             echo  "没有该文件";
             return;
         }else{
             ob_clean();
             ob_start();
          $fp = fopen($file_path,"r");
            $file_size = filesize($file_path);
            Header("Content-type:application/octet-stream");
            Header("Accept-Ranges:bytes");
            Header("Accept-Length:".$file_size);
            Header("Content-Disposition:attchment; filename=".$fil_name);
          $buffer=1024;
            $file_count=0;
             while (!feof($fp) && $file_count<$file_size ){
                 $file_con=fread($fp,$buffer);
                 $file_count +=$buffer;
              echo $file_con;
            }
            fclose($fp);
            ob_end_flush();
       }
    }
}