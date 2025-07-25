# Stripe Webhook Setup Guide

## 🎯 **Required Webhook Events**

Your e-commerce system is now configured to handle these webhook events:

### **Payment Events (Required):**
- ✅ **`checkout.session.completed`** - When a customer completes checkout
- ✅ **`payment_intent.succeeded`** - When payment is successfully processed  
- ✅ **`payment_intent.payment_failed`** - When payment fails

### **Additional Events (Recommended):**
- ✅ **`payment_intent.canceled`** - When payment is canceled
- ✅ **`invoice.payment_succeeded`** - For subscription payments (future use)
- ✅ **`invoice.payment_failed`** - For failed subscription payments (future use)

## 🚀 **Step-by-Step Setup**

### **1. Create Webhook Endpoint**

1. Go to [Stripe Dashboard](https://dashboard.stripe.com/webhooks)
2. Click **"Add endpoint"**
3. Set **Endpoint URL:** `https://yourdomain.com/stripe/webhook`
4. Click **"Select events"**

### **2. Select Events**

Expand each section and select these events:

#### **Checkout Section:**
- ✅ `checkout.session.completed`

#### **Payment Intents Section:**
- ✅ `payment_intent.succeeded`
- ✅ `payment_intent.payment_failed`
- ✅ `payment_intent.canceled`

#### **Invoices Section:**
- ✅ `invoice.payment_succeeded`
- ✅ `invoice.payment_failed`

### **3. Save and Get Secret**

1. Click **"Add endpoint"**
2. Copy the **Signing secret** (starts with `whsec_`)
3. Add it to your `.env` file:

```env
STRIPE_WEBHOOK_SECRET=whsec_your_actual_webhook_secret_here
```

## 🧪 **Local Development**

For testing locally, use Stripe CLI:

```bash
# Install Stripe CLI
brew install stripe/stripe-cli/stripe

# Login to Stripe
stripe login

# Forward webhooks to local server
stripe listen --forward-to localhost:8000/stripe/webhook
```

This will give you a webhook secret for local testing.

## 📊 **What Each Event Does**

### **`checkout.session.completed`**
- Logs successful checkout completion
- Records customer email and payment amount
- Triggers order creation in your system

### **`payment_intent.succeeded`**
- Updates order payment status to "paid"
- Logs successful payment details
- Confirms payment processing

### **`payment_intent.payment_failed`**
- Updates order payment status to "failed"
- Logs payment failure details
- Handles failed payment gracefully

### **`payment_intent.canceled`**
- Updates order status to "cancelled"
- Updates payment status to "failed"
- Logs payment cancellation

### **`invoice.payment_succeeded`** (Future)
- Handles subscription payment success
- Logs invoice payment details
- Ready for subscription features

### **`invoice.payment_failed`** (Future)
- Handles subscription payment failure
- Logs failed invoice details
- Ready for subscription features

## 🔍 **Testing Webhooks**

### **Test with Stripe CLI:**
```bash
# Test checkout session completed
stripe trigger checkout.session.completed

# Test payment intent succeeded
stripe trigger payment_intent.succeeded

# Test payment intent failed
stripe trigger payment_intent.payment_failed
```

### **Check Logs:**
```bash
# View Laravel logs
tail -f storage/logs/laravel.log
```

## 🛡️ **Security**

- Webhooks are verified using the signing secret
- Invalid signatures are rejected automatically
- All events are logged for debugging
- Failed webhooks are handled gracefully

## 📝 **Logging**

All webhook events are logged to `storage/logs/laravel.log` with details like:
- Event type
- Customer information
- Payment amounts
- Order IDs
- Success/failure status

Your webhook system is now fully configured and ready for production! 🎉 