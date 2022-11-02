<?php

namespace Drupal\Tests\card_game\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Test memory game endpoint.
 *
 * @group card_game
 */
class CardGameResponseTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['card_game'];

  /**
   * Data provider for testFailureResponse().
   */
  public function provideTestFailureResponse() {
    return [
      [5, 5, "Either `rows` or `columns` needs to be an even number."],
      [4, 7, 'Column count is greater than 6'],
      [8, 3, 'Row count is greater than 6'],
      ['abc', 4, 'Row count should be greater than 0'],
    ];
  }

  /**
   * Check if response is in json format.
   *
   * @dataProvider provideTestFailureResponse
   */
  public function testFailureResponse($rows, $columns, $message) {
    $http_kernel = $this->container->get('http_kernel');
    $request = Request::create('/code-challenge/card-grid', 'GET',
      [
        'rows'=> $rows,
        'columns' => $columns,
      ]);
    $response = $http_kernel->handle($request, HttpKernelInterface::SUB_REQUEST);
    $json = json_decode($response->getContent());
    $this->assertFalse($json->meta->success);
    $this->assertEquals($message, $json->meta->message);
  }

  /**
   * Data provider for testSuccessResponse().
   */
  public function provideTestSuccessResponse() {
    return [
      [2, 3, 6, 3],
      [5, 6, 30, 15],
    ];
  }

  /**
   * Check if response is in json format.
   *
   * @dataProvider provideTestSuccessResponse
   */
  public function testSuccessResponse($rows, $columns, $cardCount, $uniqueCardCount) {
    $http_kernel = $this->container->get('http_kernel');
    $request = Request::create('/code-challenge/card-grid', 'GET',
      [
        'rows'=> $rows,
        'columns' => $columns,
      ]);
    $response = $http_kernel->handle($request, HttpKernelInterface::SUB_REQUEST);
    $json = json_decode($response->getContent());
    $this->assertTrue($json->meta->success);
    $this->assertEquals($cardCount, $json->meta->cardCount);
    $this->assertEquals($uniqueCardCount, $json->meta->uniqueCardCount);
    $this->assertCount($uniqueCardCount, $json->meta->uniqueCards);
    $this->assertCount($rows, $json->data->cards);
  }

}
