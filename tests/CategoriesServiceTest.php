<?php

use models\Category;
use PHPUnit\Framework\TestCase;
use services\CategoriesService;

class CategoriesServiceTest extends TestCase
{
    protected $pdo;
    protected $categoriesService;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->categoriesService = new CategoriesService($this->pdo);
    }

    public function testFindAll()
    {
        $expectedData = [
            ['id' => 1, 'name' => 'Category 1', 'created_at' => '2022-01-01', 'updated_at' => '2022-01-01', 'is_deleted' => false],
            ['id' => 2, 'name' => 'Category 2', 'created_at' => '2022-01-02', 'updated_at' => '2022-01-02', 'is_deleted' => false],
        ];

        $statement = $this->createMock(PDOStatement::class);
        $statement->method('fetch')->willReturnOnConsecutiveCalls(...$expectedData);
        $this->pdo->method('prepare')->willReturn($statement);

        $result = $this->categoriesService->findAll();

        $this->assertCount(2, $result);
    }

    public function testFindById_NonExistingCategory_ReturnsFalse()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('fetch')->willReturn(false);
        $this->pdo->method('prepare')->willReturn($statement);

        $category = $this->categoriesService->findById(999);

        $this->assertFalse($category);
    }

    public function testDeleteById_ExistingCategory_ReturnsTrue()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($statement);

        $result = $this->categoriesService->deleteById(1);

        $this->assertTrue($result);
    }

    public function testDeleteById_NonExistingCategory_ReturnsFalse()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(false);
        $this->pdo->method('prepare')->willReturn($statement);

        $result = $this->categoriesService->deleteById(999);

        $this->assertFalse($result);
    }

    //test de update
    public function testUpdate_ExistingCategory_ReturnsTrue()
    {
        $category = new Category(1, 'Category 1', '2022-01-01', '2022-01-01', false);

        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($statement);

        $result = $this->categoriesService->update($category);

        $this->assertTrue($result);
    }

    //test de save
    public function testSave_NewCategory_ReturnsTrue()
    {
        $category = new Category(null, 'Category 1', '2022-01-01', '2022-01-01', false);

        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($statement);

        $result = $this->categoriesService->save($category);

        $this->assertTrue($result);
    }

}
