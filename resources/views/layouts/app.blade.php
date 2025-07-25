<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Invenza') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
        <style>
            /* Smooth Scrolling for entire website */
            html {
                scroll-behavior: smooth;
                scroll-padding-top: 20px;
            }

            /* Custom scrollbar for webkit browsers */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }

            ::-webkit-scrollbar-track {
                background: #f7fafc;
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #cbd5e0 0%, #a0aec0 100%);
                border-radius: 4px;
                transition: all 0.3s ease;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #a0aec0 0%, #718096 100%);
            }

            /* Smooth scrolling for Firefox */
            * {
                scrollbar-width: thin;
                scrollbar-color: #cbd5e0 #f7fafc;
            }

            /* Smooth transitions for all interactive elements */
            a, button, .btn, .nav-link, .dropdown-item, .form-control, .form-select, .card, .modern-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* Enhanced smooth scrolling for containers */
            .main-content, .sidebar, .container-fluid, .content-area {
                scroll-behavior: smooth;
            }

            /* Content area - the scrollable container */
            .content-area {
                flex: 1;
                overflow-y: auto;
                overflow-x: hidden;
                padding: 24px;
                background: #f8fafc;
                scroll-behavior: smooth;
                position: relative;
                /* Prevent white space gaps */
                margin: 0;
                border: none;
                outline: none;
            }

            /* Ensure content fills the area properly */
            .content-area > * {
                margin: 0 0 1.5rem 0;
            }

            .content-area > *:last-child {
                margin-bottom: 0;
            }

            /* Fix viewport and body setup */
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
                overflow-x: hidden;
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                background: #f8fafc;
                color: #334155;
                /* prevent bounce/elastic scrolling globally */
                overscroll-behavior: none;
                -webkit-overflow-scrolling: touch;
            }

            /* App container - main layout wrapper */
            .app-container {
                display: flex;
                height: 100vh;
                overflow: hidden;
                /* Prevent overscroll behavior */
                overscroll-behavior: none;
                position: relative;
            }

            /* Fix container fluid to prevent gaps */
            .container-fluid {
                padding-left: 8px;
                padding-right: 8px;
                margin: 0;
                width: 100%;
            }

            :root {
                --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
                --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
                --info-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
                
                --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
                --sidebar-hover: rgba(148, 163, 184, 0.1);
                --sidebar-active: rgba(59, 130, 246, 0.15);
                
                --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                --card-shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                --card-shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                
                --border-radius: 12px;
                --border-radius-lg: 16px;
                --border-radius-xl: 20px;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            /* Remove duplicate body styles - handled above */
            
            /* Modern Sidebar */
            .sidebar {
                background: var(--sidebar-bg);
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
                width: 280px;
                flex-shrink: 0;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                z-index: 1000;
                height: 100vh;
                overflow-y: auto;
                overflow-x: hidden;
                /* Prevent elastic/bounce scrolling and white gaps */
                overscroll-behavior: contain;
                -webkit-overflow-scrolling: touch;
                /* Additional safari/webkit fixes */
                -webkit-transform: translateZ(0);
                transform: translateZ(0);
            }

            /* Cool Pull-to-Refresh Animation */
            .sidebar-pull-indicator {
                position: absolute;
                top: -60px;
                left: 50%;
                transform: translateX(-50%);
                width: 40px;
                height: 40px;
                background: rgba(59, 130, 246, 0.1);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 10;
                backdrop-filter: blur(10px);
                border: 2px solid rgba(59, 130, 246, 0.2);
            }

            .sidebar-pull-indicator.visible {
                opacity: 1;
                top: 10px;
            }

            .sidebar-pull-indicator.active {
                background: rgba(59, 130, 246, 0.2);
                border-color: rgba(59, 130, 246, 0.4);
                transform: translateX(-50%) scale(1.1);
            }

            .sidebar-pull-indicator i {
                color: #3b82f6;
                font-size: 16px;
                transition: transform 0.3s ease;
            }

            .sidebar-pull-indicator.active i {
                transform: rotate(180deg);
            }

            /* Scroll Progress Indicator */
            .sidebar-scroll-progress {
                position: absolute;
                right: 0;
                top: 0;
                width: 3px;
                height: 100%;
                background: rgba(148, 163, 184, 0.1);
                z-index: 5;
                border-radius: 2px;
            }

            /* Sidebar Glow Effect on Scroll */
            .sidebar.scrolling {
                box-shadow: 4px 0 32px rgba(59, 130, 246, 0.15), 4px 0 24px rgba(0, 0, 0, 0.15);
            }

            /* Cool ripple effect on scroll */
            .sidebar-ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(59, 130, 246, 0.1);
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
                z-index: 1;
            }

            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }

            /* Enhanced scroll animations */
            @keyframes pullRefresh {
                0% {
                    transform: translateX(-50%) translateY(-20px) scale(0.8);
                    opacity: 0;
                }
                50% {
                    transform: translateX(-50%) translateY(0px) scale(1.1);
                    opacity: 1;
                }
                100% {
                    transform: translateX(-50%) translateY(0px) scale(1);
                    opacity: 1;
                }
            }

            .sidebar-pull-indicator.refreshing {
                animation: pullRefresh 0.5s ease-out;
            }

            .sidebar-pull-indicator.refreshing i {
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            .sidebar::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.02)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.02)"/><circle cx="25" cy="75" r="1" fill="rgba(255,255,255,0.02)"/><circle cx="75" cy="25" r="1" fill="rgba(255,255,255,0.02)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                pointer-events: none;
            }
            
            /* Cart Icon Styles */
            .cart-icon-container {
                position: relative;
                margin-right: 15px;
            }
            
            .cart-btn {
                background: none;
                border: none;
                color: #64748b;
                font-size: 18px;
                padding: 8px;
                border-radius: 8px;
                transition: all 0.3s ease;
                position: relative;
            }
            
            .cart-btn:hover {
                color: #3b82f6;
                background: rgba(59, 130, 246, 0.1);
                transform: translateY(-2px);
            }
            
            .cart-badge {
                position: absolute;
                top: -5px;
                right: -5px;
                background: #ef4444;
                color: white;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                font-size: 11px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                animation: cartPulse 2s infinite;
            }
            
            @keyframes cartPulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
            
            /* Side Cart Panel Styles */
            .side-cart-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .side-cart-overlay.active {
                opacity: 1;
                visibility: visible;
            }
            
            .side-cart-panel {
                position: fixed;
                top: 0;
                right: -400px;
                width: 400px;
                height: 100vh;
                background: white;
                box-shadow: -5px 0 30px rgba(0, 0, 0, 0.15);
                z-index: 1050;
                transition: right 0.3s ease;
                display: flex;
                flex-direction: column;
            }
            
            .side-cart-panel.active {
                right: 0;
            }
            
            .cart-header {
                padding: 20px;
                border-bottom: 1px solid #e2e8f0;
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }
            
            .cart-title {
                margin: 0;
                font-weight: 600;
                font-size: 18px;
            }
            
            .cart-close-btn {
                background: none;
                border: none;
                color: white;
                font-size: 18px;
                padding: 8px;
                border-radius: 8px;
                transition: all 0.3s ease;
            }
            
            .cart-close-btn:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: rotate(90deg);
            }
            
            .cart-content {
                flex: 1;
                overflow-y: auto;
                padding: 20px;
            }
            
            .cart-item {
                display: flex;
                align-items: center;
                padding: 15px;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                margin-bottom: 15px;
                background: white;
                transition: all 0.3s ease;
            }
            
            .cart-item:hover {
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transform: translateY(-2px);
            }
            
            .cart-item-image {
                width: 60px;
                height: 60px;
                border-radius: 8px;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 15px;
                color: #3b82f6;
                font-size: 20px;
            }
            
            .cart-item-details {
                flex: 1;
            }
            
            .cart-item-name {
                font-weight: 600;
                color: #1e293b;
                margin-bottom: 5px;
                font-size: 14px;
            }
            
            .cart-item-price {
                color: #10b981;
                font-weight: 600;
                font-size: 14px;
            }
            
            .cart-item-controls {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-top: 10px;
            }
            
            .quantity-control {
                display: flex;
                align-items: center;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                overflow: hidden;
            }
            
            .quantity-btn {
                background: #f8fafc;
                border: none;
                padding: 8px 12px;
                color: #64748b;
                transition: all 0.3s ease;
            }
            
            .quantity-btn:hover {
                background: #e2e8f0;
                color: #1e293b;
            }
            
            .quantity-input {
                border: none;
                text-align: center;
                width: 50px;
                padding: 8px;
                font-size: 14px;
                background: white;
            }
            
            .quantity-input:focus {
                outline: none;
                background: #f8fafc;
            }
            
            .remove-item-btn {
                background: #ef4444;
                border: none;
                color: white;
                padding: 8px 12px;
                border-radius: 8px;
                font-size: 12px;
                transition: all 0.3s ease;
            }
            
            .remove-item-btn:hover {
                background: #dc2626;
                transform: scale(1.05);
            }
            
            .cart-empty-state {
                text-align: center;
                padding: 40px 20px;
            }
            
            .empty-cart-icon {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                color: white;
                font-size: 30px;
            }
            
            .cart-footer {
                padding: 20px;
                border-top: 1px solid #e2e8f0;
                background: #f8fafc;
            }
            
            .cart-summary {
                margin-bottom: 20px;
            }
            
            .summary-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 8px;
                font-size: 14px;
            }
            
            .summary-row.total {
                font-weight: 600;
                font-size: 16px;
                color: #1e293b;
                border-top: 1px solid #e2e8f0;
                padding-top: 8px;
                margin-top: 8px;
            }
            
            .cart-actions .btn {
                padding: 15px;
                font-weight: 600;
                border-radius: 12px;
                transition: all 0.3s ease;
            }
            
            .cart-actions .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
            }
            
            .cart-actions .btn:disabled {
                opacity: 0.6;
                cursor: not-allowed;
                transform: none;
                box-shadow: none;
            }
            
            /* Responsive Design */
            @media (max-width: 768px) {
                .side-cart-panel {
                    width: 100%;
                    right: -100%;
                }
                
                .cart-item {
                    padding: 12px;
                }
                
                .cart-item-image {
                    width: 50px;
                    height: 50px;
                    font-size: 16px;
                }
            }
            
            .sidebar-header {
                padding: 24px;
                border-bottom: 1px solid rgba(148, 163, 184, 0.1);
                position: relative;
            }
            
            .sidebar-logo {
                display: flex;
                align-items: center;
                gap: 12px;
            }
            
            .logo-icon {
                width: 44px;
                height: 44px;
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                border-radius: var(--border-radius);
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
                position: relative;
                overflow: hidden;
            }
            
            .logo-icon::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
                transform: rotate(45deg);
                animation: shimmer 3s infinite;
            }
            
            @keyframes shimmer {
                0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
                100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
            }
            
            .logo-icon i {
                font-size: 20px;
                color: white;
                z-index: 1;
            }
            
            .logo-text {
                color: white;
                font-size: 20px;
                font-weight: 700;
                letter-spacing: -0.025em;
            }
            
            .logo-subtitle {
                color: #94a3b8;
                font-size: 12px;
                font-weight: 500;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-top: 2px;
            }
            
            /* Navigation */
            .sidebar-nav {
                padding: 16px 0;
            }
            
            .nav-section {
                margin-bottom: 32px;
            }
            
            .nav-section-title {
                color: #64748b;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                padding: 0 24px 8px;
                margin-bottom: 8px;
            }
            
            .nav-item {
                position: relative;
                margin: 2px 16px;
                border-radius: var(--border-radius);
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .nav-link {
                display: flex;
                align-items: center;
                padding: 12px 16px;
                color: #cbd5e1;
                text-decoration: none;
                font-weight: 500;
                font-size: 14px;
                border-radius: var(--border-radius);
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }
            
            .nav-link::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
                opacity: 0;
                transition: opacity 0.2s ease;
            }
            
            .nav-link:hover {
                color: white;
                background: var(--sidebar-hover);
                transform: translateX(4px);
            }
            
            .nav-link:hover::before {
                opacity: 1;
            }
            
            .nav-link.active {
                color: white;
                background: var(--sidebar-active);
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            }
            
            .nav-link.active::before {
                opacity: 1;
            }
            
            .nav-link.active::after {
                content: '';
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 3px;
                height: 20px;
                background: linear-gradient(180deg, #3b82f6, #1d4ed8);
                border-radius: 2px;
            }
            
            .nav-icon {
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 12px;
                font-size: 16px;
                transition: transform 0.2s ease;
            }
            
            .nav-link:hover .nav-icon {
                transform: scale(1.1);
            }
            
            .nav-text {
                flex: 1;
                font-weight: 500;
            }
            
            .nav-badge {
                background: linear-gradient(135deg, #ef4444, #dc2626);
                color: white;
                font-size: 11px;
                font-weight: 600;
                padding: 2px 8px;
                border-radius: 12px;
                min-width: 20px;
                text-align: center;
            }
            
            /* Sidebar Badge Styling */
            .nav-link .badge {
                position: absolute;
                top: 8px;
                right: 8px;
                font-size: 10px;
                font-weight: 700;
                padding: 2px 6px;
                border-radius: 8px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                z-index: 5;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            
            .nav-link .badge.bg-warning {
                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
                color: white !important;
            }
            
            .nav-link .badge.bg-info {
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
                color: white !important;
            }
            
            /* Main Content */
            .main-content {
                flex: 1;
                display: flex;
                flex-direction: column;
                height: 100vh;
                overflow: hidden;
                background: #f8fafc;
                position: relative;
            }
            
            /* Top Navigation */
            .top-nav {
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border-bottom: 2px solid #e2e8f0;
                padding: 20px 32px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                position: sticky;
                top: 0;
                z-index: 100;
                flex-shrink: 0;
                backdrop-filter: blur(15px);
                min-height: 80px;
            }
            
            .top-nav-left {
                display: flex;
                align-items: center;
                gap: 24px;
                flex: 1;
            }
            
            .page-title {
                font-size: 28px;
                font-weight: 800;
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin: 0;
                white-space: nowrap;
                letter-spacing: -0.5px;
            }
            
            /* Animated Product Showcase with Photos */
            .product-showcase {
                display: flex;
                align-items: center;
                gap: 16px;
                max-width: 520px;
                overflow: hidden;
                margin-left: 20px;
            }
            
            .showcase-container {
                position: relative;
                width: 100%;
                height: 85px;
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border-radius: 16px;
                border: 1px solid #e2e8f0;
                overflow: hidden;
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
                backdrop-filter: blur(10px);
            }
            
            .showcase-slider {
                display: flex;
                align-items: center;
                height: 100%;
                transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .showcase-item {
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 100%;
                padding: 12px 16px;
                opacity: 0.7;
                transition: all 0.5s ease;
                transform: scale(0.95);
            }
            
            .showcase-item.active {
                opacity: 1;
                transform: scale(1);
            }
            
            .showcase-item-image {
                width: 56px;
                height: 56px;
                border-radius: 8px;
                overflow: hidden;
                flex-shrink: 0;
                background: #f1f5f9;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 2px solid #e2e8f0;
                transition: all 0.3s ease;
            }
            
            .showcase-item.active .showcase-item-image {
                border-color: #3b82f6;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            }
            
            .showcase-item-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }
            
            .showcase-item.active .showcase-item-image img {
                transform: scale(1.05);
            }
            
            .showcase-item-image .placeholder-icon {
                color: #94a3b8;
                font-size: 20px;
            }
            
            .showcase-item-content {
                flex: 1;
                min-width: 0;
            }
            
            .showcase-item-title {
                font-size: 14px;
                font-weight: 600;
                color: #1e293b;
                margin: 0 0 4px 0;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                line-height: 1.2;
            }
            
            .showcase-item-price {
                font-size: 12px;
                color: #10b981;
                font-weight: 600;
                margin: 0;
            }
            
            .showcase-item-category {
                font-size: 10px;
                color: #64748b;
                margin: 0;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .showcase-indicators {
                display: flex;
                gap: 4px;
                margin-left: 8px;
            }
            
            .indicator {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: #cbd5e1;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .indicator.active {
                background: #3b82f6;
                transform: scale(1.2);
            }
            
            .indicator:hover {
                background: #3b82f6;
                transform: scale(1.1);
            }
            
            /* Responsive Design */
            @media (max-width: 1024px) {
                .welcome-section {
                    gap: 16px;
                    padding: 6px 12px;
                }
                
                .welcome-text {
                    font-size: 13px;
                }
                
                .quick-stats {
                    gap: 12px;
                }
                
                .stat-item {
                    font-size: 11px;
                }
            }
            
            @media (max-width: 768px) {
                .top-nav-left {
                    gap: 16px;
                }
                
                .product-showcase {
                    max-width: 320px;
                }
                
                .showcase-container {
                    height: 60px;
                }
                
                .showcase-item-image {
                    width: 40px;
                    height: 40px;
                }
                
                .showcase-item-title {
                    font-size: 12px;
                }
                
                .showcase-item-price {
                    font-size: 10px;
                }
                
                .showcase-item-category {
                    font-size: 9px;
                }
                
                .page-title {
                    font-size: 20px;
                }
                
                .welcome-section {
                    display: none;
                }
                
                .quick-actions {
                    gap: 6px;
                }
                
                .quick-action-btn {
                    width: 36px;
                    height: 36px;
                }
            }
            
            @media (max-width: 576px) {
                .product-showcase {
                    display: none;
                }
                
                .top-nav-left {
                    gap: 12px;
                }
            }
            
            .top-nav-actions {
                display: flex;
                align-items: center;
                gap: 24px;
                flex-shrink: 0;
            }
            
            /* Welcome Section */
            .welcome-section {
                display: flex;
                align-items: center;
                gap: 28px;
                padding: 12px 20px;
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border-radius: 16px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
                backdrop-filter: blur(10px);
            }
            
            .welcome-message {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #1e293b;
                font-weight: 600;
            }
            
            .welcome-icon {
                color: #f59e0b;
                font-size: 18px;
                animation: pulse 2s infinite;
                filter: drop-shadow(0 2px 4px rgba(245, 158, 11, 0.3));
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }
            
            .welcome-text {
                font-size: 15px;
                white-space: nowrap;
                font-weight: 600;
                color: #1e293b;
            }
            
            .quick-stats {
                display: flex;
                gap: 20px;
                padding-left: 20px;
                border-left: 2px solid #e2e8f0;
            }
            
            .stat-item {
                display: flex;
                align-items: center;
                gap: 8px;
                color: #64748b;
                font-size: 13px;
                font-weight: 500;
            }
            
            .stat-item i {
                color: #3b82f6;
                font-size: 16px;
                filter: drop-shadow(0 1px 2px rgba(59, 130, 246, 0.2));
            }
            
            .stat-number {
                font-weight: 700;
                color: #1e293b;
                font-size: 14px;
            }
            
            .stat-label {
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                font-size: 11px;
                font-weight: 600;
            }
            
            /* Quick Actions */
            .quick-actions {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .quick-action-btn {
                position: relative;
                width: 44px;
                height: 44px;
                border: none;
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border-radius: 12px;
                color: #64748b;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid #e2e8f0;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            }
            
            .quick-action-btn:hover {
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                color: white;
                transform: translateY(-3px) scale(1.05);
                box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
                border-color: #3b82f6;
            }
            
            .quick-action-btn:active {
                transform: translateY(0);
            }
            
            .action-badge {
                position: absolute;
                top: -6px;
                right: -6px;
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                color: white;
                font-size: 11px;
                font-weight: 700;
                padding: 3px 7px;
                border-radius: 12px;
                border: 2px solid white;
                min-width: 18px;
                text-align: center;
                box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
            }
            
            .deals-badge {
                background: linear-gradient(135deg, #f59e0b, #d97706);
                animation: pulse 2s infinite;
            }
            
            /* Toast Notifications */
            .toast-notification {
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                border-radius: 12px;
                padding: 16px 20px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
                border: 1px solid #e2e8f0;
                z-index: 10000;
                transform: translateX(400px);
                transition: transform 0.3s ease;
                max-width: 350px;
            }
            
            .toast-notification.show {
                transform: translateX(0);
            }
            
            .toast-content {
                display: flex;
                align-items: center;
                gap: 12px;
                font-weight: 500;
            }
            
            .toast-content i {
                font-size: 18px;
            }
            
            .toast-success {
                border-left: 4px solid #10b981;
            }
            
            .toast-success .toast-content i {
                color: #10b981;
            }
            
            .toast-info {
                border-left: 4px solid #3b82f6;
            }
            
            .toast-info .toast-content i {
                color: #3b82f6;
            }
            
            .toast-error {
                border-left: 4px solid #ef4444;
            }
            
            .toast-error .toast-content i {
                color: #ef4444;
            }
            
            /* Notifications */
            .notification-btn {
                position: relative;
                width: 44px;
                height: 44px;
                border: none;
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border-radius: 12px;
                color: #64748b;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 1px solid #e2e8f0;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            }
            
            .notification-btn:hover {
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                color: white;
                transform: translateY(-3px) scale(1.05);
                box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
                border-color: #3b82f6;
            }
            
            .notification-badge {
                position: absolute;
                top: 6px;
                right: 6px;
                width: 10px;
                height: 10px;
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                border-radius: 50%;
                border: 2px solid white;
                box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
                animation: pulse 2s infinite;
            }
            
            /* User Menu */
            .user-menu {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px 16px;
                border-radius: 16px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                border: 1px solid #e2e8f0;
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            }
            
            .user-menu:hover {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                border-color: #cbd5e0;
                transform: translateY(-2px);
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            }

            /* Ultra Modern Dropdown Menus */
            .notification-dropdown,
            .user-dropdown {
                min-width: 380px;
                max-width: 420px;
                border: none;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                box-shadow: 
                    0 25px 50px -12px rgba(0, 0, 0, 0.12),
                    0 20px 25px -5px rgba(0, 0, 0, 0.08),
                    0 0 0 1px rgba(255, 255, 255, 0.5),
                    inset 0 1px 0 rgba(255, 255, 255, 0.9);
                border-radius: 24px;
                padding: 0;
                margin-top: 12px;
                overflow: hidden;
                position: relative;
                animation: dropdownSlideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            @keyframes dropdownSlideIn {
                0% {
                    opacity: 0;
                    transform: translateY(-10px) scale(0.95);
                }
                100% {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .notification-dropdown::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: 
                    linear-gradient(135deg, 
                        rgba(59, 130, 246, 0.02) 0%, 
                        rgba(16, 185, 129, 0.02) 50%,
                        rgba(236, 72, 153, 0.02) 100%
                    );
                pointer-events: none;
                z-index: 1;
            }

            .notification-dropdown > * {
                position: relative;
                z-index: 2;
            }

            .notification-dropdown .dropdown-header,
            .user-dropdown .dropdown-header {
                padding: 20px 24px 16px;
                background: 
                    linear-gradient(135deg, 
                        rgba(248, 250, 252, 0.8) 0%, 
                        rgba(241, 245, 249, 0.9) 100%
                    );
                border-radius: 24px 24px 0 0;
                border-bottom: 1px solid rgba(226, 232, 240, 0.6);
                margin-bottom: 0;
                position: relative;
                overflow: hidden;
            }

            .notification-dropdown .dropdown-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 2px;
                background: linear-gradient(90deg, 
                    #3b82f6 0%, 
                    #8b5cf6 25%, 
                    #ec4899 50%,
                    #10b981 75%,
                    #3b82f6 100%
                );
                background-size: 200% 100%;
                animation: shimmer 3s ease-in-out infinite;
            }

            @keyframes shimmer {
                0%, 100% { background-position: 200% 0; }
                50% { background-position: -200% 0; }
            }

            .notification-dropdown .dropdown-header h6 {
                font-size: 16px;
                font-weight: 700;
                background: linear-gradient(135deg, #1e293b, #475569);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin: 0;
                letter-spacing: -0.025em;
            }

            .notification-dropdown .dropdown-header .badge {
                font-size: 11px;
                font-weight: 700;
                padding: 6px 12px;
                border-radius: 20px;
                background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%) !important;
                border: 2px solid rgba(255, 255, 255, 0.8);
                box-shadow: 
                    0 4px 12px rgba(59, 130, 246, 0.3),
                    0 2px 4px rgba(0, 0, 0, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
                color: white !important;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
                animation: badgePulse 2s ease-in-out infinite;
            }

            @keyframes badgePulse {
                0%, 100% { 
                    transform: scale(1);
                    box-shadow: 
                        0 4px 12px rgba(59, 130, 246, 0.3),
                        0 2px 4px rgba(0, 0, 0, 0.1),
                        inset 0 1px 0 rgba(255, 255, 255, 0.3);
                }
                50% { 
                    transform: scale(1.05);
                    box-shadow: 
                        0 6px 16px rgba(59, 130, 246, 0.4),
                        0 4px 8px rgba(0, 0, 0, 0.15),
                        inset 0 1px 0 rgba(255, 255, 255, 0.3);
                }
            }

            .notification-item {
                padding: 16px 24px !important;
                border-bottom: 1px solid rgba(241, 245, 249, 0.5);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }

            .notification-item::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, 
                    transparent 0%, 
                    rgba(59, 130, 246, 0.1) 50%, 
                    transparent 100%
                );
                transition: left 0.5s ease;
            }

            .notification-item:hover::before {
                left: 100%;
            }

            .notification-item:hover {
                background: 
                    linear-gradient(135deg, 
                        rgba(248, 250, 252, 0.8) 0%, 
                        rgba(241, 245, 249, 0.9) 100%
                    ) !important;
                transform: translateX(4px);
                border-left: 3px solid #3b82f6;
            }

            .notification-item:last-of-type {
                border-bottom: none;
            }

            .notification-icon {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 12px;
                flex-shrink: 0;
            }

            .notification-icon i {
                color: white;
                font-size: 16px;
            }

            .notification-content {
                flex: 1;
                min-width: 0;
            }

            .notification-title {
                font-weight: 600;
                font-size: 14px;
                color: #1f2937;
                margin-bottom: 2px;
            }

            .notification-text {
                font-size: 13px;
                color: #6b7280;
                margin-bottom: 4px;
                line-height: 1.4;
            }

            .notification-time {
                font-size: 11px;
                color: #9ca3af;
                font-weight: 500;
            }

            /* Empty Notifications State - Ultra Modern Design */
            .empty-notifications {
                padding: 48px 32px !important;
                border: none !important;
                background: 
                    linear-gradient(135deg, 
                        rgba(255, 255, 255, 0.95) 0%, 
                        rgba(248, 250, 252, 0.95) 100%
                    ),
                    url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f1f5f9' fill-opacity='0.3'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") !important;
                position: relative;
                overflow: hidden;
                border-radius: 16px !important;
                margin: 8px;
                backdrop-filter: blur(10px);
            }

            .empty-notifications::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: 
                    conic-gradient(from 0deg at 50% 50%, 
                        transparent 0deg, 
                        rgba(59, 130, 246, 0.08) 120deg, 
                        transparent 240deg, 
                        rgba(16, 185, 129, 0.08) 360deg
                    );
                animation: rotate 20s linear infinite;
                pointer-events: none;
                opacity: 0.5;
            }

            .empty-notifications::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: 
                    radial-gradient(circle at 30% 20%, rgba(99, 102, 241, 0.05) 0%, transparent 50%),
                    radial-gradient(circle at 70% 80%, rgba(236, 72, 153, 0.05) 0%, transparent 50%),
                    radial-gradient(circle at 20% 80%, rgba(16, 185, 129, 0.05) 0%, transparent 50%);
                pointer-events: none;
            }

            @keyframes rotate {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-8px); }
            }

            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }

            .empty-notification-icon {
                width: 80px;
                height: 80px;
                border-radius: 24px;
                background: 
                    linear-gradient(135deg, 
                        rgba(255, 255, 255, 0.9) 0%, 
                        rgba(248, 250, 252, 0.8) 100%
                    );
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 24px;
                border: 1px solid rgba(226, 232, 240, 0.8);
                box-shadow: 
                    0 10px 30px rgba(59, 130, 246, 0.1),
                    0 4px 12px rgba(148, 163, 184, 0.08),
                    inset 0 1px 0 rgba(255, 255, 255, 0.9);
                position: relative;
                z-index: 10;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                animation: float 6s ease-in-out infinite;
            }

            .empty-notification-icon::before {
                content: '';
                position: absolute;
                top: -2px;
                left: -2px;
                right: -2px;
                bottom: -2px;
                background: linear-gradient(135deg, 
                    rgba(59, 130, 246, 0.3), 
                    rgba(16, 185, 129, 0.3), 
                    rgba(236, 72, 153, 0.3)
                );
                border-radius: 26px;
                opacity: 0;
                transition: opacity 0.4s ease;
                z-index: -1;
            }

            .empty-notification-icon:hover::before {
                opacity: 1;
                animation: pulse 2s ease-in-out infinite;
            }

            .empty-notification-icon:hover {
                transform: translateY(-4px) scale(1.05);
                box-shadow: 
                    0 20px 40px rgba(59, 130, 246, 0.2),
                    0 8px 20px rgba(148, 163, 184, 0.15),
                    inset 0 1px 0 rgba(255, 255, 255, 0.9);
            }

            .empty-notification-icon i {
                font-size: 32px;
                background: linear-gradient(135deg, #64748b, #3b82f6, #8b5cf6);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                transition: all 0.4s ease;
                filter: drop-shadow(0 2px 4px rgba(59, 130, 246, 0.2));
            }

            .empty-notification-icon:hover i {
                background: linear-gradient(135deg, #3b82f6, #8b5cf6, #ec4899);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                transform: scale(1.1);
                filter: drop-shadow(0 4px 8px rgba(59, 130, 246, 0.3));
            }

            .empty-notification-text {
                text-align: center;
                position: relative;
                z-index: 10;
            }

            .empty-title {
                font-size: 18px;
                font-weight: 800;
                background: linear-gradient(135deg, #1e293b, #475569);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 8px;
                letter-spacing: -0.025em;
                line-height: 1.2;
            }

            .empty-subtitle {
                font-size: 14px;
                color: #64748b;
                font-weight: 500;
                line-height: 1.5;
                margin-bottom: 16px;
                opacity: 0.8;
            }

            /* Add a subtle shine effect */
            .empty-notifications:hover::before {
                animation: rotate 10s linear infinite;
            }

            /* Decorative elements */
            .empty-notification-icon::after {
                content: '✨';
                position: absolute;
                top: -8px;
                right: -8px;
                font-size: 16px;
                opacity: 0;
                transition: all 0.3s ease;
                animation: float 4s ease-in-out infinite reverse;
            }

            .empty-notification-icon:hover::after {
                opacity: 1;
                transform: scale(1.2);
            }

            /* Ultra Modern View All Notifications Link */
            .view-all-notifications {
                padding: 16px 24px !important;
                font-weight: 700 !important;
                font-size: 14px !important;
                background: 
                    linear-gradient(135deg, 
                        rgba(59, 130, 246, 0.08) 0%, 
                        rgba(16, 185, 129, 0.08) 50%,
                        rgba(236, 72, 153, 0.08) 100%
                    ) !important;
                border-top: 1px solid rgba(226, 232, 240, 0.6) !important;
                border-radius: 0 0 24px 24px !important;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
                position: relative;
                overflow: hidden;
                text-decoration: none !important;
            }

            .view-all-notifications::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, 
                    transparent 0%, 
                    rgba(255, 255, 255, 0.3) 50%, 
                    transparent 100%
                );
                transition: left 0.6s ease;
            }

            .view-all-notifications:hover::before {
                left: 100%;
            }

            .view-all-notifications:hover {
                background: 
                    linear-gradient(135deg, 
                        rgba(59, 130, 246, 0.15) 0%, 
                        rgba(16, 185, 129, 0.15) 50%,
                        rgba(236, 72, 153, 0.15) 100%
                    ) !important;
                transform: translateY(-2px) scale(1.02);
                box-shadow: 
                    0 8px 25px rgba(59, 130, 246, 0.15),
                    0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .view-all-notifications span {
                background: linear-gradient(135deg, #3b82f6, #8b5cf6, #ec4899);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                position: relative;
                z-index: 2;
            }

            .view-all-notifications i {
                font-size: 16px !important;
                margin-right: 10px !important;
                background: linear-gradient(135deg, #3b82f6, #8b5cf6);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                position: relative;
                z-index: 2;
                transition: transform 0.3s ease;
            }

            .view-all-notifications:hover i {
                transform: translateX(2px) rotate(5deg);
            }

            /* Dropdown Dividers */
            .notification-dropdown .dropdown-divider {
                border-color: rgba(226, 232, 240, 0.4);
                margin: 0;
                opacity: 0.6;
            }

            .user-dropdown .dropdown-item {
                padding: 10px 20px;
                transition: all 0.2s ease;
            }

            .user-dropdown .dropdown-item:hover {
                background: #f8fafc;
                transform: translateX(2px);
            }
            
            .user-avatar {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: linear-gradient(135deg, #3b82f6, #1d4ed8);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 600;
                font-size: 14px;
            }
            
            .user-info h6 {
                margin: 0;
                font-size: 14px;
                font-weight: 600;
                color: #1e293b;
            }
            
            .user-info p {
                margin: 0;
                font-size: 12px;
                color: #64748b;
            }
            
            /* Content Area styles handled above - preventing duplication */
            
            /* Modern Cards */
            .modern-card {
                background: white;
                border-radius: var(--border-radius-lg);
                box-shadow: var(--card-shadow);
                border: 1px solid #f1f5f9;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }
            
            .modern-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 1px;
                background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.3), transparent);
            }
            
            .modern-card:hover {
                transform: translateY(-4px);
                box-shadow: var(--card-shadow-hover);
            }
            
            .card-gradient {
                position: relative;
                overflow: hidden;
            }
            
            .card-gradient::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(147, 51, 234, 0.05));
                pointer-events: none;
            }
            
            /* Status Badges */
            .status-badge {
                display: inline-block;
                padding: 8px 16px;
                border-radius: 25px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border: none;
                white-space: nowrap;
                text-align: center;
                min-width: 80px;
                line-height: 1;
                position: relative;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                transition: all 0.3s ease;
            }
            
            .status-badge:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            }
            
            .status-success {
                background: #22c55e;
                color: white;
            }
            
            .status-success:hover {
                background: #16a34a;
            }
            
            .status-warning {
                background: #f59e0b;
                color: white;
            }
            
            .status-warning:hover {
                background: #d97706;
            }
            
            .status-danger {
                background: #ef4444;
                color: white;
            }
            
            .status-danger:hover {
                background: #dc2626;
            }
            
            .status-info {
                background: #3b82f6;
                color: white;
            }
            
            .status-info:hover {
                background: #2563eb;
            }
            
            /* Modern Tables */
            .modern-table {
                background: white;
                border-radius: var(--border-radius-lg);
                overflow: hidden;
                box-shadow: var(--card-shadow);
                border: 1px solid #f1f5f9;
            }
            
            .modern-table table {
                width: 100%;
                border-collapse: collapse;
            }
            
            .modern-table table th {
                background: #f8fafc;
                color: #374151;
                font-weight: 600;
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding: 16px 12px;
                text-align: left;
                border-bottom: 2px solid #e2e8f0;
                white-space: nowrap;
            }
            
            .modern-table table td {
                padding: 16px 12px;
                border-bottom: 1px solid #f1f5f9;
                vertical-align: middle;
            }
            
            .modern-table table tbody tr {
                transition: all 0.2s ease;
            }
            
            .modern-table table tbody tr:hover {
                background: #f8fafc;
            }
            
            .modern-table table tbody tr:last-child td {
                border-bottom: none;
            }
            
            .modern-table th {
                padding: 16px 20px;
                text-align: left;
                font-weight: 600;
                font-size: 12px;
                color: #475569;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border-bottom: 1px solid #e2e8f0;
            }
            
            .modern-table td {
                padding: 16px 20px;
                border-bottom: 1px solid #f1f5f9;
                vertical-align: middle;
            }
            
            .modern-table tbody tr {
                transition: all 0.2s ease;
            }
            
            .modern-table tbody tr:hover {
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.02), rgba(147, 51, 234, 0.02));
                transform: scale(1.001);
            }
            
            .modern-table tbody tr:nth-child(even) {
                background: rgba(248, 250, 252, 0.5);
            }
            
            .modern-table tbody tr:nth-child(even):hover {
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.03), rgba(147, 51, 234, 0.03));
            }
            
            /* Modern Buttons */
            .btn-modern {
                padding: 10px 20px;
                border-radius: var(--border-radius);
                font-weight: 600;
                font-size: 14px;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                border: none;
                cursor: pointer;
                position: relative;
                overflow: hidden;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }
            
            .btn-modern::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: left 0.5s;
            }
            
            .btn-modern:hover::before {
                left: 100%;
            }
            
            .btn-modern:hover {
                transform: translateY(-2px);
            }
            
            .btn-primary-modern {
                background: linear-gradient(135deg, #3b82f6, #1d4ed8);
                color: white;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            }
            
            .btn-primary-modern:hover {
                box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
                color: white;
            }
            
            .btn-success-modern {
                background: linear-gradient(135deg, #10b981, #059669);
                color: white;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            }
            
            .btn-outline-modern {
                background: white;
                color: #3b82f6;
                border: 2px solid #3b82f6;
                box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
            }
            
            .btn-outline-modern:hover {
                background: #3b82f6;
                color: white;
                box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
            }
            
            /* Search Bars */
            .modern-search {
                position: relative;
                max-width: 400px;
            }
            
            .modern-search input {
                width: 100%;
                padding: 12px 16px 12px 48px;
                border: 2px solid #e2e8f0;
                border-radius: var(--border-radius);
                font-size: 14px;
                transition: all 0.2s ease;
                background: white;
            }
            
            .modern-search input:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
                transform: translateY(-1px);
            }
            
            .modern-search .search-icon {
                position: absolute;
                left: 16px;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                font-size: 16px;
            }
            
            /* Responsive Design */
            @media (max-width: 1024px) {
                .sidebar {
                    width: 240px;
                }
                
                .content-area {
                    padding: 16px;
                }
            }
            
            @media (max-width: 768px) {
                .app-container {
                    height: 100vh;
                    overflow: hidden;
                }
                
                .sidebar {
                    position: fixed;
                    left: -280px;
                    z-index: 1000;
                    height: 100vh;
                    width: 280px;
                }
                
                .sidebar.mobile-open {
                    left: 0;
                }
                
                .main-content {
                    width: 100%;
                    height: 100vh;
                }
                
                .content-area {
                    padding: 12px;
                }
                
                .top-nav {
                    padding: 12px 16px;
                }
                
                .page-title {
                    font-size: 20px;
                }
            }
            
            @media (max-width: 640px) {
                .modern-card {
                    border-radius: var(--border-radius);
                }
                
                .user-info {
                    display: none;
                }
                
                .top-nav-actions {
                    gap: 8px;
                }
            }
            
            /* Loading Animations */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .animate-fade-in {
                animation: fadeInUp 0.6s ease-out;
            }
            
            .animate-delay-1 { animation-delay: 0.1s; }
            .animate-delay-2 { animation-delay: 0.2s; }
            .animate-delay-3 { animation-delay: 0.3s; }
            .animate-delay-4 { animation-delay: 0.4s; }

            /* Notification Badge Animation */
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.1); }
            }

            .notification-badge {
                animation: pulse 2s infinite;
            }

            /* Mark as read functionality */
            .notification-item.read {
                opacity: 0.7;
                background: #f9fafb;
            }

            .notification-item.read .notification-title {
                font-weight: 500;
                color: #6b7280;
            }
        </style>
    </head>
    <body>
        <div class="app-container">
            <!-- Modern Sidebar -->
            <div class="sidebar" id="sidebar">
                <!-- Pull-to-refresh indicator -->
                <div class="sidebar-pull-indicator" id="pullIndicator">
                    <i class="fas fa-arrow-down"></i>
                </div>
                
                <!-- Scroll progress indicator -->
                <div class="sidebar-scroll-progress" id="scrollProgress"></div>
                
                <div class="sidebar-header">
                    <div class="sidebar-logo">
                        <div class="logo-icon">
                            <i class="fas fa-cube"></i>
                        </div>
                        <div>
                            <div class="logo-text">Invenza</div>
                            <div class="logo-subtitle">
                                @if(auth()->user()->isAdmin())
                                    Admin Panel
                                @elseif(auth()->user()->isStaff())
                                    Staff Panel
                                @else
                                    Customer Portal
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <nav class="sidebar-nav">
                    <div class="nav-section">
                        <div class="nav-section-title">Main Menu</div>
                        
                        <div class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </div>
                        
                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                        <div class="nav-item">
                            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products*') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <span class="nav-text">Products</span>
                                <span class="nav-badge">{{ \App\Models\Product::count() }}</span>
                            </a>
                        </div>
                        
                        <div class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories*') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <span class="nav-text">Categories</span>
                            </a>
                        </div>
                        
                        <div class="nav-item">
                            <a href="{{ route('stock-requests.index') }}" class="nav-link {{ request()->routeIs('stock-requests*') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <i class="fas fa-truck-loading"></i>
                                </div>
                                <span class="nav-text">Stock Requests</span>
                            </a>
                        </div>
                        @endif
                        
                        @if(auth()->user()->isAdmin())
                        <div class="nav-item">
                            <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <span class="nav-text">Customers</span>
                            </a>
                        </div>
                        
                        <div class="nav-item">
                            <a href="{{ route('proposals.index') }}" class="nav-link {{ request()->routeIs('proposals.*') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                                <span class="nav-text">Proposals</span>
                            </a>
                        </div>
                        
                        <div class="nav-item">
                            <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <span class="nav-text">Invoices</span>
                            </a>
                        </div>
                        @endif
                        
                        @if(auth()->user()->isAdmin())
                        <div class="nav-item">
                            <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers*') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <span class="nav-text">Suppliers</span>
                                <span class="badge bg-warning bg-opacity-10 text-warning ms-2 small">Admin</span>
                            </a>
                        </div>
                        @endif
                    </div>

                    @if(Auth::user()->isAdmin())
                    <div class="nav-section">
                        <div class="nav-section-title">Administration</div>
                        
                        <div class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                                <span class="nav-text">User Management</span>
                            </a>
                        </div>
                        
                        <div class="nav-item">
                            <a href="#" class="nav-link" onclick="showComingSoon('Reports')">
                                <div class="nav-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <span class="nav-text">Reports</span>
                                <span class="badge bg-info bg-opacity-10 text-info ms-2 small">Soon</span>
                            </a>
                        </div>
                        
                        <div class="nav-item">
                            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <span class="nav-text">Settings</span>
                            </a>
                        </div>
                    </div>
                    @endif
                </nav>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Top Navigation -->
                <div class="top-nav">
                    <div class="top-nav-left">
                        <h1 class="page-title">@yield('header', 'Dashboard')</h1>
                        
                        <!-- Animated Product Showcase -->

                    </div>
                    
                    <div class="top-nav-actions">

                        
                        <!-- Quick Actions - Only for Customers -->
                        @if(auth()->user()->isCustomer())
                        <div class="quick-actions">
                            <button class="quick-action-btn" onclick="showWishlist()" title="Wishlist">
                                <i class="fas fa-heart"></i>
                                <span class="action-badge" id="wishlistCount">0</span>
                            </button>
                            
                            <button class="quick-action-btn" onclick="showRecentlyViewed()" title="Recently Viewed">
                                <i class="fas fa-clock"></i>
                            </button>
                            
                            <button class="quick-action-btn" onclick="showDeals()" title="Special Deals">
                                <i class="fas fa-tag"></i>
                                <span class="action-badge deals-badge">HOT</span>
                            </button>
                        </div>
                        @endif
                        
                        <!-- Notifications -->
                        <div class="dropdown">
                            <button class="notification-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <div class="notification-badge"></div>
                            </button>
                            
                            <!-- Notifications Dropdown Menu -->
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" id="notificationsDropdown">
                                <li class="dropdown-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Notifications</h6>
                                        <span class="badge bg-primary" id="notificationCount">0</span>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <div id="notificationsList">
                                    <!-- Notifications will be loaded here dynamically -->
                                </div>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-center view-all-notifications" href="{{ route('notifications.index') }}">
                                        <i class="fas fa-eye"></i><span>View All Notifications</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Cart Icon -->
                        @if(auth()->user()->isCustomer())
                        <div class="cart-icon-container">
                            <button class="cart-btn" type="button" id="cart-toggle">
                                <i class="fas fa-shopping-cart"></i>
                                <div class="cart-badge" id="cart-count" style="display: none;">0</div>
                            </button>
                        </div>
                        @endif
                        
                        <!-- User Menu -->
                        <div class="dropdown">
                            <div class="user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->name, strpos(Auth::user()->name, ' ') !== false ? strpos(Auth::user()->name, ' ') + 1 : 1, 1)) }}
                                </div>
                                <div class="user-info d-none d-sm-block">
                                    <h6>{{ Auth::user()->name }}</h6>
                                    <p>
                                        @if(Auth::user()->isAdmin())
                                            Administrator
                                        @elseif(Auth::user()->isStaff())
                                            Staff Member
                                        @else
                                            Customer
                                        @endif
                                    </p>
                                </div>
                                <i class="fas fa-chevron-down ms-2"></i>
                            </div>
                            
                            <!-- User Dropdown Menu -->
                            <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                                <li class="dropdown-header">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->name, strpos(Auth::user()->name, ' ') !== false ? strpos(Auth::user()->name, ' ') + 1 : 1, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ Auth::user()->name }}</div>
                                            <div class="small text-muted">{{ Auth::user()->email }}</div>
                                        </div>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-question-circle me-2"></i>Help & Support</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="content-area">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Side Cart Panel -->
        @if(auth()->user()->isCustomer())
        <div class="side-cart-overlay" id="cart-overlay"></div>
        <div class="side-cart-panel" id="side-cart">
            <div class="cart-header">
                <h5 class="cart-title">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Shopping Cart
                </h5>
                <button class="cart-close-btn" id="cart-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="cart-content">
                <div id="cart-items-container">
                    <!-- Cart items will be loaded here -->
                </div>
                
                <div id="cart-empty" class="cart-empty-state" style="display: none;">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h6>Your cart is empty</h6>
                    <p>Add some products to get started!</p>
                </div>
            </div>
            
            <div class="cart-footer">
                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="cart-subtotal">$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (8%):</span>
                        <span id="cart-tax">$0.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span id="cart-total">$0.00</span>
                    </div>
                </div>
                
                <div class="cart-actions">
                    <button class="btn btn-primary w-100" id="buy-now-btn" disabled>
                        <i class="fas fa-credit-card me-2"></i>
                        Buy Now
                    </button>
                </div>
            </div>
        </div>
        @endif

        <script>
            // Laravel Route Helper for JavaScript
            window.routes = {
                'products.destroy': "{{ route('products.destroy', ':id') }}",
                'customers.destroy': "{{ route('customers.destroy', ':id') }}",
                'suppliers.destroy': "{{ route('suppliers.destroy', ':id') }}",
                'categories.destroy': "{{ route('categories.destroy', ':id') }}",
                'invoices.destroy': "{{ route('invoices.destroy', ':id') }}",
                'proposals.destroy': "{{ route('proposals.destroy', ':id') }}",
                'admin.users.destroy': "{{ route('admin.users.destroy', ':id') }}",
                'admin.users.index': "{{ route('admin.users.index') }}",
                'admin.users.show': "{{ route('admin.users.show', ':id') }}",
                'admin.users.create': "{{ route('admin.users.create') }}",
                'admin.users.store': "{{ route('admin.users.store') }}",
                'admin.users.edit': "{{ route('admin.users.edit', ':id') }}",
                'admin.users.update': "{{ route('admin.users.update', ':id') }}",
                
                // API routes
                'api.products.search': "{{ route('api.products.search') }}",
                'api.customers.search': "{{ route('api.customers.search') }}",
                'api.suppliers.search': "{{ route('api.suppliers.search') }}",
                
                // Show routes
                'products.show': "{{ route('products.show', ':id') }}",
                'customers.show': "{{ route('customers.show', ':id') }}",
                'suppliers.show': "{{ route('suppliers.show', ':id') }}",
                'categories.show': "{{ route('categories.show', ':id') }}",
                'invoices.show': "{{ route('invoices.show', ':id') }}",
                'proposals.show': "{{ route('proposals.show', ':id') }}",
            };

            // Helper function to generate routes
            function route(name, params = {}) {
                let url = window.routes[name];
                if (!url) {
                    console.error(`Route ${name} not found`);
                    return '#';
                }

                // Replace parameters
                Object.keys(params).forEach(key => {
                    url = url.replace(`:${key}`, params[key]);
                });

                return url;
            }

            // Cool Sidebar Scroll Animations
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('sidebar');
                const pullIndicator = document.getElementById('pullIndicator');
                const scrollProgress = document.getElementById('scrollProgress');
                
                let isScrolling = false;
                let scrollTimeout;
                let lastScrollTop = 0;
                let startY = 0;
                let currentY = 0;
                let isDragging = false;

                // Handle scroll events
                sidebar.addEventListener('scroll', function() {
                    const scrollTop = sidebar.scrollTop;
                    const scrollHeight = sidebar.scrollHeight;
                    const clientHeight = sidebar.clientHeight;
                    const scrollPercent = (scrollTop / (scrollHeight - clientHeight)) * 100;
                    
                    // Update scroll progress indicator
                    scrollProgress.style.background = `linear-gradient(to bottom, #3b82f6 ${scrollPercent}%, rgba(148, 163, 184, 0.1) ${scrollPercent}%)`;
                    
                    // Add scrolling class for glow effect
                    sidebar.classList.add('scrolling');
                    
                    // Clear previous timeout
                    clearTimeout(scrollTimeout);
                    
                    // Remove scrolling class after scroll ends
                    scrollTimeout = setTimeout(() => {
                        sidebar.classList.remove('scrolling');
                    }, 150);
                    
                    // Show pull indicator when at top and scrolling up
                    if (scrollTop === 0) {
                        pullIndicator.classList.add('visible');
                        setTimeout(() => {
                            pullIndicator.classList.remove('visible');
                        }, 2000);
                    } else {
                        pullIndicator.classList.remove('visible', 'active');
                    }
                    
                    lastScrollTop = scrollTop;
                });

                // Touch/Mouse events for pull-to-refresh effect
                sidebar.addEventListener('touchstart', handleStart, { passive: false });
                sidebar.addEventListener('mousedown', handleStart);
                
                sidebar.addEventListener('touchmove', handleMove, { passive: false });
                sidebar.addEventListener('mousemove', handleMove);
                
                sidebar.addEventListener('touchend', handleEnd);
                sidebar.addEventListener('mouseup', handleEnd);
                sidebar.addEventListener('mouseleave', handleEnd);

                function handleStart(e) {
                    if (sidebar.scrollTop === 0) {
                        isDragging = true;
                        startY = e.touches ? e.touches[0].clientY : e.clientY;
                        pullIndicator.classList.add('visible');
                    }
                }

                function handleMove(e) {
                    if (!isDragging || sidebar.scrollTop > 0) return;
                    
                    e.preventDefault();
                    currentY = e.touches ? e.touches[0].clientY : e.clientY;
                    const diff = currentY - startY;
                    
                    if (diff > 0 && diff < 100) {
                        const pullDistance = Math.min(diff, 60);
                        pullIndicator.style.transform = `translateX(-50%) translateY(${pullDistance - 50}px)`;
                        
                        if (pullDistance > 40) {
                            pullIndicator.classList.add('active');
                        } else {
                            pullIndicator.classList.remove('active');
                        }
                    }
                }

                function handleEnd(e) {
                    if (!isDragging) return;
                    
                    isDragging = false;
                    const diff = currentY - startY;
                    
                    if (diff > 40) {
                        // Trigger refresh animation
                        pullIndicator.classList.add('refreshing');
                        pullIndicator.querySelector('i').className = 'fas fa-sync-alt';
                        
                        setTimeout(() => {
                            pullIndicator.classList.remove('refreshing', 'active', 'visible');
                            pullIndicator.querySelector('i').className = 'fas fa-arrow-down';
                            pullIndicator.style.transform = 'translateX(-50%)';
                            
                            // Add a subtle notification
                            showRefreshNotification();
                        }, 1500);
                    } else {
                        pullIndicator.classList.remove('active', 'visible');
                        pullIndicator.style.transform = 'translateX(-50%)';
                    }
                }

                // Create ripple effect on scroll
                sidebar.addEventListener('wheel', function(e) {
                    createRipple(e.clientX - sidebar.offsetLeft, e.clientY - sidebar.offsetTop);
                });

                function createRipple(x, y) {
                    const ripple = document.createElement('div');
                    ripple.classList.add('sidebar-ripple');
                    ripple.style.left = x - 10 + 'px';
                    ripple.style.top = y - 10 + 'px';
                    ripple.style.width = '20px';
                    ripple.style.height = '20px';
                    
                    sidebar.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                }

                function showRefreshNotification() {
                    // Create a subtle notification
                    const notification = document.createElement('div');
                    notification.innerHTML = '<i class="fas fa-check-circle me-2"></i>Navigation refreshed';
                    notification.style.cssText = `
                        position: fixed;
                        top: 20px;
                        left: 50%;
                        transform: translateX(-50%);
                        background: linear-gradient(135deg, #10b981, #059669);
                        color: white;
                        padding: 12px 24px;
                        border-radius: 25px;
                        font-size: 14px;
                        font-weight: 600;
                        z-index: 9999;
                        box-shadow: 0 8px 32px rgba(16, 185, 129, 0.3);
                        opacity: 0;
                        transition: all 0.3s ease;
                    `;
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.style.opacity = '1';
                        notification.style.transform = 'translateX(-50%) translateY(0)';
                    }, 100);
                    
                    setTimeout(() => {
                        notification.style.opacity = '0';
                        notification.style.transform = 'translateX(-50%) translateY(-20px)';
                        setTimeout(() => notification.remove(), 300);
                    }, 2000);
                }
            });

            // Global search functionality
            document.getElementById('globalSearch')?.addEventListener('input', function(e) {
                // Implement global search logic
                console.log('Searching for:', e.target.value);
            });
            
            // Mobile sidebar toggle
            function toggleMobileSidebar() {
                document.querySelector('.sidebar').classList.toggle('mobile-open');
            }
            
            // Add mobile menu button for small screens
            if (window.innerWidth <= 768) {
                const mobileMenuBtn = document.createElement('button');
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                mobileMenuBtn.className = 'btn-modern btn-outline-modern d-md-none';
                mobileMenuBtn.onclick = toggleMobileSidebar;
                document.querySelector('.top-nav-actions').prepend(mobileMenuBtn);
            }
            
            // Close mobile sidebar when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.sidebar') && !e.target.closest('[onclick="toggleMobileSidebar()"]')) {
                    document.querySelector('.sidebar').classList.remove('mobile-open');
                }
            });
            
            // Add loading animation to cards
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.modern-card');
                cards.forEach((card, index) => {
                    card.classList.add('animate-fade-in');
                    if (index < 4) card.classList.add(`animate-delay-${index + 1}`);
                });

                // Load real notifications
                loadNotifications();
                updateNotificationCount();
                
                // Refresh notifications every 30 seconds
                setInterval(loadNotifications, 30000);

                // Notification functionality
                function loadNotifications() {
                    fetch('/api/notifications/recent')
                        .then(response => response.json())
                        .then(notifications => {
                            const notificationsList = document.getElementById('notificationsList');
                            const notificationCount = document.getElementById('notificationCount');
                            const notificationBadge = document.querySelector('.notification-badge');
                            
                            if (notifications.length === 0) {
                                notificationsList.innerHTML = `
                                    <li>
                                        <div class="dropdown-item text-center empty-notifications">
                                            <div class="empty-notification-icon">
                                                <i class="fas fa-bell-slash"></i>
                                            </div>
                                            <div class="empty-notification-text">
                                                <div class="empty-title">All Clear!</div>
                                                <div class="empty-subtitle">No new notifications at the moment.<br>We'll let you know when something important happens.</div>
                                            </div>
                                        </div>
                                    </li>
                                `;
                                notificationCount.textContent = '0';
                                notificationBadge.style.display = 'none';
                                return;
                            }
                            
                            let html = '';
                            let unreadCount = 0;
                            
                            notifications.forEach(notification => {
                                if (!notification.read) unreadCount++;
                                
                                let iconClass = 'fa-bell';
                                let bgClass = 'bg-info';
                                
                                if (notification.type === 'stock_update') {
                                    iconClass = 'fa-edit';
                                    bgClass = 'bg-warning';
                                } else if (notification.type === 'low_stock_alert') {
                                    iconClass = 'fa-exclamation-triangle';
                                    bgClass = 'bg-danger';
                                } else if (notification.type === 'stock_request_new') {
                                    iconClass = 'fa-box';
                                    bgClass = 'bg-primary';
                                } else if (notification.type.startsWith('stock_request')) {
                                    iconClass = 'fa-clipboard-check';
                                    bgClass = 'bg-success';
                                }
                                
                                html += `
                                    <li>
                                        <a class="dropdown-item notification-item ${notification.read ? 'read' : ''}" 
                                           href="${notification.url}" 
                                           data-notification-id="${notification.id}">
                                            <div class="d-flex">
                                                <div class="notification-icon ${bgClass}">
                                                    <i class="fas ${iconClass}"></i>
                                                </div>
                                                <div class="notification-content">
                                                    <div class="notification-title">${notification.title}</div>
                                                    <div class="notification-text">${notification.text}</div>
                                                    <div class="notification-time">${notification.time}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                `;
                            });
                            
                            notificationsList.innerHTML = html;
                            notificationCount.textContent = unreadCount;
                            
                            if (unreadCount > 0) {
                                notificationBadge.style.display = 'block';
                            } else {
                                notificationBadge.style.display = 'none';
                            }
                            
                            // Add click handlers for marking as read
                            document.querySelectorAll('.notification-item').forEach(item => {
                                item.addEventListener('click', function(e) {
                                    const notificationId = this.getAttribute('data-notification-id');
                                    if (notificationId) {
                                        markNotificationAsRead(notificationId);
                                    }
                                });
                            });
                        })
                        .catch(error => {
                            console.error('Error loading notifications:', error);
                        });
                }

                function markNotificationAsRead(notificationId) {
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
                            loadNotifications();
                        }
                    })
                    .catch(error => {
                        console.error('Error marking notification as read:', error);
                    });
                }

                function updateNotificationCount() {
                    fetch('/api/notifications/unread-count')
                        .then(response => response.json())
                        .then(data => {
                            const notificationCount = document.getElementById('notificationCount');
                            const notificationBadge = document.querySelector('.notification-badge');
                            
                            notificationCount.textContent = data.count;
                            
                            if (data.count > 0) {
                                notificationBadge.style.display = 'block';
                            } else {
                                notificationBadge.style.display = 'none';
                            }
                        })
                        .catch(error => {
                            console.error('Error updating notification count:', error);
                        });
                }

                // Welcome message time-based greeting
                function updateWelcomeMessage() {
                    const hour = new Date().getHours();
                    const welcomeIcon = document.querySelector('.welcome-icon');
                    const welcomeText = document.querySelector('.welcome-text');
                    
                    if (welcomeIcon && welcomeText) {
                        let greeting, icon;
                        
                        if (hour < 12) {
                            greeting = 'Good morning';
                            icon = 'fas fa-sun';
                        } else if (hour < 17) {
                            greeting = 'Good afternoon';
                            icon = 'fas fa-cloud-sun';
                        } else {
                            greeting = 'Good evening';
                            icon = 'fas fa-moon';
                        }
                        
                        welcomeIcon.className = icon + ' welcome-icon';
                        welcomeText.textContent = `${greeting}, {{ Auth::user()->name }}! 👋`;
                    }
                }
                
                // Update welcome message on page load
                updateWelcomeMessage();
            });

            // Coming soon functionality
            function showComingSoon(feature) {
                alert(`${feature} feature is coming soon! Stay tuned for updates.`);
            }
            
            // Side Cart Functionality
            function initializeSideCart() {
                const cartToggle = document.getElementById('cart-toggle');
                const cartClose = document.getElementById('cart-close');
                const sideCart = document.getElementById('side-cart');
                const cartOverlay = document.getElementById('cart-overlay');
                const buyNowBtn = document.getElementById('buy-now-btn');
                
                if (!cartToggle || !sideCart) return;
                
                // Toggle cart
                cartToggle.addEventListener('click', function() {
                    sideCart.classList.add('active');
                    cartOverlay.classList.add('active');
                    loadCartItems();
                });
                
                // Close cart
                cartClose.addEventListener('click', closeCart);
                cartOverlay.addEventListener('click', closeCart);
                
                // Buy now button
                buyNowBtn.addEventListener('click', function() {
                    // Show loading state
                    buyNowBtn.disabled = true;
                    buyNowBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                    
                    // Create Stripe checkout session
                    fetch('/stripe/create-checkout-session', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.url) {
                            // Redirect to Stripe checkout
                            window.location.href = data.url;
                        } else {
                            throw new Error(data.error || 'Failed to create checkout session');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to create checkout session. Please try again.');
                        buyNowBtn.disabled = false;
                        buyNowBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Buy Now';
                    });
                });
                
                function closeCart() {
                    sideCart.classList.remove('active');
                    cartOverlay.classList.remove('active');
                }
                
                // Load cart items
                function loadCartItems() {
                    fetch('/cart/items')
                        .then(response => response.json())
                        .then(data => {
                            updateCartDisplay(data);
                        })
                        .catch(error => {
                            console.error('Error loading cart:', error);
                        });
                }
                
                // Update cart display
                function updateCartDisplay(cartData) {
                    const cartItemsContainer = document.getElementById('cart-items-container');
                    const cartEmpty = document.getElementById('cart-empty');
                    const cartCount = document.getElementById('cart-count');
                    const cartSubtotal = document.getElementById('cart-subtotal');
                    const cartTax = document.getElementById('cart-tax');
                    const cartTotal = document.getElementById('cart-total');
                    
                    if (cartData.items && cartData.items.length > 0) {
                        cartItemsContainer.style.display = 'block';
                        cartEmpty.style.display = 'none';
                        buyNowBtn.disabled = false;
                        
                        // Update cart count
                        cartCount.textContent = cartData.item_count;
                        cartCount.style.display = 'block';
                        
                        // Update totals
                        cartSubtotal.textContent = `$${parseFloat(cartData.total).toFixed(2)}`;
                        const tax = parseFloat(cartData.total) * 0.08;
                        cartTax.textContent = `$${tax.toFixed(2)}`;
                        cartTotal.textContent = `$${(parseFloat(cartData.total) + tax).toFixed(2)}`;
                        
                        // Render cart items
                        renderCartItems(cartData.items);
                    } else {
                        cartItemsContainer.style.display = 'none';
                        cartEmpty.style.display = 'block';
                        buyNowBtn.disabled = true;
                        cartCount.style.display = 'none';
                        
                        cartSubtotal.textContent = '$0.00';
                        cartTax.textContent = '$0.00';
                        cartTotal.textContent = '$0.00';
                    }
                }
                
                // Render cart items
                function renderCartItems(items) {
                    const cartItemsContainer = document.getElementById('cart-items-container');
                    
                    const html = items.map(item => `
                        <div class="cart-item" data-item-id="${item.id}">
                            <div class="cart-item-image">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="cart-item-details">
                                <div class="cart-item-name">${item.product.name}</div>
                                <div class="cart-item-price">$${parseFloat(item.price).toFixed(2)}</div>
                                <div class="cart-item-controls">
                                    <div class="quantity-control">
                                        <button class="quantity-btn" onclick="updateCartQuantity(${item.id}, -1)">-</button>
                                        <input type="number" class="quantity-input" value="${item.quantity}" 
                                               min="1" max="${item.product.quantity}" 
                                               onchange="updateCartQuantity(${item.id}, 0, this.value)">
                                        <button class="quantity-btn" onclick="updateCartQuantity(${item.id}, 1)">+</button>
                                    </div>
                                    <button class="remove-item-btn" onclick="removeCartItem(${item.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    
                    cartItemsContainer.innerHTML = html;
                }
            }
            
            // Global cart functions
            function updateCartQuantity(itemId, change, newValue = null) {
                let quantity;
                const input = event.target.parentNode.querySelector('.quantity-input');
                
                if (newValue !== null) {
                    quantity = parseInt(newValue);
                } else {
                    quantity = parseInt(input.value) + change;
                }
                
                if (quantity < 1) quantity = 1;
                
                // Update input value immediately for better UX
                input.value = quantity;
                
                // Show loading state
                const cartItem = document.querySelector(`[data-item-id="${itemId}"]`);
                if (cartItem) cartItem.style.opacity = '0.7';
                
                fetch(`/cart/update/${itemId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ quantity: quantity })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Get fresh cart data from server
                        return fetch('/cart/items');
                    } else {
                        // Revert input value on error
                        input.value = quantity - change;
                        throw new Error('Failed to update quantity');
                    }
                })
                .then(response => response.json())
                .then(cartData => {
                    // Update cart display with fresh data
                    updateCartDisplay(cartData);
                    
                    // Update cart count
                    updateCartCount();
                    
                    // Show success notification
                    showSideCartNotification('Quantity updated successfully!', 'success');
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert input value on error
                    input.value = quantity - change;
                    showSideCartNotification('Failed to update quantity', 'error');
                })
                .finally(() => {
                    // Remove loading state
                    if (cartItem) cartItem.style.opacity = '1';
                });
            }
            
            function removeCartItem(itemId) {
                if (!confirm('Are you sure you want to remove this item?')) return;
                
                // Show loading state
                const cartItem = document.querySelector(`[data-item-id="${itemId}"]`);
                if (cartItem) cartItem.style.opacity = '0.7';
                
                fetch(`/cart/remove/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Get fresh cart data from server
                        return fetch('/cart/items');
                    } else {
                        throw new Error('Failed to remove item');
                    }
                })
                .then(response => response.json())
                .then(cartData => {
                    // Update cart display with fresh data
                    updateCartDisplay(cartData);
                    
                    // Update cart count
                    updateCartCount();
                    
                    // Show success notification
                    showSideCartNotification('Item removed from cart!', 'success');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showSideCartNotification('Failed to remove item', 'error');
                })
                .finally(() => {
                    // Remove loading state
                    if (cartItem) cartItem.style.opacity = '1';
                });
            }
            
            function updateCartCount() {
                fetch('/cart/count')
                    .then(response => response.json())
                    .then(data => {
                        const cartCount = document.getElementById('cart-count');
                        if (data.count > 0) {
                            cartCount.textContent = data.count;
                            cartCount.style.display = 'block';
                        } else {
                            cartCount.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error updating cart count:', error);
                    });
            }
            
            function showSideCartNotification(message, type = 'info') {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
                notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                notification.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                // Add to page
                document.body.appendChild(notification);
                
                // Auto remove after 3 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 3000);
            }
            
            function loadCartItems() {
                fetch('/cart/items')
                    .then(response => response.json())
                    .then(data => {
                        updateCartDisplay(data);
                    })
                    .catch(error => {
                        console.error('Error loading cart:', error);
                    });
            }
            
            function updateCartDisplay(cartData) {
                const cartItemsContainer = document.getElementById('cart-items-container');
                const cartEmpty = document.getElementById('cart-empty');
                const cartCount = document.getElementById('cart-count');
                const cartSubtotal = document.getElementById('cart-subtotal');
                const cartTax = document.getElementById('cart-tax');
                const cartTotal = document.getElementById('cart-total');
                const buyNowBtn = document.getElementById('buy-now-btn');
                
                if (cartData.items && cartData.items.length > 0) {
                    cartItemsContainer.style.display = 'block';
                    cartEmpty.style.display = 'none';
                    buyNowBtn.disabled = false;
                    
                    cartCount.textContent = cartData.item_count;
                    cartCount.style.display = 'block';
                    
                    cartSubtotal.textContent = `$${parseFloat(cartData.total).toFixed(2)}`;
                    const tax = parseFloat(cartData.total) * 0.08;
                    cartTax.textContent = `$${tax.toFixed(2)}`;
                    cartTotal.textContent = `$${(parseFloat(cartData.total) + tax).toFixed(2)}`;
                    
                    renderCartItems(cartData.items);
                } else {
                    cartItemsContainer.style.display = 'none';
                    cartEmpty.style.display = 'block';
                    buyNowBtn.disabled = true;
                    cartCount.style.display = 'none';
                    
                    cartSubtotal.textContent = '$0.00';
                    cartTax.textContent = '$0.00';
                    cartTotal.textContent = '$0.00';
                }
            }
            
            function renderCartItems(items) {
                const cartItemsContainer = document.getElementById('cart-items-container');
                
                const html = items.map(item => `
                    <div class="cart-item" data-item-id="${item.id}">
                        <div class="cart-item-image">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="cart-item-details">
                            <div class="cart-item-name">${item.product.name}</div>
                            <div class="cart-item-price">$${parseFloat(item.price).toFixed(2)}</div>
                            <div class="cart-item-controls">
                                <div class="quantity-control">
                                    <button class="quantity-btn" onclick="updateCartQuantity(${item.id}, -1)">-</button>
                                    <input type="number" class="quantity-input" value="${item.quantity}" 
                                           min="1" max="${item.product.quantity}" 
                                           onchange="updateCartQuantity(${item.id}, 0, this.value)">
                                    <button class="quantity-btn" onclick="updateCartQuantity(${item.id}, 1)">+</button>
                                </div>
                                <button class="remove-item-btn" onclick="removeCartItem(${item.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
                
                cartItemsContainer.innerHTML = html;
            }
            
            // Product Showcase Animation
            function initializeProductShowcase() {
                const showcase = document.getElementById('productShowcase');
                const indicators = document.querySelectorAll('.indicator');
                
                if (!showcase) return;
                
                // Fetch real product data from the backend
                fetch('/api/featured-products')
                    .then(response => response.json())
                    .then(data => {
                        if (data.products && data.products.length > 0) {
                            startShowcase(data.products);
                        } else {
                            // Fallback to sample data if no products found
                            const fallbackProducts = [
                                {
                                    name: 'Gaming Mechanical Keyboard',
                                    price: '$149.99',
                                    image: 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=100&h=100&fit=crop&crop=center',
                                    category: 'Electronics'
                                },
                                {
                                    name: 'Wireless Bluetooth Headphones',
                                    price: '$89.99',
                                    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&h=100&fit=crop&crop=center',
                                    category: 'Accessories'
                                },
                                {
                                    name: 'Premium Coffee Maker',
                                    price: '$199.99',
                                    image: 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=100&h=100&fit=crop&crop=center',
                                    category: 'Home & Kitchen'
                                },
                                {
                                    name: 'Smart Fitness Watch',
                                    price: '$299.99',
                                    image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=100&h=100&fit=crop&crop=center',
                                    category: 'Electronics'
                                },
                                {
                                    name: 'Organic Honey Jar',
                                    price: '$24.99',
                                    image: 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=100&h=100&fit=crop&crop=center',
                                    category: 'Food & Beverages'
                                }
                            ];
                            startShowcase(fallbackProducts);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching featured products:', error);
                        // Use fallback data on error
                        const fallbackProducts = [
                            {
                                name: 'Gaming Mechanical Keyboard',
                                price: '$149.99',
                                image: 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=100&h=100&fit=crop&crop=center',
                                category: 'Electronics'
                            },
                            {
                                name: 'Wireless Bluetooth Headphones',
                                price: '$89.99',
                                image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&h=100&fit=crop&crop=center',
                                category: 'Accessories'
                            },
                            {
                                name: 'Premium Coffee Maker',
                                price: '$199.99',
                                image: 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=100&h=100&fit=crop&crop=center',
                                category: 'Home & Kitchen'
                            }
                        ];
                        startShowcase(fallbackProducts);
                    });
                
                function startShowcase(products) {
                
                let currentSlide = 0;
                const totalSlides = Math.min(products.length, 3);
                
                // Create showcase items
                function createShowcaseItems() {
                    showcase.innerHTML = '';
                    
                    for (let i = 0; i < totalSlides; i++) {
                        const product = products[i];
                        const item = document.createElement('div');
                        item.className = `showcase-item ${i === 0 ? 'active' : ''}`;
                        
                        // Create image element with fallback
                        const imageHtml = product.image ? 
                            `<img src="${product.image}" alt="${product.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` :
                            '';
                        
                        item.innerHTML = `
                            <div class="showcase-item-image">
                                ${imageHtml}
                                <div class="placeholder-icon" style="display: ${product.image ? 'none' : 'flex'}">
                                    <i class="fas fa-image"></i>
                                </div>
                            </div>
                            <div class="showcase-item-content">
                                <div class="showcase-item-title">${product.name}</div>
                                <div class="showcase-item-price">${product.price}</div>
                                <div class="showcase-item-category">${product.category}</div>
                            </div>
                        `;
                        showcase.appendChild(item);
                    }
                }
                
                // Update indicators
                function updateIndicators() {
                    indicators.forEach((indicator, index) => {
                        indicator.classList.toggle('active', index === currentSlide);
                    });
                }
                
                // Animate to next slide
                function nextSlide() {
                    const items = showcase.querySelectorAll('.showcase-item');
                    
                    // Remove active class from current item
                    items[currentSlide].classList.remove('active');
                    
                    // Move to next slide
                    currentSlide = (currentSlide + 1) % totalSlides;
                    
                    // Add active class to new item
                    items[currentSlide].classList.add('active');
                    
                    // Update indicators
                    updateIndicators();
                    
                    // Animate slider
                    showcase.style.transform = `translateX(-${currentSlide * 100}%)`;
                }
                
                // Handle indicator clicks
                indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', () => {
                        const items = showcase.querySelectorAll('.showcase-item');
                        
                        // Remove active class from current item
                        items[currentSlide].classList.remove('active');
                        
                        // Update current slide
                        currentSlide = index;
                        
                        // Add active class to new item
                        items[currentSlide].classList.add('active');
                        
                        // Update indicators
                        updateIndicators();
                        
                        // Animate slider
                        showcase.style.transform = `translateX(-${currentSlide * 100}%)`;
                    });
                });
                
                // Initialize showcase
                createShowcaseItems();
                updateIndicators();
                
                // Auto-rotate every 4 seconds
                setInterval(nextSlide, 4000);
                
                // Pause on hover
                showcase.addEventListener('mouseenter', () => {
                    showcase.style.animationPlayState = 'paused';
                });
                
                showcase.addEventListener('mouseleave', () => {
                    showcase.style.animationPlayState = 'running';
                });
            }
            
            // Quick Action Functions
            function showWishlist() {
                // Create a modern toast notification
                showToast('💝 Wishlist feature coming soon!', 'info');
            }
            
            function showRecentlyViewed() {
                showToast('🕒 Recently viewed items will be available soon!', 'info');
            }
            
            function showDeals() {
                showToast('🔥 Special deals and offers coming soon!', 'success');
            }
            
            function showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = `toast-notification toast-${type}`;
                toast.innerHTML = `
                    <div class="toast-content">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                        <span>${message}</span>
                    </div>
                `;
                
                document.body.appendChild(toast);
                
                // Animate in
                setTimeout(() => toast.classList.add('show'), 100);
                
                // Auto remove
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
            
            // Update stats
            function updateStats() {
                // Simulate dynamic stats
                const totalProducts = Math.floor(Math.random() * 50) + 100;
                const trendingItems = Math.floor(Math.random() * 10) + 5;
                
                document.getElementById('totalProducts').textContent = totalProducts;
                document.getElementById('trendingItems').textContent = trendingItems;
                
                // Update wishlist count
                const wishlistCount = Math.floor(Math.random() * 5);
                const wishlistBadge = document.getElementById('wishlistCount');
                if (wishlistCount > 0) {
                    wishlistBadge.textContent = wishlistCount;
                    wishlistBadge.style.display = 'block';
                } else {
                    wishlistBadge.style.display = 'none';
                }
            }
            
            // Initialize side cart when DOM is loaded
            document.addEventListener('DOMContentLoaded', function() {
                initializeSideCart();
                initializeProductShowcase();
                updateStats();
                
                // Update stats every 30 seconds
                setInterval(updateStats, 30000);
            });
        </script>
    </body>
</html>
