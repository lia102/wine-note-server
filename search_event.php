<?php
$keyword = $_POST["keyword"];
switch($keyword){
    case "sales":
        $code = "C02003";
        break;
    
    case "tasting":
        $code = "C02001";
        break;

    case "education":
        $code = "C02005";
        break;
}
$url = "https://www.wine21.com/12_event/event_list.html?shCate=".$code;

function getContents($url){
    $ch = curl_init($url);
    $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36';

    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
    curl_setopt($ch,CURLOPT_FAILONERROR,true); //
    curl_setopt($ch,CURLOPT_HEADER, 0); //헤더값을 가져오기위해 사용
    // curl_setopt($ch, CURLOPT_COOKIE, '_pxhd=lgzEujTObKMZXazaAFPUgdDSs24oLAjgmCaf8LKhjG5uD6cEu-DK25KB5tcpUmlfLzkAnfIiRhQ5sQDLHSB33A==:-FynsAA-em5rh66Y1chQYE545Nw7dLT16lA7c3k9OM1iLUsfuT6SPt-Pz7OfNz4-7649kqYXZtk-t1VesWSku7-R9N0Iy6KTxjUuglJ1qMM=; Expires=Fri, 18 Nov 2022 07:42:49 GMT; path=/' );
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); // True 로 설정치 curl_exec()의 반환 값을 문자열로 변환
    // curl_setopt($ch,CURLOPT_USERAGENT,"'".$user_agent_list[0]."'");
    curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    //HTTP 요청에 사용되는 User-Agent 헤더의 내용
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,300); // 연결에 대한 타임 아웃 설정
    curl_setopt($ch,CURLOPT_TIMEOUT,400); // 반환 값에 대한 타임아웃 설정
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); //true로 설정시 일부 https 사이트는 안 열림
    $webcontents = curl_exec($ch); // 세션을 실행한다.
    if (curl_errno($ch)) {
      $error_msg = curl_error($ch);
      return $error_msg;
  }
    return $webcontents;
}
$content = getContents($url);
$dom = new DOMDocument();
libxml_use_internal_errors(true);
@$dom->loadHTML($content);
$xpath = new DOMXPath($dom);

$images = $xpath->query("//div[@class='thumb']/a/img");
$img = [];
$title = [];
foreach($images as $image) {
    $img[] = $image->getAttribute("src");
    $title[] = $image->getAttribute("alt");
}

$links = $xpath->query("//div[@class='txt event']/a");
$link = [];
foreach($links as $link2) {
    $link[] = $link2 ->getAttribute("href");
}

$infos = $xpath->query("//div[@class='txt event']");
$info = [];
foreach($infos as $info2) {
    $info[] = $info2 ->textContent;
}
if($images == null){
    $response["success"] = false; 
    $response["content"] = $content; 
    $response["url"] = $url;
}else{
    $response["success"] = true; 
    $response["title"] = $title;
    $response["link"] = $link;
    $response["info"] = $info;
    $response["img"] = $img;
}
echo json_encode($response);

?>