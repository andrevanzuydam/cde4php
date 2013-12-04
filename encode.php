<?php

function tUsage() {
    die("usage: encode.php <src[.php]> <out[.php]>\n");
}

if ($argc !== 3) {
    tUsage();
}

$src = $argv[1];
$tgt = $argv[2];

$src = (substr($src, -4) !== '.php') ? $src . '.php' : $src;
$tgt = (substr($tgt, -4) !== '.php') ? $tgt . '.php' : $tgt;

if (!file_exists($src)) {
    echo "File $src should exist\n";
    tUsage();
}

$code = @file_get_contents($src);
$tokens = token_get_all($code);

$ClosTag = false;
ob_start();
foreach ($tokens as $token) {
    if (is_array($token)) {
        switch ($token[0]) {
            case T_OPEN_TAG:
                if($ClosTag == TRUE){
                    echo $token[1];
                }
            case T_COMMENT:
            case T_DOC_COMMENT:
                break;
            case T_CLOSE_TAG:
                $ClosTag = true;
            default:
                echo $token[1];
                break;
        }
    }else{
        echo $token;
    }
}
$code = ob_get_clean();

$fp = fopen($tgt, 'w+');
fwrite($fp, '<'.'?'.'php'."\n");
fwrite($fp, 'eval(gzuncompress(base64_decode(\''.  base64_encode(gzcompress($code,9)).'\')));' . "\n");
fclose($fp);

?>

