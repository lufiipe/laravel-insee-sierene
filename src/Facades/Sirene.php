<?php

namespace LuFiipe\LaravelInseeSierene\Facades;

use Illuminate\Support\Facades\Facade;
use LuFiipe\InseeSierene\Sirene as ClientSirene;

/** 
 * @method static string getVersion()
 * @method static string getBaseUrl()
 * @method static \LuFiipe\InseeSierene\Response\Response siren(string $siren, ?\LuFiipe\InseeSierene\Parameters\UnitParameters $parameters = null)
 * @method static \LuFiipe\InseeSierene\Response\Collection searchLegalUnits(\LuFiipe\InseeSierene\Parameters\SearchParameters $parameters)
 * @method static \LuFiipe\InseeSierene\Response\Response siret(string $siret, ?\LuFiipe\InseeSierene\Parameters\UnitParameters $parameters = null)
 * @method static \LuFiipe\InseeSierene\Response\Collection searchEstablishments(\LuFiipe\InseeSierene\Parameters\SearchParameters $parameters)
 * @method static \LuFiipe\InseeSierene\Response\Collection searchEstablishmentsSuccessions(\LuFiipe\InseeSierene\Parameters\SuccessionLinksParameters $parameters)
 * @method static \LuFiipe\InseeSierene\Response\Response informations()
 * 
 * @see \LuFiipe\InseeSierene\Sirene
 */
class Sirene extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ClientSirene::class;
    }
}
