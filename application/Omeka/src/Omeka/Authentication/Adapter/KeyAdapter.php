<?php
namespace Omeka\Authentication\Adapter;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;

/**
 * Auth adapter for checking API keys through Doctrine.
 */
class KeyAdapter extends AbstractAdapter
{
    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * Create the adapter.
     *
     * @param EntityRepository $repository The Key repository.
     */
    public function __construct(EntityRepository $repository,
        EntityManager $entityManager
    ) {
        $this->setRepository($repository);
        $this->setEntityManager($entityManager);
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate()
    {
        $key = $this->repository->find($this->getIdentity());

        if (!$key) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null,
                array('Key identity not found.'));
        }

        if (!$key->verifyCredential($this->getCredential())) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null,
                array('Invalid key credential.'));
        }

        $key->setLastIp($_SERVER['REMOTE_ADDR']);
        $this->getEntityManager()->flush();

        return new Result(Result::SUCCESS, $key->getUser());
    }

    /**
     * Set the repository to use to look up keys.
     *
     * @param EntityRepository $repository
     */
    public function setRepository(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get the repository used to look up keys.
     *
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set the entity manager.
     *
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get the entity manager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
