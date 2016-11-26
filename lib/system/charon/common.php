<?php
/*
 Description:    commonly used web routines
 
 ****************History************************************
 Date:         	9.14.2009
 Author:       	Allen Halsted
 Mod:          	Creation
 ***********************************************************
 */

function neutralize($strNeutr)
{ //meant for richtext input only
    $strNeutr=preg_replace(array
        (
        // Remove invisible content
        '@<head[^>]*?>.*?</head>@siu',
        '@<style[^>]*?>.*?</style>@siu',
        '@<script[^>]*?.*?</script>@siu',
        '@<object[^>]*?.*?</object>@siu',
        '@<embed[^>]*?.*?</embed>@siu',
        '@<applet[^>]*?.*?</applet>@siu',
        '@<noframes[^>]*?.*?</noframes>@siu',
        '@<noscript[^>]*?.*?</noscript>@siu',
        '@<noembed[^>]*?.*?</noembed>@siu',
        '@<iframe[^>]*?.*?</iframe>@siu',
        '@<a[^>]*?.*?</a>@siu',
        '@<?xml[^>]*?>@siu',
        // Add line breaks before and after blocks
        '@</?((address)|(blockquote)|(center)|(del))@iu',
        '@</?((table)|(tbody)|(th)|(tr)|(td)|(caption))@iu',
        '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
        '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
        '@</?((frameset)|(frame)|(iframe))@iu',
        ), array
        (
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        //            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
        ), $strNeutr);

    //neutralizes script, meta tags
    $risky_tag_pat=array
        (
        "#<(/?script)#i",
        "#<(meta)#i"
        );

    $safe_tag_rep="&lt;\\1";
    $strNeutr=preg_replace($risky_tag_pat, $safe_tag_rep, $strNeutr);

    //neutralizes onclick, onmouseup etc. attributes
    $risky_attr_pat=array("#(<.*)[^a-zA-Z0-9]on.+(=.*>)#iU");
    $safe_attr_rep="\\1 \\2";
    $strNeutr=preg_replace($risky_attr_pat, $safe_attr_rep, $strNeutr);

    $strNeutr=str_replace("<o:p></o:p>", "", $strNeutr);
    $strNeutr=str_replace("'", "&#39;", $strNeutr);

    return ($strNeutr);
}
;

function strip_html_tags($text)
{
    $text=preg_replace(array
        (
        // Remove invisible content
        '@<head[^>]*?>.*?</head>@siu',
        '@<style[^>]*?>.*?</style>@siu',
        '@<script[^>]*?.*?</script>@siu',
        '@<object[^>]*?.*?</object>@siu',
        '@<embed[^>]*?.*?</embed>@siu',
        '@<applet[^>]*?.*?</applet>@siu',
        '@<noframes[^>]*?.*?</noframes>@siu',
        '@<noscript[^>]*?.*?</noscript>@siu',
        '@<noembed[^>]*?.*?</noembed>@siu',
        '@<iframe[^>]*?.*?</iframe>@siu',
        '@<a[^>]*?.*?</a>@siu',
        // Add line breaks before and after blocks
        '@</?((address)|(blockquote)|(center)|(del))@iu',
        '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
        '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
        '@</?((table)|(th)|(tr)|(td)|(caption))@iu',
        '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
        '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
        '@</?((frameset)|(frame)|(iframe))@iu',
        ), array
        (
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        "\n\$0",
        "\n\$0",
        "\n\$0",
        "\n\$0",
        "\n\$0",
        "\n\$0",
        "\n\$0",
        "\n\$0",
        ), $text);

    return strip_tags($text);
}

function strip_img_tags($text)
{
    $text=preg_replace(array
        (
        // Remove invisible content
        '@<img[^>]*?>.*?</img>@siu',
        '@<img[^>]*?>@siu',
        ), array
        (
        ' ',
        ' ',
        ), $text);

    return $text;
}

