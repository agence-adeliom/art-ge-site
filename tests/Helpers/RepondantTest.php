<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\Entity\Department;
use App\Entity\Repondant;
use App\Entity\Typologie;
use App\Repository\DepartmentRepository;
use App\Repository\TypologieRepository;

class RepondantTest {
    private string $typologie;
    private bool $restauration;
    private bool $greenSpace;
    private string $department;

    public function __construct(string $typologie, bool $restauration, ?string $department = 'ardennes', ?bool $greenSpace = true)
    {
        $this->typologie = $typologie;
        $this->restauration = $restauration;
        $this->greenSpace = $greenSpace;
        $this->department = $department;
    }

    public function getTypologie(TypologieRepository $typologieRepository): Typologie
    {
        return $typologieRepository->findOneBy(['slug' => $this->typologie]);
    }

    public function isRestauration(): bool
    {
        return $this->restauration;
    }

    public function isGreenSpace(): bool
    {
        return $this->greenSpace;
    }

    public function getDepartment(DepartmentRepository $departmentRepository): Department
    {
        return $departmentRepository->findOneBy(['slug' => $this->department]);
    }

    public function getDataSetName(): string
    {
        return sprintf('%s %s restauration %s espace vert dans %s',
            $this->typologie,
            $this->restauration ? 'avec' : 'sans',
            $this->greenSpace ? 'avec' : 'sans',
            $this->department,
        );
    }
}
