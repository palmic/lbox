<?php
class HolidayDZONE
{
    //var $name;
    //var $date;
    public $name;
    public $date;

    // Contructor to define the details of each holiday as it is created.
    function holiday($name, $date) {
        $this->name   = $name;   // Official name of holiday
        $this->date   = $date;   // UNIX timestamp of date
    }
}
?>