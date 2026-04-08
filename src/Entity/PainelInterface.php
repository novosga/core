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

namespace Novosga\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * PainelInterface
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface PainelInterface
{
    public function getId(): ?int;

    public function getNome(): ?string;
    public function setNome(?string $nome): static;

    public function getPublicId(): ?string;
    public function setPublicId(?string $publicId): static;

    public function getUnidade(): ?UnidadeInterface;
    public function setUnidade(?UnidadeInterface $unidade): static;

    /** @return Collection<int, PainelServicoInterface> */
    public function getServicos(): Collection;

    public function addServico(PainelServicoInterface $servico): static;

    public function removeServico(PainelServicoInterface $servico): static;
}
