<?php
namespace App\Entities;

class Article {
    public ?int $id;
    public string $title;
    public string $content;

    public function __construct(?int $id, string $title, string $content) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
    }
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
