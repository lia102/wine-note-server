<?php
$mode = $_POST["mode"];
$response = array();

switch($mode){
    case "justType":
        $type = $_POST["type"]; 
        $price = $_POST["price"]; 
        $webpage = "https://www.wine-searcher.com/discover?p=".$price."&s=".$type."&e=w4";
        break;
    case "afterFilter":
        $price = $_POST["price"]; 
        $type = $_POST["type"]; 
        $country = $_POST["country"]; 
        $region = $_POST["region"]; 
        $grape = $_POST["grape"];
        $world = "w0"; 
        if($country=="0"){
            $stCountry = "";
        }else{
            $stCountry = "&c=".$country;
        }
        if($region=="0"){
            $stRegion = "";
        }else{
            $stRegion = "&r=".$region;
        }
        if($grape=="0"){
            $stGrape = "";
        }else{
            $stGrape = "&g=".$grape;
        }

        $webpage = "https://www.wine-searcher.com/discover?p=".$price."&s=".$type.$stGrape.$stCountry.$stRegion."&e=w4";
        break;
    }    
// $price = "0-1"; 
// $type = "1"; 
// $country = "6"; 
// $region = "86"; 
// $grape = "1112";
// $world = "w0"; 
// $webpage = "https://www.wine-searcher.com/discover?p=0-1&s=1&g=1112&c=6&r=86&e=w4+South+Korea";

$user_agent_list = array(
    # Chrome
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
    'Mozilla/5.0 (Windows NT 5.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
    'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36',
    # Firefox
    'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.1)',
    'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko',
    'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)',
    'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko',
    'Mozilla/5.0 (Windows NT 6.2; WOW64; Trident/7.0; rv:11.0) like Gecko',
    'Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; rv:11.0) like Gecko',
    'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0)',
    'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko',
    'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)',
    'Mozilla/5.0 (Windows NT 6.1; Win64; x64; Trident/7.0; rv:11.0) like Gecko',
    'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)',
    'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)',
    'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)',
    'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:59.0) Gecko/20100101 Firefox/59.0'
);
shuffle($user_agent_list);  

// $webpage = "https://www.wine-searcher.com/discover?p=0-8&s=1&g=1770&e=w4";
    

function getContents($webpage){
    $ch = curl_init();
    $proxy = '3.37.52.118:8080';
    $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.82 Safari/537.36';
    curl_setopt($ch,CURLOPT_URL, $webpage);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
    // curl_setopt ( $ch ,  CURLOPT_PROXY ,  '3.37.52.118' ); 
    // curl_setopt ( $ch ,  CURLOPT_PROXYPORT ,  '8080' );
    // curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    // curl_setopt ( $ch ,  CURLOPT_HTTPAUTH ,  CURLAUTH_BASIC );
    curl_setopt($ch,CURLOPT_FAILONERROR,true); //
    curl_setopt($ch,CURLOPT_HEADER, 0); //헤더값을 가져오기위해 사용
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cache-Control: private, no-cache, no-store, must-revalidate"));
    // curl_setopt($ch, CURLOPT_COOKIE, '_pxhd=lgzEujTObKMZXazaAFPUgdDSs24oLAjgmCaf8LKhjG5uD6cEu-DK25KB5tcpUmlfLzkAnfIiRhQ5sQDLHSB33A==:-FynsAA-em5rh66Y1chQYE545Nw7dLT16lA7c3k9OM1iLUsfuT6SPt-Pz7OfNz4-7649kqYXZtk-t1VesWSku7-R9N0Iy6KTxjUuglJ1qMM=; Expires=Fri, 18 Nov 2022 07:42:49 GMT; path=/' );
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); // True 로 설정치 curl_exec()의 반환 값을 문자열로 변환
   // curl_setopt($ch,CURLOPT_USERAGENT,"'".$user_agent_list[0]."'");
    curl_setopt($ch,CURLOPT_USERAGENT,$ua );
    // curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    //HTTP 요청에 사용되는 User-Agent 헤더의 내용
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,300); // 연결에 대한 타임 아웃 설정
    curl_setopt($ch,CURLOPT_TIMEOUT,400); // 반환 값에 대한 타임아웃 설정
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); //true로 설정시 일부 https 사이트는 안 열림
    curl_setopt($ch, CURLOPT_REFERER, 'https://www.wine-searcher.com/');
    $webcontents = curl_exec($ch); // 세션을 실행한다.
    if (curl_errno($ch)) {
      $error_msg = curl_error($ch);
      return $error_msg;
  }
    return $webcontents;
}
// sleep(3);
$content = getContents($webpage);

//$body = $split_result[1];

// $response["content"] = $content; 
// preg_match_all('~<div class=\"offer-card__region\">\s*(<div.*?</div>\s*)?(.*?)</div>~is', $content, $region);
// preg_match_all('/<div[^>]+class="offer-card__name"[^>]*>(.*)<\/div>/', $content, $names);
// preg_match_all('/<div[^>]+class="offer-card__style-name"[^>]*>(.*)<\/div>/', $content, $style1);
// // print_r( $style1[0] );
// preg_match_all('/<div[^>]+class="offer-card__style-desc"[^>]*>(.*)<\/div>/', $content, $style2);
// preg_match_all('~<div class=\"offer-card__price d-inline-block d-lg-block ml-3 ml-lg-0\">(.*?)\s*(<div.*?</div>\s*)?</div>~is', $content, $price);

$dom = new DOMDocument();
$dom->loadHTML($content);
$xpath = new DOMXPath($dom);

$prices = $xpath->query("//div[@class='offer-card__price d-inline-block d-lg-block ml-3 ml-lg-0']/b");
$price = [];
foreach($prices as $xpathName) {
    $price[] = $xpathName ->textContent;
}

$styles = $xpath->query("//div[@class='offer-card__style-desc']");
$style = [];
foreach($styles as $style2) {
    $style[] = $style2 ->textContent;
}

$links = $xpath->query("//a[@class='offer-card__link d-flex align-items-center w-100']");
$link = [];
foreach($links as $link2) {
    $link[] = $link2 ->getAttribute("href");
}
//

$images = $xpath->query("//div[@class='d-none d-lg-block col-lg-3 text-center pt-3 pb-3']/img");
$img = [];
$name = [];
$region = [];
foreach($images as $image) {
    $img[] = $image->getAttribute("data-rel");
    $name[] = $image->getAttribute("data-winename");
    $region[] = $image->getAttribute("data-region");
}

if($name == null){
    $response["success"] = false; 
    $response["content"] = $content; 
    $response["user"] = $user_agent_list[0]; 
    $response["webpage"] = $webpage;
}else{
    $response["success"] = true; 
    $response["names"] = $name;
    $response["region"] = $region;
    $response["style"] = $style;
    $response["price"] = $price;
    $response["img"] = $img;
    $response["link"] = $link;
}
echo json_encode($response);

?>