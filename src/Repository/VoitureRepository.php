<?php

namespace App\Repository;

use App\Entity\Voiture;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Voiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoitureRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Voiture::class);
	}


	/**
	 * @param int $latitude
	 * @param float $longitude
	 * @param int $limit
	 * @return array|mixed
	 */
	public function findPerProximity($latitude, $longitude, $limit = 0)
	{
		/** @var TYPE_NAME $queryBuilder */
		$queryBuilder = $this->createQueryBuilder('v');
		$queryBuilder
			->select("v.id, v.marque, v.matricule, v.nombrePlace, v.image, u as user, X(l.geometry) as longitude, Y(l.geometry) as latitude, DISTANCE(POINT(:x, :y), l.geometry) as distance")
			->leftJoin('App\Entity\User', 'u', 'WITH', 'v.user = u.id')
			->leftJoin('App\Entity\Location', 'l', 'WITH', 'v.location = l.id')
			->setParameter('x', $longitude)
			->setParameter('y', $latitude)
			->orderBy('distance', 'ASC');
		if ($limit > 0) {
			$queryBuilder->setMaxResults($limit);
		}
		return $queryBuilder->getQuery()->getArrayResult();
	}


	/*
	public function findOneBySomeField($value): ?Voiture
	{
		return $this->createQueryBuilder('v')
			->andWhere('v.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}
