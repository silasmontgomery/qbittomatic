# qBittomatic
### A web application for finding and managing torrents.

## Installation

### Clone repo
`git clone https://github.com/silasmontgomery/qbittomatic.git`

### Install dependencies
`composer install`
`npm install`

## Create and edit .env
`cp .env.example .env`
`vim .env`
1. Set QBITTORRENT_URL to the URL of your qBittorrent web interface (including protocol and port, ie: https://some.website.com:8080)
2. Set QBITTORRENT_USERNAME to your qBittorrent web username
3. Set QBITTORRENT_PASSWORD to your qBittorrent web password
4. Set TORRENT_PATHS to the locations of your media/software folders as NAME1=PATH1,NAME2=PATH2 (ie: Movies=/files/movies,Software=/files/Software,...)

## Configure web server
Point your webserver to the public folder

## Security Vulnerabilities

If you discover a security vulnerability within qBittomatic, please send an e-mail to Silas Montgomery at nomsalis@reticent.net. All security vulnerabilities will be promptly addressed.

## License

qBittomatic is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
