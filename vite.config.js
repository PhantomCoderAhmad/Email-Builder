import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { fileURLToPath } from 'url';
import path from 'path';
import fs from 'fs';

// ✅ Correct way to get __dirname equivalent in ES modules
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

console.log("Directory name is:", __dirname);

// Fix: Ensure we correctly reference modules_statuses.json in the root of the project
const moduleStatusesPath = path.resolve(__dirname, 'modules_statuses.json'); // ✅ Use correct relative path
const modulesDir = path.resolve(__dirname, 'Modules'); // ✅ Modules is inside project root

console.log("Looking for module statuses at:", moduleStatusesPath);
console.log("Looking for Modules directory at:", modulesDir);

// Function to collect module assets paths
async function collectModuleAssetsPaths(paths, modulesPath) {
    try {
        // Read modules_statuses.json
        const moduleStatusesContent = await fs.promises.readFile(moduleStatusesPath, 'utf-8');
        const moduleStatuses = JSON.parse(moduleStatusesContent);
        
        // Read modules directory
        const moduleDirectories = await fs.promises.readdir(modulesDir);

        for (const moduleDir of moduleDirectories) {
            if (moduleDir === '.DS_Store') continue;

            if (moduleStatuses[moduleDir] === true) {
                const viteConfigPath = path.join(modulesDir, moduleDir, 'vite.config.js');

                try {
                    const stat = await fs.promises.stat(viteConfigPath);
                    if (stat.isFile()) {
                        const moduleConfig = await import(`file://${viteConfigPath}`); // ✅ Correct way to import dynamically
                        if (moduleConfig.paths && Array.isArray(moduleConfig.paths)) {
                            paths.push(...moduleConfig.paths);
                        }
                    }
                } catch (error) {
                    console.warn(`Skipping module ${moduleDir}, vite.config.js not found or invalid.`);
                }
            }
        }
    } catch (error) {
        console.error(`Error reading module statuses or configurations: ${error}`);
    }

    return paths;
}

export default async () => {
    const paths = [
        'Modules/Templates/resources/assets/css/app.css',
        'Modules/Templates/resources/assets/js/app.jsx',
        'Modules/Templates/resources/assets/js/app.js',
        'Modules/Templates/resources/assets/css/payments.css',
        'Modules/Templates/resources/assets/js/payments.js',
    ];

    const allPaths = await collectModuleAssetsPaths(paths, 'Modules');

    return defineConfig({
        plugins: [
            laravel({
                input: allPaths,
                refresh: true,
            }),
        ],
        css: {
            postcss: {
                plugins: [
                    (await import('tailwindcss')).default(path.resolve(__dirname, 'tailwind.config.js')),
                    (await import('autoprefixer')).default(),
                ],
            },
        },
        resolve: {
            alias: {
                '@modules': '/Modules',
                '@Templates': '/Modules/Templates/resources/assets/js', // Alias for templates module's JS folder
                '@pages': '/Modules/Templates/resources/assets/js/Pages', // Alias for templates module's Pages folder
                '@emotion/react': path.resolve(__dirname, 'node_modules/@emotion/react'),
                '@inertiajs/inertia-react':  path.resolve(__dirname,'/node_modules/@inertiajs/inertia-react'),
                react: path.resolve('./node_modules/react'),
            },
        },
    });
};
