import './bootstrap';
import { createApp } from 'vue';
import Toaster from '@meforma/vue-toaster';
import.meta.glob([
    '../images/**',
    '../fonts/**',
]);

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

app.use(Toaster).mount('#app');
