<?php


namespace App\Admin\Controllers\Base;


use App\Common\Helper\ConstantHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConstantController extends Controller
{
    public function getOption(Request $request)
    {
        $class = $request->get('constant_class');
        if (!$class) {
            return [];
        }
        $class = substr($class, 0, strlen($class) - 1);
        try {
            $objClass = new \ReflectionClass($class);
            $arrConst = $objClass->getConstants();
            $options = [];
            foreach ($arrConst as $key => $val) {
                $options[] = (Object)[
                    'id'   => $val,
                    'text' => ConstantHelper::message(new $class, $val)
                ];
            }
            return $options;
        } catch (\ReflectionException $e) {
            return [];
        }
    }
}
