<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
