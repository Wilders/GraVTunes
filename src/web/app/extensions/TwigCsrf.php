<?php

namespace app\extensions;

use Slim\Csrf\Guard;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class TwigCsrf
 * @author Jules Sayer <jules.sayer@protonmail.com>
 * @package app\extensions
 */
class TwigCsrf extends AbstractExtension {

    /**
     * @var Guard
     */
    protected $guard;

    /**
     * TwigCsrf constructor.
     *
     * @param Guard $guard
     */
    public function __construct(Guard $guard) {
        $this->guard = $guard;
    }
    /**
     * Nom de l'extension
     *
     * @return string
     */
    public function getName() {
        return 'slim-twig-csrf';
    }

    /**
     * Callback pour twig.
     *
     * @return array
     */
    public function getFunctions() {
        return [
            new TwigFunction('csrf', [$this, 'csrf']),
        ];
    }

    public function csrf() {
        return '<input type="hidden" name="' . $this->guard->getTokenNameKey() . '" value="' . $this->guard->getTokenName() . '"><input type="hidden" name="' . $this->guard->getTokenValueKey() . '" value="' . $this->guard->getTokenValue() . '">';
    }
}