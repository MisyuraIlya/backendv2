<?php

namespace App\Factory;

use App\Entity\PriceList;
use App\Repository\PriceListRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<PriceList>
 *
 * @method        PriceList|Proxy                     create(array|callable $attributes = [])
 * @method static PriceList|Proxy                     createOne(array $attributes = [])
 * @method static PriceList|Proxy                     find(object|array|mixed $criteria)
 * @method static PriceList|Proxy                     findOrCreate(array $attributes)
 * @method static PriceList|Proxy                     first(string $sortedField = 'id')
 * @method static PriceList|Proxy                     last(string $sortedField = 'id')
 * @method static PriceList|Proxy                     random(array $attributes = [])
 * @method static PriceList|Proxy                     randomOrCreate(array $attributes = [])
 * @method static PriceListRepository|RepositoryProxy repository()
 * @method static PriceList[]|Proxy[]                 all()
 * @method static PriceList[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static PriceList[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static PriceList[]|Proxy[]                 findBy(array $attributes)
 * @method static PriceList[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static PriceList[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class PriceListFactory extends ModelFactory
{
    private const TREASURE_NAMES = ['priceList 1', 'priceList 2','priceList 3', 'priceList 4','priceList 5', 'priceList 6'];

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'extId' => self::faker()->numberBetween(1000, 1000000),
            'title' => self::faker()->randomElement(self::TREASURE_NAMES),
            'discount' => self::faker()->numberBetween(0, 100),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(PriceList $priceList): void {})
        ;
    }

    protected static function getClass(): string
    {
        return PriceList::class;
    }
}
