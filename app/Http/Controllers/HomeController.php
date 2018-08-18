<?php

namespace App\Http\Controllers;

use App\Fault;
use App\FaultCode;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Lego\Lego;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = '故障码详情列表';

        $filter = Lego::filter(Fault::with('code'));
        $filter->addSelect('code.cate', '类型')->values([FaultCode::CATE_ABS, FaultCode::CATE_EBS]);
        $filter->addText('code.fault_code', '故障码');
        $filter->addText('dtc_no', 'dtc_no');
        $filter->addText('fault_lamp', '故障灯');
        $filter->addText('describe', '故障描述');
        $filter->addText('operation', '操作建议');
        $filter->addSelect('status', '状态')->values(['启用', '停用']);

        $grid = Lego::grid($filter);

        $grid->addLeftTopButton('新增故障详情', action('HomeController@anyFault'));
        $grid->addLeftTopButton('故障类型列表', action('HomeController@getFaultCodeList'));
        $grid->add('id', '编辑')->cell(function ($_, Fault $fault) {
            return link_to(action('HomeController@anyFault', $fault->id), '编辑');
        });
        $grid->add('code.cate', '类型');
        $grid->add('code.fault_code', '故障码');
        $grid->add('dtc_no', 'dtc_no');
        $grid->add('fault_lamp', '故障灯');
        $grid->add('describe', '故障描述');
        $grid->add('operation', '操作建议');
        $grid->add('status', '状态');
        $grid->paginate(15)->orderBy('id');

        return $grid->view('lego.default_lego_list', compact('title', 'grid'));
    }

    public function anyFault($id = null)
    {
        $title = '编辑故障详情';
        $edit = Lego::form(Fault::find($id) ?? new Fault());
//        $edit->addAutoComplete('fault_id', '所属故障码')
//            ->match(function ($keyword) {
//            return FaultCode::where('fault_code', 'like', "%{$keyword}%")
//                ->take(10)
//                ->get()
//                ->map(function (FaultCode $code) {
//                    return ['id' => $code->id, 'name' => "{$code->cate}/{$code->fault_code}"];
//                })
//                ->pluck('name', 'id')
//                ->toArray();
//        });
//        $edit->addAutoComplete('code.fault_code', '所属故障码');
        $edit->addSelect('fault_id', '所属故障码')->options(FaultCode::getAllCodes())->required();
        $edit->addText('dtc_no', 'dtc_no');
        $edit->addText('fault_lamp', '故障灯');
        $edit->addTextarea('describe', '故障描述');
        $edit->addTextarea('operation', '操作建议');
        $edit->addSelect('status', '状态')->values(['启用', '停用'])->required();
        $edit->success(action('HomeController@index'));

        return $edit->view('lego.default_lego_item', compact('title', 'edit'));
    }

    public function getFaultCodeList()
    {
        $title = '故障码列表';

        $filter = Lego::filter(new FaultCode());
        $filter->addSelect('cate', '类型')->values([FaultCode::CATE_ABS, FaultCode::CATE_EBS]);
        $filter->addText('fault_code', '故障码');
        $filter->addSelect('status', '状态')->values(['启用', '停用']);
        $grid = Lego::grid($filter);

        $grid->addLeftTopButton('新增故障码', action('HomeController@anyFaultCode'));
        $grid->addLeftTopButton('故障详情列表', action('HomeController@index'));
        $grid->add('id', '编辑')->cell(function ($_, FaultCode $fault) {
            return link_to(action('HomeController@anyFaultCode', $fault->id), '编辑');
        });
        $grid->add('cate', '类型');
        $grid->add('fault_code', '故障码');
        $grid->add('status', '状态');
        $grid->paginate(15)->orderBy('id');

        return $grid->view('lego.default_lego_list', compact('title', 'grid'));
    }

    public function anyFaultCode($id = null)
    {
        $title = '编辑故障码';
        $edit = Lego::form(FaultCode::find($id) ?? new FaultCode());
        $edit->addSelect('cate', '类型')->values([FaultCode::CATE_ABS, FaultCode::CATE_EBS]);
        $edit->addText('fault_code', '故障码');
        $edit->addSelect('status', '状态')->values(['启用', '停用'])->required();
        $edit->success(action('HomeController@getFaultCodeList'));

        return $edit->view('lego.default_lego_item', compact('title', 'edit'));
    }
}
