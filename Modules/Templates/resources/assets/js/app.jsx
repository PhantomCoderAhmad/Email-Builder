import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';

// Ensure we're loading pages from both the root and the modules
const pages = import.meta.glob([
   './Pages/**/*.jsx',
], { eager: true });

console.log("✅ Loaded Pages:", Object.keys(pages)); // For debugging

createInertiaApp({
    resolve: (name) => {
        const normalizedName = name.replace(/::/g, '/');

        // Try to find the matching page in the pages object
        const match = Object.keys(pages).find((key) =>
            key.endsWith(`${normalizedName}.jsx`)
        );

        if (!match) {
            throw new Error(`❌ Page not found: ${normalizedName}.jsx`);
        }

        const page = pages[match]; // No need for 'await'
        return page.default; // Return the default export directly
    },
    setup({ el, App, props }) {
        console.log("✅ Props received:", props); // For debugging
        createRoot(el).render(<App {...props} />);
    },
});

