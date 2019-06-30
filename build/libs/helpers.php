<?php

/**
 * Creates a url
 *
 * @todo add switch for rewritten / normal urls
 * @return string the url
 */
function url($href, $echo = false)
{
    $url = HTTP_ROOT . trim($href, "/");
    if($echo) {
        echo($url);
    } else {
        return $url; 
    }
}

/**
 * Creates a link (<a>) with description
 *
 * @todo should be makeLink according to convention ..
 * @return string the html link
 */
function makelink($href, $description, $echo = false, $ajax = false, $method = "get")
{
    $url = url($href);
    $a = (string)"";
    if($ajax) {
        $a = makeAjaxRequest($href, $method);
    }
    $link = "<a href=\"{$url}\" {$a}>$description</a>";
    if($echo) {
        echo($link);
    } else {
        return $link;
    }
}

function makePrompt($href, $description, $method = "get")
{
    $url = url($href);
    $aReq = new PromptAjaxRequestAction($url, $method);
    $onClick = new OnClickEvent($aReq);
    $link = "<a href=\"{$url}\" {$onClick}>$description</a>";
    echo $link;
}

function makeAjaxRequest($href, $method = "get", $echo = false)
{
    $aReq = new AjaxRequestAction(url($href), $method);
    $onClick = new OnClickEvent($aReq);
    if($echo) {
        echo $onClick;
    } else {
        return $onClick;
    }
}

/**
 * Formats a word to either plural or singular based on $arg
 *
 * @todo very ugly ..
 * @return the formatted word
 */
function formatPlural($arg, array $display)
{
    if((float)$arg == 1) return "{$arg} {$display[0]}";
    else return "{$arg} {$display[1]}";
}

/**
 * Echoes a variable and converts special characters to html entities
 * Uses UTF-8 charset
 *
 * @return string the variable
 */
function e($value)
{
    echo(htmlspecialchars($value, ENT_QUOTES, "UTF-8"));
}

function t($time, $format = null)
{
    if(!isset($format)) $format = "j.n.o G:i \U\h\\r";
    echo date($format, $time);
}

function icon($name, $title = "", $echo = false)
{
    $src = HTTP_ROOT . "app/public/img/icons/{$name}.png";
    $icon = "<img src=\"{$src}\" title=\"{$title}\"></img>";
    if($echo) {
        echo($icon);
    } else {
        return $icon;
    }
}

?>
