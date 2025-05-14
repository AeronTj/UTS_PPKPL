<?php
namespace App\Interfaces;

use App\Entities\Article;

interface ArticleRepositoryInterface {
    public function getAll(): array;
    public function getById(int $id): ?Article;
    public function save(Article $article): bool;
    public function update(Article $article): bool;
    public function delete(int $id): bool;
}
