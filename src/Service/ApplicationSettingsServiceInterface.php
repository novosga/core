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

use Novosga\Entity\UsuarioInterface;
use Novosga\Settings\AppearanceSettings;
use Novosga\Settings\ApplicationSettings;
use Novosga\Settings\BehaviorSettings;
use Novosga\Settings\QueueSettings;
use Novosga\Settings\UserBehaviorSettings;

/**
 * ApplicationSettingsServiceInterface
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface ApplicationSettingsServiceInterface
{
    public function loadSettings(): ApplicationSettings;

    public function loadAppearanceSettings(): AppearanceSettings;

    public function loadQueueSettings(): QueueSettings;

    public function loadBehaviorSettings(): BehaviorSettings;

    public function saveSettings(ApplicationSettings $settings): void;

    public function saveAppearanceSettings(AppearanceSettings $settings): void;

    public function saveBehaviorSettings(BehaviorSettings $settings): void;

    public function saveQueueSettings(QueueSettings $settings): void;

    /**
     * Loads behavior settings for the given user.
     *
     * When $resolveGlobal is true (default), any field not explicitly overridden
     * by the user falls back to the global BehaviorSettings value, so the returned
     * object always has concrete (non-null) values ready for enforcement.
     *
     * When $resolveGlobal is false, only the raw per-user overrides are returned;
     * fields with no override will be null, which is useful for displaying the
     * current override state in settings forms without conflating "not set" with
     * the global default.
     */
    public function loadUserBehaviorSettings(
        UsuarioInterface $usuario,
        ?bool $resolveGlobal = true,
    ): UserBehaviorSettings;

    public function saveUserBehaviorSettings(UsuarioInterface $usuario, UserBehaviorSettings $settings): void;
}
