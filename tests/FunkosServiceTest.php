<?php

use PHPUnit\Framework\TestCase;
use services\FunkosService;
use models\Funko;

class FunkosServiceTest extends TestCase
{
    protected $pdo;
    protected $funkosService;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);

        $this->funkosService = new FunkosService($this->pdo);
    }

    public function testFindAll()
    {
        $expectedData = [
            ['id' => 1, 'uuid' => '7fefb257-2c30-4aea-964c-b3d9fb9ff793', 'name' => 'Funko 1', 'image' => 'images/funkos.bmp', 'description' => 'trsst', 'price' => 100, 'stock' => 10, 'created_at' => '2021-01-01', 'updated_at' => '2021-01-01', 'category_id' => 1, 'category_name' => 'Category 1', 'is_deleted' => false],
            ['id' => 2, 'uuid' => '7fefb257-2c30-4aea-964c-b3d9fb9ff794', 'name' => 'Funko 2', 'image' => 'images/funkos.bmp', 'description' => 'trsst', 'price' => 100, 'stock' => 10, 'created_at' => '2021-01-02', 'updated_at' => '2021-01-02', 'category_id' => 2, 'category_name' => 'Category 2', 'is_deleted' => false],
        ];

        $statement = $this->createMock(PDOStatement::class);
        $statement->method('fetch')->willReturnOnConsecutiveCalls(...$expectedData);
        $this->pdo->method('prepare')->willReturn($statement);

        $result = $this->funkosService->findAllWithCategoryName();

        $this->assertCount(2, $result);
    }


    public function testDeleteById_ExistingFunko_ReturnsTrue()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($statement);

        $result = $this->funkosService->deleteById(1);

        $this->assertTrue($result);
    }

    public function testDeleteById_NonExistingFunko_ReturnsFalse()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(false);
        $this->pdo->method('prepare')->willReturn($statement);

        $result = $this->funkosService->deleteById(999);

        $this->assertFalse($result);
    }

    public function testUpdate_ExistingFunko_ReturnsTrue()
    {
        $funko = new Funko(1, "7fefb257-2c30-4aea-964c-b3d9fb9ff793", "Funko 1", "images/funkos.bmp", 100, 10, "2021-01-01", "2021-01-01", 1, "Category 1", false);

        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($statement);

        $result = $this->funkosService->update($funko);

        $this->assertTrue($result);
    }

    public function testSave_NewFunko_ReturnsTrue()
    {
        $funko = new Funko(null, "7fefb257-2c30-4aea-964c-b3d9fb9ff793", "Funko 1", "images/funkos.bmp", 100, 10, "2021-01-01", "2021-01-01", 1, "Category 1", false);

        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($statement);

        $result = $this->funkosService->save($funko);

        $this->assertTrue($result);
    }
}
