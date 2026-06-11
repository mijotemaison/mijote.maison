<?php

declare(strict_types=1);

function render_pedagogy_code_panel(string $title, string $file, string $code, string $idPrefix = 'code'): void
{
    $id = $idPrefix . '-' . md5($title . $file);
    echo '<div class="code-panel">';
    echo '<div class="code-panel__header">';
    echo '<div><p class="mb-1 small fw-black text-white">' . e($title) . '</p><p class="mb-0 small text-white-50">' . e($file) . '</p></div>';
    echo '<button class="btn btn-sm btn-outline-light" type="button" data-copy-code="' . e($id) . '">Copier</button>';
    echo '</div>';
    echo '<pre><code id="' . e($id) . '">' . e(trim($code)) . '</code></pre>';
    echo '</div>';
}

function render_guidance_panel(string $text, string $extraClass = ''): void
{
    $paragraphs = preg_split('/\R{2,}/', trim($text)) ?: [];
    $class = trim($extraClass);

    echo '<div class="slide-guidance-card' . ($class !== '' ? ' ' . e($class) : '') . '" data-presenter-only>';
    echo '<div class="d-flex flex-column flex-lg-row align-items-lg-start gap-3">';
    echo '<div class="slide-guidance-card__label">';
    echo '<p class="kicker mb-1">Fil conducteur</p>';
    echo '<span>Repère de soutenance</span>';
    echo '</div>';
    echo '<div class="slide-guidance-card__body">';

    foreach ($paragraphs as $paragraph) {
        echo '<p>' . e($paragraph) . '</p>';
    }

    echo '</div>';
    echo '</div>';
    echo '</div>';
}
