<?php
namespace predictionio\tests\Unit;

use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use predictionio\EventClient;

class EventClientTest extends \PHPUnit_Framework_TestCase {
  protected $eventClient;
  protected $history;

  const APP_ID = 8;

  protected function setUp() {
    $this->eventClient=new EventClient(self::APP_ID);
    $this->history=new History();
    $mock = new Mock([new Response(200)]);
    $this->eventClient->client->getEmitter()->attach($this->history);
    $this->eventClient->client->getEmitter()->attach($mock);
  }

  public function testSetUser() {
    $this->eventClient->setUser(1,array('age'=>20));
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('$set',$body['event']);
    $this->assertEquals('pio_user',$body['entityType']);
    $this->assertEquals(1,$body['entityId']);
    $this->assertEquals(self::APP_ID,$body['appId']);
    $this->assertEquals(20,$body['properties']['age']);
    $this->assertNotNull($body['eventTime']);
    $this->assertEquals('POST',$request->getMethod());
    $this->assertEquals('http://localhost:7070/events.json',$request->getUrl());
  }

  public function testSetUserWithEventTime() {
    $eventTime='1982-09-25T01:23:45+0800';

    $this->eventClient->setUser(1,array('age'=>20), $eventTime);
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('$set',$body['event']);
    $this->assertEquals($eventTime, $body['eventTime']);
  }

  public function testUnsetUser() {
    $this->eventClient->unsetUser(1,array('age'=>20));
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('$unset',$body['event']);
    $this->assertEquals('pio_user',$body['entityType']);
    $this->assertEquals(1,$body['entityId']);
    $this->assertEquals(self::APP_ID,$body['appId']);
    $this->assertEquals(20,$body['properties']['age']);
    $this->assertNotNull($body['eventTime']);
    $this->assertEquals('POST',$request->getMethod());
    $this->assertEquals('http://localhost:7070/events.json',$request->getUrl());
  }

  public function testUnsetUserWithEventTime() {
    $eventTime='1982-09-25T01:23:45+0800';

    $this->eventClient->unsetUser(1,array('age'=>20), $eventTime);
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('$unset',$body['event']);
    $this->assertEquals($eventTime, $body['eventTime']);
  }

  /**
   * @expectedException predictionio\PredictionIOAPIError
   */
  public function testUnsetUserWithoutProperties() {
    $this->eventClient->unsetUser(1, array());
  }
  
  public function testDeleteUser() {
    $this->eventClient->deleteUser(1);
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('$delete',$body['event']);
    $this->assertEquals('pio_user',$body['entityType']);
    $this->assertEquals(1,$body['entityId']);
    $this->assertEquals(self::APP_ID,$body['appId']);
    $this->assertNotNull($body['eventTime']);
    $this->assertEquals('POST',$request->getMethod());
    $this->assertEquals('http://localhost:7070/events.json',$request->getUrl());
  }

  public function testDeleteUserWithEventTime() {
    $eventTime='1982-09-25T01:23:45+0800';

    $this->eventClient->deleteUser(1, $eventTime);
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('$delete',$body['event']);
    $this->assertEquals($eventTime, $body['eventTime']);
  }

  public function testSetItem() {
    $this->eventClient->setItem(1,array('type'=>'book'));
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('$set',$body['event']);
    $this->assertEquals('pio_item',$body['entityType']);
    $this->assertEquals(1,$body['entityId']);
    $this->assertEquals(self::APP_ID,$body['appId']);
    $this->assertEquals('book',$body['properties']['type']);
    $this->assertNotNull($body['eventTime']);
    $this->assertEquals('POST',$request->getMethod());
    $this->assertEquals('http://localhost:7070/events.json',$request->getUrl());
  }

  public function testSetItemWithEventTime() {
    $eventTime='1982-09-25T01:23:45+0800';

    $this->eventClient->setItem(1,array('type'=>'book'), $eventTime);
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('$set',$body['event']);
    $this->assertEquals($eventTime, $body['eventTime']);
  }

  public function testUnsetItem() {
    $this->eventClient->unsetItem(1,array('type'=>'book'));
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('$unset',$body['event']);
    $this->assertEquals('pio_item',$body['entityType']);
    $this->assertEquals(1,$body['entityId']);
    $this->assertEquals(self::APP_ID,$body['appId']);
    $this->assertEquals('book',$body['properties']['type']);
    $this->assertNotNull($body['eventTime']);
    $this->assertEquals('POST',$request->getMethod());
    $this->assertEquals('http://localhost:7070/events.json',$request->getUrl());
  }

