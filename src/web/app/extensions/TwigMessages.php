<?php

namespace App\Extensions;

use Slim\Flash\Messages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class TwigMessages
 * Adapaté car outdated sur ce repo
 *
 * @link https://github.com/kanellov/slim-twig-flash for the canonical source repository
 * @package App\Extensions
 */
class TwigMessages extends AbstractExtension {

    /**
     * @var Messages
     */
    protected $flash;

    /**
     * TwigMessages constructor.
     *
     * @param Messages $flash
     */
    public function __construct(Messages $flash) {
        $this->flash = $flash;
    }

    /**
     * Nom de l'extension
     *
     * @return string
     */
    public function getName() {
        return 'slim-twig-flash';
    }

    /**
     * Callback pour twig.
     *
     * @return array
     */
    public function getFunctions() {
        return [
            new TwigFunction('flash', [$this, 'getMessages']),
        ];
    }

    /**
     * Retourne les messages flash, si la clé est donnée
     * en paramètre, alors retourne les messages associés
     * à la clé
     *
     * @param string $key
     * @return array
     */
    public function getMessages($key = null) {
        if (null !== $key) {
            return $this->flash->getMessage($key);
        }

        return $this->flash->getMessages();
    }
}