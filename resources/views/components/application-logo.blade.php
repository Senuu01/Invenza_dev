<div class="d-flex align-items-center">
    <!-- Invenza Logo Icon -->
    <div class="invenza-logo-icon me-3">
        <div class="logo-cube">
            <div class="cube-face front"></div>
            <div class="cube-face back"></div>
            <div class="cube-face right"></div>
            <div class="cube-face left"></div>
            <div class="cube-face top"></div>
            <div class="cube-face bottom"></div>
        </div>
    </div>
    
    <!-- Invenza Text -->
    <div class="invenza-brand">
        <div class="invenza-title">Invenza</div>
        @auth
            <div class="invenza-subtitle">
                @if(auth()->user()->isAdmin())
                    ADMIN PANEL
                @elseif(auth()->user()->isStaff())
                    STAFF PANEL
                @else
                    CUSTOMER PORTAL
                @endif
            </div>
        @endauth
    </div>
</div>

<style>
.invenza-logo-icon {
    width: 40px;
    height: 40px;
    position: relative;
}

.logo-cube {
    width: 100%;
    height: 100%;
    position: relative;
    transform-style: preserve-3d;
    animation: cubeRotate 8s infinite linear;
}

.cube-face {
    position: absolute;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

.cube-face.front {
    transform: translateZ(20px);
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
}

.cube-face.back {
    transform: translateZ(-20px) rotateY(180deg);
    background: linear-gradient(135deg, #1d4ed8, #3b82f6);
}

.cube-face.right {
    transform: translateX(20px) rotateY(90deg);
    background: linear-gradient(135deg, #2563eb, #1e40af);
}

.cube-face.left {
    transform: translateX(-20px) rotateY(-90deg);
    background: linear-gradient(135deg, #1e40af, #2563eb);
}

.cube-face.top {
    transform: translateY(-20px) rotateX(90deg);
    background: linear-gradient(135deg, #60a5fa, #3b82f6);
}

.cube-face.bottom {
    transform: translateY(20px) rotateX(-90deg);
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
}

@keyframes cubeRotate {
    0% { transform: rotateX(0deg) rotateY(0deg); }
    100% { transform: rotateX(360deg) rotateY(360deg); }
}

.invenza-brand {
    display: flex;
    flex-direction: column;
}

.invenza-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.5px;
    line-height: 1;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.invenza-subtitle {
    font-size: 0.65rem;
    font-weight: 600;
    color: #94a3b8;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-top: 2px;
    line-height: 1;
}

/* Dark theme adjustments */
.dark .invenza-title {
    color: #ffffff;
}

.dark .invenza-subtitle {
    color: #94a3b8;
}

/* Light theme adjustments */
.light .invenza-title {
    color: #1e293b;
}

.light .invenza-subtitle {
    color: #64748b;
}
</style>
