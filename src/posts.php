<?php
declare(strict_types=1);

namespace pskuza\rss;

use Webmozart\Assert\Assert;

class posts
{
    protected $db;
    protected $cache;

    public function __construct(\ParagonIE\EasyDB\EasyDB $db, \Doctrine\Common\Cache\ApcuCache $cache)
    {
        $this->db = $db;
        $this->cache = $cache;
    }

    public function getAllPosts(): array
    {
        //for now
        return [true, 200, "getall was called"];
    }

    public function addPost(): array
    {
        $date = new DateTime();

        $data = $this->json_decode();

        Assert::notEmpty($data['title'], 'title is empty.');
        Assert::maxLength($data['title'], 64, 'title is too long.');

        Assert::notEmpty($data['link'], 'link is empty.');
        Assert::maxLength($data['link'], 256, 'link is too long.');

        Assert::notEmpty($data['description'], 'description is empty.');
        Assert::maxLength($data['description'], 1024, 'description is too long.');

        if ($this->db->insert('posts', [
            'title' => $data['title'],
            'link' => $data['link'],
            'description' => $data['description'],
            'date' => $date->getTimestamp(),
        ])) {
            return [true, 201, 'OK.'];
        }
        return [false, 500, 'Could not create post.'];

    }

    public function editPost(): array
    {

    }

    private function json_decode(int $depth = 2): array
    {
        $data = json_decode(file_get_contents('php://input'), true, $depth);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(
                'Could not decode JSON: ' . json_last_error_msg()
            );
        }
        return $data;
    }

}