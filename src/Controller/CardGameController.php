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
class CardGameController extends ControllerBase {

  /**
   * Memory game object.
   *
   * @var \Drupal\card_game\Game\CardMemoryGame
   */
  protected $game;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
          $container->get('card_game.memory_game')
      );
  }

  /**
   * Class constructor.
   *
   * @param \Drupal\card_game\Game\CardMemoryGame $game
   *   Memory card game state.
   */
  public function __construct(CardMemoryGame $game) {
    $this->game = $game;
  }

  /**
   * Builds the response.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Request object.
   */
  public function getCards(Request $request) {
    $rows = (int) $request->query->get('rows');
    $columns = (int) $request->query->get('columns');
    $this->game->newGame($rows, $columns);
    $game = $this->game;

    if ($game->status === FALSE) {
      $data = new \stdClass();
      return new JsonResponse(
            [
              'meta' => [
                'success' => FALSE,
                'message' => $game->message,
              ],
              'data' => $data,
            ]
        );
    }

    return new JsonResponse(
          [
            'meta' => [
              'success' => TRUE,
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
