<?php

class UserType
{
  const VISITOR = 1;
  const ADMIN = 2;
  const SUPERADMIN = 3;
}

class ContentCategory
{
  const DANCE = 'DANCE';
  const JAZZ = 'JAZZ';
  const HISTORIC = 'HISTORIC';
  const HOMEPAGE = 'HOMEPAGE';
  const FOOD = 'FOOD';
  const CMS = 'CMS';
  const CHECKOUT = 'CHECKOUT';
  const CART = 'CART';

  public static function getConstants(){
    $self = new ReflectionClass(__CLASS__);
    return $self->getConstants();
  }
}