<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Menu;

use Knp\Menu\ItemInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class MenuPropertyAccessor
{
    private readonly PropertyAccessorInterface $propertyAccessor;

    public function __construct(?PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->propertyAccessor = $propertyAccessor ?? PropertyAccess::createPropertyAccessor();
    }

    public function setValue(ItemInterface $menu, string $attributeToSet, string $class): void
    {
        /** @var string */
        $current = $this->propertyAccessor->getValue($menu, $attributeToSet);

        $this->propertyAccessor->setValue($menu, $attributeToSet, trim($class.' '.$current));
    }
}
