<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class OcrController extends ResponseController
{
    protected $app_id;
    protected $api_key;
    protected $secret_key;

    /**
     * OcrController constructor.
     */
    public function __construct()
    {
        $this->app_id = config('baiduocr.APP_ID');
        $this->api_key = config('baiduocr.API_KEY');
        $this->secret_key = config('baiduocr.SECRET_KEY');
    }

    public function change(Request $request)
    {
        $image = $request->file('image');

        $client = new \AipOcr($this->app_id, $this->api_key, $this->secret_key);

        $image = file_get_contents($image);
        $options = [
            "detect_direction" => "true",
            "probability" => "true"
        ];

        $word = $client->basicAccurate($image, $options);

        $word = collect($word['words_result'])->map(function ($item){
            return $item;
        });

        $words = $word->pluck('words')->toArray();
        $word = implode("\n",$words);
        return $this->responseSuccess($word);
    }


    public function export(Request $request)
    {
        $request->validate(
            [
                'image' => "required"
            ],
            [
                "required" => '请选择文件'
            ]
        );
        $image = $request->file('image');

        $client = new \AipOcr($this->app_id, $this->api_key, $this->secret_key);

        $image = file_get_contents($image);
        $options = [
            "detect_direction" => "true",
            "probability" => "true"
        ];

        $word = $client->basicAccurate($image, $options);

        $word = collect($word['words_result'])->map(function ($item){
            return $item;
        });

        $words = $word->pluck('words')->toArray();

        $export = new PhpWord();
        $section = $export->addSection();

        $export->addFontStyle('body', ['color'=>'000000', 'size'=>10]);
        $export->addParagraphStyle('pStyle', array('align'=>'left','spacing'=>80));

        foreach ($words as $item){
            $section->addText($item, 'body', 'pStyle');
        }

        $time=date('YmdHis',time());
        $file_path = public_path("word/$time.docx");
        $save = IOFactory::createWriter($export,'Word2007');
        $save->save($file_path);

        return response()->download($file_path,$time.'.docx');
    }
}