  public function testUnsetItemWithEventTime() {
    $eventTime='1982-09-25T01:23:45+0800';

    $this->eventClient->unsetItem(1,array('type'=>'book'), $eventTime);
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('$unset',$body['event']);
    $this->assertEquals($eventTime, $body['eventTime']);
  }

  /**
   * @expectedException predictionio\PredictionIOAPIError
   */
  public function testUnsetItemWithoutProperties() {
    $this->eventClient->unsetItem(1, array());
  }

  public function testDeleteItem() {
    $this->eventClient->deleteItem(1);
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('$delete',$body['event']);
    $this->assertEquals('pio_item',$body['entityType']);
    $this->assertEquals(1,$body['entityId']);
    $this->assertEquals(self::APP_ID,$body['appId']);
    $this->assertNotNull($body['eventTime']);
    $this->assertEquals('POST',$request->getMethod());
    $this->assertEquals('http://localhost:7070/events.json',$request->getUrl());
  }

  public function testDeleteItemWithEventTime() {
    $eventTime='1982-09-25T01:23:45+0800';

    $this->eventClient->deleteItem(1, $eventTime);
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('$delete',$body['event']);
    $this->assertEquals($eventTime, $body['eventTime']);
  }

  public function testRecordAction() {
    $this->eventClient->recordUserActionOnItem('view',1,888, array('count'=>2));
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('view',$body['event']);
    $this->assertEquals('pio_user',$body['entityType']);
    $this->assertEquals(1,$body['entityId']);
    $this->assertEquals('pio_item',$body['targetEntityType']);
    $this->assertEquals(888,$body['targetEntityId']);
    $this->assertEquals(self::APP_ID,$body['appId']);
    $this->assertEquals(2,$body['properties']['count']);
    $this->assertNotNull($body['eventTime']);
    $this->assertEquals('POST',$request->getMethod());
    $this->assertEquals('http://localhost:7070/events.json',$request->getUrl());
  }

  public function testRecordActionWithEventTime() {
    $eventTime='1982-09-25T01:23:45+0800';

    $this->eventClient->recordUserActionOnItem('view',1, self::APP_ID, array(),$eventTime);
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('view',$body['event']);
    $this->assertEquals($eventTime, $body['eventTime']);
  }

  public function testCreateEvent() {
    $this->eventClient->createEvent(array(
                        'appId' => self::APP_ID,
                        'event' => 'my_event',
                        'entityType' => 'user',
                        'entityId' => 'uid',
                        'properties' => array('prop1'=>1,
                                              'prop2'=>'value2',
                                              'prop3'=>array(1,2,3),
                                              'prop4'=>true,
                                              'prop5'=>array('a','b','c'),
                                              'prop6'=>4.56
                                        ),
                        'eventTime' => '2004-12-13T21:39:45.618-07:00'
                       ));
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('my_event',$body['event']);
    $this->assertEquals('user',$body['entityType']);
    $this->assertEquals('uid',$body['entityId']);
    $this->assertEquals(self::APP_ID,$body['appId']);
    $this->assertEquals(1,$body['properties']['prop1']);
    $this->assertEquals('value2',$body['properties']['prop2']);
    $this->assertEquals(array(1,2,3),$body['properties']['prop3']);
    $this->assertEquals(true,$body['properties']['prop4']);
    $this->assertEquals(array('a','b','c'),$body['properties']['prop5']);
    $this->assertEquals(4.56,$body['properties']['prop6']);
    $this->assertEquals('2004-12-13T21:39:45.618-07:00',$body['eventTime']);
    $this->assertEquals('POST',$request->getMethod());
    $this->assertEquals('http://localhost:7070/events.json',$request->getUrl());
  }

  public function testCreateEventUsesAppIdInClient() {
    $this->eventClient->createEvent(array(
                        'appId' => 99,
                        'event' => 'my_event',
                        'entityType' => 'user',
                        'entityId' => 'uid',
                        'properties' => array('prop1'=>1),
                        'eventTime' => '2004-12-13T21:39:45.618-07:00'
                       ));
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    // ignores appId in data and uses the one defined in the event client
    $this->assertEquals(self::APP_ID,$body['appId']);
  }

  public function testGetEvent() {
    $this->eventClient->getEvent('event_id');
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('GET',$request->getMethod());
    $this->assertEquals('http://localhost:7070/events/event_id.json',
                $request->getUrl());
  }

  public function testDeleteAllEvents() {
    $this->eventClient->deleteAllEvents();
    $request=$this->history->getLastRequest();
    $body=json_decode($request->getBody(), true);

    $this->assertEquals('DELETE', $request->getMethod());
    $this->assertEquals('http://localhost:7070/events.json?appId=' . self::APP_ID,
        $request->getUrl());

  }




}
?>

