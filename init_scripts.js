// Initialization scripts for dynamically loaded content

function initStudentPage() {
    // Removed toggle event listener code to avoid duplicate event listeners
    // The toggle and AJAX form submission logic is handled in script.js
}

function initViolationPage() {
    // Re-initialize modal functionality from script_v2.js
    // Since script_v2.js uses DOMContentLoaded, we need to manually call the modal setup functions here
    // For simplicity, reload the script_v2.js by removing and adding it again

    const oldScript = document.querySelector('script[src="script_v2.js"]');
    if (oldScript) {
        oldScript.remove();
    }
    const newScript = document.createElement('script');
    newScript.src = 'script_v2.js';
    document.body.appendChild(newScript);
}

function initProfilePage() {
    // No special JS needed for profile page currently
}
