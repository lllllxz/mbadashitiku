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

        $datas = [
            'data' => $datas,
            'title' => $all['know_name']
        ];

        return view('choose',compact('datas'));
    }


    public function export_logic($data)
    {
        $word = new PhpWord();
        $section = $word->addSection();
        $word->addFontStyle('rStyle', array('bold'=>true,'color'=>'87CEEB','size'=>35));
        $word->addParagraphStyle('pStyle', array('align'=>'center','spacing'=>120));

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
        $save->save(public_path('word/test.docx'));

        return response()->download(public_path('word/test.docx'),'text.docx');
    }
}