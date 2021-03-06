# qBittomatic
### A web application for finding and managing torrents.

## Installation

### Clone repo
`git clone https://github.com/silasmontgomery/qbittomatic.git`

### Install dependencies
1. `composer install`
2. `npm install`

## Create and edit .env
1. `cp .env.example .env`
2. `vim .env`
3. Set `QBITTORRENT_URL=` to the URL of your qBittorrent web interface (including protocol and port, ie: https://some.website.com:8080)
4. Set `QBITTORRENT_USERNAME=` to your qBittorrent web username
5. Set `QBITTORRENT_PASSWORD=` to your qBittorrent web password
6. Set `TORRENT_PATHS=` to the locations of your media/software folders as NAME1=PATH1,NAME2=PATH2 (ie: Movies=/files/movies,Software=/files/Software,...)

## Configure web server
Point your webserver to the public folder

## TODO
1. Add authentication
2. Add support for additional torrent search engines
3. Add additional torrent management functionality (pause ul/dl, throttle ul/dl speeds, set share ratio, move/delete on complete, etc)

## License
qBittomatic is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
