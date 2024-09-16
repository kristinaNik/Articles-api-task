import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/js/main.js', // Update the entry module here
        ]),
    ],
    build: {
        outDir: 'public/build',
        manifest: true,
        rollupOptions: {
            input: {
                app: './resources/js/app.jsx'
            }
        }
    },
});

// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';
// import react from '@vitejs/plugin-react'; // Import the React plugin
//
// export default defineConfig({
//     plugins: [
//         laravel({
//             input: [
//                 'resources/css/app.css',
//                 'resources/js/app.jsx', // Ensure this is the entry module for React
//             ],
//             refresh: true,
//         }),
//         react(), // Add the React plugin
//     ],
//     build: {
//         outDir: 'public/build',
//         manifest: true,
//         rollupOptions: {
//             input: {
//                 app: './resources/js/app.jsx', // Ensure this path is correct
//             }
//         }
//     }
// });