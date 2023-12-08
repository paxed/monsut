<?php
header('Content-type: text/html; charset=iso-8859-1');

print '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'."\n";

$game = 'NetHack 3.7';
$arr = file('monsut.txt');

print '<html>';
print '<head>'."\n";
print '<title>'.$game.' monsters by color and symbol</title>'."\n";
print '<style type="text/css">
body {
    background: black;
    color:lightgray;
}

.f0  { color: #000000; }
.f1  { color: #0000aa; }
.f2  { color: #00aa00; }
.f3  { color: #00aaaa; }
.f4  { color: #aa0000; }
.f5  { color: #aa00aa; }
.f6  { color: #aa5500; }
.f7  { color: #aaaaaa; }
.f8  { color: #555555; }
.f9  { color: #5555ff; }
.f10 { color: #55ff55; }
.f11 { color: #55ffff; }
.f12 { color: #ff5555; }
.f13 { color: #ff55ff; }
.f14 { color: #ffff55; }
.f15 { color: #ffffff; }

span.desc {
 display: none;
}

span:hover .desc {
 display: block;
 position: absolute;
 margin-left: -2em;
 border: 1px solid black;
 padding: 5px;
 background-color: lightgray;
 color: black;
 filter:alpha(opacity=80);-moz-opacity:.80;opacity:.80;
 font-family:monospace;
}

span.sym {
 padding:0.1em;
 background:black;
 font-size:large;
}

table.mons {
 background:black;
 white-space:pre;
 font-family:monospace;
}

</style>
';

print '</head><body>'."\n";


$allchrs = array();
$allcols = array();


$colnames = array('black'=>8,'blue'=>1,'green'=>2,'cyan'=>3,
		  'red'=>4,'magenta'=>5,'brown'=>6,'lightgray'=>7,
		  'brightblue'=>9,'brightgreen'=>10,'brightcyan'=>11,
		  'brightred'=>12,'brightmagenta'=>13,'yellow'=>14,
		  'white'=>15,

		  'gray'=>7,
		  'darkgray'=>8,
		  'orange'=>12
);

foreach ($arr as $tmp) {
    if ($tmp[0] == '#') continue;
    if (preg_match('/^\'(.+)\'\t(.+)\t(.+)$/', $tmp, $matches)) {
        $chr = $matches[1];
        $color = str_replace(' ', '', $matches[2]);

        if (isset($colnames[$color]))
            $col = $colnames[$color];
        else print $matches[2]."<br>";
        $mon = $matches[3];
        array_push($allchrs, $chr);
        array_push($allcols, $col);
        if (isset($mons[$chr][$col])) {
            array_push($mons[$chr][$col], $mon);
        } else {
            $mons[$chr][$col] = array($mon);
        }
    }
}

$allcols = array_unique($allcols);
$allchrs = array_unique($allchrs);

sort($allcols);

sort($allchrs);

print '<center>'."\n";

print '<h2>'.$game.' monsters by color and symbol</h2>'."\n";

print '<table class="mons">'."\n";

function mklinks($arr)
{
    $ret = '';
    foreach ($arr as $s) {
        $p = str_replace(' ', '_', $s);
        $ret .= '<a href="http://nethackwiki.com/wiki/'.$p.'">'.$s.'</a><br>';
    }
    return $ret;
}

function getsym($chr, $col)
{
    return '<span class="sym '.$col.'">'.$chr.'</span>';
}

function dopopup($mons, $chr, $tmpcol)
{
    return '<span class="desc">' . getsym($chr, $tmpcol) . "<br>\n" . mklinks($mons) . '</span>'."\n";
}

function docell($chr, $col)
{
    global $mons, $hexcol;

    if (isset($mons[$chr][$col])) {
        $tmpcol = 'f'.$col;
        print '  <td class="'.$tmpcol.'">';
        print '<span>'.$chr;
        sort($mons[$chr][$col]);
        print dopopup($mons[$chr][$col], $chr, $tmpcol);
        print '</span>';
        print '</td>';
    } else {
        print '  <td style="color:darkgray;">';
        print '.';
        print '</td>';
    }
    print "\n";
}

foreach ($allcols as $col) {
    print ' <tr>'."\n";
    foreach ($allchrs as $chr) {
        docell($chr, $col);
    }
    print ' </tr>'."\n";
}

print '</table>'."\n";
print '</center>'."\n";
print '</body></html>';
