<?php

use PHPUnit\Framework\TestCase;
use Infrastructure\Database\ArticleRepository;
use App\Entities\Article;
use PDO;

class ArticleRepositoryTest extends TestCase
{
    private PDO $pdo;
    private ArticleRepository $repo;

    protected function setUp(): void
    {
        // Setup PDO untuk database
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec("CREATE TABLE articles (id INTEGER PRIMARY KEY, title TEXT, content TEXT)");
        $this->repo = new ArticleRepository($this->pdo);
    }

    public function testSaveCreatesArticle()
    {
        $article = new Article(null, 'Test Title', 'Test Content');
        $this->repo->save($article);

        // Verifikasi artikel telah disimpan
        $stmt = $this->pdo->query("SELECT * FROM articles WHERE title = 'Test Title'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($result);
        $this->assertEquals('Test Title', $result['title']);
    }

    public function testUpdateArticle()
    {
        // Simpan artikel
        $article = new Article(null, 'Old Title', 'Old Content');
        $this->repo->save($article);

        // Perbarui artikel
        $article->title = 'Updated Title';
        $article->content = 'Updated Content';
        $this->repo->update($article);

        // Verifikasi update
        $stmt = $this->pdo->query("SELECT * FROM articles WHERE title = 'Updated Title'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($result);
        $this->assertEquals('Updated Title', $result['title']);
    }

    public function testDeleteArticle()
    {
        // Simpan artikel
        $article = new Article(null, 'To Delete', 'Content to delete');
        $this->repo->save($article);

        // Hapus artikel
        $this->repo->delete($article->id);

        // Verifikasi artikel dihapus
        $stmt = $this->pdo->query("SELECT * FROM articles WHERE title = 'To Delete'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEmpty($result);
    }
}
