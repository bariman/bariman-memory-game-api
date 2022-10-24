<?php

namespace Drupal\Tests\card_game\Kernel;

use Drupal\Core\Url;
use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test description.
 *
 * @group card_game
 */
class CardGameResponseTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['card_game'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    // Mock required services here.
  }

  /**
   * Check if response is in json format.
   */
  public function testJsonResponse() {
    $path = Url::fromUri('base:/'. 'code-challenge/card-grid')
      ->setAbsolute()
      ->toString();
    $headers = get_headers($path);
    $json_response_type = in_array('Content-Type: application/json', $headers);
    $this->assertTrue($json_response_type);
  }

  /**
   * @return void
   */
  public function testEvenCardsResponse() {
    $path = Url::fromUri('base:/'. 'code-challenge/card-grid?rows=3&columns=5')
      ->setAbsolute()
      ->toString();
    $response = file_get_contents($path);
    $this->assertStringContainsString("Either `rows` or `columns` needs to be an even number", $response);
  }

}
