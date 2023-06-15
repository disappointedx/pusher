/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import Toaster from '@meforma/vue-toaster';
import.meta.glob([
    '../images/**',
    '../fonts/**',
]);

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
import RoomComponent from './components/RoomComponent.vue';
import TestComponent from './components/TestComponent.vue';
import QuestionComponent from './components/QuestionComponent.vue';
import WelcomeComponent from './components/welcomeComponent.vue';
import GameComponent from './components/GameComponent.vue';
app.component('example-component', ExampleComponent);
app.component('room-component', RoomComponent);
app.component('test-component', TestComponent);
app.component('question-component', QuestionComponent);
app.component('welcome-component', WelcomeComponent);
app.component('game-component', GameComponent);
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

app.use(Toaster).mount('#app');
