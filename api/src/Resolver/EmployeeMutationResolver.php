<?php


namespace App\Resolver;


use ApiPlatform\Core\GraphQl\Resolver\MutationResolverInterface;
use App\Entity\Address;
use App\Entity\Document;
use App\Entity\Employee;
use App\Entity\LanguageHouse;
use App\Entity\User;
use App\Service\MrcService;
use App\Service\ParticipationService;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class EmployeeMutationResolver implements MutationResolverInterface
{

    private EntityManagerInterface $entityManager;
    private MrcService $mrcService;
    private ParticipationService $participationService;

    public function __construct(EntityManagerInterface $entityManager, MrcService $mrcService, ParticipationService $participationService){
        $this->entityManager = $entityManager;
        $this->mrcService = $mrcService;
        $this->participationService = $participationService;
    }
    /**
     * @inheritDoc
     */
    public function __invoke($item, array $context)
    {
        if (!$item instanceof Employee && !key_exists('input', $context['info']->variableValues)) {
            return null;
        }
        switch($context['info']->operation->name->value){
            case 'createEmployee':
                return $this->createEmployee($context['info']->variableValues['input']);
            case 'updateEmployee':
                return $this->updateEmployee($context['info']->variableValues['input']);
            case 'removeEmployee':
                return $this->deleteEmployee($context['info']->variableValues['input']);
            case 'addMentoredParticipationToEmployee':
                return $this->addMentorToParticipation($context['info']->variableValues['input']);
            default:
                return $item;
        }
    }

    public function createEmployee(array $employeeArray): Employee
    {
        return $this->mrcService->createEmployee($employeeArray);
    }

    public function updateEmployee(array $input): Employee
    {
        $id = explode('/',$input['id']);
        return $this->mrcService->updateEmployee(end($id), $input);
    }

    public function deleteEmployee(array $input): ?Employee
    {
        $id = explode('/',$input['id']);
        $this->mrcService->deleteEmployee(end($id));
        return null;
    }

    public function addMentorToParticipation(array $input): Employee
    {
        $participationId = explode('/',$input['participationId']);
        $aanbiederEmployeeId = explode('/',$input['aanbiederEmployeeId']);

        return $this->participationService->addMentoredParticipationToEmployee(end($participationId), end($aanbiederEmployeeId));
    }

}
