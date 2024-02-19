<?php

namespace App\Factory;

use App\Entity\SubAttribute;
use App\Repository\SubAttributeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<SubAttribute>
 *
 * @method        SubAttribute|Proxy                     create(array|callable $attributes = [])
 * @method static SubAttribute|Proxy                     createOne(array $attributes = [])
 * @method static SubAttribute|Proxy                     find(object|array|mixed $criteria)
 * @method static SubAttribute|Proxy                     findOrCreate(array $attributes)
 * @method static SubAttribute|Proxy                     first(string $sortedField = 'id')
 * @method static SubAttribute|Proxy                     last(string $sortedField = 'id')
 * @method static SubAttribute|Proxy                     random(array $attributes = [])
 * @method static SubAttribute|Proxy                     randomOrCreate(array $attributes = [])
 * @method static SubAttributeRepository|RepositoryProxy repository()
 * @method static SubAttribute[]|Proxy[]                 all()
 * @method static SubAttribute[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static SubAttribute[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static SubAttribute[]|Proxy[]                 findBy(array $attributes)
 * @method static SubAttribute[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static SubAttribute[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class SubAttributeFactory extends ModelFactory
{
    private const TREASURE_NAMES = ['10v', '15v', '20v', '30v', '40v' , '50v', '60v'];

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
            'product' => ProductFactory::new(),
            'attribute' => AttributeMainFactory::new(),
            'title' =>  self::faker()->randomElement(self::TREASURE_NAMES),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(SubAttribute $subAttribute): void {})
        ;
    }

    protected static function getClass(): string
    {
        return SubAttribute::class;
    }
}
