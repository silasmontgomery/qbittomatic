<?php

namespace App\Http\Controllers;

use silasmontgomery\QBittorrentWebApi\Api;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Resources\TorrentResource;

class TorrentController extends Controller
{
    private $api;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $qbt_url = env('QBITTORRENT_URL');
        $qbt_username = env('QBITTORRENT_USERNAME');
        $qbt_password = env('QBITTORRENT_PASSWORD');

        if (empty($qbt_url) || empty($qbt_username) || empty($qbt_password)) {
            die("Missing .env values QBITTORRENT_URL, QBITTORRENT_USERNAME, or QBITTORRENT_PASSWORD");
        }

        $this->api = new Api($qbt_url, $qbt_username, $qbt_password);
    }

    /**
     * Return a list of torrents from qBittorrent API
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $paths = $this->getPaths();

        $getName = function ($paths, $path) {
            foreach ($paths as $one) {
                if (trim(strtolower($one['path'])) == trim(strtolower($path))) {
                    return $one['name'];
                }
            }
            return '';
        };

        //die(var_dump($getName($paths, "/files/Downloads")));

        $torrent_list = json_decode($this->api->torrentList());
        $torrents = array_map(function ($torrent) use ($paths, $getName) {
            return [
                'name' => $torrent->name,
                'state' => $torrent->state,
                'hash' => $torrent->hash,
                'size' => $torrent->total_size,
                'downloaded' => $torrent->completed,
                'completed' => ($torrent->completed / $torrent->total_size) * 100,
                'ratio' => $torrent->ratio,
                'dl_speed' => $torrent->dlspeed,
                'up_speed' => $torrent->upspeed,
                'path' => $getName($paths, $torrent->save_path),
            ];
        }, $torrent_list);
        

        return response()->json($torrents);
        //return response()->json($torrent_list);
    }

    /**
     * Return the list of torrent paths from .env
     *
     * @return JsonResponse
     */
    public function paths(): JsonResponse
    {
        return response()->json($this->getPaths());
    }

    private function getPaths(): array
    {
        $paths = explode(',', env('TORRENT_PATHS'));
        $paths_arr = [];
        foreach ($paths as $path) {
            list($k, $v) = explode('=', $path);
            $paths_arr[] = ['name' => $k, 'path' => $v];
        }

        return $paths_arr;
    }
}
