<?php
//ini_set('memory_limit','-1');

require_once('vendor/autoload.php');

use League\CLImate\CLImate;
use Curl\Curl;
use Rct567\DomQuery\DomQuery;

// VAR
define('WIDTH', shell_exec('tput cols'));
define('CF', 'kbbi.cookie.txt');
$R = "\033[31;1m";
$G = "\033[32;1m";
$D = "\033[0m";

// Function 
function br2nl($buff='') {
return trim(preg_replace('#<br[/\s]*>#si', "\n", $buff));
}

function CreateDOM($txt){
return new DomQuery($txt);
}




$cli = new CLImate();
$cli->clear();
echo $G;
echo shell_exec('figlet -tc KBBI');
$cli->border('-', WIDTH);


$getlink = $cli->input("$G [!] Enter keyword: ");
$res = $getlink->prompt();
$gu = 'https://kbbi.web.id/'.trim($res);
$cli->border('-', WIDTH);
echo '[!] Please wait fetching data from '.$gu.' .....'."\n";
$cli->border('-', WIDTH);

$curl = new Curl();
$curl->setUserAgent('Mozilla/5.0 (Linux; Android 8.1.0; ASUS_X01BDA) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.157 Mobile Safari/537.36');
$curl->setReferrer(trim($res));
$curl->setCookieFile(CF);
$curl->setCookieJar(CF);
$h = [
'Connection' => 'close',
'Accept-Encoding' => 'gzip, deflate, br',
'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7,su;q=0.6',
'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3'
];

/**/

$curl->setOpt(CURLOPT_HTTPHEADER, $h);

$curl->get($gu);

if($curl->error)
die('[!] Error: '.$curl->errorCode.' : '.$curl-errorMessage);


$dom = createDOM($curl->response);
print(br2nl(strip_tags(trim($dom->find('#d1')->html()),'<br>')));

echo PHP_EOL;

