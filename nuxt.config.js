export default {
    ssr: false,
    srcDir: 'resources/nuxt/',
    head: {
        title: 'myHub',
        meta: [
            { charset: 'utf-8' },
            { name:'viewport', content:'width-device-width, initial-scale=10' },
            { hid: 'description', name:'description', content: '' }
        ]
    },
    css: [
        '@fortawesome/fontawesome-svg-core/styles.css',
        '/css/app.css'
    ],
    plugins: ['~/plugins/fontawesome.js'],
    components: true,
    buildModules: ['@nuxtjs/fontawesome', '@nuxt/typescript-build'],
    modules: [],
    build: {
        publicPath: process.env.NODE_ENV === 'production' ? 'assets/' : null,
        extractCSS: true,
    },
    generate: {
        dir: 'nuxt-public',
    },
    server: {
        port: 3000,
        host: '127.0.0.1'
    },
    watchers: {
        webpack: {
            aggregateTimeout: 300,
            poll: 500,
        }
    },
    fontawesome: {
        icons:{
         solid: true,
         brands: true
        }
    }
}