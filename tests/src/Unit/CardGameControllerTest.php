<?php

namespace Drupal\Tests\card_game\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\card_game\Controller\CardGameController;
use ReflectionMethod;

/**
 * Test CardGameController.
 *
 * @group card_game
 */
class CardGameControllerTest extends UnitTestCase {


  /**
   * Data provider for testGenerateUniqueCards().
   */
  public function provideTestGenerateUniqueCards() {
    return [
      [['A','B'], 2],
      [['A','B','C'], 3],
    ];
  }

  /**
   * Test generateUniqueCards method.
   *
   * @dataProvider provideTestGenerateUniqueCards
   */
  public function testGenerateUniqueCards(array $expected, int $cardCount) {
    $controller = $this->getMockBuilder(CardGameController::class)
      ->disableOriginalConstructor()
      ->getMock();
    $ref_generateUniqueCards = new ReflectionMethod($controller, 'generateUniqueCards');
    $ref_generateUniqueCards->setAccessible(TRUE);
    $this->assertEquals($expected, $ref_generateUniqueCards->invokeArgs($controller, [$cardCount]));
  }

}
