<?php
    $draw = $request->get('draw');
    $start = $request->get("start");
    $rowperpage = $request->get("length");
    if(!is_numeric($rowperpage))
    {
        $rowperpage=Config::get('constants.ALL_RECORDS');
    }
    $columnIndex_arr = $request->get('order');
    $columnName_arr = $request->get('columns');
    $order_arr = $request->get('order');
    $search_arr = $request->get('search');

    $columnIndex = $columnIndex_arr[0]['column'];
    $columnName = $columnName_arr[$columnIndex]['data'];
    $columnSortOrder = $order_arr[0]['dir'];
    $searchValue = $search_arr['value'];
?>