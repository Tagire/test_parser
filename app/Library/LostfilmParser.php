<?php
namespace App\Library;

use App\Models\Log;
use GuzzleHttp;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Sunra\PhpSimple\HtmlDomParser;

class LostfilmParser
{
    const DOMAIN = 'https://www.lostfilm.tv';
    const PARSE_URL = '/new/page_';

    public function parse($page = 1)
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', self::DOMAIN . self::PARSE_URL . $page);
        if ($res->getStatusCode() !== Response::HTTP_OK) {
            throw new \Exception(implode("\n", [
                'Parsing problem, page not found',
                $res->getStatusCode(),
                $res->getBody()
            ]));
        }

        $dom = HtmlDomParser::str_get_html($res->getBody()->getContents());
        $episodesList = [];
        $episodesDom = $dom->find('div[class=row]');

        $getDateFromString = function ($string) {
            preg_match('((0[1-9]|[12]\d|3[01])\.(0[1-9]|1[0-2])\.[12]\d{3})', $string, $result);
            return $result[0];
        };

        try {
            foreach ($episodesDom as $episodeDom) {
                $episodesList[] = [
                    'series_name' => $episodeDom->find('div[class=name-ru]')[0]->text(),
                    'episode_name_ru' => $episodeDom->find('div[class=alpha]')[0]->text(),
                    'episode_name_en' => $episodeDom->find('div[class=beta]')[0]->text(),
                    'release_date_ru' => $getDateFromString($episodeDom->find('div[class=alpha]')[1]->text()),
                    'release_date_en' => $getDateFromString($episodeDom->find('div[class=beta]')[1]->text()),
                    'details_link' => self::DOMAIN . $episodeDom->find('a')[0]->attr['href'],
                ];
            }
        } catch (\Exception $e) {
            throw new \Exception(implode("\n", [
                'Parsing problem, page format changed',
                $e->getMessage(),
                $res->getBody()
            ]));
        }

        return $episodesList;
    }
}
