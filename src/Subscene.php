<?php
namespace Hsnfirdaus;
/**
 * The Main Subscene Class
 */
class CURLError extends \Exception{}
class PageStructurError extends \Exception{}
class FunctionError extends \Exception{}
class Subscene
{
	const BASE_URL = 'https://subscene.com';
	const CURL_ERROR = 'Empty page result, check your internet or maybe page not found!';
	const PAGE_CHANGED = 'Maybe page structure has been changed!';
	private $current_type = '';
	private $current_info = [];
	private $current_result = [];

	private function makeRequest($path,$post=FALSE,$base_url=TRUE)
	{
		$ch = curl_init();
		if ($base_url) {
			curl_setopt($ch, CURLOPT_URL, static::BASE_URL.$path);
		}else{
			curl_setopt($ch, CURLOPT_URL, $path);
		}
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if ($post) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__.'/cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__.'/cookie.txt');
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 8.0; Pixel 2 Build/OPD3.170816.012) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Mobile Safari/537.36');
		curl_setopt($ch, CURLOPT_REFERER, static::BASE_URL);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		$result = curl_exec($ch);
		curl_close($ch);
		if (empty($result)) {
			throw new CURLError(static::CURL_ERROR);
		}else{
			return $result;
		}
	}
	private function seasonNumber($name,$throw=TRUE)
	{
		$array_list=[
			'first'=>1,
			'second'=>2,
			'third'=>3,
			'fourth'=>4,
			'fifth'=>5,
			'sixth'=>6,
			'seventh'=>7,
			'eight'=>8,
			'ninth'=>9,
			'tenth'=>10,
			'eleventh'=>11,
			'twelfth'=>12,
			'thirteenth'=>13,
			'fourteenth'=>14,
			'fifteenth'=>15,
			'sixteenth'=>16,
			'seventeenth'=>17,
			'eighteenth'=>18,
			'nineteenth'=>19,
			'twenty'=>20,
			'thirty'=>30,
			'fourty'=>40,
			'fifty'=>50,
			'sixty'=>60,
			'seventy'=>70,
			'eighty'=>80,
			'ninety'=>90,
			'twentieth'=>20,
			'thirtieth'=>30,
			'fortieth'=>40,
			'fiftieth'=>50,
			'sixtieth'=>60,
			'seventieth'=>70,
			'eightieth'=>80,
			'ninetieth'=>90,
		];
		$explode=explode(' - ', $name);
		$explode2=explode(' Season', end($explode));
		$season_explode=explode('-', strtolower($explode2[0]));
		$season_number=0;
		for ($i=0; $i < count($season_explode); $i++) { 
			if (isset($array_list[$season_explode[$i]])) {
				$season_number=$season_number+$array_list[$season_explode[$i]];
			}else{
				if ($throw) {
					throw new Exception('Cannot Get Season number! set it manually!');
				}
				
			}
		}
		return $season_number;
	}
	public function search($query)
	{
		$result=[];
		$get_page=$this->makeRequest('/subtitles/searchbytitle',[
			'query'=>$query,
			'l'=>''
		]);
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
						$array=[
							'permalink'=>$permalink,
							'title'=>$title,
							'year'=>$year,
							'count'=>$count,
							'in_heading'=>$list[1][$i]
						];
						$season_number=$this->seasonNumber($title,FALSE);
						if ($season_number!=0) {
							$array['season']=$season_number;
						}
						$result[]=$array;
					}
				}
			}
		}else{
			throw new PageStructurError(static::PAGE_CHANGED);
		}
		if (!empty($result)) {
			$this->current_type='SEARCH';
			$this->current_result=$result;
			$this->current_info=[
				'search_query'=>$query
			];
			return $this;
		}else{
			throw new PageStructurError(static::PAGE_CHANGED);
		}
	}
	public function listSubtitle($permalink)
	{
		$result=[];
		$get_page=$this->makeRequest('/subtitles/'.$permalink);
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
			$this->current_type='LIST_SUBTITLE';
			$this->current_info=[
				'permalink'=>$permalink,
				'title'=>$title,
				'year'=>$year,
				'poster'=>$poster,
				'imdb_id'=>$imdb_id
			];
			$this->current_result=$result;
			return $this;
		}else{
			throw new PageStructurError(static::PAGE_CHANGED);
		}
	}
	public function getSubtitle($permalink,$lang_code,$id)
	{
		$get_page=$this->makeRequest('/subtitles/'.$permalink.'/'.$lang_code.'/'.$id);
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
			$this->current_type='GET_SUBTITLE';
			$this->current_info=[
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
				'comment'=>$comment
			];
			$this->current_result=[
				'preview'=>$preview,
				'details'=>$details,
				'download_link'=>static::BASE_URL.$download_link
			];
			return $this;
		}else{
			throw new PageStructurError(static::PAGE_CHANGED);
		}
	}
	public function smartFilterSeries($language='english',$primary_type=['hdtv'],$option=[])
	{
		if($this->current_type!='LIST_SUBTITLE'){
			throw new FunctionError('The request type before smart filter is must be LIST_SUBTITLE, but your request before is :'.$this->current_type);
		}
		$start=isset($option['start_episode'])?$option['start_episode']:1;
		$end=isset($option['end_episode'])?$option['end_episode']:FALSE;
		$hearing_impaired=isset($option['hearing_impaired'])?$option['hearing_impaired']:FALSE;
		if (!isset($option['season'])) {
			$season_number=$this->seasonNumber($this->current_info['title']);
		}else{
			$season_number=$option['season'];
		}
		$list=$missing_episode=$positive=$neutral=$bad=[];
		foreach ($this->current_result as $rate_filter) {
			if ($rate_filter['hearing_impaired']==$hearing_impaired) {
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
		}
		$result=array_merge($positive,$neutral,$bad);
		for ($i=0; $i < count($result); $i++) { 
			$title=$result[$i]['name'];
			if (preg_match('#S\s*([0-9]+)\s*E\s*([0-9]+)#i', $title, $se)) {
				$season=$se[1];
				$episode=$se[2];
			}elseif (preg_match('#([0-9]+)\s*(\-|x)\s*([0-9]+)#i', $title, $se)) {
				$season=$se[1];
				$episode=$se[3];
			}elseif (preg_match('#Season\s*([0-9]+)\s*Epsoide\s*([0-9]+)\s*#i', $title, $se)) {
				$season=$se[1];
				$episode=$se[2];
			}
			if (isset($title)&&(@$season==$season_number)&&isset($episode)&&(($result[$i]['language']['code']==$language)||($result[$i]['language']['name']==$language))) {
				$list[]=array_merge($result[$i],['season'=>$season,'episode'=>$episode]);
			}
		}
		$newlist=[];
		$loop=$total_episode=$start;
		while ($loop) {
			$internal=[]; 
			foreach ($list as $s) {
				if ($s['episode']==$loop) {
					$internal[]=$s;
				}
			}
			if (!empty($internal)) {
				$got=FALSE;
				if (!empty($primary_type)) {
					foreach ($primary_type as $primary) {
						if (!$got) {
							foreach ($internal as $in) {
								if (preg_match('#'.addslashes($primary).'#i', $in['name'])) {
									$got=TRUE;
									$newlist[$loop]=$in;
									break;
								}
							}
						}
					}
				}
				if (!$got) {
					$newlist[$loop]=$internal[0];
				}
			}else{
				if ($end==FALSE) {
					$loop=FALSE;
				} else {
					$missing_episode[]=$loop;
				}
			}
			if (($end==FALSE&&$loop!=FALSE)||($loop!=FALSE&&$loop<$end)) {
				$total_episode++;
				$loop++;
			}else{
				$total_episode=$total_episode-1;
				$loop=FALSE;
			}
		}
		$this->current_type='SMART_FILTER_SERIES';
		$this->current_info=array_merge($this->current_info,[
			'season'=>$season_number,
			'total_episode'=>$total_episode,
			'language'=>$language,
			'primary_type'=>$primary_type,
			'missing_episode'=>$missing_episode
		]);
		$this->current_result=$newlist;
		return $this;
	}
	public function smartDownloadSeries($folder_target=__DIR__.'/../../../../',$name_format=FALSE)
	{
		if($this->current_type!='SMART_FILTER_SERIES'){
			throw new FunctionError('The request type before smart filter is must be SMART_FILTER_SERIES, but your request before is :'.$this->current_type);
		}
		if (!is_dir(__DIR__.'/tmp')) {
			mkdir(__DIR__.'/tmp');
		}
		if (!is_dir($folder_target)) {
			mkdir($folder_target);
		}
		$backup_current_info=$this->current_info;
		$failed_list=$success_list=[];
		foreach ($this->current_result as $subtitle_list) {
			$permalink=$this->current_info['permalink'];
			$lang_code=$subtitle_list['language']['code'];
			$id=$subtitle_list['id'];
			$single_page=$this->getSubtitle($permalink,$lang_code,$id)->getArray()['result'];
			if ($single_page['download_link']) {
				if(file_put_contents('tmp.zip', $this->makeRequest($single_page['download_link'],FALSE,FALSE))){
					$zip = new \ZipArchive;
					if($zip->open('tmp.zip') === TRUE) {
					   $zip->extractTo(__DIR__.'/tmp');
					   $zip->close();
					}
					foreach (glob(__DIR__.'/tmp/*') as $f) {
						if ($name_format) {
							$ext=pathinfo($f, PATHINFO_EXTENSION);
							$name=sprintf($name_format,$subtitle_list['season'],$subtitle_list['episode']).'.'.$ext;
						}else{
							$name=basename($f);
						}
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
		$this->current_type='SMART_DOWNLOAD_SERIES';
		$this->current_info=$backup_current_info;
		$this->current_result=[
			'folder_target'=>realpath($folder_target),
			'file_failed'=>$failed_list,
			'file_saved'=>$success_list
		];
		return $this;
	}
	public function getJSON()
	{
		$response=[
			'type'=>$this->current_type,
			'info'=>$this->current_info,
			'result'=>$this->current_result
		];
		return json_encode($response);
	}
	public function getArray()
	{
		$response=[
			'type'=>$this->current_type,
			'info'=>$this->current_info,
			'result'=>$this->current_result
		];
		return $response;
	}
}
?>