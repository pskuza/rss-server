<?php
declare(strict_types=1);

namespace pskuza\rss;
use Doctrine\Common\Cache\ApcuCache;

class server
{
    protected $db;
    protected $cache;
    public $posts;

    public function __construct(string $db)
    {
        try {
            $this->db = \ParagonIE\EasyDB\Factory::create(
                'sqlite:' . $db
            );
        } catch (\Exception $e) {
            $this->error(500, 'No database connection.');
        }

        try {
            $this->cache = new ApcuCache();
        } catch (\Exception $e) {
            $this->error(500, 'Could not create cache.');
        }

        $this->posts = new posts($this->db, $this->cache);
    }

    public function error(int $http_code, string $error_message) {
        http_response_code($http_code);
        error_log($error_message, 0);
        die($error_message);
    }

    public function success(int $http_code = 200, string $message) {
        http_response_code($http_code);
        die($message);
    }
}