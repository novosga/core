<?php

namespace Novosga\Dto;

final class InstalledModule
{
    public function __construct(
        public readonly bool $active,
        public readonly string $key,
        public readonly string $displayName,
        public readonly string $iconName,
        public readonly string $homeRoute,
    ) {
    }
}
