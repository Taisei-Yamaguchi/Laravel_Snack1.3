//Vue を使うためには、このファイルを設定する必要がある。
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; //new 2023.6.4

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(), //new2023.6.4
    ],
    server:{       //new2023.6.4
        host: true,//new
    }, //new
});


module.exports = {
    build: {
      rollupOptions: {
        input: {
          main: './path/to/main.js', // メインのエントリーポイントへのパスを指定
        },
      },
    },
};