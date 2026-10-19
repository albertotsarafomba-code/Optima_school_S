import { createIcons, icons } from 'lucide';

function initLucideIcons() {
    createIcons({ icons });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLucideIcons, { once: true });
} else {
    initLucideIcons();
}
