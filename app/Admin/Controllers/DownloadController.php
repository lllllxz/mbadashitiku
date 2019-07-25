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

class DownloadController extends Controller
{
    public function logic(Request $request)
    {
        $know_name = $request->all();
        $data = Question::logic()->where('knowName',$know_name['know_name'])->get();

        return view('logic',$data->toArray());
    }
}