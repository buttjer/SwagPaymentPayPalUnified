<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace SwagPaymentPayPalUnified\Tests\Unit\PayPalBundle\Structs;

use SwagPaymentPayPalUnified\PayPalBundle\Structs\Webhook;

class WebhookStructTest extends \PHPUnit_Framework_TestCase
{
    public function test_getId()
    {
        $struct = Webhook::fromArray(['id' => 10]);

        $this->assertEquals(10, $struct->getId());
    }

    public function test_getCreationTime()
    {
        $struct = Webhook::fromArray(['create_time' => '01-01-1970']);

        $this->assertEquals('01-01-1970', $struct->getCreationTime());
    }

    public function test_getResourceType()
    {
        $struct = Webhook::fromArray(['resource_type' => 'Test']);

        $this->assertEquals('Test', $struct->getResourceType());
    }

    public function test_getEventType()
    {
        $struct = Webhook::fromArray(['event_type' => 'Test-Event']);

        $this->assertEquals('Test-Event', $struct->getEventType());
    }

    public function test_getSummary()
    {
        $struct = Webhook::fromArray(['summary' => 'Test notification triggered in PHPUnit']);

        $this->assertEquals('Test notification triggered in PHPUnit', $struct->getSummary());
    }

    public function test_getResource()
    {
        $struct = Webhook::fromArray(['resource' => ['name' => 'test']]);

        $this->assertEquals('test', $struct->getResource()['name']);
    }

    public function test_toArray()
    {
        $data = [
            'create_time' => '01-01-1970',
            'summary' => 'Test object',
            'resource_type' => 'Test',
            'event_type' => 'Test event',
            'id' => 'Test id',
            'resource' => [
                'name' => 'Test Resource',
            ],
        ];

        $struct = Webhook::fromArray($data);
        $data = $struct->toArray();

        $this->assertEquals('01-01-1970', $data['creationTime']);
        $this->assertEquals('Test object', $data['summary']);
        $this->assertEquals('Test', $data['resourceType']);
        $this->assertEquals('Test event', $data['eventType']);
        $this->assertEquals('Test id', $data['id']);
        $this->assertEquals('Test Resource', $data['resource']['name']);
    }
}
