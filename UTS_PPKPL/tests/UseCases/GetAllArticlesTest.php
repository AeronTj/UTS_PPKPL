<?php
use PHPUnit\Framework\TestCase;
use App\UseCases\GetAllArticles;
use App\Entities\Article;  
use App\Interfaces\ArticleRepositoryInterface;

class GetAllArticlesTest extends TestCase
{
    public function testExecuteGetsAllArticles()
    {
        
        $mockRepo = $this->createMock(ArticleRepositoryInterface::class);

        $mockRepo->expects($this->once())
                 ->method('getAll')
                 ->willReturn([
                     new Article(1, 'Title 1', 'Content 1'),
                     new Article(2, 'Title 2', 'Content 2')
                 ]);

        $getAllArticles = new GetAllArticles($mockRepo);
        $articles = $getAllArticles->execute();

        $this->assertCount(2, $articles);
    }

    public function testExecuteHandlesDatabaseError()
    {
        $mockRepo = $this->createMock(ArticleRepositoryInterface::class);

        $mockRepo->expects($this->once())
                 ->method('getAll')
                 ->will($this->throwException(new \Exception("Database error")));

        $getAllArticles = new GetAllArticles($mockRepo);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Database error");

        $getAllArticles->execute();
    }
}
