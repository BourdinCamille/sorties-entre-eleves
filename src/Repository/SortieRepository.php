<?php

namespace App\Repository;

use App\Entity\Etat;
use App\Util\SearchData;
use App\Entity\Participant;
use App\Entity\Sortie;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Sortie[]
     * Retourne toutes les sorties à l'exception de celles dont la date remonte à il y a plus d'un mois.
     */
    public function findAllUntilLastMonth(): array
    {
        $dateLimite = date('Y-m-d H:i', strtotime('-1 months'));
        $query = $this
            ->createQueryBuilder('s')
            ->andWhere('s.dateHeureDebut > :dateLimite')
            ->setParameter(':dateLimite', $dateLimite)
            ->orderBy('s.dateHeureDebut','DESC');
        return $query->getQuery()->getResult();
    }

    /**
     * @param SearchData $search
     * @param Participant $participant
     * @return Sortie[]
     */
    public function findSearch(SearchData $search, Participant $participant): array
    {
        $dateDuJour = new DateTime('now');
        $dateLimite = date('Y-m-d H:i', strtotime('-1 months'));
        $etatPasse = $this->getEntityManager()->getRepository(Etat::class)->findOneBy(['libelle' => 'Passée']);
        $query = $this
            ->createQueryBuilder('s')
            ->addSelect('c','e','p')
            ->join('s.siteOrganisateur','c')
            ->join('s.etat','e')
            ->leftJoin('s.participants','p')
            ->andWhere('s.dateHeureDebut > :dateLimite')
            ->setParameter(':dateLimite', $dateLimite);

        if(!empty($search->campusSelectionne))
        {
            $query = $query
                ->andWhere('c.id IN (:campusSelectionne)')
                ->setParameter('campusSelectionne', $search->campusSelectionne);
        }
        if(!empty($search->q))
        {
            $query = $query
                ->andWhere('s.nom LIKE :q')
                ->setParameter('q', "%{$search}%");
        }
        if(!empty($search->dateMin) && !empty($search->dateMax))
        {
            $query = $query
                ->andWhere('s.dateHeureDebut BETWEEN :dateMin AND :dateMax')
                ->setParameter('dateMin',$search->dateMin)
                ->setParameter('dateMax',$search->dateMax);
        }
        if($search->isOrganisateur())
        {
            $query = $query
                ->andWhere('s.organisateur = :user')
                ->setParameter('user', $participant);
        }
        if($search->isInscrit() && $search->isNotInscrit() == false)
        {
            $query = $query
                ->andWhere(':user MEMBER OF s.participants')
                ->setParameter('user', $participant);
        }
        if($search->isNotInscrit() && $search->isInscrit() == false)
        {
            $query = $query
                ->andWhere(':user NOT MEMBER OF s.participants')
                ->setParameter('user', $participant);

        }
        if($search->isPassee())
        {
            $query = $query
                ->andWhere('s.dateHeureDebut BETWEEN :dateMin AND :dateMax')
                ->setParameter('dateMin',$dateLimite)
                ->setParameter('dateMax',$dateDuJour)
                ->andWhere('s.etat = :etat')
                ->setParameter('etat',$etatPasse);
        }
        $query = $query
            ->orderBy('s.dateHeureDebut','DESC');

        return $query->getQuery()->getResult();
    }

}
