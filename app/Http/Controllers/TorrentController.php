<?php

namespace App\Http\Controllers;

use silasmontgomery\QBittorrentWebApi\Api;
use silasmontgomery\YtsApi\Api as SearchApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;

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

        // Closure to get path name in array_amp
        $getName = function ($paths, $path) {
            foreach ($paths as $one) {
                if (trim(strtolower($one['path']), " /") == trim(strtolower($path), " /")) {
                    return $one['name'];
                }
            }
            return '';
        };

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
                'folder' => $torrent->save_path
            ];
        }, $torrent_list);

        return response()->json($torrents);
    }

    /**
     * Update torrent properties
     *
     * @return JsonResponse
     */
    public function update(Request $request, string $hash): JsonResponse
    {
        $torrent = false;
        $torrents = json_decode($this->api->torrentList());
        foreach ($torrents as $one) {
            if ($one->hash == $hash) {
                $torrent = $one;
            }
        }
        
        if (!$torrent) {
            die('Torrent not found.');
        }

        if ($request->path) {
            $path = false;
            $paths = $this->getPaths();
            foreach ($paths as $one) {
                if ($one['name'] == $request->path) {
                    $path = $one['path'];
                }
            }
            if ($path) {
                return response()->json(json_decode($this->api->setTorrentLocation($hash, $path)));
            }
        }

        return response()->json(['success' => false]);
    }

    /**
     * Delete torrent
     *
     * @return JsonResponse
     */
    public function delete(Request $request, string $hash): JsonResponse
    {
        $torrent = false;
        $torrents = json_decode($this->api->torrentList());
        foreach ($torrents as $one) {
            if ($one->hash == $hash) {
                $torrent = $one;
            }
        }
        if (!$torrent) {
            die('Torrent not found.');
        }

        return response()->json(json_decode($this->api->torrentDelete($hash, $request->deleteFiles)));
    }

    /**
     * Search for torrents via torrent search engine
     *
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $request->page ? $page = $request->page : $page = 1;
        $request->limit ? $limit = $request->limit : $limit = 100;

        $apis = $this->getApis();
        $namespace = null;
        foreach ($apis as $api) {
            if ($api['name'] == $request->api) {
                $namespace = $api['namespace'] . '\Api';
            }
        }

        if (empty($namespace)) {
            die("Search API not found.");
        }

        $api = new $namespace;

        return response()->json($api->search($request->q, $page, $limit));
    }
    
    /**
     * Add new torrent
     *
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $results = json_decode($this->api->torrentAdd($request->magnet));

        return response()->json($results);
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

    /**
     * Return the list of torrent APIs from .env
     *
     * @return JsonResponse
     */
    public function searchApis(): JsonResponse
    {
        return response()->json($this->getApis());
    }

    /**
     * Return the list of search APIs from .env
     *
     * @return Array
     */
    private function getApis(): Array
    {
        $api_check = explode(',', env('TORRENT_SEARCH_APIS'))[0];
        if (empty($api_check) || count(explode('=', $api_check)) < 2) {
            die('Missing .env variable TORRENT_SEARCH_APIS (ex: TORRENT_SEARCH_APIS=Name1=namespace1,Name2=namespace2');
        }
        
        $apis = explode(',', env('TORRENT_SEARCH_APIS'));
        $api_arr = [];
        foreach ($apis as $api) {
            if (!empty($api)) {
                list($k, $v) = explode('=', $api);
                $api_arr[] = ['name' => $k, 'namespace' => $v];
            }
        }

        return $api_arr;
    }

    /**
     * Return the list of torrent paths from .env
     *
     * @return Array
     */
    private function getPaths(): array
    {
        $path_check = explode(',', env('TORRENT_PATHS'))[0];
        if (empty($path_check) || count(explode('=', $path_check)) < 2) {
            die('Missing .env variable TORRENT_PATHS (ex: TORRENT_PATHS=Name1=Path1,...,Name2=Path2');
        }
        
        $paths = explode(',', env('TORRENT_PATHS'));
        $paths_arr = [];
        foreach ($paths as $path) {
            if (!empty($path)) {
                list($k, $v) = explode('=', $path);
                $paths_arr[] = ['name' => $k, 'path' => $v];
            }
        }

        return $paths_arr;
    }
}
