@extends('layouts.app')

@section('header', 'Notifications')

@section('content')
<div class="container-fluid px-2">
    <div class="row">
        <div class="col-12">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-bell text-primary me-2"></i>
                        All Notifications
                    </h6>
                    <div>
                        <button class="btn btn-outline-modern btn-sm" onclick="markAllAsRead()">
                            <i class="fas fa-check-double me-2"></i>
                            Mark All as Read
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="modern-table">
                            @foreach($notifications as $notification)
                                <div class="notification-item p-3 border-bottom {{ $notification->read_at ? 'read' : 'unread' }}" 
                                     data-notification-id="{{ $notification->id }}">
                                    <div class="d-flex align-items-start">
                                        <div class="notification-icon me-3 {{ $notification->data['type'] === 'stock_update' ? 'bg-warning' : 'bg-info' }}">
                                            <i class="fas {{ $notification->data['type'] === 'stock_update' ? 'fa-edit' : 'fa-bell' }} text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1 {{ $notification->read_at ? 'text-muted' : 'fw-bold' }}">
                                                        {{ $notification->data['message'] ?? 'Stock Update' }}
                                                    </h6>
                                                    <p class="mb-1 text-muted">
                                                        Stock for <strong>{{ $notification->data['product_name'] }}</strong> 
                                                        updated from <strong>{{ $notification->data['old_quantity'] }}</strong> 
                                                        to <strong>{{ $notification->data['new_quantity'] }}</strong>
                                                    </p>
                                                    <small class="text-muted">
                                                        Updated by {{ $notification->data['staff_user_name'] }} • 
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                                <div class="ms-3">
                                                    @if(!$notification->read_at)
                                                        <span class="badge bg-primary">New</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="p-3">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash text-muted fs-1 mb-3"></i>
                            <h5 class="text-muted">No notifications yet</h5>
                            <p class="text-muted">You'll see notifications here when staff members update product stock.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAllAsRead() {
    fetch('/api/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error marking all notifications as read:', error);
    });
}

// Mark individual notification as read when clicked
document.querySelectorAll('.notification-item').forEach(item => {
    item.addEventListener('click', function() {
        const notificationId = this.getAttribute('data-notification-id');
        if (notificationId) {
            fetch(`/api/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.classList.add('read');
                    this.classList.remove('unread');
                    this.querySelector('.badge')?.remove();
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }
    });
});
</script>

<style>
.notification-item {
    transition: background-color 0.3s ease;
    cursor: pointer;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item.unread {
    background-color: #fff3cd;
}

.notification-item.read {
    background-color: #ffffff;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
</style>
@endsection 