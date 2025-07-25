<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;
use App\Models\User;

class LowStockNotification extends Notification
{
    use Queueable;

    public $product;
    public $staffUser;
    public $currentQuantity;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product, User $staffUser, $currentQuantity)
    {
        $this->product = $product;
        $this->staffUser = $staffUser;
        $this->currentQuantity = $currentQuantity;
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
        $stockStatus = $this->currentQuantity == 0 ? 'OUT OF STOCK' : 'LOW STOCK';
        
        return (new MailMessage)
            ->subject("🚨 {$stockStatus} Alert: {$this->product->name}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Staff member **{$this->staffUser->name}** has reported that **{$this->product->name}** is running low on stock.")
            ->line("**Current Stock Level:** {$this->currentQuantity} units")
            ->line("**Status:** {$stockStatus}")
            ->action('View Product', route('products.show', $this->product))
            ->line('Immediate action may be required to restock this item.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $stockStatus = $this->currentQuantity == 0 ? 'OUT OF STOCK' : 'LOW STOCK';
        
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'staff_user_id' => $this->staffUser->id,
            'staff_user_name' => $this->staffUser->name,
            'current_quantity' => $this->currentQuantity,
            'stock_status' => $stockStatus,
            'message' => "Staff member {$this->staffUser->name} reported low stock for {$this->product->name} (Current: {$this->currentQuantity} units)",
            'type' => 'low_stock_alert'
        ];
    }
}