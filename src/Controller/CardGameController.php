<?php

namespace Drupal\card_game\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\card_game\Game\CardMemoryGame;

/**
 * Returns responses for Card game routes.
 */
class CardGameController extends ControllerBase
{

    /**
     * @var \Drupal\card_game\Game\CardMemoryGame
     */
    protected $game;

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('card_game.memory_game')
        );
    }

    /**
     * @param \Drupal\card_game\Game\CardMemoryGame $game
     */
    public function __construct(CardMemoryGame $game)
    {
        $this->game = $game;
    }

    /**
     * Builds the response.
     */
    public function getCards(Request $request)
    {
        $rows = (int) $request->query->get('rows');
        $columns = (int) $request->query->get('columns');
        $this->game->newGame($rows, $columns);
        $game = $this->game;

        if ($game->status === false) {
            $data = new \stdClass();
            return new JsonResponse(
                [
                'meta' => [
                'success' => false,
                'message' => $game->message,
                ],
                'data' => $data,
                ]
            );
        }

        return new JsonResponse(
            [
            'meta' => [
            'success' => true,
            'cardCount' => $game->cardCount,
            'uniqueCardCount' => $game->uniqueCardCount,
            'uniqueCards' => $game->uniqueCards,
            ],
            'data' => [
            'cards' => $game->dealtCards,
            ],
            ]
        );
    }

}
