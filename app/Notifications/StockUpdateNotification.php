<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;
use App\Models\User;

class StockUpdateNotification extends Notification
{
    use Queueable;

    public $product;
    public $staffUser;
    public $oldQuantity;
    public $newQuantity;
    public $changeType;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product, User $staffUser, $oldQuantity, $newQuantity)
    {
        $this->product = $product;
        $this->staffUser = $staffUser;
        $this->oldQuantity = $oldQuantity;
        $this->newQuantity = $newQuantity;
        $this->changeType = $newQuantity > $oldQuantity ? 'increased' : 'decreased';
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
        $changeAmount = abs($this->newQuantity - $this->oldQuantity);
        $stockStatus = $this->newQuantity <= 10 ? 'LOW STOCK' : ($this->newQuantity == 0 ? 'OUT OF STOCK' : 'Normal');
        
        return (new MailMessage)
            ->subject("Stock Update Alert: {$this->product->name}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Staff member **{$this->staffUser->name}** has updated the stock for **{$this->product->name}**.")
            ->line("**Stock Change:** {$this->oldQuantity} → {$this->newQuantity} ({$this->changeType} by {$changeAmount})")
            ->line("**Current Status:** {$stockStatus}")
            ->action('View Product', route('products.show', $this->product))
            ->line('Please review this stock update and take necessary action if required.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $changeAmount = abs($this->newQuantity - $this->oldQuantity);
        $stockStatus = $this->newQuantity <= 10 ? 'LOW STOCK' : ($this->newQuantity == 0 ? 'OUT OF STOCK' : 'Normal');
        
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'staff_user_id' => $this->staffUser->id,
            'staff_user_name' => $this->staffUser->name,
            'old_quantity' => $this->oldQuantity,
            'new_quantity' => $this->newQuantity,
            'change_amount' => $changeAmount,
            'change_type' => $this->changeType,
            'stock_status' => $stockStatus,
            'message' => "Staff member {$this->staffUser->name} updated stock for {$this->product->name} from {$this->oldQuantity} to {$this->newQuantity}",
            'type' => 'stock_update'
        ];
    }
}
