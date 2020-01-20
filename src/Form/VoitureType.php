<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Voiture;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoitureType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$this->driver = $options['driver'];
		$this->data = $options['data'];

		$latitude = $this->data->getLocation() ? $this->data->getLocation()->getGeometry()->getLatitude() : "";
		$longitude = $this->data->getLocation() ? $this->data->getLocation()->getGeometry()->getLongitude() : "";

		$builder
			->add('user', EntityType::class, [
				'label' => 'Chauffeur',
				'class' => User::class,
				'choice_label' => 'lastname',
				'query_builder' => function (EntityRepository $er) {
					if ($this->driver && $this->driver->getId()) {
						return $er->createQueryBuilder('u')
							->where('u.id = :driver')
							->setParameter('driver', $this->driver->getId());
					} else {
						return $er->createQueryBuilder('u')
							->where('u.roles LIKE :roles')
							->setParameter('roles', '%ROLE_CHAUFFEUR%');
					}
				},
			])
			->add('marque', TextType::class, [
				'label' => 'Marque'
			])
			->add('nombrePlace', NumberType::class, [
				'label' => 'Nombre de places'
			])
			->add('matricule', TextType::class, [
				'label' => 'Matricule'
			])
			->add('image')
			->add('latitude', TextType::class, [
				'label' => 'Latitude',
				'data' => $latitude,
				'mapped' => false,
				'required' => false
			])
			->add('longitude', TextType::class, [
				'label' => 'Longitude',
				'data' => $longitude,
				'mapped' => false,
				'required' => false
			]);

	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Voiture::class,
			'driver' => null
		]);
	}
}
