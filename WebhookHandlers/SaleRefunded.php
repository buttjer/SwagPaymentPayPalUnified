<?php
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SwagPaymentPayPalUnified\WebhookHandlers;

use Shopware\Components\Model\ModelManager;
use Shopware\Models\Order\Order;
use Shopware\Models\Order\Status;
use SwagPaymentPayPalUnified\Components\PaymentStatus;
use SwagPaymentPayPalUnified\PayPalBundle\Components\LoggerServiceInterface;
use SwagPaymentPayPalUnified\PayPalBundle\Components\Webhook\WebhookEventTypes;
use SwagPaymentPayPalUnified\PayPalBundle\Components\Webhook\WebhookHandler;
use SwagPaymentPayPalUnified\PayPalBundle\Structs\Webhook;

class SaleRefunded implements WebhookHandler
{
    /**
     * @var LoggerServiceInterface
     */
    private $logger;

    /**
     * @var ModelManager
     */
    private $modelManager;

    /**
     * @param LoggerServiceInterface $logger
     * @param ModelManager           $modelManager
     */
    public function __construct(LoggerServiceInterface $logger, ModelManager $modelManager)
    {
        $this->logger = $logger;
        $this->modelManager = $modelManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return WebhookEventTypes::PAYMENT_SALE_REFUNDED;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(Webhook $webhook)
    {
        try {
            /** @var Order $orderRepository */
            $orderRepository = $this->modelManager->getRepository(Order::class)->findOneBy(['temporaryId' => $webhook->getResource()['parent_payment']]);
            /** @var Status $orderStatusModel */
            $orderStatusModel = $this->modelManager->getRepository(Status::class)->find(PaymentStatus::PAYMENT_STATUS_REFUNDED);

            if ($orderRepository === null) {
                $this->logger->error('[SaleRefunded-Webhook] Could not find associated order with the temporaryID ' . $webhook->getResource()['parent_payment'], ['webhook' => $webhook->toArray()]);

                return false;
            }

            //Set the payment status to "completely payed"
            $orderRepository->setPaymentStatus($orderStatusModel);

            $this->modelManager->flush($orderRepository);

            return true;
        } catch (\Exception $ex) {
            $this->logger->error('[SaleRefunded-Webhook] Could not update entity', ['message' => $ex->getMessage(), 'stacktrace' => $ex->getTrace()]);
        }

        return false;
    }
}
