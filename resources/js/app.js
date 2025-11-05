import './bootstrap';

// Dark mode toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const toggleDarkMode = () => {
        // Toggle dark class on the html element
        document.documentElement.classList.toggle('dark');
        
        // Store the preference in localStorage
        const isDarkMode = document.documentElement.classList.contains('dark');
        localStorage.setItem('darkMode', isDarkMode);
        
        // Trigger a custom event to notify any other parts of the application
        window.dispatchEvent(new CustomEvent('darkModeToggled', { detail: { isDarkMode } }));
    };

    // Update the icon based on current mode
    const updateDarkModeIcon = (button) => {
        if (!button) return;
        
        const sunIcon = button.querySelector('#sun-icon');
        const moonIcon = button.querySelector('#moon-icon');
        
        if (document.documentElement.classList.contains('dark')) {
            sunIcon?.classList.add('hidden');
            moonIcon?.classList.remove('hidden');
        } else {
            sunIcon?.classList.remove('hidden');
            moonIcon?.classList.add('hidden');
        }
    };

    // Initialize dark mode based on preference
    const initDarkMode = () => {
        // Check for saved preference in localStorage
        const savedMode = localStorage.getItem('darkMode');
        if (savedMode !== null) {
            const shouldEnableDark = JSON.parse(savedMode);
            if (shouldEnableDark && !document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.add('dark');
            } else if (!shouldEnableDark && document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
            }
        } else {
            // Check for system preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
            }
        }
        
        // Debug: Log the current state
        console.log('Dark mode init complete. Current state:', document.documentElement.classList.contains('dark'));
        
        // Update all toggle button icons after initialization
        document.querySelectorAll('[data-toggle-dark-mode]').forEach(button => {
            updateDarkModeIcon(button);
        });
    };

    // Initialize dark mode
    initDarkMode();
    
    // Add event listener to any existing dark mode toggle buttons
    const toggleButtons = document.querySelectorAll('[data-toggle-dark-mode]');
    toggleButtons.forEach(button => {
        button.addEventListener('click', toggleDarkMode);
        
        // Update icon when page loads
        updateDarkModeIcon(button);
    });

    // Listen for changes in system preference
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
        if (localStorage.getItem('darkMode') === null) {
            if (event.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        // Update all toggle icons
        document.querySelectorAll('[data-toggle-dark-mode]').forEach(button => {
            updateDarkModeIcon(button);
        });
    });

    // Update the icon when toggling
    document.addEventListener('click', (e) => {
        if (e.target.closest('[data-toggle-dark-mode]')) {
            setTimeout(() => {
                const toggleButton = e.target.closest('[data-toggle-dark-mode]');
                updateDarkModeIcon(toggleButton);
                
                // Debug: Log the state after toggle
                console.log('Dark mode toggled. Current state:', document.documentElement.classList.contains('dark'));
            }, 10);
        }
    });
});

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
