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

use Novosga\Settings\AppearanceSettings;
use Novosga\Settings\ApplicationSettings;
use Novosga\Settings\BehaviorSettings;
use Novosga\Settings\QueueSettings;

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
}
