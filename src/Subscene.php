<?php
namespace Hsnfirdaus;
/**
 * The Main Subscene Class
 */
class Subscene
{
	const BASE_URL = 'https://subscene.com';
	const CURL_ERROR = ['error'=>TRUE,'message'=>'Empty page result, check your internet or maybe page not found!'];
	const NOT_FOUND = ['error'=>TRUE,'message'=>'Can not get the result!'];

	private function makeRequest($path,$post=FALSE,$base_url=TRUE)
	{
		$ch = curl_init();
		if ($base_url) {
			curl_setopt($ch, CURLOPT_URL, static::BASE_URL.$path);
		}else{
			curl_setopt($ch, CURLOPT_URL, $path);
		}
		curl_setopt($ch, CURLOPT_PROXY, '83.97.23.90');
		curl_setopt($ch, CURLOPT_PROXYPORT, '18080');
		curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if ($post) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		curl_setopt($ch, CURLOPT_REFERER, static::BASE_URL);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	public function search($query)
	{
		$result=[];
		$get_page=$this->makeRequest('/subtitles/searchbytitle',[
			'query'=>$query,
			'l'=>''
		]);
		if (!empty($get_page)) {
			if (preg_match('#<div class=\"search-result\">(.*?)<div id=\"right\">#s', $get_page, $content)) {
				preg_match_all('#<h2.*?>(.*?)</h2>.*?<ul>(.*?)</ul>#s', $content[1], $list);
				for ($i=0; $i < count($list[1]); $i++) { 
					preg_match_all('#<li>(.*?)</li>#s', $list[2][$i], $subtitles);
					foreach ($subtitles[1] as $sub) {
						$permalink=$title=$year=$count='';
						if(preg_match('#href=\"/subtitles/(.*?)\">(.*?)</a>.*?<(span|div) class=\"subtle count\">.*?([0-9]+)\s*subtitles.*?</(span|div)>#s', $sub, $match)){
							$permalink=@$match[1];
							$title=preg_replace('#\s*\(([0-9]+)\)$#', '', trim(@$match[2]));
							preg_match('#\s*\(([0-9]+)\)$#', @$match[2], $year);
							$year=@$year[1];
							$count=$match[4];
							$result[]=[
								'permalink'=>$permalink,
								'title'=>$title,
								'year'=>$year,
								'count'=>$count,
								'in_heading'=>$list[1][$i]
							];
						}
					}
				}
			}
			if (!empty($result)) {
				$response=[
					'type'=>'search',
					'query'=>$query,
					'result'=>$result
				];
				return $response;
			}else{
				return static::NOT_FOUND;
			}
		}else{
			return static::CURL_ERROR;
		}
	}
	public function listSubtitle($permalink)
	{
		$result=[];
		$get_page=$this->makeRequest('/subtitles/'.$permalink);
		if (!empty($get_page)) {
			preg_match('#<div class=\"top left\">.*?<a.*?href=\"(.*?)\".*?imdb.com/title/#s', $get_page, $match_poster);
			$poster=@$match_poster[1];
			preg_match('#<h2.*?>(.*?)</h2>#s', $get_page, $match_h2);
			$title=trim(preg_replace('#<a(.+)#s', '', $match_h2[1]));
			preg_match('#imdb.com/title/(.*)\"#', $match_h2[1], $match_imdb);
			$imdb_id=@$match_imdb[1];
			preg_match('#<li>.*?<strong>.*?Year:.*?</strong>.*?([0-9]+).*?</li>#s', $get_page, $match_year);
			$year=@$match_year[1];
			preg_match('#<tbody>(.*?)</tbody>#s', $get_page, $match_content);
			preg_match_all('#<tr.*?>(.*?)</tr>#s', $match_content[1], $list);
			foreach ($list[1] as $sub) {
				if (preg_match('#<td class=\"a1\">.*?href=\"/subtitles/'.$permalink.'/(.*?)/([0-9]+)\">.*?<span class=\"l r (.*?)-icon\">(.*?)</span>.*?<span>(.*?)</span>.*?<td class=\"a3\">.*?<td class=\"a(.*?)\">.*?(href=\"/u/([0-9]+)\">(.*?)</a>|Anonymous).*?<td class=\"a6\">(.*?)</td>#s', $sub, $match)) {
					if ($match[6]==41) {
						$hi=TRUE;
					}else{
						$hi=FALSE;
					}
					if (!empty($match[8])&&!empty($match[9])) {
						$user=[
							'id'=>$match[8],
							'name'=>trim($match[9])
						];
					}else{
						$user=[
							'id'=>0,
							'name'=>'Anonymous',
						];
					}
					$result[]=[
						'id'=>$match[2],
						'language'=>[
							'code'=>$match[1],
							'name'=>trim($match[4])
						],
						'rate'=>$match[3],
						'name'=>trim($match[5]),
						'hearing_impaired'=>$hi,
						'user'=>$user,
						'comment'=>trim(strip_tags($match[10]))
					];
				}
			}
			if (!empty($result)) {
				$response=[
					'type'=>'list_subtitle',
					'permalink'=>$permalink,
					'title'=>$title,
					'year'=>$year,
					'poster'=>$poster,
					'imdb_id'=>$imdb_id,
					'result'=>$result
				];
				return $response;
			}else{
				return static::NOT_FOUND;
			}
		}else{
			return static::CURL_ERROR;
		}
	}
	public function getSubtitle($permalink,$lang_code,$id)
	{
		$get_page=$this->makeRequest('/subtitles/'.$permalink.'/'.$lang_code.'/'.$id);
		if (!empty($get_page)) {
			preg_match('#<div class=\"top left\">.*?<a.*?href=\"(.*?)\"#s', $get_page, $match_poster);
			$poster=@$match_poster[1];
			preg_match('#<h1.*?>(.*?)</h1>#s', $get_page, $match_h1);
			$title=trim(strip_tags(preg_replace('#<a(.+)#s', '', $match_h1[1])));
			preg_match('#imdb.com/title/(.*)\"#', $match_h1[1], $match_imdb);
			$imdb_id=@$match_imdb[1];
			preg_match('#<li class=\"release\">.*?<strong>.*?Release info\:.*?</strong>(.*?)</li>#s', $get_page, $match_release);
			$release_list=[];
			preg_match_all('#<div.*?>(.*?)</div>#s', $match_release[1], $match_release_list);
			foreach ($match_release_list[1] as $release) {
				$release_list[]=trim(strip_tags($release));
			}
			preg_match('#<li class=\"author\">.*?(href=\"/u/([0-9]+)\">(.*?)</a>|Anonymous)<span.*?</li>#s', $get_page, $match_user);
			if (!empty($match_user[2])&&!empty($match_user[3])) {
				$user_id=$match_user[2];
				$user_name=trim($match_user[3]);
			}else{
				$user_id=0;
				$user_name='Anonymous';
			}
			preg_match('#<div class=\"comment\">(.*?)</div>#s', $get_page, $match_comment);
			$comment=trim($match_comment[1]);
			preg_match('#<div.*?id=\'preview\'>.*?<p>(.*?)</p>.*?</div>#s', $get_page, $match_preview);
			$preview=trim($match_preview[1]);
			preg_match('#<div.*?details.*?>.*?<ul>(.*?)---------.*?(<li itemprop=\"aggregateRating\".*?itemprop="ratingValue">([0-9]+)</span>.*?itemprop=\"bestRating\">([0-9]+)</span>.*?itemprop="ratingCount">([0-9]+)</span>.*?<li>|<li>.*?<strong>Not rated</strong>.*?</li>).*?<strong>.*?Downloads:</strong>(.*?)</li>.*?</ul>#s', $get_page, $match_details);
			preg_match_all('#<li>.*?<strong>(.*?)\:</strong>(.*?)</li>#s', $match_details[1], $match_list_details);
			$details=[];
			for ($i=0; $i < count($match_list_details[1]); $i++) { 
				$key=str_replace(' ', '_', trim(strtolower($match_list_details[1][$i])));
				$details[$key]=trim(preg_replace('#\s+#',' ',strip_tags($match_list_details[2][$i])));
			}
			if (!empty($match_details[3])&&!empty($match_details[4])&&!empty($match_details[3])) {
				$details['ratings']=[
					'score'=>trim($match_details[3]),
					'of'=>trim($match_details[4]),
					'total_user'=>trim($match_details[5])
				];
			}else{
				$details['ratings']=[
					'score'=>0,
					'of'=>0,
					'total_user'=>0
				];
			}
			$details['total_download']=preg_replace('#([^0-9].*?)#s','',trim($match_details[6]));
			preg_match('#<div class=\"download\">.*?<a href=\"(.*?)\".*?Download (.*?) Subtitle#s', $get_page, $match_download);
			$download_link=$match_download[1];
			$lang_name=$match_download[2];
			if (!empty($download_link)) {
				$response=[
					'type'=>'get_subtitle',
					'permalink'=>$permalink,
					'language'=>[
						'code'=>$lang_code,
						'name'=>trim($lang_name)
					],
					'id'=>$id,
					'poster'=>$poster,
					'title'=>$title,
					'imdb_id'=>$imdb_id,
					'release_list'=>$release_list,
					'user'=>[
						'id'=>$user_id,
						'name'=>$user_name
					],
					'comment'=>$comment,
					'preview'=>$preview,
					'details'=>$details,
					'download_link'=>static::BASE_URL.$download_link
				];
				return $response;
			}else{
				return static::NOT_FOUND;
			}
		}else{
			return static::CURL_ERROR;
		}
	}
	public function smartFilterSeriesSubtitle($list_subtitle,$series_season,$total_episode,$language='english',$primary_type=[])
	{
		$list=$missing_episode=$positive=$neutral=$bad=[];
		foreach ($list_subtitle['result'] as $rate_filter) {
			switch ($rate_filter['rate']) {
				case 'positive':
					$positive[]=$rate_filter;
					break;

				case 'bad':
					$bad[]=$rate_filter;
					break;
				
				default:
					$neutral[]=$rate_filter;
					break;
			}
		}
		$result=array_merge($positive,$neutral,$bad);
		for ($i=0; $i < count($result); $i++) { 
			$title=$result[$i]['name'];
			if (preg_match('#S\s*([0-9]+)\s*E\s*([0-9]+)#', $title, $se)) {
				$season=$se[1];
				$episode=$se[2];
			}elseif (preg_match('#([0-9]+)\s*(\-|x)\s*([0-9]+)#', $title, $se)) {
				$season=$se[1];
				$episode=$se[3];
			}elseif (preg_match('#Season\s*([0-9]+)\s*Epsoide\s*([0-9]+)\s*#', $title, $se)) {
				$season=$se[1];
				$episode=$se[2];
			}
			if (isset($title)&&(@$season==$series_season)&&isset($episode)&&(($result[$i]['language']['code']==$language)||($result[$i]['language']['name']==$language))) {
				$list[]=array_merge($result[$i],['season'=>$season,'episode'=>$episode]);
			}
		}
		$newlist=[];
		for ($i=1; $i <= $total_episode; $i++) {
			$internal=[]; 
			foreach ($list as $s) {
				if ($s['episode']==$i) {
					$internal[]=$s;
				}
			}
			if (!empty($internal)) {
				$got=FALSE;
				if (!empty($primary_type)) {
					foreach ($primary_type as $primary) {
						if (!$got) {
							foreach ($internal as $in) {
								if (preg_match('#'.addslashes($primary).'#', $in['name'])) {
									$got=TRUE;
									$newlist[$i]=$in;
									break;
								}
							}
						}
					}
				}
				if (!$got) {
					$newlist[$i]=$internal[0];
				}
			}else{
				$missing_episode[]=$i;
			}
		}
		$response=[
			'type'=>'smart_filter_series_subtitle',
			'permalink'=>$list_subtitle['permalink'],
			'title'=>$list_subtitle['title'],
			'year'=>$list_subtitle['year'],
			'poster'=>$list_subtitle['poster'],
			'imdb_id'=>$list_subtitle['imdb_id'],
			'season'=>$series_season,
			'total_episode'=>$total_episode,
			'language'=>$language,
			'primary_type'=>$primary_type,
			'missing_episode'=>$missing_episode,
			'result'=>$newlist
		];
		return $response;
	}
	public function smartDownloadSeriesSubtitle($filter_result,$folder_target=__DIR__.'/../')
	{
		if (!is_dir('tmp')) {
			mkdir('tmp');
		}
		if (!is_dir($folder_target)) {
			mkdir($folder_target);
		}
		$failed_list=$success_list=[];
		foreach ($filter_result['result'] as $subtitle_list) {
			$permalink=$filter_result['permalink'];
			$lang_code=$subtitle_list['language']['code'];
			$id=$subtitle_list['id'];
			$single_page=$this->getSubtitle($permalink,$lang_code,$id);
			if ($single_page['download_link']) {
				if(file_put_contents('tmp.zip', $this->makeRequest($single_page['download_link'],FALSE,FALSE))){
					$name=preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($filter_result['title'])).' - S'.$subtitle_list['season'].'E'.$subtitle_list['episode'].'.srt';
					$zip = new \ZipArchive;
					if($zip->open('tmp.zip') === TRUE) {
					   $zip->extractTo('tmp');
					   $zip->close();
					}
					foreach (glob(__DIR__.'/tmp/*') as $f) {
						rename($f, $folder_target.'/'.$name);
						$success_list[]=$name;
					}
					unlink('tmp.zip');
				}else{
					$failed_list[]=[
						'episode'=>$subtitle_list['episode'],
						'subtitle_page'=>static::BASE_URL.'/subtitles/'.$permalink.'/'.$lang_code.'/'.$id,
						'download_link'=>$single_page['download_link']
					];
				}
			}else{
				$failed_list[]=[
					'episode'=>$subtitle_list['episode'],
					'subtitle_page'=>static::BASE_URL.'/subtitles/'.$permalink.'/'.$lang_code.'/'.$id
				];
			}
		}
		$response=[
			'type'=>'smart_download_series_subtitle',
			'folder_target'=>$folder_target,
			'season'=>$filter_result['season'],
			'missing_episode'=>$filter_result['missing_episode'],
			'failed_episode'=>$failed_list,
			'saved_file'=>$success_list
		];
		return $response;
	}
}
$subscene = new Subscene();
$list_subtitle = $subscene->listSubtitle('fbi-most-wanted-season-one');
$smart_filter =$subscene->smartFilterSeriesSubtitle($list_subtitle,1,14,'english',['web']);
$smart_download = $subscene->smartDownloadSeriesSubtitle($smart_filter,__DIR__.'/subtitle');
$response = json_encode($smart_download,JSON_PRETTY_PRINT);
file_put_contents('debug.json', $response);