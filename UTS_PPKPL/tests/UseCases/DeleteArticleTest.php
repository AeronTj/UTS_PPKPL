<?php
use PHPUnit\Framework\TestCase;
use App\UseCases\DeleteArticle;
use App\Interfaces\ArticleRepositoryInterface;
use App\Entities\Article;

class DeleteArticleTest extends TestCase
{
    public function testExecuteDeletesArticle()
    {
        $mockRepo = $this->createMock(ArticleRepositoryInterface::class);

        // Mock untuk getById, mengembalikan artikel yang ada
        $mockRepo->expects($this->once())
                 ->method('getById')
                 ->with(1)
                 ->willReturn(new Article(1, 'Old Title', 'Old Content'));

        // Mock untuk delete, mengembalikan true
        $mockRepo->expects($this->once())
                 ->method('delete')
                 ->with(1)
                 ->willReturn(true);

        // Buat instance use case dan eksekusi
        $deleteArticle = new DeleteArticle($mockRepo);
        $result = $deleteArticle->execute(1);

        $this->assertTrue($result);
    }

    public function testExecuteFailsWhenArticleNotFound()
    {
        $mockRepo = $this->createMock(ArticleRepositoryInterface::class);

        // Mock getById untuk mengembalikan null (arti artikel tidak ditemukan)
        $mockRepo->expects($this->once())
                 ->method('getById')
                 ->with(1)
                 ->willReturn(null);

        // Buat instance use case dan eksekusi
        $deleteArticle = new DeleteArticle($mockRepo);

        // Harapkan hasil false karena artikel tidak ditemukan
        $result = $deleteArticle->execute(1);

        $this->assertFalse($result);
    }
}
