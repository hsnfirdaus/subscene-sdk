# Subscene SDK
A php library to getting the subtitle data and files from subscene.com website with smart filter.
## Installation
### Using Composer
It's recomended to install this library by [Composer](https://getcomposer.org/) :
```
composer require "hsnfirdaus/subscene-sdk"
```
or you can just manually download this repository as zip and extract to your project directory.

### Notice
If you are using the V1 of this library, please read the (README.v1.md)

# Usage
## Calling Class
To begin, just require the autoloader of composer :
```php
require __DIR__ . '/vendor/autoload.php';
```
or if you download this script manually, require the `/src/Subscene.php` file :
```php
require __DIR__ . '/subscene-sdk/src/Subscene.php';
```
then you just call the Subscene class like this :
```
$subscene = new Hsnfirdaus\Subscene();
```
## Getting Result
This class support to get result as Array or JSON. Just call getArray() method or getJSON() method.
All method will response three object/array : type,info, and result.
## Error Handling
You can use `try{ } catch() { }` Exception to handling error.
| Exception Name     | Description               |
|--------------------|---------------------------|
|`CURLError`         | Something wrong with cURL |
|`PageStructurError` | Cannot match content from cURL Result, maybe subscene page structure has been changed. |
|`FunctionError`     | Error when running method, maybe you wrong call the method. |
## List Of Method
- **[search](#search)**
  - [Example Request](#example-request)
  - [Parameters](#parameters)
  - [Example Response](#example-response)
- **[listSubtitle](#listSubtitle)**
  - [Example Request](#example-request)
  - [Parameters](#parameters)
  - [Example Response](#example-response)
- **[getSubtitle](#getSubtitle)**
  - [Example Request](#example-request)
  - [Parameters](#parameters)
  - [Example Response](#example-response)
- **[smartFilterSeries](#smartFilterSeries)**
  - [Example Request](#example-request)
  - [Parameters](#parameters)
  - [Example Response](#example-response)
- **[smartDownloadSeries](#smartDownloadSeries)**
  - [Example Request](#example-request)
  - [Parameters](#parameters)
  - [Example Response](#example-response)
### Search
This method is using to search movie or series that subtitle is available in subscene.
#### Example Request
```php
$subscene = new Hsnfirdaus\Subscene();
$search = $subscene->search($query);
print_r($search->getArray());
```
#### Parameters
| Parameter | Type     | Default Value | Details                                             |
| --------- | -------- | ------------- | --------------------------------------------------- |
| `$query`  | `string` | null          | The title for movie/series that you want to search. |
#### Example Response
Example ```$subscene->search('ncis')->getArray()``` response :
```php
Array
(
    [type] => SEARCH
    [info] => Array
        (
            [search_query] => ncis
        )

    [result] => Array
        (
            [0] => Array
                (
                    [permalink] => ncis-los-angeles-twelfth-season
                    [title] => NCIS: Los Angeles - Twelfth Season
                    [year] => 
                    [count] => 27
                    [in_heading] => TV-Series
                    [season] => 12
                )

            [1] => Array
                (
                    [permalink] => ncis-new-orleans-seventh-season
                    [title] => NCIS: New Orleans - Seventh Season
                    [year] => 
                    [count] => 11
                    [in_heading] => TV-Series
                    [season] => 7
                )

            [2] => Array
                (
                    [permalink] => ncis-eighteenth-season
                    [title] => NCIS: Naval Criminal Investigative Service (Navy CIS) - Eighteenth Season
                    [year] => 
                    [count] => 37
                    [in_heading] => TV-Series
                    [season] => 18
                )
        )

)
```
### listSubtitle
This method is using to listing subtitle from specific permalink.
#### Example Request
```php
$subscene = new Hsnfirdaus\Subscene();
$listSubtitle = $subscene->listSubtitle($permalink);
print_r($listSubtitle->getArray());
```
#### Parameters
| Parameter     | Type     | Default Value | Details                                                        |
| ------------- | -------- | ------------- | -------------------------------------------------------------- |
| `$permalink`  | `string` | null          | The permalink of page that you will looking for list subtitle. |
#### Example Response
Example ```$subscene->listSubtitle('ncis-eighteenth-season')->getArray()``` response :
```php
Array
(
    [type] => LIST_SUBTITLE
    [info] => Array
        (
            [permalink] => ncis-eighteenth-season
            [title] => NCIS: Naval Criminal Investigative Service (Navy CIS) - Eighteenth Season
            [year] => 2020
            [poster] => https://i.jeded.com/i/ncis-eighteenth-season.196526.jpg
            [imdb_id] => tt0364845
        )

    [result] => Array
        (
            [0] => Array
                (
                    [id] => 2391386
                    [language] => Array
                        (
                            [code] => english
                            [name] => English
                        )

                    [rate] => positive
                    [name] => NCIS.S18E07.WEBRip.x264-ION10
                    [hearing_impaired] => 1
                    [user] => Array
                        (
                            [id] => 1068258
                            [name] => M_I_S
                        )

                    [comment] => Hi:WebRip/WebDl:Duration : 43 min 14 s&nbsp;
                )

            [1] => Array
                (
                    [id] => 2391385
                    [language] => Array
                        (
                            [code] => english
                            [name] => English
                        )

                    [rate] => positive
                    [name] => NCIS.S18E07.WEBRip.x264-ION10
                    [hearing_impaired] => 
                    [user] => Array
                        (
                            [id] => 1068258
                            [name] => M_I_S
                        )

                    [comment] => Non Hi:WebRip/WebDl:Duration : 43 min 14 s
&nbsp;
                )

            [2] => Array
                (
                    [id] => 2391412
                    [language] => Array
                        (
                            [code] => english
                            [name] => English
                        )

                    [rate] => neutral
                    [name] => NCIS.S18E07.The.First.Day.720p.AMZN.WEB-DL.DDP5.1.H.264-NTb
                    [hearing_impaired] => 
                    [user] => Array
                        (
                            [id] => 1358973
                            [name] => SoftSpotForBoobies
                        )

                    [comment] => A Amazon Original Subtitle &nbsp;
                )

            [3] => Array
                (
                    [id] => 2391412
                    [language] => Array
                        (
                            [code] => english
                            [name] => English
                        )

                    [rate] => neutral
                    [name] => NCIS.S18E07.The.First.Day.1080p.AMZN.WEB-DL.DDP5.1.H.264-NTb
                    [hearing_impaired] => 
                    [user] => Array
                        (
                            [id] => 1358973
                            [name] => SoftSpotForBoobies
                        )

                    [comment] => A Amazon Original Subtitle &nbsp;
                )

        )

)
```
### getSubtitle
This method is using to generate download link and information of specific subtitle.
#### Example Request
```php
$subscene = new Hsnfirdaus\Subscene();
$getSubtitle = $subscene->getSubtitle($permalink,$lang_code,$id)
print_r($getSubtitle->getArray());
```
#### Parameters
| Parameter     | Type      | Default Value | Details                                                        |
| ------------- | --------- | ------------- | -------------------------------------------------------------- |
| `$permalink`  | `string`  | null          | The permalink of page that you will get the subtitle.          |
| `$lang_code`  | `string`  | null          | The language code of subtitle.                                 |
| `$id`         | `integer` | null          | The id of subtitle.                                            |
#### Example Response
Example ```$subscene->getSubtitle('ncis-eighteenth-season','english',2391412)->getArray()``` response :
```php
Array
(
    [type] => GET_SUBTITLE
    [info] => Array
        (
            [permalink] => ncis-eighteenth-season
            [language] => Array
                (
                    [code] => english
                    [name] => English
                )

            [id] => 2391412
            [poster] => https://i.jeded.com/i/ncis-eighteenth-season.196526.jpg
            [title] => NCIS: Naval Criminal Investigative Service (Navy CIS) - Eighteenth Season
            [imdb_id] => tt0364845
            [release_list] => Array
                (
                    [0] => NCIS.S18E07.The.First.Day.1080p.AMZN.WEB-DL.DDP5.1.H.264-NTb
                    [1] => NCIS.S18E07.The.First.Day.720p.AMZN.WEB-DL.DDP5.1.H.264-NTb
                )

            [user] => Array
                (
                    [id] => 0
                    [name] => Anonymous
                )

            [comment] => A Amazon Original Subtitle
        )

    [result] => Array
        (
            [preview] => 1<br />00:00:05,135 --&gt; 00:00:08,269<br />You know what 4:00 a.m.<br />in February feels like, Ronnie?<br /><br />2<br />00:00:08,312 --&gt; 00:00:11,924<br />And I&#39;m out here chasing a cat<br />in a coal mine.<br /><br />3<br />00:00:11,968 --&gt; 00:00:14,144<br />RONNIE:<br />All I do is take the info down,<br />pass it along.<br /><br />4<br />00:00:14,188 --&gt; 00:00:17,234<br />Well, next time, verify the GPS.<br /><br />5<br />00:00:17,278 --&gt; 00:00:20,759<br />
            [details] => Array
                (
                    [online] => 2/10/2021 8:16 AM &nbsp; 1 days ago
                    [hearing_impaired] => No
                    [foreign_parts] => No
                    [framerate] => Not available
                    [files] => 1 (26,329 bytes)
                    [production_type] => From retail
                    [release_type] => Web
                    [ratings] => Array
                        (
                            [score] => 0
                            [of] => 0
                            [total_user] => 0
                        )

                    [total_download] => 156
                )

            [download_link] => https://subscene.com/subtitles/english-text/eEahuEBLjo_jAxOXb7uqIn-HDlNNckpyomovJYazgItv0-TbEJV0AwOdhVnI6NAAGwpKKQV7E1miRitP2gZSPwa9_WM8qD0dd9SgZ53iud-8nZfvXYMEPdFuZcot43gw0
        )

)
```
### smartFilterSeries
This method is using to smart filtering result from [listSubtitle](#listSubtitle), to get episode from specific release from tv series.
#### Example Request
```php
$subscene = new Hsnfirdaus\Subscene();
$listSubtitle=$subscene->listSubtitle('ncis-eighteenth-season');
$filter = $listSubtitle->smartFilterSeries($language,$primary_type,$option);
print_r($search->getArray());
```
#### Parameters
| Parameter         | Type      | Default Value | Details                                                              |
| ----------------- | --------- | ------------- | -------------------------------------------------------------------- |
| `$language`       | `string`  | english       | The language code/name that you will looking for (example : english).|
| `$primary_type`   | `array`   | `['hdtv']`    | Primary subtitle type (example : ['amzn','web','hdtv']).           |
| `$option`         | `array`   | null          | Optional Option :<br/>`season` : Season number of series, default will getting season from title.<br/>`start_episode` : Start episode that will looking for, default : 1.<br/>`end_episode` : End episode that will looking for, default is loop until end.<br/>`hearing_impaired` : Is looking for hi subtitle? set true or false, default : false.|
#### Example Response
Example :
```php
$listSubtitle=$subscene->listSubtitle('ncis-eighteenth-season');
$filter=$listSubtitle->smartFilterSeries('english',['amzn','web'],[
    'start_episode'=>1,
    'end_episode'=>3,
    'hearing_impaired'=>FALSE
]);
print_r($filter->getArray());
```
Response :
```php
Array
(
    [type] => SMART_FILTER_SERIES
    [info] => Array
        (
            [permalink] => ncis-eighteenth-season
            [title] => NCIS: Naval Criminal Investigative Service (Navy CIS) - Eighteenth Season
            [year] => 2020
            [poster] => https://i.jeded.com/i/ncis-eighteenth-season.196526.jpg
            [imdb_id] => tt0364845
            [season] => 18
            [total_episode] => 2
            [language] => english
            [primary_type] => Array
                (
                    [0] => amzn
                    [1] => web
                )

            [missing_episode] => Array
                (
                )

        )

    [result] => Array
        (
            [1] => Array
                (
                    [id] => 2335928
                    [language] => Array
                        (
                            [code] => english
                            [name] => English
                        )

                    [rate] => positive
                    [name] => NCIS.S18E01.Sturgeon.Season.720p.AMZN.WEBRip.DDP5.1.x264-NTb.41 min 28
                    [hearing_impaired] => 
                    [user] => Array
                        (
                            [id] => 1068258
                            [name] => M_I_S
                        )

                    [comment] => Hi MRD :Official:WebRip&amp;webDl:41:28&nbsp;
                    [season] => 18
                    [episode] => 01
                )

            [2] => Array
                (
                    [id] => 2340604
                    [language] => Array
                        (
                            [code] => english
                            [name] => English
                        )

                    [rate] => positive
                    [name] => ncis.s18e02.1080p.web.h264-ggwp
                    [hearing_impaired] => 
                    [user] => Array
                        (
                            [id] => 1221159
                            [name] => class123
                        )

                    [comment] => class123&nbsp;
                    [season] => 18
                    [episode] => 02
                )

            [3] => Array
                (
                    [id] => 2351225
                    [language] => Array
                        (
                            [code] => english
                            [name] => English
                        )

                    [rate] => neutral
                    [name] => NCIS.S18E03.iNTERNAL.1080p.WEB.h264-KOGi
                    [hearing_impaired] => 
                    [user] => Array
                        (
                            [id] => 1221159
                            [name] => class123
                        )

                    [comment] => class123&nbsp;
                    [season] => 18
                    [episode] => 03
                )

        )

)
```
### smartDownloadSeries
This method is try to download and unzip subtitle from [smartFilterSeries](#smartFilterSeries) result.
#### Example Request
```php
$subscene = new Hsnfirdaus\Subscene();
$listSubtitle=$subscene->listSubtitle('ncis-eighteenth-season');
$filter=$listSubtitle->smartFilterSeries('english',['amzn','web'],[
    'start_episode'=>1,
    'end_episode'=>3,
    'hearing_impaired'=>FALSE
]);
$download=$filter->smartDownloadSeries($folder_target,$name_format);
print_r($download->getArray());
```
#### Parameters
| Parameter         | Type      | Default Value        | Details                                                                                         |
| ----------------- | --------- | -------------        | ----------------------------------------------------------------------------------------------- |
| `$folder_target`  | `string`  | `__DIR__.'/../../../../` | The target folder that downloaded srt subtitle will saved. |
| `$name_format`    | `string`  | Rename subtitle to this string, example : 'NCIS - S%sE%s' |            
#### Example Response
Example :
```php
$listSubtitle=$subscene->listSubtitle('ncis-eighteenth-season');
$filter=$listSubtitle->smartFilterSeries('english',['amzn','web'],[
    'start_episode'=>1,
    'end_episode'=>3,
    'hearing_impaired'=>FALSE
]);
$download=$filter->smartDownloadSeries(__DIR__,'NCIS - S%sE%s');
print_r($filter->getArray());
```
Response :
```php
Array
(
    [type] => SMART_DOWNLOAD_SERIES
    [info] => Array
        (
            [permalink] => ncis-eighteenth-season
            [title] => NCIS: Naval Criminal Investigative Service (Navy CIS) - Eighteenth Season
            [year] => 2020
            [poster] => https://i.jeded.com/i/ncis-eighteenth-season.196526.jpg
            [imdb_id] => tt0364845
            [season] => 18
            [total_episode] => 2
            [language] => english
            [primary_type] => Array
                (
                    [0] => amzn
                    [1] => web
                )

            [missing_episode] => Array
                (
                )

        )

    [result] => Array
        (
            [folder_target] => /htdocs/subtitle
            [file_failed] => Array
                (
                )

            [file_saved] => Array
                (
                    [0] => NCIS - S18E01.srt
                    [1] => NCIS - S18E02.srt
                    [2] => NCIS - S18E03.srt
                )

        )

)
```