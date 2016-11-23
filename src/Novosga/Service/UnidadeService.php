<?php

namespace Novosga\Service;

use Novosga\Entity\Unidade;
use Novosga\Entity\UnidadeMeta;

/**
 * UnidadeService.
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
class UnidadeService extends MetaModelService
{
    protected function getMetaClass()
    {
        return UnidadeMeta::class;
    }

    protected function getMetaFieldname()
    {
        return 'unidade';
    }

    /**
     * Cria ou retorna um metadado da unidade caso o $value seja null (ou ocultado).
     *
     * @param Unidade $unidade
     * @param string  $name
     * @param string  $value
     *
     * @return UnidadeMeta
     */
    public function meta(Unidade $unidade, $name, $value = null)
    {
        return $this->modelMetadata($unidade, $name, $value);
    }
}
