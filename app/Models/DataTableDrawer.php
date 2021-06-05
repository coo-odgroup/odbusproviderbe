<?php
namespace App\Models;
class DataTableDrawer
{
    protected String $draw;
    protected String $start;
    protected String $rowperpage;
    protected String $columnIndex_arr;
    protected String $columnName_arr;
    protected String $order_arr;
    protected String $search_arr;
    protected String $columnIndex;
    protected String $columnName;
    protected String $columnSortOrder;
    protected String $searchValue;
    public function __construct(String $draw,String $start,String $rowperpage,String $columnIndex_arr,String $columnName_arr,String $order_arr,String $search_arr,String $columnIndex,String $columnName,String $columnSortOrder,String $searchValue)
    {
        $this->draw=$draw;
        $this->start=$start;
        $this->rowperpage=$rowperpage;
        $this->columnIndex_arr=$columnIndex_arr;
        $this->columnName_arr=$columnName_arr;
        $this->order_arr=$order_arr;
        $this->search_arr=$search_arr;
        $this->columnIndex=$columnIndex;
        $this->columnName=$columnName;
        $this->columnSortOrder=$columnSortOrder;
        $this->searchValue=$searchValue;
    }
    public function getDraw()
    {
        return $this->draw;
    }
    public function getStart()
    {
        return $this->start;
    }
    public function getRowPerPage()
    {
        return $this->rowperpage;
    }
    public function getColumnIndexArray()
    {
        return $this->columnIndex_arr;
    }
    public function getColumnNameArray()
    {
        return $this->columnName_arr;
    }
    public function getOrderByArray()
    {
        return $this->order_arr;
    }
    public function getSearchArray()
    {
        return $this->search_arr;
    }
    public function getColumnIndex()
    {
        return $this->columnIndex;
    }
    public function getColumnName()
    {
        return $this->columnName;
    }
    public function getColumnSortOrder()
    {
        return $this->columnSortOrder;
    }
    public function getSearchValue()
    {
        return $this->searchValue;
    }


    public function setDraw(String $draw)
    {
        $this->draw=$draw;
    }
    public function setStart()
    {
        return $this->start;
    }
    public function setRowPerPage()
    {
        return $this->rowperpage;
    }
    public function setColumnIndexArray()
    {
        return $this->columnIndex_arr;
    }
    public function setColumnNameArray()
    {
        return $this->columnName_arr;
    }
    public function setOrderByArray()
    {
        return $this->order_arr;
    }
    public function setSearchArray()
    {
        return $this->search_arr;
    }
    public function setColumnIndex()
    {
        return $this->columnIndex;
    }
    public function setColumnName()
    {
        return $this->columnName;
    }
    public function setColumnSortOrder()
    {
        return $this->columnSortOrder;
    }
    public function setSearchValue()
    {
        return $this->searchValue;
    }
    


}

