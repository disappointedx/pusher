<template>
    <div class="container">
        <div class="form">
            <div class="row">
                <div class="col inputs_room">
                    <input type="text" v-model="title" class="form-control input_room"
                        placeholder="Введите PIN комнаты">
                </div>
                <div class="col">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <label class="label_test my-1" v-if="test_id === -1">Выберите тест</label>
                            <label class="label_test" v-else>
                                {{ name }}
                            </label>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <div v-if="tests">
                                <div class="tests" v-for="(test, i) in tests" :key="i">
                                    <li>
                                        <button class="dropdown-item" @click="SetTest(tests[i].id)">
                                            <label class="label_test">{{ test.title }}</label>
                                        </button>
                                    </li>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="button_create">
                <button class="my-2 btn btn-primary" @click.prevent="store">Создать комнату</button>
            </div>
        </div>
        <div class="text-center fs-3 my-2">Текущие комнаты</div>
        <div class="row">
            <div class="col text-center fs-4 ">PIN комнаты</div>
            <div class="col text-center fs-4">Статус игры</div>
            <div class="col"></div>
        </div>
        <div v-for="room in rooms" :key="room.pin" class="room_item">
            <div class="row my-2">
                <div class="col room_pin my-3 fs-5 right">{{ room.pin }}</div>
                <div class="col text-center my-3 right" v-if="room.status_game === 3">Игра закрыта</div>
                <div class="col text-center my-3 right" v-else-if="room.status_game">Идет игра</div>
                <div class="col text-center my-3 right" v-else>Игра в ожидании</div>
                <div class="col my-3 text-center">
                    <button class="btn btn-primary enter_button" @click="GoToRoute(room.pin)">Войти</button>
                </div>
            </div>
        </div>
    </div>
    <br>
</template>

<script>
export default {
    props: ['user_id', 'rooms', 'tests'],
    mounted() {},
    data() {
        return {
            title: '',
            test_id: -1,
            name: ''
        };
    },
    created() {
        const self = this;
        window.Echo.channel('store_room').listen('.store_room', response => {
            self.rooms.unshift(response.room);
            self.$forceUpdate();
        });
        window.Echo.channel('update_room').listen('.update_room', response => {
            const roomId = response.room.id;
            const index = self.rooms.findIndex(room => room.id === roomId);
            if (index !== -1) {
                self.rooms.splice(index, 1, response.room);
                self.$forceUpdate();
            }
        });
    },
    methods: {
        store() {
            if (this.test_id === -1) {
                this.$toast.error('Вы не выбрали тест');
            } else {
                axios
                    .post('http://127.0.0.1:8000/room', {
                        _method: 'POST',
                        pin: this.title,
                        room_owner: this.user_id,
                        status_game: 0,
                        question_current: 0,
                        test_id: this.test_id
                    })
                    .then(response => {
                        this.$toast.success('Комната создана');
                        this.title = '';
                        this.rooms.unshift(response.data);
                    })
                    .catch(error => {
                        this.$toast.error('Комната не создана');
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        },
        SetTest(count) {
            this.test_id = count;
            for (let i = 0; i < this.tests.length; i++) {
                if (this.tests[i].id === this.test_id) {
                    this.name = this.tests[i].title;
                }
            }
        },
        GoToRoute(item) {
            const address = `http://127.0.0.1:8000/room/${item}`;
            window.location.href = address;
        }
    }
};
</script>

<style scoped>
.container {
    background: rgb(255, 255, 255);
    padding: 20px;
}

.input_room {
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 4px;
}

.button_create_room {
    font-size: 17px;
    background-color: #007bff;
    border-color: #007bff;
}

.button_create_room:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.label_test {
    margin-left: 5px;
}

.room_label {
    font-size: 18px;
    margin-top: 20px;
}

.right {
    border-right: 1px solid black;
}

.room_item {
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 4px;
    margin-top: 10px;
    background: white;
}

.room_pin {}

.enter_button {
    font-size: 14px;
    background-color: #007bff;
    border-color: #007bff;
}

.enter_button:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

@media (max-width: 768px) {

    /* Стили для устройств с шириной экрана до 768px (планшеты и подобные) */
    .inputs_room {
        display: flex;
        justify-content: center;
        align-items: center;
    }
}

@media (max-width: 420px) {

    /* Стили для устройств с шириной экрана до 420px (смартфоны и подобные) */
    .input_room {
        width: 250px;
    }

    .dropdown {
        text-align: center;
        margin-top: 10px;
    }

    .button_create {
        text-align: center;
        font-size: 16px;
    }

    .dropdown-menu {
        text-align: center;
    }

    ul.dropdown-menu.show {
        margin-right: 200px;
    }
}
</style>