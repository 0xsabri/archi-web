<?php

namespace App\Entity;

/**
 * Entity Employee
 * Représente un employé de la base AdventureWorks
 */
class Employee
{
    public function __construct(
        private int $businessEntityID,
        private string $nationalIDNumber,
        private string $loginID,
        private ?string $jobTitle = null,
        private ?string $birthDate = null,
        private ?string $maritalStatus = null,
        private ?string $gender = null,
        private ?string $hireDate = null,
        private ?bool $salariedFlag = null,
        private ?int $vacationHours = null,
        private ?int $sickLeaveHours = null,
        private ?bool $currentFlag = null
    ) {
    }

    // Getters
    public function getBusinessEntityID(): int
    {
        return $this->businessEntityID;
    }

    public function getNationalIDNumber(): string
    {
        return $this->nationalIDNumber;
    }

    public function getLoginID(): string
    {
        return $this->loginID;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function getMaritalStatus(): ?string
    {
        return $this->maritalStatus;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getHireDate(): ?string
    {
        return $this->hireDate;
    }

    public function getSalariedFlag(): ?bool
    {
        return $this->salariedFlag;
    }

    public function getVacationHours(): ?int
    {
        return $this->vacationHours;
    }

    public function getSickLeaveHours(): ?int
    {
        return $this->sickLeaveHours;
    }

    public function getCurrentFlag(): ?bool
    {
        return $this->currentFlag;
    }

    /**
     * Convertit l'entité en tableau associatif
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'businessEntityID' => $this->businessEntityID,
            'nationalIDNumber' => $this->nationalIDNumber,
            'loginID' => $this->loginID,
            'jobTitle' => $this->jobTitle,
            'birthDate' => $this->birthDate,
            'maritalStatus' => $this->maritalStatus,
            'gender' => $this->gender,
            'hireDate' => $this->hireDate,
            'salariedFlag' => $this->salariedFlag,
            'vacationHours' => $this->vacationHours,
            'sickLeaveHours' => $this->sickLeaveHours,
            'currentFlag' => $this->currentFlag,
        ];
    }
}