//escape quotes from a string
function removequote($strNeutr)
{
    $strNeutr=str_replace("'", "&#39;", $strNeutr);
    return ($strNeutr);
}
;
//store quotes in a string
function restorequote($strNeutr)
{
    $strNeutr=str_replace("&#39;", "'", $strNeutr);
    return ($strNeutr);
}
;
//clean up comment string
function ProtectComments($strComments)
{

    $strComments=RemoveProfanity($strComments);
	//save the line breaks becasue we are about to remove all html
    $strComments=str_replace("<br>", " &#13;", $strComments);
	//Remove all html tags
    $strComments=strip_html_tags($strComments);
    $strComments=strip_img_tags($strComments);
    $strComments=removequote($strComments);
	//restore the line breaks
    $strComments=str_replace("&#13;", "<br>", $strComments);

    return ($strComments);
}

//filter profanity from comments
function RemoveProfanity($strComments)
{

    $wordlist=array
        (
        'fuck',
        'shit',
        'dick',
        'cunt',
        'cock',
        'pussy',
        'fag',
        'nigger',
        'coon',
        'spick',
        'wetback',
        'chink',
        'raghead',
        'gook'
        );

    //while (list(, $word) = each($wordlist))
    foreach ($wordlist as $word) {
    //$strComments = preg_replace('/\b'.$word.'\b/ie', ' **** ', strtolower($strComments));
    $strComments=preg_replace('/' . $word . '/i', '#%$@!', $strComments); }

    return $strComments;
}

//list of words for search engine to ignore
function NotCommonWord($strWord)
{

    $rnt=true;
    $wordlist=array
        (
        'the',
        'of',
        'and',
        'a',
        'to',
        'in',
        'is',
        'you',
        'that',
        'it',
        'he',
        'was',
        'for',
        'on',
        'are',
        'as',
        'with',
        'his',
        'they',
        'I',
        'at',
        'be',
        'this',
        'have',
        'from',
        'or',
        'one',
        'had',
        'by',
        'word',
        'but',
        'not',
        'what',
        'all',
        'were',
        'we',
        'when',
        'your',
        'can',
        'said',
        'there',
        'use',
        'an',
        'each',
        'which',
        'she',
        'do',
        'how',
        'their',
        'if',
        'will',
        'up',
        'other',
        'about',
        'out',
        'many',
        'then',
        'them',
        'these',
        'so',
        'some',
        'her',
        'would',
        'make',
        'like',
        'him',
        'into',
        'time',
        'has',
        'look',
        'two',
        'more',
        'write',
        'go',
        'see',
        'number',
        'no',
        'way',
        'could',
        'people',
        'my',
        'than',
        'first',
        'water',
        'been',
        'call',
        'who',
        'its',
        'now',
        'find',
        'long',
        'down',
        'day',
        'did',
        'get',
        'come',
        'made',
        'may',
        'part'
        );

    if (in_array($strWord, $wordlist)) { $rnt=false; }

    return $rnt;
}

//format how long ago the stroy was published
function GetPublishDate($date)
{
    $PubDate=date('m/d/y', strtotime($date));
    //convert date difference to hours
    $date_diff=floor((time() - strtotime($date)) / (60 * 60));

    if ($date_diff < 12)
    {                         //less than 24 hours so show that
        if ($date_diff < 2) { //one hr or less just say one
        $PubDate='1 hr ago'; }
        else
            $PubDate=$date_diff . ' hrs ago';
    }
    return $PubDate;
}
;

//limit the display of text to a specific lenght
function LimitDisplay($msg, $maxlength)
{
    if (strlen($msg) > $maxlength)
    {

        $position=$maxlength; // Define how many characters you want to display.
        $message=substr($msg, 0, $position);
        $tmpstr=
            substr($message, $maxlength,
                1);           // Find what is the last character displaying. We find it by getting only last one character from your display message.

        if ($tmpstr != ' ')
        {                     // In this step, if last character is not ' '(space) do this step .
            // Find until we found that last character is ' '(space)
            // by $position+1 (14+1=15, 15+1=16 until we found ' '(space) that mean character 20)
            while ($tmpstr != ' ' && $position > 0)
            {
                $position=$position - 1;
                $tmpstr=substr($message, $position, 1);
            }
        }

        $strrtn=substr($message, 0, $position) . '...'; // Display your message
    }
    else { $strrtn=$msg; }

    return $strrtn;
}
;
//watch for spam bots
function canPost($lastpost, $diff)
{

    // When can the user post next?
    $nextpost=$lastpost + $diff;

    // What time is it now?
    $timenow=time();

    // Is the time now greater than the
    //next available post time?
    if ($timenow > $nextpost) { return true; }
    else { return false; }
}
?>