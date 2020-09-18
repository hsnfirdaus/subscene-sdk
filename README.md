# Subscene SDK
A php library to getting the subtitle data and files from subscene.com website with smart filter.
## Installation
### Using Composer
It's recomended to install this library by [Composer](https://getcomposer.org/) :
```
composer require "hsnfirdaus/subscene-sdk"
```
### Manual Installation
You can just manually download this repository as zip and extract to your project directory and include the `src/Subscene.php` file.
### Calling the class
You can call this sdk class like this :
```php
require __DIR__ . '/vendor/autoload.php';
$subscene = new Hsnfirdaus\Subscene();
```
### Response
The response of method on this class is an array.

# Usage
- **[Searching Subtitles](#searching-subtitles)**
  - [Parameters](#parameters)
  - [Response](#response)
- **[Listing Subtitles](#listing-subtitles)**
  - [Parameters](#parameters-1)
  - [Response](#response-1)
- **[Getting Subtitle](#getting-subtitle)**
  - [Parameters](#parameters-2)
  - [Response](#response-2)
- **[Smart Filter Series Subtitles](#smart-filter-series-subtitles)**
  - [Parameters](#parameters-3)
  - [Response](#response-3)
- **[Smart Download Series Subtitles](#smart-download-series-subtitles)**
  - [Parameters](#parameters-4)
  - [Response](#response-4)

## Searching Subtitles
```php
$subscene = new Hsnfirdaus\Subscene();
$search = $subscene->search($query);
echo json_encode($search,JSON_PRETTY_PRINT);
```
### Parameters
| Parameter | Type     | Default Value | Details                                             |
| --------- | -------- | ------------- | --------------------------------------------------- |
| `$query`  | `string` | null          | The title for movie/series that you want to search. |
### Response
```json
{
    "type": "search",
    "query": "fbi",
    "result": [
        {
            "permalink": "fbi-most-wanted-season-one",
            "title": "FBI: Most Wanted - First Season",
            "year": null,
            "count": "50",
            "in_heading": "TV-Series"
        },
        {
            "permalink": "fbi-second-season",
            "title": "FBI - Second Season",
            "year": null,
            "count": "108",
            "in_heading": "TV-Series"
        }
    ]
}
```
## Listing Subtitles
```php
$subscene = new Hsnfirdaus\Subscene();
$list_subtitle = $subscene->listSubtitle($permalink);
echo json_encode($list_subtitle,JSON_PRETTY_PRINT);
```
### Parameters
| Parameter     | Type     | Default Value | Details                                                        |
| ------------- | -------- | ------------- | -------------------------------------------------------------- |
| `$permalink`  | `string` | null          | The permalink of page that you will looking for list subtitle. |
### Response
```json
{
    "type": "list_subtitle",
    "permalink": "fbi-most-wanted-season-one",
    "title": "FBI: Most Wanted - First Season",
    "year": "2020",
    "poster": "https:\/\/i.jeded.com\/i\/fbi-most-wanted-season-one.171539.jpg",
    "imdb_id": "tt9742936",
    "result": [
        {
            "id": "2174404",
            "language": {
                "code": "english",
                "name": "English"
            },
            "rate": "neutral",
            "name": "FBI_ Most Wanted - 01x09 - Reveille.AMZN.WEB-DL.NTb.English.C.orig.Addic7ed.com",
            "hearing_impaired": false,
            "user": {
                "id": "816322",
                "name": "newmegadeth"
            },
            "comment": "I just uploaded! hope you enjoy ...39:54&nbsp;"
        },
        {
            "id": "2174400",
            "language": {
                "code": "english",
                "name": "English"
            },
            "rate": "neutral",
            "name": "FBI_ Most Wanted - 01x09 - Reveille.AMZN.WEB-DL.NTb.English.C.orig.Addic7ed.com",
            "hearing_impaired": true,
            "user": {
                "id": "816322",
                "name": "newmegadeth"
            },
            "comment": "I just uploaded! hope you enjoy ...39:54&nbsp;"
        }
    ]
}
```
## Getting Subtitle
```php
$subscene = new Hsnfirdaus\Subscene();
$subtitle_details = $subscene->getSubtitle($permalink,$lang_code,$id);
echo json_encode($subtitle_details,JSON_PRETTY_PRINT);
```
### Parameters
| Parameter     | Type      | Default Value | Details                                                        |
| ------------- | --------- | ------------- | -------------------------------------------------------------- |
| `$permalink`  | `string`  | null          | The permalink of page that you will get the subtitle.          |
| `$lang_code`  | `string`  | null          | The language code of subtitle.                                 |
| `$id`         | `integer` | null          | The id of subtitle.                                            |
### Response
```json
{
    "type": "get_subtitle",
    "permalink": "fbi-most-wanted-season-one",
    "language": {
        "code": "english",
        "name": "English"
    },
    "id": 2174404,
    "poster": "https:\/\/i.jeded.com\/i\/fbi-most-wanted-season-one.171539.jpg",
    "title": "FBI: Most Wanted - First Season",
    "imdb_id": "tt9742936",
    "release_list": [
        "FBI_ Most Wanted - 01x09 - Reveille.AMZN.WEB-DL.NTb.English.C.orig.Addic7ed.com"
    ],
    "user": {
        "id": 0,
        "name": "Anonymous"
    },
    "comment": "I just uploaded! hope you enjoy ...39:54",
    "preview": "1<br \/>00:00:00,000 --&gt; 00:00:05,583<br \/>Synced &amp; corrected by -robtor-<br \/>www.addic7ed.com<br \/><br \/>2<br \/>00:00:07,781 --&gt; 00:00:09,134<br \/>Emma Cain is picking up<br \/><br \/>3<br \/>00:00:09,158 --&gt; 00:00:11,261<br \/>where her husband, Tyler Cain, left off.<br \/><br \/>4<br \/>00:00:11,285 --&gt; 00:00:13,930<br \/>An hour ago, she walked into<br \/>Iglesia De Santa Maria<br \/><br \/>5<br \/>00:00:13,954 --&gt; 00:00:16,177<br \/>in Saugerties and killed 12 women.<br \/><br \/>6<br \/>",
    "details": {
        "online": "3\/28\/2020 3:04 PM &nbsp;",
        "hearing_impaired": "No",
        "foreign_parts": "No",
        "framerate": "Not available",
        "files": "1 (22,191 bytes)",
        "production_type": "Transcript (By listening)",
        "release_type": "Not available",
        "ratings": {
            "score": 0,
            "of": 0,
            "total_user": 0
        },
        "total_download": "206"
    },
    "download_link": "https:\/\/subscene.com\/subtitles\/english-text\/C65oiWI8KIMDuT7fBLXWz5KtmeDVVzwIc52qtGS-AqyGcZq20lZKfUZYUhZz-T4pJk77c7kwsCMa9OQoujTzxEcMXlMpbmyFEHZcfOULXo5AM0fH2v1RfgjVm31abIUN0"
}
```
## Smart Filter Series Subtitles
This method will try looking for all episodes subtitle on the selected series season.
```php
$subscene = new Hsnfirdaus\Subscene();
$list_subtitle = $subscene->listSubtitle('fbi-most-wanted-season-one');
$smart_filter =$subscene->smartFilterSeriesSubtitle($list_subtitle,$series_season,$total_episode,$language,$primary_type);
echo json_encode($smart_filter,JSON_PRETTY_PRINT);
```
### Parameters
| Parameter         | Type      | Default Value | Details                                                              |
| ----------------- | --------- | ------------- | -------------------------------------------------------------------- |
| `$list_subtitle`  | `array`   | null          | The array response from [listSubtitle()](#listing-subtitles) method.   |
| `$series_season`  | `integer` | null          | The season number that you will looking for (example : 2).           |
| `$total_episode`  | `integer` | null          | The total number of that season (example : 14).                      |
| `$language`       | `string`  | english       | The language code/name that you will looking for (example : english).|
| `$primary_type`   | `array`   | null          | Primary subtitle type (example : ['amzn','web','hdtv']).             |
### Response
```json
{
    "type": "smart_filter_series_subtitle",
    "permalink": "fbi-most-wanted-season-one",
    "title": "FBI: Most Wanted - First Season",
    "year": "2020",
    "poster": "https:\/\/i.jeded.com\/i\/fbi-most-wanted-season-one.171539.jpg",
    "imdb_id": "tt9742936",
    "season": 1,
    "total_episode": 14,
    "language": "english",
    "primary_type": [
        "web"
    ],
    "missing_episode": [],
    "result": {
        "1": {
            "id": "2121883",
            "language": {
                "code": "english",
                "name": "English"
            },
            "rate": "positive",
            "name": "FBI.Most.Wanted.S01E01.HDTV.x264-SVA",
            "hearing_impaired": false,
            "user": {
                "id": "1206457",
                "name": "_ViSHAL_"
            },
            "comment": "Synced and Corrected by robtor - www.addic7ed.com &nbsp;",
            "season": "01",
            "episode": "01"
        },
        //Until last episode
        "14": {
            "id": "2208141",
            "language": {
                "code": "english",
                "name": "English"
            },
            "rate": "positive",
            "name": "FBI.Most.Wanted.S01E14.HDTV.x264-KILLERS",
            "hearing_impaired": false,
            "user": {
                "id": "1206851",
                "name": "Flatto"
            },
            "comment": "*Season Finale* \/ Non-HI \/ Duration 42:18 \/ Credits to Firefly (addic7ed.com)&nbsp;",
            "season": "01",
            "episode": "14"
        }
    }
}
```
## Smart Download Series Subtitles
This method will try downloaded all subtitles and unzip, move and rename it.
```php
$subscene = new Hsnfirdaus\Subscene();
$list_subtitle = $subscene->listSubtitle('fbi-most-wanted-season-one');
$smart_filter = $subscene->smartFilterSeriesSubtitle($list_subtitle,1,14,'english',['web']);
$smart_download = $subscene->smartDownloadSeriesSubtitle($smart_filter,$folder_target);
echo json_encode($smart_download,JSON_PRETTY_PRINT);
```
### Parameters
| Parameter         | Type      | Default Value        | Details                                                                                         |
| ----------------- | --------- | -------------        | ----------------------------------------------------------------------------------------------- |
| `$smart_filter`   | `array`   | null                 | The array response from [smartFilterSeriesSubtitle()](#smart-filter-series-subtitles) method.   |
| `$folder_target`  | `string`  | ```__DIR__.'/../'``` | The target folder that downloaded srt subtitle will saved.                                      |
### Response
```json
{
    "type": "smart_download_series_subtitle",
    "folder_target": "E:\\subscene-sdk\\src\\subtitle",
    "season": 1,
    "missing_episode": [],
    "failed_episode": [],
    "saved_file": [
        "fbi--most-wanted---first-season - S01E01.srt",
        "fbi--most-wanted---first-season - S01E02.srt",
        "fbi--most-wanted---first-season - S01E03.srt",
        "fbi--most-wanted---first-season - S01E04.srt",
        "fbi--most-wanted---first-season - S01E05.srt",
        "fbi--most-wanted---first-season - S01E06.srt",
        "fbi--most-wanted---first-season - S01E07.srt",
        "fbi--most-wanted---first-season - S01E08.srt",
        "fbi--most-wanted---first-season - S01E09.srt",
        "fbi--most-wanted---first-season - S01E10.srt",
        "fbi--most-wanted---first-season - S01E11.srt",
        "fbi--most-wanted---first-season - S01E12.srt",
        "fbi--most-wanted---first-season - S01E13.srt",
        "fbi--most-wanted---first-season - S01E14.srt"
    ]
}
```