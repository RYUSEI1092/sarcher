/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 import './bootstrap';
 import { createApp } from 'vue';
 
 /**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
import LaravelTop from './components/LaravelTop.vue';
import RentyHeader from './components/RentyHeader.vue';
import RentyFooter from './components/RentyFooter.vue';
import Register from './pages/Register.vue';
import Main from './pages/Main.vue';
import Bookshelf from './pages/Bookshelf.vue';
import Books from './pages/Books.vue';
import BooksDetail from './pages/BooksDetail.vue';
import Login from './pages/Login.vue';
import Error from './pages/Error.vue';
app.component('example-component', ExampleComponent);
app.component('laravel-top', LaravelTop);
app.component('renty-header', RentyHeader);
app.component('renty-footer', RentyFooter);
app.component('pages-register', Register);
app.component('pages-main', Main);
app.component('pages-bookshelf', Bookshelf);
app.component('pages-books', Books);
app.component('pages-booksdetail',BooksDetail);
app.component('pages-login', Login);
app.component('pages-error', Error);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
 
 // Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
 //     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
 // });
 
 /**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */
 
app.mount('#app');
 