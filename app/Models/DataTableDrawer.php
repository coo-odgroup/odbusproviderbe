<?php

namespace App\Models;

class DataTableDrawer
{
    protected $draw;
    protected $start;
    protected $rowperpage;
    protected $columnIndex_arr;
    protected $columnName_arr;
    protected $order_arr;
    protected $search_arr;
    protected $columnIndex;
    protected $columnName;
    protected $columnSortOrder;
    protected $searchValue;

    public function __construct(
        $draw,
        $start,
        $rowperpage,
        $columnIndex_arr,
        $columnName_arr,
        $order_arr,
        $search_arr,
        $columnIndex,
        $columnName,
        $columnSortOrder,
        $searchValue
    ) {
        $this->draw = $draw;
        $this->start = $start;
        $this->rowperpage = $rowperpage;
        $this->columnIndex_arr = $columnIndex_arr;
        $this->columnName_arr = $columnName_arr;
        $this->order_arr = $order_arr;
        $this->search_arr = $search_arr;
        $this->columnIndex = $columnIndex;
        $this->columnName = $columnName;
        $this->columnSortOrder = $columnSortOrder;
        $this->searchValue = $searchValue;
    }

    // Getters
    public function getDraw() { return $this->draw; }
    public function getStart() { return $this->start; }
    public function getRowPerPage() { return $this->rowperpage; }
    public function getColumnIndexArray() { return $this->columnIndex_arr; }
    public function getColumnNameArray() { return $this->columnName_arr; }
    public function getOrderByArray() { return $this->order_arr; }
    public function getSearchArray() { return $this->search_arr; }
    public function getColumnIndex() { return $this->columnIndex; }
    public function getColumnName() { return $this->columnName; }
    public function getColumnSortOrder() { return $this->columnSortOrder; }
    public function getSearchValue() { return $this->searchValue; }

    // Setters
    public function setDraw($draw) { $this->draw = $draw; }
    public function setStart($start) { $this->start = $start; }
    public function setRowPerPage($rowperpage) { $this->rowperpage = $rowperpage; }
    public function setColumnIndexArray($columnIndex_arr) { $this->columnIndex_arr = $columnIndex_arr; }
    public function setColumnNameArray($columnName_arr) { $this->columnName_arr = $columnName_arr; }
    public function setOrderByArray($order_arr) { $this->order_arr = $order_arr; }
    public function setSearchArray($search_arr) { $this->search_arr = $search_arr; }
    public function setColumnIndex($columnIndex) { $this->columnIndex = $columnIndex; }
    public function setColumnName($columnName) { $this->columnName = $columnName; }
    public function setColumnSortOrder($columnSortOrder) { $this->columnSortOrder = $columnSortOrder; }
    public function setSearchValue($searchValue) { $this->searchValue = $searchValue; }
}
