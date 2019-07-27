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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Overtrue\Pinyin\Pinyin;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;

class LogicController extends Controller
{
    public function logic(Request $request)
    {
        $all = $request->all();
        $datas = Question::logic()->where('knowName',$all['know_name'])->get();
        $datas = $datas->map(function ($item){
            $item['optionList'] = json_decode($item['optionList']);

            return $item;
        });

        $datas = [
            'data' => $datas,
            'title' => $all['know_name']
        ];

        return view('choose',compact('datas'));
    }


    public function export_logic(Request $request)
    {
        $know_name = $request->input('k');
        $datas = Question::logic()->where('knowName',$know_name)->get();
        $datas = $datas->map(function ($item){
            $item['optionList'] = json_decode($item['optionList']);

            return $item;
        })->toArray();

        $word = new PhpWord();
        $section = $word->addSection();

        $word->addFontStyle('body', ['color'=>'000000', 'size'=>10]);
        $word->addParagraphStyle('pStyle', array('align'=>'left','spacing'=>80));

        $section->addText($know_name, ['bold'=>true,'color'=>'000000','size'=>15], ['align'=>'center','spacing'=>150]);
        foreach($datas as $k=>$v){
            $section->addText('【单项选择】'.++$k, ['color'=>'000000', 'size'=>12],'pStyle');
            $section->addText($v['content'], 'body','pStyle');
            $section->addTextBreak();
            foreach($v['optionList'] as $item){
                $section->addText($item->sign.'.'.$item->content, 'body','pStyle');
            }
            $section->addTextBreak();
            $section->addText('解析: '.$v['analysis'], 'body','pStyle');
            $section->addTextBreak(3);
        }

        $pinyin = new Pinyin();
        $time=date('YmdHis',time());
        $file_name = $time.'-'.$pinyin->abbr($know_name);
        $file_path = public_path("word/$file_name.docx");
        $save = IOFactory::createWriter($word,'Word2007');
        $save->save($file_path);

        return response()->download($file_path,$pinyin->abbr($know_name).'.docx');
    }
}