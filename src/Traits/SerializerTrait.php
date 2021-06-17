<?php

namespace App\Traits;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

trait SerializerTrait
{
  public function serializer(): Serializer
  {
    $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
    $jsonEncoder = new JsonEncoder();
    $objectNormalizer = new ObjectNormalizer($classMetadataFactory);

    return new Serializer([$objectNormalizer], [$jsonEncoder]);
  }

}        
