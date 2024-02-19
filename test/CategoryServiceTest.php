<?php
use PHPUnit\Framework\TestCase;
use services\CategoriaService;
use models\Categoria;
require_once __DIR__ . '/../src/services/CategoriaService.php';
class CategoryServiceTest extends TestCase
{
    private $pdo;
    private $categoriasService;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->categoriasService = new CategoriaService($this->pdo);
    }

    public function testFindAll()
    {
        $categoria1 = new Categoria("1", "Test Categoria 1", date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), false);
        $categoria2 = new Categoria("2", "Test Categoria 2", date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), false);

        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->exactly(3))
            ->method('fetch')
            ->willReturnOnConsecutiveCalls(
                ['id' => '1', 'name_category' => 'Test Categoria 1', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'), 'is_deleted' => false],
                ['id' => '2', 'name_category' => 'Test Categoria 2', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'), 'is_deleted' => false],
                false
            );

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $categorias = $this->categoriasService->findAll();

        $this->assertIsArray($categorias);

        foreach ($categorias as $categoria) {
            $this->assertInstanceOf(Categoria::class, $categoria);
        }

        $this->assertEquals($categoria1->getId(), $categorias[0]->getId());
        $this->assertEquals($categoria1->nameCategory, $categorias[0]->nameCategory);
        $this->assertEquals($categoria1->isDeleted, $categorias[0]->isDeleted);

        $this->assertEquals($categoria2->getId(), $categorias[1]->getId());
        $this->assertEquals($categoria2->nameCategory, $categorias[1]->nameCategory);
        $this->assertEquals($categoria2->isDeleted, $categorias[1]->isDeleted);
    }

    public function testFindById()
    {
        $categoria = new Categoria("1", "Test Categoria 1", date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), false);

        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'id' => $categoria->getId(),
                'name_category' => $categoria->nombre,
                'created_at' => $categoria->createdAt,
                'updated_at' => $categoria->updatedAt,
                'is_deleted' => $categoria->isDeleted,
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $resultCategoria = $this->categoriasService->findById("1");

        $this->assertInstanceOf(Categoria::class, $resultCategoria);
        $this->assertEquals($categoria->getId(), $resultCategoria->getId());
    }

    public function testFindByIdNotFound()
    {
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn([]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $resultCategoria = $this->categoriasService->findById("1");

        $this->assertNull($resultCategoria);
    }

    public function testFindByName()
    {
        $categoriaName = "Test Categoria 1";

        $categoria = new Categoria("1", $categoriaName, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), false);

        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'id' => $categoria->getId(),
                'name_category' => $categoria->nameCategory,
                'created_at' => $categoria->createdAt,
                'updated_at' => $categoria->updatedAt,
                'is_deleted' => $categoria->isDeleted,
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $resultCategoria = $this->categoriasService->findByName($categoriaName);

        $this->assertInstanceOf(Categoria::class, $resultCategoria);
        $this->assertEquals($categoria->getId(), $resultCategoria->getId());
        $this->assertEquals($categoria->nameCategory, $resultCategoria->nameCategory);
    }

    public function testFindByNameNotFound()
    {
        $categoriaName = "TestCategoryNotFound";

        // Mock de PDOStatement
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn([]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $resultCategoria = $this->categoriasService->findByName($categoriaName);

        $this->assertFalse($resultCategoria);
    }


    public function testDeleteByIdSuccess()
    {
        $categoriaId = "1";

        $stmt1 = $this->createMock(PDOStatement::class);

        $stmt1->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt1);


        $result = $this->categoriasService->deleteById($categoriaId);

        $this->assertTrue($result);
    }

    public function testDeleteByIdWithAssociatedFunkos()
    {
        $categoriaId = "1";

        $stmt1 = $this->createMock(PDOStatement::class);

        $stmt1->expects($this->once())
            ->method('execute')
            ->willReturn(false);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt1);

        $result = $this->categoriasService->deleteById($categoriaId);

        $this->assertFalse($result);
    }


    public function testUpdateWithExistingCategoryName()
    {
        $stmt1 = $this->createMock(PDOStatement::class);

        $stmt1->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt1);

        $categoriaToUpdate = new Categoria("update_id", "Test Categoria", date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), false);

        $result = $this->categoriasService->update($categoriaToUpdate);

        $this->assertNull($result);
    }

    public function testSaveWithNonExistingCategoryName()
    {
        $categoriaToSave = new Categoria(null, "Test Categoria", null, null, false);

        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(false);


        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $result = $this->categoriasService->save($categoriaToSave);

        $this->assertNull($result);
    }


}