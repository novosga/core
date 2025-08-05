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

namespace Novosga\Service;

use DateTimeInterface;
use Exception;
use Novosga\Entity\AgendamentoInterface;
use Novosga\Entity\AtendimentoInterface;
use Novosga\Entity\ClienteInterface;
use Novosga\Entity\EntityMetadataInterface;
use Novosga\Entity\LocalInterface;
use Novosga\Entity\PrioridadeInterface;
use Novosga\Entity\ServicoInterface;
use Novosga\Entity\ServicoUsuarioInterface;
use Novosga\Entity\UnidadeInterface;
use Novosga\Entity\UsuarioInterface;

/**
 * AtendimentoServiceInterface.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface AtendimentoServiceInterface
{
    public const ATTR_NAMESPACE = 'global';

    // estados do atendimento
    public const SENHA_EMITIDA = 'emitida';
    public const CHAMADO_PELA_MESA = 'chamado';
    public const ATENDIMENTO_INICIADO = 'iniciado';
    public const ATENDIMENTO_ENCERRADO = 'encerrado';
    public const NAO_COMPARECEU = 'nao_compareceu';
    public const SENHA_CANCELADA = 'cancelada';
    public const ERRO_TRIAGEM = 'erro_triagem';

    // resolucoes
    public const RESOLVIDO = 'resolvido';
    public const PENDENTE = 'pendente';

    public function getById(int $id): ?AtendimentoInterface;

    /** @return array<string,string> */
    public function getSituacoes(): array;

    /** @return array<string,string> */
    public function getResolucoes(): array;

    public function getNomeSituacao(string $status): string;

    /**
     * Cria ou retorna um metadado do atendimento caso o $value seja null (ou ocultado).
     * @return ?EntityMetadataInterface<UnidadeInterface>
     */
    public function meta(
        AtendimentoInterface $atendimento,
        string $name,
        mixed $value = null
    ): ?EntityMetadataInterface;

    public function buscaAtendimento(UnidadeInterface $unidade, int $id): ?AtendimentoInterface;

    /** @return AtendimentoInterface[] */
    public function buscaAtendimentos(UnidadeInterface $unidade, string $senha): array;

    /**
     * Gera um novo atendimento.
     */
    public function distribuiSenha(
        int|UnidadeInterface $unidade,
        int|UsuarioInterface $usuario,
        int|ServicoInterface $servico,
        int|PrioridadeInterface $prioridade,
        ?ClienteInterface $cliente = null,
        ?AgendamentoInterface $agendamento = null,
    ): AtendimentoInterface;

    /**
     * Adiciona uma nova senha na fila de chamada do painel de senhas.
     */
    public function chamarSenha(AtendimentoInterface $atendimento, UsuarioInterface $usuario): void;

    /**
     * @param ServicoUsuarioInterface[] $servicos
     */
    public function chamarProximo(
        UnidadeInterface $unidade,
        UsuarioInterface $usuario,
        LocalInterface $local,
        string $tipo,
        array $servicos,
        int $numeroLocal,
    ): ?AtendimentoInterface;

    /**
     * Vincula o atendimento ao usuário
     */
    public function chamarAtendimento(
        AtendimentoInterface $atendimento,
        UsuarioInterface $usuario,
        LocalInterface $local,
        int $numeroLocal
    ): bool;

    /**
     * Move os registros da tabela atendimento para a tabela de historico de atendimentos.
     * Se a unidade não for informada, será acumulado serviços de todas as unidades.
     */
    public function acumularAtendimentos(
        ?UsuarioInterface $usuario,
        ?UnidadeInterface $unidade,
        DateTimeInterface $ateData,
    ): void;

    /**
     * Retorna o atendimento em andamento do usuario informado.
     */
    public function getAtendimentoAndamento(
        int|UsuarioInterface $usuario,
        int|UnidadeInterface|null $unidade
    ): ?AtendimentoInterface;

    /** @throws Exception */
    public function iniciarAtendimento(AtendimentoInterface $atendimento, UsuarioInterface $usuario): void;

    /** @throws Exception */
    public function naoCompareceu(AtendimentoInterface $atendimento, UsuarioInterface $usuario): void;

    /**
     * Redireciona um atendimento para outro serviço.
     * @throws Exception
     */
    public function redirecionar(
        AtendimentoInterface $atendimento,
        UsuarioInterface $usuario,
        ServicoInterface|int $novoServico,
        UsuarioInterface|int|null $novoAtendente = null,
    ): AtendimentoInterface;

    /**
     * Transfere o atendimento para outro serviço e prioridade.
     * @throws Exception
     */
    public function transferir(
        AtendimentoInterface $atendimento,
        UsuarioInterface $usuario,
        ServicoInterface|int $novoServico,
        PrioridadeInterface|int $novaPrioridade
    ): void;

    /**
     * Atualiza o status da senha para cancelado.
     * @throws Exception
     */
    public function cancelar(AtendimentoInterface $atendimento, UsuarioInterface $usuario): void;

    /**
     * Reativa o atendimento para o mesmo serviço e mesma prioridade.
     * Só pode reativar atendimentos que foram: Cancelados ou Não Compareceu.
     * @throws Exception
     */
    public function reativar(AtendimentoInterface $atendimento, UsuarioInterface $usuario): void;

    /**
     * @param ServicoInterface[]|int[] $servicosRealizados
     * @throws Exception
     */
    public function encerrar(
        AtendimentoInterface $atendimento,
        UsuarioInterface $usuario,
        array $servicosRealizados,
        ServicoInterface|int|null $servicoRedirecionado = null,
        UsuarioInterface|int|null $novoUsuario = null,
    ): void;

    /**
     * Apaga os dados de atendimento da unidade ou global
     */
    public function limparDados(?UsuarioInterface $usuario, ?UnidadeInterface $unidade): void;
}
