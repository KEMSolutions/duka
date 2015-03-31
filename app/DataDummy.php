<?php namespace App;

Class DataDummy {

    private $id;
    private $name;
    private $price;
    private $slug;
    private $thumbnail;
    private $thumbnail_lg;
    private $link;

    public function __construct($id, $name, $price, $thumbnail, $thumbnail_lg){
        $this->id = $id;
        $this->name= $name;
        $this->price = $price;
        $this->thumbnail = $thumbnail;
        $this->thumbnail_lg = $thumbnail_lg;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function getThumbnail_lg()
    {
        return $this->thumbnail_lg;
    }

}