<?php

/**
 * @package local_qrbasedimage
 * @author Pearl Miglani <miglanipearl@gmail.com> and Shreyans Jain <shreyansja1n@outlook.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


function local_qrbasedimage_before_footer(){

    global $PAGE;
    $page = end(explode('/',$PAGE->url->__toString()));
    if (explode('?',$page)[0]=='attempt.php'){
        global $attemptobj;
        require_once(__DIR__ . '/../../mod/quiz/locallib.php');
        require_once(__DIR__.'/../../config.php');
        include('url.php');
        global $DB;
        $uniqueid = $attemptobj->get_uniqueid();
        $slot = $attemptobj->get_slots()[0];
        $attemptid = $attemptobj->get_attemptid();
        $qid = $DB->get_record_select('question_attempts','questionid',['questionusageid'=>$uniqueid, 'slot'=>$slot],'questionid');
        $questionid = $qid->questionid;
        $url_components = parse_url($page);
        parse_str($url_components['query'], $params);
        require_once('encry.php');
        include('url.php');
        $st = $attemptid.'-'.$slot.'-'.$questionid.'-'.$uniqueid;
        $encryption = openssl_encrypt($st, $ciphering,$encryption_key, $options, $encryption_iv);
        $PAGE->requires->js("/local/qrbasedimage/quizpage.js");
        $PAGE->requires->js_init_call('getDetails',array($encryption,$attemptid,$url));
 

    }

    elseif (explode('?',$page)[0]=='review.php' || explode('?',$page)[0]=='reviewquestion.php'){
        require_once(__DIR__.'/../../config.php');
        include('url.php');    
        global $DB;
        $url_components = parse_url($page);
        parse_str($url_components['query'], $params);
        $attemptid = $params['attempt'];
        $images = $DB->get_records('images', ['attemptid'=>$attemptid],'','*');
        $PAGE->requires->js("/local/qrbasedimage/reviewpage.js");
        foreach($images as $imgdata){
            $PAGE->requires->js_init_call('displayImages',array(json_encode($imgdata,$url)));
        }
    }

    elseif (explode('?',$page)[0]=='report.php'){

        require_once(__DIR__.'/../../config.php');
        include('url.php');    
        global $DB;
        $url_components = parse_url($_SERVER['REQUEST_URI']);
        parse_str($url_components['query'], $params);
        $quesid = $params['qid'];
        $slot = $params['slot'];
        $images = $DB->get_records('images', ['quesid'=>$quesid, 'slot'=>$slot],'','*');
        foreach ($images as $img){
            $PAGE->requires->js_init_call('displayImages',array(json_encode($imgdata,$url)));
        }

    }
    
}
