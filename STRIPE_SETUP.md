# Stripe Setup Guide

## Environment Variables

Add the following variables to your `.env` file:

```env
STRIPE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET=sk_test_your_secret_key_here
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here
```

## Getting Your Stripe Keys

1. **Sign up for Stripe**: Go to [stripe.com](https://stripe.com) and create an account
2. **Get your API keys**: 
   - Go to Dashboard > Developers > API keys
   - Copy your Publishable key and Secret key
   - Use test keys for development (they start with `pk_test_` and `sk_test_`)

## Setting up Webhooks (Optional)

1. **Create a webhook endpoint**:
   - Go to Dashboard > Developers > Webhooks
   - Click "Add endpoint"
   - Set the endpoint URL to: `https://yourdomain.com/stripe/webhook`
   - Select events: `checkout.session.completed`, `payment_intent.succeeded`, `payment_intent.payment_failed`
   - Copy the webhook signing secret

2. **For local development**:
   - Use Stripe CLI: `stripe listen --forward-to localhost:8000/stripe/webhook`
   - Or use ngrok: `ngrok http 8000` and use the ngrok URL

## Testing

Use these test card numbers:
- **Success**: 4242 4242 4242 4242
- **Decline**: 4000 0000 0000 0002
- **Requires Authentication**: 4000 0025 0000 3155

## Features

✅ **Side Cart Panel**: Beautiful sliding cart with item management
✅ **Quantity Controls**: Add/remove items with real-time updates
✅ **Stripe Checkout**: Secure payment processing with Stripe
✅ **Order Management**: Complete order tracking and management
✅ **Real-time Updates**: Cart count and totals update instantly
✅ **Responsive Design**: Works on all devices
✅ **Error Handling**: Comprehensive error handling and user feedback

## How It Works

1. **Add to Cart**: Click the cart icon on any product
2. **Manage Cart**: Use the side cart panel to adjust quantities or remove items
3. **Checkout**: Click "Buy Now" to proceed to Stripe checkout
4. **Payment**: Complete payment securely through Stripe
5. **Order Confirmation**: Get redirected back with order details

The system automatically:
- Creates Stripe checkout sessions
- Processes payments securely
- Creates orders in the database
- Updates product inventory
- Sends confirmation emails
- Handles webhook events 