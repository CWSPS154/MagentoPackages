<?php

namespace CWSPS154\SalesOrderReports\Cron;

use CWSPS154\SalesOrderReports\Model\Report\OrderRegionReport;
use CWSPS154\SalesOrderReports\Model\Report\PendingOrderReport;
use CWSPS154\SalesOrderReports\Model\Report\OrderStatusReport;
use Psr\Log\LoggerInterface;

class UpdateOrderDetails
{
    /**
     * @param LoggerInterface $logger
     * @param PendingOrderReport $pendingOrderReport
     * @param OrderStatusReport $orderStatusReport
     * @param OrderRegionReport $orderRegionReport
     */
    public function __construct(
        public LoggerInterface    $logger,
        public PendingOrderReport $pendingOrderReport,
        public OrderStatusReport  $orderStatusReport,
        public OrderRegionReport  $orderRegionReport,
    ) {
    }

    /**
     * Execute cron for pending orders dashboard
     *
     * @return void
     */
    public function execute(): void
    {
        try {
            $this->pendingOrderReport->setOrderDetails();
            $this->orderStatusReport->setOrderDetails();
            $this->orderRegionReport->setOrderDetails();
            $this->logger->info('Order details updated successfully.');
        } catch (\Exception $e) {
            $this->logger->error('Error while updating order details: ' . $e->getMessage());
        }
    }
}
