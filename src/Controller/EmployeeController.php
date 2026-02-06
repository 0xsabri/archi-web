<?php

namespace App\Controller;

use App\Model\EmployeeModel;

/**
 * EmployeeController
 * Gère les endpoints liés aux employés
 */
class EmployeeController
{
    private EmployeeModel $employeeModel;

    public function __construct()
    {
        $this->employeeModel = new EmployeeModel();
    }

    /**
     * Liste tous les employés
     * GET /employees
     * 
     * @return void
     */
    public function list(): void
    {
        try {
            $employees = $this->employeeModel->findAll();

            // Convertir les objets Employee en tableaux
            $employeesArray = array_map(
                fn($employee) => $employee->toArray(),
                $employees
            );

            $response = [
                'status' => 'success',
                'count' => count($employeesArray),
                'data' => $employeesArray
            ];

            http_response_code(200);
            echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des employés',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Affiche un employé par son ID
     * GET /employees/{id}
     * 
     * @param int $id BusinessEntityID de l'employé
     * @return void
     */
    public function show(int $id): void
    {
        try {
            $employee = $this->employeeModel->findById($id);

            if ($employee === null) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Employé non trouvé',
                    'id' => $id
                ], JSON_PRETTY_PRINT);
                return;
            }

            $response = [
                'status' => 'success',
                'data' => $employee->toArray()
            ];

            http_response_code(200);
            echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération de l\'employé',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }
}
