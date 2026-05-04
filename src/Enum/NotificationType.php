<?php

namespace App\Enum;

enum NotificationType: string
{
    case InvoiceCreated = 'invoice_created';
    case PaymentReceived = 'payment_received';
    case InvoiceOverdue = 'invoice_overdue';
}
