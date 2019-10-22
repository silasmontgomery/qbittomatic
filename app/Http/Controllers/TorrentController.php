<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use silasmontgomery\QBittorrentWebApi\Api;

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
     * @return json
     */
    public function index()
    {
        return $this->api->torrentList();
        /*
        return response()->json([
            [
                'name' => 'File 1',
                'status' => 'Downloading'
            ],
            [
                'name' => 'File 2',
                'status' => 'Downloading'
            ],
            [
                'name' => 'File 3',
                'status' => 'Completed'
            ],
        ]);
        */
    }
}
