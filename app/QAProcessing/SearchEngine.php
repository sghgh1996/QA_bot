<?php
/**
 * Created by PhpStorm.
 * User: sadjad-pc
 * Date: 7/22/17
 * Time: 5:45 PM
 */

namespace App\QAProcessing;


abstract class SearchEngine{
    abstract protected function getResult($query);
}