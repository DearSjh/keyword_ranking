<?php

$arr = [
    '上海油条机',
    '江苏油条机',
    '浙江油条机',
    '杭州油条机',
    '苏州油条机',
    '无锡油条机',
    '南京油条机',
    '上海油条机厂家',
    '上海油条机价格',
    '江苏油条机厂家',
    '江苏油条机价格',
    '浙江油条机厂家',
    '浙江油条机价格',
    '杭州油条机厂家',
    '杭州油条机价格',
    '苏州油条机厂家',
    '苏州油条机价格',
    '无锡油条机厂家',
    '无锡油条机价格',
    '南京油条机厂家',
    '南京油条机价格',
    '上海油条机全自动',
    '上海油条机自动',
    '上海油条机仿手工全自动',
    '江苏油条机全自动',
    '江苏油条机自动',
    '江苏油条机仿手工全自动',
    '浙江油条机全自动',
    '浙江油条机自动',
    '浙江油条机仿手工全自动',
    '杭州油条机全自动',
    '杭州油条机自动',
    '杭州油条机仿手工全自动',
    '苏州油条机全自动',
    '苏州油条机自动',
    '苏州油条机仿手工全自动',
    '无锡油条机全自动',
    '无锡油条机自动',
    '无锡油条机仿手工全自动',
    '南京油条机全自动',
    '南京油条机自动',
    '南京油条机仿手工全自动',
    '上海全自动油条机厂家',
    '上海全自动油条机价格',
    '上海自动油条机厂家',
    '上海自动油条机价格',
    '上海仿手工全自动油条机厂家',
    '上海仿手工全自动油条机价格',
    '江苏全自动油条机厂家',
    '江苏全自动油条机价格',
    '江苏自动油条机厂家',
    '江苏自动油条机价格',
    '江苏仿手工全自动油条机厂家',
    '江苏仿手工全自动油条机价格',
    '浙江全自动油条机厂家',
    '浙江全自动油条机价格',
    '浙江自动油条机厂家',
    '浙江自动油条机价格',
    '浙江仿手工全自动油条机厂家',
    '浙江仿手工全自动油条机价格',
    '杭州全自动油条机厂家',
    '杭州全自动油条机价格',
    '杭州自动油条机厂家',
    '杭州自动油条机价格',
    '杭州仿手工全自动油条机厂家',
    '杭州仿手工全自动油条机价格',
    '苏州全自动油条机厂家',
    '苏州全自动油条机价格',
    '苏州自动油条机厂家',
    '苏州自动油条机价格',
    '苏州仿手工全自动油条机厂家',
    '苏州仿手工全自动油条机价格',
    '无锡全自动油条机厂家',
    '无锡全自动油条机价格',
    '无锡自动油条机厂家',
    '无锡自动油条机价格',
    '无锡仿手工全自动油条机厂家',
    '无锡仿手工全自动油条机价格',
    '南京全自动油条机厂家',
    '南京全自动油条机价格',
    '南京自动油条机厂家',
    '南京自动油条机价格',
    '南京仿手工全自动油条机厂家',
    '南京仿手工全自动油条机价格',
    '全自动油条机厂家',
    '全自动油条机价格',
    '自动油条机厂家',
    '自动油条机价格',
    '仿手工全自动油条机厂家',
    '仿手工全自动油条机价格',
];



$sr = new SearchRanking();


foreach ($arr as $key => $item) {
    echo $key;
    echo ':';
    echo $sr->get(['chuan-wan.com', 'chuanwan18.com'], $item);
    echo PHP_EOL;
}


class SearchRanking
{

    private $header = [
        'Accept: */*',
        'Accept-Language: zh-CN,zh;q=0.9',
        'Connection: keep-alive',
        'Host: www.baidu.com',
        'is_referer: https://www.baidu.com/',
        'is_xhr: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36',
        'X-Requested-With: XMLHttpRequest',
    ];

    public function __construct()
    {
        array_push($this->header, 'Cookie: ' . $this->getCookie());
    }

    public function get($siteUrl, $keywords)
    {

        $url = "https://www.baidu.com/s?ie=utf-8&f=3&rsv_bp=1&rsv_idx=1&tn=baidu&wd={$keywords}";
        $contents = $this->curl($url);

        preg_match_all('/<div\sclass="result\sc-container(.*?)<\/div><\/div>/s', $contents, $matches);

        $ranking = 11;

        foreach ($matches[0] as $match) {

            if (is_string($siteUrl)) {
                if (stripos($match, $siteUrl) !== false) {
                    preg_match_all('/\sid=\"(\d+)\"/s', $match, $rr);
                    $ranking = $rr[1][0];
                }

            } elseif (is_array($siteUrl)) {

                foreach ($siteUrl as $url) {
                    file_put_contents('search.html', $match, FILE_APPEND);

                    if (stripos($match, $url) !== false) {
                        preg_match_all('/\sid=\"(\d+)\"/s', $match, $rr);
                        if (!empty($rr[1][0]) && $rr[1][0] < $ranking) {
                            $ranking = $rr[1][0];

                        }
                    }
                }

            }

        }

        return $ranking > 10 ? '10+' : $ranking;

    }


    public function curl($url)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:' . $this->randIP()));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_HEADER, 1); //返回response头部信息

        $data = curl_exec($ch);
        curl_close($ch);

        $this->setCookie($data);

        return $data;
    }

    public function setCookie($cookies)
    {
        $pregCookie = '/Set-Cookie: (.*)/i';
        preg_match_all($pregCookie, $cookies, $matches);

        if ($matches[1]) {

            $str = '';
            foreach ($matches[1] as $cookie) {
                $str .= trim($cookie) . ';';
            }
            $str = trim(str_replace(' ', '', $str), ';');
            file_put_contents('cookie.txt', $str);
            return $str;

        }

        return '';
    }

    public function getCookie()
    {
        return file_get_contents('cookie.txt');
    }

}


