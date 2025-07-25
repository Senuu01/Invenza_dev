<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\StockRequest;

class StockRequestNotification extends Notification
{
    use Queueable;

    public $stockRequest;
    public $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(StockRequest $stockRequest, $type)
    {
        $this->stockRequest = $stockRequest;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $stockRequest = $this->stockRequest;
        $product = $stockRequest->product;
        $supplier = $stockRequest->supplier;
        $requester = $stockRequest->requestedBy;

        switch ($this->type) {
            case 'new_request':
                return (new MailMessage)
                    ->subject("New Stock Request: {$product->name}")
                    ->greeting("Hello {$notifiable->name},")
                    ->line("A new stock request has been submitted by **{$requester->name}**.")
                    ->line("**Product:** {$product->name}")
                    ->line("**Supplier:** {$supplier->name}")
                    ->line("**Quantity Requested:** {$stockRequest->quantity_requested}")
                    ->line("**Notes:** " . ($stockRequest->notes ?: 'No notes provided'))
                    ->action('Review Request', route('stock-requests.show', $stockRequest))
                    ->line('Please review and approve or reject this stock request.');

            case 'approved':
                return (new MailMessage)
                    ->subject("Stock Request Approved: {$product->name}")
                    ->greeting("Hello {$notifiable->name},")
                    ->line("Your stock request has been **approved** by the administrator.")
                    ->line("**Product:** {$product->name}")
                    ->line("**Supplier:** {$supplier->name}")
                    ->line("**Quantity Requested:** {$stockRequest->quantity_requested}")
                    ->line("**Admin Notes:** " . ($stockRequest->admin_notes ?: 'No additional notes'))
                    ->action('View Request', route('stock-requests.show', $stockRequest))
                    ->line('The stock will be added to inventory once the order is completed.');

            case 'rejected':
                return (new MailMessage)
                    ->subject("Stock Request Rejected: {$product->name}")
                    ->greeting("Hello {$notifiable->name},")
                    ->line("Your stock request has been **rejected** by the administrator.")
                    ->line("**Product:** {$product->name}")
                    ->line("**Supplier:** {$supplier->name}")
                    ->line("**Quantity Requested:** {$stockRequest->quantity_requested}")
                    ->line("**Reason:** " . ($stockRequest->admin_notes ?: 'No reason provided'))
                    ->action('View Request', route('stock-requests.show', $stockRequest))
                    ->line('Please contact the administrator if you have any questions.');

            case 'completed':
                return (new MailMessage)
                    ->subject("Stock Request Completed: {$product->name}")
                    ->greeting("Hello {$notifiable->name},")
                    ->line("Your stock request has been **completed** and the inventory has been updated.")
                    ->line("**Product:** {$product->name}")
                    ->line("**Supplier:** {$supplier->name}")
                    ->line("**Quantity Added:** {$stockRequest->quantity_requested}")
                    ->line("**New Stock Level:** {$product->quantity}")
                    ->action('View Product', route('products.show', $product))
                    ->line('The stock is now available in the inventory.');

            default:
                return (new MailMessage)
                    ->subject("Stock Request Update: {$product->name}")
                    ->line("Your stock request has been updated.");
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $stockRequest = $this->stockRequest;
        $product = $stockRequest->product;
        $supplier = $stockRequest->supplier;
        $requester = $stockRequest->requestedBy;

        switch ($this->type) {
            case 'new_request':
                return [
                    'stock_request_id' => $stockRequest->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'supplier_id' => $supplier->id,
                    'supplier_name' => $supplier->name,
                    'requester_id' => $requester->id,
                    'requester_name' => $requester->name,
                    'quantity_requested' => $stockRequest->quantity_requested,
                    'message' => "New stock request for {$product->name} from {$supplier->name}",
                    'type' => 'stock_request_new',
                    'url' => route('stock-requests.show', $stockRequest)
                ];

            case 'approved':
                return [
                    'stock_request_id' => $stockRequest->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'supplier_id' => $supplier->id,
                    'supplier_name' => $supplier->name,
                    'quantity_requested' => $stockRequest->quantity_requested,
                    'message' => "Stock request for {$product->name} has been approved",
                    'type' => 'stock_request_approved',
                    'url' => route('stock-requests.show', $stockRequest)
                ];

            case 'rejected':
                return [
                    'stock_request_id' => $stockRequest->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'supplier_id' => $supplier->id,
                    'supplier_name' => $supplier->name,
                    'quantity_requested' => $stockRequest->quantity_requested,
                    'message' => "Stock request for {$product->name} has been rejected",
                    'type' => 'stock_request_rejected',
                    'url' => route('stock-requests.show', $stockRequest)
                ];

            case 'completed':
                return [
                    'stock_request_id' => $stockRequest->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'supplier_id' => $supplier->id,
                    'supplier_name' => $supplier->name,
                    'quantity_requested' => $stockRequest->quantity_requested,
                    'message' => "Stock request for {$product->name} has been completed",
                    'type' => 'stock_request_completed',
                    'url' => route('products.show', $product)
                ];

            default:
                return [
                    'stock_request_id' => $stockRequest->id,
                    'message' => "Stock request update",
                    'type' => 'stock_request_update'
                ];
        }
    }
}
