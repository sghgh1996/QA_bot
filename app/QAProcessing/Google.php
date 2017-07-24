<?php
/**
 * Created by PhpStorm.
 * User: sadjad-pc
 * Date: 7/22/17
 * Time: 5:54 PM
 */

namespace App\QAProcessing;

use DiDom\Document;

class Google extends SearchEngine{

    /**
     * @param $query
     */
    public function getResult($query){
        $search_url = 'https://www.google.com/search?hl=en&tbo=d&site=&source=hp&q='.$query;

        $userAgent = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:54.0) Gecko/20100101 Firefox/54.0";
        $ch = curl_init ("");
        curl_setopt ($ch, CURLOPT_URL, $search_url);
        curl_setopt ($ch, CURLOPT_USERAGENT, $userAgent); // set user agent
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $output = curl_exec ($ch);
        curl_close($ch);

        file_put_contents('google.html',"\xEF\xBB\xBF". $output);

        $doc = new Document();
        $doc->loadHtmlFile('google.html');
//        $number = $doc->find('div#resultStats');
//        $number = $number[0]->text();
//        $number = preg_replace("/\([^)]+\)/","",$number);
//        $number = preg_split("/[\s]+/", $number);
//        $number = $number[1];
//        $number = preg_replace('/[^\d.]/', '', $number);
//        $total_results = intval($number);
        $snippet = $doc->find('div.s div span span');
        
        $snippet = $snippet[0]->text();
        echo $snippet;
//        echo $total_results;
    }
}