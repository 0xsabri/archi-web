<?php

namespace App\Model;

use App\Core\Database;
use App\Entity\Employee;
use PDO;
use PDOException;

/**
 * EmployeeModel (Repository)
 * Gère les opérations de lecture/écriture pour les employés
 */
class EmployeeModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Récupère tous les employés
     * 
     * @return Employee[] Tableau d'objets Employee
     */
    public function findAll(): array
    {
        try {
            $sql = "SELECT 
                        EmployeeID,
                        NationalIDNumber,
                        LoginID,
                        Title,
                        BirthDate,
                        MaritalStatus,
                        Gender,
                        HireDate,
                        SalariedFlag,
                        VacationHours,
                        SickLeaveHours,
                        CurrentFlag
                    FROM employee
                    ORDER BY EmployeeID ASC
                    LIMIT 100";

            $stmt = $this->db->query($sql);
            $results = $stmt->fetchAll();

            $employees = [];
            foreach ($results as $row) {
                $employees[] = new Employee(
                    businessEntityID: (int) $row['EmployeeID'],
                    nationalIDNumber: $row['NationalIDNumber'],
                    loginID: $row['LoginID'],
                    jobTitle: $row['Title'],
                    birthDate: $row['BirthDate'],
                    maritalStatus: $row['MaritalStatus'],
                    gender: $row['Gender'],
                    hireDate: $row['HireDate'],
                    salariedFlag: (bool) $row['SalariedFlag'],
                    vacationHours: (int) $row['VacationHours'],
                    sickLeaveHours: (int) $row['SickLeaveHours'],
                    currentFlag: (bool) $row['CurrentFlag']
                );
            }

            return $employees;
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la récupération des employés : " . $e->getMessage());
        }
    }

    /**
     * Récupère un employé par son ID
     * 
     * @param int $id EmployeeID de l'employé
     * @return Employee|null L'employé ou null si non trouvé
     */
    public function findById(int $id): ?Employee
    {
        try {
            $sql = "SELECT 
                        EmployeeID,
                        NationalIDNumber,
                        LoginID,
                        Title,
                        BirthDate,
                        MaritalStatus,
                        Gender,
                        HireDate,
                        SalariedFlag,
                        VacationHours,
                        SickLeaveHours,
                        CurrentFlag
                    FROM employee
                    WHERE EmployeeID = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            return new Employee(
                businessEntityID: (int) $row['EmployeeID'],
                nationalIDNumber: $row['NationalIDNumber'],
                loginID: $row['LoginID'],
                jobTitle: $row['Title'],
                birthDate: $row['BirthDate'],
                maritalStatus: $row['MaritalStatus'],
                gender: $row['Gender'],
                hireDate: $row['HireDate'],
                salariedFlag: (bool) $row['SalariedFlag'],
                vacationHours: (int) $row['VacationHours'],
                sickLeaveHours: (int) $row['SickLeaveHours'],
                currentFlag: (bool) $row['CurrentFlag']
            );
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la récupération de l'employé : " . $e->getMessage());
        }
    }
}