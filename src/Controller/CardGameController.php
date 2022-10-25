<?php

namespace Drupal\card_game\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns responses for Card game routes.
 */
class CardGameController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function getCards(Request $request) {
    $rows = $request->query->get('rows');
    $columns = $request->query->get('columns');
    $message = $this->validateParams($rows, $columns);

    if ($message !== TRUE) {
      return new JsonResponse([
        'meta' => [
          'success' => FALSE,
          'message' => $message,
        ],
        'data' => [],
      ]);
    }
    $cardCount = ((int) $rows * (int) $columns) / 2;
    $uniqueCards = $this->generateUniqueCards($cardCount);
    $dealtCards = $this->generateDeck($uniqueCards, $rows, $columns);
    return new JsonResponse([
      'meta' => [
        'success' => TRUE,
        'cardCount' => 4,
        'uniqueCardCount' => 2,
        'uniqueCards' => $uniqueCards,
      ],
      'data' => [
        'cards' => $dealtCards,
      ],
    ]);
  }

  /**
   * Validates rows and columns.
   *
   * @param string $rows
   *   Rows.
   * @param string $columns
   *   Columns.
   *
   * @return bool|string
   *   Error message or true in case of success.
   */
  protected function validateParams($rows, $columns) {
    if (is_null($rows) || is_null($columns)) {
      return "Both 'rows' and 'columns' need to be set";
    }
    if (!is_numeric($rows) || !is_numeric($columns)) {
      return "Both 'rows' and 'columns' need to be integer numbers";
    }
    if ($rows > 6) {
      return 'Row count is greater than 6';
    }
    if ($columns > 6) {
      return 'Column count is greater than 6';
    }
    if ($columns % 2 == 1 && $rows % 2 == 1) {
      return "Either `rows` or `columns` needs to be an even number.";
    }

    return TRUE;
  }

  /**
   * Generate a set of unique cards.
   *
   * @param int $card_count
   *   Set size.
   *
   * @return array
   *   Array of unique generated cards.
   */
  protected function generateUniqueCards(int $card_count) {
    $uniqueCards = [];

    for ($x = 65; $x < 65 + $card_count; $x++) {
      $uniqueCards[] = chr($x);
    }

    return $uniqueCards;
  }

  /**
   * Create shuffled deck.
   *
   * @param array $cards
   *   Cards.
   * @param int $rows
   *   Rows.
   * @param int $columns
   *   Columns.
   *
   * @return array
   *   Dealt deck.
   */
  protected function generateDeck(array $cards, int $rows, int $columns) {
    $deck = array_merge($cards, $cards);
    shuffle($deck);
    $dealtCards = [];
    for ($r = 0; $r < $rows; $r++) {
      for ($c = 0; $c < $columns; $c++) {
        $dealtCards[$r][$c] = array_shift($deck);
      }
    }

    return $dealtCards;
  }

}
