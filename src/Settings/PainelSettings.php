<?php

declare(strict_types=1);

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Settings;

/**
 * PainelSettings
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class PainelSettings
{
    public function __construct(
        public string $logo = '',
        public string $corFundoDestaque = '',
        public string $corFundoRodape = '',
        public string $corFundoHistorico = '',
        public string $corFundoRelogio = '',
    ) {
    }
}
