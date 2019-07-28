<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('考不出来腿打断')
            ->description(' ')
            ->row("<div style=\"font-size: 50px;color: #636b6f;font-family: 'Raleway', sans-serif;font-weight: 100;
display: block;text-align: center;margin: 20px 0 10px 0px;\"><i class='fa fa-pencil'></i> <i class='fa fa-book'></i></div>")
            ->row(function (Row $row) {

                $row->column(3, function (Column $column) {
                    $column->append(view('logic'));
                });

                $row->column(3, function (Column $column) {
                    $column->append(view('math'));
                });

                $row->column(3, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });

                $row->column(3, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
    }
}
